<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 255)->nullable();
            $table->string('last_name', 255);
            $table->string('last_year_grade')->nullable();
            $table->integer('consultants_id')->default(0);
            $table->string('parents_job_title', 255)->nullable();
            $table->string('home_phone', 20)->nullable();
            $table->enum('egucation_level', ['10', '11', '12'])->default('12');
            $table->string('father_phone', 20)->nullable();
            $table->string('mother_phone', 20)->nullable();
            $table->string('phone', 20)->index();
            $table->string('school', 255)->nullable();
            $table->double('average')->default(-1);
            $table->enum('major', ['mathematics', 'experimental', 'humanities'])->default('experimental');
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
        Schema::dropIfExists('students');
    }
}
