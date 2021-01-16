<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMiddleTableForMergedStudents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('middle_table_for_merged_students', function (Blueprint $table) {
            $table->id();
            $table->integer('main_user_id')->nullable();
            $table->integer('auxilary_user_id')->nullable();
            $table->integer('second_auxilary_user_id')->nullable();
            $table->integer('third_auxilary_user_id')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('middle_table_for_merged_students');
    }
}
