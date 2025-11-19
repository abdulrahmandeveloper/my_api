<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{

    public function index()
    {
        $customers = Customer::all();
        if ($customers->isEmpty()) {
            return response()->json(["message" => "No customers found", "data" => []], 200);
        }
        return response()->json(["message" => "Customers retrieved successfully", "data" => $customers], 200);
    }

   
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'sometimes|email',
            'phone' => 'required|string|unique:customers,phone',
            'address' => 'sometimes|string|max:500'
        ]);
        $customers = Customer::create($validatedData);
        return response()->json(["message" => "Customer created successfully", "data" => $customers], 201);
    }

  
    public function show(string $id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }
        return response()->json(["message" => "Customer retrieved successfully", "data" => $customer], 200);
    }

  
    public function update(Request $request, string $id)
    {
        try {
            $customer = Customer::find($id);
            
            if (!$customer) {
                return response()->json(['message' => 'Customer not found'], 404);
            }

            if ($request->has('phone') && $request->phone !== $customer->phone) {
                $existingCustomer = Customer::where('phone', $request->phone)
                    ->where('id', '!=', $id)
                    ->first();
                
                if ($existingCustomer) {
                    return response()->json([
                        'message' => 'The phone number has already been taken by another customer.',
                        'errors' => ['phone' => ['This phone number is already in use.']]
                    ], 422);
                }
            }

            $request->validate([
                'name' => 'string|max:255',
                'email' => 'email|nullable',
                'phone' => 'string',
                'address' => 'string|max:500|nullable'
            ]);

            $customer->update($request->all());
            return response()->json([
                "message" => "Customer updated successfully", 
                "data" => $customer
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating customer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }
        $customer->delete();
        return response()->json(['message' => 'Customer deleted successfully']);
    }


        
    
}
