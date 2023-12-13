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
class CreateCampaignWhatsappMessageQueuesTable extends Migration  {

	//  Global variables
	public $_tableNameArray = array(
		"campaign_whatsapp_message_queues",
		"campaign_whatsapp_message_queue_archives",
	);

	//----------------------------------------------------------------------------------------
	//  Run the migrations.
	public function up()  {
		foreach ($this->_tableNameArray as $tableName)  {
			Schema::create($tableName, function (Blueprint $table) {

				//  Default columns
				$table->bigIncrements('id');
				$table->timestamps();
				$table->softDeletes();

				//----------------------------------------------------------------------------------------
				//  Columns for this class
				$table->string('created_by', 32)->nullable();
				$table->string('updated_by', 32)->nullable();

				$table->unsignedBigInteger('offer_id')->default(0);
				$table->unsignedBigInteger('coupon_id')->default(0);
				$table->unsignedInteger('priority')->default(100);

				$table->string('mobile_from', 24)->nullable();
				$table->string('mobile', 24)->nullable();
				$table->text('message')->nullable();
				$table->string('media', 240)->nullable();
				$table->string('message_type', 48)->nullable();

				$table->timestamp('schedule_at')->nullable();
				$table->timestamp('expiry_at')->nullable();
				$table->timestamp('cancel_at')->nullable();
				$table->timestamp('send_at')->nullable();

				$table->string('vendor', 24)->nullable();
				$table->string('message_id', 64)->nullable();

				//  Status = Current status summary, for easy query
				$table->string('status', 16)->nullable();
				$table->text('response')->nullable();
				$table->text('delivery_receipt')->nullable();

				$table->string('cost', 16)->nullable();

				$table->index(['mobile']);
				$table->index(['message_id']);
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
