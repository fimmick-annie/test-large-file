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
use App\Models\Member;

//========================================================================================
class CampaignCoupon extends Model implements Auditable  {

	//  Inform Model it is soft delete instead of real delete
	use SoftDeletes;

	use \OwenIt\Auditing\Auditable;

	//----------------------------------------------------------------------------------------
	//  The attributes that are mass assignable.
	protected $guarded = ['id'];

	//----------------------------------------------------------------------------------------
	public function offer()  {
		return $this->belongsTo("App\Models\CampaignOffer");
	}

	//----------------------------------------------------------------------------------------
	//  Date value only, no time
	public static function getList($fromDate=null, $toDate=null, $offerID=0)  {

		$query = self::query();
		if ($offerID > 0)  {$query->where("parent_offer_id", $offerID);}
		if ($fromDate != null)  {$query->where("expiry_at", ">=", $fromDate." 00:00:00");}
		if ($toDate != null)  {$query->where("start_at", "<=", $toDate." 23:59:59");}

		$dataArray = $query->orderBy("created_at", "desc")->get();
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	//  Date value only, no time
	public static function getListWithCreateAt($fromDate=null, $toDate=null, $offerID=0)  {

		$query = self::query();
		if ($offerID > 0)  {$query->where("parent_offer_id", $offerID);}
		if ($fromDate != null)  {$query->where("created_at", ">=", $fromDate." 00:00:00");}
		if ($toDate != null)  {$query->where("created_at", "<=", $toDate." 23:59:59");}

		$dataArray = $query->orderBy("created_at", "desc")->get();
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	public static function getWithMobile($mobile=null)  {
		$query = self::where("mobile", $mobile);
		$dataArray = $query->get();
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	public static function getUnusedWithMobile($mobile=null)  {
		$query = self::where("mobile", $mobile)
			->whereNull("use_at");
		$dataArray = $query->get();
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	public static function getWithMobileOrReferral($text=null)  {
		$query = self::leftJoin("campaign_offers", "campaign_coupons.offer_id", "=", "campaign_offers.id")
			->where("mobile", "LIKE", "%".$text."%")
			->orWhere("referral_code", "LIKE", "%".$text."%");
		$dataArray = $query->get();
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	public static function getRecordsToBeArchived($limit=1000)  {
		$date = date("Y-m-d H:i:s", strtotime("-30 days"));
		$query = self::where('use_at', '<', $date)
			->whereOr('expiry_at', '<', $date)
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
	public static function referOpen($offerID, $referrerCode=null)  {
		if ($referrerCode == null)  {return;}

		//  Preview's User Agent:
		//  HangOut: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36 Google (+https://developers.google.com/+/web/snippet/)
		//  WhatsApp: WhatsApp/2.21.110.15 i
		//  LINE: facebookexternalhit/1.1;line-poker/1.0
		//  WeChat: Mozilla/5.0 (Linux; Android 7.0; FRD-AL00 Build/HUAWEIFRD-AL00; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/53.0.2785.49 Mobile MQQBrowser/6.2 TBS/043602 Safari/537.36 MicroMessenger/6.5.16.1120 NetType/WIFI Language/zh_CN
		$userAgent = strtolower($_SERVER["HTTP_USER_AGENT"]);
		if (strpos($userAgent, "micromessenger") != false)  {return;}
		if (strpos($userAgent, "web/snippet") != false)  {return;}
		if (strpos($userAgent, "whatsapp") != false)  {return;}
		if (strpos($userAgent, "facebook") != false)  {return;}
		if (strpos($userAgent, "google") != false)  {return;}

		$coupon = self::where("referral_code", $referrerCode)->where("offer_id", $offerID)->first();
		if (empty($coupon))  {return;}

		$json = json_decode($coupon->referral_data, true);
		if (isset($json["open"]) == false)  {$json["open"] = 1;}
		else  {$json["open"] = intval($json["open"])+1;}

		$coupon->referral_data = json_encode($json);
		$coupon->save();
	}

	//----------------------------------------------------------------------------------------
	public static function referSuccess($offerID, $referrerCode=null)  {
		if ($referrerCode == null)  {return null;}

		$coupon = self::where("referral_code", $referrerCode)->where("offer_id", $offerID)->first();
		if (empty($coupon))  {return null;}

		$json = json_decode($coupon->referral_data, true);
		if (isset($json["registration"]))  {$json["registration"]++;}
		else  {$json["registration"] = 1;}

		$coupon->referral_data = json_encode($json);
		$coupon->save();

		$referrerMobile = $coupon->mobile;
		$referrerEmail = $coupon->email;
		$registration = $json["registration"];

		return array(
			"referrerMobile" => $referrerMobile,
			"referrerEmail" => $referrerEmail,
			"registration" => $registration,
			"coupon" => $coupon,
		);
	}

	//----------------------------------------------------------------------------------------
	//  offerIDs = 1, 2, 3
	public static function getCouponByOfferIDs($mobile, $offerIDs=null)  {
		if (empty($mobile))  {return null;}
		if ($offerIDs == null)  {return null;}

		//  Not use str_replace because it cannot handle case like "1, ,2,   3"
		$array = preg_split("/[\s,]+/", $offerIDs);

		$query = self::where("mobile", $mobile)->whereIn("offer_id", $array);
		$dataArray = $query->get();
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	public static function getCouponByReferralCode($mobile, $referralCode)  {
		if (empty($mobile))  {return null;}
		if (empty($referralCode))  {return null;}

		$query = self::where("mobile", $mobile)
			->where("referral_code", $referralCode)
			->orderBy("coupon_order", "asc");

		$data = $query->get()->first();
		return $data;
	}

	//----------------------------------------------------------------------------------------
	public static function getIssuedByOfferID($offerID, $storeCode=null, $startAt=null, $endAt=null)  {
		$query = self::where("offer_id", $offerID);

		if ($storeCode != null)  {
			$query->where("form_data->selectedRedemptionStore", $storeCode);
		}

		if ($startAt != null)  {$query->where("created_at", ">=", $startAt);}
		if ($endAt != null)  {$query->where("created_at", "<=", $endAt);}

		$count = $query->count();
		return $count;
	}

	//----------------------------------------------------------------------------------------
	//  Return a list of who take coupon
	public static function getMobilesWithOfferID($offerID)  {

		$couponArray = self::select("mobile")
			->where("offer_id", $offerID)
			->get();

		return $couponArray;
	} 

	//----------------------------------------------------------------------------------------
	//  Return offer id list
	public static function getSuggestedOffersID($offerID, $usersWhoRedeemItArray)  {

		$now = date("Y-m-d H:i:s");
		$result = DB::table("campaign_coupons")
			->leftJoin("campaign_offers", "campaign_coupons.offer_id", "=", "campaign_offers.id")
			->select("offer_id", "use_at", "statistic_data")
			->whereIn("mobile", $usersWhoRedeemItArray)
			->where("offer_id", "!=", $offerID)
			->where("campaign_offers.start_at", "<=", $now)
			->where("campaign_offers.end_at", ">=", $now)
			->get();

		$scoreDictionary = [];
		foreach($result as $record)  {

			$id = $record->offer_id;
			$score = 1;

			//  Use open count as score
			$statisticData = $record->statistic_data;
			if ($statisticData != null)  {

				$json = json_decode($statisticData, true);
				$openScore = intval($json["open"] ?? "0");
				$score += $openScore;
			}

			if (!is_null($record->use_at))  {$score += 4;}
			if (isset($scoreDictionary[$id]))  {

				//  Existing record of the offer
				$scoreDictionary[$id] += $score;
			}  else  {

				//  First record of the offer
				$scoreDictionary[$id] = $score;
			}
		}

		arsort($scoreDictionary);
		$dictionary = array_slice($scoreDictionary, 0, 5, true);
		$suggestedOfferIDArray = array_keys($dictionary);
		return $suggestedOfferIDArray;
	}


	//----------------------------------------------------------------------------------------
	// 2023.03.14 Kay -- change coupon referral to member referral
// 	public static function referSuccessByMemberInCustomerJourey($offerID, $memberReferralCode=null)  {
// 		if ($memberReferralCode == null)  {return null;}
// 
// 		// use the referrerCode to find back the member
// 		$referrerMember = Member::getMemberByReferralCode($memberReferralCode);
// 		if (is_null($referrerMember))  {return null;}
// 
//         $mobile = $referrerMember->mobile;
//         $coupon = self::where("mobile", $mobile)->where("offer_id", $offerID)->first();
//         if (empty($coupon))  {return null;} 
// 		
// 		$json = json_decode($coupon->referral_data, true);
// 		if (isset($json["registration"]))  {$json["registration"]++;}
// 		else  {$json["registration"] = 1;}
// 
// 		$coupon->referral_data = json_encode($json);
// 		$coupon->save();
// 
// 		$referrerMobile = $coupon->mobile;
// 		$referrerEmail = $coupon->email;
// 		$registration = $json["registration"];
// 
// 		return array(
// 			"referrerMobile" => $referrerMobile,
// 			"referrerEmail" => $referrerEmail,
// 			"registration" => $registration,
// 			"coupon" => $coupon,
// 		);
// 	}

}
