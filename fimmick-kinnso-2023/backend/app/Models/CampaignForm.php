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
class CampaignForm extends Model  {

	//  Inform Model it is soft delete instead of real delete
	use SoftDeletes;

	//----------------------------------------------------------------------------------------
	//  The attributes that are not mass assignable.
	protected $guarded = ['id'];

	//----------------------------------------------------------------------------------------
	public static function createForm($offerID, $formCode, $formData)  {

		$json = json_encode($formData);

		$query = self::firstOrNew([
			"offer_id" => $offerID,
			"form_code" => $formCode,
			"form_data" => $json,
		]);
		$query->expiry_at = date("Y-m-d H:i:s", strtotime("+1 hour"));
		$query->save();

		return $query;
	}

	//----------------------------------------------------------------------------------------
	public static function getFormData($offerID, $formCode)  {

		$now = date("Y-m-d H:i:s");
		$record = self::where("form_code" , $formCode)
			->where("offer_id" , $offerID)
			->where("expiry_at", ">", $now)
			->orderBy('id', 'asc')				// This prevent problem when duplicate entry
			->first();

		return $record;
	}
}
