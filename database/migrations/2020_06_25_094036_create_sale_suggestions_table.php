<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleSuggestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_suggestions', function (Blueprint $table) {
            $table->id();
            $table->integer('if_products_id')->nullable();
            $table->integer('if_moral_tags_id')->nullable();
            $table->integer('if_need_tags_id')->nullable();
            $table->integer('if_schools_id')->nullable();
            $table->integer('if_last_year_grade')->nullable();
            $table->integer('if_avarage')->nullable();
            $table->integer('if_sources_id')->nullable();
            $table->integer('then_product1_id')->nullable();
            $table->integer('then_product2_id')->nullable();
            $table->integer('then_product3_id')->nullable();
            $table->integer('users_id');
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
        Schema::dropIfExists('sale_suggestions');
    }
}
