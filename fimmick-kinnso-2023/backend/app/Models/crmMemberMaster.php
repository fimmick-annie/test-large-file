<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8  PHP 7.1  MySQL 8.0
//  Copyrights Fimmick Limited, 2020.  All rights reserved.
//----------------------------------------------------------------------------------------

namespace App\Models;

//----------------------------------------------------------------------------------------
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

//========================================================================================
class crmMemberMaster extends Model  {

	//  Inform Model it is soft delete instead of real delete
	use SoftDeletes;

	//----------------------------------------------------------------------------------------
	//  The attributes that are mass assignable.
	protected $guarded = ['id'];

	public static function getWithMobileString($mobileString) {
		$countryCodes = ["+852", "+853"];

		$mobile = null;
		$mobileRegion = null;
		foreach ($countryCodes as $code){
			if (strpos($mobileString, $code) == 0) {
				$mobile = str_replace($code, "", $mobileString);
				$mobileRegion = $code;
				break;
			}
		}

		if ($mobile == null) {return null;}

		$mobile = intval($mobile);
		$dataArray = self::where('mobile_num', $mobile)
			->where('mobile_region', $mobileRegion)
			->get();

		return $dataArray;
	}
}
