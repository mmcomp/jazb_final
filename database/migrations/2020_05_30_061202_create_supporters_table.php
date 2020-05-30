<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supporters', function (Blueprint $table) {
            $table->id();
            $table->integer('users_id')->default(0);
            $table->string('first_name', 255)->nullable();
            $table->string('last_name', 255);
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->string('national_code', 20)->nullable();
            $table->string('egucation', 255)->nullable();
            $table->string('major', 255)->nullable();
            $table->string('home_phone', 20)->nullable();
            $table->string('cell_phone', 20)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('work', 255)->nullable();
            $table->integer('maximum_student')->default(1);
            $table->string('image_path', 255)->nullable();
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
        Schema::dropIfExists('supporters');
    }
}
