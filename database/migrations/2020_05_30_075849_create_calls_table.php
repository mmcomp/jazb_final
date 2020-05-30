<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calls', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->enum('result', ['no_answer', 'unsuccessful', 'successful', 'rejected'])->default('no_answer');
            $table->integer('student_id');
            $table->integer('users_id');
            $table->integer('products_id');
            $table->enum('replier', ['student', 'father', 'mother', 'other'])->default('student');
            $table->dateTime('next_call')->nullable();
            $table->enum('next_to_call', ['student', 'father', 'mother', 'other'])->default('student');
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
        Schema::dropIfExists('calls');
    }
}
