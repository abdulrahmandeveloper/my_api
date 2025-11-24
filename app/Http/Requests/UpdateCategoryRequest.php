<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('id');
        
        return [
            'name' => 'sometimes|required|string|max:30|min:2|unique:categories,name,' . $categoryId,
            'description' => 'sometimes|nullable|string|max:500'
        ];
    }

    public function messages(): array
    {
        return [
            'name.min' => 'Category name must be at least 2 characters',
            'name.max' => 'Category name cannot exceed 30 characters',
            'name.unique' => 'This category name is already in use by another category',
            'description.max' => 'Description cannot exceed 500 characters'
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