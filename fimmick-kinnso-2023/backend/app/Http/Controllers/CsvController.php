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
use App\Exports\SmsExport;
use Illuminate\Http\Request;
use App\Exports\CouponExport;

//========================================================================================
class CsvController extends Controller  {

	//----------------------------------------------------------------------------------------
	public function sms()  {
		return (new SmsExport)->download(filename('sms_logs', '_Ymdhis'), \Maatwebsite\Excel\Excel::CSV);
	}

	//----------------------------------------------------------------------------------------
	public function coupons()  {
		return (new CouponExport)->download(filename('coupons', '_Ymdhis'), \Maatwebsite\Excel\Excel::CSV);
	}
}
