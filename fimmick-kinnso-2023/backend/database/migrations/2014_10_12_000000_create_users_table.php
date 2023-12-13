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
class CreateUsersTable extends Migration  {

	//----------------------------------------------------------------------------------------
	//  Run the migrations.
	public function up()  {
		Schema::create('foso_users', function (Blueprint $table) {

			//  Default columns
			$table->bigIncrements('id');
			$table->timestamps();

			//----------------------------------------------------------------------------------------
			//  Columns for this class
			$table->string('name');
			$table->string('email')->unique();
			$table->string('password');

			$table->timestamp('email_verified_at')->nullable();
			$table->dateTime('change_password_at')->nullable();
			$table->rememberToken();
			$table->string('api_token')->unique()->nullable()->default(null);
		});
	}

	//----------------------------------------------------------------------------------------
	//  Reverse the migrations.
	public function down()  {
		Schema::dropIfExists('foso_users');
	}
}
