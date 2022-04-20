<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "role"      => "required",
            "name"      => "required",
            "email"     => "required|unique:users",
            "phone"     => "required|unique:users",
            "password"  => "required"
        ];
    }

    public function attributes()
    {
        return [
            "email" => "email address",
            "phone" => "phone number",
        ];
    }
}
