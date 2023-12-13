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
use Illuminate\Http\Request;

use App\Models\CampaignWhatsappMessageQueue;
use App\Models\CampaignCustomerJourney;
use App\Models\CampaignMasterJourney;
use App\Models\CampaignCouponPool;
use App\Models\WhatsappWebhook;
use App\Models\CampaignCoupon;
use App\Models\CampaignOffer;

use App\Models\crmWhatsappOutboundMessage;
use App\Models\crmWhatsappInboundMessage;
use App\Models\crmCampaignMaster;
use App\Models\crmSurveyHistory;
use App\Models\crmSurveyMaster;
use App\Models\crmMemberMaster;
use App\Models\crmCouponMaster;
use App\Models\crmBrandMaster;
use App\Models\crmActionTable;
use App\Models\crmQuotaTable;

//========================================================================================
class DataTeamController extends Controller  {

	//----------------------------------------------------------------------------------------
	//  Note: No necessary at this moment
	function updateCRMTraffic()  {
	}

	//----------------------------------------------------------------------------------------
	function updateCRMMemberMaster()  {
		$timestampFile = "member_master.timestamp";
		$folder = storage_path("app/public/");
		$now = date("Y-m-d H:i:s");

		//  Get last timestamp
		$filePath = $folder.$timestampFile;
		if (file_exists($filePath))  {$fromTimestamp = file_get_contents($filePath);}
		if (empty($fromTimestamp))  {$fromTimestamp = "2020-08-01 00:00:00";}

		$fromDate = substr($fromTimestamp, 0, 10);
		$toDate = substr($now, 0, 10);

		//
		$dataArray = CampaignCoupon::getList($fromDate, $toDate, 0);
		foreach ($dataArray as $data)  {

			$region = $this->getRegionFromMobile($data->mobile);
			$length = strlen($region);

			//  Note: Data team use INT type for mobile
			$mobile = intval(substr($data->mobile, $length));

			$member = crmMemberMaster::firstOrNew(array(
				"mobile_region" => $region,
				"mobile_num" => $mobile,
			));

			$member->created_by = __FUNCTION__;
			$member->updated_by = __FUNCTION__;
			$member->record_status = "Member";
			$member->email = $data->email;
			$member->referral_code = $data->referral_code;
			$member->referrer_code = $data->referrer_code;

			if ($member->id == null)  {
				$member->master_id = 0;
			}  else  {
				$member->master_id = $member->id;
			}

			$member->save();

			if ($member->master_id == 0)  {

				//  Load record again to fresh id value
				$member = crmMemberMaster::firstOrNew(array(
					"mobile_region" => $region,
					"mobile_num" => $mobile,
				));
				$member->master_id = $member->id;
				$member->save();
			}
		}

		//  Save timestamp for next round
		file_put_contents($filePath, $now);
	}

	//----------------------------------------------------------------------------------------
	function updateCRMBrandMaster()  {

		$timestampFile = "brand_master.timestamp";
		$folder = storage_path("app/public/");
		$now = date("Y-m-d H:i:s");

		//  Get last timestamp
		$filePath = $folder.$timestampFile;
		if (file_exists($filePath))  {$fromTimestamp = file_get_contents($filePath);}
		if (empty($fromTimestamp))  {$fromTimestamp = "2020-08-01 00:00:00";}

		$from = $fromTimestamp;
		$to = $now;

		$offers = CampaignOffer::where('updated_at', '>=', $from)
			->where('updated_at', '<', $to)
			->get();

		foreach ($offers as $offer)  {

			$ini = $offer->getIniArray();

			if (isset($ini['settings']['brand_name']))  {
				$brandName = $ini['settings']['brand_name'];
			}

			if (empty($brandName))  {continue;}

			$query = crmBrandMaster::where('brand_name', $brandName)
				->get();

			if (count($query) > 0)  {continue;}

			$newBrand = new crmBrandMaster();
			$newBrand->created_at = $offer->updated_at;
			$newBrand->updated_at = $offer->updated_at;
			$newBrand->created_by = __FUNCTION__;
			$newBrand->brand_name = $brandName;

			$newBrand->save();

		}

		//  Update timestamp file records
		file_put_contents($filePath, $now);
	}

	//----------------------------------------------------------------------------------------
	//  Note: No necessary at this moment
	function updateCRMLocationMaster()  {
	}

	//----------------------------------------------------------------------------------------
	function updateCRMCampaignMaster()  {

		$timestampFile = "campaign_master.timestamp";
		$folder = storage_path("app/public/");
		$now = date("Y-m-d H:i:s");

		//  Get last timestamp
		$filePath = $folder.$timestampFile;
		if (file_exists($filePath))  {$fromTimestamp = file_get_contents($filePath);}
		if (empty($fromTimestamp))  {$fromTimestamp = "2020-08-01 00:00:00";}

		$from = $fromTimestamp;
		$to = $now;

		$offers = CampaignOffer::where('updated_at', '>=', $from)
			->where('updated_at', '<', $to)
			->get();

		foreach ($offers as $offer)  {

			$offerID = $offer->id;
			$offerName = $offer->offer_name;
			$iniArray = $offer->getIniArray();

			$brandName = $iniArray['settings']['brand_name'];
			$brand = crmBrandMaster::getByName($brandName);
			$brandID = $brand->id;

			$record = crmCampaignMaster::where('campaign_name', $offerName)
				->first();

			if ($record == null)  {
				$record = new crmCampaignMaster();
				$record->created_by = __FUNCTION__;
				$record->created_at = $offer->created_at;
				$record->campaign_name = $offerName;
			}

			$record->updated_at = $offer->updated_at;
			$record->updated_by = __FUNCTION__;

			$record->brand_name = $brandName;
			$record->brand_id = $brandID;

			$record->campaign_desc = $offer->offer_title ." " .$offer->offer_subtitle;

			$record->start_date = $offer->start_at;
			$record->end_date = $offer->end_at;
			$record->save();
		}

		//  Update timestamp file records
		file_put_contents($filePath, $now);
	}

	//----------------------------------------------------------------------------------------
	function updateCRMCouponMaster()  {

		$timestampFile = "coupon_master.timestamp";
		$folder = storage_path("app/public/");
		$now = date("Y-m-d H:i:s");

		//  Get last timestamp
		$filePath = $folder.$timestampFile;
		if (file_exists($filePath))  {$fromTimestamp = file_get_contents($filePath);}
		if (empty($fromTimestamp))  {$fromTimestamp = "2020-08-01 00:00:00";}

		$from = $fromTimestamp;
		$to = $now;

		$coupons = CampaignCoupon::where('updated_at', '>=', $from)
			->where('updated_at', '<', $to)
			->get();

		foreach ($coupons as $coupon)  {

			$offerID = $coupon->offer_id;
			$uniqueCode = $coupon->unique_code;
			$offer = CampaignOffer::getOfferByID($offerID);
			$iniArray = $offer->getIniArray();

			$brandName = $iniArray['settings']['brand_name'];
			$brand = crmBrandMaster::getByName($brandName);
			$brandID = $brand->id;

			$record = crmCouponMaster::where('coupon_name', $uniqueCode)
				->where('campaign_id', $offerID)
				->first();

			if ($record == null)  {
				$record = new crmCouponMaster();
				$record->coupon_name = $uniqueCode;
				$record->created_by = __FUNCTION__;
				$record->campaign_id = $offerID;
			}

			$record->offer_id = 1;
			$record->brand_id = $brandID;
			$record->start_date = $coupon->start_at;
			$record->expiry_date = $coupon->expiry_at;

			$record->updated_by = __FUNCTION__;
			$record->created_at = $coupon->created_at;
			$record->updated_at = $coupon->updated_at;

			$record->save();
		}

		//  Update timestamp file records
		file_put_contents($filePath, $now);

	}

	//----------------------------------------------------------------------------------------
	function updateCRMSurveyMaster()  {

		$timestampFile = "survey_master.timestamp";
		$folder = storage_path("app/public/");
		$now = date("Y-m-d H:i:s");

		//  Get last timestamp
		$filePath = $folder.$timestampFile;
		if (file_exists($filePath))  {$fromTimestamp = file_get_contents($filePath);}
		if (empty($fromTimestamp))  {$fromTimestamp = "2020-08-01 00:00:00";}

		$from = $fromTimestamp;
		$to = $now;

		$mjs = CampaignMasterJourney::where('updated_at', '>=', $from)
			->where('updated_at', '<', $to)
			->where('type', '>=', 200)
			->where('type', '<', 300)
			->get();

		foreach ($mjs as $journey)  {

			$offerID = $journey->offer_id;
			$offer = CampaignOffer::getOfferByID($offerID);
			$iniArray = $offer->getIniArray();

			$brandName = $iniArray['settings']['brand_name'];
			$brand = crmBrandMaster::getByName($brandName);
			$brandID = $brand->id;

			$survey = crmSurveyMaster::where('brand_id', $brandID)
				->where('campaign_id', $offerID)
				->where('survey_name', $journey->node_name)
				->first();

			if ($survey == null)  {
				$survey = new crmSurveyMaster();
				$survey->created_by = __FUNCTION__;
			}

			$nodeSettings = json_decode($journey->node_settings, true);

			$survey->content = $nodeSettings['message'];
			$survey->brand_id = $brandID;
			$survey->created_at = $journey->created_at;
			$survey->updated_at = $journey->updated_at;
			$survey->campaign_id = $offerID;
			$survey->survey_name = $journey->node_name;

			$survey->save();
		}

		//  Update timestamp file records
		file_put_contents($filePath, $now);
	}

	//----------------------------------------------------------------------------------------
	function updateCRMSurveyHistory()  {

		$timestampFile = "survey_history.timestamp";
		$folder = storage_path("app/public/");
		$now = date("Y-m-d H:i:s");

		//  Get last timestamp
		$filePath = $folder.$timestampFile;
		if (file_exists($filePath))  {$fromTimestamp = file_get_contents($filePath);}
		if (empty($fromTimestamp))  {$fromTimestamp = "2020-08-01 00:00:00";}

		$from = $fromTimestamp;
		$to = $now;

		$cjs = CampaignCustomerJourney::where('updated_at', '>=', $from)
			->where('updated_at', '<', $to)
			->where('type', '>=', 200)
			->where('type', '<', 300)
			->get();

		foreach ($cjs as $journey)  {

			$mobile = $journey->mobile;
			$offerID = $journey->offer_id;
			$nodeName = $journey->node_name;

			$surveyMaster = crmSurveyMaster::where('campaign_id', $offerID)
				->where('survey_name', $nodeName)
				->first();

			$surveyID = $surveyMaster->id;
			$brandID = $surveyMaster->brand_id;

			$member = crmMemberMaster::getWithMobileString($mobile);

			if (count($member) > 0)  {$member = $member[0];}
			//  Member not found, skip this record
			else  {continue;}

			$memberID = 0;
			if ($member)  {$memberID = $member->id;}

			$record = crmSurveyHistory::where('member_id', $memberID)
				->where('survey_id', $surveyID)
				->where('campaign_id', $offerID)
				->first();

			if ($record == null)  {
				$record = new crmSurveyHistory();
				$record->member_id = $memberID;
				$record->survey_id = $surveyID;
				$record->campaign_id = $offerID;
				$record->created_by = __FUNCTION__;
			}

			$nodeData = json_decode($journey->node_data, true);
			$record->answer = (isset($nodeData['answer']) ? $nodeData['answer'] : "");
			$record->created_at = $journey->created_at;
			$record->updated_at = $journey->updated_at;
			$record->save();
		}

		//  Update timestamp file records
		file_put_contents($filePath, $now);
	}

	//----------------------------------------------------------------------------------------
	function updateCRMQuotaTable()  {
		$timestampFile = "quota_table.timestamp";
		$folder = storage_path("app/public/");
		$now = date("Y-m-d H:i:s");

		//  Get last timestamp
		$filePath = $folder.$timestampFile;
		if (file_exists($filePath))  {$fromTimestamp = file_get_contents($filePath);}
		if (empty($fromTimestamp))  {$fromTimestamp = "2020-08-01 00:00:00";}

		$fromDate = substr($fromTimestamp, 0, 10);
		$toDate = substr($now, 0, 10);

		//
		$dataArray = CampaignCoupon::getList($fromDate, $toDate, 0);
		foreach ($dataArray as $data)  {

			$offer = $data->offer;

			$quota = crmQuotaTable::firstOrNew(array(
				"offer_id" => $offer->id,
			));

			if ($quota->created_by == null)  {
				$quota->created_by = __FUNCTION__;
			}

			$quota->updated_by = __FUNCTION__;
			// $quota->brand_id = null;
			// $quota->campaign_id = null;
			// $quota->offer_id = $offer->id;
			$quota->start_date = $offer->start_at;
			$quota->end_date = $offer->end_at;
			$quota->expiry_date = $data->expiry_at;
			$quota->quota = $offer->quota;
			$quota->remaining_quota = ($offer->quota - $offer->quota_issued);
			$quota->location_code = $data->selected_channel;
			// $quota->gift_name = null;
			// $quota->gift_desc = null;
			$quota->save();
		}

		//  Save timestamp for next round
		file_put_contents($filePath, $now);
	}

	//----------------------------------------------------------------------------------------
	//  Note: No necessary at this moment
	function updateCRMGiftTable()  {
	}

	//----------------------------------------------------------------------------------------
	function updateCRMActionTable()  {

		$timestampFile = "action_table.timestamp";
		$folder = storage_path("app/public/");
		$now = date("Y-m-d H:i:s");

		//  Get last timestamp
		$filePath = $folder.$timestampFile;
		if (file_exists($filePath))  {$fromTimestamp = file_get_contents($filePath);}
		if (empty($fromTimestamp))  {$fromTimestamp = "2020-08-01 00:00:00";}

		$from = $fromTimestamp;
		$to = $now;

		$workingTableArray = CampaignWhatsappMessageQueue::where('updated_at', ">=", $from)
			->where('updated_at', "<", $to)
			->get();
		foreach ($workingTableArray as $item)  {

			$mobile = $item->mobile;
			$member = crmMemberMaster::getWithMobileString($mobile);

			$messageID = $item->message_id;
			$crmItem = crmActionTable::where("message_id", $messageID)
				->first();

			if (count($member) > 0)  {$member = $member[0];}
			//  Member not found, skip this record
			else  {continue;}

			$memberID = 0;
			if ($member)  {$memberID = $member->id;}

			$offerID = $item->offer_id;
			$offer = CampaignOffer::getOfferByID($offerID);
			if ($offer == null)  {

				Log::error("### Offer is null with ID #$offerID...");
				continue;
			}

			$iniArray = $offer->getIniArray();

			$brandName = $iniArray['settings']['brand_name'];
			$brand = crmBrandMaster::getByName($brandName);
			$brandID = $brand->id;

			$journeyID = 0;
			$journeyName = $item->created_by;

			$coupons = CampaignCoupon::getWithMobile($mobile);
			if (count($coupons) > 0)  {$coupon = $coupons[0];}
			$uniqueCode = $coupon->unique_code;

			if ($crmItem == null)  {
				$crmItem = new crmActionTable();
				$crmItem->message_id = $messageID;
			}
			$crmItem->created_at = $item->send_at;
			$crmItem->created_by = $item->created_by;
			$crmItem->updated_by = $item->updated_by;
			$crmItem->updated_at = $item->updated_at;

			$crmItem->member_id = $memberID;
			$crmItem->mobile = $mobile;

			$crmItem->blasting_type = "whatsapp";
			$crmItem->brand_id = $brandID;
			$crmItem->campaign_id = $offerID;
			$crmItem->journey_id = $journeyID;
			$crmItem->journey_name = $journeyName;

			$crmItem->content = $item->message;
			$crmItem->schedule_at = $item->schedule_at;
			$crmItem->cancel_at = $item->cancel_at;
			$crmItem->action_at = $item->send_at;

			$crmItem->send_result = "Success";
			$crmItem->return_json = $item->response;
			$crmItem->return_id = $messageID;
			$crmItem->return_status = $item->status;

			$crmItem->open_count = $item->status == "Read" ? 1 : 0;
			$crmItem->unique_code = $uniqueCode;

			$crmItem->save();
		}
	}

	//----------------------------------------------------------------------------------------
	function updateCRMWhatsAppInboundMessage()  {

		$timestampFile = "whatsapp_inbound_message.timestamp";
		$folder = storage_path("app/public/");
		$now = date("Y-m-d H:i:s");

		//  Get last timestamp
		$filePath = $folder.$timestampFile;
		if (file_exists($filePath))  {$fromTimestamp = file_get_contents($filePath);}
		if (empty($fromTimestamp))  {$fromTimestamp = "2020-08-01 00:00:00";}

		$from = $fromTimestamp;
		$to = $now;

		// get data from working
		$array = WhatsappWebhook::where('updated_at', '>=', $from)
			->where('updated_at', '<', $to)
			->get();

		foreach ($array as $item)  {

			$messageID = $item->message_id;
			$contentJSON = $item->content;
			$itemContent = json_decode($contentJSON);

			// check if this record is inbound or outbound callback
			$msgBody = "";
			if (property_exists($itemContent, "Body"))  {
				$msgBody = $itemContent->Body;
			} else {
				// not an inbound msg
				continue;
			}

			$mobile = str_replace("whatsapp:", "", $itemContent->From);
			$member = crmMemberMaster::getWithMobileString($mobile);

			if (count($member) > 0)  {$member = $member[0];}
			//  Member not found, skip this record
			else  {continue;}

			$memberID = 0;
			if ($member)  {$memberID = $member->id;}

			$inboundMsg = new crmWhatsappInboundMessage();
			$inboundMsg->created_by = $item->vendor;
			$inboundMsg->content = $msgBody;
			$inboundMsg->member_id = $memberID;
			$inboundMsg->message_id = $messageID;
			$inboundMsg->send_result = "Sent";

			$returnStatus = "Arrvied";
			$inboundMsg->return_status = $returnStatus;
			$inboundMsg->open_count = 1;

			$inboundMsg->created_at = $item->created_at;
			$inboundMsg->updated_at = $item->updated_at;

			$inboundMsg->save();
		}

		//  Update timestamp file records
		file_put_contents($filePath, $now);
	}

	//----------------------------------------------------------------------------------------
	function updateCRMWhatsappOutboundMessage()  {

		$timestampFile = "whatsapp_outbound_message.timestamp";
		$folder = storage_path("app/public/");
		$now = date("Y-m-d H:i:s");

		//  Get last timestamp
		$filePath = $folder.$timestampFile;
		if (file_exists($filePath))  {$fromTimestamp = file_get_contents($filePath);}
		if (empty($fromTimestamp))  {$fromTimestamp = "2020-08-01 00:00:00";}

		$from = $fromTimestamp;
		$to = $now;

		$workingTableArray = CampaignWhatsappMessageQueue::whereNotNull("send_at")
			->where('updated_at', ">=", $from)
			->where('updated_at', "<", $to)
			->get();

		foreach ($workingTableArray as $item)  {

			$messageID = $item->message_id;
			$crmItem = crmWhatsappOutboundMessage::where("message_id", $messageID)
				->first();

			$mobile = $item->mobile;
			$member = crmMemberMaster::getWithMobileString($mobile);

			if (count($member) > 0)  {$member = $member[0];}
			//  Member not found, skip this record
			else {continue;}

			$memberID = 0;
			if ($member)  {$memberID = $member->id;}

			if ($crmItem == null)  {
				$crmItem = new crmWhatsappOutboundMessage();
				$crmItem->message_id = $messageID;
			}
			$crmItem->created_at = $item->sent_at;
			$crmItem->created_by = $item->created_by;
			$crmItem->updated_at = $item->updated_at;
			$crmItem->member_id = $memberID;
			$crmItem->content = $item->message;
			$crmItem->send_result = "Success";
			$crmItem->return_status = $item->status;

			$crmItem->open_count = $item->status == "Read" ? 1 : 0;

			$crmItem->save();
		}

		//  Update timestamp file records
		file_put_contents($filePath, $now);
	}

	//----------------------------------------------------------------------------------------
	function getRegionFromMobile($mobile)  {
		if (empty($mobile))  {return $mobile;}

		$regionArray = array(
			"1",		//  United States
			"7",		//  Russia
			"44",		//  United Kingdom
			"60",		//  Malaysia
			"61",		//  Australia
			"62",		//  Indonesia
			"63",		//  Philippines
			"65",		//  Singapore
			"66",		//  Thailand
			"81",		//  Japan
			"82",		//  South Korea
			"852",		//  Hong Kong
			"853",		//  Macau
			"86",		//  China
			"886",		//  Taiwan
		);

		foreach ($regionArray as $region)  {

			//  mobile = +85293101987
			$index = strpos($mobile, $region);
			if ($index != 1)  {continue;}

			return "+".$region;
		}

		//  Region not found, use default value
		$region = substr($mobile, 0, 4);
		return $region;
	}

}
