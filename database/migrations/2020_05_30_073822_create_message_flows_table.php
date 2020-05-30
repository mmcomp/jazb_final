<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_flows', function (Blueprint $table) {
            $table->id();
            $table->integer('messages_id');
            $table->integer('users_id');
            $table->integer('sender_id');
            $table->enum('type', ['main', 'cc', 'bcc'])->default('main');
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
        Schema::dropIfExists('message_flows');
    }
}
