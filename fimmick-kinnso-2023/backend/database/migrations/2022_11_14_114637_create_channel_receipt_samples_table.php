<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelReceiptSamplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_receipt_samples', function (Blueprint $table) {

             //  Default columns
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();
            
            //----------------------------------------------------------------------------------------
			//  Columns for this class
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->string('channel', 48); 
            $table->string('receipt_sample_url', 180);
            $table->string('save_type', 8)->default("url");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channel_receipt_samples');
    }
}
