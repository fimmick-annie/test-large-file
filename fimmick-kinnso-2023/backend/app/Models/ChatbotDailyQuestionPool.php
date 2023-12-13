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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\ChatbotDailyQuestionReply;

//========================================================================================
class ChatbotDailyQuestionPool extends Model  {

	//  Inform Model it is soft delete instead of real delete
	use SoftDeletes;

	//----------------------------------------------------------------------------------------
	//  The attributes that are not mass assignable.
	protected $guarded = ['id'];

	//----------------------------------------------------------------------------------------
	//  The attributes that are mass assignable.
// 	protected $fillable = [
// 		"list_name",
// 		"offer_id",
// 		"ordering",
// 		'start_at',
// 		'end_at'
// 	];

	//----------------------------------------------------------------------------------------
	//  Pick one question that excluded answered
	// 	SELECT * FROM chatbot_daily_question_pools WHERE id NOT IN (
	// 		SELECT question_id FROM chatbot_daily_question_replies WHERE member_id=1
	// 	);
	public static function pickQuestion($mobile)  {
		$question = self::whereNotIn("id", function ($query) use ($mobile)  {

			$query->select("question_id")
			->from("chatbot_daily_question_replies")
			->where("mobile", $mobile);

		})->where("layer", "1")->where("weight", "!=", 0)->inRandomOrder()->first();
		return $question;
	}

	//----------------------------------------------------------------------------------------
	public static function getQuestionWithID($id)  {
		$question = self::where("id", $id)->first();
		return $question;
	}

	public static function getAllQuestionTag()  {
		$tagArray = [];
		$records = self::all();

		if($records->isEmpty()){
			return $tagArray;
		}

		$index = 0;
		
		foreach($records as $record)  {

			$answer = json_decode($record->answers, true);
			$keyArray = array_keys($answer);
			
			foreach($keyArray as $key){
				$labelArray = $answer[$key]['label'];

				if(!$labelArray == false){
					foreach($labelArray as $num => $value){
						if (!empty($value)){
							$tagArray[$index] = $value;
							$index++;
						}
					}
				}
			}
		}
		return array_unique($tagArray);
	}

}
