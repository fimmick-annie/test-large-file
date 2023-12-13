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
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Session;

use App\Models\Member;
use App\Models\FosoUser;
use App\Models\MemberEvent;
use App\Models\ChatbotState;
use App\Models\CampaignOffer;
use App\Models\MarketingList;
use App\Models\UploadFileLog;
use App\Models\CampaignCoupon;
use App\Models\CampaignBanner;
use App\Models\FosoActivityLog;
use App\Models\WhatsappWebhook;
use App\Models\PointTransaction;
use App\Models\CampaignQuickReply;
use App\Models\CampaignStoreQuota;
use App\Models\CampaignCouponPool;
use App\Models\ChannelReceiptSample;
use App\Models\CampaignOfferChannel;
use App\Models\CampaignCouponArchive;
use App\Models\CampaignMasterJourney;
use App\Models\WhatsappWebhookArchive;
use App\Models\CampaignCustomerJourney;
use App\Models\CampaignWhatsappMessageQueue;
use App\Models\CampaignCustomerJourneyArchive;
use App\Models\CampaignWhatsappMessageQueueArchive;
use App\Models\DashboardGenericChart;
use App\Models\DashboardTimeChart;
use App\Models\AppUser;
use App\Models\CampaignListingLists;
use App\Models\CampaignListing;
use App\Models\CampaignOfferHunting;
use App\Models\Redemption;
use App\Models\RedemptionCode;
use App\Models\FormUA;

use Maatwebsite\Excel\Facades\Excel;
use Google_Client;
use Exception;
use Throwable;
use DateTime;

//========================================================================================
class FOSOMainController extends Controller  {

	use AuthenticatesUsers;

	//----------------------------------------------------------------------------------------
	protected $redirectTo = '/foso';

	//----------------------------------------------------------------------------------------
	public function __construct()  {
		$this->middleware('guest')->only([
			'loginPage',
			'loginAPI',
		]);
	}

	//----------------------------------------------------------------------------------------
	//  Mark: Pages
	//----------------------------------------------------------------------------------------
	public function homePage(Request $request)  {
		return view('foso.homepage.index');
	}

	//----------------------------------------------------------------------------------------
	public function loginPage(Request $request)  {
		return view('foso.auth.login');
	}

	//----------------------------------------------------------------------------------------
	// offer listing
	public function offerListingPage(Request $request) {
		return view('foso.campaigns.campaign_offer_listing');
	}

	public function offerListingPageGetList(Request $request) {
		return CampaignListingLists::get();
	}

	public function offerListingPageGetOffersByList(Request $request) {
		$listName = $request->input('listName', 'default');
		return CampaignListing::query()
		->where('list_name',$listName)
		->orderBy("ordering", "desc")
		->leftJoin('campaign_offers', 'campaign_listings.offer_id', 'campaign_offers.id')
		->select('campaign_listings.*', 'campaign_offers.offer_name', )
		->get();
	}

	public function offerListingPageCreateNewList(Request $request) {
		$response = array(
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);
		$listName = $request->input('listName', '');
		if(!$listName) {
			$response['message'] = 'List Name can\'t be empty.';
			return $response;
		}
		if(CampaignListingLists::query()->where('list_name', $listName)->first()) {
			$response['message'] = 'List Name already exist.';
			return $response;
		}
		$newList = CampaignListingLists::create([
			'list_name' => $listName
		]);
		$response['new_list'] = $newList;
		$response["status"] = 0;
		return $response;
	}

	public function offerListingPageRearangeOffersPermutation(Request $request) {
		$response = array(
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$listName = $request->input('listName', '');
		$oldIndex = $request->input('oldIndex', '');
		$newIndex = $request->input('newIndex', '');
		if(!$listName) {
			return $response;
		}

		$offersOfList = CampaignListing::query()
			->where('list_name', $listName)
			->orderBy('ordering', 'desc')
			->get();

		$count = 0;
		foreach ($offersOfList as $offer)  {

			$change = 0;
			if ($count == $oldIndex)  {
				$change = ($newIndex-$oldIndex) * -10;
			}
			else if ($count >= $oldIndex && $count <= $newIndex)  {
				$change = 10;
			}
			else if ($count <= $oldIndex && $count >= $newIndex)  {
				$change = -10;
			}

			if ($change != 0)  {
				$offer->ordering += $change;
				$offer->save();
			}
			$count++;
		}

		$response["status"] = 0;
		$response["message"] = "OK";
		return response()->json($response);
	}

	public function offerListingPageAddOfferIntoList(Request $request) {
		$response = array(
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$listName = $request->input('listName');
		$offerId = $request->input('offerId');
		$startAt = $request->input('startAt');
		$endAt = $request->input('endAt');

		if(!$listName || !$offerId || !$startAt || !$endAt) {
			return $response;
		}
		// offer not exist
		if( !CampaignOffer::find($offerId) ) {
			$response['message'] = 'Offer Not Exist.';
			return $response;
		}
		// offer in list already
		if(CampaignListing::query()
			->where('list_name', $listName)
			->where('offer_id' , $offerId)
			->first()
		) {
			$response['message'] = 'Offer Already In List.';
			return $response;
		}
		// javascript timestamp to php date
		$startAt = date('Y-m-d H:i:s', $startAt / 1000);
		$endAt = date('Y-m-d H:i:s', $endAt / 1000);

		// get last biggest ordering index
		$offer = CampaignListing::query()
			->where('list_name', $listName)
			->orderBy('ordering', 'desc')
			->first();

		$ordering = 100;
		if ($offer)  {
			$ordering = $offer->ordering + 10;
		}

		CampaignListing::create([
			'list_name' => $listName,
			'offer_id' => $offerId,
			'ordering' => $ordering,
			'start_at' => $startAt,
			'end_at' => $endAt,
		]);

		$response["status"] = 0;
		$response["message"] = "OK";
		return response()->json($response);
	}
	public function offerListingPageUpdateOfferIntoList(Request $request) {
		$response = array(
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$listName = $request->input('listName');
		$offerId = $request->input('offerId');
		$startAt = $request->input('startAt');
		$endAt = $request->input('endAt');

		if(!$listName || !$offerId || !$startAt || !$endAt) {
			return $response;
		}
		// offer not exist
		if( !CampaignOffer::find($offerId) ) {
			$response['message'] = 'Offer Not Exist.';
			return $response;
		}
		// offer not in list
		$offerInList = CampaignListing::query()
			->where('list_name', $listName)
			->where('offer_id' , $offerId)
			->first();

		if(!$offerInList) {
			$response['message'] = 'Offer Not In List.';
			return $response;
		}
		// javascript timestamp to php date
		$startAt = date('Y-m-d H:i:s', $startAt / 1000);
		$endAt = date('Y-m-d H:i:s', $endAt / 1000);

		$offerInList->start_at = $startAt;
		$offerInList->end_at = $endAt;
		$offerInList->save();

		$response["status"] = 0;
		$response["message"] = "OK";
		return response()->json($response);
	}

	public function offerListingPageReniveOfferIntoList(Request $request) {
		$response = array(
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$listName = $request->input('listName');
		$offerId = $request->input('offerId');


		if(!$listName || !$offerId) {
			return $response;
		}

		// offer in list
		$record = CampaignListing::query()
			->where('list_name', $listName)
			->where('offer_id' , $offerId)
			->first()->delete();

		$response["status"] = 0;
		$response["message"] = "OK";
		return response()->json($response);

	}

	//----------------------------------------------------------------------------------------
	//  Redemption Related Pages --- 2022.06.30 Kay
	//----------------------------------------------------------------------------------------
	public function redemptionListPage(Request $request)  {

		//  Activity log
		// $user = Auth::user();
		// FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Visit FOSO redemption listing page", "Redemption");

		if (null !== $request->input("from"))  {$fromDate = $request->input("from");}
		else  {$fromDate = date("Y-m-d", strtotime("-30 days"));}

		if (null !== $request->input("to"))  {$toDate = $request->input("to");}
		else  {$toDate = date("Y-m-d", strtotime("+30 days"));}

		// Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Read FOSO redemption listing page from [$fromDate] to [$toDate]", "Redemption");

		return view('foso.redemption.redemption_list', [
			"fromDate" => $fromDate,
			"toDate" => $toDate,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function redemptionListAPI(Request $request)  {

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

		$array = Redemption::getList($fromDate, $toDate);
		
		$dataArray = array();
		foreach ($array as $row)  {

			$openCount = 0;
			$json = $row->statistic_data;
			if ($json != null)  {

				$dictionary = json_decode($json, true);
				if (isset($dictionary["open"]))  {

					$openCount = intval($dictionary["open"]);
				}
			}

			$titleJSON = $row->title;
			$subtitleJSON = $row->subtitle;
			$zh_title = $titleJSON["zh-HK"];
			$zh_subtitle = $subtitleJSON["zh-HK"];

			$tempredemption = Redemption::getRedemption($row->id);	
			$tempQuota = $tempredemption->quota;
			$tempQuotaIssued = $tempredemption->quota_issued;

			$dataArray[] = array(
				$row->id, $row->start_at, $row->end_at, $row->ordering,
				$row->code_type, $row->thumbnail_filename, $zh_title,
				$zh_subtitle, $tempQuota, $tempQuotaIssued, $row->required_issued,
				$row->details, $row->void_details, $openCount,

				//  Must be the last one
				route("foso.redemption.settings.html", ["id"=>$row->id]),
				// route("foso.redemption.settings.html", ["idpath"=>$row->redemption_path]),
			);

		}

		//  Output now
		$response["data"] = $dataArray;
		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	//  Redemption settings page
	public function redemptionSettingsPage(Request $request)  {
		
		$id = $request->id;
		$redemption = Redemption::getRedemption($id); 
		// $redemptionPath = $request->idpath;
		// $redemption = Redemption::getRedemptionByRedemptionPath($redemptionPath); 

		//  Offer start date and end date, default last for 30 days
		if ($id == '0'){

			//  Activity log
			$user = Auth::user();
			FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Visit FOSO redemption - Go to create new redemption settings page");

			$createDateTime = date("Y-m-d H:i:s");
			$startDate = date("Y-m-d");
			$startTime = "00:00:00";
			$endDate = date("Y-m-d", strtotime("+30 days"));
			$endTime = "23:59:59";

			$zh_title = "";
			$zh_subtitle = "";
			$zh_details = "";
			$zh_void_details = "";

			$isUnique = false;
			do {
				$randomCode = FOSOMainController::generateRandomString(16);  //genearate random code
				$checkUsed = Redemption::getRedemptionByRedemptionPath($randomCode);
				if ($checkUsed == null){$isUnique = true;}
			}while($isUnique == false);

		}else{

			//  Activity log
			$user = Auth::user();
			FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Visit FOSO redemption - Go to redemption id [$id] settings page");

			$createDateTime = $redemption->created_at;
			$startDate = substr($redemption->start_at, 0, 10);
			$startTime = substr($redemption->start_at, -8, 5);
			$endDate = substr($redemption->end_at, 0, 10);
			$endTime = substr($redemption->end_at, -8, 5);

			$titleJSON = $redemption->title;
			$subtitleJSON = $redemption->subtitle;
			$detailsJSON = $redemption->details;
			$voidDetailsJSON = $redemption->void_details;
			$zh_title = $titleJSON["zh-HK"] ?? "";
			$zh_subtitle = $subtitleJSON["zh-HK"] ?? "";
			$zh_details = $detailsJSON["zh-HK"] ?? "";
			$zh_void_details = $voidDetailsJSON["zh-HK"] ?? "";

			$randomCode = $redemption->redemption_path;

		}

		//----------------------------------------------------------------------------------------
		return view('foso.redemption.redemption_settings', [
			"randomCode" => $randomCode, //redemption_path
			"id" => $id,
			"startDate" => $startDate,  
			"startTime" => $startTime, 
			"endDate" => $endDate, 
			"endTime" => $endTime, 
			"createDateTime" => $createDateTime,
			"zhTitle" => $zh_title,
			"zhSubtitle" =>$zh_subtitle,
			"zhDetails" => $zh_details,
			"zhVoidDetails" =>$zh_void_details,
			"redemption" => $redemption,
		]);
	}

	public function saveRedemptionSettingsAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);
		
		//  Check if all required parameters are available
		$parameterArray = array(
			"id", "created_at", 
			"startDate", "startTime", "endDate", "endTime", "ordering", "codeType",
			"title", "subtitle", "redemptionPath","quota", "quotaIssued","requiredPoints", "details", "voidDetails",
			// "updated_at",
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

		$id = $request->id;
		$redemption = Redemption::firstOrCreate(['id' => $id]);

		// Activity log
		if ($id == '0' ){$remark = "Create new redemption id #".$redemption->id." in FOSO redemption settings page";
		}else{ $remark = "Update redemption id #".$redemption->id." in FOSO redemption settings page"; }
		
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), $remark, "Redemption");
		
		//----------------------------------------------------------------------------------------
		//  Save 
		$redemption->ordering = $request->input("ordering");
		$redemption->code_type = $request->input("codeType");
		$redemption->start_at = $request->input("startDate")." ".$request->input("startTime").":00";
		$redemption->end_at = $request->input("endDate")." ".$request->input("endTime").":59";
		$redemption->required_points = $request->input("requiredPoints");

		$tempRedemptionPath = $request->input("redemptionPath");
		if ($tempRedemptionPath != $redemption->redemption_path){
			$checkUsed = Redemption::getRedemptionByRedemptionPath($tempRedemptionPath);

			if ($checkUsed != null){
				$response["status"] = -20;
				$response["message"] = "The redemption path is already used.";
				return response()->json($response);
			}

			$redemption->redemption_path = $tempRedemptionPath;
		}
		
		// Handle the language issure for title, subtitle, details and void details
		if (!is_null($request->input("title")))  {
			$array = ["zh-HK" => $request->input("title")];
			$redemption->title = $array;
		}

		if (!is_null($request->input("subtitle")))  {
			$array = ["zh-HK" => $request->input("subtitle")];
			$redemption->subtitle = $array;
		}

		if (!is_null($request->input("details")))  {
			$array = ["zh-HK" => $request->input("details")];
			$redemption->details = $array;
		}

		if (!is_null($request->input("voidDetails")))  {
			$array = ["zh-HK" => $request->input("voidDetails")];
			$redemption->void_details = $array;
		}

		$thumbnailPath = $request->input("path");

		if (strlen($thumbnailPath)>0){

			$oldfilename = $redemption->thumbnail_filename;
			// delete the previous thumbnail
			if (strlen($oldfilename)>0){
				$oldfile = Storage::disk('redemption')->get($oldfilename);
				if ($oldfile == true){
					Storage::disk('redemption')->delete($oldfilename);
				}
			}

			$randomStr = FOSOMainController::generateRandomString(16);

			$extension = strtolower(substr($thumbnailPath, -4));
			$newfilename = $randomStr.$redemption->id.$extension;

			// save the new thumbnail to redemption folder and delete the temp thumbnail
			$newfile = Storage::disk('local')->get('uploads/'.$thumbnailPath);
			if ($newfile == true){
				Storage::disk('redemption')->put($newfilename, $newfile);
				Storage::disk('local')->delete('uploads/'.$thumbnailPath);
			}

			$redemption->thumbnail_filename = $newfilename;
		}

		$result = $redemption->save();

		//----------------------------------------------------------------------------------------
		//  Output now
		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}

	public function redemptionResourcesUploadAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$id = $request->id;

		if (isset($request->remove))  {

			$fileURL = $request->default;

			$extension = strtolower(substr($fileURL, -4));
			if ($extension != ".png" && $extension != ".jpg")  {

				$response["status"] = -1;
				$response["message"] = "Invalid filename...";
				return response()->json($response);
			}

			$redemption = Redemption::getRedemption($id);
			$filename = $redemption->thumbnail_filename;
			$file = Storage::disk('redemption')->get($filename);

			if ($file == true)  {
				Storage::disk('redemption')->delete($filename);
				$redemption->thumbnail_filename = "";
				$redemption->save();
			}else{
				//  Fail
				$response["status"] = -10;
				$response["message"] = "Unable to remove file...";
				return response()->json($response);
			}

			$response["status"] = 0;
			$response["message"] = "File remove success";
			return response()->json($response);

		}

		//----------------------------------------------------------------------------------------
		$log = new CampaignFileUploadController;
		$result = $log->upload($request);

		if (strtolower($result['status']) == "ok" && isset($result['uniqid'])) {
			$log = UploadFileLog::where('uniqid', $result['uniqid'])
				->first();

			//  get the temp thumbnail with the random name and put file to /uploads
			$file = Storage::disk('local')->get('uploads/'.$log->name);

			$response["status"] = 10;
			$response["message"] = "New thumbnail file put on the uploads, with name [$log->name]";

			$user = Auth::user();
			FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Update thumbnail: [$log->name] for redemption id #$id", "Redemption");

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
	
	public function redemptionCSVUploadAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$id = $request->id;
		$redemption = Redemption::getRedemption($id);

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

			// uniqueCode add to redemtpion
			$row = 0;
			$errorCount = 0;
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
	
				$row++;
				//  Skip header row
				if ($row <= 1)  {continue;}

				$uniqueCode = $data[0];
				// check if redemption id and code pair is not used before, creat a new one in Redemption code DB
				RedemptionCode::firstOrCreate(['code'=> $uniqueCode, 'redemption_id' => $id]);
			}
			
			fclose($handle);
			Storage::disk('local')->delete('uploads/'.$log->name); //clear the CSV file

			$redemption = Redemption::getRedemption($id);
			// when the quota <0 that means "out of quota" is set, so need not to check the amount of redemption code 
			if ($redemption->quota >=0){
				$redemption->quota = RedemptionCode::getCodeCount($id);
				$redemption->save();

				$response["status"] = 30;
				$response["message"] = "The csv file of quota for redemption id [$id] is uploaded";
			}

			$user = Auth::user();
			FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Add $row quota of code for redemption id #$id with CSV file [$log->name]", "Redemption");

		}
		return response()->json($result);
	}

	public function outOfRedemptionQuotaAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		//  Load redemption record
		$id = $request->id;
		$redemption = Redemption::getRedemption($id);

		if ($redemption == null)  {
			$response["status"] = -1;
			$response["message"] = "Redemption [$id] record not found...";
			return response($response, 500);
		}

		// change the quota to negative number to indicate that is is set to "out of quota"
		if($redemption->quota > 0){
			$redemption->quota = 0 - $redemption->quota;
			$redemption->save();
		}

		//----------------------------------------------------------------------------------------
		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Update redemption id #$id to 'Out of Quota' ");

		//----------------------------------------------------------------------------------------
		//  Finally
		$response["status"] = 40;
		$response['message'] = "Done, OUT of quota now";
		return response($response, 200);
	}

	//----------------------------------------------------------------------------------------
	public function resumeRedemptionQuotaAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		//  Load redemption record
		$id = $request->id;
		$redemption = Redemption::getRedemption($id);

		if ($redemption == null)  {
			$response["status"] = -1;
			$response["message"] = "Redemption [$id] record not found...";
			return response($response, 500);
		}

		if ($redemption->quota < 0)  {
			$redemption->quota = 0 - $redemption->quota;
			$redemption->save();
		}

		//----------------------------------------------------------------------------------------
		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Update redemption id #$id to 'Resume Quota' ","Redemption");

		//----------------------------------------------------------------------------------------
		//  Finally
		$response["status"] = 40;
		$response['message'] = "Done, RESUME qouta now";
		return response($response, 200);
	}


	//----------------------------------------------------------------------------------------
	//  Offer Related Pages
	//----------------------------------------------------------------------------------------
	public function offerListPage(Request $request)  {

		if (null !== $request->input("from"))  {$fromDate = $request->input("from");}
		else  {$fromDate = date("Y-m-d", strtotime("-30 days"));}

		if (null !== $request->input("to"))  {$toDate = $request->input("to");}
		else  {$toDate = date("Y-m-d", strtotime("+30 days"));}

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Read FOSO campaign offer listing page from $fromDate to $toDate", "Offer");

		return view('foso.campaigns.offer_list', [
			"fromDate" => $fromDate,
			"toDate" => $toDate,
		]);
	}

	//----------------------------------------------------------------------------------------
	//  Offer settings root
	public function offerPage(Request $request)  {
		$offerCode = $request->offer_code;
		return redirect()->route("foso.campaigns.offer.settings.html", ["offer_code" => $offerCode]);
	}

	//----------------------------------------------------------------------------------------
	//  Offer settings page
	public function offerSettingsPage(Request $request)  {

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Visit FOSO campaign offer settings page");

		$offerIDText = "";
		$bladeFolder = "";
		$offerCode = $request->offer_code;
 		$offer = CampaignOffer::getOffer($offerCode);

		//  If not "new" and offer not found then abort
		if (!$offer && $offerCode != "new")  {abort(404);}

		$newOffer = false;
		$randomCode = "";
		$fileExist = false;
		if (empty($offerCode) || $offerCode == "new")  {

			$newOffer = true;
			$randomCode = FOSOMainController::generateRandomString(16);

		}  else  {

			$bladeFolder = $offer->blade_folder;
			$offerIDText = "#".$offer->id;

			if (file_exists(public_path('offers/'.$offer->offer_name))){$fileExist = true;}
		}

		$port = ($_SERVER["SERVER_PORT"] == "80" || $_SERVER["SERVER_PORT"] == "443") ? "" : ":".$_SERVER["SERVER_PORT"];
		$scheme = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? "https" : "http";
		$baseURL = $scheme."://".$_SERVER["SERVER_NAME"].$port."/offer/";

		$offerURL = $baseURL;
		if ($randomCode == "")  {$offerURL .= $offerCode;}
		else  {$offerURL .= $randomCode;}

		//  Offer start date and end date, default last for 30 days
		$startDate = date("Y-m-d");
		$startTime = "00:00:00";
		$endDate = date("Y-m-d", strtotime("+30 days"));
		$endTime = "23:59:59";

		if (!empty($offer->start_at))  {
			$startDate = substr($offer->start_at, 0, 10);
			$startTime = substr($offer->start_at, -8, 5);
		}
		if (!empty($offer->end_at))  {
			$endDate = substr($offer->end_at, 0, 10);
			$endTime = substr($offer->end_at, -8, 5);
		}

		//  Read settings from .ini
		$triggerURL = "";
		$description = "";
		if ($newOffer == false)  {

			$filePath = "./offers/".$offer->offer_name."/offer.ini";
			if (file_exists($filePath) == true)  {

				//  Exists
				$offer->ini = parse_ini_file($filePath, true);
			}

			$triggerMessage = "";
			if (isset($offer->ini["settings"]["whatsapp_trigger_message"]))  {
				$triggerMessage = $offer->ini["settings"]["whatsapp_trigger_message"];
			}

			$sender = env('WHATSAPP_SENDER', '');
			$sender = str_replace("whatsapp:", "", $sender);
			$sender = str_replace("+", "", $sender);
			$triggerURL = "https://wa.me/".$sender."?text=".urlencode($triggerMessage);

			if (isset($offer->ini["settings"]["offer_description"]))  {
				$description = $offer->ini["settings"]["offer_description"];
			}
		}

		//----------------------------------------------------------------------------------------
		//  Extract GA code and FB pixel
		$googleAnalytics = "";
		$facebookPixel = "";
		$gtm = "";
		if (isset($offer->tracking_code))  {

			$dictionary = json_decode($offer->tracking_code, true);

			if (isset($dictionary["facebookPixel"]))  {

				$array = $dictionary["facebookPixel"];
				$count = count($array);
				if ($count > 0)  {$facebookPixel = $array[0];}
			}

			if (isset($dictionary["googleAnalytics"]))  {

				$array = $dictionary["googleAnalytics"];
				$count = count($array);
				if ($count > 0)  {$googleAnalytics = $array[0];}
			}

			if (isset($dictionary["gtm"]))  {

				$array = $dictionary["gtm"];
				$count = count($array);
				if ($count > 0)  {$gtm = $array[0];}
			}
		}

		//----------------------------------------------------------------------------------------
		//  Extract webhook from JSON
		$offerRegistrationWebhook = "";
		$couponActivationWebhook = "";
		if (isset($offer->webhook))  {

			$dictionary = json_decode($offer->webhook, true);

			if (isset($dictionary["offerRegistration"]))  {
				$offerRegistrationWebhook = $dictionary["offerRegistration"];
			}

			if (isset($dictionary["couponActivation"]))  {
				$couponActivationWebhook = $dictionary["couponActivation"];
			}
		}

		$statisticData = null;
		if ($offer != null)  {
			$statisticData = json_decode($offer->statistic_data, true);
		}

		//----------------------------------------------------------------------------------------
		return view('foso.campaigns.offer_settings', [
			"offerRegistrationWebhook" => $offerRegistrationWebhook,
			"couponActivationWebhook" => $couponActivationWebhook,
			"googleAnalytics" => $googleAnalytics,
			"statisticData" => $statisticData,
			"facebookPixel" => $facebookPixel,
			"offerIDText" => $offerIDText,
			"description" => $description,
			"bladeFolder" => $bladeFolder,
			"triggerURL" => $triggerURL,
			"checkExist" => $fileExist,  //check whether is in server
			"randomCode" => $randomCode,
			"offerCode" => $offerCode,
			"startDate" => $startDate,
			"startTime" => $startTime,
			"offerURL" => $offerURL,
			"endDate" => $endDate,
			"endTime" => $endTime,
			"baseURL" => $baseURL,
			"offer" => $offer,
			"gtm" => $gtm,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function offerCouponListPage(Request $request)  {

		$offerCode = $request->offer_code;
		$offer = CampaignOffer::getOffer($offerCode);
		if (!$offer)  {abort(404);}

		if (null !== $request->input("from"))  {$fromDate = $request->input("from");}
		else  {$fromDate = date("Y-m-d", strtotime("-30 days"));}

		if (null !== $request->input("to"))  {$toDate = $request->input("to");}
		else  {$toDate = date("Y-m-d");}

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Read FOSO campaign offer coupon page: Offer id #$offer->id, from [$fromDate] to [$toDate] ", "Offer");

		return view('foso.campaigns.offer_coupon_list', [
			"offerCode" => $offerCode,
			"fromDate" => $fromDate,
			"toDate" => $toDate,
			"offer" => $offer,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function offerCouponDetailsPage(Request $request, $offer_code, $unique_code)  {
		dd($offer_code, $unique_code);
	}

	//----------------------------------------------------------------------------------------
	public function offerResourcesPage(Request $request)  {

		$offerCode = $request->offer_code;
		$offer = CampaignOffer::getOffer($offerCode);
		if (!$offer)  {abort(404);}

		$filePath = "./offers/".$offer->offer_name."/offer.ini";
		if (file_exists($filePath) == true)  {

			//  Exists
			$offer->ini = parse_ini_file($filePath, true);
		}

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Read FOSO campaign offer resources page: Offer id #$offer->id", "Offer");

		return view('foso.campaigns.offer_resources', [
			"offerCode" => $offerCode,
			"offer" => $offer,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function offerRulesPage(Request $request)  {

		$offerCode = $request->offer_code;
		$offer = CampaignOffer::getOffer($offerCode);
		if (!$offer)  {abort(404);}

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Read FOSO campaign offer rules page: Offer id #$offer->id", "Offer");

		$string = $offer->webhook;
		if (strlen($string) < 2)  {abort(404);}

		//  Default value
		$offerRegistrationWebhookType = 10;
		$offerRegistrationWebhookURL = "";
		$offerRegistrationNPickM = "";
		$offerRegistrationM = "";

		$couponActivationWebhookType = 10;
		$couponActivationWebhookURL = "";
		$couponActivationReferralCount = 0;
		$couponActivationReferralOfferID = 0;
		$couponActivationMessage = "";

		//  Get saved settings
		$json = json_decode($string, true);
		if (isset($json["offerRegistrationM"]))  {$offerRegistrationM = intval($json["offerRegistrationM"]);}
		if (isset($json["offerRegistrationNPickM"]))  {$offerRegistrationNPickM = intval($json["offerRegistrationNPickM"]);}
		if (isset($json["offerRegistrationWebhookURL"]))  {$offerRegistrationWebhookURL = $json["offerRegistrationWebhookURL"];}
		if (isset($json["offerRegistrationWebhookType"]))  {$offerRegistrationWebhookType = intval($json["offerRegistrationWebhookType"]);}

		if (isset($json["couponActivationMessage"]))  {$couponActivationMessage = $json["couponActivationMessage"];}
		if (isset($json["couponActivationWebhookURL"]))  {$couponActivationWebhookURL = $json["couponActivationWebhookURL"];}
		if (isset($json["couponActivationWebhookType"]))  {$couponActivationWebhookType = intval($json["couponActivationWebhookType"]);}
		if (isset($json["couponActivationReferralCount"]))  {$couponActivationReferralCount = intval($json["couponActivationReferralCount"]);}
		if (isset($json["couponActivationReferralOfferID"]))  {$couponActivationReferralOfferID = intval($json["couponActivationReferralOfferID"]);}

		return view('foso.campaigns.offer_rules', [

			"offerRegistrationM" => $offerRegistrationM,
			"offerRegistrationNPickM" => $offerRegistrationNPickM,
			"offerRegistrationWebhookURL" => $offerRegistrationWebhookURL,
			"offerRegistrationWebhookType" => $offerRegistrationWebhookType,

			"couponActivationMessage" => $couponActivationMessage,
			"couponActivationWebhookURL" => $couponActivationWebhookURL,
			"couponActivationWebhookType" => $couponActivationWebhookType,
			"couponActivationReferralCount" => $couponActivationReferralCount,
			"couponActivationReferralOfferID" => $couponActivationReferralOfferID,

			"offerCode" => $offerCode,
			"offer" => $offer,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function offerQuotasPage(Request $request)  {

		$offerCode = $request->offer_code;
		$offer = CampaignOffer::getOffer($offerCode);
		if (!$offer)  {abort(404);}

		if (null !== $request->input("from"))  {$fromDate = $request->input("from");}
		else  {$fromDate = date("Y-m-d", strtotime("-30 days"));}

		if (null !== $request->input("to"))  {$toDate = $request->input("to");}
		else  {$toDate = date("Y-m-d", strtotime("+30 days"));}

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Read FOSO campaign offer quotas page: Offer id #$offer->id, from [$fromDate] to [$toDate] ", "Offer");

		return view('foso.campaigns.offer_quotas', [
			"offerCode" => $offerCode,
			"fromDate" => $fromDate,
			"toDate" => $toDate,
			"offer" => $offer,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function offerQuotasConfirmPage(Request $request)  {

		$offerCode = $request->offer_code;
		$uniqid = $request->get("uniqid");

		$offer = CampaignOffer::getOffer($offerCode);
		$offerID = $offer->id;

		//  Load CSV filename from database
		$uploader = UploadFileLog::where('uniqid', $uniqid)->first();
		$file = 'app/uploads/'.$uploader->name;

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Confirming quota file [$file] for Offer id #$offerID ", "Offer");

		$row = 0;
		$errorCount = 0;
		$dataArray = array();
		$confirmImportDisable = "";
		if (($handle = fopen(storage_path($file), "r")) !== FALSE)  {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

				$row++;
				if ($row <= 1)  {continue;}

				//  Prevent not a comma separated format
				$count = count($data);
				if ($count < 7)  {continue;}

				//  Get quota issued from database
				$startAt = $data[0];
				$endAt = $data[1];

				//  Check if a valid date format
				$startAtValid = $this->validateDate($startAt);
				$endAtValid = $this->validateDate($endAt);

				if (strtotime($endAt) < strtotime($startAt))  {
					$endAtValid = false;
				}

				$quota = 0;
				$found = false;
				$quotaIssued = 0;
				if ($startAtValid != false && $endAtValid != false)  {

					$storeCode = $data[2];
					$quota = intval($data[4]);

					$quotaArray = CampaignStoreQuota::getQuotaRecord($offerID, $storeCode, $startAt, $endAt);

					$record = null;
					$count = count($quotaArray);
					if ($count > 0)  {$record = $quotaArray[0];}

					if ($record != null)  {

						$found = true;
						$quotaIssued = intval($record->quota_issued);
					}
				}

				//  data[0] = Quota issued
				//  data[1] = Status
				$data[] = $quotaIssued;

				//  Add status column to data
				if ($quota < $quotaIssued || $startAtValid == false || $endAtValid == false)  {
					$data[] = "error";
					$errorCount++;
					$confirmImportDisable = "disabled";
				}  else  {
					if ($found == true)  {$data[] = "exists";}
					else  {$data[] = "normal";}
				}

				$dataArray[] = $data;
			}
			fclose($handle);

			//  No data found, disable import button
			if (count($dataArray) == 0)  {
				$confirmImportDisable = "disabled";
			}
		}

		return view('foso.campaigns.offer_quotas_confirm', [
			"confirmImportDisable" => $confirmImportDisable,
			"errorCount" => $errorCount,
			"offerCode" => $offerCode,
			"dataArray" => $dataArray,
			"uniqid" => $uniqid,
			"offer" => $offer,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function offerCouponPoolPage(Request $request)  {

		$offerCode = $request->offer_code;
		$offer = CampaignOffer::getOffer($offerCode);
		if (!$offer)  {abort(404);}

		$usage = CampaignCouponPool::select(
			"store_code",
			DB::raw('count(*) as total'),
			DB::raw('count(NULLIF(mobile, "")) as used')
			)
		->where('offer_id', $offer->id)
		->groupBy('store_code')
		->get();

		return view('foso.campaigns.offer_coupon_pool', [
			"offerCode" => $offerCode,
			"offer" => $offer,
			"usage" => $usage,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function offerCouponPoolConfirmPage(Request $request)  {

		$offerCode = $request->offer_code;
		$uniqid = $request->get("uniqid");

		$offer = CampaignOffer::getOffer($offerCode);
		$offerID = $offer->id;

		//  Load CSV filename from database
		$uploader = UploadFileLog::where('uniqid', $uniqid)->first();
		$file = 'app/uploads/'.$uploader->name;

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Confirming quota file [$file] for Offer id #$offerID ", "Offer");

		$row = 0;
		$errorCount = 0;
		$dataArray = array();
		if (($handle = fopen(storage_path($file), "r")) !== FALSE)  {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

				$row++;
				if ($row <= 1)  {continue;}

				$uniqueCode = $data[0];
				$store_code = $data[1];
				$mobile = $data[2];
				$uniqueName = $data[3];

				$found = false;

				$existingSearchInPool = CampaignCouponPool::where('offer_id', $offerID)
					// ->where('store_code', $store_code)
					->where('unique_code', $uniqueCode)
					->count();

				if ($existingSearchInPool > 0) {
					$found = true;
				}

				//  Add status column to data
				if ($found == true)  {$data[] = "exists";}
				else  {$data[] = "normal";}

				$dataArray[] = $data;
			}
			fclose($handle);
		}

		return view('foso.campaigns.offer_coupon_pool_confirm', [
			"offerCode" => $offerCode,
			"dataArray" => $dataArray,
			"uniqid" => $uniqid,
			"offer" => $offer,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function offerWhatsAppPage(Request $request)  {

		$offerCode = $request->offer_code;
		$offer = CampaignOffer::getOffer($offerCode);
		if (!$offer)  {abort(404);}

		if (null !== $request->input("from"))  {$fromDate = $request->input("from");}
		else  {$fromDate = date("Y-m-d", strtotime("-7 days"));}

		if (null !== $request->input("to"))  {$toDate = $request->input("to");}
		else  {$toDate = date("Y-m-d");}

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Read FOSO campaign offer WhatsApp page from [$fromDate] to [$toDate]", "Offer");

		return view('foso.campaigns.offer_whatsapp_list', [
			"offerCode" => $offerCode,
			"fromDate" => $fromDate,
			"toDate" => $toDate,
			"offer" => $offer,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function offerCustomerJourneyPage(Request $request)  {

		$offerCode = $request->offer_code;
		$offer = CampaignOffer::getOffer($offerCode);
		$offer->ini = parse_ini_file("./offers/".$offer->offer_name."/offer.ini", true);
		$masterJourneyNodes = CampaignMasterJourney::getJourney($offer->id);
		$quickreplylist = CampaignQuickReply::getList();
		// TODO: select the template
		$selectedTemplate = "";
		foreach ($masterJourneyNodes as $node){
			if ($node->type == 250){
				$selectedTemplate = "1";
			}
		}

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Read FOSO campaign offer chatbot journey page for Offer id #$offer->id", "Offer");

		$trigger = "";
		if (isset($offer->ini["settings"]["whatsapp_trigger_message"]))  {
			$trigger = $offer->ini["settings"]["whatsapp_trigger_message"];
		}

		$length = mb_strlen($trigger);
		if ($length > 10)  {

			$head = mb_substr($trigger, 0, 4);
			$tail = mb_substr($trigger, $length-4, 4);
			$trigger = $head."...".$tail;
		}

		return view('foso.campaigns.offer_chatbot_journey', [
			"master_journey_nodes" => $masterJourneyNodes,
			"quickReplyTemplate" => $quickreplylist,
			"selectedTemplate" => $selectedTemplate,
			"offerCode" => $offerCode,
			"trigger" => $trigger,
			"offer" => $offer,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function offerINIPage(Request $request)  {

		$offerCode = $request->offer_code;
		$offer = CampaignOffer::getOffer($offerCode);
		//	$ini = file_get_contents("./offers/".$offer->offer_name."/offer.ini");

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Read FOSO campaign offer ini page for Offer id #".$offer->id, "Offer");

		$iniReadOnly = "readonly";
		if ($user->roles->first()->name == "Super-Administrator")  {$iniReadOnly = "";}

		$disk = Storage::disk('offer');
		$ini = $disk->get($offer->offer_name."/offer.ini");

		return view('foso.campaigns.offer_ini', [
			"iniReadOnly" => $iniReadOnly,
			"offerCode" => $offerCode,
			"offer" => $offer,
			"ini" => $ini,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function bannerlistPage(Request $request) {
		//  Activity log
		// $user = Auth::user();
		// FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Read FOSO Banner listing page", "Banner");

		if (null !== $request->input("from"))  {$fromDate = $request->input("from");}
		else  {$fromDate = date("Y-m-d", strtotime("-30 days"));}

		if (null !== $request->input("to"))  {$toDate = $request->input("to");}
		else  {$toDate = date("Y-m-d", strtotime("+30 days"));}

		return view('foso.banner.bannerlist', [
			"fromDate" => $fromDate,
			"toDate" => $toDate,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function bannerlistAPI(Request $request) {

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

		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Visit FOSO Banner listing page from [$fromDate] to [$toDate] ", "Banner");

		$array = CampaignBanner::getList($fromDate, $toDate);

		$dataArray = array();
		foreach ($array as $row){

			$imageUrl = json_decode($row->image_url, true);
			$targetUrl = json_decode($row->target_url, true);

			$imagetemp = "";
			if (!is_null($imageUrl)){
				if ($row->type=="key-visuals" && isset($imageUrl["mobile"])){
					$imagetemp = $imageUrl["mobile"];
				}else if($row->type == "banners" && isset($imageUrl["image"]) ){
					$imagetemp = $imageUrl["image"];
				}
				$imagetemp = substr($imagetemp, 1);
			}
	
			$dataArray[] = array(
				$row->id, $row->started_at, $row->ended_at, 
				$row->type, $row->weight, 
				// $row->target_url, $row->image_url,
				$targetUrl, $imagetemp,

				//  Must be the last one
				route("foso.banner.settings.html", ["id"=>$row->id]),
			);
		}

		//  Output now
		$response["data"] = $dataArray;
		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function bannerSettingsPage(Request $request){

		$id = $request->id;
		$tempbanner = CampaignBanner::where('id', $id)->first();
		$image1 = '';
		$image2 = '';
		$target1 = '';
		$target2 = '';
		$bannerType = '';
		$tempWeight = 0;
		
		//  banner start date and end date, default last for 30 days
		if ($id == '0'){

			//  Activity log
			$user = Auth::user();
			FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Visit FOSO Campaign Banner - Go to create new banner settings page", "Banner");

			// $createDateTime = date("Y-m-d H:i:s");
			$startDate = date("Y-m-d");
			$startTime = "00:00:00";
			$endDate = date("Y-m-d", strtotime("+30 days"));
			$endTime = "23:59:59";

		}else{

			//  Activity log
			$user = Auth::user();
			FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Visit FOSO Campaign Banner - Go to banner id #[$id] settings page", "Banner");

			// $createDateTime = $tempbanner->created_at;
			// $updateDateTime = $tempbanner->updated_at;
			$startDate = substr($tempbanner->started_at, 0, 10);
			$startTime = substr($tempbanner->started_at, -8, 5);
			$endDate = substr($tempbanner->ended_at, 0, 10);
			$endTime = substr($tempbanner->ended_at, -8, 5);
			$bannerType = $tempbanner->type;
			$tempWeight = $tempbanner->weight;

			$imageUrl = json_decode($tempbanner->image_url, true);
			$targetUrl = json_decode($tempbanner->target_url, true);

			if ($tempbanner->type=='key-visuals'){
				if(isset($imageUrl['mobile'])){$image1 = $imageUrl['mobile'];}
				if(isset($imageUrl['desktop'])){$image2 = $imageUrl['desktop'];}
				if(isset($targetUrl['mobile'])){$target1 = $targetUrl['mobile'];}
				if(isset($targetUrl['desktop'])){$target2 = $targetUrl['desktop'];}
			}else{
				if(isset($imageUrl['image'])){$image1 = $imageUrl['image'];}
				if(isset($targetUrl['url'])){$target1 = $targetUrl['url'];}
			}
		}

		//----------------------------------------------------------------------------------------
		return view('foso.banner.bannersettings', [
			"id" => $id,
			// "createDateTime" => $createDateTime,
			// "updateDateTime" => $updateDateTime,
			"startedDate" => $startDate,  
			"startedTime" => $startTime,
			"endedDate" => $endDate, 
			"endedTime" => $endTime,
			"image1" => $image1,
			"image2" => $image2,
			"target1" => $target1,
			"target2" => $target2,
			"bannerType" => $bannerType,
			"currentweight" => $tempWeight,
			"campaignBanner" => $tempbanner,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function saveBannerSettingsAPI(Request $request){

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);
		
		//  Check if all required parameters are available
		$parameterArray = array(
			"id", "startedDate", "startedTime", "endedDate", "endedTime",  "bannerType", "bannerweight"
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

		$id = $request->id;
		$banner = CampaignBanner::firstOrCreate(['id' => $id]);
		$currectType = $request->input("bannerType");

		if ($id == '0'){
			$oldType = $currectType;
			$remark = "Create new banner with id #".$banner->id." in FOSO Campaign banner settings page";
		}else{
			$oldType = $banner->type;
			$remark = "Update banner id #".$banner->id." in FOSO Campaign banner settings page";
		}

		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), $remark, "Banner");

		//----------------------------------------------------------------------------------------
		//  Save 
		$banner->started_at = $request->input("startedDate")." ".$request->input("startedTime").":00";
		$banner->ended_at = $request->input("endedDate")." ".$request->input("endedTime").":59";
		$banner->type = $currectType;
		$banner->weight = $request->input("bannerweight");

		$imgUrlArray = json_decode($banner->image_url, true);
		$targetUrlArray = json_decode($banner->target_url, true);

		$imgUrlPath1 = $request->pathimg1;
		$imgUrlPath2 = $request->pathimg2;
		$targetUrlPath1 = $request->targeturl1;
		$targetUrlPath2 = $request->targeturl2;
		// dd($request);
		$imgNew1 = '';
		$imgNew2 = '';

		if ($oldType == $currectType){

			if ($currectType == 'key-visuals'){

				// handlie image1 
				if (strlen($imgUrlPath1)>0){
					if ($imgUrlPath1 == "deleted"){
						File::delete(public_path($imgUrlArray["mobile"]));
					}else{
						$file1 = Storage::disk('local')->get('uploads/'.$imgUrlPath1);
						if ($file1 == true){
							$extension = strtolower(substr($imgUrlPath1, -4));
							$randomStr = FOSOMainController::generateRandomString(16);
							$imgNew1 = "/website/key-visuals/".$banner->id.$randomStr.$extension;
							File::move(storage_path('app/uploads/'.$imgUrlPath1), public_path($imgNew1));
						}
					}
					$imgUrlArray["mobile"] = $imgNew1 ;
				}

				// handlie image1 
				if (strlen($imgUrlPath2)>0){
					if ($imgUrlPath2 == "deleted"){
						File::delete(public_path($imgUrlArray["desktop"]));
					}else{
						$file2 = Storage::disk('local')->get('uploads/'.$imgUrlPath2);
						if ($file2 == true){
							$extension = strtolower(substr($imgUrlPath2, -4));
							$randomStr = FOSOMainController::generateRandomString(16);
							$imgNew2 = "/website/key-visuals/".$banner->id.$randomStr.$extension;
							File::move(storage_path('app/uploads/'.$imgUrlPath2), public_path($imgNew2));
							
						}
					}
					// save
					$imgUrlArray["desktop"] = $imgNew2 ;
				}

				// save
				$targetUrlArray["mobile"]=$targetUrlPath1;
				$targetUrlArray["desktop"]=$targetUrlPath2;

			}else{

				// handlie image1 ONLY
				if (strlen($imgUrlPath1)>0){
					if ($imgUrlPath1 == "deleted"){
						File::delete(public_path($imgUrlArray["image"]));
					}else{
						$file1 = Storage::disk('local')->get('uploads/'.$imgUrlPath1);
						if ($file1 == true){
							$extension = strtolower(substr($imgUrlPath1, -4));
							$randomStr = FOSOMainController::generateRandomString(16);
							$imgNew1 = "/website/banners/".$banner->id.$randomStr.$extension;
							File::move(storage_path('app/uploads/'.$imgUrlPath1), public_path($imgNew1));
						}
					}
					// save
					$imgUrlArray["image"] = $imgNew1 ;
				}
				// save
				$targetUrlArray["url"] = $targetUrlPath1;
			}

		}else{
			
			//from key-visuals to "banners"
			if ($oldType == 'key-visuals'){

				// handle image1
				// the image changed
				if (strlen($imgUrlPath1)>0){

					if ($imgUrlPath1 == "deleted"){
						File::delete(public_path($imgUrlArray["mobile"]));
						
					}else{
						$file1 = Storage::disk('local')->get('uploads/'.$imgUrlPath1);
						if ($file1 == true){
							$extension = strtolower(substr($imgUrlPath1, -4));
							$randomStr = FOSOMainController::generateRandomString(16);
							$imgNew1 = "/website/banners/".$banner->id.$randomStr.$extension;
							File::move(storage_path('app/uploads/'.$imgUrlPath1), public_path($imgNew1));
						}
					}

				}else{
					// the image no change
					$passImg1 = $imgUrlArray["mobile"];
					$imgNew1 = str_replace("key-visuals", "banners", $passImg1);
					File::move(public_path($passImg1), public_path($imgNew1));
				}

				// clear the old desktop image for last type status
				$passImg2 = $imgUrlArray["desktop"];
				File::delete(public_path($passImg2));

				// save
				$imgUrlArray = ["image" => $imgNew1];
				$targetUrlArray = ["url" => $targetUrlPath1];

			}else{ 
				//from banners to "key-visuals"

				// handle image 1
				if (strlen($imgUrlPath1)>0){
					if ($imgUrlPath1 == "deleted"){
						File::delete(public_path($imgUrlArray["image"]));

					}else{
						$file1 = Storage::disk('local')->get('uploads/'.$imgUrlPath1);
						if ($file1 == true){
							$extension = strtolower(substr($imgUrlPath1, -4));
							$randomStr = FOSOMainController::generateRandomString(16);
							$imgNew1 = "/website/key-visuals/".$banner->id.$randomStr.$extension;
							File::move(storage_path('app/uploads/'.$imgUrlPath1), public_path($imgNew1));
						}
					}
				}else{

					$passImg1 = $imgUrlArray["image"];
					$imgNew1 = str_replace("banners", "key-visuals", $passImg1);
					File::move(public_path($passImg1), public_path($imgNew1));
				}

				// handle image 2
				if (strlen($imgUrlPath2)>0){
					$file2 = Storage::disk('local')->get('uploads/'.$imgUrlPath2);
					if ($file2 == true){
						$extension = strtolower(substr($imgUrlPath2, -4));
						$randomStr = FOSOMainController::generateRandomString(16);
						$imgNew2 = "/website/key-visuals/".$banner->id.$randomStr.$extension;
						File::move(storage_path('app/uploads/'.$imgUrlPath2), public_path($imgNew2));
					}
				}else{ // if there are no new image for desktop
					$passImg2 = $imgUrlArray["image"];
					$imgNew2 = str_replace("banners", "key-visuals", $passImg2);
				}
				// save
				$imgUrlArray = ["mobile" => $imgNew1, "desktop" => $imgNew2, ];
				$targetUrlArray = ["mobile" => $targetUrlPath1, "desktop" => $targetUrlPath2, ];
			}
		}

		if((strlen($imgUrlPath1)+strlen($imgUrlPath2))>0){$banner->image_url = json_encode($imgUrlArray);}
		$banner->target_url = json_encode($targetUrlArray);
		
		$banner->save();

		//----------------------------------------------------------------------------------------
		//  Output now
		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
		
	}

	//----------------------------------------------------------------------------------------
	public function bannerResourcesUploadAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$id = $request->id;
		$type = $request->bannertype;
		$imagetype = $request->filename;

		if (isset($request->remove))  {

			// $fileURL = $request->default;

			// $extension = strtolower(substr($fileURL, -4));
			// if ($extension != ".png" && $extension != ".jpg")  {

			// 	$response["status"] = -1;
			// 	$response["message"] = "Invalid filename...";
			// 	return response()->json($response);
			// }

			$banner = CampaignBanner::where('id', $id)->first();

			$imgUrl = json_decode($banner->image_url, true);

			if ($type == 'banners'){
				$filename = $imgUrl["image"];
				$imgUrl["image"] = "";

			}else if($type == 'key-visuals'){
				if ($imagetype == 'image1'){
					$filename =  $imgUrl['mobile'];
					$imgUrl['mobile'] = "";

				}else if($imagetype == 'image2'){
					$filename =  $imgUrl['desktop'];
					$imgUrl['desktop'] = "";
				}

			}else{
				//  Fail
				$response["status"] = -15;
				$response["message"] = "Unknown banner type...";
				return response()->json($response);
			}

			//  Fail
			if (!File::exists(public_path($filename)))  {
				$response["status"] = -10;
				$response["message"] = "Unable to remove file...";
				return response()->json($response);
			}

			$response["status"] = 0;
			$response["message"] = "File remove success";
			return response()->json($response);
		}

		//----------------------------------------------------------------------------------------
		$log = new CampaignFileUploadController;
		$result = $log->upload($request);

		if (strtolower($result['status']) == "ok" && isset($result['uniqid'])) {
			$log = UploadFileLog::where('uniqid', $result['uniqid'])
				->first();

			//  get the temp thumbnail with the random name and put file to /uploads
			$file = Storage::disk('local')->get('uploads/'.$log->name);

			// 2023.03.27 Kay 
			// resize image where upload
			$maxwidth = 448; // for banner or kv image 1
			$maxheight = 282; // for banner or kv image 1
			if($type == 'key-visuals' && $imagetype == 'image2'){
					$maxwidth = 1194;
					$maxheight = 356;
			}

			$this->checktoResizeUploadedImage(storage_path('app/uploads/'.$log->name), $maxwidth, $maxheight);
			// 2023.03.27 end 

			$response["status"] = 10;
			$response["message"] = "New thumbnail file put on the uploads, with name [$log->name]";

			// --- clear the useless file long than 30 days in disk('local')
			collect(Storage::disk('local')->listContents('uploads', true))
				->each(function($fileClear) {

				if ($fileClear['timestamp'] < now()->subDays(30)->getTimestamp()) {  
				// if ($fileClear['timestamp'] < now()->subMinute(15)->getTimestamp()) {  
					Storage::disk('local')->delete($fileClear['path']);
				}
			});
		}
		return response()->json($result);
	}

	//----------------------------------------------------------------------------------------
	public function stopBannerLaunch(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		//  Load redemption record
		$id = $request->id;
		$banner = CampaignBanner::where('id', $id)->first();

		if ($banner == null)  {
			$response["status"] = -10;
			$response["message"] = "Banner [$id] record not found...";
			return response($response);
		}

		if ($banner->weight >= 0 )  {
			$banner->weight = 0 - $banner->weight;
			$banner->save();
		}

		//----------------------------------------------------------------------------------------
		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Update banner id #$id to 'Stop launching' ", "Banner");

		//----------------------------------------------------------------------------------------
		//  Finally
		$response["status"] = 10;
		$response['message'] = "Done, STOP launcing now";
		return response($response);
	}

	//----------------------------------------------------------------------------------------
	public function resumeBannerLaunch(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		//  Load redemption record
		$id = $request->id;
		$banner = CampaignBanner::where('id', $id)->first();

		if ($banner == null)  {
			$response["status"] = -10;
			$response["message"] = "Banner [$id] record not found...";
			return response($response);
		}

		if ($banner->weight <= 0 )  {
			$banner->weight = 0 - $banner->weight;
			$banner->save();
		}

		//----------------------------------------------------------------------------------------
		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Update banner id #$id to 'Resume launching' ", "Banner");

		//----------------------------------------------------------------------------------------
		//  Finally
		$response["status"] = 10;
		$response['message'] = "Done, RESUME launcing now";
		return response($response);
	}

	//----------------------------------------------------------------------------------------
	public function dashboardPage(Request $request)  {
		return view('foso.campaigns.dashboard');
	}

	//----------------------------------------------------------------------------------------
	public function reportsAllCouponPage(Request $request)  {

		if (null !== $request->input("from"))  {$fromDate = $request->input("from");}
		else  {$fromDate = date("Y-m-d", strtotime("-1 days"));}

		if (null !== $request->input("to"))  {$toDate = $request->input("to");}
		else  {$toDate = date("Y-m-d", strtotime("+1 days"));}

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Read FOSO campaign report all coupon page from [$fromDate] to [$toDate]", "Offer");

		return view('foso.campaigns.reports_all_coupons', [
			"fromDate" => $fromDate,
			"toDate" => $toDate,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function keyVisualsPage(Request $request)  {
		return view('foso.campaigns.landing.keyvisuals');
	}

	//----------------------------------------------------------------------------------------
	public function topicsPage(Request $request)  {
		return view('foso.campaigns.landing.topics');
	}

	//----------------------------------------------------------------------------------------
	public function categoriesPage(Request $request)  {
		return view('foso.campaigns.landing.categories');
	}

	//----------------------------------------------------------------------------------------
	public function bannersPage(Request $request)  {
		return view('foso.campaigns.landing.banners');
	}

	//----------------------------------------------------------------------------------------
	public function hotOffersPage(Request $request)  {
		return view('foso.campaigns.landing.hotoffers');
	}

	//----------------------------------------------------------------------------------------
	public function readMePage(Request $request)  {
		return view('foso.campaigns.read_me');
	}

	//----------------------------------------------------------------------------------------
	// 	Card Number: 5547241545834201
	// 	EXP: 2512
	// 	CVV2/CVC2: 942
	public function faqPage(Request $request)  {

		$timeout = date("ymdHis", strtotime("+24 hours"));

		//  CCBA system limited 1-15 digits
		$orderID = "89".$timeout.rand(0, 9);

		//  http://124.127.94.56:18101/CCBIS/B2CMainPlat_00?
		//  MERCHANTID=105000059990027&POSID=313375473&BRANCHID=010741100&ORDERID=622255327412860&PAYMENT=1.20&CURCODE=344&TXCODE=OBS001&REMARK1=Fimmick testing&REMARK2=Please ignore&TIMEOUT=210731235959
		//  &MAC=1d28715f2a2266aabe4b7e40dafe7b7d&CCB_IBSVersion=V6&DETAILS=Kinnso
		$transactionCode = env("CCBA_TRANSACTION_CODE", "");
		$currencyCode = env("CCBA_CURRENCY_CODE", "");
		$merchantID = env("CCBA_MERCHANTID", "");
		$publicKey = env("CCBA_PUBLIC_KEY", "");
		$branchID = env("CCBA_BRANCHID", "");
		$posID = env("CCBA_POSID", "");
		$host = env("CCBA_PAYMENT_URL", "");

		$content = "MERCHANTID=".$merchantID.
			"&POSID=".$posID.
			"&BRANCHID=".$branchID.
			"&ORDERID=".$orderID.								// Unique order number
			"&PAYMENT=1.20".
			"&CURCODE=".$currencyCode.
			"&TXCODE=".$transactionCode.
			"&REMARK1=Fimmick".									// Need escape()
			"&REMARK2=".										// Reserved for CCBA
			"&TIMEOUT=".$timeout;

		$dataForEncrypt = $content."&PUB=".$publicKey;
		$mac = md5($dataForEncrypt);

		$paymentURLCCBA = $host.$content."&MAC=".$mac."&CCB_IBSVersion=V6&DETAILS=Fimmick-Kinnso-Payment-Test";

		//----------------------------------------------------------------------------------------
		return view('foso.campaigns.faq', [
			"paymentURLCCBA" => $paymentURLCCBA,

			"transactionCode" => $transactionCode,
			"currencyCode" => $currencyCode,
			"merchantID" => $merchantID,
			"publicKey" => $publicKey,
			"branchID" => $branchID,
			"posID" => $posID,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function whatsAppQueuePage(Request $request)  {

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Visit FOSO campaign WhatsApp queue page");

		if (null !== $request->input("from"))  {$fromDate = $request->input("from");}
		else  {$fromDate = date("Y-m-d", strtotime("-1 days"));}

		if (null !== $request->input("to"))  {$toDate = $request->input("to");}
		else  {$toDate = date("Y-m-d", strtotime("+1 days"));}

		return view('foso.campaigns.whatsapp_queue', [
			"fromDate" => $fromDate,
			"toDate" => $toDate,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function whatsAppInboundPage(Request $request)  {

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Visit FOSO campaign WhatsApp inbound page");

		if (null !== $request->input("from"))  {$fromDate = $request->input("from");}
		else  {$fromDate = date("Y-m-d", strtotime("-7 days"));}

		if (null !== $request->input("to"))  {$toDate = $request->input("to");}
		else  {$toDate = date("Y-m-d", strtotime("+7 days"));}

		return view('foso.campaigns.whatsapp_inbound', [
			"fromDate" => $fromDate,
			"toDate" => $toDate,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function manageToolPage(Request $request)  {

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Visit FOSO campaign manage tool page");

		$port = ($_SERVER["SERVER_PORT"] == "80" || $_SERVER["SERVER_PORT"] == "443") ? "" : ":".$_SERVER["SERVER_PORT"];
		$scheme = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? "https" : "http";
		$baseURL = $scheme."://".$_SERVER["SERVER_NAME"].$port."/";

		return view('foso.campaigns.managetool', [
			"baseURL" => $baseURL,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function marketingListPage(Request $request)  {

		if (null !== $request->input("from"))  {$fromDate = $request->input("from");}
		else  {$fromDate = date("Y-m-d", strtotime("-7 days"));}

		if (null !== $request->input("to"))  {$toDate = $request->input("to");}
		else  {$toDate = date("Y-m-d");}

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Read FOSO marketing listing page from [$fromDate] to [$toDate]", "Marketing");

		return view('foso.marketing.list', [
			"fromDate" => $fromDate,
			"toDate" => $toDate,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function marketingListCreatePage(Request $request)  {

		$count = 0;
		$createButtonState = " disabled";

		$listName = "";
		if (null !== $request->input("name"))  {$listName = $request->input("name");}

		$filename = "marketing_list.csv";
		if (null !== $request->input("file"))  {
			$filename = $request->input("file");
			$count |= 1;
		}

		$uniqueID = "";
		if (null !== $request->input("uid"))  {
			$uniqueID = $request->input("uid");
			$count |= 2;
		}

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Create FOSO marketing listing page with CSV file [$filename] for id #$uniqueID ", "Marketing");

		if ($count == 3)  {$createButtonState = "";}

		return view('foso.marketing.list_create', [
			"createButtonState" => $createButtonState,
			"filename" => $filename,
			"uniqueID" => $uniqueID,
			"listName" => $listName,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function marketingWhatsAppBlastPage(Request $request)  {
		$dataArray = MarketingList::getListNameArray();

		$passwordArray = array(
			"Connecticutian",
			"Consanguineous",
			"Embourgeoisement",
			"Impedimenta",
			"Jackasseries",
			"Myrmecophilous",
			"Omphaloskepsis",
			"Polyphiloprogenitive",
			"Psychotomimetic",
			"Tergiversation",
			"Trichotillomania",
			"Xenotransplantation",
		);
		$length = count($passwordArray);
		$index = rand(0, $length-1);
		$password = $passwordArray[$index];

		return view('foso.marketing.whatsapp_blast', [
			"dataArray" => $dataArray,
			"password" => $password,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function membersSearchPage(Request $request)  {

		$mobile = "";
		if (null !== $request->input("mobile"))  {$mobile = $request->input("mobile");}

		return view('foso.members.search', [
			"mobile" => $mobile,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function membersDetailPage(Request $request)  {

        $path = $request->path();
        $mobile = str_replace("foso/members/", "", $path);

        $record = Member::getMemberByMobile($mobile);
        if ($record == null)  {return "Member not found.";}

        // Kay --2022.07.29 add
        $monthNow = date('m');
        $yearNow = date('Y');

        if ($monthNow <= 6){
            $period1 = $yearNow."-06-30 23:59:59";
            $period2 = $yearNow."-12-31 23:59:59";
        }else{
            $period1 = $yearNow."-12-31 23:59:59";
            $period2 = ($yearNow+1)."-06-30 23:59:59";
        }

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Read FOSO member detail page for mobile [$mobile] ", "User");

        return view('foso.members.detail', [
            "mobile" => $mobile,
            "record" => $record,
            "period1" => $period1,
            "period2" => $period2,
        ]);
    }


	//----------------------------------------------------------------------------------------
	public function appUserPage(Request $request)  {
		return view('foso.app.user');
	}

	//----------------------------------------------------------------------------------------
	public function appUserDetailPage(Request $request)  {
		$path = $request->path();
		$id = str_replace("foso/app/user/", "", $path);

		$record = AppUser::find($id);
		if ($record == null)  {return "User not found.";}

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Read FOSO AppUser detail page for AppUser id #$id ", "User");

		return view('foso.app.user_detail', [
			"id" => $id,
			"record" => $record,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function appUserChangePassword(Request $request)  {
		$email = $request->input('email', '');
		if(!$email) {
			return ["OK" => false,'message' => 'Email must not empty.'];
		}
		$password = $request->input('password', '');
		$password_confirm = $request->input('password_confirm', '');
		if( $password !== $password_confirm) {
			return ["OK" => false,'message' => 'Password and password confirm must be same.'];
		}
		$appUser = AppUser::getUserByEmail($email)->update([
			'password' => $password,
		]);

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Update FOSO AppUser password for AppUser id #$appUser->id ", "User");

		return [
			"OK" => true,
			'message' => 'Change App user password success.',
			'user' => $appUser
		];
	}

	//----------------------------------------------------------------------------------------
	public function appUserDelete(Request $request)  {
		$email = $request->input('email', '');
		if(!$email) {
			return ["OK" => false,'message' => 'Email must not empty.'];
		}
		AppUser::getUserByEmail($email)->delete();

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Delete FOSO AppUser with id #$appUser->id ", "User");

		return [
			"OK" => true,
			'message' => 'Delete App user success.',
		];
	}

	//----------------------------------------------------------------------------------------
	public function scanLogPage(Request $request)  {
		return view('foso.app.scan_log');
	}

	//----------------------------------------------------------------------------------------
	public function campaignsWhatsAppSimulatorPage(Request $request)  {
		$mobile = env("WHATSAPP_SIMULATOR_NUMBER", "");
		// $mobile = config('app.whatsappNumber');
		return view('foso.campaigns.whatsapp_simulator', [
			"mobile" => $mobile,
		]);
	}

	//----------------------------------------------------------------------------------------
	//--- 2022.08.04 Kay -- Start up
	public function thirdPartyEventPage(Request $request) {

		if (null !== $request->input("from"))  {$fromDate = $request->input("from");}
		else  {$fromDate = date("Y-m-d", strtotime("-1 month"));}

		if (null !== $request->input("to"))  {$toDate = $request->input("to");}
		else  {$toDate = date("Y-m-d");}

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Read Third party event listing from [$fromDate] to [$toDate] ", "Third Party");		

		return view('foso.thirdparty.eventlist', [
			"fromDate" => $fromDate,
			"toDate" => $toDate,
		]);

	}

	public function uaformCSVDownloadAPI(Request $request) {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$toDate = null;
		$fromDate = null;
		if (null !== $request['toDateUpdate'])  {$toDate = $request['toDateUpdate'];}
		if (null !== $request['fromDateUpdate'])  {$fromDate = $request['fromDateUpdate'];}

		$csvfilePath = FormUA::getCSVPath($fromDate, $toDate);

		if($csvfilePath  == null){
			$response["status"] = -20;
			$response["message"] = "The csv report file cannot be generated.";
		}

		$response["status"] = 0;
		$response["message"] = "Done, csv report file generated.";
		$response["filepath"] = $csvfilePath;

		// every time enter th function will also clear the older files
		collect(Storage::disk('public')->listContents('foso/2022_uaf_imoney_csv', true))
			->each(function($file) {
			if ($file['extension'] == 'csv' && $file['timestamp'] < now()->subDays(15)->getTimestamp()) {
			// if ($file['extension'] == 'csv' && $file['timestamp'] < now()->subMinute(10)->getTimestamp()) { // for testing
				Storage::disk('public')->delete($file['path']);
			}
		});

		return response()->json($response);

	}

	//----------------------------------------------------------------------------------------
	//  Mark: APIs
	//----------------------------------------------------------------------------------------
	public function loginAPI(Request $request)  {

		// Google2FA + GoogleLogin
		if(isset($request['idtoken']) && !isset($request['email'])) {

			$token = $request['idtoken'];
			$id = config('services.google.client_id');
			$secret = config('services.google.client_secret');
			$client = new Google_Client(['client_id' => $id]);
			$payload = $client->verifyIdToken($token);
			if ($payload) {

				$email = $payload['email'];
				$user = FosoUser::where('email', $email)->first();
				if($user) {
					Auth::login($user);
					$name = $payload['name'];
					$picture = $payload['picture'];
					// set GoogleLogin flag
					Auth::user()->setGoogleLoginAttribute(1);
					Auth::user()->setGoogleNameAttribute($name);
					Auth::user()->setGooglePictureAttribute($picture);
					return 'Login:'.$user['email'];
				} else {
					$message = "This account is unauthorised.";
					return '<label class="error mt-2">This user is unauthorised.</label>';
				}
			} else {

				return $this->failedLogin($request);
			}
		}

		if(config('auth.google2fa')) {
			$tolerance = 0;
			$otp = $request['one_time_password'];
			$authenticator = app('PHPGangsta_GoogleAuthenticator');
			$user = new FosoUser();
			$secret = json_decode($user->getGoogle2faKeyByEmail($request['email']));
			if(isset($secret->google_2fa_key)) {
				$verification = $authenticator->verifyCode($secret->google_2fa_key, $otp, $tolerance);
			} else {
				return $this->failedLogin($request);
			}
			if ($verification) {
					return $this->login($request);
			} else {
					return $this->failedLogin($request);
					// return redirect(URL()->previous());
			}
		} else {

			return $this->login($request);
		}
	}

	//----------------------------------------------------------------------------------------
	public function logoutAPI(Request $request)  {

		// GoogleLogin
		if(Auth::user()) {
			Auth::user()->setGoogleLoginAttribute(0);
			Auth::user()->setGoogleNameAttribute(null);
			Auth::user()->setGooglePictureAttribute(null);
		}

		return $this->logout($request);
	}

	//----------------------------------------------------------------------------------------
	public function failedLogin(Request $request)  {
		$this->incrementLoginAttempts($request);
		if (method_exists($this, 'hasTooManyLoginAttempts') &&
			$this->hasTooManyLoginAttempts($request))  {

			$this->fireLockoutEvent($request);
			return $this->sendLockoutResponse($request);
		}
		return $this->sendFailedLoginResponse($request);
	}

	//----------------------------------------------------------------------------------------
	public function offerListAPI(Request $request)  {
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

		$array = CampaignOffer::getList($fromDate, $toDate);

		$dataArray = array();
		foreach ($array as $row)  {

			$openCount = 0;
			$json = $row->statistic_data;
			if ($json != null)  {

				$dictionary = json_decode($json, true);
				if (isset($dictionary["open"]))  {

					$openCount = intval($dictionary["open"]);
				}
			}

			$dataArray[] = array(
				$row->id, $row->start_at, $row->end_at, $row->offer_code, $row->offer_name,
				$row->offer_title, $row->offer_subtitle, $row->blade_folder,
				$row->code_type, $row->channel_expiry, $row->confirmation_method,
				$row->quota, $row->quota_issued, $openCount,

				//  Must be the last one
				route("foso.campaigns.offer.settings.html", ["offer_code"=>$row->offer_code]),
			);
		}

		//  Output now
		$response["data"] = $dataArray;
		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	//  Status:
	//  -10 = Unable to create resource folder...
	public function saveOfferSettingsAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		//  Check if all required parameters are available
		$parameterArray = array(
			"startDate", "startTime", "endDate", "endTime", "offerName", "offerTitle",
			"bladeFolder", "quota", "couponType", "brandName"
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

		//----------------------------------------------------------------------------------------
		//  Try to get offer record first
		$ini = null;
		$newRecord = false;
		$offerCode = $request->input("offerCode");
		$disk = Storage::disk('offer');

		//  Offer code in URL
		$originalOfferCode = $request->offer_code;

		$offerName = $request->input("offerName");
		$bladeFolder = $request->input("bladeFolder");

		$offer = CampaignOffer::getOffer($originalOfferCode);
		if ($offer == null)  {

			//  Not found, create one
			$newRecord = true;

			$offer = new CampaignOffer();
			$offer->offer_code = $offerCode;
			$offer->offer_name = $offerName;

			//  Activity log
			$user = Auth::user();
			FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Create new offer in FOSO offer setting page with offer name [$offerName]", "Offer");

			//  Create resources folder
			$publicPath = public_path();
			$targetPath = $publicPath."/offers/".$offerName;

			//  Check if folder already exists
			if (file_exists($targetPath))  {

				//  Output now
				$response["status"] = -20;
				$response["message"] = "Directory name '$offerName' already exists, please use another name...";
				return response()->json($response);
			}

			$result = mkdir($targetPath, 0775);
			if ($result !== true)  {

				$response["status"] = -10;
				$response["message"] = "Unable to create resource folder...";
				return response()->json($response);
			}

			//  Copy default medias
			$resourcePath = resource_path();
			$sourcePath = $resourcePath."/views/campaigns/".$bladeFolder."/medias";
			$fileArray = scandir($sourcePath);
			foreach ($fileArray as $file)  {

				if ($file == ".")  {continue;}
				if ($file == "..")  {continue;}

				$sourceFilePath = $sourcePath."/".$file;
				if (!is_readable($sourceFilePath))  {continue;}

				if (is_dir($sourceFilePath))  {

					//  It is folder in medias, create it
					$targetFilePath = $targetPath."/".$file;
					mkdir($targetFilePath, 0775);
					continue;
				}

				$targetFilePath = $targetPath."/".$file;
				copy($sourceFilePath, $targetFilePath);
			}

			$resourceURL = route("foso.campaigns.offer.resources.html", ["offer_code"=>$offerCode]);
			$response["resourceURL"] = $resourceURL;

			//----------------------------------------------------------------------------------------
			//  Read default settings from .ini
			// $iniFilePath = $publicPath."/offers/".$offerName."/offer.ini";

			$iniFilePath = $offer->offer_name."/offer.ini";
			$ini = parse_ini_string($disk->get($iniFilePath), true);

			if (isset($ini["settings"]["code_type"]))  {
				$offer->code_type = $ini["settings"]["code_type"];
			}  else  {
				$offer->code_type = json_encode(array("type"=>"static"));
			}

			if (isset($ini["settings"]["channel_expiry"]))  {
				$offer->channel_expiry = $ini["settings"]["channel_expiry"];
			}  else  {
				$offer->channel_expiry = json_encode(array("default"=>"+7 days"));
			}

			if (isset($ini["settings"]["confirmation_method"]))  {
				$offer->confirmation_method = $ini["settings"]["confirmation_method"];
			}  else  {
				$offer->confirmation_method = json_encode(array("default"=>"whatsapp"));
			}

			if (isset($ini["default"]["tnc"]))  {
				$offer->tnc = $ini["default"]["tnc"];
			}

			//  Brand name is saved in .ini file, not in database
// 			if (isset($ini["settings"]["brand_name"]))  {
// 				$offer->brandName = $ini["settings"]["brand_name"];
// 			}  else  {
// 				$offer->brandName = json_encode(array("default"=>"dreyers"));
// 			}

			//  If more than one tracking code in same channel, then we should
			//  read from .ini first and then override
			if (isset($ini["settings"]["tracking_code"]))  {
				$offer->tracking_code = $ini["settings"]["tracking_code"];
			}  else  {
				$offer->tracking_code = json_encode(array(
					"googleAnalytics" => array(""),
					"facebookPixel" => array(""),
					"gtm" => array(""),
				));
			}

			if (isset($ini["settings"]["webhook"]))  {
				$offer->webhook = $ini["settings"]["webhook"];
			}  else  {
				$offer->webhook = json_encode(array(
					"offerRegistration" => route("webhook.offer.registration.json"),
					"couponActivation" => route("webhook.coupon.activation.json"),
				));
			}

			//  Clone master journey below once have offer ID
		}

		//----------------------------------------------------------------------------------------
		//  Save
		$offer->offer_code = $offerCode;
		$offer->start_at = $request->input("startDate")." ".$request->input("startTime").":00";
		$offer->end_at = $request->input("endDate")." ".$request->input("endTime").":59";
		$offer->offer_name = $offerName;
		$offer->offer_title = $request->input("offerTitle");
		$offer->blade_folder = $bladeFolder;
		$offer->coupon_type = $request->input("couponType");
		$offer->likeCounter = $request->input('likeCounter', 0);
		$offer->tag = $request->input("offerTags");
		if ($offer->tag == null)  {$offer->tag = "";}

		if (empty($request->input("tnc")) == false)  {
			$offer->tnc = $request->input("tnc");
		}

		if (empty($request->input("category")) == false)  {
			$offer->category = $request->input("category");
		}
		if (empty($request->input("filter")) == false)  {
			$offer->filter = $request->input("filter");
		}

		//  Quota is now calculate after upload CSV
// 		$offer->quota = intval($request->input("quota"));

		if ($request->input("offerSubtitle") == null)  {$offer->offer_subtitle = "";}
		else  {$offer->offer_subtitle = $request->input("offerSubtitle");}

		$offer->bundled_offers_id = $request->input("bundledOffersID");

		//  Update tracking codes
		$dictionary = json_decode($offer->tracking_code, true);

		$array = array("");
		if (isset($dictionary["facebookPixel"]))  {
			$array = $dictionary["facebookPixel"];
			if ($request->input("facebookPixel") == null)  {$array[0] = "";}
			else  {$array[0] = $request->input("facebookPixel");}
		}
		$dictionary["facebookPixel"] = $array;

		$array = array("");
		if (isset($dictionary["googleAnalytics"]))  {
			$array = $dictionary["googleAnalytics"];
			if ($request->input("googleAnalytics") == null)  {$array[0] = "";}
			else  {$array[0] = $request->input("googleAnalytics");}
		}
		$dictionary["googleAnalytics"] = $array;

		$array = array("");
		if (isset($dictionary["gtm"]))  {
			$array = $dictionary["gtm"];
			if ($request->input("gtm") == null)  {$array[0] = "";}
			else  {$array[0] = $request->input("gtm");}
		}
		$dictionary["gtm"] = $array;

		$offer->tracking_code = json_encode($dictionary);

		//  Update webhook URLs
		$dictionary = json_decode($offer->webhook, true);

		if ($request->input("offerRegistrationWebhook") == null)  {$dictionary["offerRegistration"] = "";}
		else  {$dictionary["offerRegistration"] = $request->input("offerRegistrationWebhook");}

		if ($request->input("couponActivationWebhook") == null)  {$dictionary["couponActivation"] = "";}
		else  {$dictionary["couponActivation"] = $request->input("couponActivationWebhook");}

		$offer->webhook = json_encode($dictionary);

		//  Machine learning related
		if ($request->has("machineLearningLabels"))  {
			$offer->ml_labels = $request->input("machineLearningLabels");
		}

		//  Save view counter
		$viewCounter = intval($request->input("viewCounter"));
		$statisticDictionary = array("open"=>$viewCounter);
		if (empty($offer->statistic_data) == false)  {

			$statisticDictionary = json_decode($offer->statistic_data, true);
			$statisticDictionary["open"] = $viewCounter;
		}
		$offer->statistic_data = json_encode($statisticDictionary);

		//  Save to database table
		$result = $offer->save();

		//----------------------------------------------------------------------------------------
		//  Clone master journey
		if ($newRecord == true)  {

			$offerID = $offer->id;

			$journeyFilePath = $offer->offer_name."/journey.sql";

			//  2022.05.13 Pacess
			//  Import journey if available
			if ($disk->exists($journeyFilePath))  {
			//  2022.05.13 End

				$content = $disk->get($journeyFilePath);
				$array = explode("\n", $content);
				foreach ($array as $line)  {

					$line = trim($line);
					$length = strlen($line);
					if ($length < 2)  {continue;}

					$line = "\$csv=array".substr($line, 0, $length-1).";";
					eval($line);

					$ordering = $csv[5];
					$nodeName = $csv[6];
					$type = $csv[7];

					//  Need this otherwise JSON format is broken
					$nodeSettings = str_replace("\\\\n", "\\n", $csv[8]);
					$nodeSettings = str_replace("\\\"", "\"", $nodeSettings);

					$record = new CampaignMasterJourney();
					$record->offer_id = $offerID;
					$record->ordering = $ordering;
					$record->node_name = $nodeName;
					$record->type = $type;
					$record->node_settings = $nodeSettings;
					$record->save();
				}

			//  2022.05.13 Pacess
			//  Import journey if available
			}
			//  2022.05.13 End
		}

		//----------------------------------------------------------------------------------------
		//  Some settings are saved in .ini, should also update them
		if ($request->has("whatsappTriggerMessage") ||
			$request->has("whatsappNotificationMessage") ||
			$request->has("whatsappReminderTime") ||
			$request->has("whatsappReminderMessage") ||
			$request->has("whatsappOutOfQuotaMessage") ||
			$request->has("whatsappExpiryMessage") ||
			$request->has("whatsappReferralNotificationMessage") ||

			$request->has("dailyReportRecipients") ||
			$request->has("dailyCouponRecipients") ||
			$request->has("dailyCouponDateExtend") ||

			$request->has("brandName") ||
			$request->has("description"))  {

			if ($ini == null)  {

				// $ini = $disk->get($offer->offer_name."/offer.ini");
				// $iniFilePath = "./offers/".$offer->offer_name."/offer.ini";
				$iniFilePath = $offer->offer_name."/offer.ini";
				$ini = parse_ini_string($disk->get($iniFilePath), true);
			}

			if ($request->has("whatsappTriggerMessage"))  {
				$ini["settings"]["whatsapp_trigger_message"] = str_replace(["\n", "\r"], "", $request->input("whatsappTriggerMessage"));
			}

			if ($request->has("whatsappNotificationMessage"))  {
				$ini["offer_thankyou"]["notification_whatsapp_content"] = $request->input("whatsappNotificationMessage");
			}

			if ($request->has("whatsappReminderTime"))  {
				$ini["offer_thankyou"]["reminder_notification_whatsapp_time"] = $request->input("whatsappReminderTime");
			}

			if ($request->has("whatsappReminderMessage"))  {
				$ini["offer_thankyou"]["reminder_notification_whatsapp_content"] = $request->input("whatsappReminderMessage");
			}

			if ($request->has("whatsappReferralNotificationMessage"))  {
				$ini["offer_thankyou"]["referral_notification_whatsapp_content"] = $request->input("whatsappReferralNotificationMessage");
			}

			if ($request->has("whatsappOutOfQuotaMessage"))  {
				$ini["settings"]["whatsapp_out_of_quota_message"] = $request->input("whatsappOutOfQuotaMessage");
			}

			if ($request->has("whatsappExpiryMessage"))  {
				$ini["coupon_expired"]["whatsapp_expiry_message"] = $request->input("whatsappExpiryMessage");
			}

			if ($request->has("journeyFinishMessage"))  {
				$ini["settings"]["journey_finish_message"] = $request->input("journeyFinishMessage");
			}

			//  Report settings
			if ($request->has("dailyOutboundReportRecipients"))  {
				$ini["settings"]["daily_outbound_report_recipients"] = $request->input("dailyOutboundReportRecipients");
			}

			if ($request->has("dailyCouponReportRecipients"))  {
				$ini["settings"]["daily_coupon_report_recipients"] = $request->input("dailyCouponReportRecipients");
			}

			if ($request->has("dailyReportPasswordRecipients"))  {
				$ini["settings"]["daily_report_password_recipients"] = $request->input("dailyReportPasswordRecipients");
			}

			if ($request->has("dailyCouponDateExtend"))  {
				$ini["settings"]["daily_coupon_date_extend"] = $request->input("dailyCouponDateExtend");
			}

			if ($request->has("brandName"))  {
				$ini["settings"]["brand_name"] = $request->input("brandName");
			}

			if ($request->has("description"))  {

				//  Somehow description contains HTML with ", it will break .ini
				$description = $request->input("description");
				$description = str_replace("\"", "'", $description);
				$ini["settings"]["offer_description"] = $description;
			}

			if ($request->has("sharingMessage"))  {
				$ini["settings"]["sharing_message"] = $request->input("sharingMessage");
			}

			//  Theme related
			if ($request->has("offerThemeButtonColor"))  {
				$ini["settings"]["theme_button_color"] = $request->input("offerThemeButtonColor");
			}
			if ($request->has("offerThemeButtonTextColor"))  {
				$ini["settings"]["theme_button_text_color"] = $request->input("offerThemeButtonTextColor");
			}
			if ($request->has("offerThemeButtonHoverColor"))  {
				$ini["settings"]["theme_button_hover_color"] = $request->input("offerThemeButtonHoverColor");
			}

			$filePath = "./offers/".$iniFilePath;
			write_ini_file($filePath, $ini);
			$iniString = file_get_contents($filePath);

			$disk->put($iniFilePath, $iniString);
		}

		//----------------------------------------------------------------------------------------
		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Update offer settings of offer code [$offerCode] name [$offerName]", "Offer");

		//----------------------------------------------------------------------------------------
		//  Output now
		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function cloneOfferAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		try {
			$offerCode = $request->input("offerCode");
			$response['offerCode'] = $offerCode;
			$offer = CampaignOffer::getOffer($offerCode);
			$offerName = $offer->offer_name;
			$date = date('YmdHis');
			// clone offer folder with name = YYYYMMDDHHIISS
			$disk = Storage::disk('offer');
			$files = $disk->allFiles($offerName);
			foreach($files as $file) {
				$disk->copy($file, str_replace($offerName, $date, $file));
			}
			// end clone files
			// clone record to campaign_offers
			$newOffer = $offer->replicate();
			$newOffer->created_at = date('Y-m-d H:i:s');
			$newOffer->offer_code = $date;
			$newOffer->offer_name = $date;
			// $newOffer->offer_title = $date;
			// $newOffer->offer_subtitle = $date;
			$newOffer->quota = 0;
			$newOffer->quota_issued = 0;
			$newOffer->save();
			// end clone record
			// clone journeys from campaign_master_journeys
			$nodes = CampaignMasterJourney::getNodes($offer->id);
			foreach($nodes as $node) {
				$newNode = $node->replicate();
				$newNode->offer_id = $newOffer->id;
				$newNode->save();
			}
			// end clone journeys

			//----------------------------------------------------------------------------------------
			//  Activity log
			$user = Auth::user();
			FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Clone offer with offer code [$offerCode]...", "Offer");

		} catch (Throwable $e) {
			report($e);
			$response['status'] = -1;
			$response['message'] = $e->getMessage();
			return $response;
		}

		//----------------------------------------------------------------------------------------
		//  Finally
		$response["status"] = 0;
		$response['message'] = "OK";

		//  201 = Created
		return response($response, 201);
	}

	//----------------------------------------------------------------------------------------
	public function offerResourcesAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		//  Check if all required parameters are available
		$parameterArray = array(
			"subject", "subject_first_paragraph", "subject_readmore_paragraph",
			"highlight_subject", "highlight_paragraph",
			"facebook_link", "instagram_link",
			"follow_subject",
		);
		$availableCount = 0;
		foreach ($parameterArray as $parameter)  {
			if (null !== $request->input($parameter))  {$availableCount++;}
		}

		if ($availableCount == 0)  {
			$response["status"] = -1;
			$response["message"] = "Parameter not found...";
			return response()->json($response);
		}

		//----------------------------------------------------------------------------------------
		//  Load offer record
		$disk = Storage::disk('offer');
		$ini = null;

		//  Offer code in URL
		$offerCode = $request->offer_code;
		$offer = CampaignOffer::getOffer($offerCode);
		if ($offer == null)  {

			$response["status"] = $status;
			$response["message"] = "Offer '$offerCode' not found...";
			return response()->json($response);
		}
		$response["offerCode"] = $offerCode;

		//----------------------------------------------------------------------------------------
		//  Load INI file
		$iniFilePath = $offer->offer_name."/offer.ini";
		$ini = parse_ini_string($disk->get($iniFilePath), true);

		foreach ($parameterArray as $parameter)  {

			if ($request->has($parameter) == false)  {continue;}

			//  Somehow description contains HTML with ", it will break .ini
			$value = $request->input($parameter);
			$value = str_replace("\"", "'", $value);
			$ini["offer_details"][$parameter] = $value;
		}

		$filePath = "./offers/".$iniFilePath;
		write_ini_file($filePath, $ini);
		$iniString = file_get_contents($filePath);

		$disk->put($iniFilePath, $iniString);

		//----------------------------------------------------------------------------------------
		//  Finally
		$response["status"] = 0;
		$response["message"] = "Done!";
		return response($response);
	}

	//----------------------------------------------------------------------------------------
	public function outOfQuotaAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		//  Load offer record
		$offerCode = $request->input("offerCode");
		$offer = CampaignOffer::getOffer($offerCode);
		$response['offerCode'] = $offerCode;

		//  Set max quota to current quota
		$offer->quota = $offer->quota_issued;
		$offer->save();

		//----------------------------------------------------------------------------------------
		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Update offer with offer code [$offerCode] to 'Out of Quota'", "Offer");

		//----------------------------------------------------------------------------------------
		//  Finally
		$response["status"] = 0;
		$response['message'] = "OK";
		return response($response, 200);
	}

	//----------------------------------------------------------------------------------------
	public function resumeQuotaAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		//  Load offer record
		$offerCode = $request->input("offerCode");
		$offer = CampaignOffer::getOffer($offerCode);
		$response['offerCode'] = $offerCode;

		if ($offer == null)  {
			$response["status"] = -1;
			$response["message"] = "Offer record not found...";
			return response($response, 500);
		}

		//  Get offer type
		if ($offer->coupon_type == "pre-generated")  {

			//  Pre-generated offer, count records
			$maxQuota = CampaignCouponPool::getMaxQuota($offer->id);

		}  else  {

			//  Randomly generated offer, count sum
			$maxQuota = CampaignStoreQuota::getMaxQuota($offer->id);
		}
		$offer->quota = $maxQuota;
		$offer->save();

		//----------------------------------------------------------------------------------------
		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Update offer with offer code [$offerCode] to 'Resume Quota'", "Offer");

		//----------------------------------------------------------------------------------------
		//  Finally
		$response["status"] = 0;
		$response['message'] = "OK";
		return response($response, 200);
	}

	//----------------------------------------------------------------------------------------
	public function clearAllWhitelistedAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$messageArray = array();
		$offerCode = $request->offer_code;

		//----------------------------------------------------------------------------------------
		//  Clear coupon
		$dictionary = $this->offerCouponClearWhitelisted($offerCode);
		if ($dictionary["status"] != 0)  {

			$response["status"] = 0;
			$response['message'] = "Unable to clear coupon whitelist...";
			return response($response, 500);
		}
		$messageArray[] = $dictionary["message"];

		//----------------------------------------------------------------------------------------
		//  Clear coupon pool
		$dictionary = $this->offerCouponPoolClearWhitelisted($offerCode);
		if ($dictionary["status"] != 0)  {

			$response["status"] = 0;
			$response['message'] = "Unable to clear coupon pool whitelist...";
			return response($response, 500);
		}
		$messageArray[] = $dictionary["message"];

		//----------------------------------------------------------------------------------------
		//  Clear journey
		$dictionary = $this->offerJourneyClearWhiteListedRecords($offerCode);
		if ($dictionary["status"] != 0)  {

			$response["status"] = 0;
			$response['message'] = "Unable to clear coupon pool whitelist...";
			return response($response, 500);
		}
		$messageArray[] = $dictionary["message"];

		//----------------------------------------------------------------------------------------
		//  Finally
		$message = implode("\n\n", $messageArray);

		$response["status"] = 0;
		$response['message'] = $message;
		return response($response, 200);
	}

	//  Kay 2022.09.28 -- export the offer files in zip file to download
	//----------------------------------------------------------------------------------------
    public function exportOfferFileAPI(Request $request)  {

        $offerCode = $request->offer_code;
        // get content from DB about the offer
		$offer = CampaignOffer::getOffer($offerCode);

		$zip = new \ZipArchive();
		if(!File::isDirectory(storage_path("app/uploads/offerzip/"))) {
			 //creates directory if not exists
			 File::makeDirectory(storage_path("app/uploads/offerzip/"), 0777, true, true);
		}
		$zipfilename = storage_path("app/uploads/offerzip/".$offer->offer_name.".zip");
        $pathdir = public_path("/offers/".$offer->offer_name);

		$zip->open($zipfilename, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

		if (is_dir($pathdir)){

			$files = new \RecursiveIteratorIterator(
				new \RecursiveDirectoryIterator($pathdir), \RecursiveIteratorIterator::LEAVES_ONLY
			);
			
			foreach ($files as $name => $file){
				// Get real and relative path for current file
				$filePath = $file->getRealPath();
				$relativePath = substr($filePath, strlen($pathdir) + 1);

				if (!$file->isDir()){
					// Add current file to archive
					$zip->addFile($filePath, $relativePath);
				}else {
					if($relativePath !== false)
						$zip->addEmptyDir($relativePath);
				}
			}

		
			$contentArray = $offer->toArray();
			// unset($contentArray['id']);
			// unset($contentArray['created_at']);
			// unset($contentArray['updated_at']);
			// unset($contentArray['deleted_at']);
			// unset($contentArray['quota']);
			// unset($contentArray['quota_issued']);

			$contentJSON = json_encode($contentArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
			// $contentJSON = $offer->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
			file_put_contents(storage_path("app/uploads/offerzip/"."campaign_offer.txt"), print_r($contentJSON, true));
			$zip->addFile(storage_path("app/uploads/offerzip/"."campaign_offer.txt"), "campaign_offer.txt");
			
			// get the journey from DB
			$offerJourney = CampaignMasterJourney::getJourney($offer->id);
			$journey = $offerJourney->toArray();

			
			$journeyJSON = json_encode($journey, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
			file_put_contents(storage_path("app/uploads/offerzip/"."campaign_journey.txt"), print_r($journeyJSON, true));
			$zip->addFile(storage_path("app/uploads/offerzip/"."campaign_journey.txt"), "campaign_journey.txt");
			$zip->close();

			Storage::disk('local')->delete("uploads/offerzip/campaign_offer.txt");
			Storage::disk('local')->delete("uploads/offerzip/campaign_journey.txt");

			//  Activity log
			$user = Auth::user();
			FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Export offer id #$offer->id as zip to download", "Offer");

			// clear old files which is generated more than 7 days
			collect(Storage::disk('local')->listContents('uploads/offerzip', true))
				->each(function($file) {
					if ($file['type'] == 'file' && $file['timestamp'] < now()->subDays(7)->getTimestamp()) {
					// if ($file['type'] == 'file' && $file['timestamp'] < now()->subMinutes(1)->getTimestamp()) { //for testing
						Storage::disk('local')->delete($file['path']);
					}
			});

        	return response()->download($zipfilename);
		}

		return redirect()->back();
    }

	public function offerImportAPI(Request $request){

		// unzip the file and checking 
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

			//  get the temp file with the random name
			$file = Storage::disk('local')->get('uploads/'.$log->name);
			$zipfilepath = 'app/uploads/'.$log->name;

			$filename = explode(".", $result['filename']);
			$offerName = $filename[0];

			// create new offer by offer code first
			$unqiue = false;
			$code = "";
			$count = 0;
			while(($unqiue == false || strlen($code)<=0) && $count<15){
				$code = FOSOMainController::generateRandomString(16);
				$temp = CampaignOffer::where("offer_code",$code)->first();
				if (is_null($temp)){$unqiue = true;}
				$count++;
			}
			$offer = CampaignOffer::create(['offer_code' => $code]);
			$offerID = $offer->id;

			// check name whether is used before, if yes, the dateTime add to name
			// handle too long file name (no more than 32)
			$tempOff = CampaignOffer::where("offer_name",$offerName)->first();
			$filesInServer = Storage::disk('offer')->directories("");
			if (!is_null($tempOff) || in_array($offerName, $filesInServer)){

				if (strlen($offerName)>26){$offerName = substr($offerName, 0, 26);}
				$tempEnd = str_pad($offerID, 5, "0", STR_PAD_LEFT);
				$offerName .= "_".$tempEnd;

			} else {

				if (strlen($offerName)>32){$offerName = substr($offerName, 0, 32);}

			}
			$offer->offer_name = $offerName;

			$zip = new \ZipArchive;
			$path = public_path("offers/".$offerName);
			if ($zip->open(storage_path($zipfilepath)) === TRUE) {
				$zip->extractTo($path."/");

				$zip->close();
			}
			Storage::disk('local')->delete('uploads/'.$log->name); //clear the CSV file	
		}

		$contentPath = $path."/campaign_offer.txt";
		$journayPath = $path."/campaign_journey.txt";
		$iniPath = $path."/offer.ini";

		//check whether campaign_offer.txt exists in import zip file and not empty
		if (!file_exists($contentPath)){
			Storage::disk('offer')->deleteDirectory($offerName);
			$response["status"] = -10;
			$response["message"] = "Import failed, campaign_offer.txt is not found.";
			return response()->json($response);
		}

		if (filesize($contentPath)==0){
			Storage::disk('offer')->deleteDirectory($offerName);
			$response["status"] = -15;
			$response["message"] = "Import failed, campaign_offer.txt is empty.";
			return response()->json($response);
		}

		//check whether offer.ini exists in import zip file and not empty
		if (!file_exists($iniPath)){
			Storage::disk('offer')->deleteDirectory($offerName);
			$response["status"] = -20;
			$response["message"] = "Import failed, offer.ini is not found.";
			return response()->json($response);
		}

		if (filesize($iniPath)==0){
			Storage::disk('offer')->deleteDirectory($offerName);
			$response["status"] = -25;
			$response["message"] = "Import failed, offer.ini is empty.";
			return response()->json($response);
		}

		// $offer = CampaignOffer::create(['offer_name' => $offerName]);
		//  Offer start date and end date, default last for 30 days
		$offer->start_at= date("Y-m-d")." 00:00:00";
		$offer->end_at = date("Y-m-d", strtotime("+30 days"))." 23:59:59";
		// $offer->offer_code = $code;

		$content = file_get_contents($contentPath);
		$contentArray = json_decode($content, true);

		$offer->coupon_type = isset($contentArray["coupon_type"])? $contentArray["coupon_type"]:"randomly-generated"; 
		$offer->offer_title = isset($contentArray["offer_title"])? $contentArray["offer_title"]:$offerName;
		$offer->offer_subtitle = isset($contentArray["offer_subtitle"])? $contentArray["offer_subtitle"]:$offerName;
		$offer->blade_folder = isset($contentArray["blade_folder"])? $contentArray["blade_folder"]:"";
		$offer->code_type = isset($contentArray["code_type"])? $contentArray["code_type"]:null;
		$offer->channel_expiry = isset($contentArray["channel_expiry"])? $contentArray["channel_expiry"]:null;
		$offer->confirmation_method = isset($contentArray["confirmation_method"])? $contentArray["confirmation_method"]:null;
		$offer->tnc = isset($contentArray["tnc"])? $contentArray["tnc"]:null;

		$offer->webhook = isset($contentArray["tracking_code"])? $contentArray["webhook"]:null;
		$offer->tracking_code = isset($contentArray["tracking_code"])? $contentArray["tracking_code"]:null;
		$offer->statistic_data = isset($contentArray["statistic_data"])? $contentArray["statistic_data"]:null;
		
		$offer->likeCounter = isset($contentArray["likeCounter"])? $contentArray["likeCounter"]:0;
		$offer->tag = isset($contentArray["tag"])? $contentArray["tag"]:null;
		$offer->ml_labels = isset($contentArray["ml_labels"])? $contentArray["ml_labels"]:null;
		$offer->category = isset($contentArray["category"])? $contentArray["category"]:null;
		$offer->filter = isset($contentArray["filter"])? $contentArray["filter"]:null;

		$offer->save();
		Storage::disk('offer')->delete($offerName."/campaign_offer.txt");

		$offerID = $offer->id;
		if (file_exists($journayPath)){
			$offerJourney = file_get_contents($journayPath);
			$journeyArray = json_decode($offerJourney, true);
			foreach($journeyArray as $journey){
				CampaignMasterJourney::saveJourneyNode($offerID, $journey["node_name"], $journey["type"], $journey["node_settings"]);
			}
			Storage::disk('offer')->delete($offerName."/campaign_journey.txt");
		}

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Create new offer id #$offerID by importing zip offer file", "Offer");
		
		$response["status"] = 1;
		$response["message"] = "Offer import successfully";
		$response["url"] = route("foso.campaigns.offer.settings.html", ["offer_code"=>$code]);
		return response()->json($response);

	}

	public function offerCollationPage(Request $request)  {

		// Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Read FOSO offer listing page - collation");

		return view('foso.campaigns.offer_collation');
	}

	public function offerCollationAPI(Request $request){

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);
		
		$filesInServer = Storage::disk('offer')->directories("");

		// common is for offer page structure, so should unset in list
		foreach ($filesInServer as $key => $value){
			if ($value == "common") {unset($filesInServer[$key]);}
		}

		$fileInList = CampaignOffer::getAllOfferName();
		
		// In Server, but not in list
		$offerClearInServe = array_diff($filesInServer, $fileInList );
		// In List, but not in Server
		$offerClearInList = array_diff($fileInList, $filesInServer );
		// exist in both sides
		$offerBoth = array_intersect($filesInServer, $fileInList );

		$dataArray = array();
		foreach ($offerBoth as $row)  {
			$dataArray[] = array(
				$row, "Normal", 0,
			);
		}
		foreach ($offerClearInServe as $row)  {
			$dataArray[] = array(
				$row, "Folder exists in server but no record",1,
			);
		}
		foreach ($offerClearInList as $row)  {
			$dataArray[] = array(
				$row, "Record in database but not exist in server", 2,
			);
		}
	
		$response["data"] = $dataArray;
		$response["status"] = 1;
		$response["message"] = "Done";
		return response()->json($response);
	}

	public function offerDeleteInServeAPI(Request $request){

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$offerName = $request->name;

		if (Storage::disk('offer')->exists($offerName)){
			Storage::disk('offer')->deleteDirectory($offerName);

			//  Activity log
			$user = Auth::user();
			FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Delete Offer Folder name [$offerName] in server for collation", "Offer");

			$response["status"] = 1;
			$response["message"] = "Done, ".$offerName." is deleted in Server.";
			return response()->json($response);
		}

		$response["status"] = -10;
		$response["message"] = $offerName." is not found in server.";
		return response()->json($response);

	}

	public function offerDeleteInRecordAPI(Request $request){

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$offerName = $request->name;
		$fileInList = CampaignOffer::getAllOfferName();

		if (in_array($offerName, $fileInList)){
			$record = CampaignOffer::where('offer_name',$offerName)->first();
			$record->delete();

			//  Activity log
			$user = Auth::user();
			FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Delete Offer record with name [$offerName] in DB table for collation", "Offer");

			$response["status"] = 1;
			$response["message"] = "Done, ".$offerName." is deleted in Campaign_Offers table.";
			return response()->json($response);
		}

		$response["status"] = -10;
		$response["message"] = $offerName." is not found in table.";
		return response()->json($response);
	}


	//----------------------------------------------------------------------------------------
	// 	_token: oVElyAgEVCLHSulhFSBMsMIthyT4jHRVhA3yBMuC
	// 	offerRegistrationWebhookType: 20
	// 	offerRegistrationNPickM: 14
	// 	offerRegistrationWebhookURL:
	// 	couponActivationWebhookType: 20
	// 	couponActivationReferralCount: 13
	public function saveOfferRulesAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		//  Get offer object with offer code first
		$offerCode = $request->offer_code;
		$offer = CampaignOffer::getOffer($offerCode);

		$original = json_decode($offer->webhook, true);

		$dictionary = $request->all();
		unset($dictionary["_token"]);

		$key = "couponActivationWebhookURL";
		if (isset($dictionary[$key]) && $dictionary[$key] == null)  {
			$dictionary[$key] = "";
		}

		$key = "offerRegistrationWebhookURL";
		if (isset($dictionary[$key]) && $dictionary[$key] == null)  {
			$dictionary[$key] = "";
		}

		$webhookDictionary = array_merge($original, $dictionary);
		$offer->webhook = json_encode($webhookDictionary);
		$offer->save();

		//----------------------------------------------------------------------------------------
		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Update Rules of offer code [$offerCode]", "Offer");

		//----------------------------------------------------------------------------------------
		//  Output now
		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function offerCouponPoolConfirmAPI(Request $request)  {
		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$offerCode = $request->offer_code;
		$uniqid = $request->get("uniqid");

		$offer = CampaignOffer::getOffer($offerCode);
		$offerID = $offer->id;

		//  Load CSV filename from database
		$uploader = UploadFileLog::where('uniqid', $uniqid)->first();
		$file = 'app/uploads/'.$uploader->name;

		if (($handle = fopen(storage_path($file), "r")) === FALSE)  {
			$response["status"] = -1;
			$response["message"] = "Unexpected error...";
			return response()->json($response);
		}

		$row = 0;
		$errorCount = 0;
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

			$row++;

			//  Skip header row
			if ($row <= 1)  {continue;}

			//  Get quota issued from database
			$a = 0;
			$uniqueCode = $data[$a++];
			$storeCode = $data[$a++];
			$mobile = $data[$a++];
			$uniqueName = $data[$a++];

			$parameterA = $data[$a++];
			$parameterB = $data[$a++];
			$parameterC = $data[$a++];

			$couponPool = CampaignCouponPool::where('offer_id', $offerID)
					// ->where('store_code', $storeCode)
					->where('unique_code', $uniqueCode)
					->first();

			$record = null;
			if ($couponPool != null)  {$record = $couponPool;}

			if ($record == null)  {
				$record = new CampaignCouponPool();
				$record->created_by = __FUNCTION__;
			}  else  {
				$record->updated_by = __FUNCTION__;
			}

			$record->offer_id = $offerID;
			$record->store_code = $storeCode;
			$record->unique_code = $uniqueCode;
			$record->mobile = $mobile;
			$record->unique_name = $uniqueName;
			$record->parameter_a = $parameterA;
			$record->parameter_b = $parameterB;
			$record->parameter_c = $parameterC;
			$record->save();
		}
		fclose($handle);

		//  Update total quota of the offer
		$totalQuota =  CampaignCouponPool::where('offer_id', $offerID)->count();

		$offer->quota = $totalQuota;
		$result = $offer->save();

		//----------------------------------------------------------------------------------------
		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Confirm offer code [$offerCode] quotas file [$file]", "Offer");

		//----------------------------------------------------------------------------------------
		//  Output now
		$response["status"] = 0;
		$response["totalQuota"] = $totalQuota;
		$response["message"] = "Import successfully";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	//  Delete coupon records that matches whitelist
	public function offerCouponPoolClearWhitelistedAPI(Request $request)  {
		$offerCode = $request->offer_code;
		$response = $this->offerCouponPoolClearWhitelisted($offerCode);
		return response()->json($response);
	}

	public function offerCouponPoolClearWhitelisted($offerCode)  {
		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$offer = CampaignOffer::getOffer($offerCode);
		$offerINI = parse_ini_file("./offers/".$offer->offer_name."/offer.ini", true);

		$limit = intval(env("FOSO_WHITELIST_LIMIT", "50"));

		$count = 0;
		$index = 0;
		$separator = " ";
		$removedArray = array();
		while ($index <= $limit)  {

			$index++;

			$key = sprintf("whitelist_mobile_%02d", $index);
			if (!isset($offerINI["coupon"][$key]))  {break;}

			$mobile = $offerINI["coupon"][$key];
			$affectedRows = CampaignCouponPool::deleteWithMobile($mobile);
			if ($affectedRows > 0)  {

				$removedArray[] = $mobile;
				$count++;
			}
		}

		//  Also restore quota
		if ($count > 0)  {

			if ($offer->quota_issued > $count)  {$offer->quota_issued -= $count;}
			else  {$offer->quota_issued = 0;}
			$offer->save();

			//----------------------------------------------------------------------------------------
			//  Activity log
			$user = Auth::user();
			FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Reset offer code [$offerCode]'s coupon pool whitelist, quota issued -$count");

			//----------------------------------------------------------------------------------------
			//  Refresh coupon quota issued value
			$issued = CampaignCouponPool::issuedQuota($offer->id, -1);
			$offer->quota_issued = $issued;
			$offer->save();

		}  else  {

			$removedArray[] = " Empty";
		}

		//----------------------------------------------------------------------------------------
		//  Output
		$response["status"] = 0;
		$response["message"] = "Coupon pool records with mobile numbers have been removed. Ref:".implode(", ", $removedArray);
		$response["dataArray"] = $removedArray;
		return $response;
	}

	//----------------------------------------------------------------------------------------
	public function offerCouponPoolImageUploadConfirmAPI(Request $request) {
		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$offerCode = $request->offer_code;
		$offer = CampaignOffer::getOffer($offerCode);

		if (!$request->has('filenameJSON')) {
			$response['status'] = -80;
			$response['message'] = "Please provide file names";
			return response()->json($response);
		}

		$filenamesArray = json_decode($request->filenameJSON);

		$resultArray = [];
		foreach ($filenamesArray as $filename) {

			$withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
			$coupon = CampaignCouponPool::getWithCode($withoutExt);

			$fileStatus = "";
			if (!$coupon) {
				$fileStatus = "not found";
			} else {

				$uniqueName = $coupon->unique_name;
				if ($uniqueName != "" || $uniqueName != NULL) {
					$fileStatus = "modify";
				} else {
					$fileStatus = "insert";
				}
			}

			$resultArray[] = ['code' => $withoutExt, 'status' => $fileStatus];
		}

		$response["status"] = 0;
		$response["message"] = "OK";
		$response['dataArray'] = $resultArray;
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function offerCouponPoolImageUploadAPI(Request $request) {
		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$allowExtensions = ['png'];

		$offerCode = $request->offer_code;
		$offer = CampaignOffer::getOffer($offerCode);

		$files = $request->file('files');

		if (!$request->hasFile('files')) {
			$response["status"] = -80;
			$response["message"] = "Did not receive any files";
			return response()->json($response);
		}

		$processedFiles = 0;
		foreach ($files as $file) {

			$originalFilename = $file->getClientOriginalName();
			$extension        = $file->getClientOriginalExtension();
			$filesize         = $file->getClientSize();
			$uniqid           = uniqid('upload_file_', true);

			// Check if uploaded file's extension is allowed.
			if (! in_array($extension, $allowExtensions)) {
				$response['message'] = 'Uploaded file\'s extension must be '.implode(', ', $allowExtensions).'.';
				Log::error(__FUNCTION__ .' Uploaded file\'s extension must be '.implode(', ', $allowExtensions).'.');
				return $response;
			}

			// Check if uploaded file' size is within max filesize.
			if ($filesize > 10 * 1024 * 1024) {
				$response['message'] = 'Uploaded file\'s size must be within 10MB.';
				Log::error(__FUNCTION__. ' Uploaded file\'s size must be within 10MB.');
				return $response;
			}

			$uniqueName = md5($originalFilename.time());
			$newFilename = $uniqueName.'.'.File::extension($originalFilename);

			$result = $file->move(public_path() . "/offers/$offer->offer_name/coupons", $newFilename);

			if ($result) {
				$log = new UploadFileLog;
				$log->uniqid        = $uniqid;
				$log->name          = $newFilename;
				$log->size          = $filesize;
				$log->extension     = $extension;
				$log->original_name = $originalFilename;
				$log->created_by    = __FUNCTION__;

				if ($log->save()) {
						$response['status']   = 0;
						$response['uniqid']   = $uniqid;
						$response['filename'] = $newFilename;
						$response["serverFilename"] = $newFilename;
				}
			}

			$originalWithoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $originalFilename);
			$updateCouponAffectedRows = CampaignCouponPool::where('unique_code', $originalWithoutExt)
				->where('offer_id', $offer->id)
				->update(['unique_name' => $uniqueName]);

			$processedFiles++;
		}

		$response["status"] = 0;
		$response["message"] = "Processed $processedFiles files";
		return response()->json($response);
	}

	// ---------------------------------------------------------------------------------------
	public function offerCouponPoolListAPI(Request $request) {
		$response = array(
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$offerCode = $request->offer_code;
		$offer = CampaignOffer::getOffer($offerCode);
		if ($offer == null)  {
			$response["status"] = -1;
			$response["message"] = "Offer record not found...";
			return response()->json($response);
		}

		$offerID = $offer->id;

		$array = CampaignCouponPool::getByOfferID($offerID);

		$max = 1000;
		$dataArray = array();
		foreach ($array as $row)  {

			$imageURL = $row->unique_name != NULL && $row->unique_name != ""
				? asset("offers/$offer->offer_name/coupons/$row->unique_name.png")
				: "";

			$dataArray[] = array(
				$row->created_at->toDateTimeString(), $row->created_by, $row->updated_at->toDateTimeString(), $row->updated_by,
				$row->offer_id, $row->store_code, $row->unique_code,
				$row->mobile, $row->unique_name, $imageURL,
				$row->parameter_a, $row->parameter_b, $row->parameter_c,
			);

			if ($max-- <= 0)  {break;}
		}

		//  Output now
		$response["data"] = $dataArray;
		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function offerCouponPoolUploadAPI(Request $request)  {
		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$offerCode = $request->offer_code;
		$offer = CampaignOffer::getOffer($offerCode);

		$uploader = new CampaignFileUploadController;
		$result = $uploader->upload($request);

		if (strtolower($result['status']) != "ok")  {
			$response["status"] = -1;
			$response["message"] = $result['message'];
			return;
		}

		//  Output now
		$response["data"] = $result;
		$response["status"] = 0;
		$response["message"] = "Upload successfully";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function offerCouponListAPI(Request $request)  {
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

		//  Get offer object with offer code first
		$offerID = 0;
		$array = null;
		$offerCode = $request->offer_code;
		if ($offerCode != "all")  {
			$offer = CampaignOffer::getOffer($offerCode);
			$offerID = $offer->id;
		}
		$array = CampaignCoupon::getListWithCreateAt($fromDate, $toDate, $offerID);

		$dataArray = array();
		foreach ($array as $row)  {

			//  create_at is default column in Model, it is Carbon type
			$createdAt = $row["created_at"]->toDateTimeString();

			$dataArray[] = array(
				$createdAt, $row->offer_id, $row->parent_offer_id, $row->coupon_order,
				$row->unique_code, $row->mobile, $row->email,
				$row->start_at, $row->use_at, $row->expiry_at,
				$row->form_data,
				$row->referrer_code, $row->referral_code, $row->referral_data,
			);
		}

		//  Output now
		$response["data"] = $dataArray;
		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function offerCouponCSVDownloadAPI(Request $request)  {

		$LIMIT_PER_TAKE = 500;

		$folder = storage_path("app/public/");
		$from = $request->from;
		$to = $request->to;

		$array = [];
		$columns = [];

		$offerID = 0;
		$offerCode = $request->offer_code;
		if ($offerCode != "all")  {

			$offerCode = $request->offer_code;
			$offer = CampaignOffer::getOffer($offerCode);
			$offerID = $offer->id;
		}

		$query = CampaignCoupon::where('expiry_at', '>=', $from.' 00:00:00')
			->where('start_at', '<=', $to.' 23:59:59');

		if ($offerID != 0)  {
			$query = $query->where('parent_offer_id', $offerID);
		}

		$query = $query->orderBy('created_at', 'DESC')
			->orderBy('unique_code', 'ASC')
			->orderBy('parent_offer_id', 'ASC')
			->orderBy('offer_id', 'ASC')
			->orderBy('coupon_order', 'ASC');

		//----------------------------------------------------------------------------------------
		$dataCount = $query->count();
		$loopCount = ($dataCount/$LIMIT_PER_TAKE);
		for ($i=0; $i<$loopCount; $i++)  {

			$coupons = $query->skip($i * $LIMIT_PER_TAKE)
				->take($LIMIT_PER_TAKE)
				->get();

			foreach ($coupons as $coupon)  {
				foreach ($coupon->toArray() as $key => $value) {
					if ($key != 'form_data')  {
						$columns[$key] = '';
					}
				}

				if ($coupon->form_data != null) {
					$formData = json_decode($coupon->form_data, true);
					if ($formData) {
						foreach ($formData as $key => $value) {

							switch ($key)  {
								// Which form field should be skip.
								case '_token':
								case 'mobile':
								case 'areaCode':
								case 'areaCodeConfirm':
								case 'mobileConfirm':
								case 'selectedChannel':  continue 2;
								default:
							}

							$key = Str::snake($key);

							//  Fix 'ID' become '_i_d'
							$searchArray = array("_i_d", "whats_app");
							$replaceArray = array("_id", "whatsapp");
							$key = str_replace($searchArray, $replaceArray, $key);

							$columns[$key] = '';
						}
					}
				}
			}
		}

		//----------------------------------------------------------------------------------------
		//  Sort array with key
		//  Skip columns for do not sort.
		$dontSortColumns = [
			'id',
			'created_at',
			'updated_at',
			'deleted_at',
			'offer_id',
			'parent_offer_id',
			'unique_code'
		];

		$columns2 = $columns;
		foreach ($columns as $key => $nothing)  {

			$skip = false;
			foreach ($dontSortColumns as $s)  {

				if ($key == $s)  {$skip = true;}
			}

			if (!$skip)  {unset($columns[$key]);}
		}

		foreach ($columns2 as $key => $nothing)  {

			$skip = true;
			foreach ($dontSortColumns as $s)  {

				if ($key == $s)  {$skip = false;}
			}

			if (!$skip)  {unset($columns2[$key]);}
		}

		ksort($columns2);
		$columns = Arr::collapse([
			$columns, $columns2,
		]);

		//----------------------------------------------------------------------------------------
		//  Begin append table contents to file
		$brandName = env("BRAND_NAME", "");
		$brandName = str_replace(" ", "_", $brandName);
		$filename = $brandName.'_Whatsapp_Offer_Coupons'.now()->format('YmdHis') .'.csv';
		$filePath = $folder.$filename;
		$handle = fopen($filePath, 'w');

		// write table head
		fputcsv($handle, array_keys($columns));

		$query = CampaignCoupon::where('expiry_at', '>=', $from.' 00:00:00')
			->where('start_at', '<=', $to.' 23:59:59');

		if ($offerID != 0)  {
			$query = $query->where('parent_offer_id', $offerID);
		}

		$query = $query->orderBy('created_at', 'DESC')
			->orderBy('unique_code', 'ASC')
			->orderBy('parent_offer_id', 'ASC')
			->orderBy('offer_id', 'ASC')
			->orderBy('coupon_order', 'ASC');

		// write table contents
// 		$loopCount = ($dataCount/$LIMIT_PER_TAKE);
		for ($i=0; $i<$loopCount; $i++)  {

			$coupons = $query->skip($i * $LIMIT_PER_TAKE)
				->take($LIMIT_PER_TAKE)
				->get();

			foreach ($coupons as $coupon)  {

				$record = [];
				if ($coupon->form_data == null)  {
					$formdata = null;
				} else {
					$formdata = json_decode($coupon->form_data);
				}

				// merge record with keys
				foreach ($columns as $key => $column)  {

					$key2 = Str::camel($key);
					if (isset($coupon->$key))  {
						$record[$key] = $coupon->$key;
					}  else if ($formdata && isset($formdata->$key2))  {
						$record[$key] = $formdata->$key2;
					}  else  {
						$record[$key] = null;
					}
				}
				fputcsv($handle, $record);
			}
		}

		//----------------------------------------------------------------------------------------
		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Download offer #$offerID coupon CSV file [$filename]", "Offer");

		//----------------------------------------------------------------------------------------
		// CSV download
		fclose($handle);
		return response()->download($filePath);
	}

	//----------------------------------------------------------------------------------------
	//  Delete coupon records that matches whitelist
	public function offerCouponClearWhitelistedAPI(Request $request)  {
		$offerCode = $request->offer_code;
		$response = $this->offerCouponClearWhitelisted($offerCode);
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function offerCouponClearWhitelisted($offerCode)  {
		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$offer = CampaignOffer::getOffer($offerCode);
		$offerINI = parse_ini_file("./offers/".$offer->offer_name."/offer.ini", true);

		$limit = intval(env("FOSO_WHITELIST_LIMIT", "50"));

		$index = 0;
		$removedArray = array();
		while ($index <= $limit)  {

			$index++;

			$key = sprintf("whitelist_mobile_%02d", $index);
			if (!isset($offerINI["coupon"][$key]))  {break;}

			$mobile = $offerINI["coupon"][$key];

			//  REMARK: Should be include offer?
			$couponArray = CampaignCoupon::getWithMobile($mobile);
			$count = count($couponArray);
			if ($count > 0)  {

				$removedArray[] = $mobile;

				foreach ($couponArray as $coupon)  {
					$coupon->forceDelete();
				}
			}
		}

		if (count($removedArray) == 0)  {
			$removedArray[] = " Empty";
		}

		//----------------------------------------------------------------------------------------
		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Reset offer code [$offerCode] whitelist", "Offer");

		//----------------------------------------------------------------------------------------
		//  Refresh coupon quota issued value
		$dataArray = CampaignCoupon::getList(null, null, $offer->id);
		$count = count($dataArray);
		$offer->quota_issued = $count;
		$offer->save();

		//----------------------------------------------------------------------------------------
		//  TODO: Also refresh store quota issued
		$storeArray = CampaignStoreQuota::getStoreListWithPeriod($offer->id);
		foreach ($storeArray as $store)  {

			$storeCode = $store->store_code;
			$startAt = $store->start_at;
			$endAt = $store->end_at;

			//  Get coupons of specific offer
			$count = CampaignCoupon::getIssuedByOfferID($offer->id, $store->store_code, $startAt, $endAt);
			$store->quota_issued = $count;
			$store->save();
		}

		//----------------------------------------------------------------------------------------
		//  Output
		$response["status"] = 0;
		$response["message"] = "Coupon records with mobile numbers have been removed. Ref:".implode(", ", $removedArray);
		$response["dataArray"] = $removedArray;
		return $response;
	}

	//----------------------------------------------------------------------------------------
	public function offerResourcesUploadAPI(Request $request)  {

		$offerCode = $request->offer_code;
		$offer = CampaignOffer::getOffer($offerCode);

		//  Check if remove file or
		if (isset($request->remove))  {

			//  Remove file
			$response = array(
				"timeStamp" => Date("YmdHis"),
				"apiName" => __FUNCTION__,
				"status" => -99,
				"message" => "Unexpected error...",
			);

			$fileURL = $request->default;
			$extension = strtolower(substr($fileURL, -4));
			if ($extension != ".png" && $extension != ".jpg")  {

				$response["status"] = -1;
				$response["message"] = "Invalid filename...";
				return response()->json($response);
			}

			//  File type ok, delete now
			$filePath = getcwd()."/offers/".$offer->offer_name."/".$request->filename.$extension;
			$result = false;
			if (file_exists($filePath))  {
				$result = unlink($filePath);
			}
			if ($result == false)  {

				//  Fail
				$response["status"] = -10;
				$response["message"] = "Unable to remove file...";
				return response()->json($response);
			}

			$response["status"] = 0;
			$response["message"] = "File remove success";
			return response()->json($response);
		}

		//----------------------------------------------------------------------------------------
		$log = new CampaignFileUploadController;
		$result = $log->upload($request);

		//  Move to offer folder if status is ok.
		if (strtolower($result['status']) == "ok" && isset($result['uniqid'])) {
			$log = UploadFileLog::where('uniqid', $result['uniqid'])
				->first();

			//  Save image to external server
			$file = Storage::disk('local')->get('uploads/'.$log->name);
			Storage::disk('offer')->put($offer->offer_name.'/'.$request->filename.'.'.$log->extension, $file);
			Storage::disk('local')->delete('uploads/'.$log->name);
		}
		return response()->json($result);
	}

	//----------------------------------------------------------------------------------------
	public function offerQuotasListAPI(Request $request)  {
		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$offerCode = $request->offer_code;
		$offer = CampaignOffer::getOffer($offerCode);
		if ($offer == null)  {
			$response["status"] = -1;
			$response["message"] = "Offer record not found...";
			return response()->json($response);
		}

		$offerID = $offer->id;

		$toDate = null;
		$fromDate = null;
		if (null !== $request->input("to"))  {$toDate = $request->input("to");}
		if (null !== $request->input("from"))  {$fromDate = $request->input("from");}

		$array = CampaignStoreQuota::getList($fromDate, $toDate, $offerID);

		$dataArray = array();
		foreach ($array as $row)  {

			$dataArray[] = array(
				$row->created_at, $row->created_by, $row->updated_at, $row->updated_by,
				$row->offer_id, $row->start_at, $row->end_at, $row->store_code,
				$row->ordering, $row->quota, $row->quota_issued,
				$row->store_name, $row->store_address,
			);
		}

		//  Output now
		$response["data"] = $dataArray;
		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function offerQuotasUploadAPI(Request $request)  {
		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$offerCode = $request->offer_code;
		$offer = CampaignOffer::getOffer($offerCode);

		$uploader = new CampaignFileUploadController;
		$result = $uploader->upload($request);

		if (strtolower($result['status']) != "ok")  {
			$response["status"] = -1;
			$response["message"] = $result['message'];
			return;
		}

		//  Output now
		$response["data"] = $result;
		$response["status"] = 0;
		$response["message"] = "Upload successfully";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function offerQuotasConfirmAPI(Request $request)  {
		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$offerCode = $request->offer_code;
		$uniqid = $request->get("uniqid");

		$offer = CampaignOffer::getOffer($offerCode);
		$offerID = $offer->id;

		//  Load CSV filename from database
		$uploader = UploadFileLog::where('uniqid', $uniqid)->first();
		$file = 'app/uploads/'.$uploader->name;

		if (($handle = fopen(storage_path($file), "r")) === FALSE)  {
			$response["status"] = -1;
			$response["message"] = "Unexpected error...";
			return response()->json($response);
		}

		$row = 0;
		$errorCount = 0;
		$successCount = 0;
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

			$row++;

			//  Skip header row
			if ($row <= 1)  {continue;}

			if ( count($data) < 7 )  {continue;}

			//  Get quota issued from database
			$startAt = $data[0];
			$endAt = $data[1];
			$storeCode = $data[2];
			$ordering = intval($data[3]);
			$quota = intval($data[4]);
			$storeName = $data[5];
			$storeAddress = $data[6];

			$quotaArray = CampaignStoreQuota::getQuotaRecord($offerID, $storeCode, $startAt, $endAt);

			$record = null;
			$count = count($quotaArray);
			if ($count > 0)  {$record = $quotaArray[0];}

			if ($record == null)  {
				$record = new CampaignStoreQuota();
				$record->created_by = __FUNCTION__;
			}  else  {
				$record->updated_by = __FUNCTION__;
			}
			$record->offer_id = $offerID;
			$record->start_at = $startAt;
			$record->end_at = $endAt;
			$record->store_code = $storeCode;
			$record->ordering = $ordering;
			$record->quota = $quota;
			$record->store_name = $storeName;
			$record->store_address = $storeAddress;
			$record->save();

			$successCount++;
		}
		fclose($handle);

		//  Update total quota of the offer
		$totalQuota = 0;
		$quotaArray = CampaignStoreQuota::getQuotaRecord($offerID, null, null, null);
		foreach ($quotaArray as $record)  {

			$quota = intval($record->quota);
			$totalQuota += $quota;
		}

		$offer->quota = $totalQuota;
		$result = $offer->save();

		//----------------------------------------------------------------------------------------
		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Confirm offer code [$offerCode] quotas file [$file]", "Offer");

		//----------------------------------------------------------------------------------------
		//  Output now
		$response["status"] = 0;
		$response["totalQuota"] = $totalQuota;
		$response["message"] = "Import successfully";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function offerWhatsAppAPI(Request $request)  {
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

		//  Get offer object with offer code first
		$offerCode = $request->offer_code;
		$offer = CampaignOffer::getOffer($offerCode);

		$offerID = $offer->id;
		$array = CampaignWhatsappMessageQueue::getList($fromDate, $toDate, $offerID);

		$dataArray = array();
		foreach ($array as $row)  {

			//  create_at is default column in Model, it is Carbon type
			$createdAt = $row["created_at"]->toDateTimeString();
			$scheduleAt = $row["schedule_at"];
			$cancelAt = $row["cancel_at"];
// 			if (null != empty($cancelAt))  {$cancelAt = $cancelAt->toDateTimeString();}
			$sendAt = $row["send_at"];
// 			if (null != empty($sendAt))  {$sendAt = $sendAt->toDateTimeString();}

			$dataArray[] = array(
				$row->id,
				$createdAt, $row->coupon_id, $row->mobile, $row->message,
				$scheduleAt, $cancelAt, $sendAt,
				$row->status, $row->response,
				$row->delivery_receipt,
			);
		}

		//  Output now
		$response["data"] = $dataArray;
		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function whatsAppQueueAPI(Request $request)  {
		ini_set('memory_limit', '512M');

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

		$array = CampaignWhatsappMessageQueue::getList($fromDate, $toDate, 0);

		$dataArray = array();
		foreach ($array as $row)  {

			//  create_at is default column in Model, it is Carbon type
			$createdAt = $row["created_at"]->toDateTimeString();
			$scheduleAt = $row["schedule_at"];
			$cancelAt = $row["cancel_at"];
			$sendAt = $row["send_at"];

			$dataArray[] = array(
				$row->id,
				$createdAt, $row->offer_id, $row->coupon_id, $row->mobile, $row->message,
				$scheduleAt, $cancelAt, $sendAt,
				$row->status, $row->response,
				$row->delivery_receipt,
			);
		}

		//  Output now
		$response["data"] = $dataArray;
		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function whatsAppQueueResendAPI(Request $request)  {
		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$id = null;
		if (null !== $request->input("id"))  {$id = $request->input("id");}
		$response["id"] = $id;

		if ($id == null)  {
			$response["status"] = -1;
			$response["message"] = "Parameter not found...";
			return response()->json($response);
		}

		//----------------------------------------------------------------------------------------
		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Resend message id #$id" ,"Whatsapp");

		//----------------------------------------------------------------------------------------
		$affectedRows = CampaignWhatsappMessageQueue::resendMessage($id);
		$response["affectedRows"] = $affectedRows;

		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function whatsAppQueueCancelAPI(Request $request)  {
		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$id = null;
		if (null !== $request->input("id"))  {$id = $request->input("id");}
		$response["id"] = $id;

		if ($id == null)  {
			$response["status"] = -1;
			$response["message"] = "Parameter not found...";
			return response()->json($response);
		}

		//----------------------------------------------------------------------------------------
		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Cancel message id #$id" ,"Whatsapp");

		//----------------------------------------------------------------------------------------
		$affectedRows = CampaignWhatsappMessageQueue::cancelMessage($id);
		$response["affectedRows"] = $affectedRows;

		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function whatsAppInboundAPI(Request $request)  {
		ini_set('memory_limit', '512M');

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

		$array = WhatsappWebhook::getList($fromDate, $toDate, "message");

		$dataArray = array();
		foreach ($array as $row)  {

			//  create_at is default column in Model, it is Carbon type
			$createdAt = $row["created_at"]->toDateTimeString();
			$messageID = $row["message_id"];
			$content = $row["content"];

			//  Extract JSON
			$from = "";
			$body = "";

			// 	"SmsMessageSid":"SM0df7c80c214295415c937e93c7adffd5",
			// 	"NumMedia":"0",
			// 	"SmsSid":"SM0df7c80c214295415c937e93c7adffd5",
			// 	"SmsStatus":"received",
			// 	"Body":"UAT: \\u6211\\u60f3\\u9818\\u53d6 L\\u2019Occitane \\u9ad4\\u9a57\\u88dd\\uff08\\u63db\\u9818\\u7de8\\u78bc\\uff1aGAj8Sw5m\\uff09\\u7684\\u63db\\u9818\\u9023\\u7d50\\uff01",
			// 	"To":"whatsapp:+85230016606",
			// 	"NumSegments":"1",
			// 	"MessageSid":"SM0df7c80c214295415c937e93c7adffd5",
			// 	"AccountSid":"ACa8c4e3793f543cc3b4d68b112171edf1",
			// 	"From":"whatsapp:+85294129112",
			// 	"ApiVersion":"2010-04-01"

			// 	"MediaContentType0":"image\/jpeg",
			// 	"SmsMessageSid":"MM01ec25167795ebbcd3775d99588414d8",
			// 	"NumMedia":"1",
			// 	"SmsSid":"MM01ec25167795ebbcd3775d99588414d8",
			// 	"SmsStatus":"received",
			// 	"Body":null,
			// 	"To":"whatsapp:+85230016606",
			// 	"NumSegments":"1",
			// 	"MessageSid":"MM01ec25167795ebbcd3775d99588414d8",
			// 	"AccountSid":"ACa8c4e3793f543cc3b4d68b112171edf1",
			// 	"From":"whatsapp:+85297231930",
			// 	"MediaUrl0":"https:\/\/api.twilio.com\/2010-04-01\/Accounts\/ACa8c4e3793f543cc3b4d68b112171edf1\/Messages\/MM01ec25167795ebbcd3775d99588414d8\/Media\/ME78c5cadf68433a270f0a8e9f31d47a6f",
			// 	"ApiVersion":"2010-04-01"
			if (empty($content) == false)  {

				$json = json_decode($content, true);
				$from = $json["From"];
				$body = $json["Body"];

				//  Handling media body
				$mediaBody = "";
				$mediaCount = intval($json["NumMedia"]);
				for ($i=0; $i<$mediaCount; $i++)  {
					if (isset($json["MediaUrl".$i]))  {

						$mediaURL = $json["MediaUrl".$i];
						$mediaBody .= "<a href='$mediaURL' target='_blank'><img src='$mediaURL' class='img-responsive'></a>";
					}
				}
				$body = $mediaBody.$body;

				//  Remove prefix
				$from = str_replace("whatsapp:", "", $from);
			}

			$dataArray[] = array(
				$row->id,
				$createdAt, $messageID,
				$from, $body,
			);
		}

		//  Output now
		$response["data"] = $dataArray;
		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function searchCouponAPI(Request $request)  {
		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$text = null;
		if (null !== $request->input("filter"))  {$text = $request->input("filter");}

		if ($text == null)  {
			$response["data"] = array();
			$response["status"] = 10;
			$response["message"] = "Filter not set...";
			return response()->json($response);
		}

		$array = CampaignCoupon::getWithMobileOrReferral($text);

		$dataArray = array();
		foreach ($array as $row)  {

			//  create_at is default column in Model, it is Carbon type
			$createdAt = $row["created_at"]->toDateTimeString();
			$mobile = $row["mobile"];

			$dataArray[] = array(
				$row->id, $createdAt,
				$row->offer_id, $row->offer_title,
				$row->unique_code, $row->mobile,
				$row->start_at, $row->use_at, $row->expiry_at,
				$row->form_data,
				$row->referrer_code, $row->referral_code, $row->referral_data,
			);
		}

		//  Output now
		$response["data"] = $dataArray;
		$response["status"] = 0;
		$response["filter"] = $text;
		$response["message"] = "Done";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function saveJourneyNodeAPI(Request $request)  {
		try  {
			$offer_code = $request->offer_code;
			$offer = CampaignOffer::getOffer($offer_code);
			$offerID = $offer->id;

			$node_id = null;
			if($request->has('node_id')){
				$node_id=$request->input('node_id');
			}
			$is_deleted=false;
			if($request->has('is_deleted')){
				$is_deleted=$request->input('is_deleted');
			}
			$node_name=$request->input('node_name');
			$node_type=$request->input('node_type');
			$node_settings=$request->input('node_settings');

			CampaignMasterJourney::saveJourneyNode($offerID, $node_name, $node_type, json_encode($node_settings), $node_id, $is_deleted);

			//auto create next node(s)
			if($request->has('new_node_names')){
				foreach((array)$request->input('new_node_names') as $new_node_name)  {
					CampaignMasterJourney::saveJourneyNode($offerID, $new_node_name, '0', '');
				}
			}

			//update the next node name of other node
			if($request->has('node_name_old')){
				$node_name_old=$request->input('node_name_old');
				$fields_have_to_be_checked=['nextNode','alreadyExistsNode','expiryNode','outOfQuotaNode','webhookErrorNode','node_name_next_then','node_name_next_else'];

				$objects=CampaignMasterJourney::where('offer_id', $offerID)->get();
				foreach($objects as $object){
					if($object->node_settings!==null && $object->node_settings!==''){
						$is_updated=false;
						$array_node_settings=json_decode($object->node_settings,true);
						foreach((array)$fields_have_to_be_checked as $field_have_to_be_checked){
							if(isset($array_node_settings[$field_have_to_be_checked])){
								if($array_node_settings[$field_have_to_be_checked]===$node_name_old){
									$array_node_settings[$field_have_to_be_checked]=$node_name;
									$is_updated=true;
								}
							}
						}
						if(isset($array_node_settings['options'])){
							foreach((array)$array_node_settings['options'] as $option=>$next_node_name){
								if($next_node_name===$node_name_old){
									$array_node_settings['options'][$option]=$node_name;
									$is_updated=true;
								}
							}
						}
						if($is_updated){
							CampaignMasterJourney::saveJourneyNode($object->offer_id,$object->node_name,$object->type,json_encode($array_node_settings),$object->id);
						}
					}
				}
			}

			return json_encode(array(
				'status'=>'success'
			));
		}  catch(\Exception $e)  {
			return json_encode(array(
				'status'=>'failed'
			));
		}
	}

	//----------------------------------------------------------------------------------------
	public function getJourneyNodesAPI(Request $request)  {

		$offer_code = $request->offer_code;
		$offer = CampaignOffer::getOffer($offer_code);
		$offerID = $offer->id;
		$objects = CampaignMasterJourney::getJourney($offerID);

		$reply_master=array();
		foreach($objects as $object){
			$node_name=$object->node_name;

			$objects_customer_journey = CampaignCustomerJourney::getNodeWithName($offerID, $node_name);

			$total_number_of_node=0;
			$number_of_node_completed=0;
			foreach($objects_customer_journey as $object_customer_journey){
				if($object_customer_journey->completed_at){
					$number_of_node_completed++;
				}
				$total_number_of_node++;
			}

			array_push($reply_master,array(
				'id'=>$object->id,
				'node_name'=>$node_name,
				'node_type'=>$object->type,
				'node_settings'=>json_decode($object->node_settings),
				'completion_rate'=>($total_number_of_node===0)?0:round($number_of_node_completed/$total_number_of_node*100)
			));
		}
		$reply['master']=$reply_master;

		if($request->has('mobile')){
			$objects=CampaignCustomerJourney::getNodesByUser(array('mobile'=>$request->mobile), $offerID);

			$reply_customer=array();
			$mobile_number='';
			foreach($objects as $object){
				array_push($reply_customer,array(
					'node_name'=>$object->node_name,
					'completed_at'=>$object->completed_at,
					'node_data'=>$object->node_data
				));
				$mobile_number=$object->mobile;
			}
			$reply['customer']=$reply_customer;
			$reply['mobile']=$mobile_number;
		}

		return json_encode($reply);
	}

	//----------------------------------------------------------------------------------------
	public function offerJourneyClearWhiteListedRecordsAPI(Request $request)  {
		$offerCode = $request->offer_code;
		$response = $this->offerJourneyClearWhiteListedRecords($offerCode);
		return response()->json($response);
	}

	public function offerJourneyClearWhiteListedRecords($offerCode)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$offer = CampaignOffer::getOffer($offerCode);
		$offerID = $offer->id;

		$offerINI = parse_ini_file("./offers/".$offer->offer_name."/offer.ini", true);

		$limit = intval(env("FOSO_WHITELIST_LIMIT", "50"));

		$index = 0;
		$removedArray = array();
		while ($index <= $limit)  {

			$index++;

			$key = sprintf("whitelist_mobile_%02d", $index);
			if (!isset($offerINI["coupon"][$key]))  {break;}

			$mobile = $offerINI["coupon"][$key];

			//  Clear chatbot state
			$stateArray = ChatbotState::where('mobile', $mobile)->get();
			foreach ($stateArray as $state)  {

				$data = json_decode($state->chatbot_data, true);
				if (isset($data['offer-'.$offerID]))  {
					unset($data['offer-'.$offerID]);
				}
				if (isset($data['currentOfferID']))  {
					unset($data['currentOfferID']);
				}
				$state->chatbot_data = json_encode($data);
				$state->save();
			}

			//  Clear Chatbot Journey
			$affectedRows = CampaignCustomerJourney::where('mobile', $mobile)->forceDelete();
			if ($affectedRows > 0)  {
				$removedArray[] = $mobile;
			}

		}

		if (count($removedArray) == 0)  {
			$removedArray[] = " Empty";
		}

		//----------------------------------------------------------------------------------------
		//  Output
		$response["status"] = 0;
		$response["message"] = "Journey records with mobile numbers have been removed. Ref:".implode(", ", $removedArray);
		$response["dataArray"] = $removedArray;
		return $response;
	}

	//----------------------------------------------------------------------------------------
	public function offerJourneyArchiveAllRecordsAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$offerCode = $request->offer_code;
		$offer = CampaignOffer::getOffer($offerCode);

		$offerID = $offer->id;
		$response["offerID"] = $offerID;

		$count = 0;
		$dataArray = CampaignCustomerJourney::getNodes($offerID);
		foreach ($dataArray as $data)  {

			//  Soft delete records that completed 30 days ago
			$array = $data->attributesToArray();
			unset($array["id"]);

			$archive = CampaignCustomerJourneyArchive::firstOrNew(array(
				"id" => $data->id,
			));
			$archive->fill($array);
			$result = $archive->save();

			if ($result == true)  {$data->delete();}
			$count++;
		}
		$response["count"] = $count;
		return json_encode($response);
	}

	//----------------------------------------------------------------------------------------
	public function offerJourneyUploadAPI(Request $request)  {

		$offerCode = $request->offer_code;
		$offer = CampaignOffer::getOffer($offerCode);

		//----------------------------------------------------------------------------------------
		$log = new CampaignFileUploadController;
		$result = $log->upload($request);

		//  Move to offer folder if status is ok.
		if (strtolower($result['status']) == "ok" && isset($result['uniqid'])) {
			$log = UploadFileLog::where('uniqid', $result['uniqid'])
				->first();

			//  TODO: Save image to external server
			$outputPath = $offer->offer_name.'/journey/'.$log->name;
			$file = Storage::disk('local')->get('uploads/'.$log->name);

			$saveResult = Storage::disk('offer')->put($outputPath, $file);
			$result["saveResult"] = $saveResult;
			if ($saveResult != true)  {

				//  Error
				$result["status"] = "Unable to save image...";
				return response()->json($result);
			}

			Storage::disk('local')->delete('uploads/'.$log->name);
		}
		return response()->json($result);
	}

	//----------------------------------------------------------------------------------------
	public function offerJourneyReportCSVDownloadAPI(Request $request)  {
		$columns_json = array('node_data');

		$folder = storage_path("app/public/");
		$from = $request->from;
		$to = $request->to;

		$offerID = 0;
		$offerCode = $request->offer_code;
		if ($offerCode != "all")  {

			$offerCode = $request->offer_code;
			$offer = CampaignOffer::getOffer($offerCode);
			$offerID = $offer->id;
		}

		$rows = CampaignCustomerJourney::where('offer_id',$offerID)->get()->toArray();

		$columns_extra=array();
		foreach((array)$columns_json as $column_json){
			foreach((array)$rows as $key=>$row){
				try{
					$array_node_data=json_decode($row[$column_json],true);
					$columns_extra=array_merge($columns_extra,array_keys((array)$array_node_data));
				}catch(\Exception $e){
					continue;
				}
			}
		}

		foreach((array)$columns_json as $column_json){
			foreach((array)$rows as $key=>$row){
				try{
					$array_node_data=json_decode($row[$column_json],true);
				}catch(\Exception $e){
					continue;
				}
				foreach((array)$columns_extra as $column_extra){
					$rows[$key][$column_extra]=isset($array_node_data[$column_extra])?$array_node_data[$column_extra]:'';
				}
			}
		}

		$brandName = env("BRAND_NAME", "");
		$brandName = str_replace(" ", "_", $brandName);
		$filename = $brandName.'_Whatsapp_Offer_Customer_Journey_Report_'.now()->format('YmdHis').'.csv';
		$filePath = $folder.$filename;
		$handle = fopen($filePath, 'w');

		$i = 0;
		foreach ((array)$rows as $row)  {

			foreach ((array)$columns_json as $column_not_to_export)  {
				unset($row[$column_not_to_export]);
			}

			//  Write table head
			if ($i === 0)  {fputcsv($handle, array_keys((array)$row));}

			$values = array_values((array)$row);
			foreach ((array)$values as $key=>$value)  {
				if (is_array($value))  {$values[$key] = json_encode($value);}
			}

			fputcsv($handle, array_values((array)$values));
			$i++;
		}

		//  CSV download
		fclose($handle);
		return response()->download($filePath);
	}

	//----------------------------------------------------------------------------------------
	public function offerJourneyCSVDownloadAPI(Request $request)  {

		$brandName = env("BRAND_NAME", "");
		$brandName = str_replace(" ", "_", $brandName);
		$filename = $brandName.'_Whatsapp_Offer_Customer_Journey_'.now()->format('YmdHis').'.csv';
		$folder = storage_path("app/public/");
		$from = $request->from;
		$to = $request->to;

		//  Get offer ID with offer code
		$offerCode = $request->offer_code;
		$offer = CampaignOffer::getOffer($offerCode);
		$offerID = $offer->id;

		//  Create CSV file
		$filePath = $folder.$filename;
		$handle = fopen($filePath, 'w');

		$headerArray = array(
			"id"," created_at", "updated_at", "deleted_at", "offer_id", "ordering", "node_name",
			"type", "node_settings", "mobile", "email", "user_id", "canceled_at", "triggered_at",
			"completed_at", "node_data",
		);
		fputcsv($handle, $headerArray);

		$offset = 0;
		$limit = 1000;
		while ($limit > 0)  {

			$dataArray = CampaignCustomerJourney::getNodesWithPaging($offerID, $offset, $limit);
			foreach ($dataArray as $node)  {

				$rowArray = array(
					$node->id, $node->created_at, $node->updated_at, $node->deleted_at,
					$node->offer_id, $node->ordering, $node->node_name, $node->type,
					$node->node_settings, $node->mobile, $node->email, $node->user_id,
					$node->canceled_at, $node->triggered_at, $node->completed_at, $node->node_data,
				);
				fputcsv($handle, $rowArray);
			}

			$count = count($dataArray);
			$offset += $count;
			$limit = $count;
		}

		//  CSV download
		fclose($handle);
		return response()->download($filePath);
	}

	//----------------------------------------------------------------------------------------
	public function offerJourneyUpdateOrderingAPI(Request $request) {
		$response = array(
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$offerCode = $request->offer_code;
		$offer = CampaignOffer::getOffer($offerCode);
		if ($offer == null)  {
			$response["status"] = -1;
			$response["message"] = "Offer record not found...";
			return response()->json($response);
		}

		if (!$request->has("nodeName") || !$request->has("oldIndex") || !$request->has("newIndex"))  {
			$response["status"] = -2;
			$response["message"] = "nodeName or indexes not found...";
			return response()->json($response);
		}
		$nodeName = $request->nodeName;
		$oldIndex = $request->oldIndex;
		$newIndex = $request->newIndex;

		$offerID = $offer->id;
		$journeyArray = CampaignMasterJourney::getJourney($offerID);

		$count = 0;
		foreach ($journeyArray as $journey)  {

			$change = 0;
			if ($journey->node_name == $nodeName)  {
				$change = ($newIndex-$oldIndex) * 10;
			}
			else if ($count >= $oldIndex && $count <= $newIndex)  {
				$change = -10;
			}
			else if ($count <= $oldIndex && $count >= $newIndex)  {
				$change = 10;
			}

			if ($change != 0)  {
				$journey->ordering += $change;
				$journey->save();
			}
			$count++;
		}

		$response["status"] = 0;
		$response["message"] = "OK";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function saveOfferINIAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$ini = $request->ini;
		if (empty($ini))  {

			$response["status"] = -10;
			$response["message"] = "Invalid content...";
			return response()->json($response);
		}

		$offerCode = $request->offer_code;
		$offer = CampaignOffer::getOffer($offerCode);
// 		file_put_contents("./offers/".$offer->offer_name."/offer.ini", $ini);

		$disk = Storage::disk('offer');
		$disk->put($offer->offer_name."/offer.ini", $ini);

		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function marketingListSearchAPI(Request $request)  {
		ini_set('memory_limit', '512M');

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

		$array = MarketingList::getList($fromDate, $toDate, null);

		$dataArray = array();
		foreach ($array as $row)  {

			//  create_at is default column in Model, it is Carbon type
			$createdAt = $row["created_at"]->toDateTimeString();

			$updatedAt = "";
			if ($row->updated_at != null)  {
				$updatedAt = $row["updated_at"]->toDateTimeString();
			}

			$dataArray[] = array(
				$row->id,
				$createdAt, $updatedAt,
				$row->list_name,
				$row->mobile,
				$row->username,
				$row->parameter_a,
				$row->parameter_b,
				$row->parameter_c,
			);
		}

		//  Output now
		$response["data"] = $dataArray;
		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function marketingWhatsAppBlastAPI(Request $request)  {
		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$message = null;
		if (null !== $request->input("message"))  {$message = $request->input("message");}
		if ($message == null)  {

			$response["status"] = -1;
			$response["message"] = "Parameter not found...";
			return response()->json($response);
		}

		$listName = null;
		if (null !== $request->input("listName"))  {$listName = $request->input("listName");}

		$scheduleTime = date("Y-m-d H:i:s");
		if (null !== $request->input("scheduleTime"))  {

			$scheduleTime = $request->input("scheduleTime");
			$scheduleTime = date("Y-m-d H:i:s", strtotime($scheduleTime));
		}

		$expiryAt = date("Y-m-d H:i:s", strtotime($scheduleTime." +24 hours"));

		$mobileFrom = env("WHATSAPP_SENDER", "");
		$mobileFrom = str_replace("whatsapp:", "", $mobileFrom);

		//----------------------------------------------------------------------------------------
		//  Get marketing list records
		$dataArray = MarketingList::getList(null, null, $listName);
		$count = count($dataArray);
		if ($count <= 0)  {

			$response["status"] = -2;
			$response["message"] = "Number not found...";
			return response()->json($response);
		}

		//  Send SMS one by one
		$response["count"] = $count;
		foreach ($dataArray as $data)  {

			$mobile = $data["mobile"];
			if (strlen($mobile) <= 0)  {continue;}

			$username = $data["username"];
			$parameterA = $data["parameter_a"];
			$parameterB = $data["parameter_b"];
			$parameterC = $data["parameter_c"];

			$searchArray = array(
				"{{mobile}}", "{{username}}", "{{parameter_a}}", "{{parameter_b}}", "{{parameter_c}}",
			);
			$replaceArray = array(
				$mobile, $username, $parameterA, $parameterB, $parameterC,
			);

			$personalizedMessage = str_replace($searchArray, $replaceArray, $message);
			$messageType = $listName;

			//  Add to queue
			$whatsAppQueue = new CampaignWhatsappMessageQueue();
			$whatsAppQueue->created_by = basename(__FILE__);
			$whatsAppQueue->offer_id = 0;
			$whatsAppQueue->coupon_id = 0;
			$whatsAppQueue->mobile_from = $mobileFrom;
			$whatsAppQueue->mobile = $mobile;
			$whatsAppQueue->message = $personalizedMessage;
			$whatsAppQueue->message_type = $messageType;
			$whatsAppQueue->schedule_at = $scheduleTime;
			$whatsAppQueue->vendor = "twilio";
			$whatsAppQueue->cost = "template";

			$whatsAppQueue->expiry_at = $expiryAt;

			$whatsAppQueue->save();
		}

		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function marketingListUploadCheckAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$offerCode = $request->offer_code;
		$offer = CampaignOffer::getOffer($offerCode);

		$uploader = new CampaignFileUploadController;
		$result = $uploader->upload($request);

		if (strtolower($result['status']) != "ok")  {
			$response["status"] = -1;
			$response["result"] = $result;
			if (isset($result['message']))  {
				$response["message"] = $result['message'];
			}  else  {
				$response["message"] = "Failed upload file...";
			}
			return response()->json($response);
		}

		//  Output now
		$response["data"] = $result;
		$response["status"] = 0;
		$response["message"] = "Upload successfully";
		// dd(response()->json($response));
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function marketingListUploadAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$uniqueID = $request->uniqueID;
		$listName = $request->listName;

		//  Load CSV filename from database
		$uploader = UploadFileLog::where('uniqid', $uniqueID)->first();
		$file = 'app/uploads/'.$uploader->name;

		if (($handle = fopen(storage_path($file), "r")) === FALSE)  {

			$response["status"] = -10;
			$response["message"] = "Unable to read upload file...";
			return response()->json($response);
		}

		$row = 0;
		$now = date("Y-m-d H:i:s");
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

			$row++;

			//  Skip header row
			if ($row <= 1)  {continue;}

			//  Get quota issued from database
			$mobile = WhatsAppController::trimMobile($data[0]);
			if (strlen($mobile) == 0)  {
				$response['error'][] = " \nRow " .$row. ': Empty mobile number';
				continue;
			}

			if (strpos($mobile, "+") === false)  {
				$mobile = "+".$mobile;
			}

			//  Find '+' start from index 1
			if (strpos($mobile, "+", 1) !== false || strpos($mobile, ".") !== false)  {
				$response['error'][] = " \nRow " .$row. ": Invaild mobile number";
				continue;
			}

			$username = "";
			if (isset($data[1]))  {$username = $data[1];}

			$parameterA = "";
			if (isset($data[2]))  {$parameterA = $data[2];}

			$parameterB = "";
			if (isset($data[3]))  {$parameterB = $data[3];}

			$parameterC = "";
			if (isset($data[4]))  {$parameterC = $data[4];}

			$record = MarketingList::firstOrNew(array(
				"mobile" => $mobile,
				"list_name" => $listName,
			));
			$record->updated_at = $now;
			$record->username = $username;
			$record->parameter_a = $parameterA;
			$record->parameter_b = $parameterB;
			$record->parameter_c = $parameterC;
			$record->save();
		}
		fclose($handle);

		//  Output now
		$response["rows"] = $row;
		$response["status"] = 0;
		$response["message"] = "List updated";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function membersSearchAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$mobile = null;
		if (null !== $request->input("mobile"))  {$mobile = $request->input("mobile");}

// 		if ($mobile == null || strlen($mobile) < 4)  {
// 			$response["data"] = array();
// 			$response["status"] = -1;
// 			$response["message"] = "Parameter not found...";
// 			return response()->json($response);
// 		}

		$array = Member::getList(null, null, $mobile);

		$dataArray = array();
		foreach ($array as $row)  {

			//  create_at is default column in Model, it is Carbon type
			$createdAt = $row["created_at"]->toDateTimeString();

			$updatedAt = null;
			if ($row["updated_at"] != null)  {$updatedAt = $row["updated_at"]->toDateTimeString();}

			//  No need 'toDateTimeString()' for optout_at
			$optOutAt = null;
			if ($row["optout_at"] != null)  {$optOutAt = $row["optout_at"];}

			$dataArray[] = array(
				$row->id, $createdAt, $updatedAt, $row->mobile, $row->username,
				$optOutAt, $row["mute_until"], $row["offer_involved"],
			);
		}

		//  Output now
		$response["data"] = $dataArray;
		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function membersDetailUpdateAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
			// "data" => $request->input(),
		);
		$original_record = Member::getMemberByMobile($request->input('mobile'));
		$updated_record = Member::updateMember($request->input('mobile'), $request->input());

		if($original_record['optout_at'] != $updated_record['optout_at']){

			$event = MemberEvent::create();
			$event['user_id'] = $request->input('mobile');
			$event['create_by'] = __FUNCTION__;

			if($updated_record['optout_at'] != null){
				$event['event'] = "optout";
				// $response["optout_event"] = "optout";
			} else {
				$event['event'] = "unoptout";
				// $response["optout_event"] = "unoptout";
			}
			$event->save();
		}
		if($original_record['mute_until'] != $updated_record['mute_until']){

			$event = MemberEvent::create();
			$event['user_id'] = $request->input('mobile');
			$event['create_by'] = __FUNCTION__;

			if($updated_record['mute_until'] != null){
				$event['event'] = "mute";
				// $response["mute_event"] = "mute";
			} else {
				$event['event'] = "unmute";
				// $response["mute_event"] = "unmute";
			}

			$event->save();
		}
		$response["message"] = "Update success.";
		$response["status"] = 0;
		$response["updated_record"] = $updated_record;
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function membersUnmuteAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
			// "data" => $request->input(),
		);

// 		$array =  explode('/', $_SERVER['REQUEST_URI']);
// 		$count = count($array);
// 		$mobile = $array[$count-2];
		$mobile = $request->mobile;

		$record = Member::unMute($mobile);
		if ($record == null)  {

			$response["message"] = "Unmute failed.";
			$response["status"] = -1;
			return response()->json($response);
		}

		$response["message"] = "Unmute success.";
		$response["status"] = 200;
		// $response["record"] = $record;
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function membersOptInAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
			// "data" => $request->input(),
		);

		//  Extract mobile number from URL
// 		$array =  explode('/', $_SERVER['REQUEST_URI']);
// 		$count = count($array);
// 		$mobile = $array[$count-2];
		$mobile = $request->mobile;
		$record = Member::unOptOut($mobile);
		if ($record == null)  {

			$response["message"] = "Opt-In failed.";
			$response["status"] = -1;
			return response()->json($response);
		}

		$response["message"] = "Opt-In success.";
		$response["status"] = 200;
		// $response["record"] = $record;
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function membersTransactionsAPI(Request $request)  {
		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		//  Extract mobile number from URL
		$mobile = $request->mobile;
		$member = Member::getMemberByMobile($mobile);
		$memberID = $member->id;

		$dataArray = array();
		$array = PointTransaction::where("member_id", $memberID)->get();
		foreach ($array as $record)  {

			$id = $record->id;
			$createdAt = $record->created_at->toDateTimeString();
			$validAt = $record->valid_at;
			$deltaPoints = $record->delta_points;
			$expiryAt = $record->expiry_at;
			$type = $record->transaction_type;
			$descriptionJSON = json_decode($record->description, true);

			$description = $descriptionJSON["zh-HK"];

			$dataArray[] = array($id, $createdAt, $deltaPoints, $validAt, $expiryAt, $type, $description);
		}
		$response["data"] = $dataArray;

		$response["message"] = "Done.";
		$response["status"] = 0;
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function getDashBoardDataApi(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$genericChartRecordArray = DashboardGenericChart::all();
		$timeCharRecordArray = DashboardTimeChart::all();

		$response["genericChartRecordArray"] = $genericChartRecordArray;
		$response["timeCharRecordArray"] = $timeCharRecordArray;
		$response["message"] = "Success.";
		$response["status"] = 200;
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function getDashboardOfferDataApi()
	{
		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);
		$record = CampaignOffer::getAllOfferTitle();

		$response["offerArray"] = $record;
		$response["message"] = "Success.";
		$response["status"] = 200;
		return response()->json($response);
	}
	//----------------------------------------------------------------------------------------
	public function getDashboardDataByOfferIdApi()
	{
		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$array =  explode('/', $_SERVER['REQUEST_URI']);
		$count = count($array);
		$offerID = $array[$count - 1];

		$record = DashboardGenericChart::getListByOfferId($offerID);
		// $date = [];
		// foreach($record as $el){
		// 	$date[] = $el->record_date;
		// }
		$timeChart = DashboardTimeChart::getListByOfferId($offerID);
		$response["record"] = $record;
		$response["timeChart"] = $timeChart;
		$response["message"] = "Success.";
		$response["status"] = 200;
		// $response["date"] = $date;
		return response()->json($response);
	}

	//----------------------------------------------------------------------------------------
	public function appUserApi(Request $request)  {
		return AppUser::all();
	}

	//----------------------------------------------------------------------------------------
	public function createAppUserPage(Request $request)  {
		return view('foso.app.user_create');
	}

	//----------------------------------------------------------------------------------------
	public function createAppUserApi(Request $request)  {
		$name = $request->input('name', '');
		$email = $request->input('email', '');
		$roles = $request->input('roles', '');
		$password = $request->input('password', '');
		if(!$email) {
			return ["OK" => false,'message' => 'Email must not empty.'];
		}
		if(!$password) {
			return ["OK" => false,'message' => 'Password must not empty.'];
		}
		if(AppUser::getUserByEmail($email)) {
			return ["OK" => false,'message' => 'Email already exist.'];
		}

		$appUser = AppUser::create([
			'name' => $name,
			'email' => $email,
			'roles' => $roles,
			'password' => $password,
		]);

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Create AppUser name [$name] with id #$appUser->id ", "User");

		return [
			"OK" => true,
			'message' => 'Create App user success.',
			'user' => $appUser
		];
	}

	//----------------------------------------------------------------------------------------
	public function appUserDetailUpdateApi(Request $request)  {
		$name = $request->input('name', '');
		$email = $request->input('email', '');
		$roles = $request->input('roles', '');
		$token_expiry_at = $request->input('token_expiry_at', '');
		// $password = $request->input('password', '');
		if(!$email) {
			return ["OK" => false,'message' => 'Email must not empty.'];
		}
		// if(!$password) {
		// 	return ["OK" => false,'message' => 'Password must not empty.'];
		// }
		// if(AppUser::getUserByEmail($email)) {
		// 	return ["OK" => false,'message' => 'Email already exist.'];
		// }

		$appUser = AppUser::getUserByEmail($email)->update([
			'name' => $name,
			'email' => $email,
			'roles' => $roles,
			'token_expiry_at' => $token_expiry_at,
			// 'password' => $password,
		]);

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Update AppUser name [$name] with id #$appUser->id ", "User");

		return [
			"OK" => true,
			'message' => 'Update App user success.',
			'user' => $appUser
		];
	}

	// //----------------------------------------------------------------------------------------
	// public function checkDataCompletenessApi(){
	// 	$response = array(
	// 		"timeStamp" => Date("YmdHis"),
	// 		"apiName" => __FUNCTION__,
	// 		"status" => -99,
	// 		"message" => "Unexpected error...",
	// 	);
	// }
	//----------------------------------------------------------------------------------------
	public function clientCSVDownload(Request $request)  {
		$request->from = $request->get('from', date("Y-m-d", strtotime("-30 days")));
		$request->to = $request->get('to', date("Y-m-d"));
		return $this->offerCouponCSVDownloadAPI($request);
	}

	//----------------------------------------------------------------------------------------
	public function exportMatrix(Request $request)  {

		//  Find available offers
		$index = 1;
		$offerDictionary = array();
		$offerArray = array("Users");
		$array = CampaignOffer::where("id", ">=", "9")->orderBy("id")->get();
		foreach ($array as $offer)  {

			$offerID = $offer->id;
			$offerTitle = $offer->offer_title;
			$offerSubtitle = $offer->offer_subtitle;

			$offerDictionary[$offerID] = $index;
			$index++;

			$text = "#".$offerID." ".$offerTitle.": ".$offerSubtitle;
			$offerArray[] = $text;
		}

		//----------------------------------------------------------------------------------------
		$outputDictionary = array();
		$couponArray = CampaignCoupon::where("offer_id", ">=", "9")->orderBy("mobile")->get();
		foreach ($couponArray as $coupon)  {

			$offerID = $coupon->offer_id;
			$mobile = $coupon->mobile;

			//  New row
			if (isset($outputDictionary[$mobile]) == false)  {
				$outputDictionary[$mobile][0] = $mobile;
				for ($i=1; $i<$index; $i++)  {
					$outputDictionary[$mobile][$i] = 0;
				}
			}

			$offset = $offerDictionary[$offerID];
			$outputDictionary[$mobile][$offset] = 1;
		}

		//----------------------------------------------------------------------------------------
		$file = Date("YmdHis")."_ml.csv";
		$filePath = storage_path($file);

		//  Write CSV header
		$handle = fopen($filePath, 'w');
		fputcsv($handle, $offerArray);

		//  Write CSV content
		foreach ($outputDictionary as $mobile => $array)  {
			fputcsv($handle, $array);
		}
		fclose($handle);
	}

	//----------------------------------------------------------------------------------------
	//  Mark: Help function
	//----------------------------------------------------------------------------------------
	public static function generateRandomString($length=16)  {
		$characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$charactersLength = strlen($characters);

		$randomString = "";
		for ($i=0; $i<$length; $i++)  {
			$randomString .= $characters[rand(0, $charactersLength-1)];
		}
		return $randomString;
	}

	//----------------------------------------------------------------------------------------
	public static function validateDate($date)  {
		$format = "Y-m-d H:i:s";
		$datetime = DateTime::createFromFormat($format, $date);
		$result = ($datetime && ($datetime->format($format) === $date));
		return $result;
	}

	//----------------------------------------------------------------------------------------
	//  Tables to-be-archive
	//  1. campaign_customer_journey
	//  2. campaign_whatsapp_message_queues
	//  3. whatsapp_webhooks
	public static function processDataArchive()  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		//  1. campaign_customer_journey
		$count = 0;
		$loop = 0;
		do  {

			$loop++;
			$dataArray = CampaignCustomerJourney::getRecordsToBeArchived(1000);
			Log::debug("1a. Loop #$loop, count ".count($dataArray));
			foreach ($dataArray as $data)  {

				//  Soft delete records that completed 30 days ago
				$array = $data->attributesToArray();
				unset($array["id"]);

				$archive = CampaignCustomerJourneyArchive::firstOrNew(array(
					"id" => $data->id,
				));
				$archive->fill($array);
				$result = $archive->save();

				if ($result == true)  {$data->delete();}
				$count++;
			}
		}  while(count($dataArray) > 0 && $loop < 100);
		$response["CampaignCustomerJourney"] = $count;

		//  Hard delete records that archived 7 days ago
		$loop = 0;
		do  {

			$loop++;
			$dataArray = CampaignCustomerJourney::getRecordsToBeDeleted(1000);
			Log::debug("1b. Loop #$loop, count ".count($dataArray));
			foreach ($dataArray as $data)  {$data->forceDelete();}

		}  while(count($dataArray) > 0 && $loop < 100);

		//----------------------------------------------------------------------------------------
		//  1.1. campaign_customer_journey (Offer has ended 30 days ago)
		$offerArray = CampaignOffer::getRecordsToBeArchived();
		foreach ($offerArray as $offer)  {

			$offerID = $offer->id;
			$count = 0;
			$loop = 0;
			do  {

				$loop++;
				$dataArray = CampaignCustomerJourney::getRecordsByOfferID($offerID, 1000);
				foreach ($dataArray as $data)  {

					//  Soft delete records that completed 30 days ago
					$array = $data->attributesToArray();
					unset($array["id"]);

					$archive = CampaignCustomerJourneyArchive::firstOrNew(array(
						"id" => $data->id,
					));
					$archive->fill($array);
					$result = $archive->save();

					if ($result == true)  {$data->delete();}
					$count++;
				}
			}  while(count($dataArray) > 0 && $loop < 100);
		}

		//----------------------------------------------------------------------------------------
		//  2. campaign_whatsapp_message_queues
		$count = 0;
		$loop = 0;
		do  {

			$loop++;
			$dataArray = CampaignWhatsappMessageQueue::getRecordsToBeArchived(1000);
			Log::debug("2a. Loop #$loop, count ".count($dataArray));
			foreach ($dataArray as $data)  {

				//  Soft delete records that completed 30 days ago
				$array = $data->attributesToArray();
				unset($array["id"]);

				$archive = CampaignWhatsappMessageQueueArchive::firstOrNew(array(
					"id" => $data->id,
				));
				$archive->fill($array);
				$result = $archive->save();

				if ($result == true)  {$data->delete();}
				$count++;
			}
		}  while(count($dataArray) > 0 && $loop < 100);
		$response["CampaignWhatsappMessageQueue"] = $count;

		//  Hard delete records that archived 7 days ago
		$loop = 0;
		do  {

			$loop++;
			$dataArray = CampaignWhatsappMessageQueue::getRecordsToBeDeleted(1000);
			Log::debug("2b. Loop #$loop, count ".count($dataArray));
			foreach ($dataArray as $data)  {$data->forceDelete();}

		}  while(count($dataArray) > 0 && $loop < 100);

		//----------------------------------------------------------------------------------------
		//  3. whatsapp_webhooks
		$count = 0;
		$loop = 0;
		do  {

			$loop++;
			$dataArray = WhatsappWebhook::getRecordsToBeArchived(1000);
			Log::debug("3a. Loop #$loop, count ".count($dataArray));
			foreach ($dataArray as $data)  {

				//  Soft delete records that completed 30 days ago
				$array = $data->attributesToArray();
				unset($array["id"]);

				$archive = WhatsappWebhookArchive::firstOrNew(array(
					"id" => $data->id,
				));
				$archive->fill($array);
				$result = $archive->save();

				if ($result == true)  {$data->delete();}
				$count++;
			}
		}  while(count($dataArray) > 0 && $loop < 100);
		$response["WhatsappWebhookArchive"] = $count;

		//  Hard delete records that archived 7 days ago
		$loop = 0;
		do  {

			$loop++;
			$dataArray = WhatsappWebhook::getRecordsToBeDeleted(1000);
			Log::debug("3b. Loop #$loop, count ".count($dataArray));
			foreach ($dataArray as $data)  {$data->forceDelete();}

		}  while(count($dataArray) > 0 && $loop < 100);

		//----------------------------------------------------------------------------------------
		//  4. campaign_coupons
		$count = 0;
		$loop = 0;
		do  {

			$loop++;
			$dataArray = CampaignCoupon::getRecordsToBeArchived(1000);
			Log::debug("4a. Loop #$loop, count ".count($dataArray));
			foreach ($dataArray as $data)  {

				//  Soft delete records that completed 30 days ago
				$array = $data->attributesToArray();
				unset($array["id"]);

				$archive = CampaignCouponArchive::firstOrNew(array(
					"id" => $data->id,
				));
				$archive->fill($array);
				$result = $archive->save();

				if ($result == true)  {$data->delete();}
				$count++;
			}
		}  while(count($dataArray) > 0 && $loop < 100);
		$response["CampaignCouponArchive"] = $count;

		//  Hard delete records that archived 7 days ago
		$loop = 0;
		do  {

			$loop++;
			$dataArray = CampaignCoupon::getRecordsToBeDeleted(1000);
			Log::debug("4b. Loop #$loop, count ".count($dataArray));
			foreach ($dataArray as $data)  {$data->forceDelete();}

		}  while(count($dataArray) > 0 && $loop < 100);

		//----------------------------------------------------------------------------------------
		//  Output
		$response["status"] = 0;
		$response["message"] = "Done";
		return $response;
	}

	//----------------------------------------------------------------------------------------
	//  Function written by Bobby Choi
	public function linkInjection($link)  {
		$link .= " ";
		for ($i=0; $i<strlen($link); $i++)  {

			if ($link[$i] === 'h' && strlen($link) - $i > 10)  {

				if (substr($link, $i, 6) === "href='")  {
					while(substr($link, $i, 4) !== '</a>')  {$i++;}
				}
				else if(substr($link, $i, 7) === 'http://' || substr($link, $i, 8) === 'https://')  {

					$last = $i;
					while ($link[$i] !== ' ' && $this->isAscii($link[$i]) === true)  {$i++;}
					$url = substr($link, $last, $i - $last);
					$link = substr_replace($link, "<a href='".$url."'>".$url."</a>", $last, strlen($url));
					while (substr($link, $i, 4) !== '</a>')  {$i++;}
				}

			}  else if (strlen($link) - $i > 3)  {

				if (substr($link, $i, 3) === 'www' && substr($link, max($i - 3, 0), 3) !== '://')  {

					$last = $i;
					while ($link[$i] !== ' ' && $this->isAscii($link[$i]) === true)  {$i++;}

					$url = substr($link, $last, $i - $last);
					$link = substr_replace($link, "<a href='https://".$url."'>".$url."</a>", $last, strlen($url));
					while (substr($link, $i, 4) !== "</a>")  {$i++;}
				}

			}
		}
		return (substr($link, 0, -1));
	}

	//----------------------------------------------------------------------------------------
	//  Function written by Bobby Choi
	public function isAscii($char)  {
		return 0 === preg_match('/[^\x00-\x7F]/', $char);
	}

	//----------------------------------------------------------------------------------------
	public function logout(Request $request)  {
		$this->guard()->logout();
		return redirect()->route('foso.main.login.html');
	}

	//----------------------------------------------------------------------------------------
	protected function guard()  {
		return Auth::guard('foso');
	}

	//----------------------------------------------------------------------------------------
	public function fixDuplicatedMember(Request $request)  {
		$mobileArray = array(
			"+85261492229", "+85254208252", "+12062273032", "+85293083116",
			"+85298689056", "+85252398175", "+85267193996", "+85292706191",
			"+85293123088", "+85262240457", "+85296395728", "+85261070180",
			"+85251167775", "+85293057871", "+85267507090", "+85291284597",
			"+85291259091", "+85298248256", "+85293602600", "+85264465072",
			"+85260301521", "+85261398092", "+85269203157", "+85298873493",
			"+85298721618", "+85292493291", "+85265886954", "+85295814010",
			"+85268713286", "+85263603471", "+85254322708", "+85296594281",
			"+85256677795", "+85296090891", "+85290802851", "+85266435932",
			"+85297611612", "+85296393777", "+85291964067", "+85293289640",
			"+85290493787", "+85265488330", "+85251653068", "+85291775360",
			"+85261109218", "+85261475706", "+85294952793", "+85252209531",
			"+85260808960", "+85263333208", "+85297663889", "+85264827275",
			"+85267069122", "+85262192999", "+85290807777", "+85292346655",
			"+85261384947", "+85292690610", "+85291091333", "+85298059875",
			"+85294114333", "+85267429015", "+85260507640", "+85298112003",
			"+85261738867", "+85253454073", "+85294563109", "+85296777841",
			"+85261511576", "+85295567716", "+85298098486", "+85261031178",
			"+85291205534", "+85269809588", "+85296338402", "+85253682122",
			"+85264647993", "+85252740904", "+85292670232", "+85259111332",
			"+85253636733", "+85265404089", "+85261417371", "+85296254485",
			"+85262384714", "+85253720406", "+85255703496", "+85298330875",
			"+85292599386", "+85297805244", "+85266718662", "+85267041992",
			"+85253366911", "+85290468740", "+85252225426", "+85260862202",
			"+85298318089", "+85296737659", "+85292012139", "+85296357839",
			"+85267999660", "+85259890759", "+85293172861", "+85298323230",
			"+85297151887", "+85268512137", "+85295583706", "+85267703468",
			"+85260285077", "+85254992185", "+85293515431", "+85298355060",
			"+85256227127", "+85293881249", "+85298651001", "+85291040912",
			"+85297257262", "+85259883997", "+85268447154", "+85292491210",
			"+85292760653", "+85297304354", "+85265907758", "+85264370829",
			"+85297927641", "+85260773568", "+85295218285", "+85292238976",
			"+85297904031", "+85293892639", "+61403723848", "+85294321072",
			"+85293801577", "+85267626814", "+85254188767", "+85267654890",
			"+85255827867", "+85264054479", "+85260122103", "+85262910422",
			"+85298570781", "+85251289330", "+85294029539", "+85293027477",
			"+85257288602", "+85298550919", "+85291996118", "+85296824174",
			"+85295404824", "+85261774328", "+85293473837", "+85297094828",
			"+85265866381", "+85261518173", "+85263537739", "+85298086322",
			"+85264064082", "+85296134401", "+85267342333", "+85265076508",
			"+85254998435", "+85260201207", "+85296583132", "+85292220734",
			"+85265779491", "+85297760195", "+85298880329", "+85296188962",
			"+85265022168", "+85293899915", "+85262552997", "+85255257005",
			"+85296878159", "+85262106038", "+85268478644", "+85262211923",
			"+85297505254", "+85297100086", "+85298329849", "+85298014194",
			"+85294700018", "+85267448208", "+85268292866", "+85290316891",
			"+85264812463", "+85267335753", "+85291219060", "+85264026661",
			"+85296744195", "+85251702643", "+85265725519", "+85255939119",
			"+85295507055", "+85293153590", "+85264346288", "+85295399503",
			"+85295827205", "+85255469225", "+85291739150", "+85298721668",
			"+85264160498", "+85292683814", "+85263932282", "+85296708407",
			"+85261935066", "+85266420333", "+85263311009", "+85254068218",
			"+85296460770", "+85291394161", "+85291079846", "+85293132557",
			"+85253989553",
		);

		foreach ($mobileArray as $mobile)  {

			$array = Member::where("mobile", $mobile)
				->orderBy("id", "asc")
				->get();

			$count = count($array);
			if ($count <= 1)  {continue;}

			$a = $array[0];
			$jsonA = json_decode($a->offer_involved, true);

			$b = $array[1];
			$jsonB = json_decode($b->offer_involved, true);

			$json = json_encode(array_merge($jsonB, $jsonA));
			$a->offer_involved = $json;
			$a->save();

			$b->delete();

			echo("<br>$mobile ... $count ... $json");
		}
	}


	//----------------------------------------------------------------------------------------
	//  Offer Hunting Pages -- Kay 2022.07.14
	//----------------------------------------------------------------------------------------
	public function offerHuntingListPage(Request $request)  {

		if (null !== $request->input("from"))  {$fromDate = $request->input("from");}
		else  {$fromDate = date("Y-m-d", strtotime("-30 days"));}

		if (null !== $request->input("to"))  {$toDate = $request->input("to");}
		else  {$toDate = date("Y-m-d", strtotime("+30 days"));}

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Read FOSO Offer-hunting listing page from [$fromDate] to [$toDate]", "Offer-hunting");

		return view('foso.offerhunting.hunting_list', [
			"fromDate" => $fromDate,
			"toDate" => $toDate,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function offerHuntingListAPI(Request $request)  {

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
	
		$array = CampaignOfferHunting::getList($fromDate, $toDate);

		$dataArray = array();
		foreach ($array as $row)  {

			$openCount = 0;
			$json = $row->statistic_data;
			if ($json != null)  {

				$dictionary = json_decode($json, true);
				if (isset($dictionary["open"]))  {

					$openCount = intval($dictionary["open"]);
				}
			}

			$createdAt = $row["created_at"]->toDateTimeString();
			$updatedAt = $row["updated_at"]->toDateTimeString();

			$dataArray[] = array(
				$row->id, $createdAt, $updatedAt, $row->deleted_at, 
				$row->created_by, $row->updated_by, $row->deleted_by,
				$row->name, $row->mobile_num, $row->discount_content, $row->media, 
				$row->member_id, $row->status, $row->approved_point,
				$row->approved_at, $row->approved_by, $openCount,

				//  Must be the last one
				route("foso.offerhunting.settings.html", ["id"=>$row->id]),
			);
		}
		// dd($dataArray);
		//  Output now
		$response["data"] = $dataArray;
		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}

	public function offerHuntingSettingsPage(Request $request)  {

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Visit FOSO offer hunting settings page");

		$id = $request->id;
 		$offerhunting = CampaignOfferHunting::getOfferHunting($id);

		$memberIDinHunting = $offerhunting->member_id;
		$memberID = "";

		if ($memberIDinHunting == null){
			$member = Member::getMemberByMobile('+852'.$offerhunting->mobile_num); 
			if ($member != null ){	
				$memberID = $member->id;
			}
		}else{
			$memberID = $memberIDinHunting;
		}

		//----------------------------------------------------------------------------------------
		return view('foso.offerhunting.hunting_settings', [
			"id" => $id,
			"member_id" => $memberID,
			"offerhunting" => $offerhunting,
		]);
	}

	public function saveOfferHuntingSettingsAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		//  Check if all required parameters are available
		$parameterArray = array(
			"id", "created_at", "updated_at", 
			// "deleted_at", 
			"created_by", "updated_by", 
			// "deleted_by",
			"name", "mobile_num", 
			// "discount_content", "media",
			// "member_id", "status", 
			// "approved_point", "approved_at", "approved_by",
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

		//----------------------------------------------------------------------------------------
		$id = $request->input("id");
		$offerHunting = CampaignOfferHunting::getOfferHunting($id);

		if ($offerHunting == null)  {
			$response["status"] = -20;
			$response["message"] = "Error: Offer hunting '$id' does not exist.";
			return response()->json($response);
		}

		//----------------------------------------------------------------------------------------
		//  Save
		// $offerHunting->created_at = $request->input("created_at");
		// $offerHunting->updated_at = date("Y-m-d h:i:s");
		// $offerHunting->deleted_at = $request->input("deleted_at");
		$offerHunting->updated_by = __FUNCTION__;
		// $offerHunting->deleted_by = $request->input("deleted_by");
		$offerHunting->name = $request->input("name");
		$offerHunting->mobile_num = $request->input("mobile_num");

		// if ($offerHunting->member_id == null){
		// }else{ $offerHunting->member_id = $request->input("member_id");}
		// $offerHunting->status = $request->input("status");
		// $offerHunting->approved_at = $request->input("approved_at");
		// $offerHunting->approved_by = $request->input("approved_by");
		// $offerHunting->approved_point = $request->input("approved_point");
		
		//  Save to database table
		$result = $offerHunting->save();
		
		//----------------------------------------------------------------------------------------
		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Save settings of offer hunting [$id] ");

		//----------------------------------------------------------------------------------------
		//  Output now
		$response["status"] = 0;
		$response["message"] = "Done";
		return response()->json($response);
	}

	public function approveHuntingAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$id = $request->input("id");

		$offerHunting = CampaignOfferHunting::getOfferHunting($id);
		$memberIDinHunting = $offerHunting->member_id;

		$mobileNumOld = $request->input("old_mobile_num");
		$mobileNum = $request->input("mobile_num");
		$name = $request->input("name");
		// $approved_pt = $request->input("approved_point");

		// $offerHunting->updated_at = date("Y-m-d h:i:s");
		$offerHunting->updated_by = __FUNCTION__;
		$offerHunting->approved_at = date("Y-m-d h:i:s");
		$offerHunting->approved_by = __FUNCTION__;
		$memberNow = Member::getMemberByMobile('+852'.$mobileNum);

		// if ($mobileNumOld != $mobileNum){
		// 	$memberIDinHunting = $memberNow->id;
		// }

		if($memberNow == null){
			$memberNow = Member::createMember('+852'.$mobileNum);
			$memberNow->username = $name;
			$memberNow->save();
		}

		$memberIDinHunting = $memberNow->id;

		// $offerhunting->approved_point = $approved_pt;
		$offerHunting->member_id = $memberIDinHunting;
		$offerHunting->name = $name;
		$offerHunting->mobile_num = $mobileNum;
		$offerHunting->approved_point = intval('50'); 
		$memberNow->addOfferHuntingPoint();
		
		$offerHunting->status = "approved";
		$offerHunting->save();

		//----------------------------------------------------------------------------------------
		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Approve offer hunting [$id] ...");

		//----------------------------------------------------------------------------------------
		//  Finally
		$response["status"] = 0;
		$response['message'] = "OK";
		return response($response, 200);
	}

	public function rejectHuntingAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$id = $request->input("id");
		$oldStatus = $request->input("old_status");
		$mobileNum = $request->input("mobile");
		$offerHunting = CampaignOfferHunting::getOfferHunting($id);
		
		// $offerHunting->updated_at = date("Y-m-d h:i:s");
		$offerHunting->status = "rejected";
		$offerHunting->save();

		// if previous status is approved, have to withdraw the pt 
		if ($oldStatus == "approved"){
			$memberNow = Member::getMemberByMobile('+852'.$mobileNum);
			$memberNow->withdrawOfferHuntingPoint();
		}
		//----------------------------------------------------------------------------------------
		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Reject offer hunting [$id] ......");

		//----------------------------------------------------------------------------------------
		//  Finally
		$response["status"] = 0;
		$response['message'] = "OK";
		return response($response, 200);
	}

	// 2022.11.22 Kay for upload receipt  -- receipt sample for Channel
	//----------------------------------------------------------------------------------------
	public function offerChannelSapmlePage(Request $request)  {

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Visit FOSO campaign offer Channel setup page", "Offer");

		$offerCode = $request->offer_code;
		$offer = CampaignOffer::getOffer($offerCode);

		$unpickChannel = ChannelReceiptSample::getNotSelectedChannelByID($offer->id);
		$pickChannel = ChannelReceiptSample::getSelectedChannelByID($offer->id);
		// dd($unpickChannel, $pickChannel);

		$offerChannelRecord = CampaignOfferChannel::getOfferChannel($offer->id, date("Y-m-s H:i:s") );
		$startDate = null;
		$startTime = null;
		$endDate = null;
		$endTime = null;

		if ($offerChannelRecord){
			if (!is_null($offerChannelRecord->start_at)){
				$startDate = substr($offerChannelRecord->start_at, 0, 10);
				$startTime = substr($offerChannelRecord->start_at, -8, 5);
			}
			if (!is_null($offerChannelRecord->end_at)){
				$endDate = substr($offerChannelRecord->end_at, 0, 10);
				$endTime = substr($offerChannelRecord->end_at, -8, 5);
			}else{
				$offerEnd = $offer->end_at;
				$end = date("Y-m-d 23:59:59", strtotime("$offerEnd +30 days"));
				$endDate = substr($end, 0, 10);
				$endTime = substr($end, -8, 5);
			}
		}else{
			$offerEnd = $offer->end_at;
			$end = date("Y-m-d 23:59:59", strtotime("$offerEnd +30 days"));
			$endDate = substr($end, 0, 10);
			$endTime = substr($end, -8, 5);
		}

		if(!empty($offerChannelRecord->receipt_approval_point)){
			$approvePoint = $offerChannelRecord->receipt_approval_point;
		}else{
			$approvePoint = 10;
		}
		return view('foso.campaigns.offer_channel', [
			"startDate" => $startDate,
			"startTime" => $startTime,
			"endDate" => $endDate,
			"endTime" => $endTime,
			"approvePoint" => $approvePoint,
			"unpickChannel" => $unpickChannel,
			"pickChannel" => $pickChannel,
			"offerCode" => $offerCode,
			"offer" => $offer,
		]);
	}

	//----------------------------------------------------------------------------------------
	public function saveofferChannelSapmlePage(Request $request)  {

		//  Activity log
		$user = Auth::user();
		FosoActivityLog::addLog($user->name, $user->email, url()->full(), "Visit FOSO campaign offer Channel setup page", "Offer");

		$offerCode = $request->offer_code;
		$offer = CampaignOffer::getOffer($offerCode);

		$record = CampaignOfferChannel::firstOrCreate(['offer_id' => $offer->id ]);

		$starttime = $request->input("startTime");
		if (empty($request->input("startTime"))){$starttime = " 00:00";}
		if (empty($request->input("startDate"))){
			$record->start_at = null;
		}else{
			$record->start_at = $request->input("startDate")." ".$starttime.":00";
		}

		$endtime = $request->input("endTime");
		if (empty($request->input("endTime"))){$endtime = " 23:59";}
		if (empty($request->input("endDate"))){
			$record->end_at = null;
		}else{
			$record->end_at = $request->input("endDate")." ".$endtime.":59";
		}

		$record->receipt_approval_point = intval($request->input('point'));
		if (!empty( $request->input('final'))){$record->sample_id_involved = $request->input('final');}

		$record->save();

		$response["status"] = 10;
		$response['message'] = "OK";
		return response($response, 200);
	}

	//----------------------------------------------------------------------------------------
	public function getQuickReplyContentAPI(Request $request)  {

		$response = array(
			"timeStamp" => Date("YmdHis"),
			"apiName" => __FUNCTION__,
			"status" => -99,
			"message" => "Unexpected error...",
		);

		$quickreplyID = $request->templateID;

		$content = CampaignQuickReply::where('id', $quickreplyID)->first();

		$replyJSON = json_decode($content->reply,true);
		$reply = $replyJSON["actions"];

		if($content){
			$response["text"] = $content->text;
			$response['reply'] = $reply;
			$response["status"] = 10;
			$response['message'] = "OK";
		}

		return response($response, 200);
	}

	// 2023.03.27 Kay -----------------------------------------------------------------------
	// TODO: Kernel - check and resize all image in the folder
	public function checkAllBannerAndKV(){
		
		$kvList = CampaignBanner::getListwithURL("key-visuals");
		$bannerList = CampaignBanner::getListwithURL("banners");
		$kvMobile = 0;
		$kvDesktop = 0;
		$changedbanner = 0;

// dd($kvList, $bannerList);
		$json = [];

		foreach ($kvList as $kv){
			$json = json_decode($kv, true);
			$mobileImg = $json["mobile"];
			$desktopImg = $json["desktop"];

			if (!empty($mobileImg)){
				$resultkvmobile = $this->checktoResizeUploadedImage(public_path($mobileImg), 448, 282);
				if($resultkvmobile){$kvMobile++;}
			}
			if (!empty($desktopImg)){
				$resultkvdesktop = $this->checktoResizeUploadedImage(public_path($desktopImg), 1194, 356);
				if($resultkvdesktop){$kvDesktop++;}
			}
 		}

		foreach ($bannerList as $banner){
			$json = json_decode($banner, true);
			$img = $json["image"];
			if (!empty($img)){
				$result = $this->checktoResizeUploadedImage(public_path($img), 448, 282);
				if($result){$changedbanner++;}
			}
		}

		Log::info("'Amount of resized key-visuals (mobile):".$kvMobile."'");
		Log::info("'Amount of resized key-visuals (desktop):".$kvDesktop."'");
		Log::info("'Amount of resized banner :".$changedbanner."'");
	}

	//----------------------------------------------------------------------------------------
	//  TODO: function of resize upload image
	public function checktoResizeUploadedImage($imageFile=null, $maxwidth=100, $maxhight=50 )  {

		if (empty($imageFile)){
			$response["message"] = "Filename is empty";
			return response()->json($response);
		}

		$haveUpdate = false;

		if (file_exists($imageFile) && getimagesize($imageFile) == true){
			
			list($width, $height, $type, $attr) = getimagesize($imageFile);

			if ( $width > $maxwidth && $height > $maxhight ) {
	
				$ratio = $width/$height;
				$rationWidth = $width / $maxwidth;
				$rationHeight = $height / $maxhight;
				if($rationWidth < $rationHeight) {
					$new_width = $maxwidth;
					$new_height = $new_width/$ratio;
				} else {
					$new_height = $maxhight;
					$new_width = $new_height*$ratio;
				}
	
				$file = explode('.', $imageFile);
				$fileCount = count($file);
	
				$source = imagecreatefromstring( file_get_contents( $imageFile ) );
				$destination = imagecreatetruecolor( $new_width, $new_height );
			
				imagealphablending($destination, false); // keep png with transparent bg
				imagesavealpha($destination, true); // keep png with transparent bg
	
				imagecopyresampled( $destination, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
				imagedestroy( $source );

				$lowerExtension = strtolower($file[$fileCount-1]);
				if ($lowerExtension == "png")  {imagepng($destination, $imageFile);}
				if ($lowerExtension == "jpg" || $lowerExtension == "jpeg")  {imagejpeg($destination, $imageFile);}

				imagedestroy( $destination );
				$haveUpdate = true;
// list($width, $height, $type, $attr) = getimagesize($imageFile);
// dd($width, $height, $imageFile );
			}
		}
		//  Output now
		return $haveUpdate;
	}

	// 2023.03.27 End -----------------------------------------------------------------------

}