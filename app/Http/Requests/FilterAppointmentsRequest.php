<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterAppointmentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'egn' => 'nullable|string|size:10',
        ];
    }
}
