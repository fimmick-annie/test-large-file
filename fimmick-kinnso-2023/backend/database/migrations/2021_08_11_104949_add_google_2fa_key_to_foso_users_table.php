<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGoogle2faKeyToFosoUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('foso_users', function (Blueprint $table) {
            // Google2FA
            $table->string('google_2fa_key', 24)->nullable()->after('password');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('foso_users', function (Blueprint $table) {
            // Google2FA
            $table->dropColumn('google_2fa_key');
        });
    }
}
