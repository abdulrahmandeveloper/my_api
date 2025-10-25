<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    
    public function index()
    {
        $subscriptions = Subscription::all();
        if ($subscriptions->isEmpty()) {
            return response()->json(["message" => "No subscriptions found", "data" => []], 204);
        }
        return response()->json(["message" => "Subscriptions retrieved successfully", "data" => $subscriptions], 200);
    }

   
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'customer_id' => 'required|integer|exists:users,id',
    
            'status' => 'required|string|in:active,inactive,cancelled',
            'start_date' => 'required|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date'
        ]);
        $subscription = Subscription::create($validatedData);
        return response()->json(["message" => "Subscription created successfully", "data" => $subscription], 201);
    }

    
    public function show(string $id)
    {
        $subscription = Subscription::find($id);
        if (!$subscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }
        return response()->json(["message" => "Subscription retrieved successfully", "data" => $subscription], 200);
    }

        
    


    public function update(Request $request, string $id)
    {
        $subscription = Subscription::find($id);
        if (!$subscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }
        $validatedData = $request->validate([
            'customer_id' => 'sometimes|required|integer|exists:users,id',
            'status' => 'sometimes|required|string|in:active,inactive,cancelled',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date'
        ]);
        $subscription->update($validatedData);
        return response()->json(["message" => "Subscription updated successfully", "data" => $subscription], 200);
    }

    public function destroy(string $id)
    {
        $subscription = Subscription::find($id);
        if (!$subscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }
        $subscription->delete();
        return response()->json(['message' => 'Subscription deleted successfully']);
    }
}

