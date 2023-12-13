<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignQuickRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_quick_replies', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();

            //----------------------------------------------------------------------------------------
			//  Columns for this class
            $table->string('template_name', 24); 
            $table->string('sid', 48);
            $table->text('text')->nullable();
            $table->json('reply')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campagin_quick_replies');
    }
}
