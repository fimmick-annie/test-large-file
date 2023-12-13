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
class CreateCrmActionTablesTable extends Migration  {

	//----------------------------------------------------------------------------------------
	//  Run the migrations.
	public function up()  {
		Schema::create('crm_action_tables', function (Blueprint $table) {

			// Default columns
			$table->bigIncrements('id');
			$table->timestamps();
			$table->softDeletes();

			//----------------------------------------------------------------------------------------
			//  Columns for this class
			$table->string('created_by', 40);
			$table->string('updated_by', 40)->nullable();
			$table->string('deleted_by', 40)->nullable();

			$table->bigInteger('member_id')->nullable();
			$table->string('mobile')->nullable();
			$table->string('blasting_type', 16)->nullable();

			$table->bigInteger('brand_id')->nullable();
			$table->bigInteger('campaign_id')->nullable();
			$table->bigInteger('journey_id')->nullable();

			$table->string('journey_name', 32)->nullable();
			$table->string('message_id', 64);
			$table->text('content')->nullable();
			$table->integer('touch_point')->nullable();
			$table->string('content_day', 8)->nullable();

			$table->timestamp('schedule_at')->nullable();
			$table->timestamp('cancel_at')->nullable();
			$table->timestamp('action_at')->nullable();

			$table->string('send_result', 16)->nullable();
			$table->text('return_json')->nullable();
			$table->string('return_id', 64)->nullable();
			$table->string('return_status', 16)->nullable();

			$table->integer('open_count')->nullable();
			$table->integer('click_count')->nullable();

			$table->string('unique_code', 32)->nullable();
		});
	}

	//----------------------------------------------------------------------------------------
	//  Reverse the migrations.
	public function down()  {
		Schema::dropIfExists('crm_action_tables');
	}
}
