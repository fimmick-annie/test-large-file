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

use App\Models\CampaignQuickReply;

//========================================================================================
class QuickReplySeeder extends Seeder  {

	//----------------------------------------------------------------------------------------
	//  Run the database seeds.
	public function run()  {

		//----------------------------------------------------------------------------------------
		//  Create default records for testing
		CampaignQuickReply::create([
			"template_name" => "kfc_template_01",
			"sid" => "HXd37e7e2329e47133eb356849e30918ec",
			"text" => "答答問題先，你最希望獲取KFC邊款菜式嘅優惠？",
			"reply" => json_encode([
				"actions" => [
					["id" => "kfc01_reply_01", "title" => "桶飯"],
					["id" => "kfc01_reply_02", "title" => "燒雞"],
					["id" => "kfc01_reply_03", "title" => "雞卷"]
				]
			])
		]);
	}
}
