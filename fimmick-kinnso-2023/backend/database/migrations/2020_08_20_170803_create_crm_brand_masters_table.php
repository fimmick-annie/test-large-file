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
class CreateCrmBrandMastersTable extends Migration  {

	//----------------------------------------------------------------------------------------
	//  Run the migrations.
	public function up()  {
		Schema::create('crm_brand_masters', function (Blueprint $table) {
			// Default columns
			$table->bigIncrements('id');
			$table->timestamps();
			$table->softDeletes();

			//----------------------------------------------------------------------------------------
			//  Columns for this class
			$table->string('created_by', 40);
			$table->string('updated_by', 40)->nullable();
			$table->string('deleted_by', 40)->nullable();

			$table->string('brand_name', 32)->nullable();
			$table->string('brand_type', 16)->nullable();
		});
	}

	//----------------------------------------------------------------------------------------
	//  Reverse the migrations.
	public function down()  {
		Schema::dropIfExists('crm_brand_masters');
	}
}
