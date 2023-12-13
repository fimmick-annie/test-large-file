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
//  This table is used by CRM Data Team
class CreateCrmWhatsappOutboundMessagesTable extends Migration  {

	//----------------------------------------------------------------------------------------
	//  Run the migrations.
	public function up()  {
		Schema::create('crm_whatsapp_outbound_messages', function (Blueprint $table) {

			//  Default columns
			$table->bigIncrements('id');
			$table->timestamps();
			$table->softDeletes();

			//----------------------------------------------------------------------------------------
			//  Columns for this class
			$table->string('created_by', 40);
			$table->string('updated_by', 40)->nullable();
			$table->string('deleted_by', 40)->nullable();

			$table->string('message_id', 64);
			$table->string('member_id', 16);
			$table->text('content')->nullable();
			$table->string('send_result', 16)->nullable();
			$table->string('return_status', 16)->nullable();

			$table->unsignedTinyInteger('open_count')->default(0);
			$table->unsignedTinyInteger('click_count')->default(0);
		});
	}

	//----------------------------------------------------------------------------------------
	//  Reverse the migrations.
	public function down()  {
		Schema::dropIfExists('crm_whatsapp_outbound_messages');
	}
}
