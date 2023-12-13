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

use App\Models\CampaignOffer;

//========================================================================================
class CreateCampaignOffersTable extends Migration  {

	//----------------------------------------------------------------------------------------
	//  Run the migrations.
	public function up()  {
		Schema::create('campaign_offers', function (Blueprint $table)  {

			//  Default columns
			$table->bigIncrements('id');
			$table->timestamps();
			$table->softDeletes();

			//----------------------------------------------------------------------------------------
			//  Columns for this class
			$table->timestamp('start_at')->nullable();
			$table->timestamp('end_at')->nullable();

			$table->string('offer_code', 32)->default('UAT')->unqiue();
			$table->string('offer_name', 32)->default('offer-uat');

			$table->string('offer_title', 64)->default('Offer for UAT');
			$table->string('offer_subtitle', 64)->default('');

			$table->string('blade_folder', 48)->default('default_offer_template');

			//  Code type:
			//  static = Static image, all coupons are using same image
			//  unique = Unique image, every coupon has its own image
			//  [{
			//	  'channel': 'mannings',
			//	  'type': 'static',
			//	  'image_name': 'barcode_1.jpg'
			//  }, {
			//	  'channel': 'crcare',
			//	  'type': 'static',
			//	  'image_name': 'barcode_2.jpg'
			//  }, {
			//	  'channel': 'watsons',
			//	  'type': 'unqiue'
			//  }]
			$table->json('code_type')->nullable();

			//  Channel name and its expiry date:
			//  strtotime('+3 days')
			//  strtotime('2020-12-31 23:59:59')
			//  {
			//		'renew_duration': '+7 days'
			//      'default': '2020-12-31 23:59:59',       // For getting offer but channel not selected yet
			//      'mannings': '+30 mins',
			//      'watsons': '+60 mins',
			//      'crcare': '2020-12-31 23:59:59'
			//  }
			$table->json('channel_expiry')->nullable();

			//  How to communicate with users?
			$table->json('confirmation_method')->nullable();

			$table->integer('quota')->default(0);
			$table->integer('quota_issued')->default(0);

			$table->text('tnc')->nullable();

			//  Comma separated IDs
			$table->string('bundled_offers_id', 16)->nullable();

			$table->json('tracking_code')->nullable();
			$table->json('webhook')->nullable();
			$table->json('statistic_data')->nullable();
		});
	}

	//----------------------------------------------------------------------------------------
	//  Reverse the migrations.
	public function down()  {
		Schema::dropIfExists('campaign_offers');
	}
}
