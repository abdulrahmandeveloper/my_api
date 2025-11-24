<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Illuminate\Database\QueryException;

class OrderController extends Controller
{
    public function index()
    {
        try {
            $orders = Order::all();
            
            if ($orders->isEmpty()) {
                return response()->json([
                    "message" => "No orders found",
                    "data" => []
                ], 200);
            }
            
            return response()->json([
                "message" => "Orders retrieved successfully",
                "data" => $orders
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve orders',
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }

    public function store(StoreOrderRequest $request)
    {
        try {
            $order = Order::create($request->validated());
            
            return response()->json([
                "message" => "Order created successfully",
                "data" => $order
            ], 201);
            
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Failed to create order',
                'error' => 'Database error occurred'
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create order',
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }

    public function show(string $id)
    {
        try {
            if (!is_numeric($id)) {
                return response()->json(['message' => 'Invalid order ID'], 400);
            }

            $order = Order::find($id);
            
            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }
            
            return response()->json([
                "message" => "Order retrieved successfully",
                "data" => $order
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve order',
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }

    public function update(UpdateOrderRequest $request, string $id)
    {
        try {
            if (!is_numeric($id)) {
                return response()->json(['message' => 'Invalid order ID'], 400);
            }

            $order = Order::find($id);
            
            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            $order->update($request->validated());
            
            return response()->json([
                "message" => "Order updated successfully",
                "data" => $order->fresh()
            ], 200);
            
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Failed to update order',
                'error' => 'Database error occurred'
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update order',
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            if (!is_numeric($id)) {
                return response()->json(['message' => 'Invalid order ID'], 400);
            }

            $order = Order::find($id);
            
            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            $order->delete();
            
            return response()->json([
                'message' => 'Order deleted successfully'
            ], 200);
            
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Failed to delete order',
                'error' => 'Database constraint violation'
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete order',
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }
}