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
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

//========================================================================================
class LoginRecord extends Model  {

	//  Inform Model it is soft delete instead of real delete
	use SoftDeletes;

	//----------------------------------------------------------------------------------------
	//  The attributes that are mass assignable.
	protected $guarded = ['id'];

	//----------------------------------------------------------------------------------------
	public static function getRecordWithToken($token)  {

		$now = date("Y-m-d H:i:s");

		$record = self::where("login_token", $token)
			->whereNull("used_at")
			->where("valid_until", ">=", $now)
			->first();
		return $record;
	}

	//----------------------------------------------------------------------------------------
	public static function isReachesLimit($mobile)  {
		return false;
	}

	//----------------------------------------------------------------------------------------
	public static function createLoginRecord($memberID, $mobile, $token)  {

		$now = date("Y-m-d H:i:s");
		$validUntil = date("Y-m-d H:i:s", strtotime("+5 minutes"));

		//  Void all existing tokens of the mobile
		$affectedRows = self::where("mobile", $mobile)
			->whereNull("deleted_at")
			// ->where("valid_until", ">=", $now)
			->update(["deleted_at"=>$now]);

		//  Create a new one
		$record = new LoginRecord();
		$record->member_id = $memberID;
		$record->mobile = $mobile;
		$record->login_token = $token;
		$record->valid_until = $validUntil;
		$record->save();
	}

}