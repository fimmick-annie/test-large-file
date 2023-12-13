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
class CampaignCouponPool extends Model  {

	//  Inform Model it is soft delete instead of real delete
	use SoftDeletes;

	protected $table = "campaign_coupon_pool";

	//----------------------------------------------------------------------------------------
	//  The attributes that are mass assignable.
	protected $guard = [];

	//----------------------------------------------------------------------------------------
	public static function getByOfferID($offerID)  {
		if (empty($offerID))  {return null;}

		$query = self::where("offer_id", $offerID);
		$dataArray = $query->get();

		return $dataArray;
	}

	// --------------------------------------------------------------------------
	public static function availableQuota($offerID, $store_code=-1)  {

		//  2021.08.20 Pacess
		//  Somehow mobile is set to empty and it will caused return 0
// 		$query = self::where("offer_id", $offerID)
// 			->whereNull("mobile");

		$query = self::where("offer_id", $offerID)
			->where(function($query)  {
				$query->whereNull("mobile")
					->orWhere("mobile", "");
			});
		//  2021.08.20 End

		if ($store_code != -1)  {
			$query->where("store_code", $store_code);
		}

		$count = $query->count();
		return $count;
	}

	// --------------------------------------------------------------------------
	public static function issuedQuota($offerID, $store_code=-1)  {

		//  2021.08.20 Pacess
		//  Somehow mobile is set to empty and it will caused return 0
// 		$query = self::where("offer_id", $offerID)
// 			->whereNull("mobile");

		$query = self::where("offer_id", $offerID)
			->where(function($query)  {
				$query->whereNull("mobile")
					->orWhere("mobile", "");
			});
		//  2021.08.20 End

		if ($store_code != -1)  {
			$query->where("store_code", $store_code);
		}

		$count = $query->count();
		return $count;
	}

	// --------------------------------------------------------------------------
	public static function voidCoupon($offerID, $mobile, $storeCode)  {
		$affectedRows = self::where('offer_id', $offerID)
		->where(function ($query)  {
			$query->whereNull('mobile')
			->orWhere('mobile', '');
		})
		->where('store_code', $storeCode)
		->limit(1)
		->update(['mobile' => $mobile]);

		$model = self::where('offer_id', $offerID)
		->where('mobile', $mobile)
		->where('store_code', $storeCode)
		->first();

		return [
			"affectedRows" => $affectedRows,
			"model" => $model,
		];
	}

	// --------------------------------------------------------------------------
	public static function deleteWithMobile($mobile)  {
		$affectedRows = self::where("mobile", $mobile)->update(['mobile' => NULL]);
		return $affectedRows;
	}

	// --------------------------------------------------------------------------
	public static function getWithCode($code)  {
		$record = self::where("unique_code", $code)->first();
		return $record;
	}

	// --------------------------------------------------------------------------
	public static function getMaxQuota($offerID)  {
		$value = self::where('offer_id', $offerID)
			->count();
		return $value;
	}

}
