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

use App\Models\CampaignCoupon;

//========================================================================================
class CreateCampaignCouponsTable extends Migration  {

	public $_tableNameArray = array(
		"campaign_coupons",
		"campaign_coupon_archives",
	);

	//----------------------------------------------------------------------------------------
	//  Run the migrations.
	public function up()  {
		foreach ($this->_tableNameArray as $tableName)  {
			Schema::create($tableName, function (Blueprint $table)  {

				//  Default columns
				$table->bigIncrements('id');
				$table->timestamps();
				$table->softDeletes();

				//----------------------------------------------------------------------------------------
				//  Columns for this class
				$table->unsignedBigInteger('offer_id')->default(0);
				$table->unsignedBigInteger('parent_offer_id')->default(0);
				$table->unsignedBigInteger('coupon_order')->default(100);

				//  If null then generate QR code with unique_code as content
				$table->string('unique_code', 32)->nullable()->collation('utf8_bin');

				$table->string('mobile', 24)->nullable();
				$table->string('email', 48)->nullable();

				$table->timestamp('start_at')->nullable();
				$table->timestamp('use_at')->nullable();
				$table->timestamp('expiry_at')->nullable();

				//  Selected channel name
				//  mannings, watsons, crcare, circle-k
				$table->string('selected_channel', 16)->nullable();

				$table->json('form_data')->nullable();

				//  Fields for referral
				$table->string('referrer_code', 32)->nullable()->collation('utf8_bin');
				$table->string('referral_code', 32)->nullable()->collation('utf8_bin');
				$table->json('referral_data')->nullable();
			});
		}
	}

	//----------------------------------------------------------------------------------------
	//  Reverse the migrations.
	public function down()  {
		foreach ($this->_tableNameArray as $tableName)  {
			Schema::dropIfExists($tableName);
		}
	}
}
