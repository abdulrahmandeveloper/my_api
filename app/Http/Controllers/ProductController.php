<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

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


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'required|string|max:1000'
        ]);

        $product = Product::create($validated);
        return Response()->json(['message' => "Product created successfully.", 'data' => $product], 201);
    }


    public function show(string $id)
    {
        $product = Product::find($id);
        if (is_null($product)) {
            return Response()->json(['message' => 'Product Not Found'], 404);
        }
        return Response()->json(["message" => 'Product retrieved successfully', 'data' => $product]);
    }


    public function update(Request $request, string $id)
    {
        $product = Product::find($id);
        if (is_null($product)) {
            return Response()->json(['message' => 'Product Not Found'], 404);
        }
       
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'model' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric',
            'description' => 'sometimes|string|max:1000'
        ]);

        $product->update($validated);
        return Response()->json(["message" => 'Product updated successfully', 'data' => $product]);
    }

  
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if (is_null($product)) {
            return Response()->json(['message' => 'Product Not Found'], 404);
        }
        $product->delete();
        return Response()->json(['message' => 'Product Deleted Successfully']);
    }

}
   

