<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;   
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;


Route::post('/test-login', function() {
    return response()->json(['message' => 'POST works']);
});




Route::get('/debug', function () {
    return response()->json([
        'message' => 'routes/api.php is loading',
        'routes' => collect(app('router')->getRoutes())->map(fn($r) => $r->uri())
    ]);
});


Route::post('/signup', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

   
    Route::get('/products', [ProductController::class, 'index']);      
    Route::get('/products/{id}', [ProductController::class, 'show']);  
    Route::post('/products', [ProductController::class, 'store']);     
    Route::put('/products/{id}', [ProductController::class, 'update']); 
    Route::delete('/products/{id}', [ProductController::class, 'destroy']); 

    Route::get('/customers', [CustomerController::class, 'index']);
    Route::get('/customers/{id}', [CustomerController::class, 'show']); 
    Route::post('/customers', [CustomerController::class, 'store']);     
    Route::put('/customers/{id}', [CustomerController::class, 'update']); 
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy']); 

    Route::get('/orders', [OrderController::class, 'index']);           
    Route::get('/orders/{id}', [OrderController::class, 'show']);       
    Route::post('/orders', [OrderController::class, 'store']);     
    Route::put('/orders/{id}', [OrderController::class, 'update']);     
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']); 

    Route::get('/channels', [ChannelController::class, 'index']);       
    Route::get('/channels/{id}', [ChannelController::class, 'show']);   
    Route::post('/channels', [ChannelController::class, 'store']);     
    Route::put('/channels/{id}', [ChannelController::class, 'update']); 
    Route::delete('/channels/{id}', [ChannelController::class, 'destroy']); 

    Route::get('/subscriptions', [SubscriptionController::class, 'index']);     
    Route::get('/subscriptions/{id}', [SubscriptionController::class, 'show']); 
    Route::post('/subscriptions', [SubscriptionController::class, 'store']);     
    Route::put('/subscriptions/{id}', [SubscriptionController::class, 'update']); 
    Route::delete('/subscriptions/{id}', [SubscriptionController::class, 'destroy']); 

    Route::get('/categories', [CategoryController::class, 'index']);    
    Route::get('/categories/{id}', [CategoryController::class, 'show']); 
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']); 
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']); 
    
   
});