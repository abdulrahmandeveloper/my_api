<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|integer|exists:customers,id',
            'order_date' => 'required|date|before_or_equal:today'
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.exists' => 'Selected customer does not exist',
            'order_date.date' => 'Order date must be a valid date',
            'order_date.before_or_equal' => 'Order date cannot be in the future'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}