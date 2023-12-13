<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020.  All rights reserved.
//----------------------------------------------------------------------------------------

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//========================================================================================
class CreateAuditsTable extends Migration  {

	//----------------------------------------------------------------------------------------
	//  Run the migrations.
	public function up()  {
		Schema::create('audits', function (Blueprint $table) {
			$table->increments('id');
			$table->string('user_type')->nullable();
			$table->unsignedBigInteger('user_id')->nullable();
			$table->string('event');
			$table->morphs('auditable');
			$table->text('old_values')->nullable();
			$table->text('new_values')->nullable();
			$table->text('url')->nullable();
			$table->ipAddress('ip_address')->nullable();
			$table->text('user_agent')->nullable();
			$table->string('tags')->nullable();
			$table->timestamps();

			$table->index(['user_id', 'user_type']);
		});
	}

	//----------------------------------------------------------------------------------------
	//  Reverse the migrations.
	public function down()  {
		Schema::drop('audits');
	}
}
