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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Member;
use App\Models\ChatbotState;
use App\Models\CampaignForm;
use App\Models\CampaignOffer;
use App\Models\CampaignCoupon;
use App\Models\SegmentExchange;
use App\Models\CampaignQuickReply;
use App\Models\CampaignCouponPool;
use App\Models\CampaignStoreQuota;
use App\Models\CampaignMasterJourney;
use App\Models\CampaignCustomerJourney;
use App\Models\CampaignWhatsappMessageQueue;
use App\Models\PointTransaction;

//========================================================================================
class CouponChatbotController extends Controller  {

	//----------------------------------------------------------------------------------------
	//  chatbotData = Chatbot state record from database
	function fillDynamicContent($message, $coupon, $offerCode, $nullNode=null , $member = null)  {

		if ($message == null)  {return $message;}
// 		if ($coupon == null)  {return $message;}

		$port = ($_SERVER["SERVER_PORT"] == "80" || $_SERVER["SERVER_PORT"] == "443") ? "" : ":".$_SERVER["SERVER_PORT"];
		$scheme = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? "https" : "http";
		$baseURL = $scheme."://".$_SERVER["SERVER_NAME"].$port."/";

		$toWallet = "";
		$fromWallet = "";
		$openseaURL = "";
		$explorerURL = "";

		$uniqueCode = "";
		$referralCode = "";
		$startAtDate = "";
		$expiryAtDate = "";
		$formData = null;
		if ($coupon != null)  {

			//  Extract values from coupon object
			$uniqueCode = $coupon->unique_code;
			$referralCode = $coupon->referral_code;
			$startAtDate = $coupon->start_at;
			$expiryAtDate = $coupon->expiry_at;
			$formData = $coupon->form_data;
		}  else  {

			//  Extract values from null node
			if ($nullNode != null)  {

				if (isset($nullNode->node_data)){
					
					$nodeData = $nullNode->node_data;
					
					if (empty($nodeData) == false)  {

						$json = json_decode($nodeData, true);
						if (isset($json["uniqueCode"]))  {$uniqueCode = $json["uniqueCode"];}
						if (isset($json["referralCode"]))  {$referralCode = $json["referralCode"];}
						if (isset($json["startAtDate"]))  {$startAtDate = $json["startAtDate"];}
						if (isset($json["expiryAtDate"]))  {$expiryAtDate = $json["expiryAtDate"];}

						if (isset($json["toWallet"]))  {$toWallet = $json["toWallet"];}
						if (isset($json["fromWallet"]))  {$fromWallet = $json["fromWallet"];}
						if (isset($json["openseaURL"]))  {$openseaURL = $json["openseaURL"];}
						if (isset($json["explorerURL"]))  {$explorerURL = $json["explorerURL"];}
					}
				}
			}
		}

		//  Get other bundled information from coupon pool
		$parameterA = "";
		$parameterB = "";
		$parameterC = "";
		$couponPoolRecord = CampaignCouponPool::getWithCode($uniqueCode);
		if ($couponPoolRecord != null)  {

			$parameterA = $couponPoolRecord->parameter_a;
			$parameterB = $couponPoolRecord->parameter_b;
			$parameterC = $couponPoolRecord->parameter_c;
		}

		//  No time required
		$startAtDate = substr($startAtDate, 0, 10);
		$expiryAtDate = substr($expiryAtDate, 0, 10);

		$link = $baseURL.$uniqueCode."/";
		$referralLink = $baseURL."offer/".$offerCode."?r=".$referralCode;
		
		// for member referral in offer
		$memberRefCode = "";
		$memberRefLink = "";
		$offerAndMemberRefLink = "";
		if (is_null($member) == false)  {

			$memberRefCode = $member->referral_code;
			$memberRefLink = $baseURL."offer/".$offerCode."?m=".$memberRefCode;
			$offerAndMemberRefLink = $referralLink."&m=".$memberRefCode;
		}

		$uniqueCodeGroup = substr($uniqueCode, -1);

		//  Fill in dynamic content from database fields
		$searchArray = array(
			"{{link}}", "{{uniqueCode}}", "{{referralLink}}", "{{referralCode}}",
			"{{startDate}}", "{{endDate}}",
			"{{parameterA}}", "{{parameterB}}", "{{parameterC}}",
			"{{uniqueCodeGroup}}",
			"{{toWallet}}", "{{fromWallet}}", "{{openseaURL}}", "{{explorerURL}}",
			"{{uniqueCodeGroup}}", "{{memberRefCode}}",  "{{memberRefLink}}", "{{offerAndMemberRefLink}}"

		);
		$replaceArray = array(
			$link, $uniqueCode, $referralLink, $referralCode,
			$startAtDate, $expiryAtDate,
			$parameterA, $parameterB, $parameterC,
			$uniqueCodeGroup,
			$toWallet, $fromWallet, $openseaURL, $explorerURL,
			$uniqueCodeGroup, $memberRefCode, $memberRefLink, $offerAndMemberRefLink,
		);
		$output = str_replace($searchArray, $replaceArray, $message);

		//----------------------------------------------------------------------------------------
		//  Fill in dynamic content with form data
		if ($coupon != null)  {

			$dictionary = json_decode($coupon->form_data, true);
			if ($dictionary != null)  {
				foreach ($dictionary as $key=>$value)  {
					$search = "{{".$key."}}";
					$output = str_replace($search, $value, $output);
				}
			}

			//  Fill in dynamic content with referral data
			$dictionary = json_decode($coupon->referral_data, true);
			if ($dictionary != null)  {
				foreach ($dictionary as $key=>$value)  {
					$search = "{{".$key."}}";
					$output = str_replace($search, $value, $output);
				}
			}
		}
		return $output;
	}

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
			"canTerminate" => false,
			"canContinue" => true,
		);

		//  Shared variables
		$now = date("Y-m-d H:i:s");
		$currentOffer = null;
		$nullNode = null;
		$couponID = 0;
		$ini = null;
		
		//  User information
		$mobile = isset($userInfo["mobile"]) ? $userInfo["mobile"] : "";
		$email = isset($userInfo["email"]) ? $userInfo["email"] : "";
		$userID = isset($userInfo["userID"]) ? $userInfo["userID"] : "";

		if (isset($chatbotData["currentOfferID"]) == false)  {$offerID = 0;}
		else  {$offerID = intval($chatbotData["currentOfferID"]);}

		//----------------------------------------------------------------------------------------
		//  Tell Segment what users replied
		if (empty($incomingMessage) == false)  {

			$json = json_encode(array(
				"userId" => $mobile,
				"event" => "WhatsApp Message Details",
				"properties" => array(
					"incomingMessage" => $incomingMessage,
					"offerID" => $offerID,
				),
				"timestamp" => $now,
			));
			$this->callSegmentTrack($json);
		}

		//----------------------------------------------------------------------------------------
		//  No matter any offer is running, check if incoming message is a trigger message
		$today = date("Y-m-d");
		$fromDate = date("Y-m-d", strtotime("-7 days"));
		$offerArray = CampaignOffer::getList($fromDate, $today);
		foreach ($offerArray as $offer)  {
			
			//  Get trigger message
			$offerCode = $offer->offer_code;

			$filePath = "./offers/".$offer->offer_name."/offer.ini";
			if (file_exists($filePath) == false)  {continue;}

			$ini = parse_ini_file($filePath, true);
			if (isset($ini["settings"]["whatsapp_trigger_message"]) == false)  {continue;}

			$triggerMessage = $ini["settings"]["whatsapp_trigger_message"];
			if ($triggerMessage == null)  {continue;}

			//  2021.02.02 Pacess
			//  Requested by CRM & Yvonne, support case in-sensitive
			$lowerIncomingMessage = strtolower($incomingMessage);
			$lowerTriggerMessage = strtolower($triggerMessage);
			$index = strpos($lowerIncomingMessage, $lowerTriggerMessage);
			if ($index === false)  {continue;}

			//  Trigger message matches!
			$currentOffer = $offer;
			$offerID = $offer->id;
			$chatbotData["currentOfferID"] = $offerID;
			$chatbotData["trigger"] = $incomingMessage;

			//  Check if offer expired
			$offerEndTimestamp = strtotime($offer->end_at);
			$currentTimestamp = strtotime($today);
			if ($offerEndTimestamp < $currentTimestamp)  {

				//  Offer expired!
				$message = $ini["coupon_expired"]["whatsapp_expiry_message"];
				$message = str_replace("\\n", "\n", $message);

				//  Offer expired, no need to keep state in this offer
				$chatbotData["currentOfferID"] = 0;

				$outputDictionary["message"] = $message;
				$outputDictionary["messageType"] = "whatsapp_expiry_message";
				$outputDictionary["chatbotData"] = $chatbotData;
				$outputDictionary["canContinue"] = false;

				//  Values for debug
				$outputDictionary["offerID"] = $offerID;
				return $outputDictionary;
			}

			//  Copy master journey to customer journey
			$nodeArray = CampaignMasterJourney::getNodes($offerID);
			foreach ($nodeArray as $node)  {

				$journeyNode = CampaignCustomerJourney::firstOrNew(array(
					"offer_id" => $offerID,
					"node_name" => $node->node_name,
					"type" => $node->type,
					"mobile" => $mobile,
				));
				$journeyNode->ordering = $node->ordering;
				$journeyNode->node_settings = $node->node_settings;
				$journeyNode->mobile = $mobile;
				$journeyNode->save();
			}

			//  TODO: Can optimize save null node in one go?

			//  Save trigger message to null node
			//  *** Null node must add last to prevent ordering problem
			$nullNode = CampaignCustomerJourney::insertAtNullNode($userInfo, $offerID, "triggerMessage", $incomingMessage);

			//----------------------------------------------------------------------------------------
			//  Trigger message may include referral code and lead source
			//  Extract referrer code
			//  Ref:a1b2c3

			// $matchArray = array();
			// preg_match("/Ref:([0-9a-zA-Z]+)/", $incomingMessage, $matchArray);
			// $count = count($matchArray);
			// if ($count > 1)  {

			// 	//  Referral process is not completed yet, it is base on issue coupon
			// 	//  Save referrer code for use at that moment
			// 	$referrerCode = $matchArray[1];
			// 	$nullNode = CampaignCustomerJourney::insertAtNullNode($userInfo, $offerID, "referrerCode", $referrerCode);
			// }

			//----------------------------------------------------------------------------------------
			//  Trigger message may include form code
			$matchArray = array();
			preg_match("/Fid:([0-9a-zA-Z]+)/", $incomingMessage, $matchArray);
			$count = count($matchArray);
			if ($count > 1)  {

				//  Save form code for use later
				$formCode = $matchArray[1];
				$nullNode = CampaignCustomerJourney::insertAtNullNode($userInfo, $offerID, "formCode", $formCode);
			}

			//----------------------------------------------------------------------------------------
			//  Trigger message may include Segment AID token
			//  我要八折優惠！ AID:Zu7YiF
			//  我想領取免費「MANUKA發酵綜合蔬果飲」！(Reg no.: xxx)
			$aid = null;
			$aidToken = null;
			$matchArray = array();
// 			preg_match("/AID:[0-9a-zA-Z]+/", $incomingMessage, $matchArray);
			preg_match("/Reg no.:[0-9a-zA-Z]+/", $incomingMessage, $matchArray);
			$count = count($matchArray);
			if ($count > 0)  {
			
				//  Save AID token
				$string = $matchArray[0];
// 				$aidToken = substr($string, 4);
				$aidToken = substr($string, strlen("Reg no.:"));
				$nullNode = CampaignCustomerJourney::insertAtNullNode($userInfo, $offerID, "aidToken", $aidToken);

				//  Get AID with token
				$record = SegmentExchange::getRecordWithToken($aidToken);
				if (empty($record) == false)  {
					
					$aid = $record->aid;
					$referrerCode = $record->referrer_code;
					if ($referrerCode != '')  {
						$nullNode = CampaignCustomerJourney::insertAtNullNode($userInfo, $offerID, "referrerCode", $referrerCode);
					}  else  {
						$nullNode = CampaignCustomerJourney::insertAtNullNode($userInfo, $offerID, "aid", $aid);
					}

					// 2023.03.13 Kay --- Add for Node 335
					$memberReferralCode = $record->member_referral_code;
					if ($memberReferralCode != '')  {
						$nullNode = CampaignCustomerJourney::insertAtNullNode($userInfo, $offerID, "memberReferralCode", $memberReferralCode);
					}  

					//  Somehow AID also include form data
					$formCode = $record->form_code;
					if (empty($formCode) == false)  {

						$nullNode = CampaignCustomerJourney::insertAtNullNode($userInfo, $offerID, "formCode", $formCode);
					}
					
					//  Kay 2022.08.16 for PMS#182 =====================================================================
					//  Kay 2022.09.16 Renew definition of new member: no mobile in record or no point now and before ===============
					//  TODO: Also check if member referrer code, add point if exists
					if (!is_null($record->member_referral_code)){
						$referrerMember = Member::where('referral_code', $record->member_referral_code)->first();
							
						// member cannot make self-referral 
						if (!is_null($referrerMember) && $referrerMember->mobile != $mobile){
							
							// $referree = Member::getMemberByMobile($mobile);
							// $existedOffer = json_decode($referree->offer_involved, true);
							// checking whether any offer taken before and any referrer before, if all none, can get the referal point
							// if (($existedOffer == null || empty(array_values($existedOffer)) == true ) && $referree->referrer_id == 0){
							if (Member::checkIfNewMember($mobile)==true){

								$referree = Member::getMemberByMobile($mobile);
								
								// add referral & offer pt to referree
								if (!is_null($referree)){
									$referree->addReferreePointByMemberReferral(config('points.success_referree')); 
								}
								
								// add referral pt to referrer 
								$referrerMember->addReferralPoinByMemberReferral(config('points.success_referral'));
							
								// set the relation befween referrer and referree by setting referrer_id in member record
								$referree->referrer_id = $referrerMember->id;
								$referree->save();
							}

						}else{
							$record->member_referral_code = null; //Invalid: self-referral
						}
					}

					//  Save back mobile number for debug
					$record->mobile = $mobile;
					$record->save();
				}
			}

			//----------------------------------------------------------------------------------------
			//  Call segment
			$array = array(
				"userId" => $mobile,
				"traits" => array(
					"mobile" => $mobile,
					"phone" => $mobile,
				),
				"timestamp" => $now,
			);
			if (empty($aid) == false)  {$array["anonymousId"] = $aid;}
			
			//  Machine learning label
			if ($offer->ml_labels != null)  {

				$labelArray = explode(",", $offer->ml_labels);
				$labelArray = array_map("trim", $labelArray);
				$array["traits"]["description"] = $labelArray;

				//  2021.11.25 Pacess
				//  Test if new key and new structure can prevent traits being overwritten
//TakeOut				$array["traits"]["labels"] = array($offerID=>$labelArray);
				$array["traits"]["offer_".$offerID."_labels"] = $labelArray;
				//  2021.11.25 End
			}

			$data = json_encode($array);
// Log::debug("Call segment: ".$data);
			$this->callSegmentIdentify($data);

			//----------------------------------------------------------------------------------------
			//  Reset incoming message to prevent problem to answer node
			$incomingMessage = "";

			//  Update involved offer record in member
			Member::involvedOffer($mobile, $offerID);

			//  Offer match, no need to loop other offers
			break;
		}
		$outputDictionary["chatbotData"] = $chatbotData;

		//----------------------------------------------------------------------------------------
		//  No specific offer
		$outputDictionary["offerID"] = $offerID;
		if ($offerID == 0)  {

			//  Nothing can do, process next branch
			$outputDictionary["canTerminate"] = true;
			return $outputDictionary;
		}
		
		//----------------------------------------------------------------------------------------
		//  OK, has an offer, also need to check if offer expired
// Log::debug("offerID_235=".$offerID);
		if ($currentOffer == null)  {$currentOffer = CampaignOffer::getOfferByID($offerID);}
		if ($currentOffer == null)  {

			//  Still null, unable to continue
			$outputDictionary["canContinue"] = false;
			$outputDictionary["canTerminate"] = true;
			
			return $outputDictionary;
		}

		$offerEndTimestamp = strtotime($currentOffer->end_at);
		$currentTimestamp = strtotime($today);
		if ($offerEndTimestamp < $currentTimestamp)  {

			//  Offer expired!
			$message = "";
			if (isset($ini["coupon_expired"]["whatsapp_expiry_message"]))  {

				$message = $ini["coupon_expired"]["whatsapp_expiry_message"];
				$message = str_replace("\\n", "\n", $message);
			}

			//  Offer expired, no need to keep state in this offer
			$chatbotData["currentOfferID"] = 0;

			$outputDictionary["message"] = $message;
			$outputDictionary["messageType"] = "whatsapp_expiry_message";
			$outputDictionary["chatbotData"] = $chatbotData;
			$outputDictionary["canContinue"] = false;
			return $outputDictionary;
		}
		
		//----------------------------------------------------------------------------------------
		//  Execute node
		$formData = null;
		$nodeName = null;
		$dataKey = "offer-".$offerID;
// Log::debug("Offer key: $dataKey");
// Log::debug("Chatbot data: ".json_encode($chatbotData));

		//  Get current node name of current offer from chatbot state
		if (isset($chatbotData[$dataKey]))  {
// Log::debug("Chatbot data has offer key");
			if (isset($chatbotData[$dataKey]["currentNode"]))  {
				$nodeName = $chatbotData[$dataKey]["currentNode"];
// Log::debug("Chatbot data has current node: $nodeName");
			}
		}
// Log::debug("Chatbot data node name: $nodeName");

		//  nodeName:
		//    null = State not found, offer should be just triggered
		//    empty = Offer journey already ended
		//    others = Node name
		//
		//  Note: Must use !== and === to prevent null and empty are the same
		if ($nodeName !== null && $nodeName === "")  {

			//  Journey has ended, show finish message
// Log::debug("Journey has ended");
			$chatbotData["currentOfferID"] = 0;
			if ($ini == null)  {

				//  Load .ini for finish message
				$offer = CampaignOffer::getOfferByID($offerID);
				$ini = parse_ini_file("./offers/".$currentOffer->offer_name."/offer.ini", true);
			}

			//  Show finish message if user want to trigger a finished journey
			$outputDictionary["chatbotData"] = $chatbotData;
			if ($ini != null)  {
				if (isset($ini["settings"]["journey_finish_message"]))  {

					$message = $ini["settings"]["journey_finish_message"];
					$message = str_replace("\\n", "\n", $message);
					if (empty($message) == false)  {

						$outputDictionary["message"] = $message;
						$outputDictionary["messageType"] = "journey_finish_message";
						$outputDictionary["canContinue"] = false;
						return $outputDictionary;
					}
				}
			}
			
			//  Journey ended but no finish message
			$outputDictionary["canTerminate"] = true;
			return $outputDictionary;
		}
		
		//  Node name must be either null or have name, must not a finish node
		//  *** getNode support null node name, it will return the first non-completed
		//      node base on ordering
		$journeyNode = CampaignCustomerJourney::getNode($userInfo, $offerID, $nodeName);
		if ($journeyNode == null)  {

			//  Have node name but no record in database
// Log::debug("Journey node is null");
			$outputDictionary["canTerminate"] = true;
			return $outputDictionary;
		}
		
		//  This is need because above $nodeName may null
		$offerCode = $currentOffer->offer_code;
		$nodeName = $journeyNode->node_name;
// Log::debug("Node name: $nodeName");

		//  2022.08.30 Pacess
		if ($nullNode == null)  {
			$nullNode = CampaignCustomerJourney::getNodeWithName($offerID, "null-node");
		}
		//  2022.08.30 End

		//----------------------------------------------------------------------------------------
		//  Call Segment
		$this->callSegmentTrack(json_encode(array(
			"userId" => $mobile,
			"event" => "WhatsApp Chatbot",
			"properties" => array(
				"nodeName" => $nodeName,
				"nodeType" => $journeyNode->type,

				//  2021.07.15 Pacess
				//  Requested by Karman Lo
				"offerCode" => $offerCode,
				"offerName" => $currentOffer->offer_name,
				"offerTitle" => $currentOffer->offer_title,
				"offerSubtitle" => $currentOffer->offer_subtitle,
				//  2021.07.15 End
			),
			"timestamp" => $now,
		)));

		//----------------------------------------------------------------------------------------
		//  Seems everything alright
		$nextNodeName = "";
		$nodeSettings = json_decode($journeyNode->node_settings, true);

		switch ($journeyNode->type)  {

			//----------------------------------------------------------------------------------------
			//  Message node
			case 100:  {
				$journeyNode->triggered_at = $now;
				$offerCode = $currentOffer->offer_code;
				$memberInvolid = Member::getMemberByMobile($mobile);
				//  Get coupon record for dynamic content
				$coupon = null;
				$array = CampaignCoupon::getCouponByOfferIDs($mobile, $offerID);
				$count = count($array);
				if ($count > 0)  {$coupon = $array[0];}

				$message = (isset($nodeSettings["message"])) ? $nodeSettings["message"] : null;
				if (empty($message) == false)  {$message = $this->fillDynamicContent($message, $coupon, $offerCode, $nullNode, $memberInvolid);}

				//  Image handling
				$media = (isset($nodeSettings["media"])) ? $nodeSettings["media"] : null;
				if (empty($media) == false)  {$media = $this->fillDynamicContent($media, $coupon, $offerCode, $nullNode, $memberInvolid);}

				//  Schedule handling
				if (isset($nodeSettings["schedule"]))  {

					//  Scheduled message
					$timestamp = strtotime($nodeSettings["schedule"]);
					$scheduledDate = date("Y-m-d H:i:s", $timestamp);
					$expiryDate = date("Y-m-d H:i:s", $timestamp+(60*60*24*3));

					//  Save scheduled message to message queue
					$whatsAppQueue = new CampaignWhatsappMessageQueue();
					$whatsAppQueue->created_by = basename(__FILE__);
					$whatsAppQueue->offer_id = $offerID;
					$whatsAppQueue->coupon_id = $couponID;
					$whatsAppQueue->mobile = $mobile;
					$whatsAppQueue->message = $message;
					$whatsAppQueue->media = $media;
					$whatsAppQueue->message_type = $nodeName;
					$whatsAppQueue->schedule_at = $scheduledDate;
					$whatsAppQueue->expiry_at = $expiryDate;
					$whatsAppQueue->vendor = "twilio";
					$whatsAppQueue->cost = "template";
					$whatsAppQueue->save();

					$journeyNode->node_data = json_encode(array(
						"scheduledDate" => $scheduledDate
					));

					//  No need return message to chatbot as this is a scheduled message

				}  else  {

					//  Instant message, let chatbot to send immediately
					$outputDictionary["media"] = $media;
					$outputDictionary["message"] = $message;
					$outputDictionary["messageType"] = $nodeName;
				}

				$journeyNode->completed_at = $now;
				$journeyNode->save();

				//  If no next node then clear current node
				if (isset($nodeSettings["nextNode"]) == false)  {$nextNodeName = "";}
				else  {$nextNodeName = $nodeSettings["nextNode"];}
			}  break;

			//----------------------------------------------------------------------------------------
			//  Question nodes
			case 200:  {
				if ($journeyNode->triggered_at != null)  {

					$optionDictionary = $nodeSettings["options"];
					$lowerCaseOptionDictionary = array_change_key_case($optionDictionary, CASE_LOWER);
					$lowerCaseIncomingMessage = strtolower($incomingMessage);
					if (isset($lowerCaseOptionDictionary[$lowerCaseIncomingMessage]) || isset($optionDictionary["any"]))  {

						//  User answered provided answer
						$journeyNode->node_data = json_encode(array("answer" => $incomingMessage));
						$journeyNode->completed_at = $now;
						$journeyNode->save();

						//  Save user input to null node
						$nodeNameCamelCase = lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $nodeName))));
						$nullNode = CampaignCustomerJourney::insertAtNullNode($userInfo, $offerID, $nodeNameCamelCase, $incomingMessage);

						$chatbotData[$dataKey][$nodeName] = $incomingMessage;
						if (isset($lowerCaseOptionDictionary[$lowerCaseIncomingMessage]))  {

							//  Multiple choice reply
							$nextNodeName = $lowerCaseOptionDictionary[$lowerCaseIncomingMessage];
							$incomingMessage = "";
							if (isset($chatbotData[$dataKey]["wrongInputCount"])){$chatbotData[$dataKey]["wrongInputCount"] = 0;}

						}  else  {

							//  Free text reply
							if (isset($optionDictionary["any"]))  {
								$nextNodeName = $optionDictionary["any"];
								$incomingMessage = "";
								if (isset($chatbotData[$dataKey]["wrongInputCount"])){$chatbotData[$dataKey]["wrongInputCount"] = 0;}
							}
						}

						//  Save user input to null node
						$nodeNameCamelCase = lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $nodeName))));
						$nullNode = CampaignCustomerJourney::insertAtNullNode($userInfo, $offerID, $nodeNameCamelCase, $incomingMessage);
						break;
					}
				}

				//----------------------------------------------------------------------------------------
				//  User reply invalid option or run first time, send question again
				$nextNodeName = $nodeName;
// Log::debug("Next node name 2: $nextNodeName");

				$message = $nodeSettings["message"];
				$outputDictionary["message"] = $message;
				$outputDictionary["messageType"] = $nodeName;

				$outputDictionary["canContinue"] = false;
				$outputDictionary["canTerminate"] = true;

				//  Support media
				$media = (isset($nodeSettings["media"])) ? $nodeSettings["media"] : null;
				$outputDictionary["media"] = $media;

				if ($journeyNode->triggered_at == null)  {
					$journeyNode->triggered_at = $now;
					$journeyNode->save();
				}

				//  Schedule handling
				if (isset($nodeSettings["schedule"]))  {

					//  Scheduled message
					$timestamp = strtotime($nodeSettings["schedule"]);
					$scheduledDate = date("Y-m-d H:i:s", $timestamp);
					$expiryDate = date("Y-m-d H:i:s", $timestamp+(60*60*24*3));

					//  Save scheduled message to message queue
					$whatsAppQueue = new CampaignWhatsappMessageQueue();
					$whatsAppQueue->created_by = basename(__FILE__);
					$whatsAppQueue->offer_id = $offerID;
					$whatsAppQueue->coupon_id = $couponID;
					$whatsAppQueue->mobile = $mobile;
					$whatsAppQueue->message = $message;
					$whatsAppQueue->media = $media;
					$whatsAppQueue->message_type = $nodeName;
					$whatsAppQueue->schedule_at = $scheduledDate;
					$whatsAppQueue->expiry_at = $expiryDate;
					$whatsAppQueue->vendor = "twilio";
					$whatsAppQueue->cost = "template";
					$whatsAppQueue->save();

					$journeyNode->node_data = json_encode(array(
						"scheduledDate" => $scheduledDate
					));

					//  No need return message to chatbot as this is a scheduled message
					$outputDictionary["message"] = "";
					$outputDictionary["media"] = "";
				}
				
				//----------------------------------------------------------------------------------------
				//  If wrong input more than #, then exit journey
				if (isset($chatbotData[$dataKey]["wrongInputCount"]) == false)  {
					$chatbotData[$dataKey]["wrongInputCount"] = 0;
				}

				//  Empty incoming message if it is trigger message
				if (empty($incomingMessage) == true)  {
					$chatbotData[$dataKey]["wrongInputCount"] = 0;
				}  else  {

					//  Have enter something
					$chatbotData[$dataKey]["wrongInputCount"]++;
					if ($chatbotData[$dataKey]["wrongInputCount"] >= 2)  {

						//  Too much wrong input, exit offer journey loop, continue branch
						$chatbotData["currentOfferID"] = 0;

						$outputDictionary["media"] = "";
						$outputDictionary["message"] = "";
						$outputDictionary["messageType"] = "";
						$outputDictionary["canContinue"] = true;
					}
				}
			}  break;

			//----------------------------------------------------------------------------------------
			//  Quick-Reply nodes --- Kay 2023.02.27
			case 250:  {
				if ($journeyNode->triggered_at != null)  {

					$lowerCaseIncomingMessage = strtolower($incomingMessage);
					$optionNameDictionary = $nodeSettings["optionsName"];
					$optionDictionary = $nodeSettings["options"];
					foreach ($optionNameDictionary as $key => $value)  {

						$lowerCaseOptionValue = strtolower($value);
						if ($lowerCaseOptionValue != $lowerCaseIncomingMessage)  {continue;}

						//  User answered provided answer
						$journeyNode->node_data = json_encode(array($key => $incomingMessage));
						$journeyNode->completed_at = $now;
						$journeyNode->save();

						//  Save user input to null node
						$nodeNameCamelCase = lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $nodeName))));
						$nullNode = CampaignCustomerJourney::insertAtNullNode($userInfo, $offerID, $nodeNameCamelCase, $incomingMessage);

						$chatbotData[$dataKey][$nodeName] = $incomingMessage;

						//  Input message matches option text
						$nextNodeName = $optionDictionary[$key];
						$incomingMessage = "";
						if (isset($chatbotData[$dataKey]["wrongInputCount"]))  {$chatbotData[$dataKey]["wrongInputCount"] = 0;}

						//  Clear parent's incoming message
						$outputDictionary["incomingMessage"] = "";

						//  Break 2 is very important
						break 2;
					}
				}

				//----------------------------------------------------------------------------------------
				//  User reply invalid option or run first time, send question again
				$nextNodeName = $nodeName;

				//  templateID is required
				if (isset($nodeSettings["templateID"]))  {

					$templateID = intval($nodeSettings["templateID"]);
					$quickReply = CampaignQuickReply::getRecordByID($templateID);
					if ($quickReply != null)  {

						$quickReplySID = $quickReply->sid;
						$outputDictionary["quickReplySID"] = $quickReplySID;

						//  Construct message for simulator by template ID
						$message = $quickReply->text."\n\n";

						$replyJSON = $quickReply->reply;
						$replyDictionary = json_decode($replyJSON, true);
						if ($replyDictionary != null && isset($replyDictionary["actions"]))  {

							$actionsArray = $replyDictionary["actions"];
							foreach ($actionsArray as $actionDictionary)  {

								if (isset($actionDictionary["title"]))  {

									$title = $actionDictionary["title"];
									$message .= $title."\n";
								}
							}
						}
					}

					$outputDictionary["message"] = $message;

				}  else  {

					//  TODO: It should be error, need handle?
				}

				if (isset($nodeSettings["parameter"])){
					$parameter = $nodeSettings["parameter"];
					$outputDictionary["parameter"] = $parameter;
				}

				$outputDictionary["messageType"] = $nodeName;

				$outputDictionary["canContinue"] = false;
				$outputDictionary["canTerminate"] = true;

				//  Support media
				// $media = (isset($nodeSettings["media"])) ? $nodeSettings["media"] : null;
				// $outputDictionary["media"] = $media;

				if ($journeyNode->triggered_at == null)  {
					$journeyNode->triggered_at = $now;
					$journeyNode->save();
				}

				//  Schedule handling
				if (isset($nodeSettings["schedule"]))  {

					//  Scheduled message
					$timestamp = strtotime($nodeSettings["schedule"]);
					$scheduledDate = date("Y-m-d H:i:s", $timestamp);
					$expiryDate = date("Y-m-d H:i:s", $timestamp+(60*60*24*3));

					//  Save scheduled message to message queue
					$whatsAppQueue = new CampaignWhatsappMessageQueue();
					$whatsAppQueue->created_by = basename(__FILE__);
					$whatsAppQueue->offer_id = $offerID;
					$whatsAppQueue->coupon_id = $couponID;
					$whatsAppQueue->mobile = $mobile;
					$whatsAppQueue->message = $message;
					$whatsAppQueue->media = $media;
					$whatsAppQueue->message_type = $nodeName;
					$whatsAppQueue->schedule_at = $scheduledDate;
					$whatsAppQueue->expiry_at = $expiryDate;
					$whatsAppQueue->vendor = "twilio";
					$whatsAppQueue->cost = "template";
					$whatsAppQueue->save();

					$journeyNode->node_data = json_encode(array(
						"scheduledDate" => $scheduledDate
					));

					//  No need return message to chatbot as this is a scheduled message
					$outputDictionary["message"] = "";
					$outputDictionary["media"] = "";
				}
				
				//----------------------------------------------------------------------------------------
				//  If wrong input more than #, then exit journey
				if (isset($chatbotData[$dataKey]["wrongInputCount"]) == false)  {
					$chatbotData[$dataKey]["wrongInputCount"] = 0;
				}

				//  Empty incoming message if it is trigger message
				if (empty($incomingMessage) == true)  {
					$chatbotData[$dataKey]["wrongInputCount"] = 0;
				}  else  {

					//  Have enter something
					$chatbotData[$dataKey]["wrongInputCount"]++;
					if ($chatbotData[$dataKey]["wrongInputCount"] >= 2)  {

						//  Too much wrong input, exit offer journey loop, continue branch
						$chatbotData["currentOfferID"] = 0;

						$outputDictionary["media"] = "";
						$outputDictionary["message"] = "";
						$outputDictionary["messageType"] = "";
						$outputDictionary["canContinue"] = true;
					}
				}
			}  break;

			//----------------------------------------------------------------------------------------
			//  Process nodes: Issue coupon node
			case 300:  {
				$journeyNode->triggered_at = $now;
// 				$offerCode = $currentOffer->offer_code;

				$selectedRedemptionPeriodID = isset($nodeSettings["selectedRedemptionPeriodID"]) ? $nodeSettings["selectedRedemptionPeriodID"] : 0;
				$selectedRedemptionStore = isset($nodeSettings["selectedRedemptionStore"]) ? $nodeSettings["selectedRedemptionStore"] : "";

				//  If store is not specify, try getting from form record
				if (strtolower($selectedRedemptionStore) == "use-form")  {

					$formData = CampaignCustomerJourney::getNullNodeByKey($userInfo, $offerID, "formData");
					if ($formData != null)  {

						$formData = json_decode($formData["form_data"], true);
						$selectedRedemptionStore = $formData["selectedRedemptionStore"];
						$selectedRedemptionPeriodID = $formData["selectedRedemptionPeriodID"];
					}
				}

				$referrerCode = CampaignCustomerJourney::getNullNodeByKey($userInfo, $offerID, "referrerCode");

				//  Issue coupon
				$dictionary = array(
					"email" => $email,
					"offer" => $currentOffer,
					"mobile" => $mobile,
					"offerCode" => $offerCode,
					"referrerCode" => $referrerCode,
					"selectedChannel" => "whatsapp",
					"confirmationMethod" => "whatsapp",
					"selectedRedemptionPeriodID" => $selectedRedemptionPeriodID,
					"selectedRedemptionStore" => $selectedRedemptionStore,
				);

				//  Also include form data if available
				if ($formData != null)  {
					$dictionary = array_merge($dictionary, $formData);
				}

				$resultDictionary = app('App\\Http\\Controllers\\CampaignOfferController')->registerAndIssueCoupon($dictionary);
				if ($resultDictionary == null)  {

					//  Unknown error
					$outputDictionary["canContinue"] = false;
					$outputDictionary["canTerminate"] = true;
					break;
				}

				//  registerAndIssueCoupon already return referral code
				$resultDictionary["offerCode"] = $offerCode;
				$resultDictionary["referrerCode"] = $referrerCode;

				//  Save coupon record in node data and chatbot data
				$journeyNode->node_data = json_encode($resultDictionary);
				$journeyNode->completed_at = $now;
				$journeyNode->save();

				//  Put machine learning labels
				if ($currentOffer->ml_labels != null)  {

					$labelArray = explode(",", $currentOffer->ml_labels);
					$labelArray = array_map("trim", $labelArray);
					$resultDictionary["couponDescription"] = $labelArray;
				}

				//  Only when debug flag is on
				if (array_key_exists("uniqueCode", $resultDictionary))  {
					$nullNode = CampaignCustomerJourney::insertAtNullNode($userInfo, $offerID, "uniqueCode", $resultDictionary["uniqueCode"]);
				}
				if (array_key_exists("couponCodeURL", $resultDictionary))  {
					$nullNode = CampaignCustomerJourney::insertAtNullNode($userInfo, $offerID, "couponCodeURL", $resultDictionary["couponCodeURL"]);
				}
				if (array_key_exists("couponCodeFilename", $resultDictionary))  {
					$nullNode = CampaignCustomerJourney::insertAtNullNode($userInfo, $offerID, "couponCodeFilename", $resultDictionary["couponCodeFilename"]);
				}

				//  Handle what's next
				$status = $resultDictionary["status"];
				switch ($status)  {

					case 0:  {
						//  If no next node then clear current node
						if (isset($nodeSettings["nextNode"]) == false)  {$nextNodeName = "";}
						else  {$nextNodeName = $nodeSettings["nextNode"];}

						// 	"userId": "019mr8mf4r",
						// 	"event": "Item Purchased",
						// 	"properties": {
						// 		"name": "Leap to Conclusions Mat",
						// 		"revenue": 14.99
						// 	},
						// 	"context": {
						// 		"ip": "24.5.68.47"
						// 	},
						// 	"timestamp": "2012-12-02T00:30:12.984Z"
						$this->callSegmentTrack(json_encode(array(
							"userId" => $mobile,
							"event" => "Issue coupon",
							"properties" => $resultDictionary,
							"timestamp" => $now,
						)));
					}  break;

					case -5:  {
						//   -5 = Offer already ended...
						$expiryNode = "";
						if (isset($nodeSettings["expiryNode"]) == true)  {
							$expiryNode = $nodeSettings["expiryNode"];
						}

						if (empty($expiryNode) == false)  {
							$nextNodeName = $expiryNode;
							break;
						}

						//  Expiry node not defined, use .ini setting
						if ($ini == null)  {
							$ini = parse_ini_file("./offers/".$currentOffer->offer_name."/offer.ini", true);
						}
						$message = $ini["coupon_expired"]["whatsapp_expiry_message"];
						$message = str_replace("\\n", "\n", $message);

						$outputDictionary["media"] = "";
						$outputDictionary["message"] = $message;
						$outputDictionary["messageType"] = $nodeName;

						$outputDictionary["canContinue"] = false;
						$outputDictionary["canTerminate"] = true;
						$nextNodeName = $nodeName;
					}  break;

					case -6:
					case -7:
					case -8:  {
						//   -6 = Not enough quota...
						//   -7 = Not enough quota...
						if (isset($nodeSettings["outOfQuotaNode"]))  {
							$nextNodeName = $nodeSettings["outOfQuotaNode"];
						}
					}  break;

					case -10:
					case -15:  {
						//  -10 = Coupon already exists...
						//  -15 = You have registered one of offer in same series...
						if (isset($nodeSettings["alreadyExistsNode"]))  {
							$nextNodeName = $nodeSettings["alreadyExistsNode"];
						}
					}  break;

					case -50:  {
						//  -50 = Webhook error...
						if (isset($nodeSettings["webhookErrorNode"]))  {
							$nextNodeName = $nodeSettings["webhookErrorNode"];
						}
					}  break;

					default:  {
						//  -2 = Please register again after...
						$outputDictionary["canContinue"] = false;
						$outputDictionary["canTerminate"] = true;
						$nextNodeName = $nodeName;
					}  break;
				}
			}  break;

			//----------------------------------------------------------------------------------------
			//  Process nodes: Cancel message queue
			case 310:  {
				$nodeNameToBeCanceled = isset($nodeSettings["nodeName"]) ? $nodeSettings["nodeName"] : 0;
				$affectedRows = CampaignWhatsappMessageQueue::cancelMessageWithData($offerID, $mobile, $nodeNameToBeCanceled);

				$journeyNode->triggered_at = $now;
				$journeyNode->completed_at = $now;
				$journeyNode->node_data = json_encode(array(
					"affectedRows" => $affectedRows,
					"nodeName" => $nodeName,
					"offerID" => $offerID,
					"mobile" => $mobile,
				));
				$journeyNode->save();

				//  If no next node then clear current node
				if (isset($nodeSettings["nextNode"]) == false)  {$nextNodeName = "";}
				else  {$nextNodeName = $nodeSettings["nextNode"];}
			}  break;

			//----------------------------------------------------------------------------------------
			//  Process nodes: Cancel customer journey node
			case 320:  {
				$targetNode = CampaignCustomerJourney::getNode($userInfo, $offerID, $nodeName);
				if ($targetNode == null)  {

					//  Unknown error
					$outputDictionary["canContinue"] = false;
					$outputDictionary["canTerminate"] = true;
					$nextNodeName = $nodeName;
					break;
				}

				$targetNode->canceled_at = $now;
				$targetNode->save();

				$journeyNode->triggered_at = $now;
				$journeyNode->completed_at = $now;
				$journeyNode->save();

				//  If no next node then clear current node
				if (isset($nodeSettings["nextNode"]) == false)  {$nextNodeName = "";}
				else  {$nextNodeName = $nodeSettings["nextNode"];}
			}  break;

			//----------------------------------------------------------------------------------------
			//  Process nodes: Referral node
			case 330:  {
				$message = null;
				$nextNodeName = $nodeName;

				if ($journeyNode->triggered_at == null)  {
					$journeyNode->triggered_at = $now;

					//  When not reply by user, then nothing to show, just keep stay
					if (empty($incomingMessage))  {

						$outputDictionary["media"] = "";
						$outputDictionary["message"] = "";
						$outputDictionary["messageType"] = $nodeName;

						$outputDictionary["canContinue"] = false;
						$outputDictionary["canTerminate"] = true;

						$journeyNode->save();
						break;
					}
				}

				//  When not reply by user, then nothing to show, just keep stay
				if (isset($nodeSettings["inProgressMessage"]))  {

					//  Get coupon record for dynamic content
					//  getCouponByOfferIDs support comma-separated offer ID
					$coupon = null;
					$array = CampaignCoupon::getCouponByOfferIDs($mobile, $offerID);
					$count = count($array);
					if ($count > 0)  {$coupon = $array[0];}

					$message = $nodeSettings["inProgressMessage"];
					$message = $this->fillDynamicContent($message, $coupon, $offerCode);
				}

				$outputDictionary["canContinue"] = false;
				$outputDictionary["canTerminate"] = true;

				$outputDictionary["media"] = "";
				$outputDictionary["message"] = $message;
				$outputDictionary["messageType"] = $nodeName;

				$journeyNode->save();
			}  break;

			//----------------------------------------------------------------------------------------
			//  2023.03.13 Kay
			//  Use to determine the referal success
			case 335:  {
				if ($journeyNode->triggered_at == null)  {
					$journeyNode->triggered_at = $now;
				}

				//  Get current users referrer code
				$nodeDataDictionary = [];
				$memberReferralCode = CampaignCustomerJourney::getNullNodeByKey($userInfo, $offerID, "memberReferralCode");
				if (strlen($memberReferralCode) > 0)  {

					//  Get parent member record by parent referral code
					$parentMember = Member::getMemberByReferralCode($memberReferralCode);
					if ($parentMember != null)  {

						//  Get null node of current offer of parent member
						$offerIDTOBeCheck = $offerID;
						$parentUserInfo = ["mobile"=>$parentMember->mobile];

						//  Increase member referral count of parent member
						$nodeKey = "referred_count";
						$parentReferredCount = CampaignCustomerJourney::getNullNodeByKey($parentUserInfo, $offerIDTOBeCheck, $nodeKey);
						if (empty($parentReferredCount))  {$parentReferredCount = 1;}
						else  {$parentReferredCount = intval($parentReferredCount)+1;}

						CampaignCustomerJourney::insertAtNullNode($parentUserInfo, $offerIDTOBeCheck, $nodeKey, $parentReferredCount);
						$nodeDataDictionary["parentReferredCount"] = $parentReferredCount;
					}
				}

				if (isset($nodeSettings["nextNode"]) == false)  {$nextNodeName = "";}
				else  {$nextNodeName = $nodeSettings["nextNode"];}

				$nodeDataDictionary["memberReferralCode"] = $memberReferralCode;

				//  Save coupon record in node data and chatbot data
				$journeyNode->completed_at = $now;
				$journeyNode->node_data = json_encode($nodeDataDictionary);
				$journeyNode->save();

			}  break;
			//  2023.03.13 End

			//----------------------------------------------------------------------------------------
			//  Process nodes: Get form data node
			case 340:  {
				$nodeDataAray = array();
				$journeyNode->triggered_at = $now;
				$journeyNode->completed_at = $now;

				//  Get form code from null node
				$formCode = CampaignCustomerJourney::getNullNodeByKey($userInfo, $offerID, "formCode");
				$nodeDataAray["formCode"] = $formCode;
				if ($formCode == null)  {

					//  Error
					$nextNodeName = $nodeSettings["failNode"];
					$journeyNode->save();
					break;
				}

				//  Get form data with form code
				$formData = CampaignForm::getFormData($offerID, $formCode);
				$nodeDataAray["formData"] = $formData;
				if ($formData == null)  {

					//  Error
					$nextNodeName = $nodeSettings["failNode"];
					$journeyNode->save();
					break;
				}

				$nextNodeName = $nodeSettings["nextNode"];
				$journeyNode->node_data = json_encode($nodeDataAray);
				$journeyNode->save();
				
				$nullNode = CampaignCustomerJourney::insertAtNullNode($userInfo, $offerID, "formData", $formData);

			}  break;

			//----------------------------------------------------------------------------------------
			//  Logic nodes: Date comparison node
			case 400:  {
				$timestamp = strtotime($today);

				$date = $nodeSettings["date"];
				$specificDateTimestamp = strtotime($date);

				$journeyNode->triggered_at = $now;
				$journeyNode->completed_at = $now;
				$journeyNode->save();

				// 	"date": "2020-09-04",
				// 	"nodeIfBefore": "belgium-12h"
				// 	"nodeIfEqual": "grand-stick-12h",
				// 	"nodeIfAfter": "grand-stick-12h",
				$nextNodeName = null;
				if ($timestamp < $specificDateTimestamp)  {$nextNodeName = $nodeSettings["nodeIfBefore"];}
				else if ($timestamp == $specificDateTimestamp)  {$nextNodeName = $nodeSettings["nodeIfEqual"];}
				else if ($timestamp > $specificDateTimestamp)  {$nextNodeName = $nodeSettings["nodeIfAfter"];}
			}  break;

			//----------------------------------------------------------------------------------------
			//  Logic nodes: Coupon expiry check node
			case 410:  {
				$nodeDataDictionary = array();
				$key = "nextNode";

				//  Get coupon record
				$coupon = null;
				$array = CampaignCoupon::getCouponByOfferIDs($mobile, $offerID);
				$count = count($array);
				if ($count > 0)  {

					$coupon = $array[0];
					$couponEndTimestamp = strtotime($coupon->expiry_at);
					$currentTimestamp = strtotime($now);
					if ($currentTimestamp > $couponEndTimestamp)  {

						//  Coupon expired
						$key = "nextNodeExpiry";
					}

					$nodeDataDictionary["couponEndTimestamp"] = $couponEndTimestamp;
					$nodeDataDictionary["currentTimestamp"] = $currentTimestamp;
				}

				$nodeDataDictionary["count"] = $count;
				$nodeDataDictionary["key"] = $key;

				$journeyNode->node_data = json_encode($nodeDataDictionary);
				$journeyNode->triggered_at = $now;
				$journeyNode->completed_at = $now;
				$journeyNode->save();

				//  If no next node then clear current node
				if (isset($nodeSettings[$key]) == true)  {
					$nextNodeName = $nodeSettings[$key];
				}

				if (empty($nextNodeName))  {

					//  Expiry node not defined, use .ini setting
					if ($ini == null)  {
						$ini = parse_ini_file("./offers/".$currentOffer->offer_name."/offer.ini", true);
					}

					if (isset($ini["coupon_expired"]["whatsapp_expiry_message"]))  {

						$message = $ini["coupon_expired"]["whatsapp_expiry_message"];
						$message = str_replace("\\n", "\n", $message);

						$outputDictionary["media"] = "";
						$outputDictionary["message"] = $message;
						$outputDictionary["messageType"] = $nodeName;
					}
					$outputDictionary["canContinue"] = false;
					$outputDictionary["canTerminate"] = true;
				}
			}  break;

			//----------------------------------------------------------------------------------------
			//  Payment nodes
			case 500:  {
				$journeyNode->triggered_at = $now;
				$offerCode = $currentOffer->offer_code;

				//  Get coupon record for dynamic content
				$coupon = null;
				$array = CampaignCoupon::getCouponByOfferIDs($mobile, $offerID);
				$count = count($array);
				if ($count > 0)  {$coupon = $array[0];}

				$message = (isset($nodeSettings["message"])) ? $nodeSettings["message"] : null;
				if (empty($message) == false)  {

					$message = $this->fillDynamicContent($message, $coupon, $offerCode);

					//  Also filling with payment
					$itemPrice = floatval($nodeSettings["itemPrice"]);
					if ($itemPrice <= 0)  {

						//  Error!  Value must be positive
						$journeyNode->save();
						$outputDictionary["canContinue"] = false;
						$outputDictionary["canTerminate"] = true;
						break;
					}

					$nodeDataDictionary = array();
					$gateway = $nodeSettings["gateway"];
					$itemName = $nodeSettings["itemName"];
					$expiryTime = $nodeSettings["expiryTime"];
					$timeout = date("ymdHis", strtotime($expiryTime));
					switch (strtolower($gateway))  {

						case "ccba":
						default:  {

							//  13-8 = 5, so 00000
							$mobileNumberOnly = preg_replace("/[^0-9]+/", "", $mobile);
							$mobileNumberOnly = substr("00000".$mobileNumberOnly, -13);

							//  344 = HK Dollars
							$posID = env("CCBA_POSID", "313375473");
							$branchID = env("CCBA_BRANCHID", "010741100");
							$currencyCode = env("CCBA_CURRENCY_CODE", "344");
							$merchantID = env("CCBA_MERCHANTID", "105000059990027");
							$transactionCode = env("CCBA_TRANSACTION_CODE", "OBS001");

							//  Order ID = Date(210730123456)RecordID(6)
							//  * 25 characters, unique, Date(12)+Mobile(13)
							$orderID = date("ymdHis").$mobileNumberOnly;

							//  *** Order is important
							$urlParameter = "MERCHANTID=".$merchantID.
								"&POSID=".$posID.
								"&BRANCHID=".$branchID.
								"&ORDERID=".$orderID.
								"&PAYMENT=".$itemPrice.
								"&CURCODE=".$currencyCode.
								"&TXCODE=".$transactionCode.
								"&REMARK1=".urlencode($journeyNode->node_name).
								"&REMARK2=".urlencode("ccba").
								"&TIMEOUT=".$timeout;

							$publicKey = env("CCBA_PUBLIC_KEY", "01200d7578716a2882a22c8b020111");
							$urlParameterWithKey = $urlParameter."&PUB=".$publicKey;
							$md5 = md5($urlParameterWithKey);

							//  Payment URL
							$baseURL = env("CCBA_PAYMENT_URL", "http://124.127.94.56:18101/CCBIS/B2CMainPlat_00?");
							$paymentURL = $baseURL.$urlParameter.
								"&MAC=".$md5.
								"&DETAILS=".urlencode($itemName." ($mobile)");

							//  Fill dynamic fields now
							$searchArray = array(
								"{{paymentURL}}", "{{itemName}}", "{{itemPrice}}", "{{gateway}}",
							);
							$replaceArray = array(
								$paymentURL, $itemName, $itemPrice, $gateway,
							);
							$message = str_replace($searchArray, $replaceArray, $message);

							//  TODO: Should save parameter to database

							//  Keep data for debug
							$nodeDataDictionary["mobileNumberOnly"] = $mobileNumberOnly;
							$nodeDataDictionary["paymentURL"] = $paymentURL;
							$nodeDataDictionary["merchantID"] = $merchantID;
							$nodeDataDictionary["branchID"] = $branchID;
							$nodeDataDictionary["orderID"] = $orderID;
							$nodeDataDictionary["posID"] = $posID;
						}  break;
					}
					$journeyNode->node_data = json_encode($nodeDataDictionary);
				}

				//  Schedule handling
				$outputDictionary["message"] = $message;
				$outputDictionary["messageType"] = $nodeName;

				$journeyNode->completed_at = $now;
				$journeyNode->save();

				//  Wait until payment gateway result callback
				$outputDictionary["canContinue"] = false;
				$outputDictionary["canTerminate"] = true;
			}  break;

			//----------------------------------------------------------------------------------------
			//  Redeem NFT nodes
			case 600:  {
				$journeyNode->triggered_at = $now;
				$referrerCode = CampaignCustomerJourney::getNullNodeByKey($userInfo, $offerID, "referrerCode");

				//  Issue coupon
				$dictionary = array(
					"email" => $email,
					"offer" => $currentOffer,
					"mobile" => $mobile,
					"offerCode" => $offerCode,
					"referrerCode" => $referrerCode,
				);

				$vendor = $nodeSettings["vendor"];
				switch ($vendor)  {

					case "amuro":  {
						//  Call Amuro API and get redemption code
						$resultDictionary = AmuroNFTController::redeemNFT($dictionary);
						$journeyNode->node_data = json_encode($resultDictionary);

						$status = $resultDictionary["status"];
						if ($status >= 0)  {

							//  Success, put result to null node
							if (isset($nodeSettings["nextNode"]) == false)  {$nextNodeName = "";}
							else  {$nextNodeName = $nodeSettings["nextNode"];}

							$hash = $resultDictionary["hash"];
							$nullNode = CampaignCustomerJourney::insertAtNullNode($userInfo, $offerID, "hash", $hash);

							$fromWallet = $resultDictionary["fromWallet"];
							$nullNode = CampaignCustomerJourney::insertAtNullNode($userInfo, $offerID, "fromWallet", $fromWallet);

							$toWallet = $resultDictionary["toWallet"];
							$nullNode = CampaignCustomerJourney::insertAtNullNode($userInfo, $offerID, "toWallet", $toWallet);

							$openseaURL = $resultDictionary["openseaURL"];
							$nullNode = CampaignCustomerJourney::insertAtNullNode($userInfo, $offerID, "openseaURL", $openseaURL);

							$explorerURL = $resultDictionary["explorerURL"];
							$nullNode = CampaignCustomerJourney::insertAtNullNode($userInfo, $offerID, "explorerURL", $explorerURL);

						}  else  {

							//  Failed
							if (isset($nodeSettings["failNode"]) == false)  {$nextNodeName = "";}
							else  {$nextNodeName = $nodeSettings["failNode"];}
						}
					}  break;

					default:  {
					}  break;

				}
				$journeyNode->completed_at = $now;
				$journeyNode->save();
			}  break;

			case 700: {
				$journeyNode->triggered_at = $now;
				$member = Member::getMemberByMobile($mobile);

				if(!$member instanceof Member) {
					$member = Member::createMember($mobile);
				}

				$point = $member->addIssuePoint($nodeSettings['point'], $nodeSettings['description']['zh-HK']);
				$journeyNode->node_data = json_encode(['point_transactions_id' => $point->id]);
				$journeyNode->completed_at = $now;
				$journeyNode->save();

				if (isset($nodeSettings["nextNode"]) == false)  {$nextNodeName = "";}
				else  {$nextNodeName = $nodeSettings["nextNode"];}
			} break;

			//----------------------------------------------------------------------------------------
			default:  break;
		}

		//----------------------------------------------------------------------------------------
		//  Empty means end of journey
		$chatbotData[$dataKey]["currentNode"] = $nextNodeName;
		if ($nextNodeName == "")  {

			$chatbotData["currentOfferID"] = 0;
			CampaignCustomerJourney::cancelWaitingNodes($mobile, $offerID);

			//  End of journey
			$outputDictionary["canContinue"] = false;
			$outputDictionary["canTerminate"] = true;
		}

		//  Finally
		$outputDictionary["chatbotData"] = $chatbotData;
		return $outputDictionary;
	}

	//----------------------------------------------------------------------------------------
	//  Call by a 5 minutes cronjob
	public function processJourneyReferralNode()  {

		//  Get processing referral nodes
		//  330 = Referral node
		$nodeType = 330;
		$dataArray = CampaignCustomerJourney::getCurrentNodesWithType($nodeType);
		foreach ($dataArray as $journeyNode)  {

			//  Prepare values
			$offerID = $journeyNode->offer_id;
			$userID = $journeyNode->user_id;
			$mobile = $journeyNode->mobile;
			$email = $journeyNode->email;

			//  2023.03.15 Pacess
			//  Revise referral logic, support non-coupon based referral

			//  Referral that related to coupon
// 			$dataArray = CampaignCoupon::getCouponByOfferIDs($mobile, $offerID);
// 			if (count($dataArray) <= 0)  {continue;}
// 
// 			$coupon = $dataArray[0];
// 			if ($coupon == null)  {continue;}
// 
// 			//  Get referral requirement
// 			$nodeSettings = json_decode($journeyNode->node_settings, true);
// 			if (isset($nodeSettings["referralRequirement"]) == false)  {
// 				Log::error("### Referral settings is missing: ".json_encode($journeyNode));
// 				continue;
// 			}
// 
// 			$referralRequirement = intval($nodeSettings["referralRequirement"]);
// 			if ($referralRequirement <= 0)  {
// 				Log::error("### Invalid referral settings: ".json_encode($journeyNode));
// 				continue;
// 			}
// 
// 			//  Get current referred count
// 			$referralData = json_decode($coupon->referral_data, true);
// 			if ($referralData == null)  {
// // 				Log::error("### Referral data is missing: ".$coupon->referral_data);
// 				continue;
// 			}
// 
// 			$referralCount = 0;
// 			if (isset($referralData["registration"]) != false)  {
// 				$referralCount = intval($referralData["registration"]);
// 			}
// 
// 			//  Check if referral count ok
// 			if ($referralCount < $referralRequirement)  {continue;}

			//  Get referral requirement
			$nodeSettings = json_decode($journeyNode->node_settings, true);
			if (isset($nodeSettings["referralRequirement"]) == false)  {
				Log::error("### Referral settings is missing: ".json_encode($journeyNode));
				continue;
			}

			$referralRequirement = intval($nodeSettings["referralRequirement"]);
			if ($referralRequirement <= 0)  {
				Log::error("### Invalid referral settings: ".json_encode($journeyNode));
				continue;
			}

			//  Referral that related to coupon
			$couponID = 0;
			$dataArray = CampaignCoupon::getCouponByOfferIDs($mobile, $offerID);
			if (count($dataArray) > 0 && $dataArray[0] != null)  {

				//  Get current referred count
				$coupon = $dataArray[0];
				$couponID = $coupon->id;
				$referralData = json_decode($coupon->referral_data, true);
				if ($referralData == null)  {continue;}

				$referredCount = 0;
				if (isset($referralData["registration"]) != false)  {
					$referredCount = intval($referralData["registration"]);
				}
			}  else  {

				//  Second piority, non-coupon based referral
				$userInfo = ["mobile"=>$mobile];
				$referredCount = CampaignCustomerJourney::getNullNodeByKey($userInfo, $offerID, "referred_count");
			}

			//  Check if referral count ok
			if ($referredCount < $referralRequirement)  {continue;}
			//  2023.03.15 End

			//----------------------------------------------------------------------------------------
			//  Congratulations!  Referral requirement achieved
			$message = $nodeSettings["message"];
			if (empty($message) == false)  {

				//  Send notification message
				$nodeName = $journeyNode->node_name;
// 				$couponID = $coupon->id;
				app('App\\Http\\Controllers\\ChatbotController')->sendWhatsAppMessage($mobile, $nodeName, $message, $offerID, $couponID, null);
			}

			//  Complete journey node
			$journeyNode->completed_at = date("Y-m-d H:i:s");
			$journeyNode->save();

			//  Set chatbot state to next node and also current offer ID
			$nextNodeName = $nodeSettings["nextNode"];

			$chatbotState = ChatbotState::firstOrNew(array(
				"mobile" => $mobile,
				"channel" => "whatsapp",
			));

			if (isset($chatbotState->chatbot_data))  {
				$chatbotData = json_decode($chatbotState->chatbot_data, true);
			}  else  {
				$chatbotData = array();
			}

			$dataKey = "offer-".$offerID;
			$chatbotData[$dataKey]["currentNode"] = $nextNodeName;

			$chatbotData["currentOfferID"] = $offerID;

			$chatbotState->branch = "coupon";
			$chatbotState->chatbot_data = json_encode($chatbotData);
			$chatbotState->save();
		}
	}

	//----------------------------------------------------------------------------------------
	// 	"userId": "019mr8mf4r",
	// 	"event": "Item Purchased",
	// 	"properties": {
	// 		"name": "Leap to Conclusions Mat",
	// 		"revenue": 14.99
	// 	},
	// 	"context": {
	// 		"ip": "24.5.68.47"
	// 	},
	// 	"timestamp": "2012-12-02T00:30:12.984Z"
	function callSegmentTrack($json)  {
		$url = "https://api.segment.io/v1/track";
		$this->segmentPost($url, $json);
	}

	//----------------------------------------------------------------------------------------
	function callSegmentIdentify($json)  {
		$url = "https://api.segment.io/v1/identify";
		$this->segmentPost($url, $json);
	}

	//----------------------------------------------------------------------------------------
	function segmentPost($url, $json)  {

		$accessToken = "UlFtQ21BaXZ2QWFNem03dFRPRXJvMU9ia3g5QTRFcGs=";

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Basic ".$accessToken));
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($curl);
		curl_close($curl);

		return $response;
	}

	//----------------------------------------------------------------------------------------
	function segmentTest()  {

// 		$mobile = "+85294129112";
// 		$offerCode = "A1b2C3d4";
// 		$referrerCode = "D4c3B2a1";
// 		$uniqueCode = $offerCode;
// 		$now = date("Y-m-d H:i:s");
//
// 		$this->callSegmentTrack(json_encode(array(
// 			"userId" => $mobile,
// 			"event" => "Issue coupon",
// 			"properties" => array(
// 				"offerCode" => $offerCode,
// 				"referralCode" => $referrerCode,
// 				"uniqueCode" => $uniqueCode,
// 			),
// 			"timestamp" => $now,
// 		)));

		//----------------------------------------------------------------------------------------
		//  Fill up mobile number
		$now = date("Y-m-d H:i:s");
		$mobile = "+85257292803";

		$array = array(
			"userId" => $mobile,
			"traits" => array(
				"mobile" => $mobile,
				"phone" => $mobile,
			),
			"timestamp" => $now,
		);

		$data = json_encode($array);
		$this->callSegmentIdentify($data);

	}

}
