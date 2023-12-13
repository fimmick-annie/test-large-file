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
class CreateLineWebhooksTable extends Migration  {

	//  Global variables
	public $_tableNameArray = array(
		"line_webhooks",
		"line_webhook_archives",
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
				$table->string('vendor', 24)->nullable();
				$table->string('message_id', 64)->nullable();

				//  Status = Current status summary, for easy query
				$table->string('status', 16)->nullable();
				$table->text('content')->nullable();
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
