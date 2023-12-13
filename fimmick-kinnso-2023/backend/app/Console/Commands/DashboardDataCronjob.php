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
use Illuminate\Support\Facades\DB;
use App\Models\DashboardGenericChart;
//========================================================================================
class DashboardDataCronjob extends Command
{

	//----------------------------------------------------------------------------------------
	//  The name and signature of the console command.
	protected $signature = 'cron:dashboardDataCronjob';

	//  The console command description.
	protected $description = 'dashboardDataCronjob';

	//----------------------------------------------------------------------------------------
	//  Create a new command instance.
	public function __construct()
	{
		parent::__construct();
	}

	//----------------------------------------------------------------------------------------
	//  Execute the console command.
	public function handle()
	{
		Log::info(">>> Start '" . $this->signature . "'");

		// get offer 
		$offers = DB::table('campaign_offers')
			->where('end_at', '>', date('Y-m-d H:i:s'))
			->get();

		// number of coupon issued
		// get data by offer
		foreach ($offers as $offer) {
			$id = $offer->id;
			// for 1 days
			for ($i = 0; $i < 1; $i++) {
				$issuedCouponsCount = DB::table('campaign_coupons')
					->where('offer_id', $id)
					->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime('-' . $i . 'days')), date('Y-m-d 23:59:59', strtotime('-' . $i . 'days'))])
					->count();
				$usedCouponsCount = DB::table('campaign_coupons')
					->where('offer_id', $id)
					->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime('-' . $i . 'days')), date('Y-m-d 23:59:59', strtotime('-' . $i . 'days'))])
					->whereNotNull('use_at')
					->count();
				$usersCount = DB::table('campaign_customer_journeys')
					->select('mobile')
					->where('offer_id', $id)
					->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime('-' . $i . 'days')), date('Y-m-d 23:59:59', strtotime('-' . $i . 'days'))])
					->groupBy('mobile')
					->get()
					->count();

				$today = date('Y-m-d');
				$record = DashboardGenericChart::firstOrCreate([
					'offer_id' => $id,
					'record_date' => $today,
				]);
				$record->number_of_users = $usersCount;
				$record->number_of_coupons_issued = $issuedCouponsCount;
				$record->number_of_coupons_used = $usedCouponsCount;
				$record->save();
				// Log::info(">>> cron '" . $this->signature . "' " . $id . ' ' . date('Y-m-d', strtotime('-' . $i . 'days')) . ' ' . $issuedCouponsCount . ' ' . $usedCouponsCount . ' ' . $usersCount);
			}
		}
		Log::info(">>> End '" . $this->signature . "'");
	}
}
