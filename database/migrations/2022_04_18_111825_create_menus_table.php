<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("slug");
            $table->string('description');
            $table->string("unit_name");
            $table->float("unit_count");
            $table->float("price");
            $table->string("ingredients");
            $table->string("image_url");
            $table->string("hsn_code");
            $table->boolean("is_active")->default(0);
            $table->boolean("is_available")->default(0);
            $table->foreignId("restaurant_id")->constrained()->onUpdate("cascade")->onDelete("cascade");
            $table->foreignId("category_id")->constrained()->onUpdate("cascade")->onDelete("cascade");;
            $table->unsignedBigInteger("approved_by")->nullable();
            $table->foreign("approved_by")->references("id")->on("users");
            $table->boolean("is_processing")->default(1);
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
        Schema::dropIfExists('menus');
    }
}
