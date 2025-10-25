<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Channel;

class ChannelController extends Controller
{
    
    public function index()
    {
       $channel = Channel::all();
       return response()->json(["data" => $channel]);
    }

    
    public function store(Request $request)
    {
        $channel = Channel::create($request->all());
        return response()->json(["message" => "Channel created successfully", "data" => $channel], 201);
    }

    
    public function show(string $id)
    {
        $channel = Channel::find($id);
        if (!$channel) {
            return response()->json(['message' => 'Channel not found'], 404);
        }
        return response()->json(["message" => "Channel retrieved successfully", "data" => $channel]);
    }

        
    

    
    public function update(Request $request, string $id)
    {
        $channel = Channel::find($id);
        if (!$channel) {
            return response()->json(['message' => 'Channel not found'], 404);
        }
        $channel->update($request->all());
        return response()->json(["message" => "Channel updated successfully", "data" => $channel]);
    }

    
    public function destroy(string $id)
    {
        $channel = Channel::find($id);
        if (!$channel) {
            return response()->json(['message' => 'Channel not found'], 404);
        }
        $channel->delete();
        return response()->json(['message' => 'Channel deleted successfully']);
    }
        
    
}
