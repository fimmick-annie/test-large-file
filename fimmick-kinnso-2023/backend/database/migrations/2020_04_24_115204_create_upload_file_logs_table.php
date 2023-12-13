<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadFileLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upload_file_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('uniqid', 64)->nullable();
            $table->string('name')->nullable();
            $table->integer('size')->nullable();
            $table->string('extension')->nullable();
            $table->string('original_name')->nullable();

            $table->timestamp('created_at')->nullable();
            $table->string('created_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('upload_file_logs');
    }
}
