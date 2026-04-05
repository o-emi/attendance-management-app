<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeBreakColumnsTypeOnCorrectionRequestBreakTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('correction_request_break_times', function (Blueprint $table) {
            $table->time('break_start')->change();
            $table->time('break_end')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('correction_request_break_times', function (Blueprint $table) {
            $table->timestamp('break_start')->change();
            table->timestamp('break_end')->nullable()->change();
        });
    }
}
