<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeesResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'cedula' => $this->cedula,
            'id_management' => $this->id_management,
            'state' => $this->state,
            'type_employee' => $this->type_employee,
            'position' => $this->position
        ];
    }
}
