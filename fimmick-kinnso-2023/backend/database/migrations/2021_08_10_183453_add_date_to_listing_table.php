<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2021.  All rights reserved.
//----------------------------------------------------------------------------------------

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

//========================================================================================
class AddDateToListingTable extends Migration  {

	//----------------------------------------------------------------------------------------
	//  Run the migrations.
	public function up()  {
		Schema::table("campaign_listings", function (Blueprint $table)  {
			$table->timestamp("start_at")->after("ordering")->nullable();
			$table->timestamp("end_at")->after("start_at")->nullable();
		});
	}

	//----------------------------------------------------------------------------------------
	//  Reverse the migrations.
	public function down()  {
		if (Schema::hasColumn("campaign_listings", "ml_labels"))  {
			Schema::table("campaign_listings", function ($table)  {
				$table->dropColumn("start_at");
				$table->dropColumn("end_at");
			});
		}
	}

}
