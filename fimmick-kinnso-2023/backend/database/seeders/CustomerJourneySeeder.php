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

use App\Models\CampaignMasterJourney;

//========================================================================================
class CustomerJourneySeeder extends Seeder  {

	//----------------------------------------------------------------------------------------
	//  Run the database seeds.
	public function run()  {
		$journeyDataDictionary = array(

			//  Kinnso #1 Coupon Image
			//        0   1                      2                     3     4  5     6
			"1" => array(
				array(1, '2021-03-30 15:40:28', '2021-03-30 15:45:52', NULL, 1, 100, 'issue-coupon', 300, '{\"nextNode\": \"coupon-message\", \"expiryNode\": \"expiry\", \"outOfQuotaNode\": \"out-of-quota\", \"webhookErrorNode\": \"out-of-quota\", \"alreadyExistsNode\": \"exists\", \"selectedRedemptionStore\": \"7-eleven\", \"selectedRedemptionPeriodID\": \"0\"}'),
				array(2, '2021-03-30 15:40:28', '2021-03-30 16:58:36', NULL, 1, 110, 'coupon-message', 100, '{\"media\": \"https://www.kinnso.com/qrcode/?c=https%3A%2F%2Fwww.kinnso.com%2F{{uniqueCode}}\", \"message\": \"你好！這是你的$5電子優惠券。\\n使用時，請向職員出示 QR Code。\", \"nextNode\": null, \"schedule\": null}'),
				array(3, '2021-03-30 15:40:28', '2021-03-30 15:40:28', NULL, 1, 120, 'out-of-quota', 100, '{\"media\": null, \"message\": \"真係唔好意思！優惠太受歡迎，已經派曬喇！😓\", \"nextNode\": null, \"schedule\": null}'),
				array(4, '2021-03-30 15:40:28', '2021-03-30 15:46:25', NULL, 1, 130, 'expiry', 100, '{\"media\": null, \"message\": \"多謝你的支持！\\n呢個活動已經完滿結束咗喇。\", \"nextNode\": null, \"schedule\": null}'),
				array(5, '2021-03-30 15:40:28', '2021-03-30 15:46:31', NULL, 1, 140, 'exists', 100, '{\"media\": null, \"message\": \"好多謝你的支持！\\n\\n請注意，每人只可領取及使用電子優惠券一次。根據我哋嘅紀錄，你早前已經成功登記及領取電子優惠劵。\", \"nextNode\": null, \"schedule\": null}'),
			),

			//  Kinnso #2 Coupon code
			"2" => array(
				array(1, '2021-03-30 15:40:28', '2021-03-30 15:45:52', NULL, 2, 100, 'issue-coupon', 300, '{\"nextNode\": \"coupon-message\", \"expiryNode\": \"expiry\", \"outOfQuotaNode\": \"out-of-quota\", \"webhookErrorNode\": \"out-of-quota\", \"alreadyExistsNode\": \"exists\", \"selectedRedemptionStore\": \"hktvmall\", \"selectedRedemptionPeriodID\": \"0\"}'),
				array(2, '2021-03-30 15:40:28', '2021-03-30 16:58:36', NULL, 2, 110, 'coupon-message', 100, '{\"media\": \"http://www.kinnso.com/offers/kinnso-demo-02/journey-kv.jpg\", \"message\": \"你好！多謝你的支持！你可以用以下優惠代碼享用 $10 優惠！請點擊以下網址：\\nhttps://50addoil.com/\\n\\n結帳時輸入以下優惠碼，就可以享用優惠。\", \"nextNode\": \"coupon-code\", \"schedule\": null}'),
				array(2, '2021-03-30 15:40:28', '2021-03-30 15:46:31', NULL, 2, 120, 'coupon-code', 100, '{\"media\": null, \"message\": \"{{uniqueCode}}\", \"nextNode\": null, \"schedule\": null}'),
				array(3, '2021-03-30 15:40:28', '2021-03-30 15:40:28', NULL, 2, 130, 'out-of-quota', 100, '{\"media\": null, \"message\": \"真係唔好意思！優惠太受歡迎，已經派曬喇！😓\", \"nextNode\": null, \"schedule\": null}'),
				array(4, '2021-03-30 15:40:28', '2021-03-30 15:46:25', NULL, 2, 140, 'expiry', 100, '{\"media\": null, \"message\": \"多謝你的支持！\\n呢個活動已經完滿結束咗喇。\", \"nextNode\": null, \"schedule\": null}'),
				array(5, '2021-03-30 15:40:28', '2021-03-30 15:46:31', NULL, 2, 150, 'exists', 100, '{\"media\": null, \"message\": \"好多謝你的支持！\\n\\n請注意，每人只可領取及使用電子優惠券一次。根據我哋嘅紀錄，你早前已經成功登記及領取電子優惠劵。\", \"nextNode\": null, \"schedule\": null}'),
			),

			//  Kinnso #3 Product sampling
			"3" => array(
				array(1, '2021-03-30 15:40:28', '2021-03-30 15:45:52', NULL, 3, 100, 'get-form-data', 340, '{\"nextNode\": \"issue-coupon\", \"failNode\": \"expiry\"}'),
				array(2, '2021-03-30 15:40:28', '2021-03-30 15:45:52', NULL, 3, 110, 'issue-coupon', 300, '{\"nextNode\": \"coupon-message\", \"expiryNode\": \"expiry\", \"outOfQuotaNode\": \"out-of-quota\", \"webhookErrorNode\": \"out-of-quota\", \"alreadyExistsNode\": \"exists\", \"selectedRedemptionStore\": \"use-form\", \"selectedRedemptionPeriodID\": \"0\"}'),
				array(3, '2021-03-30 15:40:28', '2021-03-30 16:58:36', NULL, 3, 120, 'coupon-message', 100, '{\"media\": \"https://www.kinnso.com/qrcode/?c={{uniqueCode}}\", \"message\": \"你好！這是你的$5電子優惠券。\\n使用時，請向職員出示 QR Code。\", \"nextNode\": null, \"schedule\": null}'),
				array(4, '2021-03-30 15:40:28', '2021-03-30 15:40:28', NULL, 3, 130, 'out-of-quota', 100, '{\"media\": null, \"message\": \"真係唔好意思！優惠太受歡迎，已經派曬喇！😓\", \"nextNode\": null, \"schedule\": null}'),
				array(5, '2021-03-30 15:40:28', '2021-03-30 15:46:25', NULL, 3, 140, 'expiry', 100, '{\"media\": null, \"message\": \"多謝你的支持！\\n呢個活動已經完滿結束咗喇。\", \"nextNode\": null, \"schedule\": null}'),
				array(6, '2021-03-30 15:40:28', '2021-03-30 15:46:31', NULL, 3, 150, 'exists', 100, '{\"media\": null, \"message\": \"好多謝你的支持！\\n\\n請注意，每人只可領取及使用電子優惠券一次。根據我哋嘅紀錄，你早前已經成功登記及領取電子優惠劵。\", \"nextNode\": null, \"schedule\": null}'),
			),

			//  Kinnso #4 Live streaming link
			"4" => array(
				array(1, '2021-03-30 15:40:28', '2021-03-30 15:45:52', NULL, 4, 100, 'issue-coupon', 300, '{\"nextNode\": \"coupon-message\", \"expiryNode\": \"expiry\", \"outOfQuotaNode\": \"out-of-quota\", \"webhookErrorNode\": \"out-of-quota\", \"alreadyExistsNode\": \"exists\", \"selectedRedemptionStore\": \"circle-k\", \"selectedRedemptionPeriodID\": \"0\"}'),
				array(2, '2021-03-30 15:40:28', '2021-03-30 16:58:36', NULL, 4, 110, 'coupon-message', 100, '{\"media\": \"https://www.kinnso.com/offers/kinnso-demo-04/journey_kv.jpg\", \"message\": \"一戟即可前往直播：\\nhttps://www.kinnso.com/\", \"nextNode\": null, \"schedule\": null}'),
				array(3, '2021-03-30 15:40:28', '2021-03-30 15:40:28', NULL, 4, 120, 'out-of-quota', 100, '{\"media\": null, \"message\": \"真係唔好意思！優惠太受歡迎，已經派曬喇！😓\", \"nextNode\": null, \"schedule\": null}'),
				array(4, '2021-03-30 15:40:28', '2021-03-30 15:46:25', NULL, 4, 130, 'expiry', 100, '{\"media\": null, \"message\": \"多謝你的支持！\\n呢個活動已經完滿結束咗喇。\", \"nextNode\": null, \"schedule\": null}'),
				array(5, '2021-03-30 15:40:28', '2021-03-30 15:46:31', NULL, 4, 140, 'exists', 100, '{\"media\": null, \"message\": \"好多謝你的支持！\\n\\n請注意，每人只可領取及使用電子優惠券一次。根據我哋嘅紀錄，你早前已經成功登記及領取電子優惠劵。\", \"nextNode\": null, \"schedule\": null}'),
			),

			//  Kinnso #5 CCBA payment
			"5" => array(
				array(1, '2021-07-29 17:53:16', '2021-07-30 14:12:34', NULL, 5, 100, 'issue-coupon', 300, '{\"nextNode\": \"congratulations-message\", \"expiryNode\": \"expiry\", \"outOfQuotaNode\": \"out-of-quota\", \"webhookErrorNode\": \"out-of-quota\", \"alreadyExistsNode\": \"exists\", \"selectedRedemptionStore\": \"default\", \"selectedRedemptionPeriodID\": \"0\"}'),
				array(2, '2021-07-29 17:53:16', '2021-07-30 14:15:36', NULL, 5, 110, 'expiry', 100, '{\"media\": null, \"message\": \"多謝你的支持！\\n呢個活動已經完滿結束咗喇。\", \"nextNode\": null, \"schedule\": null}'),
				array(3, '2021-07-29 17:53:16', '2021-07-30 14:15:40', NULL, 5, 120, 'exists', 100, '{\"media\": null, \"message\": \"好多謝你的支持！\\n\\n請注意，每人只可領取及使用電子優惠券一次。根據我哋嘅紀錄，你早前已經成功登記及領取電子優惠劵。\", \"nextNode\": null, \"schedule\": null}'),
				array(4, '2021-07-29 17:53:16', '2021-07-30 14:15:40', NULL, 5, 130, 'out-of-quota', 100, '{\"media\": null, \"message\": \"真係唔好意思！優惠太受歡迎，已經派曬喇！😓\", \"nextNode\": null, \"schedule\": null}'),
				array(5, '2021-07-29 17:53:16', '2021-07-30 14:50:27', NULL, 5, 140, 'congratulations-message', 100, '{\"media\": \"http://127.0.0.1:8000/offers/kinnso-demo-05/journey_kv.png\", \"message\": \"我真係恭喜你呀！「Mirror 拉闊」門票已經預留咗畀你！你有 15 分鐘時間，立即點以下連結付款拎佢走啦！\", \"nextNode\": \"payment\", \"schedule\": null}'),
				array(6, '2021-07-29 17:53:16', '2021-07-30 14:38:25', NULL, 5, 150, 'payment', 500, '{\"gateway\": \"ccba\", \"message\": \"{{paymentURL}}\", \"itemName\": \"「Mirror 拉闊門票」一張\", \"nextNode\": \"coupon-message\", \"itemPrice\": \"780\", \"expiryTime\": \"+15 minutes\", \"failMessage\": \"對不起，購票失敗。請再試一次！\\n{{paymentURL}}\"}'),
				array(7, '2021-07-29 17:53:16', '2021-07-30 14:15:40', NULL, 5, 160, 'coupon-message', 100, '{\"media\": \"https://www.kinnso.com/qrcode/?c={{uniqueCode}}\", \"message\": \"恭喜你成功購票！這是你的入場 QR Code。\", \"nextNode\": null, \"schedule\": null}'),
			),

			//  Kinnso #6 NFT
			"6" => array(
				array(30, '2022-06-23 15:27:56', '2022-06-23 15:27:56', NULL, 6, 100, 'redeem-nft', 600, '{\"vendor\": \"amuro\", \"failNode\": \"message-fail\", \"nextNode\": \"message-success\"}'),
				array(31, '2022-06-23 16:53:58', '2022-06-23 16:55:24', NULL, 6, 110, 'message-success', 100, '{\"media\": null, \"message\": \"Congratulations!  You got a NFT!  The url is:\\n{{uniqueCode}}\", \"nextNode\": null, \"schedule\": null}'),
				array(32, '2022-06-23 16:53:58', '2022-06-23 16:55:55', NULL, 6, 120, 'message-fail', 100, '{\"media\": null, \"message\": \"Oh, sorry...  You are not able to redeem the NFT (T_T)\", \"nextNode\": null, \"schedule\": null}'),
			),

		);

		//----------------------------------------------------------------------------------------
		foreach ($journeyDataDictionary as $offerID => $journeyDataArray)  {

			$ordering = 100;
			foreach ($journeyDataArray as $journeyData)  {

				$nodeName = $journeyData[6];
				$type = $journeyData[7];
				$nodeSettings = $journeyData[8];

				$nodeSettings = str_replace("\\\"", "\"", $nodeSettings);

				CampaignMasterJourney::create([
					"offer_id" => $offerID,
					"ordering" => $ordering,
					"node_name" => $nodeName,
					"type" => $type,
					"node_settings" => $nodeSettings,
				]);

				$ordering += 10;
			}
		}
	}
}
