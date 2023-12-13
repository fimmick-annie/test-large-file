<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRedemptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redemptions', function (Blueprint $table) {

            //  Default columns
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();

            //----------------------------------------------------------------------------------------
			//  Columns for this class
            $table->datetime('start_at')->nullable();
            $table->datetime('end_at')->nullable();
            $table->integer('ordering')->default(100);
            $table->string('code_type', 32)->nullable();
            $table->string('thumbnail_filename', 48)->nullable();
            $table->json('title')->nullable();
            $table->json('subtitle')->nullable();
            $table->string('redemption_path', 48)->nullable(); // 2022.07.22 --Kay
            $table->integer('quota')->default(0);
            $table->integer('quota_issued')->default(0);
            $table->integer('required_points')->default(100);
            $table->json('details')->nullable();
            $table->json('void_details')->nullable();

            $table->index('start_at');
            $table->index('end_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('redemptions');
    }
}
