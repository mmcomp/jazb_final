<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeIfProductsIdAndIfNeedTagsIdAndIfMoralTagsIdToVarcharSaleSuggestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_suggestions', function (Blueprint $table) {
            $table->string('if_products_id')->nullable()->change();
            $table->string('if_moral_tags_id')->nullable()->change();
            $table->string('if_need_tags_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_suggestions', function (Blueprint $table) {
            $table->integer('if_products_id')->nullable()->change();
            $table->integer('if_moral_tags_id')->nullable()->change();
            $table->integer('if_need_tags_id')->nullable()->change();
        });
    }
}
