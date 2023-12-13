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
use Illuminate\Support\Facades\DB;

use OwenIt\Auditing\Contracts\Auditable;

//========================================================================================
class MarketingList extends Model implements Auditable  {

	//  Inform Model it is soft delete instead of real delete
	use SoftDeletes;

	use \OwenIt\Auditing\Auditable;

	//----------------------------------------------------------------------------------------
	//  The attributes that are mass assignable.
	protected $guarded = ['id'];

	//----------------------------------------------------------------------------------------
	//  Date value only, no time
	public static function getList($fromDate=null, $toDate=null, $listName=null)  {

		$query = self::query();
		if ($listName != null)  {$query->where("list_name", $listName);}
		if ($fromDate != null)  {$query->where("updated_at", ">=", $fromDate." 00:00:00");}
		if ($toDate != null)  {$query->where("updated_at", "<=", $toDate." 23:59:59");}

		$dataArray = $query->orderBy("list_name", "desc")->get();
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	public static function getListNameArray()  {

		$query = self::select(DB::raw('list_name, COUNT(*) as count'))->groupBy("list_name");

		$dataArray = $query->orderBy("list_name", "asc")->get();
		return $dataArray;
	}

}
