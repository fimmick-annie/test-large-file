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
use Illuminate\Support\Facades\DB;

use OwenIt\Auditing\Contracts\Auditable;

//========================================================================================
class CampaignStoreQuota extends Model implements Auditable  {

	//  Inform Model it is soft delete instead of real delete
	use SoftDeletes;

	use \OwenIt\Auditing\Auditable;

	//----------------------------------------------------------------------------------------
	//  The attributes that are mass assignable.
	protected $guarded = ['id'];

	//----------------------------------------------------------------------------------------
	public static function getStoreList($offerID)  {
		$now = date("Y-m-d H:i:s");
		$storeCodeArray = self::select("store_name")
			->where("offer_id", $offerID)
			->where("start_at", "<=", $now)
			->where("end_at", ">=", $now)
			->groupBy("store_name")
			->get();
		return $storeCodeArray;
	}

	//----------------------------------------------------------------------------------------
	public static function getStoreListWithPeriod($offerID)  {
		$now = date("Y-m-d H:i:s");
		$storeArray = self::where("offer_id", $offerID)
			// ->where("start_at", "<=", $now)
			->where("end_at", ">=", $now)
			->get();
		return $storeArray;
	}

	//----------------------------------------------------------------------------------------
	public static function getStoreListWithQuotaFlag($offerID)  {
		$now = date("Y-m-d H:i:s");
		$storeCodeArray = self::select("store_name", DB::raw("SUM(quota)>SUM(quota_issued) AS have_quota"))
			->where("offer_id", $offerID)
			// Kay 2022.12.19 remark for selecting the coupon before the period
			// ->where("start_at", "<=", $now)
			->where("end_at", ">=", $now)
			->groupBy("store_name")
			->get();
		return $storeCodeArray;
	}

	//----------------------------------------------------------------------------------------
	public static function getStorePeriod($offerID, $storeName)  {
		$now = date("Y-m-d H:i:s");
		$array = self::where("offer_id", $offerID)
			->where("store_name", $storeName)
			// Kay 2022.12.19 remark for selecting the coupon before the period
			// ->where("start_at", "<=", $now)
			->where("end_at", ">=", $now)
			->orderBy("start_at", "asc")
			->get();
		return $array;
	}

	//----------------------------------------------------------------------------------------
	public static function getStoreWithName($offerID, $storeName)  {
		$store = self::where("offer_id", $offerID)
			->where("store_name", $storeName)
			->first();
		return $store;
	}

	//----------------------------------------------------------------------------------------
	public static function getStoreWithQuotaID($offerID, $storeQuotaID)  {
		$store = self::where("offer_id", $offerID)
			->where("id", $storeQuotaID)
			->first();
		return $store;
	}

	//----------------------------------------------------------------------------------------
	public static function getList($fromDate, $toDate, $offerID)  {
		$query = self::where("offer_id", $offerID);
		if ($fromDate != null)  {$query->where("end_at", ">=", $fromDate." 00:00:00");}
		if ($toDate != null)  {$query->where("start_at", "<=", $toDate." 23:59:59");}

		$dataArray = $query->orderBy("id", "desc")->get();
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	public static function getQuotaRecord($offerID, $storeCode=null, $startAt=null, $endAt=null)  {
		$query = self::query()->where("offer_id", $offerID);

		if ($storeCode != null)  {$query->where("store_code", $storeCode);}
		if ($startAt != null)  {$query->where("start_at", $startAt);}
		if ($endAt != null)  {$query->where("end_at", $endAt);}

		$dataArray = $query->get();
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	public static function deductQuota($offerID, $storeCode, $storeQuotaID)  {
		$affectRows = self::where('offer_id', '=', $offerID);

		if (isset($storeQuotaID) && $storeQuotaID!=0) {$affectRows->where('id', $storeQuotaID);}
		if (isset($storeCode)) {$affectRows->where('store_code', $storeCode);}

		$affectRows = $affectRows->whereRaw('`quota`>=`quota_issued`+1')
			// Kay 2022.12.10 remark for selecting the coupon before the period
			// ->where('start_at', '<', DB::raw('NOW()'))
			->where('end_at', '>', DB::raw('NOW()'))
			->update(['quota_issued'=>DB::raw('`quota_issued`+1')]);
		return $affectRows;
	}

	//----------------------------------------------------------------------------------------
	public static function checkStoreCode($offerID, $storeCode)  {
		$array = self::where('offer_id', '=', $offerID)

			//  This is for case-insensitive
			->where('store_code', $storeCode)

			//  This is for case-sensitive
// 			->whereRaw('BINARY `store_code`=?', [$storeCode])

			->get();
		return $array;
	}

	//----------------------------------------------------------------------------------------
	public static function getMaxQuota($offerID)  {
		$value = self::where('offer_id', $offerID)
			->sum("quota");
		return $value;
	}

	//----------------------------------------------------------------------------------------
	public static function getStoreCodeByFormData($offerID, $storeName, $periodID=0)  {
		
		if ($periodID > 0){
			$record = self::where('id', $periodID)->first();
		}else{
			$record = self::where('offer_id', $offerID)
				->where('store_name', $storeName)
				->first();
		}

		return $record->store_code;
	}

	//----------------------------------------------------------------------------------------
	public static function getStorePeriodWithSelectDate($offerID, $storeName, $dateSelected){

		$timeslot = $dateSelected." 00:00:00";

		$array = self::where("offer_id", $offerID)
			->where("store_name", $storeName)
			->where("start_at", "<=", $now)
			->where("end_at", ">=", $now)
			->orderBy("start_at", "asc")
			->get();

	}

}
