<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateEmployeesRequest extends FormRequest
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
                'firstName' => ['required', 'string'],
                'lastName' => ['required', 'string'],
                'cedula' => ['required', 'integer'],
                'management' => ['required', 'integer'],
                'state' => ['required', 'string'],
                'typeEmployee' => ['required', 'string'],
                'position' => ['required', 'string'],
                'phone' => ['required', 'integer']
            ];
        }else {
            return [
                'firstName' => ['sometimes','string'],
                'lastName' => ['sometimes','string'],
                'cedula' => ['sometimes','integer'],
                'management' => ['sometimes','integer'],
                'state' => ['sometimes','string'],
                'typeEmployee' => ['sometimes','string'],
                'position' => ['sometimes','string'],
                'phone' => ['sometimes', 'integer']
            ];
        }
    }
    protected function prepareForValidation()
    {
        if ($this->firstName) {
            $this->merge([
                'firt_name' => $this->firstName
            ]);
        }
        if ($this->lastName) {
            $this->merge([
                'last_name' => $this->lastName
            ]);
        }
        if ($this->management) {
            $this->merge([
                'id_management' => $this->management
            ]);
        }
        if ($this->typeEmployee) {
            $this->merge([
                'type_employee' => $this->typeEmployee
            ]);
        }
        
    }
    
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 404,
            'errors' => $validator->errors()
        ], 404));
    }
    
}
