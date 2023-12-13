<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020-2021.  All rights reserved.
//----------------------------------------------------------------------------------------

namespace App\Http\Controllers;

//----------------------------------------------------------------------------------------
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Picqer\Barcode;
use DateTime;
use Session;
use DB;


use App\Http\Controllers\LoginController;
use App\Http\Controllers\CampaignFileUploadController;

use App\Models\CampaignWhatsappMessageQueue;
use App\Models\CampaignOfferReceiptUpload;
use App\Models\CampaignOfferHunting;
use App\Models\ChannelReceiptSample;
use App\Models\CampaignOfferChannel;
use App\Models\RedemptionHistory;
use App\Models\PointTransaction;
use App\Models\RedemptionCode;
use App\Models\UploadFileLog;
use App\Models\LoginRecord;
use App\Models\Redemption;
use App\Models\Member;
use App\Models\FormUA;

//========================================================================================
class WebsiteController extends Controller  {

	//---------------------------------------------------------------------------------------
	public static $KEY_UA_FORM_SUCCESS = "UA_FORM_SUCCESS";

	//----------------------------------------------------------------------------------------
	//  Mark: Pages
	//----------------------------------------------------------------------------------------
	public function appInstallPage(Request $request)  {
		$userAgent = $request->server('HTTP_USER_AGENT');

		$dictionary = $this->getDeviceAndOS($userAgent);
		$operatingSystem = $dictionary["operatingSystem"];
		$device = $dictionary["device"];

		return view("website/app_install", [
			"ipAddress" => $request->ip(),
			"userAgent" => $userAgent,
			"operatingSystem" => $operatingSystem,
			"device" => $device,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function comingSoonPage(Request $request)  {
		$userAgent = $request->server('HTTP_USER_AGENT');

		$dictionary = $this->getDeviceAndOS($userAgent);
		$operatingSystem = $dictionary["operatingSystem"];
		$device = $dictionary["device"];

		return view("website/coming_soon", [
			"ipAddress" => $request->ip(),
			"userAgent" => $userAgent,
			"operatingSystem" => $operatingSystem,
			"device" => $device,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function aboutUsPage(Request $request)  {
		$userAgent = $request->server('HTTP_USER_AGENT');

		$dictionary = $this->getDeviceAndOS($userAgent);
		$operatingSystem = $dictionary["operatingSystem"];
		$device = $dictionary["device"];

		return view("website/about_us", [
			"ipAddress" => $request->ip(),
			"userAgent" => $userAgent,
			"operatingSystem" => $operatingSystem,
			"device" => $device,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function offerHuntingPage(Request $request)
	{
		$userAgent = $request->server('HTTP_USER_AGENT');

		$dictionary = $this->getDeviceAndOS($userAgent);
		$operatingSystem = $dictionary["operatingSystem"];
		$device = $dictionary["device"];

		return view("website/offer_hunting", [
			"ipAddress" => $request->ip(),
			"userAgent" => $userAgent,
			"operatingSystem" => $operatingSystem,
			"device" => $device,
		]);
	}

	public function offerHuntingSuccessPage(Request $request)
	{
		$userAgent = $request->server('HTTP_USER_AGENT');

		$dictionary = $this->getDeviceAndOS($userAgent);
		$operatingSystem = $dictionary["operatingSystem"];
		$device = $dictionary["device"];

		return view("website/offer_hunting_success", [
			"ipAddress" => $request->ip(),
			"userAgent" => $userAgent,
			"operatingSystem" => $operatingSystem,
			"device" => $device,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function storeOfferHuntingPage(Request $request)
	{
		// TODO requestion validation and store
		$userAgent = $request->server('HTTP_USER_AGENT');
		$dictionary = $this->getDeviceAndOS($userAgent);
		$operatingSystem = $dictionary["operatingSystem"];
		$device = $dictionary["device"];

		$rules = [
			// "name" => 'required',
			"whatsapp_number" => 'required|regex:/^[4-9]\d{7}$/',
			"discount_content" => 'required',
			"file" => 'nullable|mimes:png,jpg,jpeg,doc,docx|max:104857600'
		];

		$message = [
			// 'name.required' => 'The username field is required.',
			'whatsapp_number.required' => 'The whatsapp_number field is required.',
			'discount_content.required' => 'The discount_content field is required.',
			'file.mimes' => 'mimes',
			'file.max' => 'max'
		];

		$validator = Validator::make(
			$request->all(),
			$rules,
			$message
		);

		if ($validator->fails()) {
			return back()
				->withInput($request->input())
				->withErrors($validator->errors());
		}

		$offerHunting = CampaignOfferHunting::create(
			[
				'created_by' => __CLASS__ . "->" . __FUNCTION__,
				'updated_by' => __CLASS__ . "->" . __FUNCTION__,
				// 'name' => $request->input('name'),
				'mobile_num' => $request->input('whatsapp_number'),
				'discount_content' => $request->input('discount_content')
			]
		);

		$file = $request->file('file');
		// $filePath = 'app/foso/report-us';
		$filePath = 'storage/foso/report-us'; // path changed by Kay 2022.07.18

		if (isset($file)) {
			$filename = $this->filename($file->getClientOriginalName());
			// $file->move(storage_path('app/foso/report-us'), $filename);
			$file->move(storage_path('app/public/foso/report-us'), $filename); // path changed by Kay 2022.07.18
			$filePath .= '/' . $filename;
			$offerHunting->media = $filePath;
			$offerHunting->save();
		}

		EmailController::sendOfferHuntingEmail(config('web.offer_hunting.notification_emails_list'), $offerHunting);

		// --- 2022.10.31 remarked by request of DP------------
		// //  Save scheduled message to message queue
		// $scheduleAt = now();
		// $expiredAt = date("Y-m-d H:i:s", strtotime(now())+(60*60*24*3));;
		// $prefix = \App::environment(['production']) ? '' : 'UAT: ';
		// // TODO replace the offer template msg content
		// $offerHuntingMsg = $prefix . '謝謝報料，請靜候佳音';

		// $whatsAppQueue = new CampaignWhatsappMessageQueue();
		// $whatsAppQueue->created_by = __CLASS__ . "->" . __FUNCTION__;
		// $whatsAppQueue->offer_id = 0;
		// $whatsAppQueue->coupon_id = 0;
		// $whatsAppQueue->mobile = '+852' . $request->input('whatsapp_number');
		// $whatsAppQueue->message = $offerHuntingMsg;
		// $whatsAppQueue->message_type = 'offer-hunting';
		// $whatsAppQueue->schedule_at = $scheduleAt;
		// $whatsAppQueue->expiry_at = $expiredAt;
		// $whatsAppQueue->vendor = "twilio";
		// $whatsAppQueue->cost = "template";
		// $whatsAppQueue->save();

		return redirect()->route('website.offerhunting.success.html');
	}

	//----------------------------------------------------------------------------------------
	public function termsAndConditionsPage(Request $request)  {
		$userAgent = $request->server('HTTP_USER_AGENT');

		$dictionary = $this->getDeviceAndOS($userAgent);
		$operatingSystem = $dictionary["operatingSystem"];
		$device = $dictionary["device"];

		return view("website/terms_and_conditions", [
			"ipAddress" => $request->ip(),
			"userAgent" => $userAgent,
			"operatingSystem" => $operatingSystem,
			"device" => $device,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function privacyPage(Request $request)  {
		$userAgent = $request->server('HTTP_USER_AGENT');

		$dictionary = $this->getDeviceAndOS($userAgent);
		$operatingSystem = $dictionary["operatingSystem"];
		$device = $dictionary["device"];

		return view("website/privacy", [
			"ipAddress" => $request->ip(),
			"userAgent" => $userAgent,
			"operatingSystem" => $operatingSystem,
			"device" => $device,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function partnershipPage(Request $request)  {
		$userAgent = $request->server('HTTP_USER_AGENT');

		$dictionary = $this->getDeviceAndOS($userAgent);
		$operatingSystem = $dictionary["operatingSystem"];
		$device = $dictionary["device"];

		return view("website/partnership", [
			"ipAddress" => $request->ip(),
			"userAgent" => $userAgent,
			"operatingSystem" => $operatingSystem,
			"device" => $device,
		]);
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

	protected function filename($filename)
	{
		return now()->format('YmdHis_') . md5($filename.time()) . '.' . File::extension($filename);
	}

	//----------------------------------------------------------------------------------------
	public function uaformPage(Request $request)
	{
		$userAgent = $request->server('HTTP_USER_AGENT');

		$dictionary = $this->getDeviceAndOS($userAgent);
		$operatingSystem = $dictionary["operatingSystem"];
		$device = $dictionary["device"];

		return view("website/uaform", [
			"ipAddress" => $request->ip(),
			"userAgent" => $userAgent,
			"operatingSystem" => $operatingSystem,
			"device" => $device,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function storeUAformPage(Request $request)
	{
		// TODO requestion validation and store
		$userAgent = $request->server('HTTP_USER_AGENT');
		$dictionary = $this->getDeviceAndOS($userAgent);
		$operatingSystem = $dictionary["operatingSystem"];
		$device = $dictionary["device"];

		$rules = [
			// "name" => 'required|regex:/^[a-zA-Z\u4e00-\u9fa5]*$/',
			"name" => 'required',
			"whatsapp_number" => 'required|regex:/^[4-9]\d{7}$/',
			"account_number" => 'required|regex:/^[0-9\-]{14}$/',
			"confirm_rightinfo" => 'accepted',
			"accept_whatsappnotice" => 'accepted',
		];

		$message = [
			'name.required' => 'The username field is required.',
			'whatsapp_number.required' => 'The whatsapp_number field is required.',
			'account_number.required' => 'The account_number field is required.',
			'confirm_rightinfo' => 'Checkbox of confirm_rightinfo is required to be checked.',
			'accept_whatsappnotice' => 'Checkbox of accept_whatsappnotice is required to be checked.',
		];

		$validator = Validator::make(
			$request->all(),
			$rules,
			$message
		);
		if ($validator->fails()) {
			return back()
				->withInput($request->input())
				->withErrors($validator->errors());
		}

		$record = FormUA::create(
			[
				'name' => $request->input('name'),
				'mobile' => $request->input('whatsapp_number'),
				'ua_account' => $request->input('account_number'),
			]
		);

		$checkedinfo = $request->input('confirm_rightinfo');
		$accpetedNotice = $request->input('accept_whatsappnotice');

		$checkedinfo2 = false;
		$accpetedNotice2 = false;

		if ($checkedinfo== "on"){ $checkedinfo2 = true;}
		if ($accpetedNotice == "on"){$accpetedNotice2 = true;}

		$record->confirm_right_info = $checkedinfo2;
		$record->accept_whatsapp_notice = $accpetedNotice2;
		
		$record->save();

		//  Save session to allow loading success page once
		Session::put(self::$KEY_UA_FORM_SUCCESS, time());

		return redirect()->route('website.uaform.success.html');
	}

	//----------------------------------------------------------------------------------------
	public function uaformSuccessPage(Request $request)  {

		//  If seesion key not set that means it do not have a success form submit
		if (!Session::has(self::$KEY_UA_FORM_SUCCESS))  {
			return redirect()->route("website.uaform.html");
		}

		Session::forget(self::$KEY_UA_FORM_SUCCESS);
		$userAgent = $request->server('HTTP_USER_AGENT');

		$dictionary = $this->getDeviceAndOS($userAgent);
		$operatingSystem = $dictionary["operatingSystem"];
		$device = $dictionary["device"];

		return view("website/uaform_success", [
			"ipAddress" => $request->ip(),
			"userAgent" => $userAgent,
			"operatingSystem" => $operatingSystem,
			"device" => $device,
		]);
	}

	//  2022.06.06 Pacess
	//  Add new menu items
	//----------------------------------------------------------------------------------------
	public function kinnsoPointsPage(Request $request)  {
		$userAgent = $request->server('HTTP_USER_AGENT');

		$dictionary = $this->getDeviceAndOS($userAgent);
		$operatingSystem = $dictionary["operatingSystem"];
		$device = $dictionary["device"];

		return view("website/kinnso_points", [
			"ipAddress" => $request->ip(),
			"userAgent" => $userAgent,
			"operatingSystem" => $operatingSystem,
			"device" => $device,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function myRewardsPage(Request $request)  {

		// 2022.07.11 ---Kay
		if (!Session::has(LoginController::$KEY_MEMBER_ID))  {
			// use sesson to go back my-rewards after login
			Session::put("loginRedirectURL", route("website.myrewards.html"));
			return redirect()->route("website.login.html");
		}
		$memberID = intval(Session::get(LoginController::$KEY_MEMBER_ID));
		
		$pointBalance = Member::where('id', '=', $memberID)->value('point_balance');

		// 2022.07.27 also take 'expire_at'
		$recolums = ['id', 'redemption_id', 'void_at', 'expire_at'];
		$redemptionHistoryArray = RedemptionHistory::GetRedemptionHistoryByID($memberID, $recolums);

		return view("website/myrewards", [
			'pointBalance' => $pointBalance,
			'redemptionHistoryArray' => $redemptionHistoryArray,
		]);

	}

	//----------------------------------------------------------------------------------------
	public function myRewardDetailAPI(Request $request)  {

		// check session
		if (!Session::has(LoginController::$KEY_MEMBER_ID))  {
			return response()->json(['status' => 'force_reload'], 401);
		}
		$memberID = intval(Session::get(LoginController::$KEY_MEMBER_ID));

		$redemptionHistoryID = $request->input('id', null);

		if (is_null($redemptionHistoryID))  {
			return response()->json(['status' => 'force_reload'], 401);
		}
		// check redemption history record
		$redemptionHistoryDetail = RedemptionHistory::getRedemptionHistoryDetail($memberID, $redemptionHistoryID);
		$redemption = $redemptionHistoryDetail->redemption ?? null;
		$redemptionCode = $redemptionHistoryDetail->redemptionCode ?? null;
		if (!$redemptionHistoryDetail or !$redemption or !$redemptionCode)  {
			return response()->json(['status' => 'force_reload'], 401);
		}

		// $tempTitle = $redemption->title;
		// list($language, $content) = explode(":", $tempTitle);
		// $tempTitle = substr($content, 0, -2);

		// $tempSubTitle = $redemption->subtitle;
		// list($sblanguage, $sbcontent) = explode(":", $tempSubTitle);
		// $tempSubTitle = substr($sbcontent, 0, -2);


		$data = [
			'code_type' => $redemption->code_type ?? null,
			'thumbnail_filename' => $redemption->thumbnail_filename ?? null,
			'title' => $redemption->title ?? null,
			'subtitle' => $redemption->subtitle ?? null,
			'details' => $redemption->details ?? null,
			'void_details' => $redemption->void_details ?? null,
			'code' => $redemptionCode->code ?? null,
			// --- 2022.07.19
			'void_at' => $redemptionHistoryDetail->void_at ?? null,

		];
		return response()->json(['status' => 'success', 'data' => $data]);
	}

	//----------------------------------------------------------------------------------------
	public function redemptionPage(Request $request)  {

		$userAgent = $request->server('HTTP_USER_AGENT');

		$dictionary = $this->getDeviceAndOS($userAgent);
		$operatingSystem = $dictionary["operatingSystem"];
		$device = $dictionary["device"];
		
		$memberID = 0;
		$pointBalance = 0;
		$columns = ['id', 'thumbnail_filename', 'title', 'subtitle', 'quota', 'quota_issued', 'required_points', 'details'];
		$redemptionGifts = Redemption::getAvaiableGifts($columns);

		if (Session::has(LoginController::$KEY_MEMBER_ID))  {
			
			$memberID = intval(Session::get(LoginController::$KEY_MEMBER_ID));
			$columns = ['id', 'point_balance'];
			$member = Member::getMemberById($memberID, $columns);
			$pointBalance = $member->point_balance ?? 0;

		}else{
			//entry redemption page from Chatbot Meun 
			$tokenMenu = $request->input('_t');  

			if (!empty($tokenMenu)){
				$record= LoginRecord::getRecordWithToken($tokenMenu);
				if ($record!=null){
					 // confirm the token can be used one time only
					$memberID = $record->member_id;
					$columns = ['id', 'point_balance'];
					$member = Member::getMemberById($memberID, $columns);
					$pointBalance = $member->point_balance ?? 0;
					
					// fill info to LoginRecord
					$record->used_at = date("Y-m-d H:i:s");
					$record->ip_address = $request->ip();
					$record->user_agent = $userAgent;
					$record->save();

					// build the session for user can surf with id after entering the redemption page 
					Session::put(LoginController::$KEY_MEMBER_ID, $memberID);
				}
			}
		}
		
		foreach($redemptionGifts as $redemptionGift)
		{
			$remainQuota = $redemptionGift->quota - $redemptionGift->quota_issued;

			// $redemptionGift->title = json_decode($redemptionGift->title, true);
			// $redemptionGift->subtitle = json_decode($redemptionGift->subtitle, true);
			// $redemptionGift->details = json_decode($redemptionGift->details, true);
			$redemptionGift->maximum_quantity = $pointBalance > 0 ? intval(floor($pointBalance / $redemptionGift->required_points)): 0;
			if ( $redemptionGift->maximum_quantity > $remainQuota )
				$redemptionGift->maximum_quantity = $remainQuota;
		}

		return view("website/redemption", [
			"ipAddress" => $request->ip(),
			"userAgent" => $userAgent,
			"operatingSystem" => $operatingSystem,
			"device" => $device,
			'memberID' => $memberID,
			'pointBalance' => $pointBalance,
			'redemptionGifts' => $redemptionGifts,
		]);
	}
	//  2022.06.06 End

	//----------------------------------------------------------------------------------------
	public function PointsHistoryPage(Request $request)  {

		// check session
		if (!Session::has(LoginController::$KEY_MEMBER_ID))  {
			return response()->json(['status' => 'force_reload'], 401);
		}
		$memberID = intval(Session::get(LoginController::$KEY_MEMBER_ID));

		// check the required data by memberID
		$columns = ['id', 'point_balance', 'period_1_points', 'period_2_points'];
		$member = Member::getMemberById($memberID, $columns);
		if (!$member)  {
			return response()->json(['status' => 'force_reload'], 401);
		}
		$pointBalance = $member->point_balance;
		$period1Points = $member->period_1_points;
		$period2Points = $member->period_2_points;


		// take the point transaction record ---refer redemptionPage function -- 2022.06.21 Kay
		$ptcolums = ['created_at', 'delta_points', 'valid_at', 'description'];
		$pointHistoryArray = PointTransaction::getPointTransactionByMemberID($memberID,$ptcolums);
		//dd($pointHistoryArray);
		foreach ($pointHistoryArray as $pointHistory)
		{
			$pointHistory->description = json_decode($pointHistory->description, true);
		}

		$nowYear = date('Y');
		$nowMonth = date('m');

		return view("website/point_history", [
			'pointBalance' => $pointBalance,
			'period1Points' => $period1Points,
			'period2Points' => $period2Points,
			'pointHistory' => $pointHistoryArray,
			'nowYear' => $nowYear,
			'nowMonth' => $nowMonth,
			
		]);
	}

	public function barcodeGenerator(Request $request){

		if ($request->exists("c") == false)  {return null;}

		$content = $request->input("c");

		$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
		$image = $generator->getBarcode($content, $generator::TYPE_CODE_128);
		
    	return response($image)->header('Content-type','image/png');

	}


	//---need to edit to reture JSON
	public function redemptionHistoryRefresh(Request $request, $listtype){
	
		// ----$list_type take the data type , deflault is 0: take all
		$memberID = intval(Session::get(LoginController::$KEY_MEMBER_ID));
	
		$recolums = ['id', 'redemption_id', 'void_at', 'expire_at'];
		//dd($listtype);
		//---check with data attribute (check listtype)

		$redemptionHistoryArray = RedemptionHistory::GetRedemptionHistoryByID2($memberID, $recolums, $listtype);
		
		// foreach ($redemptionHistory as $b){
		// 	$b->redemption->title = json_decode($b->redemption->title, true);
		// 	$b->redemption->subtitle = json_decode($b->redemption->subtitle, true);
		// }
		// //dd($RedempHistory );
		return response()->json(['redemptionHistoryArray' => $redemptionHistoryArray,]);

	}



	//----------------------------------------------------------------------------------------
	public function redeem(Request $request)  {
		// check session
		if (!Session::has(LoginController::$KEY_MEMBER_ID))  {
			return response()->json(['status' => 'force_reload'], 401);
		}
		$memberID = intval(Session::get(LoginController::$KEY_MEMBER_ID));

		// check member record
		$columns = ['id', 'point_balance', 'period_1_points', 'period_2_points'];
		$member = Member::getMemberById($memberID, $columns);
		if (!$member)  {
			return response()->json(['status' => 'force_reload'], 401);
		}
		$pointBalance = $member->point_balance;
		$period1Points = $member->period_1_points;
		$period2Points = $member->period_2_points;
		
		// validation
		$validator = Validator::make($request->all(), [
			'id' => 'required',
			'quantity' => 'required|integer|min:1',
		]);

		if ($validator->fails())  {
			return response()->json(['status' => 'force_reload'], 401);
		}

		$validated = $validator->validated();
		$redemptionID = $validated['id'];
		$quantity = $validated['quantity'];
		
		// check redemption record
		$columns = ['id', 'title', 'quota', 'quota_issued', 'required_points'];
		$redemption = Redemption::getAvaiableGiftById($redemptionID, $columns);
		if (!$redemption)  {
			return response()->json(['status' => 'force_reload'], 401);
		}
		$titleDecode = $redemption->title;
		$redemptionGiftTitle = isset($titleDecode['zh-HK']) ? $titleDecode['zh-HK'] : '';
		$requiredPoints = $redemption->required_points;
		$quotaRemaining = $redemption->quota - $redemption->quota_issued;
		if ($quantity > $quotaRemaining)  {
			return response()->json(['status' => 'error', 'errors' => ['quantity' => ['獎賞剩餘'.$quotaRemaining.'份']]]);
		}

		// check point balance
		$totalRequiredPoints = $requiredPoints * $quantity;
		if ( $totalRequiredPoints > $pointBalance )  {
			return response()->json(['status' => 'error', 'errors' => ['quantity' => ['積分不足']]]);
		}
		$requiredPeriod1Points = $totalRequiredPoints;
		$requiredPeriod2Points = 0;
		if ( $requiredPeriod1Points > $period1Points )  {
			$requiredPeriod1Points = $period1Points;
			$requiredPeriod2Points = $totalRequiredPoints - $period1Points;
		}
		if ( $requiredPeriod1Points > $period1Points or $requiredPeriod2Points > $period2Points )  {
			return response()->json(['status' => 'error', 'errors' => ['quantity' => ['積分不足']]]);
		}

		// check redemption code
		$availableCodeCount = RedemptionCode::getAvailableCodeCount($redemptionID);
		if ( $availableCodeCount < $quantity )  {
			return response()->json(['status' => 'error', 'errors' => ['quantity' => ['發生錯誤，請稍後再試。(#01)']]]);
		}
		
		DB::beginTransaction();
		$rollback = false;
		$currentDateTime = date('Y-m-d H:i:s');
		$errorCode = '';

		// update redemption quota
		$affectedRows = Redemption::addQuotaIssued($redemptionID, $quantity);
		if ( $affectedRows == 0 )  {
			$rollback = true;
			$errorCode = '(#02)';
		}

		// update member point balance
		if ( $rollback === false )  {
			$affectedRows = Member::deductPoints($memberID, $totalRequiredPoints, $requiredPeriod1Points, $requiredPeriod2Points);
			if ( $affectedRows == 0 )  {
				$rollback = true;
				$errorCode = '(#03)';
			}
		}

		// create redemption hirtory and assign redemption code
		for( $i = 0; $i < $quantity; $i++ )  {
			$redemptionHistory = RedemptionHistory::create([
										'member_id' => $memberID,
										'redemption_id' => $redemptionID,
										//'expire_at' => date("Y-m-d H:i:s", strtotime("+2 month")),  //TODO: confirm what "expire_at" will be (if carbon:, ->toDateTimeString();)
									]);
			if ( !$redemptionHistory )  {
				$rollback = true;
				$errorCode = '(#04)';
				break;
			}
			$redemptionHistoryID = $redemptionHistory->id;
			$affectedRows = RedemptionCode::assignCode($redemptionID, $redemptionHistoryID);
			if ( $affectedRows != 1 )  {
				$rollback = true;
				$errorCode = '(#05)';
				break;
			}
		}

		// create point transaction
		if ( $rollback === false )  {
			$description = ['zh-HK' => $redemptionGiftTitle.' x '.$quantity];
			$pointTransaction = PointTransaction::create([
									'member_id' => $memberID,
									'delta_points' => $totalRequiredPoints * -1,
									'valid_at' => $currentDateTime,
									'expiry_at' => date('Y-m-d 23:59:59'),  // 2022.09.21 Kay: add for point calculation
									'transaction_type' => PointTransaction::$TYPE_REDEMPTION,
									'description' => json_encode($description, JSON_UNESCAPED_UNICODE),
								]);
			if ( !$pointTransaction )  {
				$rollback = true;
				$errorCode = '(#06)';
			}
		}

		if ( $rollback === true )  {
			DB::rollBack();
			return response()->json(['status' => 'error', 'errors' => ['quantity' => ['發生錯誤，請稍後再試。'.$errorCode]]]);
		}

		DB::commit();
		return response()->json(['status' => 'success']);
	}

	//----------------------------------------------------------------------------------------
	public function shoplineEnquiryPage(Request $request)  {
		$userAgent = $request->server('HTTP_USER_AGENT');

		$dictionary = $this->getDeviceAndOS($userAgent);
		$operatingSystem = $dictionary["operatingSystem"];
		$device = $dictionary["device"];

		return view("website/shoplineenquiry", [
			"ipAddress" => $request->ip(),
			"userAgent" => $userAgent,
			"operatingSystem" => $operatingSystem,
			"device" => $device,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function receiptUploadPage(Request $request)
	{	
		$userAgent = $request->server('HTTP_USER_AGENT');

		$dictionary = $this->getDeviceAndOS($userAgent);
		$operatingSystem = $dictionary["operatingSystem"] ?? "";
		$device = $dictionary["device"] ?? "";

		// Check which method to enter the page 1.from Chatbot Menu 2.from website without login 3. from website with login
		// ----- check entry redemption page from Chatbot Meun
		// $tokenMenu = "";
		// $tokenMenu = $request->input('_uptoken');
		// if (!empty($tokenMenu)){

		// 	$record= LoginRecord::getRecordWithToken($tokenMenu);
		// 	if ($record!=null){
		// 		// confirm the token can be used one time only
		// 		$memberID = $record->member_id;

		// 		// fill info to LoginRecord
		// 		$record->used_at = date("Y-m-d H:i:s");
		// 		$record->ip_address = $request->ip();
		// 		$record->user_agent = $userAgent;
		// 		$record->save();

		// 		Session::put(LoginController::$KEY_MEMBER_ID, $memberID);
		// 	}else{
		// 		return redirect()->route("receipt.login.html");
		// 	}
		// }else{
		// 	// from website
		// 	if (!Session::has(LoginController::$KEY_MEMBER_ID)){
		// 		return redirect()->route("receipt.login.html");
		// 	}else{
		// 		$memberID = intval(Session::get(LoginController::$KEY_MEMBER_ID));
		// 	}
		// }
		
		if (Session::has(LoginController::$KEY_MEMBER_ID)){
			$memberID = intval(Session::get(LoginController::$KEY_MEMBER_ID));

		}else{
			$tokenMenu = "";
			$tokenMenu = $request->input('_uptoken');

			if(empty($tokenMenu)){
				return redirect()->route("receipt.login.html");
			}

			$record= LoginRecord::getRecordWithToken($tokenMenu);
			if ($record!=null){
				// confirm the token can be used one time only
				$memberID = $record->member_id;

				// fill info to LoginRecord
				$record->used_at = date("Y-m-d H:i:s");
				$record->ip_address = $request->ip();
				$record->user_agent = $userAgent;
				$record->save();

				Session::put(LoginController::$KEY_MEMBER_ID, $memberID);
			}else{
				return redirect()->route("receipt.login.html");
			}
		}

		$keys = array();
		$sampleList = array();
		$offerInvolvedVaild = Member::getOfferInvolvedListByID($memberID); // get valid offer id and title for this member 
		// dd($offerInvolvedVaild);

		return view("website/receipt_upload_page", [
			"ipAddress" => $request->ip(),
			"userAgent" => $userAgent,
			"operatingSystem" => $operatingSystem,
			"device" => $device,
			"id" => $memberID,
			"offerInfo" => $offerInvolvedVaild,
		]);
	}
	
	//----------------------------------------------------------------------------------------
	public function offerChannelRecordApi(Request $request, $offerid){
		
		if (is_null($offerid) || $offerid == '0'){
			return response()->json(['status' => 'force_reload'], 401);
		}

		$sampleList = ChannelReceiptSample::getChannelAndUrlbyIDs($offerid);
		// dd("contorller", $sampleList);
		return response()->json(['data'=> $sampleList]);

	}

	//----------------------------------------------------------------------------------------
	public function receiptUploadApi(Request $request)
	{
		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$id = $request->id;

		$log = new CampaignFileUploadController;
		$result = $log->upload($request);

		if (strtolower($result['status']) == "ok" && isset($result['uniqid'])) {
			$log = UploadFileLog::where('uniqid', $result['uniqid'])
				->first();

			//  get the temp thumbnail with the random name and put file to /uploads
			$file = Storage::disk('local')->get('uploads/'.$log->name);
			$path = 'app/uploads/'.$log->name;

			$response["status"] = 10;
			$response["message"] = "Successfully uploaded.";
			$response["path"] = $result['serverFilename'];
			// $response["message"] = "Receipt put on the uploads, with name [$log->name]";

			// --- clear the useless file long than 30 days in disk('local')
			collect(Storage::disk('local')->listContents('uploads/', true))
				->each(function($fileClear) {

					if ($fileClear['timestamp'] < now()->subDays(30)->getTimestamp()) {  // normal : clear within 30 days
					// if ($fileClear['timestamp'] < now()->subMinute(15)->getTimestamp()) {  //testing : clear 15 min
						Storage::disk('local')->delete($fileClear['path']);
					}
			});
		}
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function saveReceiptPage(Request $request){

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		//  Check if all required parameters are available
		$parameterArray = array("id", "offer", "channel", "issueDate", "amount", "receiptNumber", "path");

		$status = 0;
		foreach ($parameterArray as $parameter)  {
			$status--;
			if (null === $request->input($parameter))  {
				$response["status"] = $status;
				$response["message"] = "Parameter $parameter not found...";
				return response()->json($response);
			}
		}
		$memberID = $request->id;
		$application = CampaignOfferReceiptUpload::create([
			'member_id' => $memberID
		]);

		$application->offer_id = $request->input("offer");
		$application->purchase_date = $request->input("issueDate");
		$application->purchase_amount = $request->input("amount");
		$application->merchant_caption_id = $request->input("channel");
		$application->invoice_number = $request->input("receiptNumber");
		$application->status = "pending";

		$imgPath = $request->input("path");
		$newPath = "";

		// TODO -- 1. check date , offer and channel
		// 2. check amount

		if(!Storage::disk('local')->exists('uploads/'.$imgPath)){
			$application->status = "rejected";
			$application->receipt_path = "";

			$tempReason["value"] = "Image not found";
			$tempReason["zh-HK"] = "沒有檔案";
			$application->reject_reason = json_encode($tempReason, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

			$application->handler = "SYSTEM";
			$application->handle_date = date("Y-m-d H:i:s");
			
	 	}else{
			$ext = pathinfo($imgPath, PATHINFO_EXTENSION);
			$newPath = str_pad($application->id, 6, "0",STR_PAD_LEFT).".".$ext;
			if(!File::isDirectory(storage_path('app/public/uploads-receipts/'))) {
				//creates directory if not exists
				File::makeDirectory(storage_path('app/public/uploads-receipts/'), 0777, true, true);
		   	}
			File::move(storage_path('app/uploads/'.$imgPath), public_path('/storage/uploads-receipts/'.$newPath));
			$application->receipt_path = 'uploads-receipts/'.$newPath;
		}
		$application->save();
		
		//----------------------------------------------------------------------------------------
		//  Output now
		$response["status"] = 10;
		$response["message"] = "Done";
		return response()->json($response);

	}

	//----------------------------------------------------------------------------------------
	public function receiptRecordListApi(Request $request){

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$memberID = $request->id;

		$dataColumns = [
			'id', 'created_at',
			'purchase_date',
			'offer_id','status',
			'reject_reason',
			'receipt_path',
		];

		$list = CampaignOfferReceiptUpload::getListByID($memberID, $dataColumns);

		foreach ($list as $item){
			if($item->status == "rejected"){
				$reasonJSON = $item->reject_reason;
				$item->reject_reason = json_decode($reasonJSON, true);
			}
		}

		$response["status"] = 10;
		$response["data"] = $list ;
		$response["message"] = "Done";

		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function receiptRecordDisplayApi(Request $request, $receiptID){

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$dataColumns = [
			'id', 'created_at',
			'offer_id','status',
			'reject_reason',
			'receipt_path',
		];

		$record = CampaignOfferReceiptUpload::getRecordbyID($receiptID, $dataColumns);

		if($record->status == "rejected"){
			$reasonJSON = $record->reject_reason;
			$record->reject_reason = json_decode($reasonJSON, true);
		}
		
		$response["status"] = 10;
		$response["data"] = $record ;
		$response["message"] = "Done";

		return response()->json($response);
	}

}

