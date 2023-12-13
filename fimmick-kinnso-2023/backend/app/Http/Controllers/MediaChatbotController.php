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
use Illuminate\Support\Facades\Log;
use App\Models\IncomeMediaRecord;
use Illuminate\Support\Facades\Storage;

//========================================================================================
class MediaChatbotController extends Controller  {

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

		// new request json with media url
		// {
		//     "MediaContentType0": "image\/jpeg",
		//     "SmsMessageSid": "MM14ac47ddbe269d29aa2a508eea9c538e",
		//     "NumMedia": "1",
		//     "SmsSid": "MM14ac47ddbe269d29aa2a508eea9c538e",
		//     "SmsStatus": "received",
		//     "Body": "Nice flower",
		//     "To": "whatsapp:+85264500628",
		//     "NumSegments": "1",
		//     "MessageSid": "MM14ac47ddbe269d29aa2a508eea9c538e",
		//     "AccountSid": "AC09737959feb5b60cefa9b2130a18cdde",
		//     "From": "whatsapp:+85294129112",
		//     "MediaUrl0": "https:\/\/api.twilio.com\/2010-04-01\/Accounts\/AC09737959feb5b60cefa9b2130a18cdde\/Messages\/MM14ac47ddbe269d29aa2a508eea9c538e\/Media\/MEd091fb584a331678e13b069eff318d3c",
		//     "ApiVersion": "2010-04-01"
		// }
		$count = (int)$requestDictionary["NumMedia"];
		if ($count <= 0)  {return $outputDictionary;}

		for ($i=0; $i<$count; $i++)  {

			//  Save media
			$mobile = str_replace("whatsapp:+", "", $requestDictionary["From"]);
			$mediaTypeKey = 'MediaContentType'.$i;
			$mediaType = explode("/", $requestDictionary[$mediaTypeKey]);
			if (count($mediaType) >= 2)  {

				//  Explode success
				$filePath = date("Ymd")."\\".date("YmdHis")."_".$mobile."_".$i.".".$mediaType[1];
			}  else  {

				//  Explode fail
				$filePath = date("Ymd")."\\".date("YmdHis")."_".$mobile."_".$i.".".$mediaType[0];
			}

			$urlKey = "MediaUrl".$i;
			$url = $requestDictionary[$urlKey];
			$url = str_replace("\\", "", $url);

			$userAgent = $_SERVER["HTTP_USER_AGENT"];
			if (empty($userAgent))  {$userAgent = "Fimmick";}

			$options = array(
				CURLOPT_RETURNTRANSFER => true,       // return web page
				CURLOPT_HEADER         => false,      // do not return headers
				CURLOPT_FOLLOWLOCATION => true,       // follow redirects
				CURLOPT_USERAGENT      => $userAgent, // who am i
				CURLOPT_AUTOREFERER    => true,       // set referer on redirect
				CURLOPT_CONNECTTIMEOUT => 120,        // timeout on connect
				CURLOPT_TIMEOUT        => 120,        // timeout on response
				CURLOPT_MAXREDIRS      => 10,         // stop after 10 redirects
			);
			$curl = curl_init($url);
			curl_setopt_array($curl, $options);
			$content = curl_exec($curl);
			curl_close($curl);

			Storage::disk('local')->put($filePath, $content);

			//  Add record to db
			IncomeMediaRecord::addRecord($mobile, Storage::size($filePath), Storage::path($filePath), $requestDictionary["Body"], "Received");
		}

		//  Finished handling, no need to process other children
		$outputDictionary["message"] = "$count image has been saved.";
		$outputDictionary["canContinue"] = false;
		$outputDictionary["canTerminate"] = true;
		return $outputDictionary;
	}
}
