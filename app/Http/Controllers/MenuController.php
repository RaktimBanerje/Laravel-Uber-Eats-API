<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = Menu::all();
        return response($menus, 200);        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($this->authorize("create", Menu::class)){
            $menu = Menu::create([
                "name" => $request["name"],
                "slug" => $this->slugify($request["name"]),
                "description" => $request["description"],
                "unit_name" => $request["unit_name"],
                "unit_count" => $request["unit_count"],
                "price" => $request["price"],
                "ingredients" => $request["ingredients"],
                "image_url" => $request["image_url"],
                "hsn_code" => $request["hsn_code"],
                "restaurant_id" => $request->user()->id,
                "category_id" => $request["category_id"],
            ]);

            return response($menu, 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $menu = Menu::find($id);

        if($menu) {
            return response($menu, 200);
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
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */

    /*
        Resturent - Can Update (All except [is_activate, approved_by, is_processing])- If Menu is activated
    */
    
    public function update(Request $request, $id)
    {
        $menu = Menu::find($id);

        if($menu){

            if($this->authorize("update", $menu)) {
                $menu->name = isset($request["name"]) ? $request["name"] : $menu["name"];
                $menu->slug = isset($request["name"]) ? $this->slugify($request["name"]) : $menu["slug"];
                $menu->description = isset($request["description"]) ? $request["description"] : $menu["description"];
                $menu->price = isset($request["price"]) ? $request["price"] : $menu["price"];
                $menu->ingredients = isset($request["ingredients"]) ? $request["ingredients"] : $menu["ingredients"];
                $menu->image_url = isset($request["image_url"]) ? $request["image_url"] : $menu["image_url"];
                $menu->hsn_code = isset($request["hsn_code"]) ? $request["hsn_code"] : $menu["hsn_code"];
                $menu->is_available = isset($request["is_available"]) ? $request["is_available"] : $menu["is_available"];
                $menu->restaurant_id = isset($request["restaurant_id"]) ? $request["restaurant_id"] : $menu["restaurant_id"];
                $menu->category_id = isset($request["category_id"]) ? $request["category_id"] : $menu["category_id"];
                
                $flag = $menu->save();
                
                if($flag) {
                    $updatedMenu = Menu::find($menu->id);
    
                    return response($updatedMenu, 200);
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
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $menu = Menu::find($id);

        if($menu) {

            if($this->authorize("delete", $menu)) {
                
                $flag = Menu::destroy($id);

                if($flag){
                    return response($menu, 200);
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

    public function approve(Request $request, $id) {
        
        $menu = Menu::find($id);

        if($menu) {
            if($this->authorize("approve", $menu)) {
                $menu->is_active = 1;
                $menu->is_processing = 0;
                $menu->approved_by = auth()->user()["id"];
            };

            $flag = $menu->save();

            if($flag) {
                return response(200);
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

    public function slugify($name) 
    {
        return $name;
    }
}
