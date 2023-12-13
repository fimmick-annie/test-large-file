<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignListingsListTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_listings_list_tables', function (Blueprint $table) {
			//  Default columns
			$table->bigIncrements('id');
			$table->timestamps();
			$table->softDeletes();

            $table->string('list_name')->default('default');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_listings_list_tables');
    }
}
