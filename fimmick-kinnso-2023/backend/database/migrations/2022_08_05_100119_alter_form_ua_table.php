<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFormUaTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::table('form_ua', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn("form_ua", "deleted_at"))  {
            Schema::table('form_ua', function (Blueprint $table) {
                $table->dropColumn("deleted_at");
            });
        }
    }
}
