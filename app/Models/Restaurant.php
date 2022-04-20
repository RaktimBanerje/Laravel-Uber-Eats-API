<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Restaurant extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        "name",
        "slug",
        "description",
        "email",
        "phone",
        "image_url",
        "email_verified_at",
        "password",
        "country",
        "state",
        "city",
        "address",
        "open_at",
        "close_at",
        "is_open",
        "is_active",
        "is_approved",
        "approved_by",
        'is_processing'
    ];

    protected $hidden = [
        "password",
        "email_verified_at"
    ];
}
