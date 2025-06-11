<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'appointment_datetime' => 'required|date|after:now',
            'client_name' => 'required|string|max:255',
            'egn' => 'required|string|size:10',
            'description' => 'nullable|string|max:1000',
            'notification_method' => 'required|in:sms,email',
        ];
    }
}
