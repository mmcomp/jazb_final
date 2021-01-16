<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMiddleTableForMergedStudents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('middle_table_for_merged_students', function (Blueprint $table) {
            $table->dropColumn('second_auxilary_user_id');
            $table->dropColumn('third_auxilary_user_id');
            $table->renameColumn('main_user_id','main_students_id');
            $table->renameColumn('auxilary_user_id','auxilary_students_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('middle_table_for_merged_students', function (Blueprint $table) {
            $table->integer('second_auxilary_user_id')->nullable();
            $table->integer('third_auxilary_user_id')->nullable();
            $table->renameColumn('main_students_id','main_user_id');
            $table->renameColumn('auxilary_students_id','auxilary_user_id');
        });
    }
}
