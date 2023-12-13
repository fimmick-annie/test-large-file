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
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use OwenIt\Auditing\Contracts\Auditable;

//========================================================================================
class CampaignMasterJourney extends Model implements Auditable  {

	//  Inform Model it is soft delete instead of real delete
	use SoftDeletes;

	use \OwenIt\Auditing\Auditable;

	//----------------------------------------------------------------------------------------
	//  The attributes that are mass assignable.
	protected $guarded = ['id'];

	//----------------------------------------------------------------------------------------
	public static function getNodes($offerID)  {
		$query = self::where("offer_id", $offerID)
			->orderBy("ordering", "asc");
		$dataArray = $query->get();
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	public static function getJourney($offer_id)  {
		$objects = self::where("offer_id", "=", $offer_id)->orderBy('ordering', 'asc')->get();
		return $objects;
	}

	//----------------------------------------------------------------------------------------
	public static function getNodeById($node_id)  {
		$objects = self::where("id", $node_id)->get();
		if (count($objects) > 0)  {return $objects[0];}
		return null;
	}

	//----------------------------------------------------------------------------------------
	public static function saveJourneyNode($offer_id, $name, $type, $settings,$node_id=null,$is_deleted=false)  {
		if ($node_id === null)  {

			//insert
			$ordering = 100;
			$nodes = self::getJourney($offer_id);
			if (count($nodes) > 0) {
				$node = $nodes[count($nodes) - 1];
				$ordering = $node->ordering + 10;
			}

			$record = new CampaignMasterJourney();
			$record->offer_id = $offer_id;
			$record->ordering = $ordering;
			$record->node_name = $name;
			$record->type = $type;
			$record->node_settings = $settings;
			$record->save();

		}  else  {

			//update
			$record=self::getNodeById($node_id);
			if ($record !== null)  {

				$record->node_name = $name;
				$record->type = $type;
				$record->node_settings = $settings;

				if ($is_deleted)  {
					$record->delete();
				}  else  {
					$record->save();
				}
			}
		}
	}

}
