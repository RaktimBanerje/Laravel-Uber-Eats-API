<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer = auth()->user();
        $cart = Cart::where("customer_id", $customer["id"])->get();
        return response($cart, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customer = auth()->user();

        $menu = Menu::find($request["menu_id"]);

        if($menu) {

            if(!$menu["is_active"]) {
                
                $response = [ 
                    "menu" => $menu,
                    "message" => "This menu is not listed for sell"
                ];

                return response($response, 400);
            }

            $cart = Cart::create([
                "customer_id" => $customer["id"],
                "menu_id" => $menu["id"],
                "quantity" => isset($request["quantity"]) ? $request["quantity"] : 1
            ]);

            if($cart) {
                return response($cart, 200);
            }
            else {
                $response = [
                    "message" => "Something went wrong"
                ];

                return response($response, 500);
            }
        }
        else { 
            $response = [
                "message" => "No result found"
            ];

            return response($response, 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cart = Cart::find($id);

        if($cart) {

            if($this->authorize("update", $cart)){
                $operation = $request["operation"];
                $count = (int)$request["count"];
    
                if($operation == "+") {
                    $cart->quantity = $cart["quantity"] + $count;
                }
    
                if($operation == "-") {
                    
                    if(($cart["quantity"] - $count) < 1) {
    
                        $response = [
                            "message" => "Bad request"
                        ];
    
                        return response($response, 400);
                    }
    
                    else {
                        $cart->quantity = $cart["quantity"] - $count;
                    }
                }
    
                $flag = $cart->save();
    
                if($flag) {
                    return response($flag, 200);
                }
                else {
                    $response = [
                        "message" => "Something went wrong"
                    ];
    
                    return response($response, 500);
                }
            }
        }
        else {
            $response = [
                "message" => "No result found"
            ];

            return response($response, 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request  $request, $id)
    {
        $cart = Cart::find($id);

        if($cart) {
            if($this->authorize("delete", $cart)) {
                $flag = Cart::destroy($id);

                if($flag) {
                    return response($cart, 200);
                }
                else {
                    $response = [
                        "message" => "Something went wrong"
                    ];
        
                    return response($response, 500);
                }
            }
        }
        else {
            $response = [
                "message" => "No result found"
            ];

            return response($response, 404);
        }

    }

    public function destroy_all() {
        $customer = auth()->user();

        $flag = Cart::where("customer_id", $customer["id"])->delete();

        if($flag) {
            return response($flag, 200);
        }
        else {
            $response = [
                "message" => "Something went wrong"
            ];

            return response($response, 500);
        }
    }
}
