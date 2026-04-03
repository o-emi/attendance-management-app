<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorrectionRequestBreakTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('correction_request_break_times', function (Blueprint $table) {
            $table->id();

            $table->foreignId('correction_request_id')
            ->constrained()
            ->cascadeOnDelete();

            $table->timestamp('break_start');
            $table->timestamp('break_end')->nullable();

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
        Schema::dropIfExists('correction_request_break_times');
    }
}
