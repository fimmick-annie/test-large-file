<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020.  All rights reserved.
//----------------------------------------------------------------------------------------

//  Error code:
//   10 = Generic reply has been sent
//    1 = Node has been proceed
//    0 = OK
//  -99 = Unknown error...

namespace App\Http\Controllers;

//----------------------------------------------------------------------------------------
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Twilio\Rest\Client;
use DB;

use App\Models\CampaignWhatsappMessageQueue;
use App\Models\ChatbotDailyQuestionReply;
use App\Models\CampaignCustomerJourney;
use App\Models\CampaignCouponPool;
use App\Models\PointTransaction;
use App\Models\WhatsappWebhook;
use App\Models\CampaignCoupon;
use App\Models\ChatbotState;
use App\Models\LineWebhook;
use App\Models\Member;

//========================================================================================
class ChatbotController extends Controller  {

	//----------------------------------------------------------------------------------------
	function sendWhatsAppMessage($mobile, $messageType, $message, $offerID=0, $couponID=0, $media=null, $sid=null)  {

		$now = date("Y-m-d H:i:s");
		$result = "";
		$messageID = "";

		if (!is_null($sid))  {

			// $message are parameter in the template message
			$receiver = "whatsapp:".$mobile;
			$result = ChatbotController::contentAPIMenu($receiver, $message, $sid);
			$status = "Success";

		}  else if (empty($message) == false || empty($media) == false)  {

			$prefix = env("WHATSAPP_PREFIX", "");
			$message = $prefix . $message;

			$receiver = "whatsapp:" . $mobile;
			$sid = env('WHATSAPP_SID', '');
			$token = env('WHATSAPP_TOKEN', '');
			$sender = env('WHATSAPP_SENDER', '');

			$whatsAppEnabled = env('WHATSAPP_ENABLED', false);
			if ($whatsAppEnabled == true)  {
				// $dictionary = [
				// 	"from" => $sender,
				// 	"body" => $message,
				// ];
				// if (!empty($media))  {
				// 	$dictionary["mediaUrl"] = [$media];
				// }

				// $twilio = new Client($sid, $token);
				// $result = $twilio->messages->create($receiver, $dictionary);

				// //  [Twilio.Api.V2010.MessageInstance accountSid=AC04720401fa35445e2b51221457f30f21 sid=SMb8ee374ebfb14f82973c7336b0a90712]
				// $matchArray = array();
				// preg_match("/sid=([0-9a-zA-Z]+)\]/", $result, $matchArray);
				// $count = count($matchArray);
				// if ($count > 1)  {
				// 	$messageID = $matchArray[1];
				// }

				//  No need include menu
				$dictionary = [
					"from" => $sender,
					"body" => $message,
				];
				if (!empty($media))  {
					$dictionary["mediaUrl"] = [$media];
				}

				$twilio = new Client($sid, $token);
				$result = $twilio->messages->create($receiver, $dictionary);

				//  [Twilio.Api.V2010.MessageInstance accountSid=AC04720401fa35445e2b51221457f30f21 sid=SMb8ee374ebfb14f82973c7336b0a90712]
				$matchArray = array();
				preg_match("/sid=([0-9a-zA-Z]+)\]/", $result, $matchArray);
				$count = count($matchArray);
				if ($count > 1)  {
					$messageID = $matchArray[1];
				}

			} else {

				//  Disabled WhatsApp for saving money
				$result = "[Twilio.Api.V2010.MessageInstance accountSid=ACa8c4e3793f543cc3b4d68b112171edf1 sid=SM940bd08464984df49cd8f246e683704a] Disabled";
				$messageID = "Disabled";
			}
			$status = "Success";
		} else {

			$status = "Success";
			$result = "[Twilio.Api.V2010.MessageInstance accountSid=ACa8c4e3793f543cc3b4d68b112171edf1 sid=SM940bd08464984df49cd8f246e683704a] Simulation";
			$messageID = "Simulation";
		}
		$response["whatsapp"] = $result;

		$expiryDate = date("Y-m-d H:i:s", strtotime("+3 days"));

		//  Add to whatsapp log
		$whatsAppQueue = new CampaignWhatsappMessageQueue();
		$whatsAppQueue->created_by = basename(__FILE__);
		$whatsAppQueue->offer_id = $offerID;
		$whatsAppQueue->coupon_id = $couponID;
		$whatsAppQueue->mobile = $mobile;
		$whatsAppQueue->message = $message;
		$whatsAppQueue->media = $media;
		$whatsAppQueue->message_type = $messageType;
		$whatsAppQueue->schedule_at = $now;
		$whatsAppQueue->expiry_at = $expiryDate;
		$whatsAppQueue->send_at = $now;
		$whatsAppQueue->vendor = "twilio";
		$whatsAppQueue->message_id = $messageID;
		$whatsAppQueue->status = $status;
		$whatsAppQueue->response = $result;
		$whatsAppQueue->cost = "session";
		$whatsAppQueue->save();
	}

	//----------------------------------------------------------------------------------------
	//  WhatsApp
	//----------------------------------------------------------------------------------------
	function webhookTwilioMessageComesInAPI(Request $request)  {
		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		//----------------------------------------------------------------------------------------
		//  Incoming JSON template:
		//  "SmsMessageSid":"SM1153edcc9b93e0a4a1413da69a9366f7",
		//  "NumMedia":"1",
		//  "MediaContentType0": "image\/jpeg",
		//  "MediaUrl0": "https:\/\/api.twilio.com\/2010-04-01\/Accounts\/AC09737959feb5b60cefa9b2130a18cdde\/Messages\/MM14ac47ddbe269d29aa2a508eea9c538e\/Media\/MEd091fb584a331678e13b069eff318d3c",
		//  "SmsSid":"SM1153edcc9b93e0a4a1413da69a9366f7",
		//  "SmsStatus":"received",
		//  "Body":"UAT: \u6211\u60f3\u9818\u53d6 L\u2019Occitane \u9ad4\u9a57\u88dd\uff08\u63db\u9818\u7de8\u78bc\uff1aitqOzi5w\uff09\u7684\u63db\u9818\u9023\u7d50\uff01",
		//  "To":"whatsapp:+85264500540",
		//  "NumSegments":"1",
		//  "MessageSid":"SM1153edcc9b93e0a4a1413da69a9366f7",
		//  "AccountSid":"ACa8c4e3793f543cc3b4d68b112171edf1",
		//  "From":"whatsapp:+85260382020",
		//  "ApiVersion":"2010-04-01"

		//  Save incoming message first
		$messageID = "";
		$dictionary = $request->post();
		if (isset($dictionary["SmsSid"]))  {
			$messageID = $dictionary["SmsSid"];
		}
		WhatsappWebhook::addRecord("twilio", $messageID, "message", json_encode($dictionary));

		// some checking
		if (isset($dictionary["ErrorUrl"]))  {
			$errorURL = $dictionary["ErrorUrl"];
			Log::error("ERROR: " . $errorURL);
			$response["errorURL"] = $errorURL;
			return response()->json($response);
		}

		$mobile = (isset($dictionary["From"])) ? explode("whatsapp:", $dictionary["From"])[1] : null;
		if ($mobile == null)  {
			$response["status"] = -1;
			$response["message"] = "Mobile number not found...";
			return response()->json($response);
		}

		// check body / incoming message is not null
		if (isset($dictionary["Body"]) == false)  {
			$message = __("messages.CHATBOT_REPLY_NORMAL");
			$this->sendWhatsAppMessage($mobile, "customer-service", $message, 0, 0, null);

			//  Not a valid incoming message
			$response["status"] = 0;
			$response["message"] = "Done";
			return response()->json($response);
		}
		// end checking

		//  First or new member by mobile
// 		Member::firstOrNew(array("mobile" => $mobile));

// 		$memberID = md5($mobile);
// 		Member::firstOrNew(array("member_id" => $memberID));

		Member::createMember($mobile);

		//  Redirect message to UAT site
		$_uat = $this->redirectUATMessage($mobile, $dictionary);
		if ($_uat)  {
			$response["result"] = $_uat;
			return response()->json($response);
		}

		//  Only process text message
		$incomingMessage = $dictionary["Body"];
		//  Remove prefix from incoming message, otherwise cannot test outside production environment
		$prefix = env("WHATSAPP_PREFIX", "");
		$message = "";
		$incomingMessage = str_replace($prefix, "", $incomingMessage);

		$logicBoxOutputArr = $this->processChatbotLogic($incomingMessage, $mobile, $dictionary);
		if ($logicBoxOutputArr)  {
			// just send whatsApp message
			$this->handleLogicBoxOutput($logicBoxOutputArr, $mobile);
			// No need further process, can exit now
			// return $response;
			return '';
		}
		//  todo: below is not very good, should put into chatbot logic box
		//  Suppose handled all incoming message, but all children no response
		//  Nobody can handle, then show customer service message
		//  Check if member optout or mute, then send cs
		// $memberIsOptOut = $this->checkMemberIsOptOut($mobile);
		// if($memberIsOptOut == false || !$memberIsOptOut)  {
		// 	$this->sendWhatsAppMessage($mobile, "customer-service", $message, 0, 0, null);
		// 	//  No need further process, can exit now
		// 	return $response;
		// }
		//  end todo
		//  Finally
// 		return $response;

		// 		return "<Response>".
		// 			"<Say>".$message."</Say>".
		// 			"</Response>";

		//----------------------------------------------------------------------------------------
		//  Finally
		return "";
	}

	//----------------------------------------------------------------------------------------
	public function webhookTwilioStatusCallbackAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$dictionary = $request->post();
		if (count($dictionary) == 0)  {return response()->json($response);}

		$messageID = "";
		$json = json_encode($dictionary);
		// 		$message = "\n".date("Y-m-d H:i:s")." ".$json;
		// 		file_put_contents("whatsapp_status.log", $message, FILE_APPEND);
		if (isset($dictionary["SmsSid"]))  {
			$messageID = $dictionary["SmsSid"];
		}
		WhatsappWebhook::addRecord("twilio", $messageID, "status", $json);

		$status = strtolower($dictionary["SmsStatus"]);
		switch ($status)  {

				//----------------------------------------------------------------------------------------
				//	{
				//		"SmsSid":"SMdf30baebb5ba4a2f8521b878305163d7",
				//		"SmsStatus":"sent",
				//		"MessageStatus":"sent",
				//		"ChannelToAddress":"+8529412XXXX",
				//		"To":"whatsapp:+85294129112",
				//		"ChannelPrefix":"whatsapp",
				//		"MessageSid":"SMdf30baebb5ba4a2f8521b878305163d7",
				//		"AccountSid":"ACa8c4e3793f543cc3b4d68b112171edf1",
				//		"From":"whatsapp:+85264500540",
				//		"ApiVersion":"2010-04-01",
				//		"ChannelInstallSid":"XE5786edb45e496c58de2b5caecc3a1819"
				//	}
			case "sent": {
					$messageID = $dictionary["MessageSid"];
					if (strlen($messageID) <= 0)  {

						//  Error
						$response["status"] = -1;
						$response["message"] = "Invalid message ID...";
						return response()->json($response);
					}

					//  9 = String length of "whatsapp:"
					$mobile = $dictionary["To"];
					$mobile = substr($mobile, 9);

					$messageStatus = ucfirst($status);

					CampaignWhatsappMessageQueue::updateMessageStatusAndReceipt($mobile, "twilio", $messageID, $messageStatus, $json);
				}
				break;

				//----------------------------------------------------------------------------------------
				//	{
				//		"EventType":"DELIVERED",
				//		"SmsSid":"SMdf30baebb5ba4a2f8521b878305163d7",
				//		"SmsStatus":"delivered",
				//		"MessageStatus":"delivered",
				//		"ChannelToAddress":"+8529412XXXX",
				//		"To":"whatsapp:+85294129112",
				//		"ChannelPrefix":"whatsapp",
				//		"MessageSid":"SMdf30baebb5ba4a2f8521b878305163d7",
				//		"AccountSid":"ACa8c4e3793f543cc3b4d68b112171edf1",
				//		"From":"whatsapp:+85264500540",
				//		"ApiVersion":"2010-04-01",
				//		"ChannelInstallSid":"XE5786edb45e496c58de2b5caecc3a1819"
				//	}
			case "delivered": {
					$messageID = $dictionary["MessageSid"];
					if (strlen($messageID) <= 0)  {

						//  Error
						$response["status"] = -1;
						$response["message"] = "Invalid message ID...";
						return response()->json($response);
					}

					//  9 = String length of "whatsapp:"
					$mobile = $dictionary["To"];
					$mobile = substr($mobile, 9);

					$messageStatus = ucfirst($status);

					CampaignWhatsappMessageQueue::updateMessageStatusAndReceipt($mobile, "twilio", $messageID, $messageStatus, $json);
				}
				break;

				//----------------------------------------------------------------------------------------
				// 	{
				// 		"EventType":"READ",
				// 		"SmsSid":"SM34411677f6894cd19266b7d4e6996a30",
				// 		"SmsStatus":"read",
				// 		"MessageStatus":"read",
				// 		"ChannelToAddress":"+8529244XXXX",
				// 		"To":"whatsapp:+85292446907",
				// 		"ChannelPrefix":"whatsapp",
				// 		"MessageSid":"SM34411677f6894cd19266b7d4e6996a30",
				// 		"AccountSid":"ACa8c4e3793f543cc3b4d68b112171edf1",
				// 		"From":"whatsapp:+85264500540",
				// 		"ApiVersion":"2010-04-01"
				// 	}
			case "read": {
					$messageID = $dictionary["MessageSid"];
					if (strlen($messageID) <= 0)  {

						//  Error
						$response["status"] = -1;
						$response["message"] = "Invalid message ID...";
						return response()->json($response);
					}

					//  9 = String length of "whatsapp:"
					$mobile = $dictionary["To"];
					$mobile = substr($mobile, 9);

					$messageStatus = ucfirst($status);

					CampaignWhatsappMessageQueue::updateMessageStatusAndReceipt($mobile, "twilio", $messageID, $messageStatus, $json);
				}
				break;

			default: {
				}
				break;
		}

		//----------------------------------------------------------------------------------------
		//  Output now
		// 		return "<xml>".
		// 			"<Response>".
		// 			"<Say>Status callback API done</Say>".
		// 			"</Response>".
		// 			"</xml>";

		return "";
	}

	//----------------------------------------------------------------------------------------
	public function webhookTwilioFallbackAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$dictionary = $request->post();
		if (count($dictionary) == 0)  {
			return response()->json($response);
		}

		$messageID = "";
		$json = json_encode($dictionary);
		if (isset($dictionary["SmsSid"]))  {
			$messageID = $dictionary["SmsSid"];
		}
		WhatsappWebhook::addRecord("twilio", $messageID, "fallback", $json);

		//  Output now
		// 		return "<xml>".
		// 			"<Response>".
		// 			"<Say>Fallback API done</Say>".
		// 			"</Response>".
		// 			"</xml>";

		return "";
	}

	//----------------------------------------------------------------------------------------
	//  Facebook Messenger
	//----------------------------------------------------------------------------------------
	function webhookFacebookMessageComesInAPI(Request $request)  {
	}

	//----------------------------------------------------------------------------------------
	//  LINE
	//----------------------------------------------------------------------------------------
	//  Sample request:  {
	//		"events":[{
	//			"type":"message",
	//			"replyToken":"fc94fdb6671c4251baa0c8c930909b1b",
	//			"source":{
	//				"userId":"Uec30c4e8ff21506619b9d483feb5469a",
	//				"type":"user"
	//			},
	//			"timestamp":1597245034945,
	//			"mode":"active",
	//			"message":{
	//				"type":"text",
	//				"id":"12488158795725",
	//				"text":"Good"
	//			}
	//		}],
	//		"destination":"Ufd637714c4a33ebf2c3d80ee99e2aed8"
	//	}
	function webhookLineMessageComesInAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		//----------------------------------------------------------------------------------------
		//  Save all incoming message for safe
		$messageID = time();
		$dictionary = $request->post();
		$json = json_encode($dictionary);
		$status = "";

		LineWebhook::addRecord("line", $messageID, $status, $json);

		//----------------------------------------------------------------------------------------
		$eventArray = $json["events"];
		foreach ($eventArray as $event)  {

			//  Empty message
			if (isset($event["message"]) == false)  {

				//  TODO: Send CS message
				continue;
			}

			if (isset($event["Source"]["userId"]) == false)  {

				//  TODO: Send CS message
				continue;
			}

			$userID = $event["Source"]["userId"];
			Member::createMemberByID($userID);

			$message = $event["message"]["text"];
		}

		//  Check if member optout or mute
		// 		Member::createMember($mobile);
		//
		// 		$mute = false;
		// 		$optout = false;
		// 		$member = null;
		// 		$memberArray = Member::getList(null, null, $mobile);
		// 		if ($memberArray != null)  {
		//
		// 			$count = count($memberArray);
		// 			if ($count > 0)  {
		//
		// 				$member = $memberArray[0];
		// 				if ($member->optout_at != null)  {$optout = true;}
		// 				if ($member->mute_until != null)  {
		//
		// 					$time = strtotime($member->mute_until);
		// 					if ($time > time())  {
		//
		// 						//  Muting
		// 						$mute = true;
		// 						return "";
		// 					}
		// 				}
		// 			}
		// 		}

		//----------------------------------------------------------------------------------------
	}

	//----------------------------------------------------------------------------------------
	public function clearSimulatorRecord()  {
		$mobile = env('WHATSAPP_SIMULATOR_NUMBER');
		CampaignCouponPool::where('mobile', $mobile)->delete();
		CampaignCoupon::where('mobile', $mobile)->delete();
		CampaignCustomerJourney::where('mobile', $mobile)->delete();
		ChatbotState::where('mobile', $mobile)->delete();

		//  2022.03.08 Pacess
		ChatbotDailyQuestionReply::where('mobile', $mobile)->delete();

		$member = Member::getMemberByMobile($mobile);
		if ($member != null)  {
			PointTransaction::where('member_id', $member->id)->delete();
		}
		//  2022.03.08 End
	}

	//----------------------------------------------------------------------------------------
	public function whatsAppSimulator(Request $request)  {

		$message = $request->input('message');

		//----------------------------------------------------------------------------------------
		if (trim(strtolower($message)) == 'clear')  {
			$this->clearSimulatorRecord();
			$return = [];
			$return[]['message'] = 'Clear Record Success.';
			return $return;
		}

		//----------------------------------------------------------------------------------------
		$json = $request->all();
		$chatbotOutputArr = $this->processChatbotLogic($message, env('WHATSAPP_SIMULATOR_NUMBER'), $json);
		$return = [];
		foreach ($chatbotOutputArr as $output)  {
			// foreach($branchOutputArr as $output)  {
			if ($output['media'])  {
				$return[]['media'] = $output['media'];
			}
			if ($output['message'])  {
				$return[]['message'] = $output['message'];
			}
			// }
		}
		if(empty($return))  {
			$memberIsOptOut = $this->checkMemberIsOptOut(env('WHATSAPP_SIMULATOR_NUMBER'));
			if ($memberIsOptOut == false || !$memberIsOptOut)  {
				$messengerLink = env("BRAND_MESSENGER_LINK", "");
				$message = __("messages.CHATBOT_REPLY_NORMAL",  ["messengerLink" => $messengerLink]);
				$return[]['message'] = $message;
			}
		}
		return json_encode($return, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}

	//----------------------------------------------------------------------------------------
	//  TODO: Should pass $resultDictionary
	public function handleLogicBoxOutput($logicBoxOutputArr, $mobile)  {

		// See if any message need to be sent
		// Message will be empty
		$messageDelay = 0;

		$replied = false;
		foreach ($logicBoxOutputArr as $logicBoxOutput)  {
			$media = $logicBoxOutput["media"];
			$message = $logicBoxOutput["message"];
			if (empty($message) == false || empty($media) == false)  {

				//  Optional return values
				$offerID = 0;
				$couponID = 0;

				//  20210610 Pacess
				//  Offer ID & coupon ID is from resultDictionary but not exists
				//  It should be logicBoxOutput after restructure
// 				if (isset($resultDictionary["offerID"]))  {
// 					$offerID = $resultDictionary["offerID"];
// 				}
// 				if (isset($resultDictionary["couponID"]))  {
// 					$couponID = $resultDictionary["couponID"];
// 				}
				if (isset($logicBoxOutput["offerID"]))  {
					$offerID = $logicBoxOutput["offerID"];
				}
				if (isset($logicBoxOutput["couponID"]))  {
					$couponID = $logicBoxOutput["couponID"];
				}
				//  20210610 End

				$sid = null;
				if (isset($logicBoxOutput["quickReplySID"]))  {
					$sid = $logicBoxOutput["quickReplySID"];
				}

				//  To prevent message first-in-last-out issue
				if ($messageDelay > 0)  {
					sleep($messageDelay);
				}

				$messageType = $logicBoxOutput["messageType"];
				$this->sendWhatsAppMessage($mobile, $messageType, $message, $offerID, $couponID, $media, $sid);
				$replied = true;

				//  Setup delay time for next instant message
				//  6 seconds delay for image process by Twilio, value from measurement
				if (empty($media))  {$messageDelay = 3;}
				else  {$messageDelay = 6+4;}
			}
		}

		//----------------------------------------------------------------------------------------
		//  Kind of customer service message
		if ($replied == false)  {

			//  TODO: Should show menu message here?  Or combine with normal reply.

			$messengerLink = env("BRAND_MESSENGER_LINK", "");
			$message = __("messages.CHATBOT_REPLY_NORMAL",  ["messengerLink" => $messengerLink]);
			$memberIsOptOut = $this->checkMemberIsOptOut($mobile);
			if ($memberIsOptOut == false || !$memberIsOptOut)  {

				$this->sendWhatsAppMessage($mobile, "customer-service", $message, 0, 0, null);
				//  No need further process, can exit now
				return 'ok';
			}
		}

		return 'ok';
	}

	//----------------------------------------------------------------------------------------
	public function processChatbotLogic($message, $mobile, $dictionary)  {

		$responseArray = [];
		$incomingMessage = $message;

		//  *** Order is important ***
		$branchDictionary = array(
			"optout" => "OptOutChatbotController",
			"login" => "LoginController",

			//  Menu cannot be last, it will modify 'incomingMessage'
			"menu" => "ChatbotMenuController",

			//  Daily question's priority must before coupon
			"daily_question" => "DailyQuestionController",
			"coupon" => "CouponChatbotController",
		);

		//  Load chatbot state of current user
		$chatbotState = ChatbotState::firstOrNew(array(
			"mobile" => $mobile,
			"channel" => "whatsapp",
			"branch" => "coupon",
		));

		$userInfo = array(
			"mobile" => $mobile,
		);

		if ($chatbotState->chatbot_data == null)  {
			$chatbotState->chatbot_data = json_encode(array());
		}
		Log::debug("\n");

		//  Call children one by one
		$chatbotData = json_decode($chatbotState->chatbot_data, true);
		foreach ($branchDictionary as $branch => $controller)  {

			Log::debug(__FUNCTION__." Processing ".$branch);

			$canTerminate = false;
			$canContinue = false;
			$loop = 0;
			do  {

				$loop++;

				//  Process the first node or current node
				$resultDictionary = app('App\\Http\\Controllers\\' . $controller)->process($chatbotData, $userInfo, $incomingMessage, $dictionary);
				Log::debug(__FUNCTION__." resultDictionary=".json_encode($resultDictionary));
				if ($resultDictionary == null)  {

					//  Stop this branch, continue next branch
					$canContinue = true;
					$canTerminate = true;
				}  else  {

					//  Return:
					//    media = Image URL for reply message
					//    message = Reply message
					//    messageType = Type or name of reply message, used in message queue
					//    chatbotData = State data that save to database
					//    canContinue = Continue process next node or branch?
					//    canTerminate = Current branch cannot handle?
					$responseArray[] = $resultDictionary;
					$chatbotData = $resultDictionary["chatbotData"];
					if (isset($resultDictionary["canContinue"]))  {
						$canContinue = $resultDictionary["canContinue"];
					}
					if (isset($resultDictionary["canTerminate"]))  {
						$canTerminate = $resultDictionary["canTerminate"];
					}

					//----------------------------------------------------------------------------------------
					// process empty message to trigger logic node ?
//Pacess:It breaks logic					$incomingMessage = '';
					if (isset($resultDictionary["incomingMessage"]))  {
						$incomingMessage = $resultDictionary["incomingMessage"];
					}
				}

				//  canContinue is much powerful than canTerminate
				//  canTerminate just control current branch
			}  while ($canContinue == true && $canTerminate == false && $loop < 20);

			//  Update latest state to database
			$chatbotState->chatbot_data = json_encode($chatbotData);
			$chatbotState->save();

			if ($canContinue == false)  {

				//  No need further process, can exit now
				//  *** Message can be empty
				return $responseArray;
			}
		}
		return $responseArray;
	}

	//----------------------------------------------------------------------------------------
	public function checkMemberIsOptOut($mobile)  {

		$mute = false;
		$optout = false;
		$member = null;
		$memberArray = Member::getList(null, null, $mobile);
		if ($memberArray != null)  {

			$count = count($memberArray);
			if ($count > 0)  {

				$member = $memberArray[0];
				if ($member->optout_at != null)  {
					$optout = true;
					return true;
				}
				if ($member->mute_until != null)  {

					$time = strtotime($member->mute_until);
					if ($time > time())  {

						//  Muting
						$mute = true;

						//  20201125 Pacess
						//  Requested by CRM, mute all message
						// 	return "<Response><Say>###  Mute  ###</Say></Response>";
						//  20201125 End

						return true;
					}
				}
			}
		}
		$messengerLink = env("BRAND_MESSENGER_LINK", "");
		$message = __("messages.CHATBOT_REPLY_NORMAL",  ["messengerLink" => $messengerLink]);
		if ($mute == false)  {

			//  If user has triggered CS reply for 10 times consecutively,
			//  we will stop to reply the users for rolling 24hr, except user
			//  resends the prefill trigger message.
			if ($member != null)  {

				$json = array();
				$muteTimeArray = array();
				$muteData = $member->mute_data;
				if ($muteData != null && strlen($muteData) > 0)  {

					$json = json_decode($muteData, true);
					if (isset($json["muteTimeArray"]))  {
						$muteTimeArray = $json["muteTimeArray"];
					}
				}
				$muteTimeArray[] = date("Y-m-d H:i:s");

				//  Remove records > 24 hours
				$newTimeArray = array();
				$past24Hours = time() - (60 * 60 * 24);
				foreach ($muteTimeArray as $timeString)  {

					$time = strtotime($timeString);
					if ($time >= $past24Hours)  {
						$newTimeArray[] = $timeString;
					}
				}

				//  Check if wrong message count exists limit
				$count = count($newTimeArray);
				$maxCSTriggered = intval(env("WHATSAPP_MAX_CS_TRIGGERED", "3"));
				if ($count >= $maxCSTriggered)  {

					$newTimeArray = array();
					$member->mute_until = date("Y-m-d H:i:s", strtotime("+24 hours"));
				}

				$json["muteTimeArray"] = $newTimeArray;
				$muteData = json_encode($json);
				$member->mute_data = $muteData;
				$member->save();
			}

			// $this->sendWhatsAppMessage($mobile, "customer-service", $message, 0, 0, null);
		}
		return false;
	}

	//----------------------------------------------------------------------------------------
	public function redirectUATMessage($mobile, $dictionary)  {

		//  Below is for UAT checking, don't change it
		$prefix = "UAT: ";
		$length = strlen($prefix);
		$incomingMessage = $dictionary["Body"];

		if ($_SERVER["SERVER_NAME"] != env("DOMAIN_PRODUCTION", ""))  {return null;}

		//----------------------------------------------------------------------------------------
		//  Running on production server
		$mode = "production";

		$chatbotState = ChatbotState::firstOrNew(array(
			"mobile" => $mobile,
			"channel" => "whatsapp",
			"branch" => "coupon",
		));
		if ($chatbotState != null)  {

			//  2023.03.15 Pacess
			//  Fix no reply issue for new member if incoming message is not a trigger
			if ($chatbotState->id == 0)  {
				$jsonDictionary = ["mode"=>"production"];
				$chatbotState->chatbot_data = json_encode($jsonDictionary);
				$chatbotState->save();
			}
			//  2023.03.15 End

			$jsonDictionary = json_decode($chatbotState->chatbot_data, true);
			if ($jsonDictionary != null)  {

				//  Check change mode
				$lowerIncomingMessage = strtolower($incomingMessage);
				if ($lowerIncomingMessage == "uat-mode")  {

					//  Change to UAT mode
					$jsonDictionary["mode"] = "uat";
					$chatbotState->chatbot_data = json_encode($jsonDictionary);
					$chatbotState->save();

					return "Mode changed, no need continue";
				}

				if ($lowerIncomingMessage == "live-mode")  {

					//  Change back to live mode
					$jsonDictionary["mode"] = "production";
					$chatbotState->chatbot_data = json_encode($jsonDictionary);
					$chatbotState->save();

					return "Mode changed, no need continue";
				}

				//  Not changing mode, get the mode value and see if redirect is need
				if (isset($jsonDictionary["mode"]) == true)  {
					$mode = $jsonDictionary["mode"];
				}
			}
		}

		//  If prefix is "UAT: " or in UAT mode
		$head = substr($incomingMessage, 0, $length);
		if ($head == $prefix || $mode == "uat")  {

			//  But this is UAT message, redirect to UAT
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, "https://" . env("DOMAIN_STAGING", "") . "/api/chatbot/twilio/message");
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $dictionary);
			$result = curl_exec($curl);
			curl_close($curl);

			//  If returning non-empty result, it will cause chatbot continue to run
			//  but we have passed to staging, should not do anything.
			return "Done on UAT, no need continue";
		}
		return null;
	}

	//----------------------------------------------------------------------------------------
	public static function contentAPIMenu($receiver, $message, $quickReplySID=null){

        $sender = env('WHATSAPP_SENDER', '');

        $sid = env('TWILIO_ACCOUNT_SID','');
        $token = env('TWILIO_AUTH_TOKEN','');
        $msgssid = env('TWILIO_MSG_SSID','');

        // Deflaut
        $menuSid = "HXb3baf80bc2b2ce8293abcb60dda37bbc";
		if (strlen($quickReplySID)>0){

			//  When sending quick reply message, $message contains dump data, need to clear here
			$menuSid = $quickReplySID;
			$message = "";
		}
		$senderEn = urlencode($sender);
		$receiverEn = urlencode($receiver);
		
		$content = 'To='.$receiverEn.'&MessagingServiceSid='.$msgssid.'&ContentSid='.$menuSid.'&From='.$senderEn;
		
		//$message are parameter in the template message
		if (strlen($message)>0){
			$messageTemp = $message;
			
			// for UAT format
			$messageTemp = str_replace('UAT: ', 'UAT:\n', $message );

			$str = str_replace(PHP_EOL, '\n', $messageTemp);
			$messageEn = urlencode($str);
			$content .= '&ContentVariables=%7B%221%22%3A%22'.$messageEn.'%22%7D'; 
		}
		
		$apiurl = "https://api.twilio.com/2010-04-01/Accounts/".$sid."/Messages";

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $apiurl,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $content,
			CURLOPT_HTTPHEADER => array(
			'Authorization: Basic QUM2NmI0Y2FkMDY5N2Y5Y2Y1NzA5MTQ2YWY2MmVhNWM2ZToyYzU4ODYwNTdmYjliNWMyOWE4M2ZiMmY2MTIzMGUyYw==',
			'Content-Type: application/x-www-form-urlencoded'
			),
		));

		$response = curl_exec($curl);
		curl_close($curl);
		return $response; //.$content;
	}

}
