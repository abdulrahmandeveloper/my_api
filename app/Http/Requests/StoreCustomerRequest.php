<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|min:2',
            'email' => 'nullable|email|unique:customers,email',
            'phone' => 'required|string|unique:customers,phone|regex:/^[0-9+\-\s()]+$/',
            'address' => 'nullable|string|max:500'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Customer name is required',
            'name.min' => 'Name must be at least 2 characters',
            'email.email' => 'Please provide a valid email address',
            'email.unique' => 'This email is already registered',
            'phone.required' => 'Phone number is required',
            'phone.unique' => 'This phone number is already registered',
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