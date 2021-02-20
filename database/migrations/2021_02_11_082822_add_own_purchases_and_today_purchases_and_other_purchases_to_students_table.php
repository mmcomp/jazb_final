<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOwnPurchasesAndTodayPurchasesAndOtherPurchasesToStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->integer('ownPurchases')->default(0)->unsigned();
            $table->integer('otherPurchases')->default(0)->unsigned();
            $table->integer('todayPurchases')->default(0)->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('ownPurchases');
            $table->dropColumn('otherPurchases');
            $table->dropColumn('todayPurchases');
        });
    }
}
