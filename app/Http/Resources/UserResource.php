<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'cedula' => $this->employees->cedula,
            'first_name' => $this->employees->first_name,
            'last_name' => $this->employees->last_name,
            'management' => $this->employees->management,
            'state' => $this->employees->state,
            'type_employee' => $this->employees->type_employee,
            'position' => $this->employees->position,
            'phone' => $this->employees->phone,
            'time_token' => $this->timeToken->description,
            'expiry_month' => $this->expiryMonth->description
        ];
    }
}
