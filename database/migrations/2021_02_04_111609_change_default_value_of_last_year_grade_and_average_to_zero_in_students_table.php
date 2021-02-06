<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\Type;


class ChangeDefaultValueOfLastYearGradeAndAverageToZeroInStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Type::hasType('double')) {
            Type::addType('double', FloatType::class);
        }
        Schema::table('students', function (Blueprint $table) {
            $table->string('last_year_grade')->default('0')->change();
            $table->double('average')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Type::hasType('double')) {
            Type::addType('double', FloatType::class);
        }
        Schema::table('students', function (Blueprint $table) {
            $table->string('last_year_grade')->nullable()->change();
            $table->double('average')->default(-1)->change();
        });
    }
}
