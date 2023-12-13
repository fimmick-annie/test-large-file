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

use GuzzleHttp;

//========================================================================================
class CampaignWebhookController extends Controller  {

	//----------------------------------------------------------------------------------------
	//  Call when user submit an offer form
	public function offerRegistrationAPI(Request $request)  {
		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$offerID = $request->input("offerID");





		//  Check mobile if exists in database, return error if exists
// 		$mobile = $request->input("areaCode").$request->input("mobile");
// 		$email = $request->input("email");
//
// 		$selectedChannel = $request->input("selectedChannel");
// 		$confirmationMethod = $request->input("confirmationMethod");
//

// 		$client = new GuzzleHttp\Client();
// 		$result = $client->request("POST", 'https://pacessho.fimmickapps.com/lineSticker/7348/7348_meta_1.txt');
// 		$body = (string)($result->getBody());
// 		$response["result"] = $body;

		$result = "\n".json_encode($request->all());
		$response["result"] = $result;
		file_put_contents("call_log.txt", $result, FILE_APPEND);

		//  Output now
// 		$response["data"] = $dataArray;
		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function couponActivationAPI(Request $request)  {
		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		//  Output now
// 		$response["data"] = $dataArray;
		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}




// 	public function test(Request $request)  {
// 		$response = array(
// 			"timeStamp" => Date("YmdHis"),
// 			"apiName" => __FUNCTION__,
// 			"status" => -99,
// 			"message" => "Unexpected error...",
// 		);
//
// 		$url = "api/webhook/offerRegistration";
//
// 		$dictionary = array(
// 			"message" => "Good!",
// 		);
//
// 		//  Prevent hang when local host
// 		$client = new GuzzleHttp\Client([
// 			"base_uri" => "http://127.0.0.1:8003",
// 			"defaults" => ["exceptions" => false]
// 		]);
// 		$result = $client->request("POST", $url, [
// 			"body" => json_encode($dictionary),
// 		]);
// 		$body = (string)($result->getBody());
// 		$response["result"] = $body;
//
// 		//  Output now
// // 		$response["data"] = $dataArray;
// 		$response["status"] = 0;
// 		$response["message"] = "Done";
// 		return response()->json($response);
// 	}

}
