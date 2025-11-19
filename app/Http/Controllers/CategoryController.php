<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::all();
        if( $categories->isEmpty()) {
            return response()->json(['message' => 'No categories found', 'data' => []], 200);
        }
        return response()->json(["message" => 'Categories retrieved successfully', 'data' => $categories], 200);
    }

  
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:30',
            'description' => 'nullable|string'
        ]);

        $category = Category::create($validatedData);
        return response()->json(['message' => 'Category created successfully', 'data' => $category], 201);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return response()->json(["message" => 'Category retrieved successfully', 'data' => $category], 200);
    }


    public function update(Request $request, $id)
    {

        $category = Category::findOrFail($id);

        if(!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:30',
            'description' => 'sometimes|nullable|string'
        ]);
        
        $category->update($validatedData);
        return response()->json(['message' => 'Category updated successfully', 'data' => $category], 200);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}