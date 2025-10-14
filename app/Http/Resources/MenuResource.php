<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'food_category' => $this->food_category,
            'name_ingredient' => $this->name_ingredient,
            'date_menu' => $this->date_menu
        ];
    }
}
