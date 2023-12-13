<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020-2021.  All rights reserved.
//----------------------------------------------------------------------------------------

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

//========================================================================================
class AlterMemberTableAddLabelsField extends Migration  {

	//----------------------------------------------------------------------------------------
	//  Run the migrations.
	public function up()  {
		Schema::table('members', function (Blueprint $table) {
			$table->text("ml_labels")->nullable();
		});
	}

	//----------------------------------------------------------------------------------------
	//  Reverse the migrations.
	public function down()  {
		if (Schema::hasColumn("members", "ml_labels"))  {
			Schema::table("members", function ($table)  {
				$table->dropColumn("ml_labels");
			});
		}
	}
}
