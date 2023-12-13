<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020-2022.  All rights reserved.
//----------------------------------------------------------------------------------------

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

//========================================================================================
class AlterMemberTableHoneyPoints extends Migration  {

	//----------------------------------------------------------------------------------------
	//  Run the migrations.
	public function up()  {
		Schema::table('members', function (Blueprint $table) {
			$table->string("referral_code", 16)->nullable();
			$table->unsignedInteger("referrer_id")->default(0);
			// $table->unsignedInteger("point_balance")->default(0); -- Kay 2022.08.17 let the point balance be signed
			$table->integer("point_balance")->default(0);
		});
	}

	//----------------------------------------------------------------------------------------
	//  Reverse the migrations.
	public function down()  {
		if (Schema::hasColumn("members", "point_balance"))  {
			Schema::table("members", function ($table)  {
				$table->dropColumn("point_balance");
			});
		}

		if (Schema::hasColumn("members", "referrer_id"))  {
			Schema::table("members", function ($table)  {
				$table->dropColumn("referrer_id");
			});
		}

		if (Schema::hasColumn("members", "referral_code"))  {
			Schema::table("members", function ($table)  {
				$table->dropColumn("referral_code");
			});
		}
	}
}
