<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020-2021.  All rights reserved.
//----------------------------------------------------------------------------------------

namespace App\Http\Controllers;

//----------------------------------------------------------------------------------------
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

//========================================================================================
class Controller extends BaseController  {

	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	//----------------------------------------------------------------------------------------
	function __construct()  {
		app()->setLocale("zh-HK");
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

}
