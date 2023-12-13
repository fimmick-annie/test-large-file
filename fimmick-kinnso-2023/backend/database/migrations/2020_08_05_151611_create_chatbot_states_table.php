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
class CreateChatbotStatesTable extends Migration  {

	//----------------------------------------------------------------------------------------
	//  Run the migrations.
	public function up()  {
		Schema::create('chatbot_states', function (Blueprint $table)  {

			//  Default columns
			$table->bigIncrements('id');
			$table->timestamps();
			$table->softDeletes();

			//----------------------------------------------------------------------------------------
			//  Columns for this class
			$table->string('mobile', 24)->nullable();
			$table->string('email', 48)->nullable();

			//  For Facebook / WeChat / Others' ID
			$table->string('user_id', 64)->nullable();

			//  Communication channel
			//  WhatsApp / Facebook / WeChat / Others
			$table->string('channel', 16)->default("");

			//  Master / Coupon / Stamp /...etc.
			$table->string('branch', 16)->default("");

			//  Below field is a place to share values between chatbot children
			$table->json('chatbot_data')->nullable();
		});
	}

	//----------------------------------------------------------------------------------------
	//  Reverse the migrations.
	public function down()  {
		Schema::dropIfExists('chatbot_states');
	}
}
