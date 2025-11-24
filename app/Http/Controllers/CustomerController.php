<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Database\QueryException;

class CustomerController extends Controller
{
    public function index()
    {
        try {
            $customers = Customer::all();
            
            if ($customers->isEmpty()) {
                return response()->json([
                    "message" => "No customers found",
                    "data" => []
                ], 200);
            }
            
            return response()->json([
                "message" => "Customers retrieved successfully",
                "data" => $customers
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve customers',
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }

    public function store(StoreCustomerRequest $request)
    {
        try {
            $customer = Customer::create($request->validated());
            
            return response()->json([
                "message" => "Customer created successfully",
                "data" => $customer
            ], 201);
            
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return response()->json([
                    'message' => 'Duplicate entry detected',
                    'error' => 'This phone number already exists'
                ], 409);
            }
            
            return response()->json([
                'message' => 'Failed to create customer',
                'error' => 'An unexpected error occurred'
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create customer',
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }

    public function show(string $id)
    {
        try {
            if (!is_numeric($id)) {
                return response()->json(['message' => 'Invalid customer ID'], 400);
            }

            $customer = Customer::find($id);
            
            if (!$customer) {
                return response()->json(['message' => 'Customer not found'], 404);
            }
            
            return response()->json([
                "message" => "Customer retrieved successfully",
                "data" => $customer
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve customer',
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }

    public function update(UpdateCustomerRequest $request, string $id)
    {
        try {
            if (!is_numeric($id)) {
                return response()->json(['message' => 'Invalid customer ID'], 400);
            }

            $customer = Customer::find($id);
            
            if (!$customer) {
                return response()->json(['message' => 'Customer not found'], 404);
            }

            $customer->update($request->validated());
            
            return response()->json([
                "message" => "Customer updated successfully",
                "data" => $customer->fresh()
            ], 200);
            
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return response()->json([
                    'message' => 'Duplicate entry detected',
                    'error' => 'This phone number already exists'
                ], 409);
            }
            
            return response()->json([
                'message' => 'Failed to update customer',
                'error' => 'An unexpected error occurred'
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update customer',
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            if (!is_numeric($id)) {
                return response()->json(['message' => 'Invalid customer ID'], 400);
            }

            $customer = Customer::find($id);
            
            if (!$customer) {
                return response()->json(['message' => 'Customer not found'], 404);
            }

            if ($customer->orders()->count() > 0) {
                return response()->json([
                    'message' => 'Cannot delete customer',
                    'error' => 'This customer has associated orders'
                ], 409);
            }

            if ($customer->subscription) {
                return response()->json([
                    'message' => 'Cannot delete customer',
                    'error' => 'This customer has an active subscription'
                ], 409);
            }

            $customer->delete();
            
            return response()->json([
                'message' => 'Customer deleted successfully'
            ], 200);
            
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Failed to delete customer',
                'error' => 'Database constraint violation'
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete customer',
                'error' => 'An unexpected error occurred'
            ], 500);
        }
    }
}