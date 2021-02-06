<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeIfSchoolsIdTypeToVarcharSaleSuggestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_suggestions', function (Blueprint $table) {
            $table->string('if_schools_id')->nullable()->change();
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
            $table->integer('if_schools_id')->nullable()->change();
        });
    }
}
