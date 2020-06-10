<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMarketerCityProvince extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('marketers', function (Blueprint $table) {
            $table->dropColumn('cities_id');
            $table->string('city', 255)->nullable();
            $table->integer('provinces_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('marketers', function (Blueprint $table) {
            //
        });
    }
}
