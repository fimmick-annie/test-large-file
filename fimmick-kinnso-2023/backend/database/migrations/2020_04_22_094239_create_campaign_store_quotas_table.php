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

use App\Models\CampaignStoreQuota;

//========================================================================================
class CreateCampaignStoreQuotasTable extends Migration  {

	//----------------------------------------------------------------------------------------
	//  Run the migrations.
	public function up()  {
		Schema::create('campaign_store_quotas', function (Blueprint $table)  {

			//  Default columns
			$table->bigIncrements('id');
			$table->timestamps();
			$table->softDeletes();

			//----------------------------------------------------------------------------------------
			//  Columns for this class
			$table->string('created_by', 32)->nullable();
			$table->string('updated_by', 32)->nullable();
			$table->string('deleted_by', 32)->nullable();

			$table->unsignedBigInteger('offer_id')->default(0);

			//  Quota period
			$table->timestamp('start_at')->nullable();
			$table->timestamp('end_at')->nullable();

			$table->string('store_code', 16)->default('uat-store');
			$table->integer('ordering')->default(100);

			$table->unsignedInteger('quota')->default(0);
			$table->unsignedInteger('quota_issued')->default(0);

			//  Put store name and addresss in table because somehow store will be moved
			//  We can input those values and it will be changed automatically
			$table->string('store_name', 64)->default('UAT Store');
			$table->text('store_address')->nullable();
		});
	}

	//----------------------------------------------------------------------------------------
	//  Reverse the migrations.
	public function down()  {
		Schema::dropIfExists('campaign_store_quotas');
	}
}
