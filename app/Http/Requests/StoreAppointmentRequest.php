<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
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
            'email' => 'required_if:notification_method,email|nullable|email',
            'phone' => 'required_if:notification_method,sms|nullable|string',
        ];
    }
}
