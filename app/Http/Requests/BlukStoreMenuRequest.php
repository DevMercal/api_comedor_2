<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class BlukStoreMenuRequest extends FormRequest
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
            '.*foodCategory' => ['required', 'string'],
            '.*ingredient' => ['required', 'string'],
            '.*dateMenu' => ['date']
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 404,
            'data' => $validator->errors()
        ]));
    }
    protected function prepareForValidation()
    {   
        $fechaActual = Carbon::now()->toDateString();
        $now = Carbon::now();
        $data = [];
        foreach ($this->toArray() as $obj) {
            $obj['food_category'] = $obj['foodCategory'] ?? NULL;
            $obj['name_ingredient'] = $obj['ingredient'] ?? NULL;
            $obj['date_menu'] = $fechaActual;
            $obj['created_at'] = $now;
            $obj['updated_at'] = $now;
            $data[] = $obj;
        }
        $this->merge($data);
    }
}
