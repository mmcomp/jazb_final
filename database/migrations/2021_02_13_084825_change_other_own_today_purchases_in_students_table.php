<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeOtherOwnTodayPurchasesInStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->renameColumn('ownPurchases','own_purchases');
            $table->renameColumn('otherPurchases','other_purchases');
            $table->renameColumn('todayPurchases','today_purchases');
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
            $table->renameColumn('own_purchases','ownPurchases');
            $table->renameColumn('other_purchases','otherPurchases');
            $table->renameColumn('today_purchases','todayPurchases');
        });
    }
}
