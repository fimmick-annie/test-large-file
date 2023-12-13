<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020-2022.  All rights reserved.
//----------------------------------------------------------------------------------------

namespace App\Http\Controllers;

//----------------------------------------------------------------------------------------
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

use App\Models\LoginRecord;
use App\Models\Member;
use Session;

//========================================================================================
class ChatbotMenuController extends Controller  {

	//----------------------------------------------------------------------------------------
	//  Return:
	//    media = Image URL for reply message
	//    message = Reply message
	//    messageType = Type or name of reply message, used in message queue
	//    chatbotData = State data that save to database
	//    canContinue = Continue process next node or branch?
	//    canTerminate = Current branch cannot handle?
	function process($chatbotData, $userInfo, $incomingMessage, $requestDictionary)  {

		$outputDictionary = array(
			"media" => "",
			"message" => "",
			"messageType" => "",
			"chatbotData" => $chatbotData,
			"canTerminate" => true,
			"canContinue" => true,
		);

		//  Show menu if keyword match
		if (strtolower($incomingMessage) == "menu")  {

			//  Need to show menu, override current journey
			$chatbotData["currentOfferID"] = 0;
			$outputDictionary["chatbotData"] = $chatbotData;

			$outputDictionary["message"] = __("messages.CHATBOT_MENU", []);
			$outputDictionary["canTerminate"] = true;
			$outputDictionary["canContinue"] = false;
			return $outputDictionary;
		}

		//  If new comer, chatbot data is empty
		if (isset($chatbotData["currentOfferID"]))  {

			//  Chatbot is in a journey, no need to process menu
			if ($chatbotData["currentOfferID"] != 0)  {return $outputDictionary;}
		}
	
		//----------------------------------------------------------------------------------------
		//  Is user reply daily question instead?
		if (isset($chatbotData["currentDailyQuestionID"]) != false
			&& $chatbotData["currentDailyQuestionID"] > 0)  {

			//  Yes, answering daily question, skip menu logic
			$outputDictionary["canTerminate"] = true;
			return $outputDictionary;
		}
		
		//----------------------------------------------------------------------------------------
		//  No journey is in progress, should be menu reply?
		Log::debug(__FUNCTION__." incomingMessage=".$incomingMessage);
		$mobile = $userInfo["mobile"];
		$member = Member::getMemberByMobile($mobile);

		// If the user have no member account before, the member account would be created
		if (is_null($member)){$member = Member::createMember($mobile);}

		switch ($incomingMessage)  {

			//  Daily question
			case "1":
			case "與蜜熊傾偈賺積分":  {
				$outputDictionary["incomingMessage"] = "daily_question";
				$outputDictionary["canContinue"] = true;
				return $outputDictionary;
			}  break;

			//  Referral
			case "2":{

				$outputDictionary["message"] = __("messages.CHATBOT_REFERRAL_LINK_1");
				$outputDictionary["incomingMessage"] = "b2_referral_link";
				$outputDictionary["canContinue"] = true;
			
				return $outputDictionary;

			}  break;

			case "b2_referral_link":{

				$referralMessage = $member->referral_code;
				$link = asset('')."?referral=".urlencode($referralMessage); 
				unset($outputDictionary["incomingMessage"] );
				$outputDictionary["message"] = __("messages.CHATBOT_REFERRAL_LINK_2", ["link"=>$link]);
				$outputDictionary["canContinue"] = false;
			
				return $outputDictionary;

			}  break;

			// get Honey points
			case "3":  {
				$point = 0;
				$pointPeriod1 = 0;
				$pointPeriod2 = 0;
				if ($member != null && isset($member->point_balance))  {

					$point = $member->point_balance;
					$pointPeriod1 = $member->period_1_points;
					$pointPeriod2 = $member->period_2_points;
				}

				$monthNow = date('m');
				$yearNow = date('Y');
  
				if ($monthNow <= 6){
					$period1 = $yearNow."-06-30";
					$period2 = $yearNow."-12-31";
				}else{
					$period1 = $yearNow."-12-31";
					$period2 = ($yearNow+1)."-06-30";
				}
			
				//  1672502399 = 2022-12-31 23:59:59
				if (time() > 1672502399)  {

					//  After 2022, can show both periods
					$outputDictionary["message"] = __("messages.CHATBOT_MENU_POINT_BALANCE", [
						"point" => $point,
						"date1" => $period1,
						"period1points" => $pointPeriod1,
						"date2" => $period2,
						"period2points" => $pointPeriod2
					]);

				}  else  {

					//  In 2022, can show 2nd period
					$outputDictionary["message"] = __("messages.CHATBOT_MENU_POINT_BALANCE_2022", [
						"point" => $point,
						"date2" => $period2,
						"period2points" => $pointPeriod2
					]);

				}

				$outputDictionary["canContinue"] = false;
				return $outputDictionary;
			}  break;

			//  go to redemption page
			case "4":  {
				if($member != null){

					$token = FOSOMainController::generateRandomString(16);
					LoginRecord::createLoginRecord($member->id, $mobile, $token);
					$link = route('website.redemption.html').'?_t='.$token;
					// $link = route('website.redemption.html');
					// Session::put(LoginController::$KEY_MEMBER_ID, $member->id);
				}else{
					$link = route('website.redemption.html');
				}

				$outputDictionary["message"] =  __("messages.DIRECT_LOGIN_TO_REDEMPTION", ["link"=>$link]);
				$outputDictionary["canContinue"] = false;
				return $outputDictionary;

			}  break;


			// case 5 for upload receipt ---- 2022.11.15 Kay
			case "5":{
				if($member == null){
					$member = Member::createMember($mobile);
				}
				$token = FOSOMainController::generateRandomString(16);
				LoginRecord::createLoginRecord($member->id, $mobile, $token);
				$link = route("website.receiptupload.html").'?_uptoken='.$token;
				$outputDictionary["message"] =  __("messages.DIRECT_LOGIN_TO_UPLOAD_RECEIPT", ["link"=>$link]);
				$outputDictionary["canContinue"] = false;
				return $outputDictionary;

			} break;


			default:  {
				//  我要加入Kinnso！(1234Vcezmab)
				if (strpos($incomingMessage, "我要加入Kinnso！") !== false)  {
					Log::debug(__FUNCTION__." $mobile is referrer by $incomingMessage...");

					$matchArray = array();
					preg_match("/\(([0-9a-zA-Z]+)\)/", $incomingMessage, $matchArray);
					$count = count($matchArray);
					Log::debug(__FUNCTION__." count=$count");
					if ($count > 1)  {

						//  matchArray[0] = Pattern
						//  matchArray[1] = The parameter value
						$referrerCode = $matchArray[1];
						Log::debug(__FUNCTION__." referrerCode=$referrerCode");
						$referrer = Member::getMemberByReferralCode($referrerCode);
						if ($referrer != null)  {

							$point = 100;

							$referree = Member::createMember($mobile);
							$referree->addReferreePoint($point);
							$referree->referrer_id = $referrer->id;

							$referrer->addReferralPoint($point);
							Log::debug(__FUNCTION__." Referrer #".$referrer->id." +point, and referree #".$referree->id." +point!");

							$outputDictionary["message"] = __("messages.CHATBOT_REFER_SUCCESS", ["point"=>$point]);
							$outputDictionary["canContinue"] = false;
							return $outputDictionary;
						}  else  {

							Log::debug(__FUNCTION__." Referrer code $referrerCode not found...");
						}
					}
				}
			}  break;
		}
		$outputDictionary["canTerminate"] = true;
		Log::debug(__FUNCTION__." outputDictionary=".json_encode($outputDictionary));
		return $outputDictionary;
	}

}
