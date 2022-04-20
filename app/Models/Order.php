<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        "customer_id",
        "restaurant_id",
        "approved_by",
        "status",
        "amount",
        "gst",
        "phone",
        "country",
        "state",
        "city",
        "area",
        "street",
        "pin"
    ];
}
