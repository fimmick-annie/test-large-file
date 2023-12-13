<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020.  All rights reserved.
//----------------------------------------------------------------------------------------

//  Priority (Higher value, higher priority)
//  50 = Reminder, fulfillment
//  100 = Normal

namespace App\Models;

//----------------------------------------------------------------------------------------
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

//========================================================================================
class CampaignWhatsappMessageQueue extends Model  {

	//  Inform Model it is soft delete instead of real delete
	use SoftDeletes;

	//----------------------------------------------------------------------------------------
	//  The attributes that are mass assignable.
	protected $guarded = ['id'];

	//----------------------------------------------------------------------------------------
	public static function cancelMessages($couponID, $messageType="")  {
		$now = date("Y-m-d H:i:s");
		$affectedRows = CampaignWhatsappMessageQueue::where('coupon_id', $couponID)
			->where("message_type", $messageType)
			->whereNull('send_at')
			->whereNull('cancel_at')
			->update([
				'cancel_at' => $now,
				'updated_by' => basename(__FILE__),
				'status' => 'Canceled',
			]);
		return $affectedRows;
	}

	//----------------------------------------------------------------------------------------
	public static function cancelMessage($messageID)  {
		$now = date("Y-m-d H:i:s");
		$affectedRows = CampaignWhatsappMessageQueue::where('id', $messageID)
			->whereNull('send_at')
			->whereNull('cancel_at')
			->update([
				'cancel_at' => $now,
				'updated_by' => basename(__FILE__),
				'status' => 'Canceled',
			]);
		return $affectedRows;
	}

	//----------------------------------------------------------------------------------------
	public static function cancelMessageWithData($offerID, $mobile, $messageType)  {
		$now = date("Y-m-d H:i:s");
		$affectedRows = CampaignWhatsappMessageQueue::where('offer_id', $offerID)
			->where("mobile", $mobile)
			->where("message_type", $messageType)
			->whereNull('send_at')
			->whereNull('cancel_at')
			->update([
				'cancel_at' => $now,
				'updated_by' => basename(__FILE__),
				'status' => 'Canceled',
			]);
		return $affectedRows;
	}

	//----------------------------------------------------------------------------------------
	public static function resendMessage($messageID)  {
		$now = date("Y-m-d H:i:s");
		$expiryDate = date("Y-m-d H:i:s", strtotime("+1 day"));

		//  Get an existing message
		$originalMessage = CampaignWhatsappMessageQueue::where('id', $messageID)
			->get()
			->first();
		if ($originalMessage == null)  {return false;}

		//  Clone a new one
		$whatsAppQueue = new CampaignWhatsappMessageQueue();
		$whatsAppQueue->created_by = basename(__FILE__);
		$whatsAppQueue->offer_id = $originalMessage->offer_id;
		$whatsAppQueue->coupon_id = $originalMessage->coupon_id;
		$whatsAppQueue->priority = 1000;
		$whatsAppQueue->mobile = $originalMessage->mobile;
		$whatsAppQueue->message = $originalMessage->message;
		$whatsAppQueue->message_type = $originalMessage->message_type;
		$whatsAppQueue->schedule_at = $now;
		$whatsAppQueue->expiry_at = $expiryDate;
		$whatsAppQueue->cost = $originalMessage->cost;
		$result = $whatsAppQueue->save();

		return $result;
	}

	//----------------------------------------------------------------------------------------
	//  Date value only, no time
	public static function getList($fromDate=null, $toDate=null, $offerID=0)  {

		$query = self::query();
		if ($offerID > 0)  {$query->where("offer_id", $offerID);}
		if ($fromDate != null)  {$query->where("schedule_at", ">=", $fromDate." 00:00:00");}
		if ($toDate != null)  {$query->where("schedule_at", "<=", $toDate." 23:59:59");}

		$dataArray = $query->orderBy("created_at", "desc")->get();
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	//  Date value only, no time
	public static function getJob($count=0)  {
		$now = date("Y-m-d H:i:s");
		$query = self::where("schedule_at", "<=", $now)
			->where(function($query) use ($now)  {
				$query->where("expiry_at", ">=", $now)
					->orWhereNull("expiry_at");
			})
			->whereNull("cancel_at")
			->whereNull("send_at")
			->whereNull("status")
			->orderBy("priority", "desc")
			->orderBy("schedule_at", "asc");

		if ($count > 0)  {$query->limit($count);}

		$array = $query->get();
		return $array;
	}

	//----------------------------------------------------------------------------------------
	public static function getHighPriorityJob()  {
		$now = date("Y-m-d H:i:s");
		$query = self::where("schedule_at", "<=", $now)
			->where("expiry_at", ">=", $now)
			->whereNull("cancel_at")
			->whereNull("send_at")
			->whereNull("status")
			->where("priority", ">=", "1000")
			->orderBy("schedule_at", "asc");

		$array = $query->get();
		return $array;
	}

	//----------------------------------------------------------------------------------------
	//  count = 0 means no limit
	public static function getSessionJob($count=0)  {
		return self::getSpecificJob($count, "session");
	}

	//----------------------------------------------------------------------------------------
	//  count = 0 means no limit
	public static function getTemplateJob($count=0)  {
		return self::getSpecificJob($count, "template");
	}

	//----------------------------------------------------------------------------------------
	//  count = 0 means no limit
	public static function getSpecificJob($count=0, $cost)  {
		$now = date("Y-m-d H:i:s");
		$query = self::where("schedule_at", "<=", $now)
			->where(function($query) use ($now)  {
				$query->where("expiry_at", ">=", $now)
					->orWhereNull("expiry_at");
			})
			->whereNull("cancel_at")
			->whereNull("send_at")
			->whereNull("status")
			->where("cost", $cost)
			->orderBy("priority", "desc")
			->orderBy("schedule_at", "asc");

		if ($count > 0)  {$query->limit($count);}

		$array = $query->get();
		return $array;
	}

	//----------------------------------------------------------------------------------------
	public static function getSentRecordsAfterDate($date)  {
		$array = self::where("send_at", ">=", $date)
			->get();
		return $array;
	}

	//----------------------------------------------------------------------------------------
	public static function getSentTemplateMessagesAfterDate($date)  {
		$array = self::where("send_at", ">=", $date)
			->where("cost", "template")
			->get();
		return $array;
	}

	//----------------------------------------------------------------------------------------
	public static function getSentRecords($fromDateTime=null, $toDateTime=null, $offerID=0)  {

		$query = self::query();
		if ($offerID > 0)  {$query->where("offer_id", $offerID);}
		if ($fromDateTime != null)  {$query->where("send_at", ">=", $fromDateTime);}
		if ($toDateTime != null)  {$query->where("send_at", "<=", $toDateTime);}

		$dataArray = $query->orderBy("send_at", "asc")->get();
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	public static function getSentRecordsWithPaging($fromDateTime=null, $toDateTime=null, $offerID=0, $skip=0, $take=0)  {

		$query = self::query();
		if ($offerID > 0)  {$query->where("offer_id", $offerID);}
		if ($fromDateTime != null)  {$query->where("send_at", ">=", $fromDateTime);}
		if ($toDateTime != null)  {$query->where("send_at", "<=", $toDateTime);}

		if ($skip > 0)  {$query->skip($skip);}
		if ($take > 0)  {$query->take($take);}

		$dataArray = $query->orderBy("send_at", "asc")->get();
		return $dataArray;
	}

	//----------------------------------------------------------------------------------------
	public static function getRecordsWithMessageID($messageID)  {
		$array = self::where("message_id", $messageID)
			->get();
		return $array;
	}

	//----------------------------------------------------------------------------------------
	public static function getRecordsWithMobile($mobile, $offerID=0)  {
		$query = self::where("mobile", $mobile);

		if ($offerID > 0)  {$query = $query->where("offer_id", $offerID);}

		$array = $query->get();
		return $array;
	}

	//----------------------------------------------------------------------------------------
	//  Extract message ID from Twilio response
	public static function getTwilioMessageID($result)  {

		$matchArray = array();
		preg_match("/sid=([0-9a-zA-Z]+)\]/", $result, $matchArray);
		$count = count($matchArray);
		if ($count <= 1)  {return "";}

		$messageID = $matchArray[1];
		return $messageID;
	}

	//----------------------------------------------------------------------------------------
	public static function updateMessageStatusAndReceipt($mobile, $vendor, $messageID, $status, $receipt=null)  {

		$array = self::where("mobile", $mobile)
			->where("vendor", $vendor)
			->where("message_id", $messageID)
			->where("status", "<>", "Read")
			->get();

		$count = count($array);
		if ($count != 1)  {return false;}

		$queue = $array[0];
		if ($queue == null)  {return false;}

		//  Update object and save
		$queue->updated_by = basename(__FILE__);
		$queue->status = $status;
		if ($receipt != null)  {$queue->delivery_receipt = $receipt;}
		$result = $queue->save();
		return $result;
	}

	//----------------------------------------------------------------------------------------
	public static function getRecordsToBeArchived($limit=1000)  {
		$date = date("Y-m-d H:i:s", strtotime("-30 days"));
		$query = self::where('send_at', '<', $date)
			->orWhere('cancel_at', '<', $date)
			->orWhere('expiry_at', '<', $date)
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
}
