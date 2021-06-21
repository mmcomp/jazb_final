<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeEnumTypeOfExcelInPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::statement("ALTER TABLE purchases MODIFY COLUMN type ENUM('site_failed', 'site_successed', 'manual','manual_failed','excel_import') DEFAULT 'site_failed'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        DB::statement("ALTER TABLE purchases MODIFY COLUMN type ENUM('site_failed', 'site_successed', 'manual','manual_failed') DEFAULT 'site_failed'");
    }
}
