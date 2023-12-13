<?php


namespace App\Models;

//----------------------------------------------------------------------------------------
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaignQuickReply extends Model
{
    //  Inform Model it is soft delete instead of real delete
	use SoftDeletes;

	//----------------------------------------------------------------------------------------
	//  The attributes that are mass assignable.
	protected $guarded = ['id'];

	//----------------------------------------------------------------------------------------
	public static function getList(){

		$query = self::pluck("id", "template_name");
		$dataArray = [];
		
		foreach($query as $key => $value){
			$dataArray[] = ["name"=>$key, "id"=>$value];
		}

		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	public static function getSidByID($id=null){

		$record = self::where("id", $id)->first();
		$sid = "";

		if(strlen($record->sid) > 0){
			$sid = $record->sid;
		}
		
		return $sid;
	}

	//----------------------------------------------------------------------------------------
	public static function getRecordByID($id=null)  {

		if ($id == null)  {return null;}

		$record = self::where("id", $id)->first();
		return $record;
	}


}
