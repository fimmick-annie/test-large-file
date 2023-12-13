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
use App\Models\CampaignOffer;

//========================================================================================
class CampaignOfferExistsMiddleware  {

	//----------------------------------------------------------------------------------------
	public function handle($request, Closure $next)  {
		$offerCode = $request->offer_code;
		$offer = CampaignOffer::where('offer_code', $offerCode)->first();
		if (!$offer)  {
			if ($request->ajax() == true)  {

				$response = array(
					"timeStamp" => Date("YmdHis"),
					"apiName" => __FUNCTION__,
					"status" => -99,
					"message" => "### Offer not found...",
					"code" => $offerCode,
				);
				return response()->json($response);

			}  else  {abort('404');}
		}

		$bladeFolder = $offer->blade_folder;
		if (empty($bladeFolder))  {$bladeFolder = "default_offer_template";}

		$request->offer = $offer;
		$request->offerBladeFolder = $bladeFolder;

		return $next($request);
	}
}
