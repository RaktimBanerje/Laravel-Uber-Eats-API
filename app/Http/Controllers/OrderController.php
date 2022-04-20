<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $model = explode("\\", get_class($user));
        $instanceOf = end($model);

        if($instanceOf == "User") {
            $orders = Order::all();

            return response($orders, 200);
        }

        if($instanceOf == "Restaurant") {
            $orders = Order::where("restaurant_id", $user["id"])->get();

            return response($orders, 200);
        }

        if($instanceOf == "Customer") {
            $orders = Order::where("customer_id", $user["id"])->get();

            return response($orders, 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $items = $request["items"];
        $menus = [];
        $orders = [];
        
        foreach ($items as $key => $item) {
            $menu = Menu::find($item["menu_id"])->toArray();

            if(!$menu["is_active"]) {
                $response = [
                    "menu" => $menu,
                    "message" => "This menu is not listed for sell"
                ];

                return response($response, 400);
            }

            if(!$menu["is_available"]) {
                $response = [
                    "menu" => $menu,
                    "message" => "This menu is currently not available"
                ];

                return response($response, 400);
            }

            $menu["quantity"] = $item["quantity"];

            $menus[] = $menu;
        }

        $menusGroupedByRestaurants = collect($menus)->groupBy("restaurant_id");

        foreach ($menusGroupedByRestaurants as $restaurant => $menus) {

            $amount = 0;
            foreach ($menus as $key => $menu) {
                $amount = $menu["price"] * $menu["quantity"];
            }
            
            $order = Order::create([
                "customer_id" => auth()->user()["id"],
                "restaurant_id" => $restaurant,
                "amount" => $amount,
                "gst" => 18.4,
                "phone" => $request["phone"],
                "country" => $request["country"],
                "state" => $request["state"],
                "city" => $request["phone"],
                "area" => $request["area"],
                "street" => $request["street"],
                "pin" => $request["pin"]
            ]);

            foreach ($menus as $key => $menu) {
                OrderDetails::create([
                    "order_id" => $order["id"],
                    "menu_id" => $menu["id"],
                    "unit_price" => $menu["price"],
                    "quantity" => $menu["quantity"],
                    "amount" => $menu["price"] * $menu["quantity"]
                ]);
            }

            $orders[] = $order;
        }

        return response($orders, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {   
        $order = Order::find($id);
        
        if($order) {

            if($this->authorize("view", $order)){
        
                $order["items"] = OrderDetails::where("order_id", $id)->get()->toArray();

                return response($order, 200);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
