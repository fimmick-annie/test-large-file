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
class CreateChatbotDailyQuestionPoolsTable extends Migration  {

	//----------------------------------------------------------------------------------------
	//  Run the migrations.
	public function up()  {
		Schema::create('chatbot_daily_question_pools', function (Blueprint $table)  {

			//  Default columns
			$table->bigIncrements('id');
			$table->timestamps();
			$table->softDeletes();

			//----------------------------------------------------------------------------------------
			//  Columns for this class
			$table->unsignedTinyInteger('layer')->default(1);

			//  同朋友聚會，一般人均消費大約？請選擇
			//  1 - $100-$200
			//  2 - $201-$400
			//  3 - $500以上
			$table->text('question')->nullable();

			//  Support multiple options and languages, labels and also points for the answers
			$table->json('answers')->nullable();
			$table->string('answer_expiry_at', 16)->default("+24 hours");

			//  Question available period
			$table->timestamp('started_at')->nullable();
			$table->timestamp('ended_at')->nullable();

			$table->unsignedInteger('point')->default(0);
			$table->unsignedInteger('coupon_id')->default(0);
			$table->unsignedInteger('gift_id')->default(0);

			//  Larger value means much heavy, will pick first
			$table->unsignedInteger('weight')->default(100);
		});
	}

	//----------------------------------------------------------------------------------------
	//  Reverse the migrations.
	public function down()  {
		Schema::dropIfExists('chatbot_daily_question_pools');
	}
}
