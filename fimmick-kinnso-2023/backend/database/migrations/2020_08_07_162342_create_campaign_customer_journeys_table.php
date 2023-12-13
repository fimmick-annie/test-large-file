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
class CreateCampaignCustomerJourneysTable extends Migration  {

	//  Global variables
	public $_tableNameArray = array(
		"campaign_customer_journeys",
		"campaign_customer_journey_archives",
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
				$table->unsignedBigInteger('ordering')->default(100);

				//  Node name, no space, say "T+0"
				$table->string('node_name', 48)->default("");

				//  100 = Message (media+text) node
				//  200 = Question node
				//  300 = Logic node
				$table->unsignedInteger('type')->default(0);

				$table->json('node_settings')->nullable();

				//  Above structure must match with campaign_master_journeys table
				$table->string('mobile', 24)->nullable();
				$table->string('email', 48)->nullable();

				//  For Facebook / WeChat / Others' ID
				$table->string('user_id', 64)->nullable();

				$table->timestamp('canceled_at')->nullable();
				$table->timestamp('triggered_at')->nullable();
				$table->timestamp('completed_at')->nullable();
				$table->json('node_data')->nullable();

				$table->index(['mobile']);
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
