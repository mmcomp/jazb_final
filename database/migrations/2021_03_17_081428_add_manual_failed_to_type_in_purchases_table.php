<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class AddManualFailedToTypeInPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function () {
            DB::statement("ALTER TABLE purchases MODIFY COLUMN type ENUM('site_failed', 'site_successed', 'manual','manual_failed') DEFAULT 'site_failed'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function () {
            DB::statement("ALTER TABLE purchases MODIFY COLUMN type ENUM('site_failed', 'site_successed', 'manual') DEFAULT 'site_failed'");
        });
    }
}
