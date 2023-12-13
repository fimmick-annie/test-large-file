<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020.  All rights reserved.
//----------------------------------------------------------------------------------------

namespace App\Models;

//----------------------------------------------------------------------------------------
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

//========================================================================================
class CampaignCouponLinkRequest extends Model  {

	//----------------------------------------------------------------------------------------
	public static function getIPAddress()  {
		if (!empty($_SERVER["HTTP_CLIENT_IP"]))  {return $_SERVER["HTTP_CLIENT_IP"];}
		if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))  {return $_SERVER["HTTP_X_FORWARDED_FOR"];}
		return $_SERVER["REMOTE_ADDR"];
	}

	//----------------------------------------------------------------------------------------
	public static function createRecord($offerID, $mobile)  {

		$ipAddress = self::getIPAddress();

		$request = new CampaignCouponLinkRequest();
		$request->offer_id = $offerID;
		$request->mobile = $mobile;
		$request->ip_address = $ipAddress;
		$result = $request->save();
		return $result;
	}

	//----------------------------------------------------------------------------------------
	public static function getRequestCount($offerID, $mobile)  {

		$date = date("Y-m-d H:i:s", strtotime("-24 hours"));

		$query = self::where("mobile", $mobile)
			->where("offer_id", $offerID)
			->where("created_at", ">=", $date);

		$dataArray = $query->get();
		$count = count($dataArray);
		return $count;
	}

}
