<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2021.  All rights reserved.
//----------------------------------------------------------------------------------------

namespace App\Models;

//----------------------------------------------------------------------------------------
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

//========================================================================================
class SegmentExchange extends Model  {

	//  Inform Model it is soft delete instead of real delete
	use SoftDeletes;

	//----------------------------------------------------------------------------------------
	//  The attributes that are not mass assignable.
	protected $guarded = ['id'];

	//----------------------------------------------------------------------------------------
	public static function getIPAddress()  {
		if (!empty($_SERVER["HTTP_CLIENT_IP"]))  {return $_SERVER["HTTP_CLIENT_IP"];}
		if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))  {return $_SERVER["HTTP_X_FORWARDED_FOR"];}
		return $_SERVER["REMOTE_ADDR"];
	}

	//----------------------------------------------------------------------------------------
	public static function createRecord($offerCode, $aid, $aidToken, $referrerCode, $formCode="", $memberReferrerCode=null)  {

		$ipAddress = self::getIPAddress();
		$userAgent = $_SERVER["HTTP_USER_AGENT"];

		//  TODO: Need to check duplicated AID token?

		$query = self::firstOrNew([
			"offer_code" => $offerCode,
			"aid" => $aid,
			"referrer_code" => $referrerCode,
		]);
		
		$query->aid_token = $aidToken;
		$query->expiry_at = date("Y-m-d H:i:s", strtotime("+24 hours"));
		$query->ip_address = $ipAddress;
		$query->user_agent = $userAgent; 

		// -- 2022.08.15 Add
		if (is_null($memberReferrerCode) == false)  {
			$query->member_referral_code = $memberReferrerCode;
		}

		if (empty($formCode) == false)  {
			$query->form_code = $formCode;
		}

		$query->save(); 

		return $query;
	}

	//----------------------------------------------------------------------------------------
	public static function getRecordWithAID($offerCode, $aid, $referrerCode)  {

		$now = date("Y-m-d H:i:s");
		$query = self::where("offer_code" , $offerCode);
		if($referrerCode) {
			$query->where('referrer_code', $referrerCode);
		}
		if($aid) {
			$query->where('aid', $aid);
		}

		$query->orderBy("id", "asc");
		$record = $query->first();

		return $record;
	}

	//----------------------------------------------------------------------------------------
	public static function getRecordWithToken($aidToken)  {

		$now = date("Y-m-d H:i:s");
		$record = self::where("aid_token", $aidToken)
			->where("expiry_at", ">", $now)
			->orderBy("id", "asc")				// This prevent problem when duplicate entry
			->first();

		return $record;
	}

}
