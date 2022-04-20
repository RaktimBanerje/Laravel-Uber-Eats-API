<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryStoreRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return response($categories, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryStoreRequest $request)
    {
        $category = Category::create([
            "name" => $request["name"],
            "slug" => $this->slugify($request["name"]),
            "description" => $request["description"],
            "image_url" => $request["image_url"],
            "created_by" => $request->user()->id
        ]);

        return response($category, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $category = Category::find($id);

        if($category) {
            return response($category, 200);
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
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if($category){

            if($this->authorize("update", $category)) {
                $category->name = isset($request["name"]) ? $request["name"] : $category["name"];
                $category->slug = isset($request["name"]) ? $this->slugify($request["name"]) : $category["slug"];
                $category->description = isset($request["description"]) ? $request["description"] : $category["description"];
                $category->image_url = isset($request["image_url"]) ? $request["image_url"] : $category["image_url"];
                $category->is_active = isset($request["is_active"]) ? ($request["is_active"] == "1" ? 1 : 0) : $category["is_active"];
                
                $flag = $category->save();
                
                if($flag) {
                    $updatedCategory = Category::find($category->id);
    
                    return response($updatedCategory, 200);
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
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $category = Category::find($id);

        if($category) {
            if($this->authorize("delete", $category)) {
                
                $flag = Category::destroy($id);

                if($flag){
                    return response($category, 200);
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

    public function slugify($name) 
    {
        return $name;
    }
}
