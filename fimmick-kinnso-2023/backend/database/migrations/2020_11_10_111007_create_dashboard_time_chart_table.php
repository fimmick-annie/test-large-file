<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDashboardTimeChartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dashboard_time_charts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();
            //  Columns for this class
            // $table->timestamp('record_date');
            $table->unsignedInteger('offer_id');
            $table->unsignedInteger('time_slot_1');
            $table->unsignedInteger('time_slot_2');
            $table->unsignedInteger('time_slot_3');
            $table->unsignedInteger('time_slot_4');
            $table->unsignedInteger('time_slot_5');
            $table->unsignedInteger('time_slot_6');
            $table->unsignedInteger('time_slot_7');
            $table->unsignedInteger('time_slot_8');
            $table->unsignedInteger('time_slot_9');
            $table->unsignedInteger('time_slot_10');
            $table->unsignedInteger('time_slot_11');
            $table->unsignedInteger('time_slot_12');
            $table->unsignedInteger('time_slot_13');
            $table->unsignedInteger('time_slot_14');
            $table->unsignedInteger('time_slot_15');
            $table->unsignedInteger('time_slot_16');
            $table->unsignedInteger('time_slot_17');
            $table->unsignedInteger('time_slot_18');
            $table->unsignedInteger('time_slot_19');
            $table->unsignedInteger('time_slot_20');
            $table->unsignedInteger('time_slot_21');
            $table->unsignedInteger('time_slot_22');
            $table->unsignedInteger('time_slot_23');
            $table->unsignedInteger('time_slot_24');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dashboard_time_chart');
    }
}
