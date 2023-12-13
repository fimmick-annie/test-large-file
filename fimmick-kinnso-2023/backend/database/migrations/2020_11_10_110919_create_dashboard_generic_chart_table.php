<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDashboardGenericChartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dashboard_generic_charts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();
            //  Columns for this class
            $table->string('record_date',24);
            $table->unsignedInteger('offer_id');
            $table->unsignedInteger('number_of_users');
            $table->unsignedInteger('number_of_coupons_issued');
            $table->unsignedInteger('number_of_coupons_used');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dashboard_generic_chart');
    }
}
