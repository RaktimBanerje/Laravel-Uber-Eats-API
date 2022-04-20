<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RestaurantLoginRequest;
use App\Http\Requests\RestaurantRegisterRequest;
use App\Models\Restaurant;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $restaurants = Restaurant::all();

        return response($restaurants, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RestaurantRegisterRequest $request)
    {
        $user = Restaurant::create([
            "name" => $request["name"],
            "email" => $request["email"],
            "slug" => $this->slugify($request["name"]),
            "phone" => $request["phone"],
            "password" => Hash::make($request["password"])
        ]);

        $token = $user->createToken("myapptoken")->plainTextToken;

        $response = [
            "user" => $user,
            "token" => $token
        ];

        return response($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function login(RestaurantLoginRequest $request){
    
        $user = Restaurant::where("email", $request["email"])->first();

        if(!$user){
            $response = [
                "message" => "You are not a resgistered user"
            ];

            return response($response, 401);
        }
        else if(!Hash::check($request["password"], $user->password)){
            $response = [
                "message" => "Password doesn't matched"
            ];

            return response($response, 401);
        }
        else {
            $token = $user->createToken("myapptoken")->plainTextToken;

            $response = [
                "user" => $user,
                "token" => $token
            ];

            return response($response, 200);
        }
    }

    public function logout(Request $request){

        $request->user()->tokens()->delete();
        
        $response = [
            "message" => "You are successfully logout"
        ];

        return response($response, 200);
    }

    public function approve(Request $request, $id) {
        
        $restaurant = Restaurant::find($id);

        if($restaurant) {
            if($this->authorize("approve", $restaurant)) {
                $restaurant->is_active = 1;
                $restaurant->is_processing = 0;
                $restaurant->approved_by = auth()->user()["id"];
            };

            $flag = $restaurant->save();

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
