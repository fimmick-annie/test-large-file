<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToSegmentExchangesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('segment_exchanges', function (Blueprint $table) {
			//
			$table->string('mobile', 24)->nullable()->after('aid');
			$table->string('referrer_code', 32)->nullable()->after('mobile');
			$table->string('form_code', 32)->nullable()->after('referrer_code');

			//  Use RAW query because no need to install doctrine/dbal package
// 			$table->string("aid", 48)->nullable()->change();
			DB::statement("ALTER TABLE `segment_exchanges` MODIFY `aid` varchar(48) COLLATE utf8mb4_unicode_ci DEFAULT NULL;");

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('segment_exchanges', function (Blueprint $table) {
			//
			$table->dropColumn('mobile');
			$table->dropColumn('form_code');
			$table->dropColumn('referrer_code');

			//  Use RAW query because no need to install doctrine/dbal package
// 			$table->string("aid", 48)->nullable(false)->change();
			DB::statement("ALTER TABLE `segment_exchanges` MODIFY `aid` varchar(48) COLLATE utf8mb4_unicode_ci NOT NULL;");

		});
	}
}
