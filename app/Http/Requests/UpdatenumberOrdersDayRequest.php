<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatenumberOrdersDayRequest extends FormRequest
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
                'numberOrdersDay' => ['required', 'integer'],
                'dateNumberOrders' => ['date']
            ];
        }else {
            return [
                'numberOrdersDay' => ['sometimes', 'integer'],
                'dateNumberOrders' => ['date']
            ];
        }
    }

    protected function prepareForValidation()
    {   
        $fechaActual = Carbon::now()->toDateString();
        if ($this->foodCategory) {
            $this->merge([
                'numbers_orders_day' => $this->numberOrdersDay
            ]);
        }
        $this->merge([
            'date_number_orders' => $fechaActual
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
