<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormUa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_ua', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

			//----------------------------------------------------------------------------------------
			//  Columns for this class
            $table->string('name', 48)->nullable();
			$table->string('mobile', 24);
            $table->string('ua_account', 24);

            $table->boolean('confirm_right_info')->default(0);
            $table->boolean('accept_whatsapp_notice')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_ua');
    }
}
