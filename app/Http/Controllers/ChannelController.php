<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Channel;

class ChannelController extends Controller
{
    
    public function index()
    {
       $channel = Channel::all();
       if ($channel->isEmpty()) {
           return response()->json(['message' => 'No channels found', 'data' => []], 200);
       }
       return response()->json(["message" => 'Channels retrieved successfully', "data" => $channel], 200);
    }

    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            "status" => 'required|in:active,inactive',
        ]);
        $channel = Channel::create($validatedData);

        return response()->json(["message" => "Channel created successfully", "data" => $channel], 201);
    }

    
    public function show(string $id)
    {
        $channel = Channel::find($id);
        if (!$channel) {
            return response()->json(['message' => 'Channel not found'], 404);
        }
        return response()->json(["message" => "Channel retrieved successfully", "data" => $channel], 200);
    }

        
    

    
    public function update(Request $request, string $id)
    {
        $channel = Channel::find($id);
        if (!$channel) {
            return response()->json(['message' => 'Channel not found'], 404);
        }
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            "status" => 'sometimes|required|in:active,inactive',
        ]);
        $channel->update($validatedData);
        return response()->json(["message" => "Channel updated successfully", "data" => $channel], 200);
    }

    
    public function destroy(string $id)
    {
        $channel = Channel::find($id);
        if (!$channel) {
            return response()->json(['message' => 'Channel not found'], 404);
        }
        $channel->delete();
        return response()->json(['message' => 'Channel deleted successfully'], 200);
    }
        
    
}
