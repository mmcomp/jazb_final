<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeUsersTableAddExtra extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->string('national_code', 20)->nullable();
            $table->string('education', 255)->nullable();
            $table->string('major', 255)->nullable();
            $table->string('home_phone', 20)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('work_mobile', 20)->nullable();
            $table->string('home_address', 255)->nullable();
            $table->string('work_address', 255)->nullable();
            $table->integer('max_student')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
