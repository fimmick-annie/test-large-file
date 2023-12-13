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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Validator;

use App\Models\ChatbotDailyQuestionPool;
use App\Models\ChatbotDailyQuestionReply;
use App\Models\UploadFileLog;
use App\Models\CampaignOffer;
use App\Models\FosoTag;


//========================================================================================
class FOSODailyQuestionController extends Controller  {

	//----------------------------------------------------------------------------------------
	//  Mark: Pages
	//----------------------------------------------------------------------------------------
	public function listPage(Request $request)  {
		return view("foso.daily-question.list");
	}

	//----------------------------------------------------------------------------------------
	public function questionPage(Request $request)  {

		$question = "";
		$labelArray = array();
		$pointArray = array();
		$answerArray = array();
		$nextIDArray = array();
		$answerExpiryAt = "+24 hours";
		$startedAt = date("Y-m-d 00:00:00");
		$endedAt = date("Y-m-d 23:59:59", strtotime("+3 years"));
		$couponID = 0;
		$giftID = 0;
		$point = 20;
		$layer = 1;
		$weight = 100;

		$questionID = $request->question_id;
		if ($questionID == 0)  {

			//  Create a new record
		}  else  {

			//  Load from database
			$dailyQuestion = ChatbotDailyQuestionPool::getQuestionWithID($questionID);
			if ($dailyQuestion == null)  {

				//  Question not found, then create a new one
				$questionID = 0;
			}  else  {

				$questionID = $dailyQuestion->id;
				$question = $dailyQuestion->question;
				$answerExpiryAt = $dailyQuestion->answer_expiry_at;
				$startedAt = $dailyQuestion->started_at;
				$endedAt = $dailyQuestion->ended_at;
				$point = $dailyQuestion->point;
				$couponID = $dailyQuestion->coupon_id;
				$giftID = $dailyQuestion->gift_id;
				$layer = $dailyQuestion->layer;
				$weight = $dailyQuestion->weight;

				$json = json_decode($dailyQuestion->answers, true);
				if ($json != null)  {

					$index = 0;
					$keyArray = array_keys($json);
					foreach ($keyArray as $key)  {

						if (isset($json[$key]["text"]))  {

							//  v2.0 format
							$option = $json[$key]["text"];
							$label = $json[$key]["label"];
							$pointAns = $json[$key]["point"];
							if (isset($json[$key]["next_id"]))  {

								//  v2.1 format
								$nextID = $json[$key]["next_id"];
							}  else  {
								$nextID = 0;
							}

						}  else  {

							//  v1.0 format
							$option = $json[$key];

							$label = "";
							$pointAns = 0;
							$nextID = 0;
						}

						$answerArray[$index] = "$key. $option";
						$labelArray[$index] = $label;
						$pointArray[$index] = intval($pointAns);
						$nextIDArray[$index] = intval($nextID);

						$index++;
					}
				}
			}
		}

		$list = FosoTag::pluck('name')->all();
		$tagArray = array();
		$count = 0;
		foreach($list as $label){
			$data = ['id'=> $label, 'text'=> $label];
			$tagArray[] = $data;
		}
		
		//----------------------------------------------------------------------------------------
		return view('foso.daily-question.details', [
			"answerExpiryAt" => $answerExpiryAt,
			"nextIDArray" => $nextIDArray,
			"answerArray" => $answerArray,
			"pointArray" => $pointArray,
			"labelArray" => $labelArray,
			"questionID" => $questionID,
			"startedAt" => $startedAt,
			"tagArray" => $tagArray,
			"question" => $question,
			"couponID" => $couponID,
			"endedAt" => $endedAt,
			"giftID" => $giftID,
			"weight" => $weight,
			"point" => $point,
			"layer" => $layer,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function reportPage(Request $request)  {
		return view("foso.daily-question.report");
	}

	//----------------------------------------------------------------------------------------
	//  Mark: APIs
	//----------------------------------------------------------------------------------------
	public function listAPI(Request $request)  {
		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$dataArray = array();
		$array = ChatbotDailyQuestionPool::orderBy("id", "DESC")->get();
		foreach ($array as $row)  {

			$id = $row->id;
			$question = $row->question;
			$startedAt = $row->started_at;
			$endedAt = $row->ended_at;
			$point = $row->point;

			$layer = $row->layer;
			$weight = $row->weight;

			$dataArray[] = array($id, $layer, $question, $startedAt, $endedAt, $point, $weight);

		}
		$response["data"] = $dataArray;

		//----------------------------------------------------------------------------------------
		//  Output now
		$response["status"] = 0;
		$response["message"] = "Import successfully";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function questionAPI(Request $request)  {
		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		//----------------------------------------------------------------------------------------
		$dailyQuestion = null;
		$questionID = $request->question_id;
		if ($questionID > 0)  {
			$dailyQuestion = ChatbotDailyQuestionPool::getQuestionWithID($questionID);
		}

		//  New a record
		if ($dailyQuestion == null)  {
			$dailyQuestion = ChatbotDailyQuestionPool::create([]);
		}

		$layer = 1;
		if (isset($request->layer))  {$layer = intval($request->layer);}
	
		//----------------------------------------------------------------------------------------
		//  Prepare answer JSON
		//  65 = ASCII 'A'
		$index = 65;
		$answerDictionary = array();

		$allTags = FOSOTag::pluck('name')->all();

		for ($i=0; $i<8; $i++)  {

			$parameter = "answer".chr($index);
			if (isset($request->$parameter))  {

				$answerText = $request->$parameter;
				$array = explode(".", $answerText);
				
				if (count($array) >= 2)  {

					//  +1 = Full stop character
					$key = trim($array[0]);
					$option = trim(substr($answerText, strlen($array[0])+1));

					$label = "";
					$parameter = "answer".chr($index)."LabelStr";
					if (isset($request->$parameter))  {

						// ---- check array LABEL content
						if (!empty($request->$parameter)){
							$temp = ['label' => str_replace( array(',',' '),'',$request->$parameter),];
							$validator = Validator::make($temp, [
								'label' => 'regex:/^[a-zA-Z0-9\-\_\$]+$/',
							]);
						
							if ( $validator->fails()){
								$response["status"] = -20;
								$response["message"] = "Update stop.\nOnly non-chinese characters, _ , - and $ are accepted in label.\nPlease check anwswer ".chr($index)." label." ;
								return response()->json($response);
							}
						}

						$label = array_map("trim", explode(",", strtolower($request->$parameter)));
						// ---- if new tags, insert to DB
 						foreach ($label as $tag){
							if (!in_array($tag, $allTags)){
								FosoTag::create(['name' => $tag]);
							}
						}
					}

					$point = 0;
					$parameter = "answer".chr($index)."Point";
					if (isset($request->$parameter))  {$point = intval($request->$parameter);}

					$nextID = 0;
					$parameter = "answer".chr($index)."NextID";
					if (isset($request->$parameter))  {$nextID = intval($request->$parameter);}

					$answerDictionary[$key] = array(
						"text" => $option,
						"label" => $label,
						"point" => $point,
						"next_id" => $nextID,
					);
				}
			}
			$index++;
		}
		$answerJSON = json_encode($answerDictionary);

		//----------------------------------------------------------------------------------------
		//  Save or update
		$dailyQuestion->layer = $layer;
		$dailyQuestion->question = $request->question;
		$dailyQuestion->started_at = $request->startedAt;
		$dailyQuestion->ended_at = $request->endedAt;
		$dailyQuestion->answer_expiry_at = $request->answerExpiryAt;
		$dailyQuestion->point = $request->point;
		$dailyQuestion->weight = $request->weight;
		$dailyQuestion->coupon_id = $request->couponID;
		$dailyQuestion->gift_id = $request->giftID;
		$dailyQuestion->answers = $answerJSON;
		$dailyQuestion->save();

		//----------------------------------------------------------------------------------------
		//  Output now
		$response["status"] = 0;
		$response["message"] = "Question #".$dailyQuestion->id." has been updated.";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function reportAPI(Request $request)  {
		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		//  Retrieve records
		$dataArray = array();
		$array = ChatbotDailyQuestionReply::orderBy("id", "DESC")->take(1000)->get();
		foreach ($array as $record)  {

			$id = $record->id;
			$date = $record->date;
			$label = $record->label;
			$answer = $record->answer;
			$mobile = $record->mobile;
			$questionID = $record->question_id;

			$dataArray[] = array($id, $date, $mobile, $questionID, $answer, $label);
		}
		$response["data"] = $dataArray;

		//----------------------------------------------------------------------------------------
		//  Output now
		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}


	public function questionListUploadAPI(Request $request){

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
			
			$file = Storage::disk('local')->get('uploads/'.$log->name);
			$csvfilepath = 'app/uploads/'.$log->name;
			
			if (($handle = fopen(storage_path($csvfilepath), "r") )=== FALSE)  {
				$response["status"] = -1;
				$response["message"] = "Unexpected error...";
				return response()->json($response);
			}

			$row = 0;
			$question = "";
			$oldtextArray = array();
			$oldlabelArray = array();
			
			$finalkey = array();
			$finaltext = array();
			$finallable = array();
			$finalpoint = array();

			$isRightTable = true;

			// handle one question per loop
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE && $isRightTable) {
				
				$row++;
				$a=0;
				//  Skip header row
				if ($row <= 1)  {continue;}

				if (!empty($data[0]) && !empty($data[1]) && !empty($data[3]) && count($data)<=17){
				
					$record = ChatbotDailyQuestionPool::firstOrNew(['id' => ($row-1)]);
					$oldAnswer = json_decode($record->answers, true); 
					
					$record->question= $data[$a]; //

					// read csv filr - new answer array [new answers]
					$newkey = 0;
					$optionIndex = 49; //ASCII '1'
					$dataNum = count($data); // num of new answer option 

					// for ($c=1; $c < $dataNum; $c+=2){
					$c=1;
					
					while(!empty($data[$c])){
						// if (isset($data[$c])){
						$newkeyArray[$newkey] = chr($optionIndex); 
						$newtext[$newkey] = $data[$c];
						
						// ---- check array LABEL content
						if (!empty($data[$c+1])){
							$temp = ['label' => str_replace( array('|',' '),'',$data[$c+1]),];
							$validator = Validator::make($temp, [
								'label' => 'regex:/^[a-zA-Z0-9\-\_\$]+$/',
							]);
						
							if ( $validator->fails()){
								$response["status"] = -20;
								$response["message"] = "Uploading stop.\nOnly non-chinese characters, _ , - and $ are accepted in label.\nPlease check row #".$row ;
								return response()->json($response);
							}
						}
						$newlabelArray[$newkey] = array_map("trim", explode("|", strtolower($data[$c+1])));
						$newpoint[$newkey] = 0;
						$newNextID[$newkey] = 0;
						
						$newkey++;
						$optionIndex++;
						// }
						$c=$c+2;

					}

					//have existed anwser
					if ($oldAnswer != null)  {

						$oldkeyArray = array_keys($oldAnswer); //key array
						$oldDataNum = count($oldkeyArray); // num of old answer option 
						
						// get the data in BD [old data]
						for($b=0; $b < $oldDataNum; $b++){
							$tempKey = $oldkeyArray[$b];
							$oldtextArray[$b] = $oldAnswer[$tempKey]['text'];
							$oldlabelArrays[$b] = $oldAnswer[$tempKey]['label']; //in array format
							$oldpointArray[$b] = $oldAnswer[$tempKey]['point'];

							if (isset($oldAnswer[$tempKey]['next_id'])){  // if next_id is built up before
								$oldNextIDArray[$b] = $oldAnswer[$tempKey]['next_id'];
							}
						}
						
						foreach ($newkeyArray as $key)  {
						// checking key-text exist in textArray
							$finalkey = $key;
							$position = array_search($key, $newkeyArray);

							if (in_array($key, $oldtextArray)){
								// YES: label and point(10) renew
								$oldPosition = array_search($key, $oldtextArray);

								$finaltext = $newtext[$position];
								$finallableArray = $newlabelArray[$position];
								$finalpoint =  $newpoint[$position];
								
								if ($oldNextIDArray[$oldPosition]!=0){
									$finalNextID = $oldNextIDArray[$oldPosition];
								}else{$finalNextID = 0;}

							}else{
								// NO: creat new one option - build a new array
								$finaltext = $newtext[$position];
								$finallableArray = $newlabelArray[$position];
								$finalpoint = $newpoint[$position];
								$finalNextID = 0;
							}

							$answerDictionary[$key] = array(
								"text" => $finaltext,
								"label" => $finallableArray,
								"point" => $finalpoint,
								"next_id" => $finalNextID,
							);
						}
						
					}else{
						//if no answer before (all brand new)
						$num = count($newkeyArray);
						for ($c=0; $c < $num; $c++){

							$answerDictionary[$newkeyArray[$c]] = array(
								"text" => $newtext[$c],
								"label" => $newlabelArray[$c],
								"point" => $newpoint[$c],
								"next_id" => $newNextID[$c],
							);
						}
					}

					$answerJSON = json_encode($answerDictionary);
					$record->answers = $answerJSON;
					
					//----- save other information in default
					$record->layer = 1;
					$record->answer_expiry_at = "+24 hours";
					$record->started_at = date("Y-m-d H:i:s");
					$record->ended_at = date("Y-m-d H:i:s", strtotime('+5 years'));
					$record->point = 20;
					$record->coupon_id = 0;
					$record->gift_id = 0;
					if ($record->weight == null){
						$record->weight = 100;
					}
					$record->save();

					$answerDictionary=[];
					$newkeyArray=[]; 
					$newtext=[];
					$newlabelArray=[];
					$newpoint=[];
					$newNextID=[];
					$oldtextArray=[];
					$oldlabelArrays=[];
					$oldpointArray=[];
					$oldNextIDArray=[];

				}else{

					$isRightTable = false;
					$response["status"] = -10;
					$response["message"] = "Please upload file in right format";
					return response()->json($response);
					
				}
			}

			fclose($handle);
			// dd($isRightTable, $row);			
			Storage::disk('local')->delete('uploads/'.$log->name); //clear the CSV file

			if ($isRightTable != false){

				$oldTotal = ChatbotDailyQuestionPool::all()->count();

				while(($oldTotal+1) > $row){
					$oldRecord = ChatbotDailyQuestionPool::where('id', $row)->first();
					$oldRecord->weight = 0;	
					$oldRecord->save();
					$row++;
				};
			}	
		}

		$response["status"] = 30;
		$response["message"] = "The CSV file of daily question is uploaded";
		
		return response()->json($response);
	}

	public function processGetAllTags(){

		// arrry take all tags from  daily-quesiton
		$questionTagArray = ChatbotDailyQuestionPool::getAllQuestionTag();
		
		// array take all tags from offer
		$offerTagArray = CampaignOffer::getAllOfferTags();
		// combine 
		$result = array_merge($questionTagArray, $offerTagArray);
		$result = array_unique($result);

		// update the foso_tags table
		$new =0;
		foreach($result as $name){
			$record = FosoTag::where('name', $name)->first();
			if (!$record){
				FosoTag::create(['name' => $name]);
				$new++;
			}
		}

		// $index = 1;
		// foreach($result as $name){
		// 	$record = FosoTag::where('id', $index)->first();
		// 	$record->name = $name;
		// 	$index++;
		// }

		// $oldTag = FosoTag::all()->count();
		// while(($oldTag+1) > $index){
		// 	$oldRecord = FosoTag::where('id', $row)->first();
		// 	$oldRecord->delete();
		// 	$index++;
		// };
		Log::info("'New Tags amount:".$new."'");

	}

}
