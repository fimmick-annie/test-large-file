<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020.  All rights reserved.
//----------------------------------------------------------------------------------------

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

//========================================================================================
class AlterCampaignOfferAddCouponType extends Migration  {

	//----------------------------------------------------------------------------------------
	//  Run the migrations.
	public function up()  {
		Schema::table('campaign_offers', function (Blueprint $table)  {
			$table->string('coupon_type')->after('offer_code')
				->default("randomly-generated");
		});
	}


	//----------------------------------------------------------------------------------------
	//  Reverse the migrations.
	public function down()  {
		if (Schema::hasColumn('campaign_offers', 'coupon_type'))  {
			Schema::table('campaign_offers', function ($table)  {
				$table->dropColumn('coupon_type');
			});
		}
	}
}
