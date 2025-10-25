<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    
    public function index()
    {
        $order= Order::all();
        if ($order->isEmpty()) {
            return response()->json( ["message" => "No orders found", "data" => []], 204);
        }
        return response()->json(["message" => "Orders retrieved successfully", "data" => $order], 200);
    }

    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'customer_id' => 'required|integer|exists:customers,id',
            'order_date' => 'required|date',
        ]);
        $order = Order::create($validatedData);
        return response()->json(["message" => "Order created successfully", "data" => $order], 201);
    }

 
    public function show(string $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json( ["message" => "Order not found"], 404);
        }
        return response()->json(["message" => "Order retrieved successfully", "data" => $order], 200);
    }

    
    public function update(Request $request, string $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $validatedData = $request->validate([
            'customer_id' => 'sometimes|integer|exists:customers,id',
            'order_date' => 'sometimes|date',
        ]);

        $order->update($validatedData);
        return response()->json(["message" => "Order updated successfully", "data" => $order], 200);
    }

    public function destroy(string $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        $order->delete();
        return response()->json(['message' => 'Order deleted successfully']);
    }
}

        
 
