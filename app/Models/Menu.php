<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "slug",
        "description",
        "unit_name",
        "unit_count",
        "price",
        "ingredients",
        "image_url",
        "hsn_code",
        "is_active",
        "is_available",
        "restaurant_id",
        "category_id",
        "approved_by"
    ];
}
