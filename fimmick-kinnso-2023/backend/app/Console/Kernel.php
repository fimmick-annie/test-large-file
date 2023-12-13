<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020-2021.  All rights reserved.
//----------------------------------------------------------------------------------------

namespace App\Console;

//----------------------------------------------------------------------------------------
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

//========================================================================================
class Kernel extends ConsoleKernel  {

	//  The Artisan commands provided by your application.
	protected $commands = [
		//
	];

	//----------------------------------------------------------------------------------------
	//  Define the application's command schedule.
	protected function schedule(Schedule $schedule)  {

		//  WhatsApp message queue
		$schedule->command('cron:processWhatsApp')
			->everyMinute()
			->between('09:30', '22:00')
			->withoutOverlapping();

		//  Reports for Data-team
//DataTeamNotInvolved 		$schedule->command('cron:processUpdateDataTeamTable')->dailyAt("03:00");

		//  Clean up unused reports
		$schedule->command('cron:processCleanUpReport')->dailyAt("06:00");

		//  TODO: Archive coupon pool records if offer and coupon ended

		//  Archive tables
		$schedule->command('cron:processDataArchive')->dailyAt("07:00");

		//  Offer-based daily coupon report
		$schedule->command('cron:processOfferCouponDailyReport')->dailyAt("08:40");

		//  Generate report for CRM
		$schedule->command('cron:processOfferWhatsAppMonthlyReport')->dailyAt("08:50");
		$schedule->command('cron:processOfferWhatsAppDailyReport')->dailyAt("09:00");

		//  Chatbot journey: Referral & Payment
		$schedule->command('cron:processChatbotJourney')
			->everyFiveMinutes()
			->between('09:30', '22:00')
			->withoutOverlapping();

		//  Offer quota monitor
		$schedule->command('cron:processQuotaLevelAlert')->everyFiveMinutes();

		//  Delete reserve form
		$schedule->command('cron:processCleanUpReserveForm')->everyFiveMinutes();

		//  Offer related process
		$schedule->command('cron:processAutoLabeling')->hourly();

		//  Dashboard data
		$schedule->command('cron:dashboardDataCronjob')->hourly();

		//  update tag
		$schedule->command('cron:dailyCheckTagProcess')->dailyAt('13:35');

		// Re-calculate point for All Member -- 2022.08.01 Kay // 2022.08.17 change to daily
		$schedule->command('cron:processPointRecalculateAllMember')->dailyAt("3:40");

		// make point transaction record to erase expired point -- 2022.08.03 Kay
		$schedule->command('cron:processEraseYesterdayExpiry')->dailyAt("01:40");
	}

	//----------------------------------------------------------------------------------------
	//  Register the commands for the application.
	protected function commands()  {
		$this->load(__DIR__.'/Commands');
		require base_path('routes/console.php');
	}
}
