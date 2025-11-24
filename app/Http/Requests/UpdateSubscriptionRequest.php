<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'sometimes|required|integer|exists:customers,id',
            'channel_id' => 'sometimes|required|integer|exists:channels,id',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|nullable|date|after:start_date'
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.exists' => 'Selected customer does not exist',
            'channel_id.exists' => 'Selected channel does not exist',
            'start_date.date' => 'Start date must be a valid date',
            'end_date.after' => 'End date must be after start date'
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