<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreEmployeesRequest extends FormRequest
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
            //
            'firstName' => ['required', 'string'],
            'lastName' => ['required', 'string'],
            'cedula' => ['required', 'integer'],
            'management' => ['required', 'integer'],
            'state' => ['required', 'string'],
            'typeEmployee' => ['required', 'string'],
            'position' => ['required', 'string'],
            'phone' => ['required', 'integer']
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'firt_name' => $this->firstName,
            'last_name' => $this->lastName,
            'id_management' => $this->management,
            'type_employee' => $this->typeEmployee
        ]);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 401,
            'errors' => $validator->errors()
        ]));
    }
}
