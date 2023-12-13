<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignOfferReceiptUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_offer_receipt_uploads', function (Blueprint $table) {

              //  Default columns
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();
            
            //----------------------------------------------------------------------------------------
			//  Columns for this class
            $table->unsignedInteger('offer_id');
            $table->unsignedInteger('member_id');
            $table->date('purchase_date');
            $table->float('purchase_amount');
            $table->unsignedInteger('merchant_caption_id');
            $table->string('invoice_number', 64);
            $table->string('receipt_path', 240);
            $table->string('status', 16)->default("pending");
            $table->unsignedInteger('approve_point')->nullable();
            $table->timestamp('handle_date')->nullable();
            $table->string('reject_reason', 64)->nullable();
            $table->string('handler', 48)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_offer_receipt_uploads');
    }
}
