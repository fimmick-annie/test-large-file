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
class CampaignOfferValidMiddleware  {

	//----------------------------------------------------------------------------------------
	public function handle($request, Closure $next)  {

		$offer = $request->offer;
		$offerCode = $request->offer_code;

		//  Not start?
		if ($offer->start_at != null && strtotime($offer->start_at) > time())  {
			return redirect()->route("campaign.offer.comingsoon.html", ["offer_code" => $offerCode]);
		}

		//  Already ended?
		if ($offer->end_at != null && strtotime($offer->end_at) < time())  {
			return redirect()->route("campaign.offer.expired.html", ["offer_code" => $offerCode]);
		}

		//  Out of quota?
		if ($offer->quota <= $offer->quota_issued)  {
			return redirect()->route("campaign.offer.outofquota.html", ["offer_code" => $offerCode]);
		}

		return $next($request);
	}
}
