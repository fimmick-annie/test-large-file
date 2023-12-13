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
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Session;

use App\Http\Controllers\FOSOMainController;
use App\Models\LoginRecord;
use App\Models\Member;

//========================================================================================
class LoginController extends Controller  {

	public static $KEY_MOBILE = "loginMobile";
	public static $KEY_MEMBER_ID = "loginMemberID";

	//----------------------------------------------------------------------------------------
	public function loginPage(Request $request)  {
		$userAgent = $request->server("HTTP_USER_AGENT");
		$ipAddress = $request->ip();
		
		$referrerURL = $request->server("HTTP_REFERER");
		if (empty($referrerURL))  {
			if (isset($_SERVER["HTTP_REFERER"]))  {$referrerURL = $_SERVER["HTTP_REFERER"];}
			else  {$referrerURL = route("website.myrewards.html");}
		}
		Session::put("loginRedirectURL", $referrerURL);

		return view("website/login", [
			"ipAddress" => $ipAddress,
			"userAgent" => $userAgent,

			"referrerURL" => $referrerURL,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function voidLoginToken(Request $request)  {
		// dd($request->token,$request->input("_receipt") );
		//  Somehow WhatsApp will load the page for preview, skip this if user agent is "WhatsApp"
		$userAgent = $request->server("HTTP_USER_AGENT");
		$string = strtolower($userAgent);
		if (strpos($string, "whatsapp") !== false)  {return redirect()->route("campaign.offer.listing.html");}

		if (isset($request->token) == false)  {

			//  Token not found
			return redirect()->route("campaign.offer.listing.html");
		}

		//----------------------------------------------------------------------------------------
		//  Token provided, check if it is valid
		$token = $request->token;
		$record = LoginRecord::getRecordWithToken($token);
		if ($record == null)  {

			//  Invalid token
			return redirect()->route("campaign.offer.listing.html");
		}
		
		//----------------------------------------------------------------------------------------
		//  Correct token, save session
		$ipAddress = $request->ip();

		$record->used_at = date("Y-m-d H:i:s");
		$record->ip_address = $ipAddress;
		$record->user_agent = $userAgent;
		$record->save();

		Session::put(self::$KEY_MOBILE, $record->mobile);
		Session::put(self::$KEY_MEMBER_ID, $record->member_id);

		//----------------------------------------------------------------------------------------
		//  Redirect
		$referrerURL = null;

		// -------- redirct to upload receipt if parameter exist
		if (!is_null($request->input("_receipt")))  {return redirect()->route("website.receiptupload.html");}

		if (Session::has("loginRedirectURL"))  {$referrerURL = Session::get("loginRedirectURL");}

		if (empty($referrerURL))  {return redirect()->route("website.myrewards.html");}
		return redirect($referrerURL);
	}

	//----------------------------------------------------------------------------------------
	public function requestLoginTokenAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		//----------------------------------------------------------------------------------------
		//  TODO: Validate mobile number format
		$ruleArray = [
			"mobile" => 'required|regex:/^[4-9]\d{7}$/',
		];

		$messageArray = [
			'mobile.required' => 'The mobile field is required.',
		];

		$validator = Validator::make(
			$request->all(),
			$ruleArray,
			$messageArray
		);

		if ($validator->fails())  {

			//  Validation error, return error message
			return response()->json($response);
		}

		//----------------------------------------------------------------------------------------
		//  Implicit success
		$mobile = $request->mobile;

		//  TODO: See if mobile request reaches limit (5 times per hour)
		if (LoginRecord::isReachesLimit($mobile) == true)  {

			$response["status"] = -10;
			$response["message"] = "### API call reaches limits...";
			return response()->json($response);
		}

		//  TODO: Look up member ID by mobile number

		//  Generate token value
		$token = FOSOMainController::generateRandomString();
		$response["token"] = $token;

		//  TODO: Create login record
// 		$record = LoginRecord::

		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	//  chatbotData = Chatbot state record from database
	function process($chatbotData, $userInfo, $incomingMessage, $requestDictionary)  {

		//  Return:
		//    media = Image URL for reply message
		//    message = Reply message
		//    messageType = Type or name of reply message, used in message queue
		//    chatbotData = State data that save to database
		//    canContinue = Continue process next node or branch?
		//    canTerminate = Current branch cannot handle?
		$outputDictionary = array(
			"media" => "",
			"message" => "",
			"messageType" => "",
			"chatbotData" => $chatbotData,
			"canContinue" => true,
			"canTerminate" => false,
		);

		$keywordArray = array(
			"我要登入kinnso",
			"我想上載收據"
		);
		
		$message = strtolower($incomingMessage);
		foreach ($keywordArray as $keyword)  {

			$index = strpos($message, $keyword);
			if ($index === false)  {continue;}

			//  Keyword match
			//  *** Mobile format is +85293101987
			$mobile = $userInfo["mobile"];
			if (strlen($mobile) < 8)  {continue;}

			$memberID = 0;
			$member = Member::createMember($mobile);
			if ($member)  {$memberID = $member->id;}
			
			//  Member record not found, then cannot login
			if ($memberID == 0)  {return null;}

			$token = self::generateRandomString();
			LoginRecord::createLoginRecord($memberID, $mobile, $token);

			$loginURL = route("website.login.voidlogintoken.html", ["token"=>$token]);
			if ($message == $keywordArray[1]){$loginURL .= "?_receipt=".substr($token,($memberID%8),3);}
			$message = __("messages.CHATBOT_LOGIN_MESSAGE", ["loginURL"=>$loginURL]);

			//  False = Already handled incoming message, no need to continue
			$outputDictionary["canContinue"] = false;
			$outputDictionary["message"] = $message;
			$outputDictionary["messageType"] = "login";
			return $outputDictionary;

		}

		return null;
	}


	// public function voidRedemptionLoginToken(Request $request){

	// 	if (isset($request->token) == false){

	// 		//  Token not found
	// 		return redirect()->route("website.redemption.html");
	// 	}

	// 	//----------------------------------------------------------------------------------------
	// 	//  Token provided, check if it is valid
	// 	$token = $request->token;
	// 	$record = LoginRecord::getRecordWithToken($token);
	// 	if ($record == null)  {
	// 		return redirect()->route("website.redemption.html");
	// 	}

	// 	//----------------------------------------------------------------------------------------
	// 	//  Correct token, save session
	// 	$ipAddress = $request->ip();

	// 	$record->used_at = date("Y-m-d H:i:s");
	// 	$record->ip_address = $ipAddress;
	// 	$record->user_agent = $userAgent;
	// 	$record->save();

	// 	// Session::put(self::$KEY_MOBILE, $record->mobile);
	// 	Session::put(self::$KEY_MEMBER_ID, $record->member_id);

	// 	return redirect()->route("website.redemption.html");

	// }

		//----------------------------------------------------------------------------------------
		public function receiptLoginPage(Request $request)  {
			$userAgent = $request->server("HTTP_USER_AGENT");
			$ipAddress = $request->ip();
	
			$referrerURL = $request->server("HTTP_REFERER");
			if (empty($referrerURL))  {
				if (isset($_SERVER["HTTP_REFERER"]))  {$referrerURL = $_SERVER["HTTP_REFERER"];}
				else  {$referrerURL = route("website.receiptupload.html");}
			}
			Session::put("loginRedirectURL", $referrerURL);
	
			return view("website/receipt_login", [
				"ipAddress" => $ipAddress,
				"userAgent" => $userAgent,
	
				"referrerURL" => $referrerURL,
			]);
		}


}
