<?php

namespace App\Http\Controllers\API;

use App\Models\Appointment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentNotification;
use App\Http\Resources\AppointmentResource;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Requests\FilterAppointmentsRequest;

class AppointmentController extends Controller
{
    public function index(FilterAppointmentsRequest $request)
    {
        $user = auth()->user();
        $query = $user->appointments(); // Or Appointments::where('user_id', $user->id);

        if ($request->filled('date_from')) {
            $query->where('appointment_datetime', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('appointment_datetime', '<=', $request->date_to);
        }
        if ($request->filled('egn')) {
            $query->where('egn', $request->egn);
        }

        $appointments = $query->orderBy('appointment_datetime', 'asc')->paginate(10);

        return AppointmentResource::collection($appointments);
    }

    public function store(StoreAppointmentRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();

        $appointment = Appointment::create($validated);

        if ($validated['notification_method'] === 'email') {
            Mail::to($request->input('email', $appointment->email))
                ->send(new AppointmentNotification($appointment));
        }

        if ($validated['notification_method'] === 'sms') {
            // TODO: Send SMS notification
        }

        // TODO: Return message with the selected notification method
        return new AppointmentResource($appointment);
    }

    public function show(Appointment $appointment)
    {
        if (!auth()->user()->can('view', $appointment)) {
            abort(403);
        }

        return new AppointmentResource($appointment);
    }

    public function showRelatedAppointments(Appointment $appointment)
    {
        $user = auth()->user(); // Or Appointment::where('user_id', $user->id);

        $relatedAppointments = $user->appointments()->where('egn', $appointment->egn)
            ->where('user_id', Auth::id())
            ->where('appointment_datetime', '>', now())
            ->where('id', '!=', $appointment->id)
            ->orderBy('appointment_datetime')
            ->limit(5)
            ->get();

        return AppointmentResource::collection($relatedAppointments);
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        if (!auth()->user()->can('update', $appointment)) {
            abort(403);
        }

        $appointment->update($request->validated());

        return new AppointmentResource($appointment);
    }

    public function destroy(Appointment $appointment)
    {
        if (!auth()->user()->can('delete', $appointment)) {
            abort(403);
        }

        $appointment->delete();

        return response()->json([
            'message' => 'Appointment deleted successfully',
        ]);
    }
}
