<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketers', function (Blueprint $table) {
            $table->id();
            $table->integer("users_id");
            $table->string("first_name", 255)->nullable();
            $table->string("last_name", 255);
            $table->string("cell_phone", 20)->nullable();
            $table->string("national_code", 10)->nullable();
            $table->dateTime("birthdate")->nullable();
            $table->integer("cities_id")->default(0);
            $table->string("address", 255)->nullable();
            $table->string("home_phone", 20)->nullable();
            $table->string("bank_card", 255)->nullable();
            $table->string("bank_shaba", 255)->nullable();
            $table->string("image_path", 255)->nullable();
            $table->text("background")->nullable();
            $table->string("education", 255)->nullable();
            $table->string("major", 255)->nullable();
            $table->string("university", 255)->nullable();
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
        Schema::dropIfExists('marketers');
    }
}
