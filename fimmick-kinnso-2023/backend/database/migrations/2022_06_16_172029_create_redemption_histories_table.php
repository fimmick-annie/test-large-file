<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRedemptionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redemption_histories', function (Blueprint $table) {

            //  Default columns
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();

            //----------------------------------------------------------------------------------------
			//  Columns for this class
            $table->unsignedBigInteger('member_id')->nullable();
            $table->unsignedBigInteger('redemption_id')->nullable();
            $table->timestamp('void_at')->nullable();
            $table->timestamp('expire_at')->nullable();

            $table->index('member_id');
            $table->index('redemption_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('redemption_histories');
    }
}
