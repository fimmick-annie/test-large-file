<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020-2022.  All rights reserved.
//----------------------------------------------------------------------------------------

namespace App\Models;

//----------------------------------------------------------------------------------------
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

//========================================================================================
class CampaignBanner extends Model  {

	//  Inform Model it is soft delete instead of real delete
	use SoftDeletes;

	//----------------------------------------------------------------------------------------
	//  The attributes that are mass assignable.
	protected $guarded = ['id'];

	//----------------------------------------------------------------------------------------
	public static function getBanners($type="key-visual", $limit=3)  {

		$now = date("Y-m-d H:i:s");
		$query = self::where("started_at", "<=", $now)
			->where("ended_at", ">=", $now)
			->where("weight", ">", 0)  //kay 2022.08.15
			->where("type", $type);

		$array = $query->orderBy("weight", "DESC")->get();
		return $array;
	}

	public static function getList($fromDate = null, $toDate = null)  {

		$query = self::query();

		if ($fromDate != null)  {
			$query->where("ended_at", ">=", $fromDate . " 00:00:00");
		}
		if ($toDate != null)  {
			$query->where("started_at", "<=", $toDate . " 23:59:59");
		}

		$dataArray = $query->orderBy("id", "desc")->get();
		
		return $dataArray;
	}

	public static function getListwithURL($type="key-visual")  {
		
		$list = self::where("type", $type)->pluck("image_url");
		return $list->toArray();
	}
}
