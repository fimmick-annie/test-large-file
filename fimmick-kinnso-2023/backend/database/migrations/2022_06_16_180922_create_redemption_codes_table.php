<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRedemptionCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redemption_codes', function (Blueprint $table) {

            //  Default columns
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();

            //----------------------------------------------------------------------------------------
			//  Columns for this class
            $table->unsignedBigInteger('redemption_id')->nullable();
            $table->unsignedBigInteger('redemption_history_id')->nullable();
            $table->string('code', 128)->collation('utf8mb4_bin')->nullable();

            $table->unique(['redemption_id', 'code']);
            $table->index('redemption_history_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('redemption_codes');
    }
}
