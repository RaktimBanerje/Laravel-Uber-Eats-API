<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        "role",
        "name",
        "email",
        "phone",
        "image_url",
        "password",
        "isActive"
    ];

    protected $hidden = [
        "password",
        "email_verified_at"
    ];
}
