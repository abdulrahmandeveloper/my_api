<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateChannelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $channelId = $this->route('id');
        
        return [
            'name' => 'sometimes|required|string|max:255|min:2|unique:channels,name,' . $channelId,
            'description' => 'sometimes|nullable|string|max:1000',
            'status' => 'sometimes|required|in:active,inactive'
        ];
    }

    public function messages(): array
    {
        return [
            'name.min' => 'Channel name must be at least 2 characters',
            'name.unique' => 'This channel name is already in use by another channel',
            'status.in' => 'Status must be either active or inactive'
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