<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSegmentExchangesTableAddMemberReferralCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('segment_exchanges', function (Blueprint $table) {
            Schema::table('segment_exchanges', function (Blueprint $table) {
                $table->string("member_referral_code", 48)->after('referrer_code')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn("segment_exchanges", "kinnso_referral_code"))  {
			Schema::table("segment_exchanges", function ($table)  {
				$table->dropColumn("member_referral_code");
			});
		}
    }
}
