<?php

use App\Http\Controllers\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([],  function() {
    Route::apiResource("category", CategoryController::class)->only(["index", "show"]);

    Route::apiResource("menu", MenuController::class)->only(["index", "show"]);
});

// User routes
Route::group([
    "prefix" => "user/",
    "as" => "user."
],  function() {
    
    // Public Routes
    Route::group([],   function(){
        
        Route::post("login", [UserController::class, "login"]);

        Route::apiResource("", UserController::class)->only(["store"]);
    });

    // Protected routes
    Route::group([
        "middleware" => ["auth:sanctum", "user"]
    ],  function(){
        
        Route::get("logout", [UserController::class, "logout"]);

        Route::apiResource("category", CategoryController::class)->only(["store", "update", "destroy"]);
        
        Route::match(["put", "patch"], "restaurant/{id}", [RestaurantController::class, "approve"]);

        Route::match(["put", "patch"], "menu/{id}", [MenuController::class, "approve"]);

        Route::apiResource("order", OrderController::class)->except(["store"]);
    });
});


// Customer routes
Route::group([
    "prefix" => "customer/",
    "as" => "customer."
],  function() {
    
    // Public Routes
    Route::group([],   function(){
        
        Route::post("login", [CustomerController::class, "login"]);

        Route::apiResource("", CustomerController::class)->only(["store"]);
    });

    // Protected routes
    Route::group([
        "middleware" => ["auth:sanctum", "customer"]
    ],  function(){
        
        Route::get("logout", [CustomerController::class, "logout"]);

        Route::delete("/cart", [CartController::class, "destroy_all"]);

        Route::apiResource("cart", CartController::class)->except(["show"]);

        Route::apiResource("order", OrderController::class);
    });
});


// Restaurant routes
Route::group([
    "prefix" => "restaurant/",
    "as" => "restaurant."
],  function() {
    
    // Public Routes
    Route::group([],   function(){
        
        Route::post("login", [RestaurantController::class, "login"]);

        Route::apiResource("", RestaurantController::class)->only(["store"]);
    });

    // Protected routes
    Route::group([
        "middleware" => ["auth:sanctum", "restaurant"]
    ],  function(){
        
        Route::get("logout", [RestaurantController::class, "logout"]);

        Route::apiResource("menu", MenuController::class)->only(["store", "update", "destroy"])->withoutMiddleware("restaurant");
    
        Route::apiResource("order", OrderController::class)->except(["store"]);
    });
});
