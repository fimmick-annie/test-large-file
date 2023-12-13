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

//========================================================================================
class OptOutChatbotController extends Controller  {

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
			"opt-out", "optout",
			"拒收",
		);

		$message = strtolower($incomingMessage);
		foreach ($keywordArray as $keyword)  {

			$index = strpos($message, $keyword);
			if ($index === false)  {continue;}

			//  Keyword match, opt-out now
			//  *** Mobile format is +85293101987
			$mobile = $userInfo["mobile"];
			if (strlen($mobile) < 8)  {continue;}

			Member::optOut($mobile);
			$message = __("messages.CHATBOT_REPLY_OPTOUT");

			//  False = Already handled incoming message, no need to continue
			$outputDictionary["canContinue"] = false;
			$outputDictionary["message"] = $message;
			$outputDictionary["messageType"] = "opt-out";
			return $outputDictionary;

		}
		return null;
	}
}
