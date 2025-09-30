<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class StoreMenuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'foodCategory' => ['required', 'string'],
            'ingredient' => ['required', 'string'],
            'dateMenu' => ['date']
        ];
    }
    protected function prepareForValidation()
    {   
        $fechaActual = Carbon::now()->toDateString();
        $this->merge([
            'food_category' => $this->foodCategory,
            'name_ingredient' => $this->ingredient,
            'date_menu' => $fechaActual
        ]);
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 404,
            'data' => $validator->errors()
        ]));
    }
}
