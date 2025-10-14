<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class numberOrdersDayResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'numbers_orders_day' => $this->numbers_orders_day,
            'date_number_orders' => $this->date_number_orders
        ];
    }
}
