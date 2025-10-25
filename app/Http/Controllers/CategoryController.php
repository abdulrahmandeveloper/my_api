<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::all();
        return response()->json(['categories' => $categories], 200);
    }

  
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $category = Category::create($request->all());
        return response()->json(['message' => 'Category created successfully', 'category' => $category], 201);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json(['category' => $category], 200);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->all());
        return response()->json(['message' => 'Category updated successfully', 'category' => $category], 200);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}