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
class CreateMarketingListsTable extends Migration  {

	//----------------------------------------------------------------------------------------
	//  Run the migrations.
	public function up()  {
		Schema::create('marketing_lists', function (Blueprint $table) {

			// Default columns
			$table->bigIncrements('id');
			$table->timestamps();
			$table->softDeletes();

			//----------------------------------------------------------------------------------------
			//  Columns for this class
			$table->string('list_name', 48);
			$table->string('mobile', 24);

			$table->string('username', 48)->nullable();
			$table->string('parameter_a', 32)->nullable();
			$table->string('parameter_b', 64)->nullable();
			$table->text('parameter_c')->nullable();

			$table->unique(['list_name', 'mobile']);
		});
	}

	//----------------------------------------------------------------------------------------
	//  Reverse the migrations.
	public function down()  {
		Schema::dropIfExists('marketing_lists');
	}
}
