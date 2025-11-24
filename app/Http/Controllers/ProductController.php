<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{


    public function index()
    {
        $products = Product::all();
        if ($products->isEmpty()) {
            return Response()->json(["message" => "No products found", "data" => []], 200);
        }
        return Response()->json(["message" => 'Products retrieved successfully', 'data' => $products], 200);
    }


    public function store(StoreProductRequest $request)
   {
        try {
            $product = Product::create($request->validated());
            
            return response()->json([
                'message' => "Product created successfully",
                'data' => $product
            ], 201);
            
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return response()->json([
                    'message' => 'Duplicate entry detected',
                    'error' => 'This product already exists'
                ], 409);
            }
            
            return $this->errorResponse('Failed to create product');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create product');
        }
    }


    public function show(string $id)
 {
        try {
            if (!$this->isValidId($id)) {
                return response()->json(['message' => 'Invalid product ID'], 400);
            }

            $product = Product::find($id);
            
            if (is_null($product)) {
                return response()->json(['message' => 'Product not found'], 404);
            }
            
            return response()->json([
                "message" => 'Product retrieved successfully',
                'data' => $product
            ], 200);
            
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve product');
        }
    }


    public function update(UpdateProductRequest $request, string $id)
   {
        try {
            if (!$this->isValidId($id)) {
                return response()->json(['message' => 'Invalid product ID'], 400);
            }

            $product = Product::find($id);
            
            if (is_null($product)) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            $product->update($request->validated());
            
            return response()->json([
                "message" => 'Product updated successfully',
                'data' => $product->fresh()
            ], 200);
            
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return response()->json([
                    'message' => 'Duplicate entry detected',
                    'error' => 'This product already exists'
                ], 409);
            }
            
            return $this->errorResponse('Failed to update product');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update product');
        }
    }

  
    public function destroy(string $id)
     {
        try {
            if (!$this->isValidId($id)) {
                return response()->json(['message' => 'Invalid product ID'], 400);
            }

            $product = Product::find($id);
            
            if (is_null($product)) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            if ($product->orders()->count() > 0) {
                return response()->json([
                    'message' => 'Cannot delete product',
                    'error' => 'This product has associated orders and cannot be deleted'
                ], 409);
            }

            $product->delete();
            
            return response()->json(['message' => 'Product deleted successfully'], 200);
            
        } catch (QueryException $e) {
            return $this->errorResponse('Failed to delete product due to database constraints');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete product');
        }
    }

    //helpers
       private function isValidId($id): bool
    {
        return is_numeric($id) && $id > 0;
    }

    private function errorResponse(string $message, int $code = 500)
    {
        return response()->json([
            'message' => $message,
            'error' => 'An unexpected error occurred'
        ], $code);
    }

}
   

