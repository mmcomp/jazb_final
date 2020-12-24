<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCallResultsTableAddNoCallandNoAnswer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('call_results', function (Blueprint $table) {
            $table->boolean('no_call')->default(false);
            $table->boolean('no_answer')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('call_results', function (Blueprint $table) {
            //
        });
    }
}
