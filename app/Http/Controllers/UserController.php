<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserLoginRequest;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRegisterRequest $request)
    {
        $user = User::create([
            "role" => strtoupper($request["role"]),
            "name" => $request["name"],
            "email" => $request["email"],
            "phone" => $request["phone"],
            "image_url" => $request["image_url"],
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

    public function login(UserLoginRequest $request) {
        
        $user = User::where("email", $request["email"])->first();

        if(!$user) {
            $response = [
                "message" => "You are not a registered user"
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

    public function logout(Request $request) {

        $request->user()->tokens()->delete();

        $response = [
            "message" => "You are successfully logout"
        ];

        return response($response, 200);
    }
}
