<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use Illuminate\Database\QueryException;

class SubscriptionController extends Controller
{
    public function index()
    {
        try {
            $subscriptions = Subscription::all();
            
            if ($subscriptions->isEmpty()) {
                return response()->json([
                    "message" => "No subscriptions found",
                    "data" => []
                ], 200);
            }
            
            return response()->json([
                "message" => "Subscriptions retrieved successfully",
                "data" => $subscriptions
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve subscriptions',
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }

    public function store(StoreSubscriptionRequest $request)
    {
        try {
            $subscription = Subscription::create($request->validated());
            
            return response()->json([
                "message" => "Subscription created successfully",
                "data" => $subscription
            ], 201);
            
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Failed to create subscription',
                'error' => 'Database error occurred'
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create subscription',
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }

    public function show(string $id)
    {
        try {
            if (!is_numeric($id)) {
                return response()->json(['message' => 'Invalid subscription ID'], 400);
            }

            $subscription = Subscription::find($id);
            
            if (!$subscription) {
                return response()->json(['message' => 'Subscription not found'], 404);
            }
            
            return response()->json([
                "message" => "Subscription retrieved successfully",
                "data" => $subscription
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve subscription',
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }

    public function update(UpdateSubscriptionRequest $request, string $id)
    {
        try {
            if (!is_numeric($id)) {
                return response()->json(['message' => 'Invalid subscription ID'], 400);
            }

            $subscription = Subscription::find($id);
            
            if (!$subscription) {
                return response()->json(['message' => 'Subscription not found'], 404);
            }

            $subscription->update($request->validated());
            
            return response()->json([
                "message" => "Subscription updated successfully",
                "data" => $subscription->fresh()
            ], 200);
            
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Failed to update subscription',
                'error' => 'Database error occurred'
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update subscription',
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            if (!is_numeric($id)) {
                return response()->json(['message' => 'Invalid subscription ID'], 400);
            }

            $subscription = Subscription::find($id);
            
            if (!$subscription) {
                return response()->json(['message' => 'Subscription not found'], 404);
            }

            $subscription->delete();
            
            return response()->json([
                'message' => 'Subscription deleted successfully'
            ], 200);
            
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Failed to delete subscription',
                'error' => 'Database constraint violation'
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete subscription',
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }
}