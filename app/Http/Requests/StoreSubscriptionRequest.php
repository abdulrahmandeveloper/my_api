<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Subscription;

class StoreSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|integer|exists:customers,id',
            'channel_id' => 'required|integer|exists:channels,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date'
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Customer is required',
            'customer_id.exists' => 'Selected customer does not exist',
            'channel_id.required' => 'Channel is required',
            'channel_id.exists' => 'Selected channel does not exist',
            'start_date.required' => 'Start date is required',
            'start_date.after_or_equal' => 'Start date cannot be in the past',
            'end_date.after' => 'End date must be after start date'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if customer already has active subscription for this channel
            $existingSubscription = Subscription::where('customer_id', $this->customer_id)
                ->where('channel_id', $this->channel_id)
                ->whereNull('end_date')
                ->orWhere(function($query) {
                    $query->where('customer_id', $this->customer_id)
                          ->where('channel_id', $this->channel_id)
                          ->where('end_date', '>=', now());
                })
                ->first();
            
            if ($existingSubscription) {
                $validator->errors()->add('duplicate', 'Customer already has an active subscription for this channel');
            }
        });
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