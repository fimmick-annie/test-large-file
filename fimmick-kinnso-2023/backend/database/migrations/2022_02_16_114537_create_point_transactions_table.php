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
class CreatePointTransactionsTable extends Migration  {

	//----------------------------------------------------------------------------------------
	//  Run the migrations.
	public function up()  {
		Schema::create('point_transactions', function (Blueprint $table)  {

			//  Default columns
			$table->bigIncrements('id');
			$table->timestamps();
			$table->softDeletes();

			//----------------------------------------------------------------------------------------
			//  Columns for this class
			$table->unsignedBigInteger('member_id')->default(0);
			$table->integer('delta_points')->default(0);

			//  Null means never expire
			$table->timestamp('valid_at')->useCurrent();
			$table->timestamp('expiry_at')->nullable();

			//  admin
			//  daily_question
			//  redemption
			$table->string('transaction_type', 20)->nullable();

			//  Support multiple language
			$table->json('description')->nullable();
		});
	}

	//----------------------------------------------------------------------------------------
	//  Reverse the migrations.
	public function down()  {
		Schema::dropIfExists('point_transactions');
	}
}
