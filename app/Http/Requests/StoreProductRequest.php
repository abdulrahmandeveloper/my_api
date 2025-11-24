<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Product;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'min:2'
            ],
            'model' => [
                'required',
                'string',
                'max:255',
                'unique:products,model',
                'regex:/^[A-Za-z0-9\-_]+$/'
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
                'max:999999.99',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
            'description' => [
                'required',
                'string',
                'min:10',
                'max:1000'
            ],
            'category_id' => [
                'nullable',
                'integer',
                'exists:categories,id'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required',
            'name.min' => 'Product name must be at least 2 characters',
            'model.required' => 'Product model number is required',
            'model.unique' => 'This model number already exists',
            'model.regex' => 'Model number can only contain letters, numbers, dashes, and underscores',
            'price.required' => 'Price is required',
            'price.min' => 'Price cannot be negative',
            'price.max' => 'Price cannot exceed 999,999.99',
            'price.regex' => 'Price can have maximum 2 decimal places',
            'description.required' => 'Description is required',
            'description.min' => 'Description must be at least 10 characters',
            'category_id.exists' => 'Selected category does not exist'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check for duplicate name + model combination
            $existingProduct = Product::where('name', $this->name)
                                      ->where('model', $this->model)
                                      ->first();
            
            if ($existingProduct) {
                $validator->errors()->add('duplicate', 'A product with this name and model already exists');
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