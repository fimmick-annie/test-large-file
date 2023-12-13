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
class CreateCampaignCouponLinkRequestsTable extends Migration  {

	//----------------------------------------------------------------------------------------
	//  Run the migrations.
	public function up()  {
		Schema::create('campaign_coupon_link_requests', function (Blueprint $table) {

			//  Default columns
			$table->bigIncrements('id');
			$table->timestamps();
			$table->softDeletes();

			//----------------------------------------------------------------------------------------
			//  Columns for this class
			$table->unsignedBigInteger('offer_id')->default(0);

			$table->string('mobile', 24)->nullable();
			$table->string('email', 48)->nullable();

			//  For Facebook / WeChat / Others' ID
			$table->string('user_id', 64)->nullable();

			$table->string('ip_address', 16)->nullable();
		});
	}

	//----------------------------------------------------------------------------------------
	//  Reverse the migrations.
	public function down()  {
		Schema::dropIfExists('campaign_coupon_link_requests');
	}
}
