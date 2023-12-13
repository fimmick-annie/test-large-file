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
class WhatsappWebhook extends Model  {

	//  Inform Model it is soft delete instead of real delete
	use SoftDeletes;

	//----------------------------------------------------------------------------------------
	//  The attributes that are mass assignable.
	protected $guarded = ['id'];

	//----------------------------------------------------------------------------------------
	static public function addRecord($vendor, $messageID, $status, $content, $mobileFrom=null)  {

		$record = new WhatsappWebhook();
		$record->vendor = $vendor;
		$record->message_id = $messageID;
		$record->status = $status;
		$record->content = $content;

		if ($mobileFrom != null)  {$record->mobile_from = $mobileFrom;}

		$result = $record->save();
		return $result;
	}

	//----------------------------------------------------------------------------------------
	public static function getRecordsWithEventType($value)  {
		$array = self::where("content", "like", '%"EventType":"'.$value.'"%')
			->get();
		return $array;
	}

	//----------------------------------------------------------------------------------------
	//  Date value only, no time
	public static function getList($fromDate=null, $toDate=null, $status="message")  {

		$query = self::query();
		if ($status != null)  {$query->where("status", $status);}
		if ($fromDate != null)  {$query->where("created_at", ">=", $fromDate." 00:00:00");}
		if ($toDate != null)  {$query->where("created_at", "<=", $toDate." 23:59:59");}

		$dataArray = $query->orderBy("created_at", "desc")->get();
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	//  Date value only, no time
	public static function getListWithPaging($fromDate=null, $toDate=null, $status="message", $skip=0, $take=0)  {

		$query = self::query();
		if ($status != null)  {$query->where("status", $status);}
		if ($fromDate != null)  {$query->where("created_at", ">=", $fromDate." 00:00:00");}
		if ($toDate != null)  {$query->where("created_at", "<=", $toDate." 23:59:59");}

		if ($skip > 0)  {$query->skip($skip);}
		if ($take > 0)  {$query->take($take);}

		$dataArray = $query->orderBy("created_at", "desc")->get();
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	public static function getRecordsToBeArchived($limit=1000)  {
		$date = date("Y-m-d H:i:s", strtotime("-30 days"));
		$query = self::where('created_at', '<', $date)
			->limit($limit);
		$dataArray = $query->get();
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	public static function getRecordsToBeDeleted($limit=1000)  {
		$date = date("Y-m-d H:i:s", strtotime("-3 days"));
		$query = self::onlyTrashed()->where("deleted_at", "<", $date)
			->limit($limit);
		$dataArray = $query->get();
		return $dataArray;
	}




	//----------------------------------------------------------------------------------------
// 	public static function getStat()  {
// 		$startTime = "2021-03-29 16:30:00";
// 		$endTime = "2021-03-29 16:39:59";
// 		$thresholdTime = "2021-03-29 16:38:00";
//
// 		$dataArray = \DB::table("whatsapp_webhooks")
// 			->selectRaw("min(created_at) AS min, max(created_at) AS max, count(*) AS count")
// 			->where("created_at", ">=", $startTime)
// 			->where("created_at", "<=", $endTime)
// 			->groupBy("vendor")
// 			->get();
//
// dd($dataArray);
// 		return $dataArray;
// 	}

}
