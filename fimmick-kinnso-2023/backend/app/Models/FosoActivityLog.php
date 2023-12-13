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

//========================================================================================
class FosoActivityLog extends Model  {

	//  Inform Model it is soft delete instead of real delete
	use SoftDeletes;

	//----------------------------------------------------------------------------------------
	//  The attributes that are mass assignable.
	protected $guarded = ['id'];

	//----------------------------------------------------------------------------------------
	public static function addLog($username, $email, $url, $remark, $type=null)  {

		$log = new FosoActivityLog();
		$log->username = $username;
		$log->email = $email;
		$log->url = $url;
		$log->remark = $remark;
		$log->type = $type;
		$log->save();
	}

	public static function getList($fromDate = null, $toDate = null)  {
		$query = self::query();
		if ($fromDate != null)  {
			$query->where("created_at", ">=", $fromDate . " 00:00:00");
		}
		if ($toDate != null)  {
			$query->where("created_at", "<=", $toDate . " 23:59:59");
		}

		$dataArray = $query->orderBy("id", "desc")->get();
		return $dataArray;
	}
}