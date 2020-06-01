<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeUsersTableAddMissingFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('introducing', 255)->nullable();
            $table->string('student_phone', 255)->nullable();
            $table->integer('sources_id')->default(0);
            $table->integer('citys_id')->default(0);
            $table->integer('supporters_id')->default(0);
            $table->integer('users_id')->default(0);
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
