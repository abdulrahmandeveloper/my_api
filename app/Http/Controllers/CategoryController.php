<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Database\QueryException;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::all();
            
            if ($categories->isEmpty()) {
                return response()->json([
                    'message' => 'No categories found',
                    'data' => []
                ], 200);
            }
            
            return response()->json([
                "message" => 'Categories retrieved successfully',
                'data' => $categories
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve categories',
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }

    public function store(StoreCategoryRequest $request)
    {
        try {
            $category = Category::create($request->validated());
            
            return response()->json([
                'message' => 'Category created successfully',
                'data' => $category
            ], 201);
            
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return response()->json([
                    'message' => 'Duplicate entry detected',
                    'error' => 'This category name already exists'
                ], 409);
            }
            
            return response()->json([
                'message' => 'Failed to create category',
                'error' => 'Database error occurred'
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create category',
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }

    public function show(string $id)
    {
        try {
            if (!is_numeric($id)) {
                return response()->json(['message' => 'Invalid category ID'], 400);
            }

            $category = Category::find($id);
            
            if (!$category) {
                return response()->json(['message' => 'Category not found'], 404);
            }
            
            return response()->json([
                "message" => 'Category retrieved successfully',
                'data' => $category
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve category',
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }

    public function update(UpdateCategoryRequest $request, string $id)
    {
        try {
            if (!is_numeric($id)) {
                return response()->json(['message' => 'Invalid category ID'], 400);
            }

            $category = Category::find($id);
            
            if (!$category) {
                return response()->json(['message' => 'Category not found'], 404);
            }

            $category->update($request->validated());
            
            return response()->json([
                'message' => 'Category updated successfully',
                'data' => $category->fresh()
            ], 200);
            
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return response()->json([
                    'message' => 'Duplicate entry detected',
                    'error' => 'This category name already exists'
                ], 409);
            }
            
            return response()->json([
                'message' => 'Failed to update category',
                'error' => 'Database error occurred'
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update category',
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            if (!is_numeric($id)) {
                return response()->json(['message' => 'Invalid category ID'], 400);
            }

            $category = Category::find($id);
            
            if (!$category) {
                return response()->json(['message' => 'Category not found'], 404);
            }

            if ($category->products()->count() > 0) {
                return response()->json([
                    'message' => 'Cannot delete category',
                    'error' => 'This category has associated products'
                ], 409);
            }

            $category->delete();
            
            return response()->json([
                'message' => 'Category deleted successfully'
            ], 200);
            
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Failed to delete category',
                'error' => 'Database constraint violation'
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete category',
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }
}