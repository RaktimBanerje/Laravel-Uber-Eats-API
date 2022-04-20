<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRegisterRequest extends FormRequest
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
            "name"      => "required",
            "email"     => "required|unique:customers",
            "phone"     => "required|unique:customers",
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
