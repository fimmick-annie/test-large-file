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
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Twilio\Rest\Client;

use App\Models\CampaignWhatsappMessageQueue;
use App\Models\CampaignStoreQuota;
use App\Models\WhatsappWebhook;
use App\Models\CampaignCoupon;
use App\Models\CampaignOffer;
use App\Models\Member;

//========================================================================================
class WhatsAppController extends Controller  {

	//----------------------------------------------------------------------------------------
	//  Mark: APIs
	//----------------------------------------------------------------------------------------
	public function webhookTwilioMessageComesInAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$messageID = "";
		$dictionary = $request->post();
		$json = json_encode($dictionary);

		//  "SmsMessageSid":"SM1153edcc9b93e0a4a1413da69a9366f7",
		//  "NumMedia":"0",
		//  "SmsSid":"SM1153edcc9b93e0a4a1413da69a9366f7",
		//  "SmsStatus":"received",
		//  "Body":"UAT: \u6211\u60f3\u9818\u53d6 Kinnso Pre-membership\u9ad4\u9a57\u88dd\uff08\u63db\u9818\u7de8\u78bc\uff1aitqOzi5w\uff09\u7684\u63db\u9818\u9023\u7d50\uff01",
		//  "To":"whatsapp:+85264500540",
		//  "NumSegments":"1",
		//  "MessageSid":"SM1153edcc9b93e0a4a1413da69a9366f7",
		//  "AccountSid":"ACa8c4e3793f543cc3b4d68b112171edf1",
		//  "From":"whatsapp:+85260382020",
		//  "ApiVersion":"2010-04-01"

		if (isset($dictionary["SmsSid"]))  {$messageID = $dictionary["SmsSid"];}
		WhatsappWebhook::addRecord("twilio", $messageID, "message", $json);

		if (isset($dictionary["ErrorUrl"]))  {

			$errorURL = $dictionary["ErrorUrl"];
			Log::debug("ERROR: ".$errorURL);
			$response["errorURL"] = $errorURL;
			return response()->json($response);
		}

		$mobile = (isset($dictionary["From"])) ? explode("whatsapp:", $dictionary["From"])[1] : null;
		if ($mobile == null)  {

			$response["status"] = -1;
			$response["message"] = "Mobile number not found...".json_encode($dictionary);
			return response()->json($response);
		}

		//----------------------------------------------------------------------------------------
		//  Redirect message to UAT site
		if (isset($dictionary["Body"]) == false)  {

			//  Not an incoming message
			$response["status"] = 0;
			$response["message"] = "Done";
			return response()->json($response);
		}

		$prefix = "UAT: ";
		$length = strlen($prefix);
		$domain = env("DOMAIN_PRODUCTION", "");
		$incomingMessage = $dictionary["Body"];
		$head = substr($incomingMessage, 0, $length);
		if ($_SERVER["SERVER_NAME"] == $domain)  {

			//  Running on production server
			Log::debug("Running on production...");
			if ($head == $prefix)  {

				//  But this is UAT message, redirect to UAT
				$domain = env("DOMAIN_STAGING", "");

				Log::debug("Forward to UAT...\n$json\n");
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, "https://".$domain."/whatsapp/twilio/message");
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $dictionary);
				$result = curl_exec($curl);
				curl_close($curl);

				Log::debug("Result: ".$result);
				$response["result"] = $result;
				return response()->json($response);
			}
		}

		$prefix = env("WHATSAPP_PREFIX", "");

		//----------------------------------------------------------------------------------------
		//  Extract referral code from message
		//
		//  Incoming sample: {
		//		"SmsMessageSid":"SMceb3078c83a4d9b5d94f3eeea3fc4cf8",
		//		"NumMedia":"0",
		//		"SmsSid":"SMceb3078c83a4d9b5d94f3eeea3fc4cf8",
		//		"SmsStatus":"received",
		//		"Body":"Nice",
		//		"To":"whatsapp:+85264500540",
		//		"NumSegments":"1",
		//		"MessageSid":"SMceb3078c83a4d9b5d94f3eeea3fc4cf8",
		//		"AccountSid":"ACa8c4e3793f543cc3b4d68b112171edf1",
		//		"From":"whatsapp:+85294129112",
		//		"ApiVersion":"2010-04-01"
		//  }
		$offerID = 0;
		$couponID = 0;
		$messengerLink = env("BRAND_MESSENGER_LINK", "");
		$message = __("messages.CHATBOT_REPLY_NORMAL", ["messengerLink" => $messengerLink]);
		$messageType = "Customer-service";

		$referralMessage = "";

		//  20201109 Pacess
		$expiryAt = date("Y-m-d H:i:s", strtotime("+3 days"));

		//  我想領取體驗裝（換領編碼：2nxQktc4）的換領連結！
		$startIndex = strpos($incomingMessage, "：");
		if ($startIndex === false)  {

			Log::debug("Unknown message '$incomingMessage'...");
// 			return response()->json($response);
		}  else  {

			$startIndex += strlen("：");
			$endIndex = strpos($incomingMessage, "）", $startIndex);
			$length = $endIndex-$startIndex;
			$referralCode = substr($incomingMessage, $startIndex, $length);

			//  Get coupon record with referral code and mobile
			$message = "";
			$coupon = CampaignCoupon::getCouponByReferralCode($mobile, $referralCode);
			if ($coupon == null)  {

				//  Coupon was not found
				$message = __("messages.CHATBOT_REPLY_COUPON_NOT_FOUND", ["messengerLink" => $messengerLink]);
				$messageType = "Invalid-coupon";
			}  else  {

				//----------------------------------------------------------------------------------------
				//  Coupon was found, we should reply base on offer settings
				$uniqueCode = $coupon->unique_code;
				$offerID = $coupon->offer_id;
				$couponID = $coupon->id;

				$mergedArray = json_decode($coupon->form_data, true);

				$storeQuotaID = $mergedArray["selectedRedemptionPeriodID"];
				$store = CampaignStoreQuota::getStoreWithQuotaID($offerID, $storeQuotaID);
				$redeemStartAt = $store->start_at;
				$redeemEndAt = $store->end_at;

				//  20201109 Pacess
				$expiryAt = $redeemEndAt;

				$offer = CampaignOffer::getOfferByID($offerID);
				if ($offer != null)  {

					$offerINI = parse_ini_file("./offers/".$offer->offer_name."/offer.ini", true);

					//  Show expiry message if already expired
					$couponExpiryTimestamp = strtotime($coupon->expiry_at);
					$currentTimestamp = time();
					if ($currentTimestamp >= $couponExpiryTimestamp)  {

						$message = $offerINI["offer_thankyou"]["expiry_notification_whatsapp_content"];
						$messageType = "Expiry-1";
					}  else

					if (isset($offerINI["offer_thankyou"]["notification_whatsapp_content"]))  {

						$message = $offerINI["offer_thankyou"]["notification_whatsapp_content"];
						$messageType = "Thank-you-1";

						if (isset($offerINI["offer_thankyou"]["referral_notification_whatsapp_content"]))  {
							$referralMessage = $offerINI["offer_thankyou"]["referral_notification_whatsapp_content"];
						}

						//  Prepare message for notification
						$port = ($_SERVER["SERVER_PORT"] == "80" || $_SERVER["SERVER_PORT"] == "443") ? "" : ":".$_SERVER["SERVER_PORT"];
						$scheme = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? "https" : "http";
						$baseURL = $scheme."://".$_SERVER["SERVER_NAME"].$port."/";

						$link = $baseURL.$uniqueCode."/";

						$offerCode = $offer->offer_code;
						$referralLink = $baseURL."offer/".$offerCode."?r=".$referralCode;

						$startAtDate = substr($redeemStartAt, 0, 10);
						$expiryAtDate = substr($redeemEndAt, 0, 10);

						//  Replace default keywords
						$searchArray = array(
							"\\n",
							"{{link}}", "{{referralLink}}", "{{referralCode}}",
							"{{startDate}}", "{{endDate}}",
						);
						$replaceArray = array(
							"\n",
							$link, $referralLink, $referralCode,
							$startAtDate, $expiryAtDate,
						);

						$message = str_replace($searchArray, $replaceArray, $message);
						$referralMessage = str_replace($searchArray, $replaceArray, $referralMessage);

						//  Replace custom keywords
						foreach ($mergedArray as $key => $value)  {
							$message = str_replace("{{".$key."}}", $value, $message);
							$referralMessage = str_replace("{{".$key."}}", $value, $referralMessage);
						}
					}
				}

				//----------------------------------------------------------------------------------------
				//  Cancel coupon 12:00 message
// 				$affectedRows = CampaignWhatsappMessageQueue::cancelMessages($coupon->id, "Coupon");
			}
		}

		//----------------------------------------------------------------------------------------
		//  Reply WhatsApp now
		$whatsAppEnabled = env('WHATSAPP_ENABLED', false);
		if ($whatsAppEnabled == true)  {

			$receiver = "whatsapp:".$mobile;
			$sid = env('WHATSAPP_SID', '');
			$token = env('WHATSAPP_TOKEN', '');
			$sender = env('WHATSAPP_SENDER', '');

			$now = date("Y-m-d H:i:s");

			$result = "";
			if (empty($message) == false)  {

				$message = $prefix.$message;

				$twilio = new Client($sid, $token);
				$result = $twilio->messages->create($receiver, [
					"from" => $sender,
					"body" => $message
				]);
				$status = "Success";

			}  else  {

				$status = "Success";
				$result = "[Twilio.Api.V2010.MessageInstance accountSid=ACa8c4e3793f543cc3b4d68b112171edf1 sid=SM940bd08464984df49cd8f246e683704a] Simulation";
			}
			$response["whatsapp"] = $result;

			//  Add to whatsapp log
			$whatsAppQueue = new CampaignWhatsappMessageQueue();
			$whatsAppQueue->created_by = basename(__FILE__);
			$whatsAppQueue->offer_id = $offerID;
			$whatsAppQueue->coupon_id = $couponID;
			$whatsAppQueue->mobile = $mobile;
			$whatsAppQueue->message = $message;
			$whatsAppQueue->message_type = $messageType;
			$whatsAppQueue->schedule_at = $now;
			$whatsAppQueue->expiry_at = $expiryAt;		//20201109 Pacess
			$whatsAppQueue->send_at = $now;
			$whatsAppQueue->vendor = "twilio";
			$whatsAppQueue->status = $status;
			$whatsAppQueue->response = $result;
			$whatsAppQueue->cost = "session";
			$whatsAppQueue->save();

			//----------------------------------------------------------------------------------------
			//  Next, handle referral message
			$result = "";
			if (empty($referralMessage) == false)  {

				//  Only save if referral message has content
				//  * Referral message is empty may due to coupon not found
				$referralMessage = $prefix.$referralMessage;

				$twilio = new Client($sid, $token);
				$result = $twilio->messages->create($receiver, [
					"from" => $sender,
					"body" => $referralMessage
				]);
				$status = "Success";

				$response["whatsapp"] = $result;

				//  Add to whatsapp log
				$whatsAppQueue = new CampaignWhatsappMessageQueue();
				$whatsAppQueue->created_by = basename(__FILE__);
				$whatsAppQueue->offer_id = $offerID;
				$whatsAppQueue->coupon_id = $couponID;
				$whatsAppQueue->mobile = $mobile;
				$whatsAppQueue->message = $referralMessage;
				$whatsAppQueue->message_type = "Referral-2";
				$whatsAppQueue->schedule_at = $now;
				$whatsAppQueue->expiry_at = $expiryAt;		//20201109 Pacess
				$whatsAppQueue->send_at = $now;
				$whatsAppQueue->vendor = "twilio";
				$whatsAppQueue->status = $status;
				$whatsAppQueue->response = $result;
				$whatsAppQueue->cost = "template";
				$whatsAppQueue->save();
			}
		}

		//----------------------------------------------------------------------------------------
		//  Output now
// 		$response["status"] = 0;
// 		$response["message"] = "Done";
// 		return response()->json($response);

		return "<Response>".
			"<Say>".$message."</Say>".
			"</Response>";
	}

	//----------------------------------------------------------------------------------------
	// 	Sent status: {
	// 		"SmsSid":"SM8f3fb5d4a7db457b87000b4142132bd2",
	// 		"SmsStatus":"sent",
	// 		"MessageStatus":"sent",
	// 		"ChannelToAddress":"+8529412XXXX",
	// 		"To":"whatsapp:+85294129112",
	// 		"ChannelPrefix":"whatsapp",
	// 		"MessageSid":"SM8f3fb5d4a7db457b87000b4142132bd2",
	// 		"AccountSid":"ACa8c4e3793f543cc3b4d68b112171edf1",
	// 		"From":"whatsapp:+85264500540",
	// 		"ApiVersion":"2010-04-01",
	// 		"ChannelInstallSid":"XE5786edb45e496c58de2b5caecc3a1819"
	// 	}
	//
	// 	Delivered status: {
	// 		"EventType":"DELIVERED",
	// 		"SmsSid":"SM8f3fb5d4a7db457b87000b4142132bd2",
	// 		"SmsStatus":"delivered",
	// 		"MessageStatus":"delivered",
	// 		"ChannelToAddress":"+8529412XXXX",
	// 		"To":"whatsapp:+85294129112",
	// 		"ChannelPrefix":"whatsapp",
	// 		"MessageSid":"SM8f3fb5d4a7db457b87000b4142132bd2",
	// 		"AccountSid":"ACa8c4e3793f543cc3b4d68b112171edf1",
	// 		"From":"whatsapp:+85264500540",
	// 		"ApiVersion":"2010-04-01",
	// 		"ChannelInstallSid":"XE5786edb45e496c58de2b5caecc3a1819"
	// 	}
	//
	// 	Read status: {
	// 		"EventType":"READ",
	// 		"SmsSid":"SM8f3fb5d4a7db457b87000b4142132bd2",
	// 		"SmsStatus":"read",
	// 		"MessageStatus":"read",
	// 		"ChannelToAddress":"+8529412XXXX",
	// 		"To":"whatsapp:+85294129112",
	// 		"ChannelPrefix":"whatsapp",
	// 		"MessageSid":"SM8f3fb5d4a7db457b87000b4142132bd2",
	// 		"AccountSid":"ACa8c4e3793f543cc3b4d68b112171edf1",
	// 		"From":"whatsapp:+85264500540",
	// 		"ApiVersion":"2010-04-01"
	// 	}
	public function webhookTwilioStatusCallbackAPI(Request $request)  {

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
// 		$message = "\n".date("Y-m-d H:i:s")." ".$json;
// 		file_put_contents("whatsapp_status.log", $message, FILE_APPEND);
		if (isset($dictionary["SmsSid"]))  {$messageID = $dictionary["SmsSid"];}
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
			case "sent":  {
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
			}  break;

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
			case "delivered":  {
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
			}  break;

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
			case "read":  {
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
			}  break;

			default:  {
			}  break;
		}

		//----------------------------------------------------------------------------------------
		//  Output now
		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
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
// 		$message = "\n".date("Y-m-d H:i:s")." ".$json;
// 		file_put_contents("whatsapp_fallback.log", $message, FILE_APPEND);
		if (isset($dictionary["SmsSid"]))  {$messageID = $dictionary["SmsSid"];}
		WhatsappWebhook::addRecord("twilio", $messageID, "fallback", $json);

		//  Output now
		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function processQueueAPI()  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -90,
			"message" => "WhatsApp is disabled...",
		);

		$whatsAppEnabled = env('WHATSAPP_ENABLED', false);
		if ($whatsAppEnabled != true)  {

			if (app()->runningInConsole())  {return $response;}
			return response()->json($response);
		}

		//----------------------------------------------------------------------------------------
		//  Scheduler runs this function every minute, but we only send SMS within 09:30-22:00
		$hour = date("H");
		if ($hour < 9 || $hour > 22)  {

			$response["status"] = -91;
			$response["Message"] = "Message will be sent at 09:30-22:00 only...";
			return response()->json($response);
		}

		//----------------------------------------------------------------------------------------
		//  Get session message
		$sessionMessageArray = CampaignWhatsappMessageQueue::getSessionJob();

		//  Get template message with priority
		$templateMessageArray = null;
		$date = date("Y-m-d H:i:s", strtotime("-24 hours"));
		$array = CampaignWhatsappMessageQueue::getSentTemplateMessagesAfterDate($date);
		$count = count($array);
		$quota = 1000-$count;
		if ($quota > 0)  {

			$templateMessageArray = CampaignWhatsappMessageQueue::getTemplateJob($quota);
		}

		$array = $sessionMessageArray->merge($templateMessageArray);

		//----------------------------------------------------------------------------------------
		$sid = env('WHATSAPP_SID', '');
		$token = env('WHATSAPP_TOKEN', '');
		$sender = env('WHATSAPP_SENDER', '');
		$twilio = new Client($sid, $token);
		foreach ($array as $whatsAppQueue)  {

			//  Set selected list to processing first to prevent running too long
			//  and next cronjob will select same records
			$whatsAppQueue->status = "Processing";
			$whatsAppQueue->save();
		}

		//----------------------------------------------------------------------------------------
		$count = 0;
		foreach ($array as $whatsAppQueue)  {

			$trimmedMobile = WhatsAppController::trimMobile($whatsAppQueue->mobile);

			//  Simple checking
			if (strlen($trimmedMobile) <= 10)  {
				$whatsAppQueue->status = "Skip";
				$whatsAppQueue->save();
				continue;
			}

			//  Find '+', if false == error
			if (strpos($trimmedMobile, "+") === false)  {
				$whatsAppQueue->status = "Skip";
				$whatsAppQueue->save();
				continue;
			}
			//  Find '+' start from index 1, find '.', if true / !false == error
			if (strpos($trimmedMobile, "+", 1) !== false || strpos($trimmedMobile, ".") !== false)  {
				$whatsAppQueue->status = "Skip";
				$whatsAppQueue->save();
				continue;
			}

			$receiver = "whatsapp:".$trimmedMobile;

			//  2021.08.30 Pacess
			//  Removed trim because Twilio templates somehow include linebreak at the end
// 			$message = trim($whatsAppQueue->message);
			$message = $whatsAppQueue->message;
			//  2021.08.30 End

			$media = $whatsAppQueue->media;
			$status = "Success";
			$messageID = "";

			$result = "Empty message, ignore";
			if (empty($message) == false || empty($media) == false)  {

				//  Cancel it if opt-out
				$optout = false;
				if ($whatsAppQueue->offer_id == 0 && $whatsAppQueue->coupon_id == 0)  {

					//  Assumed offerID=0, couponID=0 means message not related to offer,
					//  should be kind of generic or promotional message
					if ($whatsAppQueue->message_type != "opt-out")  {

						//  Get member opt-out status
						$mobile = $trimmedMobile;
						$optout = Member::isOptOut($mobile);
					}
				}

				if ($optout == false)  {

					//  Fix Windows-style
					$message = str_replace("\r\n", "\n", $message);

					$dictionary = [
						"from" => $sender,
						"body" => $message,
					];
					if (!empty($media))  {$dictionary["mediaUrl"] = [$media];}

					$result = $twilio->messages->create($receiver, $dictionary);

					//  [Twilio.Api.V2010.MessageInstance accountSid=AC04720401fa35445e2b51221457f30f21 sid=SMb8ee374ebfb14f82973c7336b0a90712]
					$matchArray = array();
					preg_match("/sid=([0-9a-zA-Z]+)\]/", $result, $matchArray);
					if (count($matchArray) > 1)  {$messageID = $matchArray[1];}
				}  else  {

					$result = "Opt-out, ignore";
				}
			}

			//  Add to whatsapp log
			$now = date("Y-m-d H:i:s");
			$whatsAppQueue->updated_by = basename(__FILE__);
			$whatsAppQueue->send_at = $now;
			$whatsAppQueue->vendor = "twilio";
			$whatsAppQueue->message_id = $messageID;
			$whatsAppQueue->status = $status;
			$whatsAppQueue->response = $result;
			$whatsAppQueue->save();

			$count++;
		}

		//  Output now
		$response["status"] = 0;
		$response["message"] = "$count processes completed";

		//  Save to log
		$filePath = storage_path("logs/")."cronjob_process_queue_".date("Ymd").".log";
		$string = json_encode($response)."\n";
		file_put_contents($filePath, $string, FILE_APPEND);

		if (app()->runningInConsole())  {return $response;}
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function fixMessageID()  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => 0,
			"message" => "Done",
		);

		$processCount = 0;
		$date = date("Y-m-d H:i:s", strtotime("-14 days"));
		$array = CampaignWhatsappMessageQueue::getSentRecordsAfterDate($date);
		foreach ($array as $whatsAppQueue)  {

			if (!empty($whatsAppQueue->message_id) && !empty($whatsAppQueue->vendor))  {continue;}

			$processCount++;

			$result = $whatsAppQueue->response;
			$messageID = CampaignWhatsappMessageQueue::getTwilioMessageID($result);

			$whatsAppQueue->updated_by = basename(__FILE__);
			$whatsAppQueue->message_id = $messageID;
			$whatsAppQueue->vendor = "twilio";
			$whatsAppQueue->save();
		}

		$response["processCount"] = $processCount;
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function fixMessageEventType()  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => 0,
			"message" => "Done",
		);

		$processCount = 0;
		$array = WhatsappWebhook::getRecordsWithEventType("DELIVERED");

		foreach ($array as $whatsWebhook)  {

			$content = $whatsWebhook->content;
			$json = json_decode($content);

			// 	"EventType":"READ",
			// 	"SmsSid":"SMef3804d9d6cf43d1946b1b46389e931f",
			// 	"SmsStatus":"read",
			// 	"MessageStatus":"read",
			// 	"ChannelToAddress":"+8529315XXXX",
			// 	"To":"whatsapp:+85293151993",
			// 	"ChannelPrefix":"whatsapp",
			// 	"MessageSid":"SMef3804d9d6cf43d1946b1b46389e931f",
			// 	"AccountSid":"ACa8c4e3793f543cc3b4d68b112171edf1",
			// 	"From":"whatsapp:+85264500540",
			// 	"ApiVersion":"2010-04-01"
			$messageID = $json->MessageSid;

			$messageArray = CampaignWhatsappMessageQueue::getRecordsWithMessageID($messageID);
			foreach ($messageArray as $whatsAppQueue)  {

				$processCount++;

				$whatsAppQueue->updated_by = basename(__FILE__);
				$whatsAppQueue->status = "Delivered";
				$whatsAppQueue->delivery_receipt = $content;
				$whatsAppQueue->save();
			}
		}

		$response["processCount"] = $processCount;
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	//  Mark: Helper functions
	//----------------------------------------------------------------------------------------
	public static function trimMobile($mobile)  {
		$trimmedMobile = preg_replace('/[^\\d\+]+/', '', $mobile);
		return $trimmedMobile;
	}

}
