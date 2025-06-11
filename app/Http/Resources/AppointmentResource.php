<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'appointment_datetime' => $this->appointment_datetime->toDateTimeString(),
            'client_name' => $this->client_name,
            'egn' => $this->egn,
            'description' => $this->description,
            'notification_method' => $this->notification_method,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
