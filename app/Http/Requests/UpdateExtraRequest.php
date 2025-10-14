<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateExtraRequest extends FormRequest
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
        $method = $this->method();
        if ($method === 'PUT') {
            return [
                'nameExtra' => ['required', 'string'],
                'price' => ['required', 'integer']
            ];
        }else {
            return [
                'nameExtra' => ['sometimes', 'string'],
                'price' => ['sometimes', 'integer']
            ];
        }
    }

    protected function prepareForValidation()
    {   
        if ($this->nameExtra) {
            $this->merge([
                'name_extra' => $this->nameExtra
            ]);
        }
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 401,
            'errors' => $validator->errors()
        ]));
    }
}
