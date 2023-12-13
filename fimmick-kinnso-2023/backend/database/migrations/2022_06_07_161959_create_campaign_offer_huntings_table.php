<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignOfferHuntingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_offer_huntings', function (Blueprint $table) {
			//  Default columns
			$table->bigIncrements('id');
			$table->timestamps();
			$table->softDeletes();

			//----------------------------------------------------------------------------------------
			//  Columns for this class
			$table->string('created_by', 32)->nullable();
			$table->string('updated_by', 32)->nullable();
			$table->string('deleted_by', 32)->nullable();

			// match with offer_hunting input[name="name"] max length
			$table->string('name', 64);
			$table->string('mobile_num', 24);
			$table->text('discount_content');
			$table->string('media', 240)->nullable();

			$table->unsignedBigInteger('member_id')->nullable();
            $table->string('status', 16)->default("pending");
			$table->integer('approved_point',)->nullable();
			$table->dateTime('approved_at')->nullable();
			$table->unsignedBigInteger('approved_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_offer_huntings');
    }
}
