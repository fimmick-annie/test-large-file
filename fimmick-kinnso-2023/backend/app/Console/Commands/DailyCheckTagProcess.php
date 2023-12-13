<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020.  All rights reserved.
//----------------------------------------------------------------------------------------

namespace App\Console\Commands;

//----------------------------------------------------------------------------------------
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;

//========================================================================================
class DailyCheckTagProcess extends Command  {

	//----------------------------------------------------------------------------------------
	//  The name and signature of the console command.
	protected $signature = 'cron:dailyCheckTagProcess';

	//  The console command description.
	protected $description = 'update all tags';

	//----------------------------------------------------------------------------------------
	//  Create a new command instance.
	public function __construct()  {
		parent::__construct();
	}

	//----------------------------------------------------------------------------------------
	//  Execute the console command.
	public function handle()  {
		Log::info(">>> Start '".$this->signature."'");
		app("App\Http\Controllers\FOSODailyQuestionController")->processGetAllTags();
		Log::info(">>> End '".$this->signature."'");
	}
}
