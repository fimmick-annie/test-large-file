<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020.  All rights reserved.
//----------------------------------------------------------------------------------------

namespace App\Http\Controllers;

//----------------------------------------------------------------------------------------

use App\Models\AppUser;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use GuzzleHttp;
use Session;

use App\Models\CampaignWhatsappMessageQueue;
use App\Models\CampaignStoreQuota;
use App\Models\CampaignCoupon;
use App\Models\CampaignOffer;
use App\Models\Coupon;
use App\Models\Offer;

//========================================================================================
class CampaignCouponController extends Controller  {

	//----------------------------------------------------------------------------------------
	//  Mark: Pages
	//----------------------------------------------------------------------------------------
	public function comingSoonPage(Request $request, $code)  {

		$offer = $request->offer;
		$offer->ini = parse_ini_file("./offers/".$offer->offer_name."/offer.ini", true);

		$trackingCodeArray = json_decode($offer->tracking_code, true);

		$bladeFolder = $request->offerBladeFolder;
		return view("campaigns/".$bladeFolder."/coupon_comingsoon", [
			"offer" => $offer,

			"gtm" => $trackingCodeArray["gtm"],
			"facebookPixel" => $trackingCodeArray["facebookPixel"],
			"googleAnalytics" => $trackingCodeArray["googleAnalytics"],
		]);
	}

	//----------------------------------------------------------------------------------------
	public function landingPage(Request $request, $code)  {

		//  Check if channel selected
		$offer = $request->offer;
		$coupon = $request->coupon;
		$bladeFolder = $request->offerBladeFolder;
		if (!empty($coupon->selected_channel))  {

			//  Check if activation ok
			$activationOK = $this->isActivationOK($offer, $coupon);
			if ($activationOK == true)  {

				//  Already selected channel and activation ok
				$expiryAt = strtotime($coupon->expiry_at);
				if ($coupon->use_at == null && $expiryAt > time() && $expiryAt != 0)  {

					$query = $request->all();
					$query["unique_code"] = $code;
// 					return redirect()->route('campaign.coupon.countdown.html', ["unique_code" => $code]);
					return redirect()->route('campaign.coupon.countdown.html', $query);
				}

				//  Already used or expired, show thank you view
				//  (Use redirect will cause forever loop)
				return view("campaigns/".$bladeFolder."/coupon_thankyou", [
					"offer" => $offer,
					"unique_code" => $code,
				]);
			}
		}

		//  TODO: Read member ID from database
		$mobile = $coupon->mobile;
		$memberID = md5($mobile);

		//----------------------------------------------------------------------------------------
		//  Cancel coupon 12:00 message
		$affectedRows = CampaignWhatsappMessageQueue::cancelMessages($coupon->id, "Coupon");

		//----------------------------------------------------------------------------------------
		$codeType = json_decode($offer->code_type);
		$bladeFolder = $request->offerBladeFolder;
		$offerCode = $offer->offer_code;
		$message = "{{referralLink}}";

		//  Get monitoring offer
		$json = json_decode($offer->webhook, true);
		if (isset($json["couponActivationReferralOfferID"]))  {

			$monitorOfferID = $json["couponActivationReferralOfferID"];
			$monitorOffer = CampaignOffer::getOfferByID($monitorOfferID);
			$offerCode = $monitorOffer->offer_code;

			$ini = parse_ini_file("./offers/".$monitorOffer->offer_name."/offer.ini", true);
			$message = $ini["offer_thankyou"]["referral_notification_whatsapp_content"];
		}

		//  Create referral URL
		$referralCode = $coupon->referral_code;

		$port = ($_SERVER["SERVER_PORT"] == "80" || $_SERVER["SERVER_PORT"] == "443") ? "" : ":".$_SERVER["SERVER_PORT"];
		$scheme = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? "https" : "http";
		$baseURL = $scheme."://".$_SERVER["SERVER_NAME"].$port."/";

		$referralURL = $baseURL."offer/".$offerCode."?r=".$referralCode;
		$referralMessage = str_replace("{{referralLink}}", $referralURL, $message);

		$trackingCodeArray = json_decode($offer->tracking_code, true);

		return view("campaigns/".$bladeFolder."/coupon_landing", [
			"offer" => $offer,
			"coupon" => $coupon,
			"codeType" => $codeType,
			"referralURL" => $referralURL,
			"referralMessage" => $referralMessage,

			"gtm" => $trackingCodeArray["gtm"],
			"facebookPixel" => $trackingCodeArray["facebookPixel"],
			"googleAnalytics" => $trackingCodeArray["googleAnalytics"],

			"memberID" => $memberID,
			"mobile" => $mobile,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function countdownPage(Request $request, $code)  {

		//  Check if channel selected
		$coupon = $request->coupon;
		if (empty($coupon->selected_channel))  {

			//  Not selected yet
			return redirect()->route('campaign.coupon.landing.html', ["unique_code" => $code]);
		}

		//  Check if already activated
		$offer = $request->offer;
		$activationOK = $this->isActivationOK($offer, $coupon);
		if ($activationOK == false)  {

			return redirect()->route('campaign.coupon.landing.html', ["unique_code" => $code]);
		}

		//  TODO: Read member ID from database
		$mobile = $coupon->mobile;
		$memberID = md5($mobile);

		//----------------------------------------------------------------------------------------
		//  Cancel coupon 12:00 message
		$affectedRows = CampaignWhatsappMessageQueue::cancelMessages($coupon->id, "Coupon");

		//----------------------------------------------------------------------------------------
		$longitude = 0;
		$latitude = 0;

		if ($request->exists("latitude"))  {$latitude = $request->input("latitude");}
		if ($request->exists("longitude"))  {$longitude = $request->input("longitude");}

		//----------------------------------------------------------------------------------------
		$codeTypeData = null;
		$channel = $coupon->selected_channel;
		$codeType = json_decode($offer->code_type);
		foreach ($codeType as $row)  {

			//  Record example:
			//  [{"type": "static"}]
			//  [{"type": "static", "channel": "mannings", "code_image": "coupon_barcode_mannings.png"}]
			if (isset($row->channel))  {

				$supportedChannel = $row->channel;
				if ($supportedChannel == $channel)  {

					$codeTypeData = $row;
					break;
				}
			}
		}

		$expiryAt = strtotime($coupon->expiry_at);
		$year = date("Y", $expiryAt);
		$month = date("m", $expiryAt);
		$day = date("d", $expiryAt);

		$trackingCodeArray = json_decode($offer->tracking_code, true);

		$couponCodeURL = "";
		$selectedRedemptionStore = "";
		$formData = json_decode($coupon->form_data, true);
		if ($formData != null)  {

			if (isset($formData["couponCodeURL"]))  {
				$couponCodeURL = $formData["couponCodeURL"];
			}

			if (isset($formData["selectedRedemptionStore"]))  {
				$selectedRedemptionStore = $formData["selectedRedemptionStore"];
			}
		}
		
		$bladeFolder = $request->offerBladeFolder;
		return view("campaigns/".$bladeFolder."/coupon_countdown", [
			"offer" => $offer,
			"uniqueCode" => $code,
			"codeType" => $codeTypeData,
			"couponCodeURL" => $couponCodeURL,
			"selectedRedemptionStore" => $selectedRedemptionStore,

			"year" => $year,
			"month" => $month,
			"day" => $day,

			"gtm" => $trackingCodeArray["gtm"],
			"facebookPixel" => $trackingCodeArray["facebookPixel"],
			"googleAnalytics" => $trackingCodeArray["googleAnalytics"],

			"longitude" => $longitude,
			"latitude" => $latitude,

			"memberID" => $memberID,
			"mobile" => $mobile,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function thankYouPage(Request $request, $code)  {

		//  Get last used coupon
		$selectedCoupon = CampaignCoupon::where('unique_code', $code)->whereNotNull('use_at')->orderBy('coupon_order', 'desc')->first();
		if (!$selectedCoupon)  {return null;}

		$session = Session::pull("couponThankYou");
		if (empty($session))  {

			//  Show coupon landing
			return redirect()->route("campaign.coupon.landing.html", ["unique_code" => $code]);
		}

		//  Show thank you page
		$offer = $selectedCoupon->offer;
		$offer->ini = parse_ini_file("./offers/".$offer->offer_name."/offer.ini", true);

		$bladeFolder = $offer->blade_folder;

		$request->offer = $offer;
		$request->offerBladeFolder = $bladeFolder;

		//  TODO: Read member ID from database
		$mobile = $selectedCoupon->mobile;
		$memberID = md5($mobile);

		//----------------------------------------------------------------------------------------
		//  TODO: Update this
		$confirmationMethod = "whatsapp";

		//  Prepare referral message
		$redeemStartAt = $selectedCoupon->start_at;
		$redeemEndAt = $selectedCoupon->expiry_at;

		$uniqueCode = $selectedCoupon->unique_code;
		$link = "https://".$_SERVER["SERVER_NAME"]."/".$uniqueCode."/";

		$port = ($_SERVER["SERVER_PORT"] == "80" || $_SERVER["SERVER_PORT"] == "443") ? "" : ":".$_SERVER["SERVER_PORT"];
		$scheme = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? "https" : "http";
		$baseURL = $scheme."://".$_SERVER["SERVER_NAME"].$port."/";

		$referralCode = $selectedCoupon->referral_code;
		$referralLink = $baseURL."offer/".$offer->offer_code."?r=".$referralCode;

		$message = "";
		$reminderTime = null;
		$reminderMessage = "";
		$referralMessage = "";
		switch ($confirmationMethod)  {

			case "whatsapp":  {
				if (isset($offer->ini["offer_thankyou"]["notification_whatsapp_content"]))  {
					$message = $offer->ini["offer_thankyou"]["notification_whatsapp_content"];
				}
				if (isset($offer->ini["offer_thankyou"]["reminder_notification_whatsapp_time"]))  {
					$reminderTime = $offer->ini["offer_thankyou"]["reminder_notification_whatsapp_time"];
				}
				if (isset($offer->ini["offer_thankyou"]["reminder_notification_whatsapp_content"]))  {
					$reminderMessage = $offer->ini["offer_thankyou"]["reminder_notification_whatsapp_content"];
				}
				if (isset($offer->ini["offer_thankyou"]["referral_notification_whatsapp_content"]))  {
					$referralMessage = $offer->ini["offer_thankyou"]["referral_notification_whatsapp_content"];
				}
			}  break;

			case "sms":  {
				if (isset($offer->ini["offer_thankyou"]["notification_sms_content"]))  {
					$message = $offer->ini["offer_thankyou"]["notification_sms_content"];
				}
				if (isset($offer->ini["offer_thankyou"]["reminder_notification_sms_time"]))  {
					$reminderTime = $offer->ini["offer_thankyou"]["reminder_notification_sms_time"];
				}
				if (isset($offer->ini["offer_thankyou"]["reminder_notification_sms_content"]))  {
					$reminderMessage = $offer->ini["offer_thankyou"]["reminder_notification_sms_content"];
				}
				if (isset($offer->ini["offer_thankyou"]["referral_notification_sms_content"]))  {
					$referralMessage = $offer->ini["offer_thankyou"]["referral_notification_sms_content"];
				}
			}  break;
		}

		if (!empty($message))  {

			$startAtDate = substr($redeemStartAt, 0, 10);
			$expiryAtDate = substr($redeemEndAt, 0, 10);

			//  Replace default keywords
			$searchArray = array(
//No need for JS				"\\n",
				"{{link}}", "{{referralLink}}", "{{referralCode}}",
				"{{startDate}}", "{{endDate}}",
//In-request				"{{selectedRedemptionStore}}",
			);
			$replaceArray = array(
//No need for JS				"\n",
				$link, $referralLink, $referralCode,
				$startAtDate, $expiryAtDate,
//In-request				$request->selectedRedemptionStore
			);

			$message = str_replace($searchArray, $replaceArray, $message);
			$reminderMessage = str_replace($searchArray, $replaceArray, $reminderMessage);
			$referralMessage = str_replace($searchArray, $replaceArray, $referralMessage);

			//  Replace custom keywords
			foreach ($request->all() as $key => $value)  {
				$message = str_replace("{{".$key."}}", $value, $message);
				$reminderMessage = str_replace("{{".$key."}}", $value, $reminderMessage);
				$referralMessage = str_replace("{{".$key."}}", $value, $referralMessage);
			}
		}

		$trackingCodeArray = json_decode($offer->tracking_code, true);

		//----------------------------------------------------------------------------------------
		//  Output
		return view("campaigns/".$bladeFolder."/coupon_thankyou", [
			"referralMessage" => $referralMessage,
			"offer" => $offer,
			"unique_code" => $code,

			"gtm" => $trackingCodeArray["gtm"],
			"facebookPixel" => $trackingCodeArray["facebookPixel"],
			"googleAnalytics" => $trackingCodeArray["googleAnalytics"],

			"memberID" => $memberID,
			"mobile" => $mobile,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function expiredPage(Request $request, $code)  {

		$offer = $request->offer;
		$offer->ini = parse_ini_file("./offers/".$offer->offer_name."/offer.ini", true);

		$trackingCodeArray = json_decode($offer->tracking_code, true);

		$bladeFolder = $request->offerBladeFolder;
		return view("campaigns/".$bladeFolder."/coupon_expired", [
			"offer" => $offer,

			"gtm" => $trackingCodeArray["gtm"],
			"facebookPixel" => $trackingCodeArray["facebookPixel"],
			"googleAnalytics" => $trackingCodeArray["googleAnalytics"],
		]);
	}

	//----------------------------------------------------------------------------------------
	public function invalidPage(Request $request, $code)  {

		$coupon = CampaignCoupon::where("unique_code", $code)->get()->first();
		if (empty($coupon))  {return view("campaigns/common/coupon_invalid");}

		$offerID = $coupon->offer_id;
		if ($offerID <= 0)  {return view("campaigns/common/coupon_invalid");}

		$offer = Offer::where("id", $offerID)->get()->first();
		if (empty($offer))  {return view("campaigns/common/coupon_invalid");}

		$bladeFolder = $offer->blade_folder;
		return view("campaigns/".$bladeFolder."/coupon_invalid". [
			"offer" => $offer,
		]);
	}

	//----------------------------------------------------------------------------------------
	//  Page for mobile app, let merchandiser void the coupon
	public function appPage(Request $request, $code)  {
		$coupon = CampaignCoupon::where("unique_code", $code)->get()->first();
		if (empty($coupon))  {return view("campaigns/common/coupon_invalid");}

		//  Get access token
		if ($request->exists("token") == false)  {return view("campaigns/common/coupon_invalid");}
		$token = $request->input("token");
		$appUser = AppUser::getUserByAuthToken($token);
		if(!$appUser) {
			return view("campaigns/common/coupon_invalid");
		}
		$offerID = $coupon->offer_id;
		if ($offerID <= 0)  {return view("campaigns/common/coupon_invalid");}

		//  TODO: Verify token with offer


		$offer = CampaignOffer::where("id", $offerID)->get()->first();
		if (empty($offer))  {return view("campaigns/common/coupon_invalid");}

		//----------------------------------------------------------------------------------------
		//  TODO: Show page
		$day = 0;
		$year = 0;
		$month = 0;
		$latitude = 0;
		$longitude = 0;
		$couponCodeURL = "";
		$codeTypeData = null;
		$trackingCodeArray = json_decode($offer->tracking_code, true);

		$bladeFolder = $offer->blade_folder;
		return view("campaigns/".$bladeFolder."/coupon_app", [
			"offer" => $offer,
			"codeType" => $codeTypeData,
			"uniqueCode" => $code,
			"couponCodeURL" => $couponCodeURL,

			"year" => $year,
			"month" => $month,
			"day" => $day,

			"gtm" => $trackingCodeArray["gtm"],
			"facebookPixel" => $trackingCodeArray["facebookPixel"],
			"googleAnalytics" => $trackingCodeArray["googleAnalytics"],

			"longitude" => $longitude,
			"latitude" => $latitude,
			'appUser' => $appUser,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function appAPI(Request $request, $code)  {

		return response()->json($request);
	}

	//----------------------------------------------------------------------------------------
	//  Mark: APIs
	//----------------------------------------------------------------------------------------
	public function startCountDownAPI(Request $request, $code)  {

		$channel = $request->input("channel");

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"message" => "Unknown error...",
			"status" => -99,
			"code" => $code,
		);

		//  Check if coupon already using or used
		$coupon = $request->coupon;
		if (!empty($coupon->selected_channel))  {

			//  Already selected channel, means it was using
			$response["status"] = -52;
			$response["message"] = "Channel already selected...";
			return response()->json($response);
		}

		//----------------------------------------------------------------------------------------
		//  Check if channel name support for this offer
		$offer = $request->offer;
		$codeType = json_decode($offer->code_type);
		foreach ($codeType as $row)  {

			$supportedChannel = $row->channel;
			if ($supportedChannel == $channel)  {

				//  Calculate real expiry date
				$expiryDuration = "";
				$expiry = json_decode($offer->channel_expiry);
				if (isset($expiry->{$channel}))  {

					//  Get specific expiry duration for channel
					$expiryDuration = $expiry->{$channel};
				}  else  {
					if (isset($expiry->default))  {

						//  Chanel key not found, use default expiry duration
						$expiryDuration = $expiry->default;
					}  else  {

						//  Both channel and default key are not found
						$expiryDuration = "+5 minutes";
					}
				}
				$expiryAt = date("Y-m-d H:i:s", strtotime($expiryDuration));

				//----------------------------------------------------------------------------------------
				//  TODO: Generate unique coupon code image

				//----------------------------------------------------------------------------------------
				//  Update coupon status
				$coupon->expiry_at = $expiryAt;
				$coupon->selected_channel = $channel;
				if ($coupon->save())  {

					//  Everything ok!
					$response["status"] = 0;
					$response["message"] = "Done";
					return response()->json($response);
				}

				//  Unable to update coupon
				$response["status"] = -51;
				$response["message"] = "Unable to update coupon...";
				return response()->json($response);
			}
		}

		//  Channel not supported
		$response["status"] = -50;
		$response["message"] = "Channel not supported...";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function voidCouponAPI(Request $request, $code)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"message" => "Unknown error...",
			"status" => -99,
			"code" => $code,
		);

		//  Get unused and non-expired coupon
		$offerID = $request->post("offerID");
		$coupon = $this->getCurrentCoupon($request, $code, $offerID);
		if ($coupon == null)  {

			//  Not a valid coupon
			$response["status"] = -21;
			$response["message"] = "Not a valid coupon...";
			return response()->json($response);
		}
		$request->coupon = $coupon;

		$originalArray = json_decode($coupon->form_data, true);
		if ($originalArray == null)  {$originalArray = array();}

		// Check store code, it must exists in database
		$parentOfferID = $coupon->parent_offer_id;
		// $storeCode is the user typing in 
		$storeCode = $request->post("pickedRedemptionStoreCode");

		// remark: Kay 2022.12.14 ------ 
		$dataArray = CampaignStoreQuota::checkStoreCode($parentOfferID, $storeCode);
		$count = count($dataArray);

		$count2 = 0;
		if (isset($originalArray["selectedRedemptionStore"]))  {

			$selectedRedemptionStore = $originalArray["selectedRedemptionStore"];
			if ($selectedRedemptionStore == $storeCode)  {$count2 = 1;}
		}

		// remark: Kay 2022.12.14 ------ 
		if ($count <= 0 && $count2 <= 0)  {
		// if ($count2 <= 0)  {
			//  Store not found
			$response["status"] = -20;
			$response["message"] = "Store code not found ($parentOfferID:$storeCode)...";
			return response()->json($response);
		}

		// -------- Kay 2022.12.14 ------------------------------------------------------------
		// checkout timeslot if in period
		// if (isset($originalArray["selectedRedemptionPeriodID"])){
		// 	$storeQuotaID = $originalArray["selectedRedemptionPeriodID"];
		// 	$store = CampaignStoreQuota::getStoreWithQuotaID($parentOfferID, $storeQuotaID);

		// 	$redeemStartAt = $store->start_at;
		// 	$redeemEndAt = $store->end_at;

		// 	$now = date("Y-m-d H:i:s");
		// 	if ($now < $redeemStartAt || $now > $redeemEndAt){
		// 		$response["status"] = -21;
		// 		$response["message"] = "Not in selected period ($redeemStartAt - $redeemEndAt)...";
		// 		return response()->json($response);
		// 	}
		// }
		// -------- Kay | End ----------------------------------------------------------------


		//  Update form data
		$mergedArray = array_merge($originalArray, $request->all());
		$coupon->form_data = json_encode($mergedArray);

		$coupon->use_at = date("Y-m-d H:i:s");

		//  Update coupon record
		if (!$coupon->save())  {

			//  Failed
			return response()->json($response);
		}

		//  Success
		$response["status"] = 0;
		$response["message"] = "Done";
		Session::put("couponThankYou", $code);

		//----------------------------------------------------------------------------------------
		//  Void existing coupon reminders
		$affectedRows = CampaignWhatsappMessageQueue::cancelMessages($coupon->id, "Reminder");

		//----------------------------------------------------------------------------------------
		//  Check if need notification for bundled offer
		$nextCoupon = $this->getNextOrLastCoupon($request, $code);
		$expiryAt = strtotime($nextCoupon->expiry_at);
		if ($nextCoupon->use_at == null && $expiryAt > time() && $expiryAt != 0)  {

			$couponID = $nextCoupon->id;
			$mobile = $nextCoupon->mobile;
			$nextOffer = $nextCoupon->offer;
			$nextOfferINI = parse_ini_file("./offers/".$nextOffer->offer_name."/offer.ini", true);

			$formDataDictionary = json_decode($nextCoupon->form_data, true);
			$confirmationMethod = $formDataDictionary["confirmationMethod"];

			$uniqueCode = $nextCoupon->unique_code;
			$link = "https://".$_SERVER["SERVER_NAME"]."/".$uniqueCode."/";

			$prefix = env("WHATSAPP_PREFIX", "");

			//  Prepare message for notification
			$message = "";
			$reminderTime = null;
			$reminderMessage = "";
			switch ($confirmationMethod)  {

				case "whatsapp":  {
					$message = $nextOfferINI["offer_thankyou"]["notification_whatsapp_content"];
					$reminderTime = $nextOfferINI["offer_thankyou"]["reminder_notification_whatsapp_time"];
					$reminderMessage = $nextOfferINI["offer_thankyou"]["reminder_notification_whatsapp_content"];
				}  break;

				case "sms":  {
					$message = $nextOfferINI["offer_thankyou"]["notification_sms_content"];
					$reminderTime = $nextOfferINI["offer_thankyou"]["reminder_notification_sms_time"];
					$reminderMessage = $nextOfferINI["offer_thankyou"]["reminder_notification_sms_content"];
				}  break;
			}

			if (!empty($message))  {

				$json = json_decode($coupon->form_data, true);

				// $storeQuotaID = $mergedArray["selectedRedemptionPeriodID"];
				// $store = CampaignStoreQuota::getStoreWithQuotaID($parentOfferID, $storeQuotaID);

				// $redeemStartAt = $store->start_at;
				// $redeemEndAt = $store->end_at;

				$startAtDate = substr($redeemStartAt, 0, 10);
				$expiryAtDate = substr($redeemEndAt, 0, 10);

				$referralCode = $coupon->referral_code;

				$port = ($_SERVER["SERVER_PORT"] == "80" || $_SERVER["SERVER_PORT"] == "443") ? "" : ":".$_SERVER["SERVER_PORT"];
				$scheme = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? "https" : "http";
				$baseURL = $scheme."://".$_SERVER["SERVER_NAME"].$port."/";

				$referralLink = $baseURL."offer/".$nextOffer->offer_code."?r=".$referralCode;

				//  Replace default keywords
				$searchArray = array(
					"\\n",
					"{{link}}", "{{referralLink}}", "{{referralCode}}",
					"{{startDate}}", "{{endDate}}",
//In-merge					"{{selectedRedemptionStore}}",
				);
				$replaceArray = array(
					"\n",
					$link, $referralLink, $referralCode,
					$startAtDate, $expiryAtDate,
//In-merge					$mergedArray["selectedRedemptionStore"],
				);

				$message = str_replace($searchArray, $replaceArray, $message);
				$reminderMessage = str_replace($searchArray, $replaceArray, $reminderMessage);

				//  Replace custom keywords
				foreach ($mergedArray as $key => $value)  {
					$message = str_replace("{{".$key."}}", $value, $message);
					$reminderMessage = str_replace("{{".$key."}}", $value, $reminderMessage);
				}

				//----------------------------------------------------------------------------------------
				//  Send out notification
				$message = $prefix.$message;
				switch ($confirmationMethod)  {

					case "sms":  {
						$receiver = $mobile;
						$sender = "Fairvilla";

// 							$sms = new Emma([
// 								'to' => $receiver,
// 								'from' => $sender,
// 								'message' => $message,
// // 								'member_num'         => '',  // Optional
// // 								'purpose'            => '',  // Optional
// // 								'correlation_id'     => '',  // Optional
// // 								'scheduled_datetime' => '',  // Optional
// 							]);
// 							$sms->send();
					}  break;

					case "whatsapp":  {
						$receiver = "whatsapp:".$mobile;
						$sid = env('WHATSAPP_SID', '');
						$token = env('WHATSAPP_TOKEN', '');
						$sender = env('WHATSAPP_SENDER', '');

						$messageID = null;

						$whatsAppEnabled = env('WHATSAPP_ENABLED', false);
						if ($whatsAppEnabled == true)  {

							$twilio = new Client($sid, $token);
							$result = $twilio->messages->create($receiver, [
								"from" => $sender,
								"body" => $message
							]);
							$status = "Success";

							$messageID = CampaignWhatsappMessageQueue::getTwilioMessageID($result);

						}  else  {
							$status = "Success";
							$result = "[Twilio.Api.V2010.MessageInstance accountSid=ACa8c4e3793f543cc3b4d68b112171edf1 sid=SM940bd08464984df49cd8f246e683704a] Simulation";
							$messageID = "Simulation";
						}
						$response["whatsapp"] = $result;

						//  Add to whatsapp log
						$now = date("Y-m-d H:i:s");
						$whatsAppQueue = new CampaignWhatsappMessageQueue();
						$whatsAppQueue->created_by = basename(__FILE__);
						$whatsAppQueue->offer_id = $nextOffer->id;
						$whatsAppQueue->coupon_id = $couponID;
						$whatsAppQueue->mobile = $mobile;
						$whatsAppQueue->message = $message;
						$whatsAppQueue->message_type = "Thank-you-2";
						$whatsAppQueue->schedule_at = $now;
						$whatsAppQueue->expiry_at = $expiryAt;		//20201109 Pacess
						$whatsAppQueue->send_at = $now;
						$whatsAppQueue->vendor = "twilio";
						$whatsAppQueue->message_id = $messageID;
						$whatsAppQueue->status = $status;
						$whatsAppQueue->response = $result;
						$whatsAppQueue->save();
					}  break;
				}
			}

			//  Reminder notification
			if ($reminderTime != null && !empty($reminderMessage))  {

				$reminderMessage = $prefix.$reminderMessage;
				$time = date("Y-m-d H:i:s", strtotime($reminderTime));

				$whatsAppQueue = new CampaignWhatsappMessageQueue();
				$whatsAppQueue->created_by = basename(__FILE__);
				$whatsAppQueue->offer_id = $nextOffer->id;
				$whatsAppQueue->coupon_id = $couponID;
				$whatsAppQueue->priority = 50;
				$whatsAppQueue->mobile = $mobile;
				$whatsAppQueue->message = $reminderMessage;
				$whatsAppQueue->message_type = "Reminder";
				$whatsAppQueue->schedule_at = $time;
				$whatsAppQueue->expiry_at = $expiryAt;		//20201109 Pacess
				$whatsAppQueue->save();
			}
		}
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function qrcodeAPI(Request $request)  {
		if ($request->exists("c") == false)  {return null;}

		$size = 300;
		if ($request->exists("s"))  {$size = intval($request->input("s"));}

		//  Read value from URL
		$content = $request->input("c");
		if ($request->exists("l") == true)  {

			//  Generate PNG QR code image with logo
			$logoURL = $request->input("l");
			$qrcodePNG = \SimpleSoftwareIO\QrCode\Facades\QrCode::size($size)
				->format("png")
				->merge($logoURL, .25, true)
				->generate($content);

		}  else  {

			//  Generate PNG QR code image
			$qrcodePNG = \SimpleSoftwareIO\QrCode\Facades\QrCode::size($size)
				->format("png")
				->generate($content);
		}
		
		//----------------------------------------------------------------------------------------
		//  Support background
		if ($request->exists("b") == true)  {

			$x = 0;  $y = 0;
			if ($request->exists("x"))  {$x = intval($request->input("x"));}
			if ($request->exists("y"))  {$y = intval($request->input("y"));}

			$backgroundURL = $request->input("b");
			$backgroundPNG = file_get_contents($backgroundURL);
			$backgroundImage = imagecreatefromstring($backgroundPNG);
			$backgroundWidth = imagesx($backgroundImage);
			$backgroundHeight = imagesy($backgroundImage);

			$qrcodeImage = imagecreatefromstring($qrcodePNG);
			$qrWidth = imagesx($qrcodeImage);
			$qrHeight = imagesy($qrcodeImage);

			$dx = $x+($backgroundWidth-$qrWidth)/2;
			$dy = $y+($backgroundHeight-$qrHeight)/2;

			$finalImage = imagecreatetruecolor($backgroundWidth, $backgroundHeight);
			imagecopy($finalImage, $backgroundImage, 0, 0, 0, 0, $backgroundWidth, $backgroundHeight);
			imagecopyresampled($finalImage, $qrcodeImage, $dx, $dy, 0, 0, $qrWidth, $qrHeight, $qrWidth, $qrHeight);

			ob_start();
			imagepng($finalImage);
			$qrcodePNG = ob_get_clean();

			imagedestroy($backgroundImage);
			imagedestroy($qrcodeImage);
			imagedestroy($finalImage);
		}

		return response($qrcodePNG)->header("Content-Type", "image/png");
	}

	//----------------------------------------------------------------------------------------
	//  MARK: Helper function
	//----------------------------------------------------------------------------------------
	private function getNextOrLastCoupon(Request $request, $code)  {

		$couponArray = CampaignCoupon::where('unique_code', $code)->orderBy('coupon_order', 'asc')->get();
		$selectedCoupon = $couponArray->first();
		if (!$selectedCoupon)  {return null;}

		//  If more than one coupon, get the last un-used one or last one
		foreach ($couponArray as $coupon)  {

			$selectedCoupon = $coupon;

			$expiryAt = strtotime($coupon->expiry_at);
			if ($coupon->use_at == null && $expiryAt > time() && $expiryAt != 0)  {

				//  Coupon not used yet and not expired yet, pick this one
				break;
			}
		}
		return $selectedCoupon;
	}

	//----------------------------------------------------------------------------------------
	private function getCurrentCoupon(Request $request, $code, $offerID)  {

		$coupon = CampaignCoupon::where('unique_code', $code)
			->where('offer_id', $offerID)
			->whereNull('use_at')
			->orderBy('coupon_order', 'asc')
			->get()
			->first();

		return $coupon;
	}

	//----------------------------------------------------------------------------------------
	//  Check if coupon has been activated
	public function isActivationOK($offer, $coupon)  {
		$webhook = $offer->webhook;
		if (empty($webhook))  {return true;}

		$json = json_decode($webhook, true);
		if ($json == null)  {return true;}

		if (isset($json["couponActivationWebhookType"]) == false)  {return true;}

		$type = intval($json["couponActivationWebhookType"]);
		switch ($type)  {

			//  None
			default:
			case 10:  return true;

			//  Internal
			case 20:  {
				$offerID = intval($json["couponActivationReferralOfferID"]);
				if ($offerID <= 0)  {return true;}

				$referralCount = intval($json["couponActivationReferralCount"]);

				$mobile = $coupon->mobile;
				$dataArray = CampaignCoupon::getCouponByOfferIDs($mobile, $offerID);
				$count = count($dataArray);
				if ($count <= 0)  {return false;}

				$parentOffer = $dataArray[0];
				$referralData = $parentOffer->referral_data;
				if (empty($referralData))  {return false;}

				$json = json_decode($referralData, true);
				if ($json == null)  {return false;}

				if (isset($json["registration"]) == false)  {return false;}
				$registration = intval($json["registration"]);
				if ($registration >= $referralCount)  {return true;}
			}  break;

			//  External
			case 30:  {
				$url = $json["couponActivationWebhookURL"];
				if (strpos($url, "http") !== false)  {

					//  External rules webhook
					$dictionary = $request->all();
					$dictionary["offerID"] = $offerID;
					$dictionary["couponID"] = $coupon->id;
					$dictionary["mobile"] = $coupon->mobile;
					$dictionary["unique_code"] = $coupon->unique_code;

					$client = new GuzzleHttp\Client();
					$result = $client->request("POST", $url, [
						"body" => json_encode($dictionary),
					]);
					$body = (string)($result->getBody());
					$response["result"] = $body;

					//  Check if can continue
					$json = json_decode($body, true);
					return ($json["status"] >= 0);
				}
			}  break;
		}
		return false;
	}
}
