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

use OwenIt\Auditing\Contracts\Auditable;

//========================================================================================
class CampaignListing extends Model implements Auditable  {

	//  Inform Model it is soft delete instead of real delete
	use SoftDeletes;

	use \OwenIt\Auditing\Auditable;

	//----------------------------------------------------------------------------------------
	//  The attributes that are mass assignable.
	protected $fillable = [
		"list_name",
		"offer_id",
		"ordering",
		'start_at',
		'end_at'
	];

	//----------------------------------------------------------------------------------------
	public function offer()  {
		return $this->belongsTo("App\Models\CampaignOffer");
	}

	//----------------------------------------------------------------------------------------
	public static function getList($listName)  {

		$now = date("Y-m-d H:i:s");
		if (empty($listName))  {$listName = "default";}

		$array = self::where("list_name", $listName)
			->where("end_at", ">=", $now)
			->where("start_at", "<=", $now)
			->orderBy("ordering", "desc")
			->has("offer")
			->get();
		return $array;
	}
}
