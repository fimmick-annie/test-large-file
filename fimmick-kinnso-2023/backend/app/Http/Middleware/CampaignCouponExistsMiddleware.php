<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020.  All rights reserved.
//----------------------------------------------------------------------------------------

namespace App\Http\Middleware;

//----------------------------------------------------------------------------------------
use Closure;

use App\Models\CampaignCoupon;
use App\Models\CampaignOffer;

//========================================================================================
class CampaignCouponExistsMiddleware  {

	//----------------------------------------------------------------------------------------
	//  Check if unqiue code exists in database, if yes then load the record
	public function handle($request, Closure $next)  {

		$code = $request->unique_code;

		$couponArray = CampaignCoupon::where('unique_code', $code)->orderBy('coupon_order', 'asc')->get();
		$selectedCoupon = $couponArray->first();
		if (!$selectedCoupon)  {return redirect()->route('campaign.coupon.invalid.html', ["unique_code" => $code]);}

		//  Save coupon object for rest program to use
		$request->coupon = $selectedCoupon;

		//  Retrieve offer object
		$offer = null;
		$bladeFolder = null;
		if ($selectedCoupon->offer()->exists())  {

			$offer = $selectedCoupon->offer;
			$bladeFolder = $offer->blade_folder;
		}

		$request->offer = $offer;
		$request->offerBladeFolder = $bladeFolder;

		//  Call next middleware
		return $next($request);
	}
}
