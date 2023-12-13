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
use Session;

use App\Models\CampaignCoupon;

//========================================================================================
class CampaignCouponValidMiddleware  {

	//----------------------------------------------------------------------------------------
	//  Coupon code is valid, get
	public function handle($request, Closure $next)  {

		$coupon = $request->coupon;
		$code = $request->unique_code;

		$couponArray = CampaignCoupon::where('unique_code', $code)->orderBy('coupon_order', 'asc')->get();

		//  If non selected then show invlid
		$selectedCoupon = $couponArray->first();
		if (!$selectedCoupon)  {return redirect()->route('campaign.coupon.invalid.html', ["unique_code" => $code]);}

		//  If more than one coupon, get the last un-used one or last one
		$allUsed = true;
		$allExpired = true;
		foreach ($couponArray as $coupon)  {

			$expiryAt = strtotime($coupon->expiry_at);
			if ($coupon->use_at == null)  {$allUsed = false;}
			if ($expiryAt > time() && $expiryAt != 0)  {$allExpired = false;}

			if ($coupon->use_at == null && $expiryAt > time() && $expiryAt != 0)  {

				//  Coupon not used yet and not expired yet, pick this one
				$selectedCoupon = $coupon;
				break;
			}
		}

		//  Retrieve offer object
		$offer = null;
		$bladeFolder = null;
		if ($selectedCoupon->offer()->exists())  {

			$offer = $selectedCoupon->offer;
			$bladeFolder = $offer->blade_folder;
		}

		$request->offer = $offer;
		$request->offerBladeFolder = $bladeFolder;
		$request->coupon = $selectedCoupon;

		//  All used or expired then show thank you
		if ($allExpired == true)  {return redirect()->route('campaign.coupon.expired.html', ["unique_code" => $code]);}
		if ($allUsed == true)  {

			//  Thank you page will check session and redirect to landing if not found
			//  It will cause forever loop if session not found
			Session::put("couponThankYou", $code);
			return redirect()->route('campaign.coupon.thankyou.html', ["unique_code" => $code]);
		}

		return $next($request);
	}
}
