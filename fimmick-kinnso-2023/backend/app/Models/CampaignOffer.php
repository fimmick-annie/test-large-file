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
use App\Models\FosoTag;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use OwenIt\Auditing\Contracts\Auditable;

//========================================================================================
class CampaignOffer extends Model implements Auditable
{

	//  Inform Model it is soft delete instead of real delete
	use SoftDeletes;
	

	use \OwenIt\Auditing\Auditable;

	//----------------------------------------------------------------------------------------
	//  The attributes that are mass assignable.
	protected $guarded = ['id'];
	// 	protected $fillable = [
	// 		"start_at",
	// 		"end_at",
	// 		"offer_code",
	// 		"offer_name",
	// 		"offer_title",
	// 		"offer_subtitle",
	// 		"blade_folder",
	// 		"code_type",
	// 		"channel_expiry",
	// 		"quota",
	// 		"quota_issued",
	// 		"tnc",
	// 	];

	//----------------------------------------------------------------------------------------
	public function listing()  {
		return $this->hasMany("App\Models\CampaignListing");
	}

	//----------------------------------------------------------------------------------------
	public function coupons()  {
		return $this->hasMany("App\Models\CampaignCoupon");
	}

	public function getFosoTags(){
        return $this->belongsToMany(FosoTag::class);
    }

	//----------------------------------------------------------------------------------------
	public static function deductQuota($offerID)  {
		$affectRows = self::where('id', '=', $offerID)
			->whereRaw('`quota`>=`quota_issued`+1')
			->update(['quota_issued' => DB::raw('`quota_issued`+1')]);
		return $affectRows;
	}

	//----------------------------------------------------------------------------------------
	public static function increaseQuota($offerID)  {
		$affectRows = self::where('id', '=', $offerID)
			->update(['quota_issued' => DB::raw('`quota_issued`-1')]);
		return $affectRows;
	}

	//----------------------------------------------------------------------------------------
	//  Date value only, no time
	public static function getList($fromDate = null, $toDate = null)  {
		$query = self::query();
		if ($fromDate != null)  {
			$query->where("end_at", ">=", $fromDate . " 00:00:00");
		}
		if ($toDate != null)  {
			$query->where("start_at", "<=", $toDate . " 23:59:59");
		}

		$dataArray = $query->orderBy("id", "desc")->get();
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	public static function getListWithPaging($fromDate = null, $toDate = null, $skip = 0, $take = 0)  {
		$query = self::query();
		if ($fromDate != null)  {
			$query->where("end_at", ">=", $fromDate . " 00:00:00");
		}
		if ($toDate != null)  {
			$query->where("start_at", "<=", $toDate . " 23:59:59");
		}

		if ($skip > 0)  {
			$query->skip($skip);
		}
		if ($take > 0)  {
			$query->take($take);
		}

		$dataArray = $query->orderBy("id", "desc")->get();
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	public static function getOffer($offerCode = null)  {
		$query = self::where("offer_code", $offerCode);
		$dataArray = $query->first();
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	public static function getOfferByID($offerID = 0)  {
		$query = self::where("id", $offerID);
		$data = $query->first();
		return $data;
	}

	//----------------------------------------------------------------------------------------
	public static function getOfferByIDs($offerIDs = null)  {

		if ($offerIDs == null)  {return null;}

		$query = self::whereIn("id", $offerIDs);
		$dataArray = $query->get();
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	public static function getOfferByMobile($offerID = 0, $mobile = null)  {
		$query = self::where("id", $offerID);
		if (empty($mobile) == false)  {
			$query->where("mobile", $mobile);
		}

		$data = $query->first();
		return $data;
	}

	//----------------------------------------------------------------------------------------
	public static function getOffers($limit=5, $skip=0, $tag=null)  {

		$now = date("Y-m-d H:i:s");
		$query = self::where("start_at", "<=", $now)
			->where("end_at", ">=", $now);

		if (!empty($tag))  {
			$query->where("tag", "LIKE", "%hot%");
		}

		$query->orderBy("likeCounter", "DESC")
			->skip($skip)
			->take($limit);

		$data = $query->get();
		return $data;
	}

	//----------------------------------------------------------------------------------------
	public static function getOffersWithCategoryOrFilter($category=null, $filterArray=null, $limit=25, $skip=0)  {

		$now = date("Y-m-d H:i:s");
		$query = self::where("start_at", "<=", $now)
			->where("end_at", ">=", $now);

		//  Apply category condition
		if (!empty($category))  {
			$query->where("category", "LIKE", "%$category%");
		}

		//  Apply filter one by one
		if (!empty($filterArray))  {
			$query->where(function ($query) use ($filterArray)  {

				foreach ($filterArray as $filter)  {
					$query->orWhere("filter", "LIKE", "%$filter%");
				}
			});
		}

		$query->orderBy("likeCounter", "DESC")
			->skip($skip)
			->take($limit);

		$data = $query->get();
		return $data;
	}

	//----------------------------------------------------------------------------------------
	public static function getHotOffers($limit=5, $skip=0)  {
		return self::getOffers($limit, $skip, null);
	}

	//----------------------------------------------------------------------------------------
	public static function getRecordsToBeArchived()  {

		$now = date("Y-m-d 00:00:00", strtotime("-30 days"));
		$query = self::where("end_at", "<", $now);

		$data = $query->get();
		return $data;
	}

	//----------------------------------------------------------------------------------------
	public static function getReferralOffer($checkingArray=null, $offerID=0, $referralCount=0)  {

		if (empty($checkingArray))  {return null;}
		if ($referralCount == 0)  {return null;}
		if ($offerID == 0)  {return null;}

		//  10 = None
		//  20 = Internal rules
		//  30 = External webhook rules
		$query = self::whereIn("id", $checkingArray)
			->where("webhook->couponActivationWebhookType", 20)
			->where("webhook->couponActivationReferralOfferID", $offerID)
			->where("webhook->couponActivationReferralCount", "<=", $referralCount);
		$dataArray = $query->get();
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	public static function normalOpen($offerCode)  {

		//  Preview's User Agent:
		//  HangOut: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36 Google (+https://developers.google.com/+/web/snippet/)
		//  WhatsApp: WhatsApp/2.21.110.15 i
		//  LINE: facebookexternalhit/1.1;line-poker/1.0
		//  WeChat: Mozilla/5.0 (Linux; Android 7.0; FRD-AL00 Build/HUAWEIFRD-AL00; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/53.0.2785.49 Mobile MQQBrowser/6.2 TBS/043602 Safari/537.36 MicroMessenger/6.5.16.1120 NetType/WIFI Language/zh_CN
		if (isset($_SERVER["HTTP_USER_AGENT"]))  {

			$userAgent = strtolower($_SERVER["HTTP_USER_AGENT"]);
			if (strpos($userAgent, "micromessenger") != false)  {return;}
			if (strpos($userAgent, "web/snippet") != false)  {return;}
			if (strpos($userAgent, "whatsapp") != false)  {return;}
			if (strpos($userAgent, "facebook") != false)  {return;}
			if (strpos($userAgent, "google") != false)  {return;}
		}

		$offer = self::where("offer_code", $offerCode)->first();
		if (empty($offer))  {return;}

		$json = json_decode($offer->statistic_data, true);
		if (isset($json["open"]) == false)  {$json["open"] = 1;}
		else  {$json["open"] = intval($json["open"])+1;}

		$offer->statistic_data = json_encode($json);
		$offer->save();
	}

	//----------------------------------------------------------------------------------------
	public function getIniArray()  {

		$folderName = $this->offer_name;
		$iniFilePath = public_path() . "/offers/" . $folderName . "/offer.ini";
		$iniArray = parse_ini_file($iniFilePath, true);

		return $iniArray;
	}

	//----------------------------------------------------------------------------------------
	public static function getAllOfferTitle(){
		$titleArray = [];
		$records = self::all();
		if($records->isEmpty()){
			return $titleArray;
		}

		foreach($records as $record)  {
			$titleArray[$record->id] = $record->offer_title;
		}
		return $titleArray;
	}

	public static function getAllOfferTags(){
		$tagArray = [];
		$records = self::all();

		if($records->isEmpty()){
			return $tagArray;
		}

		$index = 0;
		
		foreach($records as $record)  {

			$array = array_map("trim", explode(",", $record->tag));
			if (!$array == false){
				foreach($array as $a){
					if (!empty($a)){
						$tagArray[$index] = $a;
						$index++;
					}
				}
			}
		}

		return array_unique($tagArray);
	}

	//----------------------------------------------------------------------------------------
	public static function getAllOfferName(){
		$nameArray = [];
		$records = self::all();
		if($records->isEmpty()){
			return $nameArray;
		}

		foreach($records as $record)  {
			$nameArray[$record->id] = $record->offer_name;
		}
		return $nameArray;
	}

	//----------------------------------------------------------------------------------------
	public static function getTagsIDByOfferID($id=0){

		$tagArray = [];
		$array = [];
		$index = 0;
		$tagID = 0;

		if ($id == 0){return $tagArray;}

		$offer = self::where("id", $id)->first();

		$array = array_map("trim", explode(",", $offer->tag));
		if (!is_null($array)){
			foreach($array as $a){
				if (!empty($a)){
					$tagID = FosoTag::getIDByName($a);
					$tagArray[$index] = $tagID;
					$index++;
				}
			}
		}


		return array_unique($tagArray);
	}
}
