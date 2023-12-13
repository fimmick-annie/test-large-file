<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToCampaignOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaign_offers', function (Blueprint $table) {
            //
            $table->integer('likeCounter')->default(0);
            $table->string('tag', 32)->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaign_offers', function (Blueprint $table) {
            //
            $table->dropColumn('likeCounter');
            $table->dropColumn('tag');
        });
    }
}
