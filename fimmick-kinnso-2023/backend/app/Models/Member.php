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
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Models\PointTransaction;
use App\Models\CampaignOffer;

//========================================================================================
class Member extends Model implements Auditable  {

	//  Inform Model it is soft delete instead of real delete
	use SoftDeletes;

	use \OwenIt\Auditing\Auditable;

	//----------------------------------------------------------------------------------------
	//  The attributes that are mass assignable.
	protected $guarded = ['id'];

	public function redemptionHistories()
    {
        return $this->hasMany(RedemptionHistory::class, 'member_id', 'id');
    }

	//----------------------------------------------------------------------------------------
	//  Date value only, no time
	public static function getList($fromDate=null, $toDate=null, $mobile=null)  {

		$query = self::query();
		if ($fromDate != null)  {$query->where("updated_at", ">=", $fromDate." 00:00:00");}
		if ($toDate != null)  {$query->where("updated_at", "<=", $toDate." 23:59:59");}

		if ($mobile != null)  {$query->where("mobile", "like", "%".$mobile."%");}

		//  Limit 1000 due to lack of memory on production server
// 		$dataArray = $query->orderBy("updated_at", "desc")->get();
		$dataArray = $query->orderBy("updated_at", "desc")->limit(1000)->get();
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	public static function createMember($mobile)  {

		//  +85225152218 => f0946f5b8978d37523e8d35e361ee86b
		$md5 = md5($mobile);

		//  It rely on MD5 and should be unique
		$referralCode = Member::getReferralCode($mobile);

		$record = Member::firstOrNew(array("member_id" => $md5));
		if ($record->id == 0)  {

			$record->referral_code = $referralCode;
			$record->mobile = $mobile;
			$record->save();
		}
		return $record;
	}

	//----------------------------------------------------------------------------------------
	public static function getMemberIDWithMobile($mobile)  {
		$memberID = md5($mobile);
		return $memberID;
	}

	//----------------------------------------------------------------------------------------
	public static function getMemberByMobile($mobile)  {

		//  Return single record;
		$query = self::query();
		$record = $query->where('mobile', $mobile)->first();
		return $record;
	}

	//----------------------------------------------------------------------------------------
	public static function getMemberByReferralCode($referralCode)  {

		//  Return single record;
		$query = self::query();
		$record = $query->where('referral_code', $referralCode)->first();
		return $record;
	}

	//----------------------------------------------------------------------------------------
	public static function getMemberById($id, $columns=['*'])  {

		//  Return single record;
		$record = self::where('id', $id)->first($columns);
		return $record;
	}

	//----------------------------------------------------------------------------------------
	public static function updateMember($mobile, $dataArray)  {

		$md5 = md5($mobile);

		$query = self::query();
// 		$record = $query->where('mobile', $mobile)->first();
		$record = $query->where('member_id', $md5)->first();

		$record->username = $dataArray['username'];
		$record->optout_at = $dataArray['optout_at'];
		$record->mute_until = $dataArray['mute_until'];
		$record->mute_data = $dataArray['mute_data'];
		$record->offer_involved = $dataArray['offer_involved'];
		$record->save();
		return $record;
	}

	//----------------------------------------------------------------------------------------
	public static function optOut($mobile)  {

		//  +85225152218 => f0946f5b8978d37523e8d35e361ee86b
		$md5 = md5($mobile);

		$record = Member::firstOrNew(array("member_id" => $md5));
		if ($record->optout_at != null)  {return false;}

		$now = date("Y-m-d H:i:s");

		$record->optout_at = $now;
		$record->save();
		return true;
	}

	//----------------------------------------------------------------------------------------
	public static function isOptOut($mobile)  {

		//  +85225152218 => f0946f5b8978d37523e8d35e361ee86b
		$md5 = md5($mobile);

		$record = Member::firstOrNew(array("member_id" => $md5));
		return ($record->optout_at != null);
	}

	//----------------------------------------------------------------------------------------
	public static function muteUntil($mobile, $date)  {

		//  +85225152218 => f0946f5b8978d37523e8d35e361ee86b
		$md5 = md5($mobile);

		$record = Member::firstOrNew(array("member_id" => $md5));
		$record->mute_until = $date;
		$record->save();
	}

	//----------------------------------------------------------------------------------------
	//  Return:
	//  true = Newly added
	//  false = Already exists
	public static function involvedOffer($mobile, $offerID)  {

		//  +85225152218 => f0946f5b8978d37523e8d35e361ee86b
		$md5 = md5($mobile);

//TODO: Generate a referral code and check non-exists

		$offerKey = "offer-".$offerID;
		$record = Member::firstOrNew(array("member_id" => $md5));

		$jsonString = $record->offer_involved;
		$json = json_decode($jsonString, true);
		if (isset($json[$offerKey]))  {return false;}

		$json[$offerKey] = date("Y-m-d H:i:s");
		$record->offer_involved = json_encode($json);
		$record->mobile = $mobile;
	//TODO: Referral code
		$record->save();
		return true;
	}

	//----------------------------------------------------------------------------------------
	public static function unOptOut($mobile)  {

		$record = self::getMemberByMobile($mobile);
		if ($record == null)  {return $record;}

		$record->optout_at = null;
		$record->save();
		return $record;
		// clear optout
	}

	//----------------------------------------------------------------------------------------
	public static function unMute($mobile)  {

		$record = self::getMemberByMobile($mobile);
		if ($record == null)  {return $record;}

		$record->mute_until = null;
		$record->save();
		return $record;
	}

	//----------------------------------------------------------------------------------------
	public static function deductPoints($id, $totalPoints, $period1Points, $period2Points)  {
		$affectedRows = self::where('id', $id)
							->whereRaw('point_balance - ? >= 0', [$totalPoints])
							->whereRaw('period_1_points - ? >= 0', [$period1Points])
							->whereRaw('period_2_points - ? >= 0', [$period2Points])
							->update([
								'point_balance' => DB::raw('point_balance - '.$totalPoints),
								'period_1_points' => DB::raw('period_1_points - '.$period1Points),
								'period_2_points' => DB::raw('period_2_points - '.$period2Points),
							]);
		return $affectedRows;
	}

	//----------------------------------------------------------------------------------------
	//  Mark - Helper functions
	//----------------------------------------------------------------------------------------
	public static function getReferralCode($mobile)  {

		$count = 0;
		do{

			$md5 = md5($mobile);
			$count++;

			//  Convert MD5 HEX string to decimal value
			$value = 0;
			$array = str_split($md5, 4);
			foreach ($array as $bytes)  {

				$value <<= 16;
				$value |= hexdec($bytes);
			}

			//  2023.02.09 Pacess
			//  Somehow value is negative, change it to positive
			if ($value < 0)  {$value = -$value;}
			//  2023.02.09 End

			//  Convert decimal value to Base-62
			$mapping = [
				"0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
				"A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M",
				"N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
				"a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m",
				"n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z",
			];
			$loop = 0;
			$base62 = "";
			while ($loop < 100 && $value > 0)  {

				//  Make sure no forever loop
				$loop++;

				$index = $value%62;
				$value = intval($value/62);

				$char = $mapping[$index];
				$base62 = $char.$base62;
			}

			// Kay 2022.08.15
			// isUniqueCode check the code whether is no same code in DB and is not empty
			$isUniqueCode = self::checkReferralCodeUnique($base62);
			// If the code is used or not success to gen code in this time, add . to mobile string and then calculate again to get new md5 result 
			$mobile = $mobile.".";

		}while($isUniqueCode == false && $count<10);

		return $base62;
	}

	//----------------------------------------------------------------------------------------
	public static function checkReferralCodeUnique($code)  {

		$member = self::where('referral_code', $code)->first();

		if(!empty($code) && $member == null)  {return true;}

		return false;
	}

	//----------------------------------------------------------------------------------------
	//  Mark - Instance functions
	//----------------------------------------------------------------------------------------
	public function addReferreePoint($point=100)  {

		$this->point_balance += $point;
		$this->period_2_points += $point;
		$this->save();

		//  Adding transaction record
		PointTransaction::addReferreePoint($this->id, $point);
	}

	//----------------------------------------------------------------------------------------
	public function addReferralPoint($point=100)  {

		$this->point_balance += $point;
		$this->period_2_points += $point;
		$this->save();

		//  Adding transaction record
		PointTransaction::addReferralPoint($this->id, $point);
	}

	//----------------------------------------------------------------------------------------
	public function addDailyQuestionPoint($point=100)  {

		$this->point_balance += $point;
		$this->period_2_points += $point;
		$this->save();

		//  Adding transaction record
		PointTransaction::addDailyQuestionPoint($this->id, $point);
	}

	//----------------------------------------------------------------------------------------
	public function addIssuePoint($point, $desc)  {
		$this->point_balance += intval($point);
		$this->period_2_points += intval($point);
		$this->save();

		//  Adding transaction record
		$transaction = PointTransaction::addIssuePoint($this->id, $point, $desc);
		return $transaction;
	}

	//----------------------------------------------------------------------------------------
	public function addOfferHuntingPoint($point=50)  {

		$this->point_balance += $point;
		$this->period_2_points += $point;
		$this->save();

		//  Adding transaction record
		PointTransaction::addOfferHuntingPoint($this->id, $point);
	}

	//----------------------------------------------------------------------------------------
	public function withdrawOfferHuntingPoint($point=-50)  {

		$this->point_balance += $point;
		$this->save();

		//  Adding transaction record
		PointTransaction::withdrawOfferHuntingPoint($this->id, $point);
	}

	//----------------------------------------------------------------------------------------
	public function addReferreePointByMemberReferral($point=30)  {

		$this->point_balance += $point;
		$this->period_2_points += $point;
		$this->save();

		//  Adding transaction record
		PointTransaction::addReferreePointByMemberReferral($this->id, $point);
	}

	//----------------------------------------------------------------------------------------
	public function addReferralPoinByMemberReferral($point=30)  {

		$this->point_balance += $point;
		$this->period_2_points += $point;
		$this->save();

		//  Adding transaction record
		PointTransaction::addReferreePoint($this->id, $point);
	}

	//----------------------------------------------------------------------------------------
	public static function checkReferralCodeByMemberID($id)  {

		$member = self::where('id', $id)->first();

		return $member->referral_code;
	}

	//----------------------------------------------------------------------------------------
	public static function checkIfNewMember($mobile)  {

		$member = self::where('mobile', $mobile)->first();

		if(is_null($member))  {
			return true;
		}

		$pointRecord = PointTransaction::where('member_id', $member->id)->first();
		$point = $member->point_balance + $member->period_1_points + $member->period_2_points ;
		if (is_null($pointRecord) && $point == 0)  {
			return true;
		}
		
		return false;
	}

	//----------------------------------------------------------------------------------------
	public static function getOfferInvolvedListByID($id)  {

		$member = self::where('id', $id)->first();

		$offers = json_decode($member->offer_involved, true);
		
		if(empty($offers))  {return null;}
		$offersKey = array_keys($offers);

		$offersDictionary = array();

		foreach ($offersKey as $str)  {
			$temp = explode("-", $str);
			
			if($temp[0]=="offer")  {
				$tempChannel = CampaignOfferChannel::checkValidChannel(intval($temp[1]));
				if (!is_null($tempChannel))  {
					$offer = CampaignOffer::where('id',intval($temp[1]))->first();
					$startDate = $offer->start_at;
					// select offer start date or channel start date to limit the issue date
					if (!empty($tempChannel->start_at) && $tempChannel->start_at > $startDate )  {
						$startDate = $tempChannel->start_at;
					} 
					$offersDictionary[] = array("id"=>$offer->id, "title" => $offer->offer_title, "start_at" => $startDate );
				}
			}
		}

		return $offersDictionary ;
	}


	//----------------------------------------------------------------------------------------
	public function addReceiptUploadApprovelPoint($point=50)  {

		$this->point_balance += $point;
		$this->period_2_points += $point;
		$this->save();

		//  Adding transaction record
		PointTransaction::addReceiptUploadApprovelPoint($this->id, $point);
	}

}
