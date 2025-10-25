<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    
    public function index()
    {
       return response()->json(["message" => "Subscriptions retrieved successfully", "data" => Subscription::all()], 200);
    }

   
    public function store(Request $request)
    {
        $subscription = Subscription::create($request->all());
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
        $subscription->update($request->all());
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

