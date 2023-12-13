<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020-2022.  All rights reserved.
//----------------------------------------------------------------------------------------

namespace App\Models;

//----------------------------------------------------------------------------------------
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use DateTime;

//========================================================================================
class PointTransaction extends Model  {

	//  Inform Model it is soft delete instead of real delete
	use SoftDeletes;

	//----------------------------------------------------------------------------------------
	//  The attributes that are not mass assignable.
	protected $guarded = ['id'];

	public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }

	//----------------------------------------------------------------------------------------
	public static $TYPE_REFERRAL = "referral";
	public static $TYPE_ISSUE_POINT_JOURNEY = 'journey';
	public static $TYPE_REDEMPTION = "redemption";
	public static $TYPE_OFFER_HUNTING = "Offer_hunting";
	public static $TYPE_DAILY_QUESTION = "daily_question";
	public static $TYPE_OFFER_RECEIPT = "Offer_receipt";

	//----------------------------------------------------------------------------------------
	public static function createRecord($dictionary)  {

		if ($dictionary == null)  {return false;}

		//  Mandatory fields
		if (isset($dictionary["member_id"]) == false)  {return false;}
		if (isset($dictionary["delta_points"]) == false)  {return false;}
		if (isset($dictionary["transaction_type"]) == false)  {return false;}

// 		$memberID = $dictionary["member_id"];
// 		$deltaPoints = $dictionary["delta_points"];
// 		$transactionType = $dictionary["transaction_type"];
//
// 		$record = self::firstOrNew([
// 			"member_id" => $memberID,
// 			"delta_points" => $deltaPoints,
// 			"transaction_type" => $transactionType,
// 		]);

		$record = self::firstOrNew($dictionary);
		return true;
	}

	//----------------------------------------------------------------------------------------
	//  Mark - Helper functions
	//----------------------------------------------------------------------------------------
	public static function addReferreePoint($memberID, $point=0)  {

		$expiryDate = self::regularExpiry(date("Y-m-d H:i:s"));

		$point = PointTransaction::create([
			"member_id" => $memberID,
			"delta_points" => $point,
			"valid_at" => date("Y-m-d H:i:s"),
			"expiry_at" => $expiryDate,
			"transaction_type" => PointTransaction::$TYPE_REFERRAL,
			"description" => json_encode([
				"zh-HK" => "參加者獎勵",
				"en" => "Referree bonus",
			])
		]);
	}

	//----------------------------------------------------------------------------------------
	public static function addReferralPoint($memberID, $point=0)  {

		$expiryDate = self::regularExpiry(date("Y-m-d H:i:s"));

		$point = PointTransaction::create([
			"member_id" => $memberID,
			"delta_points" => $point,
			"valid_at" => date("Y-m-d H:i:s"),
			"expiry_at" => $expiryDate,
			"transaction_type" => PointTransaction::$TYPE_REFERRAL,
			"description" => json_encode([
				"zh-HK" => "成功推介獎勵",
				"en" => "Referral success",
			])
		]);
	}

	//----------------------------------------------------------------------------------------
	public static function addDailyQuestionPoint($memberID, $point=0)  {

		$expiryDate = self::regularExpiry(date("Y-m-d H:i:s"));
		
		$point = PointTransaction::create([
			"member_id" => $memberID,
			"delta_points" => $point,
			"valid_at" => date("Y-m-d H:i:s"),
			"expiry_at" => $expiryDate,
			"transaction_type" => PointTransaction::$TYPE_DAILY_QUESTION,
			"description" => json_encode([
				"zh-HK" => "參加每日獎勵",
				"en" => "Answering daily question",
			])
		]);
	}

	//------------------------------------------------------------------------------------------
	public static function addIssuePoint($memberId, $point, $descHK, $descEN="Issue Point"){

		$expiryDate = self::regularExpiry(date("Y-m-d H:i:s"));

        $transaction = PointTransaction::create([
            "member_id" => $memberId,
            "delta_points" => $point,
			"valid_at" => date("Y-m-d H:i:s"),
			"expiry_at" => $expiryDate,
            "transaction_type" => PointTransaction::$TYPE_ISSUE_POINT_JOURNEY,
            "description" => json_encode([
                "zh-HK" => $descHK,
                "en" => $descEN,
            ])
        ]);
        return $transaction;
    }


	//------------------------------------------------------------------------------------------
	public static function addOfferHuntingPoint($memberID, $point=0)  {

		$expiryDate = self::regularExpiry(date("Y-m-d H:i:s"));

		$point = PointTransaction::create([
			"member_id" => $memberID,
			"delta_points" => $point,
			"transaction_type" => PointTransaction::$TYPE_OFFER_HUNTING,
			"valid_at" => date("Y-m-d H:i:s"),
			"expiry_at" => $expiryDate,
			"description" => json_encode([
				"zh-HK" => "參加蜜探報料",
				"en" => "Successful Offer hunting",
			])
		]);
	}

	//------------------------------------------------------------------------------------------
	public static function withdrawOfferHuntingPoint($memberID, $point=0)  {

		$point = PointTransaction::create([
			"member_id" => $memberID,
			"delta_points" => $point,
			"transaction_type" => PointTransaction::$TYPE_OFFER_HUNTING,
			"valid_at" => date("Y-m-d 00:00:00"),
			"expiry_at" => date("Y-m-d 23:59:59"),
			"description" => json_encode([
				"zh-HK" => "分數調整",
				"en" => "Point adjustment",
			])
		]);

	}

	public static function addAdjustmentPoint($memberID, $point, $validDateTime, $expiryDateTime, $adjustmentType, $description)  {

        $point = PointTransaction::create([
            "member_id" => $memberID,
            "delta_points" => $point,
            "valid_at" => $validDateTime,
			"expiry_at" => $expiryDateTime,
			"transaction_type" => $adjustmentType,
			"description" => $description
        ]);
    }



	//------------------------------------------------------------------------------------------
	//-------for member referral function--------2022.08.17 Kay
	// public static function addReferreeOfferTakingPoint($memberID, $point=0)  {

	// 	$point = PointTransaction::create([
	// 		"member_id" => $memberID,
	// 		"delta_points" => $point,
	// 		"transaction_type" => PointTransaction::$TYPE_REFERRAL,
	// 		"description" => json_encode([
	// 			"zh-HK" => "參加者獎勵",
	// 			"en" => "Referree bonus with offer",
	// 		])
	// 	]);
	// }

	//------------------------------------------------------------------------------------------
	public static function addReferreePointByMemberReferral($memberID, $point=0)  {

		$expiryDate = self::regularExpiry(date("Y-m-d H:i:s"));

		$point = PointTransaction::create([
			"member_id" => $memberID,
			"delta_points" => $point,
			"transaction_type" => PointTransaction::$TYPE_REFERRAL,
			"valid_at" => date("Y-m-d H:i:s"),
			"expiry_at" => $expiryDate,
			"description" => json_encode([
				"zh-HK" => "參加者獎勵",
				"en" => "Referree bonus",
			])
		]);
	}

	//------------------------------------------------------------------------------------------
	public static function addReferralPoinByMemberReferral($memberID, $point=0)  {

		$expiryDate = self::regularExpiry(date("Y-m-d H:i:s"));

		$point = PointTransaction::create([
			"member_id" => $memberID,
			"delta_points" => $point,
			"transaction_type" => PointTransaction::$TYPE_REFERRAL,
			"valid_at" => date("Y-m-d H:i:s"),
			"expiry_at" => $expiryDate,
			"description" => json_encode([
				"zh-HK" => "成功推薦朋友",
				"en" => "Referral success",
			])
		]);
	}


	//------------------------------------------------------------------------------------------
    //-------get point transaction history--------2022.06.22 Kay
    public static function getPointTransactionByMemberID($memberID, $ptcolumns=['*']){

        $record = self::where('member_id', $memberID)
					->orderBy('created_at', 'DESC')
                    ->get($ptcolumns);
                    
        return $record;
    }

	// get point sum : get all records with period overlap the period of start and end totally or partilally, also inculding record of expirey_at is null
	// The point balance on the endTimeLine
	public static function getPointSumByIDandPeriod($id, $startTimeLine, $endTimeLine){

        $point = self::where('member_id', $id)
                    ->where('valid_at','<=', $endTimeLine)
                    ->where(function ($query) use ($startTimeLine){
                        $query->where('expiry_at','>', $startTimeLine)
                        ->orWhereNull('expiry_at');
                    })
                    ->sum('delta_points');  
        return $point;
    }

	// get all records: the records of the same expiry date($date)
	public static function isExpiryPointOnDate($id, $date){

		$pointRecord = self::where('member_id', $id)
			->where('expiry_at','=', $date)
			->where('delta_points','>', 0)
			->get();

		// if ($pointRecord->isEmpty()){return false;}

		// return true;
		return $pointRecord;
	}


    public static function getPointTransactionByMobile($mobile){

        $record = self::where('mobile', $mobile)
                    ->get();
                    
        return $record;
    }

    // get point sum : all record with period inclulding the date ($timeLine)
    public static function getPointSumByIDAndDate($id, $timeLine){

        $point = self::where('member_id', $id)
                    ->where('valid_at','<=', $timeLine)
                    ->where('expiry_at','>=', $timeLine)
                    ->sum('delta_points');  

        return $point;
    }

	// get one record : get the record with oldest valid date in the records of the same expiry date($date)
    public static function getExpiryPointWithOldestValidAt($id, $date){

        $oldestValidAtRecord = self::where('member_id', $id)
            ->where('expiry_at','=', $date)
            ->where('delta_points','>', 0)
            ->orderBy('valid_at', 'ASC')
            ->first();

        return $oldestValidAtRecord;

    }

	// get point sum : all records of whole period of the point transaction within the period of start and end 
	public static function getPointBalanceWithinPeriod($id, $startTimeLine, $endTimeLine){

		$point = self::where('member_id', $id)
			->whereNotNull('expiry_at')
			->where('expiry_at','<=', $endTimeLine)
			->where('valid_at','>=', $startTimeLine)
			->where('transaction_type','!=', 'Balance')
			->sum('delta_points');	
		return $point;
	}

	// get the point sum: the expired date within the period of start and end
	// count all point expired in the period 1 or 2
	public static function getPointForPeriod($id, $startTimeLine, $endTimeLine){

		$point = self::where('member_id', $id)
			->whereNotNull('expiry_at')
			->where('expiry_at','>', $startTimeLine)
			->where('expiry_at','<=', $endTimeLine)
			->sum('delta_points');	
		return $point;
	}

	// get all getting point records: from creat account date to certain date
	public static function getAllRecordfromStartUnitilDate($id, $dateTime){

		$record = self::where('member_id', $id)
			->whereNotNull('expiry_at')
			->where('expiry_at','<', $dateTime)
			->where('delta_points','>', 0)
			->orderBy('expiry_at', 'ASC')
			->orderBy('valid_at', 'ASC')
			->get();
		return $record;
	}

	public static function regularExpiry($dateStr){

        $nowYear  = intval(substr($dateStr, 0, 4));
        $nowMonth = intval(substr($dateStr, 5, 2));
        
        if ($nowMonth<=6){
            $expiryDate = $nowYear."-12-31 23:59:59";
        }else{
            $expiryDate = ($nowYear+1)."-06-30 23:59:59";
        }
        return $expiryDate;
    }

	//--------------For FOSO -------------------------------------------------------------------
	public static function getList($fromDate = null, $toDate = null)  {
		
		$query = self::query()
			->with(['member' => function ($q) {
				$q->select('id','mobile');
			}]);

		if ($fromDate != null)  {
			$query->where("expiry_at", ">=", $fromDate . " 00:00:00");
		}
		if ($toDate != null)  {
			$query->where("valid_at", "<=", $toDate . " 23:59:59");
		}
		
		$dataArray = $query->orderBy("id", "desc")->get();
		
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	public static function earliestIssueDate($id){

		$now = date("y-m-h H:i:s");
		$period = "";

		$pointRecord = self::where('member_id', $id)
			->where("expiry_at", ">=", $now)
			->where("valid_at", "<=", $now)
			->where('delta_points','>', 0)
			->orderBy('valid_at', 'ASC')
            ->first();

		if (!is_null($pointRecord)){
			$year  = intval(substr($pointRecord->valid_at, 0, 4));
			$month = intval(substr($pointRecord->valid_at, 5, 2));
		}else{
			$year  = intval(substr($now, 0, 4));
			$month = intval(substr($now, 5, 2));
		}

		if ($month <= 6){
			$period = ($year-1)."-12-31 23:59:59";
		}else{
			$period = $year."-06-30 23:59:59";
		}

		return $period;
	}

	//----------------------------------------------------------------------------------------
	public static function addReceiptUploadApprovelPoint($memberID, $point=10)  {

		$expiryDate = self::regularExpiry(date("Y-m-d H:i:s"));

		$point = PointTransaction::create([
			"member_id" => $memberID,
			"delta_points" => $point,
			"transaction_type" => PointTransaction::$TYPE_OFFER_RECEIPT,
			"valid_at" => date("Y-m-d H:i:s"),
			"expiry_at" => $expiryDate,
			"description" => json_encode([
				"zh-HK" => "成功上傳收據",
				"en" => "receipt approved",
			])
		]);
	}

	public static function getAllWithoutBalance($id=0){

		if ($id == 0 || $id == null){
			return null;
		}

		$record = self::where('member_id', $id)
			->where('transaction_type','!=', 'Balance')
			->orderBy('expiry_at', 'ASC')
			->get();

		return $record;

	}

	public static function allRecordDuringPeriod($id=0, $startTimeLine, $endTimeLine ){
		
		$record = self::where('member_id', $id)
				->where('valid_at','<=', $startTimeLine)
				->where('expiry_at','>=', $endTimeLine)
				->where('delta_points','>', 0)
				->orderBy('expiry_at', 'ASC')
				->orderBy('valid_at', 'ASC')
				->get();
		
		return $record;

	}

	public static function getExpiryPoint($id, $dateTime){

        $point = self::where('member_id', $id)
                    ->where('expiry_at' , $dateTime)
                    ->sum('delta_points');  
        return $point;
    }

}
