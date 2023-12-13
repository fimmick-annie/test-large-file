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
use Illuminate\Http\Request;

use App\Models\CampaignCouponPool;
use App\Models\CampaignOffer;

//========================================================================================
class AmuroNFTController extends Controller  {

	//----------------------------------------------------------------------------------------
	public static function redeemNFT($parameterDictionary)  {

		$outputDictionary = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$mobile = $parameterDictionary["mobile"];
		if (empty($mobile))  {

			$outputDictionary["status"] = -1;
			$outputDictionary["message"] = "### Invalid mobile number...";
			return $outputDictionary;
		}

		$apiURL = config("app.amuro_api_url");
		if (empty($apiURL))  {

			$outputDictionary["status"] = -10;
			$outputDictionary["message"] = "### Invalid Amuro URL...";
			return $outputDictionary;
		}

		$apiKey = config("app.amuro_api_key");
		if (empty($apiKey))  {

			$outputDictionary["status"] = -11;
			$outputDictionary["message"] = "### Invalid Amuro key...";
			return $outputDictionary;
		}

		$offer = $parameterDictionary["offer"];

		$offerID = $offer->id;
		$storeCode = "amuro";

		//----------------------------------------------------------------------------------------
		//  Get available redemption code
		$affectedRows = CampaignOffer::deductQuota($offerID);
		if ($affectedRows > 0)  {

			//  Try to get one quota from coupon pool
			$affectedRows = CampaignCouponPool::availableQuota($offerID, $storeCode);
		}
		if ($affectedRows <= 0)  {

			//  Not enough quota
			$outputDictionary["status"] = -6;
			$outputDictionary["message"] = "Not enough quota...";
			return $outputDictionary;
		}

		$array = CampaignCouponPool::voidCoupon($offerID, $mobile, $storeCode);
		$nextAvailableCode = $array['model'];
		$affectedRows = $array['affectedRows'];
		if ($affectedRows <= 0) {

			//  Not enough quota
			$outputDictionary["status"] = -8;
			$outputDictionary["message"] = "Not enough quota (pre-generated) ...";
			return $outputDictionary;
		}
		$uniqueCode = $nextAvailableCode->unique_code;
		$outputDictionary["uniqueCode"] = $uniqueCode;
		$response = null;

		//----------------------------------------------------------------------------------------
		//  Call Amuro API
		$routeCampaignsRedeem = config("app.amuro_route_campaigns_redeem");
		$url = $apiURL.$routeCampaignsRedeem;

		$amuroApiKey = config("app.amuro_api_key");
		$parameters = "redemption_code=".urlencode($uniqueCode)."&phone=".urlencode($mobile);

		$header = [
			'X-API-Key: '.$amuroApiKey,
		];

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		$response = curl_exec($curl);
		curl_close($curl);

		$outputDictionary["apiURL"] = $url;
		$outputDictionary["apiParameters"] = $parameters;
		$environment = strtolower(config("app.env", "production"));
		if ($environment != "production")  {
			$outputDictionary["amuroApiKey"] = $amuroApiKey;
		}

		//  Invalid response
		if (empty($response))  {
			$outputDictionary["status"] = -10;
			$outputDictionary["message"] = "Invalid response...";
			return $outputDictionary;
		}

		$json = json_decode($response, true);
		if (empty($json))  {
			$outputDictionary["status"] = -11;
			$outputDictionary["message"] = "Invalid JSON data: $response...";
			return $outputDictionary;
		}

		if (!isset($json["opensea_url"]))  {
			$outputDictionary["status"] = -12;
			$outputDictionary["message"] = "Invalid JSON data: $response...";
			return $outputDictionary;
		}

		// 	{
		// 		"type": 2,
		// 		"chainId": 80001,
		// 		"nonce": 69,
		// 		"maxPriorityFeePerGas": {
		// 			"type": "BigNumber",
		// 			"hex": "0x59682f00"
		// 		},
		// 		"maxFeePerGas": {
		// 			"type": "BigNumber",
		// 			"hex": "0x59682f10"
		// 		},
		// 		"gasPrice": null,
		// 		"gasLimit": {
		// 			"type": "BigNumber",
		// 			"hex": "0x019765"
		// 		},
		// 		"to": "0x423c8F7c90040B074b92cb8A9bE24843597B9c6f",
		// 		"value": {
		// 			"type": "BigNumber",
		// 			"hex": "0x00"
		// 		},
		// 		"data": "0x42842e0e000000000000000000000000913d9568685ea77ab7c23cc5e776015afc2bdc9300000000000000000000000048ea3309e0360e4bec02ad6bf04ab64d0b136e230000000000000000000000000000000000000000000000000000000000000047",
		// 		"accessList": [],
		// 		"hash": "0x028854f6371c6f88fe071bfc7b3f2df18043b15c932969536d624e1ae632cf37",
		// 		"v": 0,
		// 		"r": "0x78d5bc982defde22f81d0203ef08f499af11eb1f104e7f318fdec122f50b32f4",
		// 		"s": "0x2177f279335517963f38fe49aacaabd3458f1ac12e7c62b2bd6c409cff0d4407",
		// 		"from": "0x913D9568685Ea77Ab7C23Cc5e776015aFc2Bdc93",
		// 		"confirmations": 0,
		// 		"opensea_url": "https://testnets.opensea.io/assets/mumbai/0x423c8F7c90040B074b92cb8A9bE24843597B9c6f/71",
		// 		"explorer_url": "https://mumbai.polygonscan.com/tx/0x028854f6371c6f88fe071bfc7b3f2df18043b15c932969536d624e1ae632cf37"
		// 	}
		$to = $json["to"];
		$from = $json["from"];
		$hash = $json["hash"];
		$nonce = $json["nonce"];
		$chainID = $json["chainId"];
		$openseaURL = $json["opensea_url"];
		$explorerURL = $json["explorer_url"];

		//----------------------------------------------------------------------------------------
		//  Finally
		$outputDictionary["hash"] = $hash;
		$outputDictionary["nonce"] = $nonce;
		$outputDictionary["toWallet"] = $to;
		$outputDictionary["fromWallet"] = $from;
		$outputDictionary["chainID"] = $chainID;
		$outputDictionary["openseaURL"] = $openseaURL;
		$outputDictionary["explorerURL"] = $explorerURL;

		$outputDictionary["status"] = 0;
		$outputDictionary["response"] = $response;
		$outputDictionary["storeCode"] = $storeCode;
		return $outputDictionary;
	}

}
