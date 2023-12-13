<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020-2022.  All rights reserved.
//----------------------------------------------------------------------------------------

namespace App\Http\Controllers;

//----------------------------------------------------------------------------------------
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

use App\Models\ChatbotDailyQuestionReply;
use App\Models\ChatbotDailyQuestionPool;
use App\Models\Member;

//========================================================================================
class DailyQuestionController extends Controller  {

	//----------------------------------------------------------------------------------------
	//  Return:
	//    media = Image URL for reply message
	//    message = Reply message
	//    messageType = Type or name of reply message, used in message queue
	//    chatbotData = State data that save to database
	//    canContinue = Continue process next node or branch?
	//    canTerminate = Current branch cannot handle?
	function process($chatbotData, $userInfo, $incomingMessage, $requestDictionary)  {

		$outputDictionary = array(
			"media" => "",
			"message" => "",
			"messageType" => "",
			"chatbotData" => $chatbotData,
			"canTerminate" => false,
			"canContinue" => true,
		);

		$mobile = $userInfo["mobile"];
//NotUseAnymore		$memberID = Member::getMemberIDWithMobile($mobile);
		$lowerIncomingMessage = strtolower($incomingMessage);

		Log::debug(__FUNCTION__." incomingMessage=".$incomingMessage);
		Log::debug(__FUNCTION__." chatbotData=".json_encode($chatbotData));

		//  Is it daily question reply?
		if (isset($chatbotData["currentDailyQuestionID"]) == false
			|| $chatbotData["currentDailyQuestionID"] == 0)  {

			//  No, there is no current daily question, see if trigger daily question
			if ($lowerIncomingMessage == "daily_question")  {

				//  Daily question only play once per day
				$reply = ChatbotDailyQuestionReply::where("mobile", $mobile)
					->where("date", date("Y-m-d"))->first();
				Log::debug(__FUNCTION__." reply=".json_encode($reply));
				if ($reply != null)  {

					//  Already answered today
					$outputDictionary["message"] = __("messages.DAILYQUESTION_ANSWERED", []);

					$chatbotData["currentDailyQuestionID"] = 0;
					$outputDictionary["chatbotData"] = $chatbotData;

					$outputDictionary["incomingMessage"] = "";
					$outputDictionary["canTerminate"] = true;
					$outputDictionary["canContinue"] = false;
					return $outputDictionary;
				}

				//  Pick a daily question
				$member = Member::createMember($mobile);
				$question = ChatbotDailyQuestionPool::pickQuestion($member->id);
				if ($question == null)  {

					//  All question have been answered
					$chatbotData["currentDailyQuestionID"] = 0;
					$outputDictionary["chatbotData"] = $chatbotData;

					//  Pass to next controller
					return $outputDictionary;
				}

				$questionText = $question->question;

				$answerDictionary = json_decode($question->answers, true);
				$answerText = implode("\n", array_map(function($key, $json)  {
					if (isset($json["text"]) == false)  {return $key.". ".$json;}
					return $key.". ".$json["text"];
				}, array_keys($answerDictionary), array_values($answerDictionary)));

				$outputDictionary["message"] = $questionText."\n\n".$answerText;

				$chatbotData["currentDailyQuestionID"] = $question->id;
				$chatbotData["currentDailyQuestionExpiryAt"] = date("Y-m-d H:i:s", strtotime($question->answer_expiry_at));
				$outputDictionary["chatbotData"] = $chatbotData;

				$outputDictionary["incomingMessage"] = "";
				$outputDictionary["canTerminate"] = true;
				$outputDictionary["canContinue"] = false;
				return $outputDictionary;
			}

			//  No current daily question and not to start a daily question
			//  Pass to next controller
			$outputDictionary["canTerminate"] = true;
			return $outputDictionary;
		}

		//----------------------------------------------------------------------------------------
		//  It should be answering a daily question
		$currentDailyQuestionID = $chatbotData["currentDailyQuestionID"];
		$question = ChatbotDailyQuestionPool::getQuestionWithID($currentDailyQuestionID);
		if ($question == null)  {

			//  This should be error, then skip it
			return $outputDictionary;
		}
		
		//  Check if valid reply
		$answersJSON = json_decode($question->answers, true);
		if (isset($answersJSON[$lowerIncomingMessage]) == false)  {

			//  Not a valid reply, no such option
			$outputDictionary["message"] = __("messages.DAILYQUESTION_REPLY_INVALID", []);
			$outputDictionary["incomingMessage"] = "";
			$outputDictionary["canTerminate"] = true;
			$outputDictionary["canContinue"] = false;
			return $outputDictionary;
		}
		$optionJSON = $answersJSON[$lowerIncomingMessage];
		$answerText = $optionJSON["text"] ?? $optionJSON;
		$answerLabel = $optionJSON["label"] ?? [];
		$nextID = $optionJSON["next_id"] ?? 0;

		//  Just for safe, in case answerLabel is not an array
		if ($answerLabel == "")  {$answerLabel = [];}

		//----------------------------------------------------------------------------------------
		//  OK, it is a valid reply, save it and add reward
		$point = $question->point;

		$reply = ChatbotDailyQuestionReply::firstOrNew([
			"question_id" => $currentDailyQuestionID,
			"date" => date("Y-m-d"),
			"mobile" => $mobile,
		]);
		$reply->question_id = $currentDailyQuestionID;
		$reply->answer = $incomingMessage.".".$answerText;
		$reply->labels = implode(",", $answerLabel);
		$reply->earned_points = $point;
		$reply->save();
		
		//  Add point
		$member = Member::getMemberByMobile($mobile);
		if ($member == null)  {$member = Member::createMember($mobile);}
		if ($member != null)  {$member->addDailyQuestionPoint($point);}
		
		//  TODO: Add coupon

		//  TODO: Add gift

		//----------------------------------------------------------------------------------------
		//  Call segment for answer labels
		$array = array(
			"userId" => $mobile,
			"traits" => array(
				"mobile" => $mobile,
				"phone" => $mobile,
			),
			"timestamp" => date("Y-m-d H:i:s"),
		);
		// if (empty($aid) == false)  {$array["anonymousId"] = $aid;}

		// //  Machine learning label
		if ($reply->labels != null)  {

			$labelArray = explode(",", $reply->labels);
			$labelArray = array_map("trim", $labelArray);
			$array["traits"]["description"] = $reply->labels;
			$array["traits"]["dailyquestion_".$currentDailyQuestionID."_labels"] = $labelArray;
		}

		$data = json_encode($array);
		$couponChatbotController = new CouponChatbotController();
		$couponChatbotController->callSegmentIdentify($data);

		//  See if it has follow up question
		if ($nextID == 0)  {
			$chatbotData["currentDailyQuestionID"] = 0;
			$outputDictionary["chatbotData"] = $chatbotData;
			$outputDictionary["message"] = __("messages.DAILYQUESTION_REPLY_DONE", ["point"=>$point]);
			$outputDictionary["incomingMessage"] = "";
			$outputDictionary["canTerminate"] = true;
			$outputDictionary["canContinue"] = false;
			return $outputDictionary;
		}

		//----------------------------------------------------------------------------------------
		//  Yes, has follow up question
		$question = ChatbotDailyQuestionPool::getQuestionWithID($nextID);
		if ($question == null)  {
			$chatbotData["currentDailyQuestionID"] = 0;
			$outputDictionary["chatbotData"] = $chatbotData;
			$outputDictionary["message"] = __("messages.DAILYQUESTION_REPLY_DONE", ["point"=>$point]);
			$outputDictionary["incomingMessage"] = "";
			$outputDictionary["canTerminate"] = true;
			$outputDictionary["canContinue"] = false;
			return $outputDictionary;
		}

		$questionText = $question->question;

		$answerDictionary = json_decode($question->answers, true);
		$answerText = implode("\n", array_map(function($key, $json)  {
			return $key.". ".$json["text"];
		}, array_keys($answerDictionary), array_values($answerDictionary)));

		$outputDictionary["message"] = $questionText."\n\n".$answerText;

		$chatbotData["currentDailyQuestionID"] = $nextID;
		$outputDictionary["chatbotData"] = $chatbotData;

		$outputDictionary["incomingMessage"] = "";
		$outputDictionary["canTerminate"] = true;
		$outputDictionary["canContinue"] = false;
		return $outputDictionary;
	}

	//----------------------------------------------------------------------------------------
	function import()  {

		$line = 0;
		$point = intval(env("DAILY_QUESTION_POINT", "10"));
		$file = "20220314_daily_question.tsv";
		$filePath = storage_path("app/".$file);
		$handle = fopen($filePath, "r");
		while (($data = fgetcsv($handle, 1000, "\t")) !== false)  {

			$line++;

			//  0 = Question
			//  1 = Option A
			//  2 = Option B
			//  3 = Option C (Optional)
			//  4 = Option D (Optional)
			$count = count($data);
			if ($count < 3)  {
				echo("\nSkipping line #$line...");
				continue;
			}

			$question = $data[0];

			//  At least 2 options
			$array = explode("|", $data[1]);
			$answerText1 = $array[0];
// 			$answerLabel1 = $array[1];
			$answerLabel1 = "";

			$array = explode("|", $data[2]);
			$answerText2 = $array[0];
// 			$answerLabel2 = $array[1];
			$answerLabel2 = "";

			$answerArray = array(
				"1" => array("text"=>$answerText1, "label"=>$answerLabel1, "point"=>0),
				"2" => array("text"=>$answerText2, "label"=>$answerLabel2, "point"=>0),
			);

			$index = 3;
			for ($i=0; $i<5; $i++)  {
				if (isset($data[3+$i]))  {

					$array = explode("|", $data[3+$i]);
					$answerText = $array[0];
// 					$answerLabel = $array[1];
					$answerLabel = "";

					$answerArray["$index"] = array(
						"text" => $answerText,
						"label" => $answerLabel,
						"point" => 0,
					);
					$index++;
				}
			}
			$answers = json_encode($answerArray);

			$question = ChatbotDailyQuestionPool::firstOrNew([
				"question" => $question,
			]);
			$question->answers = $answers;
			$question->started_at = date("Y-m-d 00:00:00");
			$question->ended_at = date("Y-m-d 23:59:59", strtotime("+3 years"));
			$question->answer_expiry_at = "+24 hours";
			$question->point = $point;
			$question->save();
		}
	}

}
