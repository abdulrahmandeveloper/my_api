<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Channel;
use App\Http\Requests\StoreChannelRequest;
use App\Http\Requests\UpdateChannelRequest;
use Illuminate\Database\QueryException;

class ChannelController extends Controller
{
    public function index()
    {
        try {
            $channels = Channel::all();
            
            if ($channels->isEmpty()) {
                return response()->json([
                    'message' => 'No channels found',
                    'data' => []
                ], 200);
            }
            
            return response()->json([
                "message" => 'Channels retrieved successfully',
                "data" => $channels
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve channels',
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }

    public function store(StoreChannelRequest $request)
    {
        try {
            $channel = Channel::create($request->validated());
            
            return response()->json([
                "message" => "Channel created successfully",
                "data" => $channel
            ], 201);
            
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return response()->json([
                    'message' => 'Duplicate entry detected',
                    'error' => 'This channel name already exists'
                ], 409);
            }
            
            return response()->json([
                'message' => 'Failed to create channel',
                'error' => 'Database error occurred'
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create channel',
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }

    public function show(string $id)
    {
        try {
            if (!is_numeric($id)) {
                return response()->json(['message' => 'Invalid channel ID'], 400);
            }

            $channel = Channel::find($id);
            
            if (!$channel) {
                return response()->json(['message' => 'Channel not found'], 404);
            }
            
            return response()->json([
                "message" => "Channel retrieved successfully",
                "data" => $channel
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve channel',
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }

    public function update(UpdateChannelRequest $request, string $id)
    {
        try {
            if (!is_numeric($id)) {
                return response()->json(['message' => 'Invalid channel ID'], 400);
            }

            $channel = Channel::find($id);
            
            if (!$channel) {
                return response()->json(['message' => 'Channel not found'], 404);
            }

            $channel->update($request->validated());
            
            return response()->json([
                "message" => "Channel updated successfully",
                "data" => $channel->fresh()
            ], 200);
            
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return response()->json([
                    'message' => 'Duplicate entry detected',
                    'error' => 'This channel name already exists'
                ], 409);
            }
            
            return response()->json([
                'message' => 'Failed to update channel',
                'error' => 'Database error occurred'
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update channel',
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            if (!is_numeric($id)) {
                return response()->json(['message' => 'Invalid channel ID'], 400);
            }

            $channel = Channel::find($id);
            
            if (!$channel) {
                return response()->json(['message' => 'Channel not found'], 404);
            }

            if ($channel->subscriptions()->count() > 0) {
                return response()->json([
                    'message' => 'Cannot delete channel',
                    'error' => 'This channel has active subscriptions'
                ], 409);
            }

            $channel->delete();
            
            return response()->json([
                'message' => 'Channel deleted successfully'
            ], 200);
            
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Failed to delete channel',
                'error' => 'Database constraint violation'
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete channel',
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }
}