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
class CreateCouponPoolTable extends Migration  {

	//----------------------------------------------------------------------------------------
	//  Run the migrations.
	public function up()  {
		Schema::create('campaign_coupon_pool', function (Blueprint $table)  {

			// Default columns
			$table->bigIncrements('id');
			$table->timestamps();
			$table->softDeletes();

			//----------------------------------------------------------------------------------------
			//  Columns for this class
			$table->string('created_by', 32)->nullable();
			$table->string('updated_by', 32)->nullable();
			$table->string('deleted_by', 32)->nullable();

			$table->unsignedBigInteger('offer_id')->default(0);
			$table->string('store_code', 16)->default('uat-store-01');

			$table->string('unique_code', 32)->nullable()->collation('utf8_bin');
			$table->string('mobile', 24)->nullable();

			$table->string('unique_name', 32)->nullable()->collation('utf8_bin');

			$table->string('parameter_a', 32)->nullable();
			$table->string('parameter_b', 64)->nullable();
			$table->text('parameter_c')->nullable();
		});
	}

	//----------------------------------------------------------------------------------------
	//  Reverse the migrations.
	public function down()  {
		Schema::dropIfExists('campaign_coupon_pool');
	}
}
