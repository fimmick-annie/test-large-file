<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2021.  All rights reserved.
//----------------------------------------------------------------------------------------

namespace App\Http\Controllers;

//----------------------------------------------------------------------------------------
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

//========================================================================================
class PaymentController extends Controller  {

	//----------------------------------------------------------------------------------------
	//  https://www.kinnso.com/api/payment/ccba/webhook?POSID=313375473&BRANCHID=010741100
	//  &ORDERID=892109011746319&PAYMENT=1.20&CURCODE=344&REMARK1=Fimmick&REMARK2=&SUCCESS=Y
	//  &ACCDATE=&SIGN=2d6cd71719d29e10d2dd2d3fa83c07c60eeac1015f51e07ee30c421d1af6bf8aa8f
	//  00e1cdf407700e87077e27fefa758da0cb2acdaf17510b876ab9d8b7f0ff99fa26a9b1ade1f777b746
	//  7b834c4088e02f895948380dbaa48a230ed1a8fd9f73bb1896f690a882655226275d7ea29335fd77ff
	//  ab2262e6acb44a063f8b96b4e
	function webhookCCBAAPI(Request $request)  {

		$path = storage_path("logs/".date("Ymd")."_ccba_webhook.log");
		$content = date("Y-m-d H:i:s")." ".json_encode($request);

		file_put_contents($content, $path, FILE_APPEND);

		//  Parameter
		$posID = $request->input("POSID");
		$orderID = $request->input("ORDERID");
		$payment = $request->input("PAYMENT");
		$remark1 = $request->input("REMARK1");
		$remark2 = $request->input("REMARK2");
		$branchID = $request->input("BRANCHID");
		$currencyCode = $request->input("CURCODE");

		$success = $request->input("SUCCESS");
		$accDate = $request->input("ACCDATE");
		$signature = $request->input("SIGN");

		//----------------------------------------------------------------------------------------
		//  Check if data is valid
		if ($success != "Y")  {
			return view('payments.ccba.failed');
		}

		//  TODO: Verify signature
		$key = env("CCBA_PUBLIC_KEY", "a353d9efbe090c30cb242a1f020111");

		//----------------------------------------------------------------------------------------
		//  TODO: Complete payment node and send out template message
		$message = "你的付款已被接納，請回覆 0 字繼續。";

		return json_encode(array("status" => "ok"));
	}
}
