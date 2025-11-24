<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customerId = $this->route('id');
        
        return [
            'name' => 'sometimes|required|string|max:255|min:2',
            'email' => 'sometimes|nullable|email|unique:customers,email,' . $customerId,
            'phone' => 'sometimes|required|string|unique:customers,phone,' . $customerId . '|regex:/^[0-9+\-\s()]+$/',
            'address' => 'sometimes|nullable|string|max:500'
        ];
    }

    public function messages(): array
    {
        return [
            'name.min' => 'Name must be at least 2 characters',
            'email.email' => 'Please provide a valid email address',
            'email.unique' => 'This email is already in use by another customer',
            'phone.unique' => 'This phone number is already in use by another customer',
            'phone.regex' => 'Phone number format is invalid'
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