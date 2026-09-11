<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BlukStoreMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'menus'                => ['required', 'array'],
            'menus.*.foodCategory' => ['required', 'string'],
            'menus.*.ingredient'   => ['required', 'string'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 422,
            'data'   => $validator->errors()
        ], 422));
    }

    /**
     * Devuelve los datos transformados listos para insertar en MySQL.
     */
    public function getFormattedData(): array
    {
        $today = Carbon::today()->toDateString();
        $now   = Carbon::now();

        return array_map(function ($item) use ($today, $now) {
            return [
                'food_category'   => $item['foodCategory'],
                'name_ingredient' => $item['ingredient'],
                'date_menu'       => $today,
                'created_at'      => $now,
                'updated_at'      => $now,
            ];
        }, $this->validated());
    }
}