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

//========================================================================================
class FOSOIPRestrictionMiddleware  {

	//----------------------------------------------------------------------------------------
	//  Coupon code is valid, get
	public function handle($request, Closure $next)  {

		$fosoIPRestrictionEnabled = env('FOSO_IP_RESTRICTION_ENABLED', false);
		if ($fosoIPRestrictionEnabled == false)  {return $next($request);}

		$host = gethostname();
		if ($host == "development-server")  {return $next($request);}

		$whitelistedIPAddress = array(
			"127.0.0.1",				// Local machine
			"59.152.241.226",			// Fimmick HK office
			"203.174.36.68",			// Fimmick HK office
			"223.197.178.58",			// Fimmick HK office

			"218.102.198.207",			// Willy Lai
		);

		$ipAddress = $request->ip();
		$result = in_array($ipAddress, $whitelistedIPAddress);
		if ($result != true)  {
			abort(404);
		}

		return $next($request);
	}
}
