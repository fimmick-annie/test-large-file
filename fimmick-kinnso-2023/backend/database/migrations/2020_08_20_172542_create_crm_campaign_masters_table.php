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
class CreateCrmCampaignMastersTable extends Migration  {

	//----------------------------------------------------------------------------------------
	//  Run the migrations.
	public function up()  {
		Schema::create('crm_campaign_masters', function (Blueprint $table) {
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
			$table->string('brand_name', 32)->nullable();

			$table->string('campaign_name', 32)->nullable();
			$table->string('campaign_type', 32)->nullable();
			$table->text('campaign_desc')->nullable();

			$table->timestamp('start_date')->nullable();
			$table->timestamp('end_date')->nullable();
		});
	}

	//----------------------------------------------------------------------------------------
	//  Reverse the migrations.
	public function down()  {
		Schema::dropIfExists('crm_campaign_masters');
	}
}
