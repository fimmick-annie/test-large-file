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
class DataTeamTableProcess extends Command  {

	//----------------------------------------------------------------------------------------
	//  The name and signature of the console command.
	protected $signature = 'cron:processUpdateDataTeamTable';

	//  The console command description.
	protected $description = 'Update tables for Data-team.';

	//----------------------------------------------------------------------------------------
	//  Create a new command instance.
	public function __construct()  {
		parent::__construct();
	}

	//----------------------------------------------------------------------------------------
	//  Execute the console command.
	public function handle()  {
		Log::info(">>> Start '".$this->signature."'");

		app("App\Http\Controllers\DataTeamController")->updateCRMTraffic();
		app("App\Http\Controllers\DataTeamController")->updateCRMMemberMaster();
		app("App\Http\Controllers\DataTeamController")->updateCRMBrandMaster();
		app("App\Http\Controllers\DataTeamController")->updateCRMLocationMaster();
		app("App\Http\Controllers\DataTeamController")->updateCRMCampaignMaster();
		app("App\Http\Controllers\DataTeamController")->updateCRMCouponMaster();
		app("App\Http\Controllers\DataTeamController")->updateCRMSurveyMaster();
		app("App\Http\Controllers\DataTeamController")->updateCRMSurveyHistory();
		app("App\Http\Controllers\DataTeamController")->updateCRMQuotaTable();
		app("App\Http\Controllers\DataTeamController")->updateCRMGiftTable();
		app("App\Http\Controllers\DataTeamController")->updateCRMActionTable();
		app("App\Http\Controllers\DataTeamController")->updateCRMWhatsAppInboundMessage();
		app("App\Http\Controllers\DataTeamController")->updateCRMWhatsappOutboundMessage();

		Log::info(">>> End '".$this->signature."'");
	}
}
