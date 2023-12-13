<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	public function up()  {
		Schema::create('app_users', function (Blueprint $table) {

			//  Default columns
			$table->bigIncrements('id');
			$table->timestamps();
            $table->softDeletes();
			//----------------------------------------------------------------------------------------
			//  Columns for this class
			$table->string('name');
			$table->string('email');
			$table->string('password');
            $table->string('roles')->nullable();

			$table->timestamp('email_verified_at')->nullable();
			$table->dateTime('change_password_at')->nullable();
			$table->rememberToken();
			$table->string('api_token')->unique()->nullable()->default(null);
            $table->string('token_expiry_at')->nullable();
		});
	}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_users');
    }
}
