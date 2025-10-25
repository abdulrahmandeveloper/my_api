<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;   
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\categor;
use App\Http\Controllers\CategoryController;

// Product Routes
Route::get('/products', [ProductController::class, 'index']);      
Route::get('/product/{id}', [ProductController::class, 'show']); 
Route::post('/product', [ProductController::class, 'store']);     
Route::put('/product/{id}', [ProductController::class, 'update']); 
Route::delete('/product/{id}', [ProductController::class, 'destroy']); 


// Customer Routes
Route::apiResource('customers', CustomerController::class);
Route::get('/customer/{id}', [CustomerController::class, 'show']); 
Route::post('/customer', [CustomerController::class, 'store']);     
Route::put('/customer/{id}', [CustomerController::class, 'update']); 
Route::delete('/customer/{id}', [CustomerController::class, 'destroy']); 


// order routes
Route::apiResource('orders', OrderController::class);
Route::get('/order/{id}', [OrderController::class, 'show']); 
Route::post('/order', [OrderController::class, 'store']);     
Route::put('/order/{id}', [OrderController::class, 'update']); 
Route::delete('/order/{id}', [OrderController::class, 'destroy']); 



// channels routes
Route::apiResource('channels', ChannelController::class);
Route::get('/channel/{id}', [ChannelController::class, 'show']); 
Route::post('/channel', [ChannelController::class, 'store']);     
Route::put('/channel/{id}', [ChannelController::class, 'update']); 
Route::delete('/channel/{id}', [ChannelController::class, 'destroy']); 



// subscription routes
Route::apiResource('subscriptions', SubscriptionController::class);
Route::get('/subscription/{id}', [SubscriptionController::class, 'show']); 
Route::post('/subscription', [SubscriptionController::class, 'store']);     
Route::put('/subscription/{id}', [SubscriptionController::class, 'update']); 
Route::delete('/subscription/{id}', [SubscriptionController::class, 'destroy']); 

// category routes
Route::apiResource('categories', CategoryController::class);    
Route::get('/category/{id}', [CategoryController::class, 'show']); 
Route::post('/category', [CategoryController::class, 'store']);
Route::put('/category/{id}', [CategoryController::class, 'update']);
Route::delete('/category/{id}', [CategoryController::class, 'destroy']);