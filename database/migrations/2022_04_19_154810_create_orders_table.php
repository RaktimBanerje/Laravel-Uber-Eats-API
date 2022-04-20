<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId("customer_id")->constrained()->onUpdate("cascade")->onDelete("cascade");
            $table->foreignId("restaurant_id")->constrained()->onUpdate("cascade")->onDelete("cascade");
            $table->unsignedBigInteger("approved_by")->nullable();
            $table->foreign("approved_by")->references("id")->on("users");
            $table->enum("status", ["PENDING", "PROCESSING", "SHIPPING", "DELIVERED"])->default("PENDING");
            $table->float("amount");
            $table->float("gst");
            $table->string("phone");
            $table->string("country");
            $table->string("state");
            $table->string("city");
            $table->string("area");
            $table->string("street");
            $table->string("pin");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
