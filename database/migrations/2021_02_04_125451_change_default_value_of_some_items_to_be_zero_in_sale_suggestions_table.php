<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDefaultValueOfSomeItemsToBeZeroInSaleSuggestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_suggestions', function (Blueprint $table) {
            $table->integer('if_last_year_grade')->default(0)->change();
            $table->integer('if_avarage')->default(0)->change();
            $table->integer('if_sources_id')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_suggestions', function (Blueprint $table) {
            $table->integer('if_last_year_grade')->nullable()->change();
            $table->integer('if_avarage')->nullable()->change();
            $table->integer('if_sources_id')->nullable()->change();
        });
    }
}
