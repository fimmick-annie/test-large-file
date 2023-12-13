<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020.  All rights reserved.
//----------------------------------------------------------------------------------------

namespace App\Http\Controllers;

//----------------------------------------------------------------------------------------
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Session;

use App\Models\CampaignOfferReceiptUpload;
use App\Models\CampaignOfferChannel;
use App\Models\ChannelReceiptSample;
use App\Models\PointTransaction;
use App\Models\FosoActivityLog;
use App\Models\CampaignCoupon;
use App\Models\CampaignOffer;
use App\Models\UploadFileLog;
use App\Models\OfferTag;
use App\Models\Member;
use App\Models\Rating;

use Exception;
use Throwable;
use DateTime;

//========================================================================================
class FOSOToolsController extends Controller  {

	//----------------------------------------------------------------------------------------
	//  Mark: APIs
	//----------------------------------------------------------------------------------------
	public function reassignMachineLearningLabel(Request $request)  {

		// Select offers
		$offerArray = CampaignOffer::getList(null, null);
		foreach ($offerArray as $offer)  {

			$offerID = $offer->id;
			$couponArray = CampaignCoupon::getList(null, null, $offerID);
			foreach ($couponArray as $coupon)  {

				$mobile = $coupon->mobile;

				//  TODO: Apply offers' label to members
				$labels = $offer->ml_labels;
				$memberArray = Member::getList(null, null, $mobile);
				foreach ($memberArray as $member)  {

// 					$

				}
			}
		}
	}

	public function refreshPointAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$memberMoblie = $request->input('mobile');
		$member = Member::where('mobile', $memberMoblie)->first();

		if ($member == null)  {
			$response = array(
				"status" => -10,
				"message" => "No member record found",
			);
			return response()->json($response);
		}

		$id = $member->id;
		// check and re-generate point erase record if any given but expired points not clear before today
		FOSOToolsController::adjustAllRecordForOneMemberUntilNow($id);
	
		$now = date('Y-m-d H:i:s');
		$monthNow = date('m');
		$yearNow = date('Y');

		if ($monthNow <= 6)  {
			// $lastPeriod = ($yearNow-1)."-12-31 23:59:59";
			// $lastPeriod = ($yearNow-1)."-06-30 23:59:59";
			$period1String = $yearNow."-06-30 23:59:59";
			$period2String = $yearNow."-12-31 23:59:59";
		} else {
			// $lastPeriod = $yearNow."-06-30 23:59:59";
			// $lastPeriod = ($yearNow-1)."-12-31 23:59:59";
			$period1String = $yearNow."-12-31 23:59:59";
			$period2String = ($yearNow+1)."-06-30 23:59:59";
		}

		// find the current valid record  -- 2022.01.04
		$lastPeriod = PointTransaction::earliestIssueDate($id);

		$pointNow = PointTransaction::getPointSumByIDandPeriod($id, $lastPeriod, $now);
		$pointPeriod1 = PointTransaction::getPointForPeriod($id, $lastPeriod, $period1String);
		$pointPeriod2 = PointTransaction::getPointForPeriod($id, $period1String, $period2String);
//         $pointNow = PointTransaction::getPointSumByIDAndDate($id, $now);
//         $pointPeriod1 = PointTransaction::getExpiryPoint($id, $period1String);
//         $pointPeriod2 = PointTransaction::getExpiryPoint($id, $period2String);

		// if point1 <0 ?? use point2
		if ( $pointPeriod1 < 0)  {
			$pointPeriod2 += $pointPeriod1;
			$pointPeriod1 = 0 ;
		}
	
		$member->point_balance = $pointNow;         //point_balance: point can be used right now
		$member->period_1_points = $pointPeriod1;   // point 1 : point will be expried before timelime of period 1
		$member->period_2_points = $pointPeriod2;   // point 2 : point will be expried before timelime of period 2
		$member->save();

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Update point of member id #$id with re-calculation", "Point");

		$response["status"] = 0;
		$response["message"] = "Updated";
		return response()->json($response);

	}

	// help to re-calculate :  recount all got but expired point whether is cleared
	public function adjustAllRecordForOneMemberUntilNow($id=0)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$member = Member::where('id', $id)->first();

		if (!$member)  {
			$response["status"] = -10;
			$response["message"] = "The member not found.";
			return $response;
		}

		// ------Extension ----------
		// 1. delete all Balance pointT
		// 2. add back Balance pointT
		$allBalancePT = PointTransaction::where('member_id', $id)->whereIn('transaction_type', ['Balance', 'Point expiry Clear'])->delete();
		$allRecord = PointTransaction::getAllWithoutBalance($id);

		foreach($allRecord as $record){
			if ($record->delta_points < 0){

				$tempPoint = $record->delta_points;
				$pointRecord = PointTransaction::allRecordDuringPeriod($id, $record->valid_at, $record->expiry_at);
				
				foreach ($pointRecord as $point){

					if($tempPoint >= 0){continue;}

					$netPoint = PointTransaction::getPointBalanceWithinPeriod($id, $point->valid_at, $point->expiry_at);
					$startRecord = date("Y-m-d 00:00:00", strtotime("$record->expiry_at +1day"));

					if ($netPoint > 0){

						$result = $tempPoint;
						$tempPoint = 0;

						PointTransaction::create([
							'member_id' => $id,
							'delta_points' => $result,
							'valid_at' => $startRecord,
							'expiry_at' => $point->expiry_at,
							'transaction_type' => 'Balance',
							'description' => json_encode([
								"zh-HK" => "內部計算",
								"en" => "Admin - Balance",
								"extension_point_id" => $record->id,
							])
						]);

					}else if ($tempPoint < (0-$point->delta_points)){

						$result = 0 - $point->delta_points;
						$tempPoint -= $result;
						
						PointTransaction::create([
							'member_id' => $id,
							'delta_points' => $result,
							'valid_at' => $startRecord,
							'expiry_at' => $point->expiry_at,
							'transaction_type' => 'Balance',
							'description' => json_encode([
								"zh-HK" => "內部計算",
								"en" => "Admin - Balance",
								"extension_point_id" => $record->id,
							])
						]);

					}
				}
			}
		}

		// --------- recount -------------
		// take all record from valid the account until today
		$now = date("Y-m-d H:i:s");
		$allGetPointRecordUntilNow = PointTransaction::getAllRecordfromStartUnitilDate($id, $now);
		$oldestValidAt = $allGetPointRecordUntilNow->min('valid_at');
	
		foreach($allGetPointRecordUntilNow as $record)  {

			$netPoint = PointTransaction::getPointBalanceWithinPeriod($id, $record->valid_at, $record->expiry_at);

			//  create record to erase the not used but expried point
			if ($netPoint > 0)  {
				$netPoint = 0 - $netPoint;

				$expiryDateStart = substr($record->expiry_at, 0, 10)." 00:00:00";
				if ($expiryDateStart < $record->valid_at)  {$expiryDateStart = $record->valid_at;}

				PointTransaction::create([
					'member_id' => $id,
					'delta_points' => $netPoint,
					'valid_at' => $expiryDateStart,
					'expiry_at' => $record->expiry_at,
					'transaction_type' => 'Point expiry Clear',
					'description' => json_encode([
						"zh-HK" => "積分到期",
						"en" => "Erase the expired point",
						"Corresponding_id" => $record->id,
					])
				]);
			}
		}

		$response["status"] = 0;
		$response["message"] = "Done";
		return $response;
	}

	public function handleAjustmentPointAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		//  Check if all required parameters are available
		$parameterArray = array(
			"point_adjust", "validDate", "validTime","adjust_type", "description_adjust_en", "description_adjust_zh", 
			// "expiryDate", "expiryTime", 
		);

		$status = 0;
		foreach ($parameterArray as $parameter)  {
			$status--;
			if (null === $request->input($parameter))  {
				$response["status"] = $status;
				$response["message"] = "Parameter $parameter not found...";
				return response()->json($response);
			}
		}

		$memberMoblie = $request->input('mobile');
		$member = Member::where('mobile', $memberMoblie)->first();

		$id = $member->id;
		$point = $request->input("point_adjust");
		$validDateTime = $request->input("validDate")." ".$request->input("validTime").":00";
	
		if ($point>0)  {
			$expiryDateTime = PointTransaction::regularExpiry(date("Y-m-d"));
			if (!empty($request->input("expiryDate")))  {$expiryDateTime = $request->input("expiryDate")." 23:59:59";}
			if (!empty($request->input("expiryTime")))  {$expiryDateTime = substr($expiryDateTime,0,11).$request->input("expiryTime");}
		} else {
			$expiryDateTime = $request->input("expiryDate")." 23:59:59";
		}

		if ($point>100000)  {
			$response["status"] = -30;
			$response["message"] = "Exceed limit of delta point";
			return response()->json($response);
		}

		// requested by DP -- not allow to import expired point to member
		if ($expiryDateTime < date("Y-m-d H:i:s"))  {
			$response["status"] = -20;
			$response["message"] = "Expired point record is not allowed to import";
			return response()->json($response);
		}

		//checking if the expiry and valid date correct(may be a period needed)
		if (strtotime($expiryDateTime) <= strtotime($validDateTime))  {
			$response["status"] = -25;
			$response["message"] = "Invalid time period setting, please check";
			return response()->json($response);
		}

		$adjustmentType = $request->input("adjust_type");
		$description["en"] = $request->input("description_adjust_en");
		$description["zh-HK"] = $request->input("description_adjust_zh");
		$descriptionJSON = json_encode($description, true);

		// add record 
		PointTransaction::addAdjustmentPoint($id, $point, $validDateTime, $expiryDateTime, $adjustmentType, $descriptionJSON);

		// re-calculate the point blance of member
		FOSOToolsController::processPointRecalculateAllMember($id);

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Add Point transaction record of $point points to member id #$id ", "Point");

		$response["status"] = 0;
		$response["message"] = "Done: A new reocrd of Point Transaction added.";
		return response()->json($response);

	}

	// --- Cron Job: daily for justify point to all member
	public function processPointRecalculateAllMember($id=0)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		// Take all existing member IDs
		// handle all member id or just one id point justify
		if($id == null || $id == '0')  {
			$listMemberIDs = Member::pluck('id'); //Excludes soft deleted
		} else {
			$listMemberIDs[] = $id;
		}

		//  lastPeriod...now...period1String...period2String
		$now = date('Y-m-d H:i:s');
		$monthNow = date('m');
		$yearNow = date('Y');

		if ($monthNow <= 6)  {
			// $lastPeriod = ($yearNow-1)."-12-31 23:59:59";
			// $lastPeriod = ($yearNow-1)."-06-30 23:59:59";
			$period1String = $yearNow."-06-30 23:59:59";
			$period2String = $yearNow."-12-31 23:59:59";
		} else {
			// $lastPeriod = $yearNow."-06-30 23:59:59";
			// $lastPeriod = ($yearNow-1)."-12-31 23:59:59";
			$period1String = $yearNow."-12-31 23:59:59";
			$period2String = ($yearNow+1)."-06-30 23:59:59";
		}

		// TO DO - for loop: 
		foreach ($listMemberIDs as $memberid) {

			$member = Member::where('id', $memberid)->first();

			// find the current valid record   -- 2022.01.04
			$lastPeriod = PointTransaction::earliestIssueDate($memberid);

			$pointNow = PointTransaction::getPointSumByIDandPeriod($memberid, $lastPeriod, $now);
			$pointPeriod1 = PointTransaction::getPointForPeriod($memberid, $lastPeriod, $period1String);
			$pointPeriod2 = PointTransaction::getPointForPeriod($memberid, $period1String, $period2String);
// 			$pointNow = PointTransaction::getPointSumByIDAndDate($id, $now);
// 			$pointPeriod1 = PointTransaction::getExpiryPoint($id, $period1String);
// 			$pointPeriod2 = PointTransaction::getExpiryPoint($id, $period2String);

			// if point1 <0 ?? use point2
			if ( $pointPeriod1 < 0)  {
				$pointPeriod2 += $pointPeriod1;
				$pointPeriod1 = 0 ;
			}

			$member->point_balance = $pointNow;
			$member->period_1_points = $pointPeriod1;
			$member->period_2_points = $pointPeriod2;
			$member->save();
		}

		$response["status"] = 0;
		$response["message"] = "Done: finished updating point record for all member";
		return $response;

	}

	// --- Cron Job: daily calculate any point expiry yesterday
	public function processEraseYesterdayExpiry($id=0)  {
	
		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		if($id == null || $id == '0')  {
			$listMemberIDs = Member::pluck('id'); //Excludes soft deleted
		} else {
			$listMemberIDs[] = $id;
		}
	
		$yesterdayStart = date("Y-m-d 00:00:00", strtotime("yesterday"));
		$yesterdayEnd = date("Y-m-d 23:59:59", strtotime("yesterday"));

		// for loop memeber in list: 
		foreach ($listMemberIDs as $memberid) {

			// $member = Member::where('id', $memberid)->first();
			$pointExpiryYesterdayRecord = PointTransaction::isExpiryPointOnDate($memberid, $yesterdayEnd);
			if(!$pointExpiryYesterdayRecord->isEmpty())  {

				// --- find the oldest valid date of the pointExpiryYesterday
				// $oldestValidAtRecord = PointTransaction::getExpiryPointWithOldestValidAt($memberid, $yesterdayEnd);
				$oldestValidAt = $pointExpiryYesterdayRecord->min('valid_at');

				// get all point between the oldest valid date - yesterday
				// for mark a negative point record to balance (offset the expired remain point)
				$netPoint = PointTransaction::getPointBalanceWithinPeriod($memberid, $oldestValidAt, $yesterdayEnd);

				if ($netPoint > 0)  {
					$netPoint = 0 - $netPoint;

					$newRecord = PointTransaction::create([
									'member_id' => $memberid,
									'delta_points' => $netPoint,
									'valid_at' => $yesterdayStart,
									'expiry_at' => $yesterdayEnd,
									'transaction_type' => 'Point expiry Clear',
									'description' => json_encode([
										"zh-HK" => "積分到期",
										"en" => "Erase the expired point",
									])
								]);
				}
			}
		}

		$response["status"] = 0;
		$response["message"] = "Done: finished updating point record for all member";
		return $response;

	}

	public function adjustmentPointCSVuplodsAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$log = new CampaignFileUploadController;
		$result = $log->upload($request);

		if (strtolower($result['status']) == "ok" && isset($result['uniqid'])) {
			$log = UploadFileLog::where('uniqid', $result['uniqid'])
				->first();

			//  get the temp thumbnail with the random name
			$file = Storage::disk('local')->get('uploads/'.$log->name);
			$csvfilepath = 'app/uploads/'.$log->name;
		
			if (($handle = fopen(storage_path($csvfilepath), "r") )=== FALSE)  {
			// if (($handle = fopen(('redemptions/'.$csvfilepath), "r")) === FALSE)  {
				$response["status"] = -1;
				$response["message"] = "Unexpected error...";
				return response()->json($response);
			}

			// read every row to point transaction
			$row = 0;
			$errMsg  = "";
			$errorCount = 0;
			$importCount = 0;
			$errorRow = array();
			$memberIncluded = array();

			$successRow = array();

			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

				$row++;
				$data = array_map('trim', $data);
				//  Skip header row
				if ($row <= 1 || empty($data))  {continue;}

				$mobile = $data[0];
				// check the moblie if a member or create
				if(preg_match('/^\+[0-9]{11}$/', $mobile))  {
					$member = Member::getMemberByMobile($mobile);
					if (is_null($member))  {$member = Member::createMember($mobile);}
				} else {
					$errMsg  = " || Wrong mobile number format >> ".$mobile;
					$errorRow[] = "row ".$row.$errMsg;
					$errorCount++;
					continue;
				}

				$point = 0;
				$validDateTime = "";
				$expiryDateTime = "";
				if (isset($data[1]))  {$point = $data[1];}
				if (isset($data[2]))  {$validDateTime = $data[2];}
				// check the point and valid date
				if (is_numeric($point) && FOSOToolsController::checkIsDatetime($validDateTime))  {

					if ($point>0)  {
						$expiryDateTime = PointTransaction::regularExpiry($validDateTime);
					
						if ($expiryDateTime < date("Y-m-d H:i:s"))  {
							$errMsg  = " || Valid date is out of period";
							$errorRow[] = "row ".$row.$errMsg;
							$errorCount++;
							continue;
						}
					} else {
						$expiryDateTime = substr($validDateTime,0,10)." 23:59:59";
					}

					if ($point>100000)  {
						$errMsg  = " || Exceed limit of delta point [<100000]  >> ". $point;
						$errorRow[] = "row ".$row.$errMsg;
						$errorCount++;
						continue;
					}

					$transaction_type = "Admin";
					$description["en"] = "special mission";
					$description["zh-HK"] = "特別任務";
					if (isset($data[3]))  {$transaction_type = $data[3];}
					if (isset($data[4]))  {$description["en"] = $data[4];}
					if (isset($data[5]))  {$description["zh-HK"] = $data[5];}
					$descriptionJSON = json_encode($description, true);
				
					$successRow[] = array($member->id, $point, $validDateTime, $expiryDateTime, $transaction_type, $descriptionJSON);

					// PointTransaction::addAdjustmentPoint($member->id, $point, $validDateTime, $expiryDateTime, $transaction_type, $descriptionJSON);
					$importCount++;

					// store the member have point adjustment
					if (!in_array($member->id, $memberIncluded))  {$memberIncluded[]=$member->id;}
			
				} else {
					if (!is_numeric($point))  {$errMsg = " || Point is not numeric >> ".$point;}
					else { $errMsg = " || Wrong valid date or time format >> ".$validDateTime;}

					$errorRow[] = "row ".$row.$errMsg;
					$errorCount++;
					continue;
				}
			}

			//  Activity log
			$user = Auth::user();
			$message = "";
			if ($errorCount == 0)  {
				foreach ($successRow as $row)  {PointTransaction::addAdjustmentPoint($row[0], $row[1], $row[2], $row[3], $row[4], $row[5]);}
				foreach ($memberIncluded as $id)  {FOSOToolsController::processPointRecalculateAllMember($id);}
				$message = "The csv file is uploaded.\n\nSuccessful import: $importCount records\n\n";

				FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Add Point transaction of $importCount record with uploading CSV file [$log->name]", "Point");
			} else {
				$message = "Upload failed.\n\nNeed correct: $errorCount records\n".implode("\n", $errorRow);
				FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Fail to upload with CSV file [$log->name]", "Point");
			}
		
			fclose($handle);
			Storage::disk('local')->delete('uploads/'.$log->name); //clear the CSV file

		}

		$response["status"] = 10;
		$response["message"] = $message ;
	
		return response()->json($response);
	}

	public function checkIsDatetime($dateTime)  {
	
		// Adjust RegExp to handle various date formats that you may be expecting
		// eg: /[\d]{4,}-[\d]{2,}-[\d]{2,}/ - matches date like 1970-01-01
		// eg: /[\d]{2,}:[\d]{2,}:[\d]{2,}/ - matches time like 12:00:00
		// combine like (!preg_match($eg1, $dateTime) && !preg_match($eg2, $dateTime))
		// for wider coverage/variability in date/time string inputs
 
		if (!preg_match('/[\d]{4,}-[\d]{2,}-[\d]{2,}(.*)[\d]{2,}:[\d]{2,}:[\d]{2,}/', $dateTime))  {
			return false;
		}

		try  {
			new DateTime($dateTime);
			return true;
		}  catch (Exception $e)  {
			return false;
		}
	 }

	//----------------------------------------------------------------------------------------
	 public function reportPointPage(Request $request)  {

		if (null !== $request->input("from"))  {$fromDate = $request->input("from");}
		else  {$fromDate = date("Y-m-d", strtotime("-30 days"));}

		if (null !== $request->input("to"))  {$toDate = $request->input("to");}
		else  {$toDate = date("Y-m-d", strtotime("+30 days"));}

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Read FOSO point report page from [$fromDate] to [$toDate]", "Point");

		return view('foso.reporting.point', [
			"fromDate" => $fromDate,
			"toDate" => $toDate,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function reportPointListAPI(Request $request)  {
	
		$toDate = null;
		$fromDate = null;
		if (null !== $request->input("to"))  {$toDate = $request->input("to");}
		if (null !== $request->input("from"))  {$fromDate = $request->input("from");}

		$array = PointTransaction::getList($fromDate, $toDate);
	
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Visit FOSO point report page from [$fromDate] to [$toDate] ");
	
		$dataArray = array();
		foreach ($array as $row)  {

			$createdAt = $row["created_at"]->toDateTimeString();
			$updatedAt = $row["updated_at"]->toDateTimeString();
		
			$des= $row->description;
			$zhDescription = "";
			$enDescription = "";
			$desJSON = json_decode($des, true); 
			if (isset($desJSON["zh-HK"]))  { $zhDescription = $desJSON["zh-HK"]; }
			if (isset($desJSON["en"]))  { $enDescription = $desJSON["en"]; }
	
		
			$mobile = "Not found";
			if (!is_null($row->member))  { $mobile = $row->member->mobile; }

			$dataArray[] = array(
				$row->id, $createdAt, $updatedAt, $mobile,
				$row->delta_points, $row->valid_at, $row->expiry_at,
				$row->transaction_type, 
				$zhDescription, $enDescription, 
			);

		}

		//  Output now
		$response["data"] = $dataArray;
		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	//--- 2022.10.24 Kay 
	public function activityLogListPage(Request $request) {

		if (null !== $request->input("from"))  {$fromDate = $request->input("from");}
		else  {$fromDate = date("Y-m-d", strtotime("-1 month"));}

		if (null !== $request->input("to"))  {$toDate = $request->input("to");}
		else  {$toDate = date("Y-m-d");}

		return view('foso.activitylog.list', [
			"fromDate" => $fromDate,
			"toDate" => $toDate,
		]);

	}

	//----------------------------------------------------------------------------------------
	public function activityLogListAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$toDate = null;
		$fromDate = null;
		if (null !== $request->input("to"))  {$toDate = $request->input("to");}
		if (null !== $request->input("from"))  {$fromDate = $request->input("from");}

		$array = FosoActivityLog::getList($fromDate, $toDate);

		$dataArray = array();
		foreach ($array as $row)  {

			$createdAt = $row["created_at"]->toDateTimeString();

			$dataArray[] = array(
				$row->id, $createdAt, $row->type, $row->username, $row->remark,$row->url,
			);
		}
		// dd($dataArray);
		//  Output now
		$response["data"] = $dataArray;
		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	//  receipt upload handling Pages -- Kay 2022.11.21
	//----------------------------------------------------------------------------------------
	public function receiptHandleListPage(Request $request)  {

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Visit FOSO offer receipt listing page", "Receipt");

		if (null !== $request->input("from"))  {$fromDate = $request->input("from");}
		else  {$fromDate = date("Y-m-d", strtotime("-30 days"));}

		if (null !== $request->input("to"))  {$toDate = $request->input("to");}
		else  {$toDate = date("Y-m-d", strtotime("+30 days"));}
	
		return view('foso.receipthandle.list', [
			"fromDate" => $fromDate,
			"toDate" => $toDate,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function receiptHandleListAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$toDate = null;
		$fromDate = null;
		if (null !== $request->input("to"))  {$toDate = $request->input("to");}
		if (null !== $request->input("from"))  {$fromDate = $request->input("from");}

		$array = CampaignOfferReceiptUpload::getList($fromDate, $toDate);

		$dataArray = array();
		foreach ($array as $row)  {

			$createdAt = $row["created_at"]->toDateTimeString();
			$updatedAt = $row["updated_at"]->toDateTimeString();
			$mobile = $row->member->mobile;
			$offerTitle = $row->campaignOffer->offer_title;

			$reason = "";
			if (strlen($row["reject_reason"])>0)  {
				$reasonJSON = json_decode($row["reject_reason"],true);
				$reason = $reasonJSON["zh-HK"];
			}

			$dataArray[] = array(
				$row->id, $createdAt, $updatedAt,
				$row->offer_id,
				$offerTitle , 
				$row->member_id, 
				$mobile, 
				$row->purchase_date, 
				$row->purchase_amount, $row->merchant_caption_id, $row->invoice_number, 
				$row->receipt_path, $row->status, $row->handle_date, 
				$reason, $row->handler,

				//  Must be the last one
				route("foso.receipthandle.settings.html", ["id"=>$row->id]),
			);
		}
		// dd($dataArray);
		//  Output now
		$response["data"] = $dataArray;
		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function receiptSettingsPage(Request $request)  {

		//  Activity log
		$user = Auth::user();
		$id = $request->id;
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Visit FOSO upload receipt id[$id] settings page", "Receipt");

		$receipt = CampaignOfferReceiptUpload::getReceiptDetail($id);

		// TODO --- receipt if in "checked" status , go confirm page
		if ($receipt->status !="pending")  {
			return redirect()->route("foso.receipthandle.settings.confirm.html", ["id" => $id]);
		}

		$channel = CampaignOfferChannel::where('offer_id', $receipt->offer_id)->first();
		$point = 10;
		if (empty($channel->receipt_approval_point))  {$point = $channel->receipt_approval_point;}
		else {$point = $receipt->approve_point;}
	
		$channelDetail = CampaignOfferChannel::getChannelListByOfferID($receipt->offer_id);

		//----------------------------------------------------------------------------------------
		return view('foso.receipthandle.settings', [
			"id" => $id,
			"receipt" => $receipt,
			"approvePoint" => $point,
			"channelDetail" => $channelDetail,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function saveReceiptAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$id = $request->input("id");
		$receiptReocrd = CampaignOfferReceiptUpload::where('id', $id)->first();
		$user = Auth::user();
		$receiptReocrd->merchant_caption_id = $request->input("channel");
		$receiptReocrd->purchase_date = $request->input("purchaseAt");
		$receiptReocrd->purchase_amount = intval($request->input("amount"));
		$receiptReocrd->invoice_number = $request->input("receiptNumber");
		$receiptReocrd->approve_point = $request->input("approvePoint");
		$receiptReocrd->save();

		//----------------------------------------------------------------------------------------
		//  Activity log
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Save upload receipt [$id] ......", "Receipt");

		//----------------------------------------------------------------------------------------
		//  Finally
		$response["status"] = 0;
		$response['message'] = "Update and save successfully";
		return response($response, 200);
	}

	//----------------------------------------------------------------------------------------
	public function handleReceiptAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$id = $request->input("id");
		$receiptReocrd = CampaignOfferReceiptUpload::where('id', $id)->first();
		$user = Auth::user();
		$receiptReocrd->merchant_caption_id = $request->input("channel");
		$receiptReocrd->purchase_date = $request->input("purchaseAt");
		$receiptReocrd->purchase_amount = intval($request->input("amount"));
		$receiptReocrd->invoice_number = $request->input("receiptNumber");
		$receiptReocrd->approve_point = $request->input("approvePoint");
		$receiptReocrd->status = "checked";
		$receiptReocrd->save();

		//----------------------------------------------------------------------------------------
		//  Activity log
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Save to handle receipt [$id] ......", "Receipt");

		//----------------------------------------------------------------------------------------
		//  Finally
		$response["status"] = 10;
		$response['message'] = "Save and process to approve";
		return response($response, 200);
	}

	//----------------------------------------------------------------------------------------
	public function comfirmSettingsReceiptPage(Request $request)  {

		//  Activity log
		$user = Auth::user();
		$id = $request->id;
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Visit FOSO upload receipt [$id] ckecked settings page", "Receipt");

		$receipt = CampaignOfferReceiptUpload::getReceiptDetail($id);
		$createDate = "";
		$updateDate = "";
		$updateTime = "";

		if ($receipt)  {
			$createDate = substr($receipt->created_at,0,10);
			$updateDate = substr($receipt->updated_at,0,10);
			$updateTime = substr($receipt->updated_at,-8,8);
		}

		$reason = "";
		if($receipt->status == 'rejected' && strlen($receipt->reject_reason)>0)  {
			$reasonJSON = json_decode($receipt->reject_reason, true);
			$reason = $reasonJSON['zh-HK'];
		}

		//----------------------------------------------------------------------------------------
		return view('foso.receipthandle.confirm', [
			"id" => $id,
			"receipt" => $receipt,
			"createDate" => $createDate,
			"updateDate" => $updateDate,
			"updateTime" => $updateTime,
			"reason" => $reason,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function reeditReceiptAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$id = $request->input("id");
		$receiptReocrd = CampaignOfferReceiptUpload::getReceiptDetail($id);
		$user = Auth::user();

		$receiptReocrd->status = "pending";
		$receiptReocrd->save();

		//----------------------------------------------------------------------------------------
		//  Activity log
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Back to edit mode to receipt [$id] ......", "Receipt");

		//----------------------------------------------------------------------------------------
		//  Finally
		$response["status"] = 0;
		$response['message'] = "Back to correct";
		return response($response, 200);
	}

	//----------------------------------------------------------------------------------------
	public function finalStatusReceiptAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$id = $request->input("id");
		$final = $request->input("finalStatus");
		$user = Auth::user();
		$receiptReocrd = CampaignOfferReceiptUpload::getReceiptDetail($id);
		$receiptReocrd->handle_date = date("Y-m-d H:i:s");
		$receiptReocrd->handler = $user->name;

		if ($final == "approve")  {

			$receiptReocrd->status = "approved";
		
			// add point to memebr
			$member = Member::where('id', $receiptReocrd->member_id)->first();
			$member->addReceiptUploadApprovelPoint($receiptReocrd->approve_point);
	
			//  Activity log
			FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Approve receipt [$id] ...", "Receipt");

		} else {

			$receiptReocrd->status = "rejected";
			$reasonValue = $request->input("reason");
			$reason['value']= $reasonValue;

			switch($reasonValue)  {

				case 'repeat':
					$reason['zh-HK']="重複上載";
					break;

				case 'unqualified_shopping':
					$reason['zh-HK']="不合資格消費";
					break;
			
				case 'expired':
					$reason['zh-HK']="過期";
					break;

				case 'image_problem':
					$reason['zh-HK']="圖片模糊";
					break;

				default:
					$reason['zh-HK']="不符合資格";
					break;
			}

			$receiptReocrd->reject_reason = json_encode($reason, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

			//  Activity log
			FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Reject receipt [$id] ...", "Receipt");

		}
		$receiptReocrd->save();

		//----------------------------------------------------------------------------------------
		//  Finally
		$response["status"] = 0;
		$response['message'] = "Rejected successfully";
		return response($response, 200);
	}

	//----------------------------------------------------------------------------------------
	//  Channel list Pages -- Kay 2022.11.22
	//----------------------------------------------------------------------------------------
	public function channelSampleListPage(Request $request)  {

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Visit FOSO offer Channel sample listing page", "channel-sample");

		if (null !== $request->input("from"))  {$fromDate = $request->input("from");}
		else  {$fromDate = date("Y-m-d", strtotime("-30 days"));}

		if (null !== $request->input("to"))  {$toDate = $request->input("to");}
		else  {$toDate = date("Y-m-d", strtotime("+30 days"));}

		return view('foso.channel.list', [
			"fromDate" => $fromDate,
			"toDate" => $toDate,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function channelSampleListAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$toDate = null;
		$fromDate = null;
		if (null !== $request->input("to"))  {$toDate = $request->input("to");}
		if (null !== $request->input("from"))  {$fromDate = $request->input("from");}

		$array = ChannelReceiptSample::getList($fromDate, $toDate);

		$dataArray = array();
		foreach ($array as $row)  {

			$createdAt = $row["created_at"]->toDateTimeString();
			$updatedAt = $row["updated_at"]->toDateTimeString();

			$dataArray[] = array(
				$row->id, $createdAt, $updatedAt,
				$row->start_at, $row->end_at,
				$row->channel, 
				$row->receipt_sample_url, 

				//  Must be the last one
				route("foso.channel.settings.html", ["id"=>$row->id]),
			);
		}

		//  Output now
		$response["data"] = $dataArray;
		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function channelSampleSettingsPage(Request $request)  {

		//  Activity log
		$user = Auth::user();
		$id = $request->id;
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Visit FOSO channel receipt sample [$id] settings page", "channel-sample");

		$sample = ChannelReceiptSample::getChannelSample($id);
		$startDate = ""; 
		$startTime = "";
		$endDate = ""; 
		$endTime = ""; 

		//  Offer start date and end date, default last for 30 days
		if ($id == '0')  {

			$createDateTime = date("Y-m-d H:i:s");
			$startDate = date("Y-m-d");
			$startTime = "00:00:00";
			$endDate = date("Y-m-d", strtotime("+1 year"));
			$endTime = "23:59:59";

		} else {

			$createDateTime = $sample->created_at;
			if (!is_null($sample->start_at))  {
				$startDate = substr($sample->start_at, 0, 10);
				$startTime = substr($sample->start_at, -8, 5);
			}
			if (!is_null($sample->end_at))  {
				$endDate = substr($sample->end_at, 0, 10);
				$endTime = substr($sample->end_at, -8, 5);
			}
			$title = $sample->channel;
		}

		//----------------------------------------------------------------------------------------
		return view('foso.channel.settings', [
			"id" => $id,
			"createDateTime" => $createDateTime,
			"startDate" => $startDate,  
			"startTime" => $startTime, 
			"endDate" => $endDate, 
			"endTime" => $endTime, 
			"sample" => $sample,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function receiptSampleUploadAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$id = $request->id;

		//----------------------------------------------------------------------------------------
		$log = new CampaignFileUploadController;
		$result = $log->upload($request);

		if (strtolower($result['status']) == "ok" && isset($result['uniqid'])) {
			$log = UploadFileLog::where('uniqid', $result['uniqid'])
				->first();

			//  get the temp thumbnail with the random name and put file to /uploads
			$file = Storage::disk('local')->get('uploads/'.$log->name);

			$response["status"] = 10;
			$response["message"] = "New receipt put on the uploads, with name [$log->name]";

			// --- clear the useless file long than 30 days in disk('local')
			collect(Storage::disk('local')->listContents('uploads', true))
				->each(function($fileClear) {

					if ($fileClear['timestamp'] < now()->subDays(30)->getTimestamp()) {  // normal : clear within 30 days
					// if ($fileClear['timestamp'] < now()->subMinute(15)->getTimestamp()) {  //testing : clear 15 min
						Storage::disk('local')->delete($fileClear['path']);
					}
			});
		}
		return response()->json($result);
	}

	//----------------------------------------------------------------------------------------
	public function saveReceiptSampleSettingsAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);
	
		//  Check if all required parameters are available
		$parameterArray = array(
			"id", "created_at", 
			"channel", "saveType"
		);

		$status = 0;
		foreach ($parameterArray as $parameter)  {
			$status--;
			if (null === $request->input($parameter))  {
				$response["status"] = $status;
				$response["message"] = "Parameter $parameter not found...";
				return response()->json($response);
			}
		}

		$sample = ChannelReceiptSample::firstOrCreate(['id' => $request->id]);
		$user = Auth::user();
		if ($sample && empty($sample->channel))  {
			FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Create channel receipt sample id #[$sample->id] settings page", "channel-sample");
		} else {
			FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Update channel receipt sample id #[$sample->id] settings page", "channel-sample");
		}

		//----------------------------------------------------------------------------------------
		//  Save 
		$starttime = $request->input("startTime");
		if (empty($request->input("startTime")))  {$starttime = " 00:00";}
		if (empty($request->input("startDate")))  {
			$sample->start_at = null;
		} else {
			$sample->start_at = $request->input("startDate")." ".$starttime.":00";
		}

		$endtime = $request->input("endTime");
		if (empty($request->input("endTime")))  {$endtime = " 23:59";}
		if (empty($request->input("endDate")))  {
			$sample->end_at = null;
		} else {
			$sample->end_at = $sample->end_at = $request->input("endDate")." ".$endtime.":59";
		}

		$sample->channel = $request->input("channel");
		$newType = $request->input("saveType");
		$nowURL = $request->input("urlInput");
		$oldURL = $sample->receipt_sample_url;
		$path = $request->input("path");

		if ($newType == "local" && !empty($path))  {

			$randomStr = FOSOMainController::generateRandomString(8);
			$ext = pathinfo($path, PATHINFO_EXTENSION);
			$newfilename = $sample->id."_".$randomStr.".".$ext;

			$newfile = Storage::disk('local')->get('uploads/'.$path);
			if (!is_null($newfile))  {
				if(!File::isDirectory(storage_path("app/uploads/foso/receipt-sample/"))) {
					//creates directory if not exists
					File::makeDirectory(storage_path("app/uploads/foso/receipt-sample/"), 0777, true, true);
				}
				Storage::disk('public')->put('/foso/receipt-sample/'.$newfilename, $newfile);
				Storage::disk('local')->delete('uploads/'.$path);
			}

			$sample->receipt_sample_url = 'storage/foso/receipt-sample/'.$newfilename;

		} else if ($nowURL != $oldURL )  {

			$sample->receipt_sample_url = $nowURL;
		
		}

		$sample->save_type = $newType;
		$sample->save();

		//----------------------------------------------------------------------------------------
		//  Output now
		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	//  Recover the missing referral code in memeber
	public function regenerateMemberReferralCode()  {
	
		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$count = 1000;
		while ($count > 0)  {

			dump("Count: $count...");
			$count--;

			$memberArray = Member::where('referral_code', '')->orWhereNull('referral_code')->take(1000)->get();
			if (count($memberArray) == 0)  {break;}

			foreach ($memberArray as $member)  {

				$mobile = $member->mobile;

				//  It rely on MD5 and should be unique
				$referralCode = Member::getReferralCode($mobile);
				$member->referral_code = $referralCode;
				$member->save();

			}
		}

		//  Output now
		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}

}