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
class CreateCrmQuotaTablesTable extends Migration  {

	//----------------------------------------------------------------------------------------
	//  Run the migrations.
	public function up()  {
		Schema::create('crm_quota_tables', function (Blueprint $table) {
			// Default columns
			$table->bigIncrements('id');
			$table->timestamps();
			$table->softDeletes();

			//----------------------------------------------------------------------------------------
			//  Columns for this class
			$table->string('created_by', 40);
			$table->string('updated_by', 40)->nullable();
			$table->string('deleted_by', 40)->nullable();

			$table->bigInteger('brand_id')->nullable();
			$table->bigInteger('campaign_id')->nullable();
			$table->bigInteger('offer_id')->nullable();

			$table->timestamp('start_date')->nullable();
			$table->timestamp('end_date')->nullable();
			$table->timestamp('expiry_date')->nullable();

			$table->integer('quota')->default(0);
			$table->integer('remaining_quota')->default(0);

			$table->string('location_code', 16);
			$table->string('gift_name', 32)->nullable();
			$table->text('gift_desc', 32)->nullable();
		});
	}

	//----------------------------------------------------------------------------------------
	//  Reverse the migrations.
	public function down()  {
		Schema::dropIfExists('crm_quota_tables');
	}
}
