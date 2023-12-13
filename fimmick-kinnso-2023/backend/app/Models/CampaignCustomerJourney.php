<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020.  All rights reserved.
//----------------------------------------------------------------------------------------

//  Node type:
//  > 100 = Message only node
//  > 200 = Question node
//  > 300 = Issue coupon node
//  > 400 = Date comparison node

namespace App\Models;

//----------------------------------------------------------------------------------------
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

//========================================================================================
class CampaignCustomerJourney extends Model  {

	//  Inform Model it is soft delete instead of real delete
	use SoftDeletes;

	//----------------------------------------------------------------------------------------
	//  The attributes that are mass assignable.
	protected $guarded = ['id'];

	//----------------------------------------------------------------------------------------
	public static function getNodes($offerID)  {
		$query = self::where("offer_id", $offerID)
			->orderBy("ordering", "asc")
			->orderBy('id', 'asc');				// This prevent problem when duplicate entry
		$dataArray = $query->get();
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	public static function getNodeWithName($offerID, $nodeName)  {
		$query = self::where("offer_id", $offerID)
			->where("node_name", $nodeName)
			->orderBy("ordering", "asc")
			->orderBy('id', 'asc');				// This prevent problem when duplicate entry
		$dataArray = $query->get();
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	public static function getNodesWithPaging($offerID, $offset, $limit)  {
		$query = self::where("offer_id", $offerID)
			->orderBy('ordering', 'asc')
			->orderBy("id", "asc")
			->offset($offset)
			->limit($limit);
		$dataArray = $query->get();
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	public static function getCurrentNodesWithType($nodeType)  {
		$query = self::where("type", $nodeType)
			->whereNull("canceled_at")
			->whereNull("completed_at")
			->whereNotNull("triggered_at")
			->orderBy("ordering", "asc")
			->orderBy('id', 'asc');				// This prevent problem when duplicate entry
		$dataArray = $query->get();
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	public static function getNode($userInfo, $offerID, $nodeName)  {

		$query = self::where("offer_id", $offerID)
			->whereNull('canceled_at')
			->whereNull('completed_at')
			->where('ordering', '<>', 1);		// Escape null node

		if (isset($userInfo["mobile"]))  {$query->where("mobile", $userInfo["mobile"]);}
		if (isset($userInfo["email"]))  {$query->where("email", $userInfo["email"]);}
		if (isset($userInfo["userID"]))  {$query->where("user_id", $userInfo["userID"]);}

		//  Optional
		if ($nodeName != null)  {$query->where("node_name", $nodeName);}

		$record = $query
			->orderBy('ordering', 'asc')
			->orderBy('id', 'asc')				// This prevent problem when duplicate entry
			->first();
		return $record;
	}

	//----------------------------------------------------------------------------------------
	public static function cancelWaitingNodes($mobile, $offerID)  {
		$now = date("Y-m-d H:i:s");
		$query = self::where("mobile", $mobile)
			->where("offer_id", $offerID)
			->whereNull('canceled_at')
			->whereNull('completed_at')
			->update(['canceled_at' => $now]);
	}

	//----------------------------------------------------------------------------------------
	public static function getNodesByUser($userInfo,$offerID){
		$query = self::where("offer_id", $offerID)
			->whereNull('canceled_at');

		if (isset($userInfo["mobile"]))  {$query->where("mobile", 'like', '%'.$userInfo["mobile"].'%');}
		if (isset($userInfo["email"]))  {$query->where("email", $userInfo["email"]);}
		if (isset($userInfo["userID"]))  {$query->where("user_id", $userInfo["userID"]);}

		$query->orderBy("ordering", "asc");
		$query->orderBy('id', 'asc');			// This prevent problem when duplicate entry

		return $query->get();
	}

	//----------------------------------------------------------------------------------------
	public static function getRecordsToBeArchived($limit=1000)  {
		$date = date("Y-m-d H:i:s", strtotime("-30 days"));
		$query = self::where("completed_at", "<", $date)
			->orWhere("canceled_at", "<", $date)
			->orderBy("id", "asc")
			->limit($limit);
		$dataArray = $query->get();
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	public static function getRecordsToBeDeleted($limit=1000)  {
		$date = date("Y-m-d H:i:s", strtotime("-7 days"));
		$query = self::onlyTrashed()->where("deleted_at", "<", $date)
			->orderBy("id", "asc")
			->limit($limit);
		$dataArray = $query->get();
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	public static function getRecordsByOfferID($offerID=0, $limit=1000)  {
		$query = self::where("offer_id", $offerID)
			->limit($limit);
		$dataArray = $query->get();
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	public static function insertAtNullNode($userInfo, $offerID, $key, $value)  {

		$nodeName = "null-node";

		$query = self::firstOrNew([
			"node_name" => $nodeName,
			"offer_id" => $offerID,
			"ordering" => 1,
			"mobile" => $userInfo["mobile"],
			"type" => 1,
		]);

		$nodeData = null;
		if (!isset($query->node_data)) {$nodeData = [];}
		else {$nodeData = json_decode($query->node_data, true);}

		$nodeData[$key] = $value;

		$query->node_data = json_encode($nodeData);
		$query->save();

		return $query;
	}

	//----------------------------------------------------------------------------------------
	public static function getNullNodeByKey($userInfo, $offerID, $key)  {

		$nodeName = "null-node";

		$query = self::where("node_name" , $nodeName)
			->where("offer_id" , $offerID)
			->where("mobile" , $userInfo["mobile"])
			->where("type" , 1)
			->orderBy('id', 'asc')				// This prevent problem when duplicate entry
			->first();

		$nodeData = null;
		if (!isset($query->node_data)) {$nodeData = [];}
		else {$nodeData = json_decode($query->node_data, true);}

		if (!isset($nodeData[$key]))  {return "";}
		return $nodeData[$key];
	}

}
