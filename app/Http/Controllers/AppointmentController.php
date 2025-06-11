<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\FilterAppointmentsRequest;

class AppointmentController extends Controller
{
    public function index()
    {
        return view('appointments.index');
    }

    public function create()
    {
        return view('appointments.create');
    }

    public function show(Appointment $appointment)
    {
        if (!auth()->user()->can('view', $appointment)) {
            abort(403);
        }

        return view('appointments.show');
    }

    public function edit(Appointment $appointment)
    {
        if (!auth()->user()->can('view', $appointment)) {
            abort(403);
        }

        return view('appointments.edit', compact('appointment'));
    }
}
