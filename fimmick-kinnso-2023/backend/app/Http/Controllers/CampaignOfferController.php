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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Twilio\Rest\Client;
use GuzzleHttp;
use Session;

use App\Libraries\Sms\Emma;
use App\Models\CampaignWhatsappMessageQueue;
use App\Models\CampaignCouponLinkRequest;
use App\Models\CampaignStoreQuota;
use App\Models\CampaignCouponPool;
use App\Models\SegmentExchange;
use App\Models\CampaignListing;
use App\Models\CampaignBanner;
use App\Models\CampaignCoupon;
use App\Models\CampaignOffer;
use App\Models\CampaignForm;
use App\Models\Member;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

//========================================================================================
class CampaignOfferController extends Controller  {

	//----------------------------------------------------------------------------------------
	//  Mark: Pages
	//----------------------------------------------------------------------------------------
	public function listingPage(Request $request)  { // landing page
		$userAgent = $request->server('HTTP_USER_AGENT');
		
		$dictionary = $this->getDeviceAndOS($userAgent);
		$operatingSystem = $dictionary["operatingSystem"];
		$device = $dictionary["device"];

		$referrerCodefromMember = $request->input('referral');
		if (!empty($referrerCodefromMember)){
			// TODO-session
			Session::put("referrerCodefromMember", $referrerCodefromMember);
		}

		return view("campaigns/offer_listing", [
			"ipAddress" => $request->ip(),
			"userAgent" => $userAgent,
			"operatingSystem" => $operatingSystem,
			"device" => $device,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function filterPage(Request $request)  {
		$filterData = [
			'category' => null,
			'hot_topic' => null,
			'filters' => null,
		];
		$filterQuery = '';
		if (isset($request->category) and
			!empty($request->category) and
			is_string($request->category))  {

			$filterData['category'] = $request->category;
			$filterQuery = '?category='.urlencode($request->category);
		} else if (isset($request->filter) and !empty($request->filter))  {

			if (is_string($request->filter))  {

				$filterData['hot_topic'] = $request->filter;
				$filterQuery = '?filter='.urlencode($request->filter);
			} else if (is_array($request->filter))  {

				$filterData['filters'] = json_encode($request->filter);
				$items = [];
				foreach($request->filter as $item)  {

					$items[] = urlencode($item);
				}
				$filterQuery = '?filter='.implode('+', $items);
			}
		}
		$userAgent = $request->server('HTTP_USER_AGENT');

		$dictionary = $this->getDeviceAndOS($userAgent);
		$operatingSystem = $dictionary["operatingSystem"];
		$device = $dictionary["device"];

		return view("campaigns/offer_filter", [
			"ipAddress" => $request->ip(),
			"userAgent" => $userAgent,
			"operatingSystem" => $operatingSystem,
			"device" => $device,
			"filterData" => $filterData,
			"filterQuery" => $filterQuery,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function listingPageApi(Request $request)  {

		//  Get current available offers
		$listName = $request->input('listName', 'default');
		$now = date("Y-m-d H:i:s");
		return $this->getOfferListing($listName, $now);
	}

	//----------------------------------------------------------------------------------------
	public function getOfferListing($listName, $now)  {

// 		$array = CampaignListing::query()
// 			->where("campaign_listings.end_at", ">=", $now)
// 			->where("campaign_listings.start_at", "<=", $now)
// 			->where("list_name", $listName)
// 			->orderBy("ordering", "desc")
// 			->leftJoin('campaign_offers', 'campaign_listings.offer_id', 'campaign_offers.id')
//
// // 			->select('campaign_listings.*', 'campaign_offers.*')
//
// 			//  Same field name will take the tails
// 			->select(
// 				'campaign_listings.offer_id',
// 				'campaign_listings.start_at',
// 				'campaign_listings.end_at',
// 				'campaign_offers.offer_code',
// 				'campaign_offers.offer_name',
// 				'campaign_offers.offer_title',
// 				'campaign_offers.offer_subtitle',
// 				'campaign_offers.start_at',
// 				'campaign_offers.end_at',
// 				'campaign_offers.likeCounter',
// 				'campaign_offers.end_at > now() AS expired')
// 			->get();

		$array = CampaignListing::query()
			->where("campaign_listings.end_at", ">=", $now)
			->where("campaign_listings.start_at", "<=", $now)
			->where("list_name", $listName)
			->orderBy("ordering", "desc")
			->leftJoin('campaign_offers', 'campaign_listings.offer_id', 'campaign_offers.id')
			->select('campaign_listings.*', 'campaign_offers.*')
			->get();
		return $array;
	}

	//----------------------------------------------------------------------------------------
	public function offerDetailsPage(Request $request, $offerCode)  {

		$offer = $request->offer;
		$offerID = $offer->id;
		$codeType = json_decode($offer->code_type);

		CampaignOffer::normalOpen($offerCode);

		//  r: for offer referral
		$referrerCode = $request->input("r");
		if (strlen($referrerCode) > 0)  {
			$request->session()->put("referrerCode", $referrerCode);

			//  Save referral statistics
			CampaignCoupon::referOpen($offerID, $referrerCode);
		}

		//  m: for member referral
		$referralCode = $request->input("m");
		if (strlen($referralCode) > 0)  {
			$request->session()->put("referrerCodefromMember", $referralCode);
		}
		
		//  Get store list
		$storeArray = CampaignStoreQuota::getStoreListWithQuotaFlag($offerID);

		$trackingCodeArray = json_decode($offer->tracking_code, true);

		$bladeFolder = $request->offerBladeFolder;

		//  Below is for new WhatsApp flow start from Nestle
		$brandName = strtolower(env("BRAND_NAME", ""));
		$brandName = str_replace(" ", "-", $brandName);

		//  WhatsApp URL
		$offer->ini = parse_ini_file("./offers/".$offer->offer_name."/offer.ini", true);

		$sender = env("WHATSAPP_SENDER", "");
		$sender = str_replace("whatsapp:+", "", $sender);

		$whatsappURL = "";
		if (isset($offer->ini["settings"]["whatsapp_trigger_message"]))  {
			$triggerMessage = $offer->ini["settings"]["whatsapp_trigger_message"];
			$whatsappURL = "https://wa.me/".$sender."?text=".urlencode($triggerMessage);
		}

		$description = "";
		if (isset($offer->ini["settings"]["offer_description"]))  {
			$description = $offer->ini["settings"]["offer_description"];
			$description = str_replace("\\n", "\n", $description);
		}

		$readMoreParagraph = "";
		if (isset($offer->ini["offer_details"]["subject_readmore_paragraph"]))  {
			$readMoreParagraph = $offer->ini["offer_details"]["subject_readmore_paragraph"];
			$readMoreParagraph = str_replace("\\n", "\n", $readMoreParagraph);
		}

		$highlightParagraph = "";
		if (isset($offer->ini["offer_details"]["highlight_paragraph"]))  {
			$highlightParagraph = $offer->ini["offer_details"]["highlight_paragraph"];
			$highlightParagraph = str_replace("\\n", "\n", $highlightParagraph);
		}

		$sharingMessage = "";
		if (isset($offer->ini["settings"]["sharing_message"]))  {
			$sharingMessage = $offer->ini["settings"]["sharing_message"];
			$sharingMessage = str_replace("\\n", "\n", $sharingMessage);
		}

		$userAgent = $request->server('HTTP_USER_AGENT');

		$dictionary = $this->getDeviceAndOS($userAgent);
		$operatingSystem = $dictionary["operatingSystem"];
		$device = $dictionary["device"];
		
		return view("campaigns/".$bladeFolder."/offer_details", [
			"offer" => $offer,
			"codeType" => $codeType,
			"storeArray" => $storeArray,
			"whatsappURL" => $whatsappURL,
			"selectedChannel" => $brandName,

			"description" => $description,
			"sharingMessage" => $sharingMessage,
			"readMoreParagraph" => $readMoreParagraph,
			"highlightParagraph" => $highlightParagraph,

			"gtm" => $trackingCodeArray["gtm"],
			"facebookPixel" => $trackingCodeArray["facebookPixel"],
			"googleAnalytics" => $trackingCodeArray["googleAnalytics"],

			"ipAddress" => $request->ip(),
			"userAgent" => $userAgent,
			"operatingSystem" => $operatingSystem,
			"device" => $device,

			// "refCode" => $refCode,
		]);
	}

	//----------------------------------------------------------------------------------------
	//  We don't want user access thank you page directly, so session must be exists
	public function thankYouPage(Request $request, $offerCode)  {

		if ($request->session()->has("offerThankYou") == false)  {
			return redirect()->route("campaign.offer.details.html", ["offer_code" => $offerCode]);
		}

		$mobile = Session::pull("offerThankYou");
		$uniqueCode = Session::pull("uniqueCode");
		$referralCode = Session::pull("referralCode");
		if (empty($mobile))  {
			return redirect()->route("campaign.offer.details.html", ["offer_code" => $offerCode]);
		}

		//  If debug then allow re-visiting thank you page
		$appDebug = config("app.debug");
		if ($appDebug == true)  {
			Session::put("offerThankYou", $mobile);
			Session::put("uniqueCode", $uniqueCode);
			Session::put("referralCode", $referralCode);
		}

		$offer = $request->offer;
		$offer->ini = parse_ini_file("./offers/".$offer->offer_name."/offer.ini", true);

		$trackingCodeArray = json_decode($offer->tracking_code, true);

		//  我想領取「DREYER’S D-COLLECTION™全新三色脆皮雪糕批系列」OK便利店半價(HK$12)電子優惠券
// 		$prefix = env("WHATSAPP_PREFIX", "");
// 		$message = $prefix.__("messages.WHATSAPP_TIGGER_REGISTRATION_DONE", ["referralCode" => $referralCode]);

		$triggerMessage = __("messages.WHATSAPP_TIGGER_REGISTRATION_DONE", ["referralCode" => $referralCode]);
		if (isset($offer->ini["settings"]["whatsapp_trigger_message"]))  {
			$triggerMessage = $offer->ini["settings"]["whatsapp_trigger_message"].$triggerMessage;
		}

		$description = "";
		if (isset($offer->ini["settings"]["offer_description"]))  {
			$description = $offer->ini["settings"]["offer_description"];
			$description = str_replace("\\n", "\n", $description);
		}

		$sharingMessage = "";
		if (isset($offer->ini["settings"]["sharing_message"]))  {
			$sharingMessage = $offer->ini["settings"]["sharing_message"];
			$sharingMessage = str_replace("\\n", "\n", $sharingMessage);
		}

		$prefix = env("WHATSAPP_PREFIX", "");
		$message = $prefix.$triggerMessage;

		$sender = env("WHATSAPP_SENDER", "whatsapp:+85264500628");
		$mobileNumberOnly = str_replace("whatsapp:+", "", $sender);
		$whatsAppLink = "https://wa.me/".$mobileNumberOnly."?text=".urlencode($message);

		//----------------------------------------------------------------------------------------
		$bladeFile = "offer_thankyou";
		$sendWhatsAppNow = env("SEND_WHATSAPP_AFTER_REGISTRATION", false);
		if ($sendWhatsAppNow == false)  {

			//  Thank you page should include a WhatsApp deep link
			$bladeFile = "offer_thankyou_deeplink";
		}

		//----------------------------------------------------------------------------------------
		//  Output
		$bladeFolder = $request->offerBladeFolder;
		$userAgent = $request->server('HTTP_USER_AGENT');

		$dictionary = $this->getDeviceAndOS($userAgent);
		$operatingSystem = $dictionary["operatingSystem"];
		$device = $dictionary["device"];

		return view("campaigns/".$bladeFolder."/".$bladeFile, [
			"offer" => $offer,
			"description" => $description,
			"whatsAppLink" => $whatsAppLink,
			"sharingMessage" => $sharingMessage,

			"gtm" => $trackingCodeArray["gtm"],
			"facebookPixel" => $trackingCodeArray["facebookPixel"],
			"googleAnalytics" => $trackingCodeArray["googleAnalytics"],

			"ipAddress" => $request->ip(),
			"userAgent" => $userAgent,
			"operatingSystem" => $operatingSystem,
			"device" => $device,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function comingSoonPage(Request $request, $offerCode)  {
		$offer = $request->offer;
		$offer->ini = parse_ini_file("./offers/".$offer->offer_name."/offer.ini", true);

		CampaignOffer::normalOpen($offerCode);

		$trackingCodeArray = json_decode($offer->tracking_code, true);

		$description = "";
		if (isset($offer->ini["settings"]["offer_description"]))  {
			$description = $offer->ini["settings"]["offer_description"];
			$description = str_replace("\\n", "\n", $description);
		}

		$sharingMessage = "";
		if (isset($offer->ini["settings"]["sharing_message"]))  {
			$sharingMessage = $offer->ini["settings"]["sharing_message"];
			$sharingMessage = str_replace("\\n", "\n", $sharingMessage);
		}

		$bladeFolder = $request->offerBladeFolder;
		$userAgent = $request->server('HTTP_USER_AGENT');

		$dictionary = $this->getDeviceAndOS($userAgent);
		$operatingSystem = $dictionary["operatingSystem"];
		$device = $dictionary["device"];

		return view("campaigns/".$bladeFolder."/offer_comingsoon", [
			"offer" => $offer,
			"description" => $description,
			"sharingMessage" => $sharingMessage,

			"gtm" => $trackingCodeArray["gtm"],
			"facebookPixel" => $trackingCodeArray["facebookPixel"],
			"googleAnalytics" => $trackingCodeArray["googleAnalytics"],

			"ipAddress" => $request->ip(),
			"userAgent" => $userAgent,
			"operatingSystem" => $operatingSystem,
			"device" => $device,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function expiredPage(Request $request, $offerCode)  {
		$offer = $request->offer;
		$offer->ini = parse_ini_file("./offers/".$offer->offer_name."/offer.ini", true);

		CampaignOffer::normalOpen($offerCode);

		$trackingCodeArray = json_decode($offer->tracking_code, true);

		$description = "";
		if (isset($offer->ini["settings"]["offer_description"]))  {
			$description = $offer->ini["settings"]["offer_description"];
			$description = str_replace("\\n", "\n", $description);
		}

		$sharingMessage = "";
		if (isset($offer->ini["settings"]["sharing_message"]))  {
			$sharingMessage = $offer->ini["settings"]["sharing_message"];
			$sharingMessage = str_replace("\\n", "\n", $sharingMessage);
		}

		$bladeFolder = $request->offerBladeFolder;
		$userAgent = $request->server('HTTP_USER_AGENT');

		$dictionary = $this->getDeviceAndOS($userAgent);
		$operatingSystem = $dictionary["operatingSystem"];
		$device = $dictionary["device"];

		return view("campaigns/".$bladeFolder."/offer_expired", [
			"offer" => $offer,
			"description" => $description,
			"sharingMessage" => $sharingMessage,

			"gtm" => $trackingCodeArray["gtm"],
			"facebookPixel" => $trackingCodeArray["facebookPixel"],
			"googleAnalytics" => $trackingCodeArray["googleAnalytics"],

			"ipAddress" => $request->ip(),
			"userAgent" => $userAgent,
			"operatingSystem" => $operatingSystem,
			"device" => $device,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function outOfQuotaPage(Request $request, $offerCode)  {
		$offer = $request->offer;
		$offer->ini = parse_ini_file("./offers/".$offer->offer_name."/offer.ini", true);

		CampaignOffer::normalOpen($offerCode);

		$trackingCodeArray = json_decode($offer->tracking_code, true);

		$bladeFolder = $request->offerBladeFolder;

		$description = "";
		if (isset($offer->ini["settings"]["offer_description"]))  {
			$description = $offer->ini["settings"]["offer_description"];
			$description = str_replace("\\n", "\n", $description);
		}

		$readMoreParagraph = "";
		if (isset($offer->ini["offer_details"]["subject_readmore_paragraph"]))  {
			$readMoreParagraph = $offer->ini["offer_details"]["subject_readmore_paragraph"];
			$readMoreParagraph = str_replace("\\n", "\n", $readMoreParagraph);
		}

		$highlightParagraph = "";
		if (isset($offer->ini["offer_details"]["highlight_paragraph"]))  {
			$highlightParagraph = $offer->ini["offer_details"]["highlight_paragraph"];
			$highlightParagraph = str_replace("\\n", "\n", $highlightParagraph);
		}

		$sharingMessage = "";
		if (isset($offer->ini["settings"]["sharing_message"]))  {
			$sharingMessage = $offer->ini["settings"]["sharing_message"];
			$sharingMessage = str_replace("\\n", "\n", $sharingMessage);
		}

		$userAgent = $request->server('HTTP_USER_AGENT');

		$dictionary = $this->getDeviceAndOS($userAgent);
		$operatingSystem = $dictionary["operatingSystem"];
		$device = $dictionary["device"];

		return view("campaigns/".$bladeFolder."/offer_outofquota", [
			"offer" => $offer,

			"description" => $description,
			"sharingMessage" => $sharingMessage,
			"readMoreParagraph" => $readMoreParagraph,
			"highlightParagraph" => $highlightParagraph,

			"gtm" => $trackingCodeArray["gtm"],
			"facebookPixel" => $trackingCodeArray["facebookPixel"],
			"googleAnalytics" => $trackingCodeArray["googleAnalytics"],

			"ipAddress" => $request->ip(),
			"userAgent" => $userAgent,
			"operatingSystem" => $operatingSystem,
			"device" => $device,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function getCouponLinkPage(Request $request, $offerCode)  {
		$offer = $request->offer;
		$offer->ini = parse_ini_file("./offers/".$offer->offer_name."/offer.ini", true);

		$trackingCodeArray = json_decode($offer->tracking_code, true);

		$description = "";
		if (isset($offer->ini["settings"]["offer_description"]))  {
			$description = $offer->ini["settings"]["offer_description"];
			$description = str_replace("\\n", "\n", $description);
		}

		$sharingMessage = "";
		if (isset($offer->ini["settings"]["sharing_message"]))  {
			$sharingMessage = $offer->ini["settings"]["sharing_message"];
			$sharingMessage = str_replace("\\n", "\n", $sharingMessage);
		}

		$bladeFolder = $request->offerBladeFolder;
		$userAgent = $request->server('HTTP_USER_AGENT');

		$dictionary = $this->getDeviceAndOS($userAgent);
		$operatingSystem = $dictionary["operatingSystem"];
		$device = $dictionary["device"];

		return view("campaigns/".$bladeFolder."/offer_coupon_link_request", [
			"offer" => $offer,
			"description" => $description,
			"sharingMessage" => $sharingMessage,

			"gtm" => $trackingCodeArray["gtm"],
			"facebookPixel" => $trackingCodeArray["facebookPixel"],
			"googleAnalytics" => $trackingCodeArray["googleAnalytics"],

			"ipAddress" => $request->ip(),
			"userAgent" => $userAgent,
			"operatingSystem" => $operatingSystem,
			"device" => $device,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function sitemapPage()  {
		$now = date("Y-m-d H:i:s");
		$offerArray = $this->getOfferListing("default", $now);

		return response()->view("website/sitemap", [
			"offerArray" => $offerArray,
		])->header("Content-Type", "text/xml");
	}

	//----------------------------------------------------------------------------------------
	//  Mark: APIs
	//----------------------------------------------------------------------------------------
	public function timeSlotAPI(Request $request, $offerCode)  {

		//  0 = Done
		//  -99 = Unexpected error...
		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$offer = $request->offer;
		$offerID = $offer->id;

		$storeName = $request->input("store_name");
		if (empty($storeName))  {

			//  Invalid store name
			$response["status"] = -20;
			$response["message"] = "Invalid store name...";
			return response()->json($response);
		}

		$periodArray = CampaignStoreQuota::getStorePeriod($offerID, $storeName);
		$response["periodArray"] = $periodArray;
		$response["status"] = 0;
		$response["message"] = "Done!";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	//  offer_code
	//  offer
	//  mobile
	//  email
	//  selectedChannel
	//  confirmationMethod
	//  selectedRedemptionPeriodID
	public function registerAPI(Request $request, $offerCode)  {

		//  Check mobile if exists in database, return error if exists
		$offer = $request->offer;
		$mobile = $request->input("areaCode").$request->input("mobile");
		$email = $request->input("email");

		$selectedChannel = $request->input("selectedChannel");
		$confirmationMethod = $request->input("confirmationMethod");
		$selectedRedemptionStore = $request->input("selectedRedemptionStore");
		$selectedRedemptionPeriodID = $request->input("selectedRedemptionPeriodID");

		$referrerCode = "";
		if ($request->session()->has("referrerCode"))  {
			$referrerCode = $request->session()->get("referrerCode", "");
		}

		//----------------------------------------------------------------------------------------
		//  Register and issue coupon now
		$dictionary = array(
			"email" => $email,
			"offer" => $offer,
			"mobile" => $mobile,
			"offerCode" => $offerCode,
			"referrerCode" => $referrerCode,
			"selectedChannel" => $selectedChannel,
			"confirmationMethod" => $confirmationMethod,
			"selectedRedemptionStore" => $selectedRedemptionStore,
			"selectedRedemptionPeriodID" => $selectedRedemptionPeriodID,
		);

		foreach ($request->all() as $key => $value)  {

			//  Cannot override existing keys
			if (isset($dictionary[$key]))  {continue;}

			$dictionary[$key] = $value;
		}

		$response = $this->registerAndIssueCoupon($dictionary);
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	//  This function was made for calling by other controller easily
	public function registerAndIssueCoupon($parameterDictionary)  {

		$email = $parameterDictionary["email"];
		$offer = $parameterDictionary["offer"];
		$mobile = $parameterDictionary["mobile"];
		$offerCode = $parameterDictionary["offerCode"];
		$referrerCode = $parameterDictionary["referrerCode"];
		$selectedChannel = $parameterDictionary["selectedChannel"];
		$confirmationMethod = $parameterDictionary["confirmationMethod"];
		$selectedRedemptionStore = $parameterDictionary["selectedRedemptionStore"];
		$selectedRedemptionPeriodID = $parameterDictionary["selectedRedemptionPeriodID"];

		//  Offer record should not include in form data of coupon
		unset($parameterDictionary["offer"]);

		//----------------------------------------------------------------------------------------
		//  0 = Done
		//  -99 = Unexpected error...
		//  -1 = Please try again after date...
		//  -5 = Offer already ended...
		//  -6 = Not enough quota...
		//  -10 = Coupon already exists...
		//  -50 = Webhook error
		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		//  Check if offer already ended
		$endAt = strtotime($offer->end_at);
		if ($endAt < time())  {

			//  Offer ended
			$response["status"] = -5;
			$response["message"] = "Offer already ended...";
//Use-parameter 			return response()->json($response);
			return $response;
		}

		//  Check if already got the coupon
		$offerID = $offer->id;
		$coupon = CampaignCoupon::where("mobile", $mobile)->where("offer_id", $offerID)->orderBy("created_at", "desc")->first();
		if (!empty($coupon))  {

			//  Coupon already exists, check if already used
			$useAt = $coupon->use_at;
			if (empty($useAt))  {

				//  Coupon not used yet, check if it is expired
				$expiryAt = 0;
				if ($coupon->expiry_at != null)  {
					$expiryAt = strtotime($coupon->expiry_at);
				}

				//  Expiry at = 0 means no expiry date
				if ($expiryAt > time() || $expiryAt == 0)  {

					//  Coupon not yet expired
					$response["status"] = -10;
					$response["message"] = "Coupon already exists...";
//Use-parameter 					return response()->json($response);
					return $response;
				}

				//  Coupon not used yet but already expired
				//  We let user get a new one
			}  else  {

				//  Coupon already used, check if it is 2 months ago
				$renewAt = strtotime($coupon->use_at." +60 days");
				if ($renewAt > time())  {

					$date = date("Y-m-d H:i:s", $renewAt);
					$response["status"] = -2;
					$response["message"] = "Please register again after $date...";
					$response["date"] = $date;
//Use-parameter 					return response()->json($response);
					return $response;
				}
			}
		}

		//  CAUTION:
		//  Somehow the issue process is too long, when register request double fire,
		//  system will issue double coupons...
		$coupon = new CampaignCoupon();
		$coupon->offer_id = $offerID;
		$coupon->parent_offer_id = $offerID;
		$coupon->mobile = $mobile;

		$success = $coupon->save();
		if (!$success)  {

			$response["status"] = -10;
			$response["message"] = "Coupon already exists...";
			return $response;
		}

		//----------------------------------------------------------------------------------------
		//  Ready to issue a coupon, ask webhook for final decision
		//	Sample response: {
		//		apiName: "registerAPI",
		//		status: -1,
		//		message: "Already registered..."
		//	}
		$webhook = $offer->webhook;
		$json = json_decode($webhook, true);
		if ($json != null && isset($json["offerRegistrationWebhookType"]))  {

			$type = intval($json["offerRegistrationWebhookType"]);
			switch ($type)  {
				default:
				case 10:  break;

				//  Internal
				case 20:  {
					$m = intval($json["offerRegistrationM"]);
					$nPickM = $json["offerRegistrationNPickM"];
					$array = CampaignCoupon::getCouponByOfferIDs($mobile, $nPickM);
					$count = count($array);
					if ($count >= $m)  {

						$coupon->delete();

						$response["offerRegistrationNPickM"] = $nPickM;
						$response["count"] = $count;
						$response["m"] = $m;

						$response["status"] = -15;
						$response["message"] = "You have registered one of offer in same series...";
//Use-parameter 						return response()->json($response);
						return $response;
					}
				}  break;

				//  External webhook
				case 30:  {
					$url = $json["offerRegistrationWebhookURL"];
					if (strpos($url, "http") !== false)  {

						//  External rules webhook
//Use-parameter 						$dictionary = $request->all();
//Use-parameter 						$dictionary["offerID"] = $offerID;

						//  This is use for webhook to determine PRODUCTION or STAGING
						$parameterDictionary["host"] = config("app.url");

						$client = new GuzzleHttp\Client();
						$result = $client->request("POST", $url, [
//Use-parameter 							"body" => json_encode($dictionary),
							"body" => json_encode($parameterDictionary),
						]);
						$body = (string)($result->getBody());
						$response["result"] = $body;

						//  Check if can continue
						$json = json_decode($body, true);
						if ($json["status"] < 0)  {

							//  Can't continue
							$response["status"] = -50;
							if (isset($json["message"]))  {

								$response["message"] = $json["message"];
							}
//Use-Parameter 							return response()->json($response);
							return $response;
						}
					}
				}  break;
			}
		}

		//----------------------------------------------------------------------------------------
		//  Deduct a quota in offer table
		$store = null;
		$affectedRows = 0;
		switch ($offer->coupon_type) {

			case "randomly-generated": {
				$affectedRows = CampaignOffer::deductQuota($offerID);
			} break;

			case "pre-generated": {

//20210812				//  Coupon pool dynamically calculate quota, only check is enough
//20210812				$affectedRows = CampaignCouponPool::availableQuota($offerID);
//20210812
//20210812				//  also update campaign offer quota for consistency,
//20210812				//  but whether the quota can be successfully issued does not depend on this
//20210812				CampaignOffer::deductQuota($offerID);

				//  Should consider double-trigger issue
				$affectedRows = CampaignOffer::deductQuota($offerID);
				if ($affectedRows > 0)  {

					//  Try to get one quota from coupon pool
					$affectedRows = CampaignCouponPool::availableQuota($offerID);
				}
			} break;
		}

		if ($affectedRows <= 0)  {

			$coupon->delete();

			//  Not enough quota
			$response["status"] = -6;
			$response["message"] = "Not enough quota...";
//Use-Parameter 			return response()->json($response);
			return $response;
		}

		//  Also deduct store quota
//Use-parameter 		$storeQuotaID = $request->selectedRedemptionPeriodID;

		$storeCode = "";
		$storeQuotaID = 0;

		// get storeCode either from period id or store name
		// legacy web sends in period id, parse storeCode from the id
		// chatbot sends in store code directly
		if ($selectedRedemptionPeriodID > 0 && $offer->coupon_type == "randomly-generated")  {

			//  = 0 means online store or no need
			//  > 0 means have store
			$storeQuotaID = $selectedRedemptionPeriodID;
			$store = CampaignStoreQuota::getStoreWithQuotaID($offerID, $storeQuotaID);
			$storeCode = $store->store_code;

		}  else if (isset($selectedRedemptionStore))  {

			$storeCode = $selectedRedemptionStore;
		}  else  {

			$coupon->delete();

			$response["status"] = -98;
			$response["message"] = "No store code specified";
//Use-Parameter 			return response()->json($response);
			return $response;
		}

		switch ($offer->coupon_type)  {

			case "randomly-generated":  {
				$affectedRows = CampaignStoreQuota::deductQuota($offerID, $storeCode, $storeQuotaID);
				// dd($affectedRows, $offerID, $storeCode, $storeQuotaID);
				if ($affectedRows <= 0)  {

					//  Restore deducted quota in offer table
					$affectedRows = CampaignOffer::increaseQuota($offerID);

					$coupon->delete();

					//  Not enough quota
					$response["status"] = -7;
					$response["message"] = "Not enough quota for '$storeCode'...";
//Use-Parameter 				return response()->json($response);
					return $response;
				}
			}  break;

			case "pre-generated":  {
				//  No need to deduct store quota, so nothing is done here
			}  break;
		}

		//----------------------------------------------------------------------------------------
		//  Get saved referrer code which saved in landing
//Use-parameter 		$referrerCode = "";
//Use-parameter 		if ($request->session()->has("referrerCode"))  {
//Use-parameter 			$referrerCode = $request->session()->get("referrerCode", "");
//Use-parameter 		}

		//----------------------------------------------------------------------------------------
		//  Update referrer statistics
		if ($referrerCode != "")  {

			//  +1 to registration key in coupon record
			$dictionary = CampaignCoupon::referSuccess($offerID, $referrerCode);
			if ($dictionary != null)  {

				$referrerMobile = $dictionary["referrerMobile"];
				$currentReferralCount = $dictionary["registration"];

				//  Get offer records with referrer mobile number
				$offerIDToBeCheckedArray = array();
				$unusedCouponArray = CampaignCoupon::getUnusedWithMobile($referrerMobile);
				foreach ($unusedCouponArray as $unusedCoupon)  {

					$referrerOfferID = $unusedCoupon->offer_id;
					$offerIDToBeCheckedArray[] = $referrerOfferID;
				}

				$offerToBeNotifyArray = CampaignOffer::getReferralOffer($offerIDToBeCheckedArray, $offerID, $currentReferralCount);
				foreach ($offerToBeNotifyArray as $offerToBeNotify)  {

					//  TODO: Should see which communication channel

					//  Send notification to referrer
					$uniqueCode = "";
					$startAtDate = "";
					$expiryAtDate = "";
					$offerToBeNotifyCouponID = 0;
					$offerCode = $offerToBeNotify->id;
					foreach ($unusedCouponArray as $unusedCoupon)  {
						if ($unusedCoupon->offer_id == $offerToBeNotify->id)  {
							$offerToBeNotifyCouponID = $unusedCoupon->id;

							$startAtDate = $unusedCoupon->start_at;
							$expiryAtDate = $unusedCoupon->expiry_at;
							$uniqueCode = $unusedCoupon->unique_code;
							$referralCode = $unusedCoupon->referral_code;

							//  No time required
							$startAtDate = substr($startAtDate, 0, 10);
							$expiryAtDate = substr($expiryAtDate, 0, 10);
							break;
						}
					}

					$message = "";
					$json = json_decode($offerToBeNotify->webhook, true);
					if (isset($json["couponActivationMessage"]))  {

						$message = $json["couponActivationMessage"];

						$port = ($_SERVER["SERVER_PORT"] == "80" || $_SERVER["SERVER_PORT"] == "443") ? "" : ":".$_SERVER["SERVER_PORT"];
						$scheme = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? "https" : "http";
						$baseURL = $scheme."://".$_SERVER["SERVER_NAME"].$port."/";

						$link = $baseURL.$uniqueCode."/";
						$referralLink = $baseURL."offer/".$offerCode."?r=".$referralCode;

						//  Replace default keywords
						$searchArray = array(
							"\\n",
							"{{link}}", "{{referralLink}}", "{{referralCode}}",
							"{{startDate}}", "{{endDate}}",
//In-request							"{{selectedRedemptionStore}}",
						);
						$replaceArray = array(
							"\n",
							$link, $referralLink, $referralCode,
							$startAtDate, $expiryAtDate,
//In-request							$request->selectedRedemptionStore
						);

						$message = str_replace($searchArray, $replaceArray, $message);

						//  Replace custom keywords
//Use-parameter 						foreach ($request->all() as $key => $value)  {
						foreach ($parameterDictionary as $key => $value)  {
							$message = str_replace("{{".$key."}}", $value, $message);
						}
					}

					$prefix = env("WHATSAPP_PREFIX", "");
					$message = $prefix.$message;
					$expiryDate = date("Y-m-d H:i:s", strtotime("+3 days"));

					//  Send out fulfillment message
					$now = date("Y-m-d H:i:s");
					$whatsAppQueue = new CampaignWhatsappMessageQueue();
					$whatsAppQueue->created_by = basename(__FILE__);
					$whatsAppQueue->offer_id = $offerToBeNotify->id;
					$whatsAppQueue->coupon_id = $offerToBeNotifyCouponID;
					$whatsAppQueue->priority = 70;
					$whatsAppQueue->mobile = $referrerMobile;
					$whatsAppQueue->message = $message;
					$whatsAppQueue->message_type = "Referral-done";
					$whatsAppQueue->schedule_at = $now;
					$whatsAppQueue->expiry_at = $expiryDate;
					$whatsAppQueue->vendor = "twilio";
					$whatsAppQueue->cost = "session";
					$whatsAppQueue->save();
				}
			}
		}

		//----------------------------------------------------------------------------------------
		//  Issue coupons
		$couponOrder = 100;						// 100 = Normal case, make some room for adhoc

		$redeemStartAt = $offer->start_at;
		$redeemEndAt = $offer->end_at;
		if ($store != null)  {

			$redeemStartAt = $store->start_at;
			$redeemEndAt = $store->end_at;
		}

		$startAt = $redeemStartAt;
		$expiryAt = $redeemEndAt;

//Use-parameter 		$formDataJSON = json_encode($request->all());
		$formDataJSON = json_encode($parameterDictionary);

		//  Created already
//		$coupon = new CampaignCoupon();
// 		$coupon->offer_id = $offerID;
// 		$coupon->parent_offer_id = $offerID;
// 		$coupon->mobile = $mobile;
		$coupon->email = $email;
		$coupon->start_at = $startAt;
		$coupon->expiry_at = $expiryAt;
		$coupon->selected_channel = $selectedChannel;
		$coupon->coupon_order = $couponOrder;
		$coupon->form_data = $formDataJSON;
		$coupon->referrer_code = $referrerCode;

		//  Try to get a default expiry date
		$channel_expiry = json_decode($offer->channel_expiry);
		if (isset($channel_expiry->default))  {
			$expiryAt = date("Y-m-d H:i:s", strtotime($channel_expiry->default, strtotime($redeemEndAt)));
			$coupon->expiry_at = $expiryAt;
		}

		//  Default length 8
		$uniqueCodeLength = 8;
		$referralCodeLength = 8;
		$offer->ini = parse_ini_file("./offers/".$offer->offer_name."/offer.ini", true);
		if ($offer->ini !== false)  {

			//  Length must be within 4 to 32
			if (isset($offer->ini["settings"]["unique_code_length"]))  {
				$length = intval($offer->ini["settings"]["unique_code_length"]);
				if ($length >= 4 && $length <= 32)  {
					$length = $uniqueCodeLength;
				}
			}

			//  Length must be within 4 to 32
			if (isset($offer->ini["settings"]["referral_code_length"]))  {
				$length = intval($offer->ini["settings"]["referral_code_length"]);
				if ($length >= 4 && $length <= 32)  {
					$length = $referralCodeLength;
				}
			}
		}

		//  Create a unique code
		$count = 0;
		$finish = false;
		while ($finish == false && $count++<1000)  {

			//  check if offer uses pre-gen or ran-gen code
			$uniqueCode = "";
			$nextAvailableCode = null;
			switch ($offer->coupon_type)  {

				case "randomly-generated":  {

					//  Generate a unique code
					$uniqueCode = Str::random($uniqueCodeLength);

					$response["couponCodeURL"] = $uniqueCode;
					$response["couponCodeFilename"] = $uniqueCode;
				}  break;

				case "pre-generated": {

					//  Void the next available coupon
					$array = CampaignCouponPool::voidCoupon($offerID, $mobile, $storeCode);
					$nextAvailableCode = $array['model'];
					$affectedRows = $array['affectedRows'];

					if ($affectedRows <= 0) {
							//  Not enough quota
						$response["status"] = -8;
						$response["message"] = "Not enough quota (pre-generated) ...";
//Use-Parameter 						return response()->json($response);
						return $response;
					}
					$uniqueCode = $nextAvailableCode->unique_code;

					//  Check if coupon image exists, if so then add it to from data
					$couponCodeFilename = $nextAvailableCode->unique_name;
					$couponCodeURL = asset("offers/".$offer->offer_name."/coupons/".$couponCodeFilename.".png");
// 					if (file_exists($couponCodeFilename))  {

						$json = json_decode($coupon->form_data, true);
						$json["couponCodeURL"] = $couponCodeURL;
						$json["couponCodeFilename"] = $couponCodeFilename;
						$response["couponCodeURL"] = $couponCodeURL;
						$response["couponCodeFilename"] = $couponCodeFilename;
						$formDataJSON = json_encode($json);
						$coupon->form_data = $formDataJSON;
// 					}
				}  break;
			}

			//  Pre-gen rule does not apply to referral, ran-gen it no matter what
			$referralCode = Str::random($referralCodeLength);

			//  Make sure unique code and referral code are unique
			$tempCoupon = CampaignCoupon::where("unique_code", $uniqueCode)
				->orWhere("referral_code", $referralCode)
				->first();

			/**
			 * If coupon model can be found and coupon type is pre-generated
			 * it means that the there are duplicated coupon in the pool
			 * mark the current coupon as used and find a new one
			 */
			if ($tempCoupon != null && $offer->coupon_type == "pre-generated") {
				// mark the coupon code as zero and try again
				if ($nextAvailableCode != null) {
					$nextAvailableCode->mobile = "0";
					$nextAvailableCode->save();
				}
				continue;
			} else if ($tempCoupon != null)  {
				//  If a coupon model can be found, it means already exists.  Pick a new code
				continue;
			}

			//  Save to database
			$coupon->unique_code = $uniqueCode;
			$coupon->referral_code = $referralCode;
			if ($coupon->save() == true)  {

				$couponID = $coupon->id;

				//  Issue bundled coupons
				//  We create coupon record here but not in coupon thank you page,
				//  because it is better
				if ($offer->bundled_offers_id != null)  {

					$idArray = explode(",", $offer->bundled_offers_id);
					foreach ($idArray as $id)  {

						//  Also deduct sub-coupon quota
						$affectedRows = CampaignOffer::deductQuota($id);
						if ($affectedRows > 0)  {

							//  +10 but not +1 because leave room to insert adhoc coupon
							$couponOrder += 10;

							//  TODO: Support coupon image for bundled-offers

							//  Sub-coupon
							$coupon = new CampaignCoupon();
							$coupon->offer_id = $id;
							$coupon->parent_offer_id = $offerID;
							$coupon->mobile = $mobile;
							$coupon->email = $email;
							$coupon->start_at = $startAt;
							$coupon->expiry_at = $expiryAt;
							$coupon->selected_channel = $selectedChannel;
							$coupon->unique_code = $uniqueCode;
							$coupon->coupon_order = $couponOrder;
							$coupon->form_data = $formDataJSON;
							$coupon->referrer_code = $referrerCode;
							$coupon->referral_code = $referralCode;
							$coupon->save();
						}
					}
				}

				//  Save successfully
				$finish = true;
				$response["status"] = 0;
				$response["message"] = "Done!";

				$response["uniqueCode"] = $uniqueCode;
				$response["referralCode"] = $referralCode;

				Session::put("offerThankYou", $mobile);
				Session::put("uniqueCode", $uniqueCode);
				Session::put("referralCode", $referralCode);

// 				$offer->ini = parse_ini_file("./offers/".$offer->offer_name."/offer.ini", true);
				if ($offer->ini !== false)  {

					//  Prepare message for notification
					$port = ($_SERVER["SERVER_PORT"] == "80" || $_SERVER["SERVER_PORT"] == "443") ? "" : ":".$_SERVER["SERVER_PORT"];
					$scheme = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? "https" : "http";
					$baseURL = $scheme."://".$_SERVER["SERVER_NAME"].$port."/";

					$link = $baseURL.$uniqueCode."/";
// 					$link = $request()->getSchemeAndHttpHost()."/".$uniqueCode."/";

					$referralLink = $baseURL."offer/".$offerCode."?r=".$referralCode;

					$prefix = env("WHATSAPP_PREFIX", "");

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
							"\\n",
							"{{link}}", "{{referralLink}}", "{{referralCode}}",
							"{{startDate}}", "{{endDate}}",
//In-request							"{{selectedRedemptionStore}}",
						);
						$replaceArray = array(
							"\n",
							$link, $referralLink, $referralCode,
							$startAtDate, $expiryAtDate,
//In-request							$request->selectedRedemptionStore
						);

						$message = str_replace($searchArray, $replaceArray, $message);
						$reminderMessage = str_replace($searchArray, $replaceArray, $reminderMessage);
						$referralMessage = str_replace($searchArray, $replaceArray, $referralMessage);

						//  Replace custom keywords
//Use-parameter 						foreach ($request->all() as $key => $value)  {
						foreach ($parameterDictionary as $key => $value)  {
							$message = str_replace("{{".$key."}}", $value, $message);
							$reminderMessage = str_replace("{{".$key."}}", $value, $reminderMessage);
							$referralMessage = str_replace("{{".$key."}}", $value, $referralMessage);
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
								$result = null;
								$sendAt = null;
								$status = null;
								$vendor = null;
								$messageID = null;

								$now = date("Y-m-d H:i:s");
								$scheduleAt = date("Y-m-d 12:00:00", strtotime("+1 day"));

								$sendWhatsAppNow = env("SEND_WHATSAPP_AFTER_REGISTRATION", false);
								if ($sendWhatsAppNow == true)  {

									$receiver = "whatsapp:".$mobile;
									$sid = env('WHATSAPP_SID', '');
									$token = env('WHATSAPP_TOKEN', '');
									$sender = env('WHATSAPP_SENDER', '');

									$sendAt = $now;
									$vendor = "twilio";

									$whatsAppEnabled = env('WHATSAPP_ENABLED', false);
									if ($whatsAppEnabled == true)  {

										//  WhatsApp Notification Message
										$twilio = new Client($sid, $token);
										$result = $twilio->messages->create($receiver, [
											"from" => $sender,
											"body" => $message
										]);
										$status = "Success";
										$messageID = CampaignWhatsappMessageQueue::getTwilioMessageID($result);

									}  else  {
										$status = "Success";
										$result = "[Twilio.Api.V2010.MessageInstance accountSid=ACa8c4e3793f543cc3b4d68b112171edf1 sid=SM940bd08464984df49cd8f246e683704a]";
										$messageID = "SM940bd08464984df49cd8f246e683704a";
									}
								}
								$response["whatsapp"] = $result;

								//  Also include unicode code if DEBUG
								$appDebug = config('app.debug');
								if ($appDebug == true)  {
									$response["uniqueCode"] = $uniqueCode;
								}

								//  Add to whatsapp log
								$whatsAppQueue = new CampaignWhatsappMessageQueue();
								$whatsAppQueue->created_by = basename(__FILE__);
								$whatsAppQueue->offer_id = $offerID;
								$whatsAppQueue->coupon_id = $couponID;
								$whatsAppQueue->mobile = $mobile;
								$whatsAppQueue->message = $message;
								$whatsAppQueue->message_type = "Coupon";
								$whatsAppQueue->schedule_at = $scheduleAt;
								$whatsAppQueue->expiry_at = $expiryAt;		//20201109 Pacess
								$whatsAppQueue->send_at = $sendAt;
								$whatsAppQueue->vendor = $vendor;
								$whatsAppQueue->message_id = $messageID;
								$whatsAppQueue->status = $status;
								$whatsAppQueue->response = $result;
								$whatsAppQueue->cost = "session";
								$whatsAppQueue->save();
							}  break;
						}
					}

					//----------------------------------------------------------------------------------------
					//  Reminder notification
					if ($reminderTime != null && !empty($reminderMessage))  {

						$reminderMessage = $prefix.$reminderMessage;
						switch ($confirmationMethod)  {

							case "sms":  {
							}  break;

							case "whatsapp":  {
								$time = date("Y-m-d H:i:s", strtotime($reminderTime));

								$whatsAppQueue = new CampaignWhatsappMessageQueue();
								$whatsAppQueue->created_by = basename(__FILE__);
								$whatsAppQueue->offer_id = $offerID;
								$whatsAppQueue->coupon_id = $couponID;
								$whatsAppQueue->mobile = $mobile;
								$whatsAppQueue->priority = 50;
								$whatsAppQueue->message = $reminderMessage;
								$whatsAppQueue->message_type = "Reminder";
								$whatsAppQueue->schedule_at = $time;
								$whatsAppQueue->expiry_at = $expiryAt;		//20201109 Pacess
								$whatsAppQueue->cost = "template";
								$whatsAppQueue->save();
							}  break;
						}
					}

					//----------------------------------------------------------------------------------------
					//  Referral notification
//  * Referral message now will not send right after registration,
//    it will be sent after user ask for coupon link
// Log::error("*** referralMessage=$referralMessage");
// 					if (!empty($referralMessage))  {
//
// Log::error("*** confirmationMethod=$confirmationMethod");
// 						$referralMessage = $prefix.$referralMessage;
// 						switch ($confirmationMethod)  {
//
// 							case "sms":  {
// 							}  break;
//
// 							case "whatsapp":  {
// 								$receiver = "whatsapp:".$mobile;
// 								$sid = env('WHATSAPP_SID', '');
// 								$token = env('WHATSAPP_TOKEN', '');
// 								$sender = env('WHATSAPP_SENDER', '');
//
// 								$result = "";
// 								$messageID = "";
// 								$whatsAppEnabled = env('WHATSAPP_ENABLED', false);
// Log::error("*** whatsAppEnabled=$whatsAppEnabled");
// 								if ($whatsAppEnabled == true)  {
//
// 									//  WhatsApp Notification Message
// 									$twilio = new Client($sid, $token);
// 									$result = $twilio->messages->create($receiver, [
// 										"from" => $sender,
// 										"body" => $referralMessage
// 									]);
// 									$status = "Success";
// 									$messageID = CampaignWhatsappMessageQueue::getTwilioMessageID($result);
//
// 								}  else  {
// 									$status = "Success";
// 									$result = "[Twilio.Api.V2010.MessageInstance accountSid=ACa8c4e3793f543cc3b4d68b112171edf1 sid=SM940bd08464984df49cd8f246e683704a]";
// 									$messageID = "SM940bd08464984df49cd8f246e683704a";
// 								}
// 								$response["whatsapp"] = $result;
//
// 								//  Add to whatsapp log
// Log::error("*** mobile=$mobile");
// 								$now = date("Y-m-d H:i:s");
// 								$whatsAppQueue = new CampaignWhatsappMessageQueue();
// 								$whatsAppQueue->created_by = basename(__FILE__);
// 								$whatsAppQueue->offer_id = $offerID;
// 								$whatsAppQueue->coupon_id = $couponID;
// 								$whatsAppQueue->priority = 50;
// 								$whatsAppQueue->mobile = $mobile;
// 								$whatsAppQueue->message = $referralMessage;
// 								$whatsAppQueue->message_type = "Referral-1";
// 								$whatsAppQueue->schedule_at = $now;
// 								$whatsAppQueue->send_at = $now;
// 								$whatsAppQueue->vendor = "twilio";
// 								$whatsAppQueue->message_id = $messageID;
// 								$whatsAppQueue->status = $status;
// 								$whatsAppQueue->response = $result;
// 								$whatsAppQueue->save();
// 							}  break;
// 						}
// 					}

				}
			}
		}
		return $response;
	}

	//----------------------------------------------------------------------------------------
	public function submitFormAPI(Request $request, $offerCode)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$offer = $request->offer;
		if ($offer == null)  {

			//  Offer not found
			return $response;
		}

		$formData = $request->input();

		//----------------------------------------------------------------------------------------
		//  Check if enough quota
		if ($offer->quota_issued >= $offer->quota)  {

			$response["status"] = -6;
			$response["message"] = "Not enough quota ".$offer->quota_issued."/".$offer->quota."...";
			return $response;
		}

		//  TODO: Check if timeslot has enough quota
		$selectedRedemptionPeriodID = $formData["selectedRedemptionPeriodID"] ?? "";

		//  TODO: Deduct quota first to prevent multiple users pick the last chance?

		//----------------------------------------------------------------------------------------
		//  Generate form code
		$count = 0;
		$finish = false;
		$uniqueCodeLength = 16;
		while ($finish == false && $count++<100)  {

			$formCode = Str::random($uniqueCodeLength);

			$tempForm = CampaignForm::where("form_code", $formCode)
				->first();

			if ($tempForm == null)  {$finish = true;}
		}

		if ($finish == false)  {

			$response["status"] = -60;
			$response["message"] = "Unable to generate form code...";
			return $response;
		}

		// ------------ Kay 2022.12.14 ------------------------------------------------------------
		// convert the store name to store code in "selectedRedemptionStore"
		$tempPeriodID = 0;
		if (isset($formData["selectedRedemptionPeriodID"])){ $tempPeriodID = $formData["selectedRedemptionPeriodID"];}

		$formData["selectedRedemptionStore_name"] = $formData["selectedRedemptionStore"];

		$tempStoreCode = "";
		$tempStoreCode = CampaignStoreQuota::getStoreCodeByFormData($offer->id, $formData["selectedRedemptionStore"], $tempPeriodID);
		if(!empty($tempStoreCode)){$formData["selectedRedemptionStore"] = $tempStoreCode;}
		// End
		
		//----------------------------------------------------------------------------------------
		//  Save form data
		CampaignForm::createForm($offer->id, $formCode, $formData);

		//  Return form code
		$response["message"] = "Form received #".$formCode;
		$response["formCode"] = $formCode;
		$response["status"] = 0;
		return $response;
	}

	//----------------------------------------------------------------------------------------
	public function getCouponLinkAPI(Request $request, $offerCode)  {

		//  0 = Done
		//  -1 = Please try again after date...
		//  -5 = Offer already ended...
		//  -6 = Not enough quota...
		//  -10 = Coupon already exists...
		//  -15 = Bundled coupon already exists...
		//  -20 = Coupon not found...
		//  -30 = Request limit exceed...
		//  -50 = Webhook error
		//  -99 = Unexpected error...
		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$offer = $request->offer;
		$offerID = $offer->id;
		$mobile = $request->input("areaCode").$request->input("mobile");

		//----------------------------------------------------------------------------------------
		//  Check if mobile reach request limit
		CampaignCouponLinkRequest::createRecord($offerID, $mobile);
		$count = CampaignCouponLinkRequest::getRequestCount($offerID, $mobile);
		$max = intval(env("MAX_FORGOT_LINK_REQUEST", "3"));
		if ($count > $max)  {

			//  Request limit exceed
			$response["status"] = -30;
			$response["message"] = "Request limit exceed...";
			return response()->json($response);
		}

		//----------------------------------------------------------------------------------------
		//  Check mobile if exists in database, return error if exists

		//  Get coupon unique code from database base on mobile and offer ID
		$array = CampaignCoupon::getCouponByOfferIDs($mobile, $offerID);
		$count = count($array);
		if ($count <= 0)  {

			//  Coupon not found
			$response["status"] = -20;
			$response["message"] = "Coupon not found...";
			return response()->json($response);
		}

		$coupon = $array[0];
		$uniqueCode = $coupon->unique_code;
		$referralCode = $coupon->referral_code;

		//  Thank you page require session to show
		Session::put("offerThankYou", $mobile);
		Session::put("uniqueCode", $uniqueCode);
		Session::put("referralCode", $referralCode);

		$response["couponLink"] = route("campaign.coupon.landing.html", ["unique_code" => $uniqueCode]);
		$response["referralLink"] = route("campaign.offer.details.html", ["offer_code" => $offerCode])."?r=".$referralCode;

		//  Pass back to frontend
		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function aidExchangeAPI(Request $request, $offerCode)  { //click whatsapp to redeirect button

		//  0 = Done
		//  -1 = Please try again after date...
		//  -5 = Offer already ended...
		//  -6 = Not enough quota...
		//  -10 = Coupon already exists...
		//  -15 = Bundled coupon already exists...
		//  -20 = Coupon not found...
		//  -30 = Request limit exceed...
		//  -50 = Webhook error
		//  -99 = Unexpected error...
		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$offer = $request->offer;
		if ($offer == null)  {

			//  Offer not found
			return $response;
		}

		$offerCode = $offer->offer_code;
		$aid = $request->input('aid', '');
		$formCode = $request->input('formCode', '');
		$referrerCode = $request->input('referrerCode', '');
		$kinnsoReferrerCode = $request->input('memberReferralCode', '');

		$aidToken = "";

		//  TODO: Get kinnso referrer code from session, default null (land on landing page)
		if (Session::has("referrerCodefromMember")){
			$tempCode = Session::pull("referrerCodefromMember");
			if (strlen($tempCode)>0){$kinnsoReferrerCode = $tempCode;}
			Session::forget('referrerCodefromMember');
		}

		$record = SegmentExchange::getRecordWithAID($offerCode, $aid, $referrerCode);
		if ($record != null)  {

			$aidToken = $record->aid_token;
			if (empty($formCode) == false)  {

				$record->form_code = $formCode;
				//TODO: KinnsoReferrerCode
				if (!empty($kinnsoReferrerCode)){
					$record->member_referral_code = $kinnsoReferrerCode;
				}
				$record->save();
			}

		}  else  {

			$uniqueCodeLength = 6;
			$aidToken = Str::random($uniqueCodeLength);
			SegmentExchange::createRecord($offerCode, $aid, $aidToken, $referrerCode, $formCode, $kinnsoReferrerCode);   //TODO: KinnsoReferrerCode
		}

		//  Pass back to frontend
		$response["status"] = 0;
		$response["message"] = "Done";
		$response["aidToken"] = $aidToken;
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function getKeyVisualArray()  {

		$type = "key-visuals";
		$bannerArray = array();
		$array = CampaignBanner::getBanners($type);
		foreach ($array as $record)  {

			$mobileImageURL = "";
			$desktopImageURL = "";
			$url = json_decode($record->image_url, true);
			if(isset($url["mobile"])){$mobileImageURL = $url["mobile"];}
			if(isset($url["desktop"])){$desktopImageURL = $url["desktop"];}

			$mobileTargetURL = "";
			$desktopTargetURL = "";
			$url = json_decode($record->target_url, true);
			if(isset($url["mobile"])){$mobileTargetURL = $url["mobile"];}
			if(isset($url["desktop"])){$desktopTargetURL = $url["desktop"];}

			$bannerArray[] = array(
				"mobile" => array("image"=>$mobileImageURL, "url"=>$mobileTargetURL),
				"desktop" => array("image"=>$desktopImageURL, "url"=>$desktopTargetURL),
			);
		}
		return $bannerArray;
	}

	//----------------------------------------------------------------------------------------
	public function getBannerArray()  {

		$type = "banners";
		$bannerArray = array();
		$array = CampaignBanner::getBanners($type);
		foreach ($array as $record)  {

			$imageURL = "";
			$url = json_decode($record->image_url, true);
			if(isset($url["image"])){$imageURL = $url["image"];}

			$targetURL = "";
			$url = json_decode($record->target_url, true);
			if(isset($url["url"])){$targetURL = $url["url"];}

			$bannerArray[] = array(
				"image"=>$imageURL, "url"=>$targetURL,
			);
		}
		return $bannerArray;
	}

	//----------------------------------------------------------------------------------------
	public function prepareOfferArray($array)  {

		$timestamp = time();
		$offerArray = array();
		foreach ($array as $record)  {

			$offerID = $record->id;
			$offerCode = $record->offer_code;
			$tagArray = $record->tag ? explode(",", $record->tag) : array();
			$offerTitle = $record->offer_title;
			$filterArray = $record->filter ? explode(",", $record->filter) : array();
			$categoryArray = $record->category ? explode(",", $record->category) : array();

			//  Convert category to labels
			$labelArray = array();
			foreach ($categoryArray as $label)  {

				$label = trim($label);
				switch ($label)  {
					case "米芝蓮 / Omakase":  $color = "#EF8EA0";  break;
					case "好去處":  $color = "#BAD88B";  break;
					case "網購":  $color = "#B7D4F7";  break;
					case "優惠":  $color = "#FCCC08";  break;
					case "美食":  $color = "#EF8EA0";  break;
					default:  $color = "#000000";  break;
				}
				$labelArray[] = array("text"=>$label, "text-color"=>$color);
			}

			$url = route("campaign.offer.details.html", ["offer_code" => $offerCode]);
			$kvURL = asset('/offers/'.$record->offer_name.'/offer_thumbnail.png');

			//----------------------------------------------------------------------------------------
			//  Somehow Digital-Partnership want to show ended offer, that's why we need
			//  run-time labeling here
			if (strtotime($record->end_at) < $timestamp)  {

				//  Ended offer, apply 'end' label
				if (in_array("end", $tagArray) == false)  {
					$tagArray[] = "end";
				}
			}

			//----------------------------------------------------------------------------------------
			$offerArray[] = array(
				"offer-id" => $offerID,
				"tags" => $tagArray,
				"key-visual" => $kvURL,
				"filter" => $filterArray,
				"category" => $categoryArray,
				"labels" => $labelArray,
				"title" => $offerTitle,
				"url" => $url,
			);
		}
		return $offerArray;
	}

	//----------------------------------------------------------------------------------------
	public function landingPageAPI(Request $request)  {

		$dictionary = array();

		//----------------------------------------------------------------------------------------
		//  Key visuals
		$keyVisualArray = $this->getKeyVisualArray();
		$dictionary["key-visuals"] = $keyVisualArray;

		//----------------------------------------------------------------------------------------
		//  Topics
		//  TODO: Should get from database
		//  米芝蓮 , 抗疫, 下午茶, 美容, Omakase, 文青, 健康, 娛樂
		// 	2022.10.27 update -- 親子, 情侶, 寵物, 打卡, 網購, 文青
		$dictionary["topics"] = array(
			// array("text"=>"米芝蓮", "text-color"=>"#F37621"),
			// array("text"=>"抗疫", "text-color"=>"#F37621"),
			// array("text"=>"下午茶", "text-color"=>"#F37621"),
			// array("text"=>"美容", "text-color"=>"#F37621"),
			// array("text"=>"Omakase", "text-color"=>"#F37621"),
			// array("text"=>"文青", "text-color"=>"#F37621"),
			// array("text"=>"健康", "text-color"=>"#F37621"),
			// array("text"=>"娛樂", "text-color"=>"#F37621"),
			array("text"=>"親子", "text-color"=>"#F37621"),
			array("text"=>"情侶", "text-color"=>"#F37621"),
			array("text"=>"寵物", "text-color"=>"#F37621"),
			array("text"=>"打卡", "text-color"=>"#F37621"),
			array("text"=>"網購", "text-color"=>"#F37621"),
			array("text"=>"文青", "text-color"=>"#F37621"),
		);

		//----------------------------------------------------------------------------------------
		//  Category
		$dictionary["categories"] = array(
			array("text"=>"好去處", "icon"=>"/website/category/activity_off.png", "highlight"=>"/website/category/activity_on.png"),
			array("text"=>"美食", "icon"=>"/website/category/food_off.png", "highlight"=>"/website/category/food_on.png"),
			array("text"=>"優惠", "icon"=>"/website/category/offer_off.png", "highlight"=>"/website/category/offer_on.png"),
			// array("text"=>"網購", "icon"=>"/website/category/shopping_off.png", "highlight"=>"/website/category/shopping_on.png"), // hidden 2022.10.27 Kay
		);

		//----------------------------------------------------------------------------------------
		//  Banners
		$keyVisualArray = $this->getBannerArray(); //HideBannersTemporarybefore
		$dictionary["banners"] = $keyVisualArray; //HideBannersTemporarybefore

		//----------------------------------------------------------------------------------------
		//  Hot offers
		$array = CampaignOffer::getHotOffers();
		$hotOfferArray = $this->prepareOfferArray($array);
		$dictionary["hot-offers"] = $hotOfferArray;

		//----------------------------------------------------------------------------------------
		//  Offers
//Old		$array = CampaignOffer::getOffers();
		$array = $this->listingPageApi($request);
		$offerArray = $this->prepareOfferArray($array);
		$dictionary["offers"] = $offerArray;

		//----------------------------------------------------------------------------------------
		//  Finally output
		return response()->json($dictionary);
	}

	//----------------------------------------------------------------------------------------
    public function recommendAPI(Request $request)  {

        $dictionary = array();

        //  Get users who liked the offer
        $offerCode = $request->offer_code;
        $offer = CampaignOffer::getOffer($offerCode);
		if ($offer == null)  {

            //  Offer not found, use hot offer instead
            $array = CampaignOffer::getHotOffers();
        }  else  {

			$array = [];

			//  Let's calculate offer score
            $usersWhoRedeemItArray = CampaignCoupon::getMobilesWithOfferID($offer->id);  // the list of who take coupon
			if (empty($usersWhoRedeemItArray) == false)  {

				$suggestedOfferIDArray = CampaignCoupon::getSuggestedOffersID($offer->id, $usersWhoRedeemItArray); //offer id list
				if (empty($suggestedOfferIDArray) == false)  {

					$array = CampaignOffer::getOfferByIDs($suggestedOfferIDArray);
				}
			}

			if (count($array) == 0)  {
				$array = CampaignOffer::getHotOffers();
			}
        }

        $offerArray = $this->prepareOfferArray($array);
        $dictionary["recommends"] = $offerArray;

        //----------------------------------------------------------------------------------------
        //  Finally output
        return response()->json($dictionary);
    }

	//----------------------------------------------------------------------------------------
	//  ?category=好去處
	//  ?filter=生日蛋糕+獨家優惠 (%E7%94%9F%E6%97%A5%E8%9B%8B%E7%B3%95+%E7%8D%A8%E5%AE%B6%E5%84%AA%E6%83%A0)
	public function filterPageAPI(Request $request)  {

		$dictionary = array();

		//  Either category or filter
		$filterArray = null;
		$category = isset($request->category) ? $request->category : null;
		if ($category == null)  {

			$filter = isset($request->filter) ? $request->filter : null;
			if ($filter != null)  {

				$filterArray = explode(" ", $filter);
			}
		}

		//----------------------------------------------------------------------------------------
		//  Topics
		//  TODO: Should get from database
		//  米芝蓮 , 抗疫, 下午茶, 美容, Omakase, 文青, 健康, 娛樂
		// 	2022.10.27 update -- 親子, 情侶, 寵物, 打卡, 網購, 文青
		$dictionary["topics"] = array(
			// array("text"=>"米芝蓮", "text-color"=>"#F37621"),
			// array("text"=>"抗疫", "text-color"=>"#F37621"),
			// array("text"=>"下午茶", "text-color"=>"#F37621"),
			// array("text"=>"美容", "text-color"=>"#F37621"),
			// array("text"=>"Omakase", "text-color"=>"#F37621"),
			// array("text"=>"文青", "text-color"=>"#F37621"),
			// array("text"=>"健康", "text-color"=>"#F37621"),
			// array("text"=>"娛樂", "text-color"=>"#F37621"),
			array("text"=>"親子", "text-color"=>"#F37621"),
			array("text"=>"情侶", "text-color"=>"#F37621"),
			array("text"=>"寵物", "text-color"=>"#F37621"),
			array("text"=>"打卡", "text-color"=>"#F37621"),
			array("text"=>"網購", "text-color"=>"#F37621"),
			array("text"=>"文青", "text-color"=>"#F37621"),
		);

		//----------------------------------------------------------------------------------------
		//  Category
		$dictionary["categories"] = array(
			array("text"=>"好去處", "icon"=>"/website/category/activity_off.png", "highlight"=>"/website/category/activity_on.png"),
			array("text"=>"美食", "icon"=>"/website/category/food_off.png", "highlight"=>"/website/category/food_on.png"),
			array("text"=>"優惠", "icon"=>"/website/category/offer_off.png", "highlight"=>"/website/category/offer_on.png"),
			// array("text"=>"網購", "icon"=>"/website/category/shopping_off.png", "highlight"=>"/website/category/shopping_on.png"),
		);

		//----------------------------------------------------------------------------------------
		//  Offers
		$array = CampaignOffer::getOffersWithCategoryOrFilter($category, $filterArray);
		$offerArray = $this->prepareOfferArray($array);
		$dictionary["offers"] = $offerArray;

		//----------------------------------------------------------------------------------------
		//  Hot offers
		$array = CampaignOffer::getHotOffers();
		$hotOfferArray = $this->prepareOfferArray($array);
		$dictionary["hot-offers"] = $hotOfferArray;

		//----------------------------------------------------------------------------------------
		//  Finally output
		return response()->json($dictionary);
	}

	//----------------------------------------------------------------------------------------
	//  Mark: Helper function
	//----------------------------------------------------------------------------------------
	//  By Johnson Shan
	public function increaseLikeCounter(Request $request) {
		$offerId = $request->input('offerId', '0');
		if($offerId == 0) {
			return;
		}
		$offer = CampaignOffer::find($offerId);
		if($offer) {
			$offer->likeCounter++;
			$offer->save();
		}
	}

	//----------------------------------------------------------------------------------------
	//  By Johnson Shan
	public function decreaseLikeCounter(Request $request) {
		$offerId = $request->input('offerId', '0');
		if($offerId == 0) {
			return;
		}
		$offer = CampaignOffer::find($offerId);
		if($offer) {
			$offer->likeCounter--;
			if($offer->likeCounter <= 0) {
				$offer->likeCounter = 0;
			}
			$offer->save();
		}
	}

	//----------------------------------------------------------------------------------------
	//  Mark: Processes
	//----------------------------------------------------------------------------------------
	public function processQuotaLevelAlert()  {

		$date = date("Y-m-d");
		$now = date("Y-m-d H:i:s");
		$array = CampaignOffer::getList($date, $date);
		foreach ($array as $offer)  {

			$offerID = $offer->id;
			$monitorDictionary = array(
				"percent_50" => "",
				"percent_75" => "",
				"percent_100" => "",
				"percent_last" => 0
			);

			$disk = Storage::disk('public');
			$filename = "offer_".$offerID."_monitor.json";
			if ($disk->exists($filename) == true)  {

				$string = $disk->get($filename);
				if (empty($string) == false)  {

					$monitorDictionary = json_decode($string, true);
				}
			}

			$alert = false;
			$percentage = 0;
			if ($offer->quota > 0)  {
				$percentage = intval(($offer->quota_issued*100)/$offer->quota);
			}
			$lastPercentage = intval($monitorDictionary["percent_last"]);

			//  >= 50%
			if ($percentage >= 50 && $lastPercentage < 50)  {

				//  Last < 50, now >= 50
				$monitorDictionary["percent_50"] = $now;
				$alert = true;
			}

			//  >= 75%
			if ($percentage >= 75 && $lastPercentage < 75)  {

				//  Last < 75, now >= 75
				$monitorDictionary["percent_75"] = $now;
				$alert = true;
			}

			//  >= 100%
			if ($percentage >= 100 && $lastPercentage < 100)  {

				//  Last < 100, now >= 100
				$monitorDictionary["percent_100"] = $now;
				$alert = true;
			}

			//  Save updated dictionary
			$monitorDictionary["percent_last"] = $percentage;
			$disk->put($filename, json_encode($monitorDictionary));

			if ($alert == false)  {continue;}

			//  Send email alert
			$brandName = env("BRAND_NAME", "");
			$prefix = env("WHATSAPP_PREFIX", "");
			try  {

				$body = "Dear all,<br>".
						"<br>".
						"Please note that quota for offer [".$offer->offer_title." (".$offer->offer_name.")] is now reaches <b><u>".$percentage."%</u></b>.  Thanks!<br>".
						"<br>".
						"$brandName WhatsApp Offer Project<br>".
						"<b>Fimmick Development Team</b><br>".
						__FUNCTION__;

				$mail = new PHPMailer(true);

				$mail->isSMTP();
				$mail->Host = "smtp.gmail.com";
				$mail->SMTPAuth = true;
				$mail->Username = "it@fimmick.com";
				$mail->Password = "bdwviinvdmmcclke";
				$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
				$mail->Port = 587;

				$mail->setFrom("it@fimmick.com", "Fimmick Development Team");

				$mail->isHTML(true);
				$mail->CharSet = "UTF-8";
				$mail->Subject = "=?UTF-8?B?".base64_encode("[$brandName] ".$prefix."$brandName Offer #$offerID quota alert...")."?=";
				$mail->Body = $body;

				//  REMARK: Remember to update FAQ as well
				$mail->AddAddress("developer1@fimmick.com");
				$mail->addBCC("pacessho@fimmick.com");
				$mail->addBCC("nestayeung@fimmick.com");

				$environment = env("APP_ENV", "");
				if ($environment == "production")  {
					$mail->AddAddress("dp@fimmick.com");
				}

				$sendResult = $mail->Send();
				echo("\nResult: $sendResult");

			}  catch (Exception $e)  {
				return("### Error sending email...".$_eol);
			}
		}
	}

	//----------------------------------------------------------------------------------------
	public function processCleanUpReserveForm()  {
		$now = date("Y-m-d H:i:s");
		$deletedRows = CampaignForm::where("expiry_at", "<", $now)->delete();
		return array("deletedRows" => $deletedRows);
	}

	//----------------------------------------------------------------------------------------
	public function processAutoLabeling()  {

		$firstFiveHot = 0;
		$date = date("Y-m-d H:i:s", strtotime("-14 days"));

		//  TODO: Should not take all offers
		$offerArray = CampaignOffer::query()->orderBy("quota_issued", "desc")->get();
		foreach ($offerArray as $offer)  {

			$tagArray = array();

			//  1st: Check if it is a recommend offer (Manually added)
			if (strpos($offer->tag, "push") !== false)  {$tagArray[] = "push";}

			//  2nd: Check if less quota left
			if (intval($offer->quota)*0.7 <= intval($offer->quota_issued))  {

				if (intval($offer->quota) <= intval($offer->quota_issued))  {
					$tagArray[] = "soldout";
				}  else  {
					$tagArray[] = "less";
				}
			}

			//  3rd: Check if it is a hot offer
			if ($firstFiveHot < 5)  {

				$tagArray[] = "hot";
				$firstFiveHot++;
			}

			//  4nd: Check if it is a new offer
			if ($date <= $offer->start_at)  {

				//  Max.3 labels
				if (count($tagArray) < 3)  {$tagArray[] = "new";}
			}

			//----------------------------------------------------------------------------------------
			$tag = strtolower(implode(", ", $tagArray));
			$offer->tag = $tag;
			$offer->save();
		}

		return array("status"=>"Done!");
	}

	//----------------------------------------------------------------------------------------
	public function getDeviceAndOS($userAgent)  {
		$lowerAgent = strtolower($userAgent);

		$operatingSystem = "Unknown";
		if (preg_match('/ipad/', $lowerAgent))  {$operatingSystem = 'iOS';}
		elseif (preg_match('/iphone/', $lowerAgent))  {$operatingSystem = 'iOS';}
		elseif (preg_match('/andriod/', $lowerAgent))  {$operatingSystem = 'Andriod';}
		elseif (preg_match('/win/', $lowerAgent))  {$operatingSystem = 'Windows';}
		elseif (preg_match('/mac/', $lowerAgent))  {$operatingSystem = 'Mac';}
		elseif (preg_match('/linux/', $lowerAgent))  {$operatingSystem = 'Linux';}

		$device = "Desktop";
		if (preg_match('/ipad/', $lowerAgent))  {$device = 'Tablet';}
		elseif (preg_match('/iphone/', $lowerAgent))  {$device = 'Mobile';}
		elseif (preg_match('/mobile/', $lowerAgent))  {$device = 'Mobile';}

		return array(
			"device" => $device,
			"operatingSystem" => $operatingSystem,
		);
	}

	
}


