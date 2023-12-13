<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCampaignOfferTableTnc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaign_offers', function (Blueprint $table) {
			//  Use RAW query because no need to install doctrine/dbal package
			DB::statement("ALTER TABLE `campaign_offers` MODIFY `tnc` MEDIUMTEXT ;");

		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaign_offers', function (Blueprint $table) {

			//  Use RAW query because no need to install doctrine/dbal package
			DB::statement("ALTER TABLE `campaign_offers` MODIFY `tnc` text;");

		});
    }
}
