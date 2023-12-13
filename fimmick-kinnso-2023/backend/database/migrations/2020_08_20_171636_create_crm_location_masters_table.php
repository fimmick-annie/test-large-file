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
class CreateCrmLocationMastersTable extends Migration  {

	//----------------------------------------------------------------------------------------
	//  Run the migrations.
	public function up()  {
		Schema::create('crm_location_masters', function (Blueprint $table) {
			// Default columns
			$table->bigIncrements('id');
			$table->timestamps();
			$table->softDeletes();

			//----------------------------------------------------------------------------------------
			//  Columns for this class
			$table->string('created_by', 40);
			$table->string('updated_by', 40)->nullable();
			$table->string('deleted_by', 40)->nullable();

			$table->string('location_code', 16);
			$table->string('category', 16)->nullable();
			$table->string('area', 24)->nullable();

			$table->string('name_tc', 24)->nullable();
			$table->string('name_en', 24)->nullable();
			$table->string('address_tc')->nullable();
			$table->string('address_en')->nullable();

			$table->string('geo_code', 32)->nullable();
			$table->integer('mobile')->nullable();
			$table->bigInteger('brand_id')->nullable();
			$table->string('channel', 32)->nullable();

		});
	}

	//----------------------------------------------------------------------------------------
	//  Reverse the migrations.
	public function down()  {
		Schema::dropIfExists('crm_location_masters');
	}
}
