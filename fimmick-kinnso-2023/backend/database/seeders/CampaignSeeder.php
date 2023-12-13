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

use App\Models\CampaignOffer;
use App\Models\CampaignBanner;
use App\Models\CampaignCoupon;
use App\Models\CampaignListing;
use App\Models\CampaignStoreQuota;
use App\Models\CampaignCouponPool;

//========================================================================================
class CampaignSeeder extends Seeder  {

	//----------------------------------------------------------------------------------------
	//  Run the database seeds.
	public function run()  {

		$startAt = date("Y-m-d 00:00:00");
		$endAt = date("Y-m-d 23:59:59", strtotime("+30 days"));

		//  Offers
		$offerArray = array(

		//  Kinnso DEMO offers
		array(1, '2022-03-22 14:18:27', '2022-03-22 14:18:27', NULL, '2022-03-22 00:00:00', '2022-04-21 23:59:59', 'kinnso-demo-01', 'randomly-generated', 'kinnso-demo-01', 'Coupon Image', 'DEMO 1', 'kinnso_01_coupon_image', '[{\"type\": \"static\", \"channel\": \"mannings\", \"code_image\": \"coupon_barcode_mannings.png\"}]', '{\"default\": \"+0 day\"}', '{\"default\": \"whatsapp\"}', 10, 0, '<ul><li>每位Facebook帳戶或電話號碼用戶只限參加活動及換領體驗裝乙次。一切有關本活動的登記資料及登記日期均以伺服器接收記錄為準。</li><li>成功登記之參加者於48小時內，透過閣下登記之手機號碼透過WhatsApp收到確認訊息。屆時請按照訊息上的指示到指定專門店領取體驗裝。</li><li>成功登記後之換領地點及日期將不能更改。</li><li>所有體驗裝及禮品數量有限，送完即止，不得轉讓、退換、兌換現金或任何其他產品。如遺失已領取的禮品，將不會補發。</li></ul>', NULL, '{\"gtm\": [\"\"], \"facebookPixel\": [\"\"], \"googleAnalytics\": [\"\"]}', '{\"offerRegistrationM\": 1, \"offerRegistrationNPickM\": \"1, 3\", \"couponActivationWebhookURL\": null, \"couponActivationWebhookType\": 10, \"offerRegistrationWebhookType\": \"10\"}', '{}', 0, 'hot', NULL, '網購', '盆菜'),
		array(2, '2022-03-22 14:18:27', '2022-03-22 14:18:27', NULL, '2022-03-22 00:00:00', '2022-04-21 23:59:59', 'kinnso-demo-02', 'pre-generated', 'kinnso-demo-02', 'Coupon Code', 'DEMO 2', 'kinnso_02_coupon_code', '[{\"type\": \"static\", \"channel\": \"mannings\", \"code_image\": \"coupon_barcode_mannings.png\"}]', '{\"default\": \"+0 day\"}', '{\"default\": \"whatsapp\"}', 4, 0, '<ul><li>每位Facebook帳戶或電話號碼用戶只限參加活動及換領體驗裝乙次。一切有關本活動的登記資料及登記日期均以伺服器接收記錄為準。</li><li>成功登記之參加者於48小時內，透過閣下登記之手機號碼透過WhatsApp收到確認訊息。屆時請按照訊息上的指示到指定專門店領取體驗裝。</li><li>成功登記後之換領地點及日期將不能更改。</li><li>所有體驗裝及禮品數量有限，送完即止，不得轉讓、退換、兌換現金或任何其他產品。如遺失已領取的禮品，將不會補發。</li></ul>', NULL, '{\"gtm\": [\"\"], \"facebookPixel\": [\"\"], \"googleAnalytics\": [\"\"]}', '{\"offerRegistrationM\": 1, \"offerRegistrationNPickM\": \"1, 3\", \"couponActivationWebhookURL\": null, \"couponActivationWebhookType\": 10, \"offerRegistrationWebhookType\": \"10\"}', '{}', 0, 'new', NULL, '好去處', '到會, 獨家優惠'),
		array(3, '2022-03-22 14:18:27', '2022-03-22 14:18:27', NULL, '2022-03-22 00:00:00', '2022-04-21 23:59:59', 'kinnso-demo-03', 'randomly-generated', 'kinnso-demo-03', 'Product Sampling', 'DEMO 3', 'kinnso_03_product_sampling', '[{\"type\": \"static\", \"channel\": \"mannings\", \"code_image\": \"coupon_barcode_mannings.png\"}]', '{\"default\": \"+0 day\"}', '{\"default\": \"whatsapp\"}', 10, 0, '<ul><li>每位Facebook帳戶或電話號碼用戶只限參加活動及換領體驗裝乙次。一切有關本活動的登記資料及登記日期均以伺服器接收記錄為準。</li><li>成功登記之參加者於48小時內，透過閣下登記之手機號碼透過WhatsApp收到確認訊息。屆時請按照訊息上的指示到指定專門店領取體驗裝。</li><li>成功登記後之換領地點及日期將不能更改。</li><li>所有體驗裝及禮品數量有限，送完即止，不得轉讓、退換、兌換現金或任何其他產品。如遺失已領取的禮品，將不會補發。</li></ul>', NULL, '{\"gtm\": [\"\"], \"facebookPixel\": [\"\"], \"googleAnalytics\": [\"\"]}', '{\"offerRegistrationM\": 1, \"offerRegistrationNPickM\": \"1, 3\", \"couponActivationWebhookURL\": null, \"couponActivationWebhookType\": 10, \"offerRegistrationWebhookType\": \"10\"}', '{}', 0, 'push', NULL, '美食, 好去處', '生日蛋糕, 首飾'),
		array(4, '2022-03-22 14:18:27', '2022-03-22 14:18:27', NULL, '2022-03-22 00:00:00', '2022-04-21 23:59:59', 'kinnso-demo-04', 'randomly-generated', 'kinnso-demo-04', 'Live Streaming Link', 'DEMO 4', 'kinnso_04_live_streaming_link', '[{\"type\": \"static\", \"channel\": \"mannings\", \"code_image\": \"coupon_barcode_mannings.png\"}]', '{\"default\": \"+0 day\"}', '{\"default\": \"whatsapp\"}', 10, 0, '<ul><li>每位Facebook帳戶或電話號碼用戶只限參加活動及換領體驗裝乙次。一切有關本活動的登記資料及登記日期均以伺服器接收記錄為準。</li><li>成功登記之參加者於48小時內，透過閣下登記之手機號碼透過WhatsApp收到確認訊息。屆時請按照訊息上的指示到指定專門店領取體驗裝。</li><li>成功登記後之換領地點及日期將不能更改。</li><li>所有體驗裝及禮品數量有限，送完即止，不得轉讓、退換、兌換現金或任何其他產品。如遺失已領取的禮品，將不會補發。</li></ul>', NULL, '{\"gtm\": [\"\"], \"facebookPixel\": [\"\"], \"googleAnalytics\": [\"\"]}', '{\"offerRegistrationM\": 1, \"offerRegistrationNPickM\": \"1, 3\", \"couponActivationWebhookURL\": null, \"couponActivationWebhookType\": 10, \"offerRegistrationWebhookType\": \"10\"}', '{}', 0, 'less', NULL, '優惠, 網購', '到會'),
		array(5, '2022-03-22 14:18:27', '2022-03-22 14:18:27', NULL, '2022-03-22 00:00:00', '2022-04-21 23:59:59', 'kinnso-demo-05', 'randomly-generated', 'kinnso-demo-05', 'CCBA Payment', 'DEMO 5', 'kinnso_05_ccba_payment', '[{\"type\": \"static\", \"channel\": \"mannings\", \"code_image\": \"coupon_barcode_mannings.png\"}]', '{\"default\": \"+0 day\"}', '{\"default\": \"whatsapp\"}', 10, 0, '<ul><li>每位Facebook帳戶或電話號碼用戶只限參加活動及換領體驗裝乙次。一切有關本活動的登記資料及登記日期均以伺服器接收記錄為準。</li><li>成功登記之參加者於48小時內，透過閣下登記之手機號碼透過WhatsApp收到確認訊息。屆時請按照訊息上的指示到指定專門店領取體驗裝。</li><li>成功登記後之換領地點及日期將不能更改。</li><li>所有體驗裝及禮品數量有限，送完即止，不得轉讓、退換、兌換現金或任何其他產品。如遺失已領取的禮品，將不會補發。</li></ul>', NULL, '{\"gtm\": [\"\"], \"facebookPixel\": [\"\"], \"googleAnalytics\": [\"\"]}', '{\"offerRegistrationM\": 1, \"offerRegistrationNPickM\": \"1, 3\", \"couponActivationWebhookURL\": null, \"couponActivationWebhookType\": 10, \"offerRegistrationWebhookType\": \"10\"}', '{}', 0, 'new, hot', NULL, '美食', '散水餅'),
		array(6, '2022-03-22 14:18:27', '2022-03-22 14:18:27', NULL, '2022-03-22 00:00:00', '2022-04-21 23:59:59', 'kinnso-demo-06', 'pre-generated', 'kinnso-demo-06', 'eCommerce Payment', 'DEMO 6', 'kinnso_06_ecommerce_payment', '[{\"type\": \"static\", \"channel\": \"mannings\", \"code_image\": \"coupon_barcode_mannings.png\"}]', '{\"default\": \"+0 day\"}', '{\"default\": \"whatsapp\"}', 10, 0, '<ul><li>每位Facebook帳戶或電話號碼用戶只限參加活動及換領體驗裝乙次。一切有關本活動的登記資料及登記日期均以伺服器接收記錄為準。</li><li>成功登記之參加者於48小時內，透過閣下登記之手機號碼透過WhatsApp收到確認訊息。屆時請按照訊息上的指示到指定專門店領取體驗裝。</li><li>成功登記後之換領地點及日期將不能更改。</li><li>所有體驗裝及禮品數量有限，送完即止，不得轉讓、退換、兌換現金或任何其他產品。如遺失已領取的禮品，將不會補發。</li></ul>', NULL, '{\"gtm\": [\"\"], \"facebookPixel\": [\"\"], \"googleAnalytics\": [\"\"]}', '{\"offerRegistrationM\": 1, \"offerRegistrationNPickM\": \"1, 3\", \"couponActivationWebhookURL\": null, \"couponActivationWebhookType\": 10, \"offerRegistrationWebhookType\": \"10\"}', '{}', 0, 'hot, less', NULL, '優惠', '首飾'),

		);
		foreach ($offerArray as $offer)  {

			$offerCode = $offer[6];
			$couponType = $offer[7];
			$offerName = $offer[8];
			$offerTitle = $offer[9];
			$offerSubtitle = $offer[10];
			$bladeFolder = $offer[11];
			$quota = $offer[15];
			$tnc = $offer[17];
			$bundledOffersID = $offer[18];

			$tag = $offer[23];
			$category = $offer[25];
			$filter = $offer[26];

			$tnc = str_replace(
				array("\\r", "\\n"),
				array("", "\n"),
				$tnc
			);

			CampaignOffer::create([
				'start_at' => $startAt,
				'end_at' => $endAt,
				'offer_code' => $offerCode,
				'coupon_type' => $couponType,
				'offer_name' => $offerName,
				'offer_title' => $offerTitle,
				'offer_subtitle' => $offerSubtitle,
				'blade_folder' => $bladeFolder,
				'code_type' => '[{"type": "static","channel": "mannings","code_image": "coupon_barcode_mannings.png"}]',
				'channel_expiry' => '{"default": "+0 day"}',
				'confirmation_method' => '{"default":"whatsapp"}',		// Must use double quote for JSON
				'quota' => $quota,
				'tnc' => $tnc,
				'bundled_offers_id' => $bundledOffersID,
				'tracking_code' => '{"gtm":[""], "googleAnalytics":[""],"facebookPixel":[""]}',
				'webhook' => '{"offerRegistrationM": 1, "offerRegistrationNPickM": "1, 3", "couponActivationWebhookURL": null, "couponActivationWebhookType": 10, "offerRegistrationWebhookType": "10"}',
				'statistic_data' => '{}',
				'tag' => $tag,
				'category' => $category,
				'filter' => $filter,
			]);
		}

		//----------------------------------------------------------------------------------------
		//  Coupons
		$couponArray = array(

			//  Kinnso DEMO coupons
			array(1, '2020-06-16 14:11:44', '2020-06-16 14:15:09', NULL, 1, 1, 100, 'code0001', '+85293101987', NULL, '2020-06-16 00:00:00', NULL, '2020-07-07 23:59:59', 'whatsapp', '{\"email\": \"\", \"mobile\": \"+85293101987\", \"offerCode\": \"kinnso-demo-01\", \"referrerCode\": \"\", \"selectedChannel\": \"whatsapp\", \"confirmationMethod\": \"whatsapp\", \"selectedRedemptionStore\": \"7-eleven\", \"selectedRedemptionPeriodID\": \"0\"}', '', '7A7849337879416A', '{\"open\": 1}'),
			array(2, '2020-06-16 14:11:44', '2020-06-16 14:15:09', NULL, 2, 2, 100, 'code0002', '+85293101987', NULL, '2020-06-16 00:00:00', NULL, '2020-07-07 23:59:59', 'whatsapp', '{\"email\": \"\", \"mobile\": \"+85293101987\", \"offerCode\": \"kinnso-demo-02\", \"referrerCode\": \"\", \"couponCodeURL\": \"http://127.0.0.1:8000/offers/kinnso-demo-02/coupons/448079969016dbf4f358fdc5021b779d.png\", \"selectedChannel\": \"whatsapp\", \"confirmationMethod\": \"whatsapp\", \"couponCodeFilename\": \"448079969016dbf4f358fdc5021b779d\", \"selectedRedemptionStore\": \"hktvmall\", \"selectedRedemptionPeriodID\": \"0\"}', '', '7A7849337879416A', '{\"open\": 1}'),
			array(3, '2020-06-16 14:11:44', '2020-06-16 14:15:09', NULL, 3, 3, 100, 'code0003', '+85293101987', NULL, '2020-06-16 00:00:00', NULL, '2020-07-07 23:59:59', 'whatsapp', '{\"email\": \"\", \"_token\": \"1PYdcwXySNnOUExjKD3j8tiiLmjytV0wnijj2yLd\", \"mobile\": \"+85225152218\", \"offerCode\": \"kinnso-demo-03\", \"confirm_tnc\": \"on\", \"referrerCode\": \"\", \"confirm_service\": \"on\", \"selectedChannel\": \"kinnso\", \"confirmationMethod\": \"whatsapp\", \"selectedRedemptionStore\": \"觀塘翠屏商場6號舖\", \"selectedRedemptionPeriodID\": \"2\"}', '', '7A7849337879416A', '{\"open\": 1}'),
			array(4, '2020-06-16 14:11:44', '2020-06-16 14:15:09', NULL, 4, 4, 100, 'code0004', '+85293101987', NULL, '2020-06-16 00:00:00', NULL, '2020-07-07 23:59:59', 'whatsapp', '{\"email\": \"\", \"mobile\": \"+85293101987\", \"offerCode\": \"kinnso-demo-04\", \"referrerCode\": \"\", \"selectedChannel\": \"whatsapp\", \"confirmationMethod\": \"whatsapp\", \"selectedRedemptionStore\": \"circle-k\", \"selectedRedemptionPeriodID\": \"0\"}', '', '7A7849337879416A', '{\"open\": 1}'),
			array(5, '2020-06-16 14:11:44', '2020-06-16 14:15:09', NULL, 5, 5, 100, 'code0005', '+85293101987', NULL, '2020-06-16 00:00:00', NULL, '2020-07-07 23:59:59', 'whatsapp', '{\"_token\": \"0uwBHortq3uBiEGzIGIzygRm2i9yQs6ufzzmmkmN\", \"mobile\": \"93101987\", \"areaCode\": \"+852\", \"agreeTerms\": \"on\", \"customerName\": \"Pacess HO\", \"customerEmail\": \"pacessho@fimmick.com\", \"mobileConfirm\": \"93101987\", \"optinWhatsApp\": \"on\", \"areaCodeConfirm\": \"+852\", \"selectedChannel\": \"whatsapp\", \"confirmationMethod\": \"whatsapp\", \"selectedRedemptionStore\": \"金鐘太古廣場 L1 Harvey Nichols專櫃\", \"selectedRedemptionPeriod\": \"2020-06-16 至 2020-06-30\", \"pickedRedemptionStoreCode\": \"3161\", \"selectedRedemptionPeriodID\": \"36\"}', '', '7A7849337879416A', '{\"open\": 1}'),
			array(6, '2020-06-16 14:11:44', '2020-06-16 14:15:09', NULL, 6, 6, 100, 'code0006', '+85293101987', NULL, '2020-06-16 00:00:00', NULL, '2020-07-07 23:59:59', 'whatsapp', '{\"_token\": \"0uwBHortq3uBiEGzIGIzygRm2i9yQs6ufzzmmkmN\", \"mobile\": \"93101987\", \"areaCode\": \"+852\", \"agreeTerms\": \"on\", \"customerName\": \"Pacess HO\", \"customerEmail\": \"pacessho@fimmick.com\", \"mobileConfirm\": \"93101987\", \"optinWhatsApp\": \"on\", \"areaCodeConfirm\": \"+852\", \"selectedChannel\": \"whatsapp\", \"confirmationMethod\": \"whatsapp\", \"selectedRedemptionStore\": \"金鐘太古廣場 L1 Harvey Nichols專櫃\", \"selectedRedemptionPeriod\": \"2020-06-16 至 2020-06-30\", \"pickedRedemptionStoreCode\": \"3161\", \"selectedRedemptionPeriodID\": \"36\"}', '', '7A7849337879416A', '{\"open\": 1}'),
		);
		foreach ($couponArray as $coupon)  {

			$offerID = $coupon[4];
			$parentOfferID = $coupon[5];
			$couponOrder = $coupon[6];
			$uniqueCode = $coupon[7];
			$selectedChannel = $coupon[13];
			$formData = $coupon[14];

			$formData = str_replace("\\\"", "\"", $formData);

			CampaignCoupon::create([
				'offer_id' => $offerID,
				'parent_offer_id' => $parentOfferID,
				'coupon_order' => $couponOrder,
				'unique_code' => $uniqueCode,
				'mobile' => '+85293101987',
				'email' => 'admin@fimmick.com',
				'start_at' => $startAt,
				'expiry_at' => $endAt,
				'selected_channel' => $selectedChannel,
				'form_data' => $formData,
				'referrer_code' => '',
				'referral_code' => 'D4C3B2A1',
			]);
		}

		//----------------------------------------------------------------------------------------
		//  Create default records for testing
		$today = date("Y-m-d 00:00:00");
		$monthLater = date("Y-m-d 23:59:59", strtotime("+1 month"));

		CampaignListing::create(['list_name'=>'DEMO Coupon List', 'offer_id'=>1, 'start_at'=>$today, 'end_at'=>$monthLater]);
		CampaignListing::create(['list_name'=>'DEMO Coupon List', 'offer_id'=>2, 'start_at'=>$today, 'end_at'=>$monthLater]);

		CampaignListing::create(['list_name'=>'DEMO Sampling List', 'offer_id'=>3, 'start_at'=>$today, 'end_at'=>$monthLater]);

		//----------------------------------------------------------------------------------------
		//  Store quota
		$storeQuotaArray = array(

			//  Offer 1
			array(1, '2020-06-16 11:51:13', '2020-06-23 12:29:08', NULL, 'offerQuotasConfirmAPI', NULL, NULL, 1, date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59', strtotime('+30 days')), '7-eleven', 100, 10, 0, '全港 7-Eleven', '全港 7-Eleven'),

			//  Offer 2

			//  Offer 3
			array(1, '2020-06-16 11:51:13', '2020-06-23 12:29:08', NULL, 'offerQuotasConfirmAPI', NULL, NULL, 3, date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59', strtotime('+30 days')), 'kt', 100, 10, 0, '觀塘翠屏商場6號舖', '觀塘翠屏商場6號舖'),
			array(2, '2020-06-16 11:51:13', '2020-06-29 18:57:56', NULL, 'offerQuotasConfirmAPI', NULL, NULL, 3, date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59', strtotime('+30 days')), 'cwb', 100, 10, 0, '銅鑼灣利園山道49-57號地下k舖', '銅鑼灣利園山道49-57號地下k舖'),
			array(3, '2020-06-16 11:51:13', '2020-06-23 12:29:08', NULL, 'offerQuotasConfirmAPI', NULL, NULL, 3, date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59', strtotime('+30 days')), 'tst', 100, 10, 0, '九龍尖東站25號舖', '九龍尖東站25號舖'),
			array(4, '2020-06-16 11:51:13', '2020-06-23 12:29:08', NULL, 'offerQuotasConfirmAPI', NULL, NULL, 3, date('Y-m-d 00:00:00', strtotime('-7 days')), date('Y-m-d 23:59:59', strtotime('+7 days')), 'mk', 100, 10, 0, '旺角弼街43號地下', '旺角弼街43號地下'),
			array(5, '2020-06-16 11:51:13', '2020-06-23 12:29:08', NULL, 'offerQuotasConfirmAPI', NULL, NULL, 3, date('Y-m-d 00:00:00', strtotime('+0 days')), date('Y-m-d 23:59:59', strtotime('+14 days')), 'mk', 100, 10, 0, '旺角弼街43號地下', '旺角弼街43號地下'),

			//  Offer 4
			array(1, '2020-06-16 11:51:13', '2020-06-23 12:29:08', NULL, 'offerQuotasConfirmAPI', NULL, NULL, 4, date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59', strtotime('+30 days')), 'circle-k', 100, 10, 0, 'Circle-K', 'Circle-K'),
		);
		foreach ($storeQuotaArray as $storeQuota)  {

			$offerID = $storeQuota[7];
			$startAt = $storeQuota[8];
			$endAt = $storeQuota[9];
			$storeCode = $storeQuota[10];
			$quota = $storeQuota[12];
			$storeName = $storeQuota[14];
			$storeAddress = $storeQuota[15];

			CampaignStoreQuota::create([
				'offer_id' => $offerID,
				'start_at' => $startAt,
				'end_at' => $endAt,
				'store_code' => $storeCode,
				'store_name' => $storeName,
				'store_address' => $storeAddress,
				'quota' => $quota,
			]);
		}

		//----------------------------------------------------------------------------------------
		//  Create default records for coupon pool
		$couponPoolArray = array(
			array(2, 'hktvmall', 'C000001', '448079969016dbf4f358fdc5021b779d', 'CODE-001', 'CODE-001', 'CODE-001'),
			array(2, 'hktvmall', 'C000002', '74c10fd2bb66a4cad8e033eb9a2d9865', 'CODE-002', 'CODE-002', 'CODE-002'),
			array(2, 'hktvmall', 'C000003', 'f612c7dd73aa40ee0a900219aeb74eed', 'CODE-003', 'CODE-003', 'CODE-003'),
			array(2, 'hktvmall', 'C000004', '37ffe5689b4fb52ea3d8657d901d3517', 'CODE-004', 'CODE-004', 'CODE-004'),
		);
		foreach ($couponPoolArray as $couponPool)  {

			$offerID = $couponPool[0];
			$storeCode = $couponPool[1];
			$uniqueCode = $couponPool[2];
			$uniqueName = $couponPool[3];
			$parameterA = $couponPool[4];
			$parameterB = $couponPool[5];
			$parameterC = $couponPool[6];

			CampaignCouponPool::create([
				'created_by' => 'Migration',
				'offer_id' => $offerID,
				'store_code' => $storeCode,
				'unique_code' => $uniqueCode,
				'unique_name' => $uniqueName,
				'parameter_a' => $parameterA,
				'parameter_b' => $parameterB,
				'parameter_c' => $parameterC,
			]);
		}

		//----------------------------------------------------------------------------------------
		//  Key visuals
		$keyVisualArray = array(
			array(1, '2022-03-25 09:26:22', '2022-03-25 09:26:22', NULL, 'key-visuals', '{"mobile":"/website/key-visuals/banner01_0623_v1@2x.png","desktop":"/website/key-visuals/banner01_0623_v1@2x.png"}', '{"mobile":"https://www.fimmick.com","desktop":"https://www.apple.com/"}', '2022-03-25 00:00:00', '2024-03-25 00:00:00', 100),
			array(1, '2022-03-25 09:26:22', '2022-03-25 09:26:22', NULL, 'key-visuals', '{"mobile":"/website/key-visuals/banner02_0623_v1@2x.png","desktop":"/website/key-visuals/banner02_0623_v1@2x.png"}', '{"mobile":"https://www.fimmick.com","desktop":"https://www.apple.com/"}', '2022-03-25 00:00:00', '2024-03-25 00:00:00', 100),
			array(1, '2022-03-25 09:26:22', '2022-03-25 09:26:22', NULL, 'key-visuals', '{"mobile":"/website/key-visuals/banner03_0623_v1@2x.png","desktop":"/website/key-visuals/banner03_0623_v1@2x.png"}', '{"mobile":"https://www.fimmick.com","desktop":"https://www.apple.com/"}', '2022-03-25 00:00:00', '2024-03-25 00:00:00', 100),
		);
		$startAt = date("Y-m-d 00:00:00");
		$endAt = date("Y-m-d 23:59:59", strtotime("+3 months"));

		foreach ($keyVisualArray as $record)  {

			$type = $record[4];
			$imageURL = $record[5];
			$targetURL = $record[6];
			$weight = $record[9];

			CampaignBanner::create([
				'type' => $type,
				'image_url' => $imageURL,
				'target_url' => $targetURL,
				'started_at' => $startAt,
				'ended_at' => $endAt,
				'weight' => $weight,
			]);
		}

		//----------------------------------------------------------------------------------------
		//  Small Banners
		$keyVisualArray = array(
			array(1, '2022-03-25 09:26:22', '2022-03-25 09:26:22', NULL, 'banners', '{"image":"/website/banners/banner_01.png"}', '{"url":"https://www.fimmick.com"}', '2022-03-25 00:00:00', '2024-03-25 00:00:00', 100),
			array(2, '2022-03-25 09:26:22', '2022-03-25 09:26:22', NULL, 'banners', '{"image":"/website/banners/banner_02.png"}', '{"url":"https://www.fimmick.com"}', '2022-03-25 00:00:00', '2024-03-25 00:00:00', 100),
			array(3, '2022-03-25 09:26:22', '2022-03-25 09:26:22', NULL, 'banners', '{"image":"/website/banners/banner_03.png"}', '{"url":"https://www.fimmick.com"}', '2022-03-25 00:00:00', '2024-03-25 00:00:00', 100),
			array(4, '2022-03-25 09:26:22', '2022-03-25 09:26:22', NULL, 'banners', '{"image":"/website/banners/banner_04.png"}', '{"url":"https://www.fimmick.com"}', '2022-03-25 00:00:00', '2024-03-25 00:00:00', 100),
		);
		$startAt = date("Y-m-d 00:00:00");
		$endAt = date("Y-m-d 23:59:59", strtotime("+3 months"));

		foreach ($keyVisualArray as $record)  {

			$type = $record[4];
			$imageURL = $record[5];
			$targetURL = $record[6];
			$weight = $record[9];

			CampaignBanner::create([
				'type' => $type,
				'image_url' => $imageURL,
				'target_url' => $targetURL,
				'started_at' => $startAt,
				'ended_at' => $endAt,
				'weight' => $weight,
			]);
		}

	}

}
