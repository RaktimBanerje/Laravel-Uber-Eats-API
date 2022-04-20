<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'image_url',
        'is_active',
        'email_verified_at',
        'password',
        'country',
        'state',
        'city',
        'address',
    ];

    protected $hidden = [
        "password",
        "email_verified_at"
    ];
}
