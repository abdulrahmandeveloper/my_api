<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Product;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('id');
        
        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                'min:2'
            ],
            'model' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                'unique:products,model,' . $productId,
                'regex:/^[A-Za-z0-9\-_]+$/'
            ],
            'price' => [
                'sometimes',
                'required',
                'numeric',
                'min:0',
                'max:999999.99',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
            'description' => [
                'sometimes',
                'required',
                'string',
                'min:10',
                'max:1000'
            ],
            'category_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:categories,id'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'name.min' => 'Product name must be at least 2 characters',
            'model.unique' => 'This model number is already in use by another product',
            'model.regex' => 'Model number can only contain letters, numbers, dashes, and underscores',
            'price.min' => 'Price cannot be negative',
            'price.max' => 'Price cannot exceed 999,999.99',
            'price.regex' => 'Price can have maximum 2 decimal places',
            'description.min' => 'Description must be at least 10 characters',
            'category_id.exists' => 'Selected category does not exist'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $productId = $this->route('id');
            
            // Check for duplicate name + model combination (excluding current product)
            if ($this->has('name') && $this->has('model')) {
                $existingProduct = Product::where('name', $this->name)
                                          ->where('model', $this->model)
                                          ->where('id', '!=', $productId)
                                          ->first();
                
                if ($existingProduct) {
                    $validator->errors()->add('duplicate', 'Another product with this name and model already exists');
                }
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