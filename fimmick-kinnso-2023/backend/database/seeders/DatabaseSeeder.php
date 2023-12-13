<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020.  All rights reserved.
//----------------------------------------------------------------------------------------
namespace Database\Seeders;
use Illuminate\Database\Seeder;

//========================================================================================
class DatabaseSeeder extends Seeder  {

	//----------------------------------------------------------------------------------------
	//  Seed the application's database.
	public function run()  {
		$this->call([
			DashboardSeeder::class,
			UserSeeder::class,
			UATSeeder::class,
			CampaignSeeder::class,
			CustomerJourneySeeder::class,
			DailyQuestionSeeder::class,
			ReferralSeeder::class,
			RedemptionSeeder::class,
			PointTransactionSeeder::class,
			CampaignOfferChannelSeeder::class,
			ChannelReceiptSampleSeeder::class,
			QuickReplySeeder::class,
		]);
	}
}
