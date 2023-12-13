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
use App\Models\FosoUser;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;

//========================================================================================
class UATguard extends AuthenticateWithBasicAuth  {

	//----------------------------------------------------------------------------------------
	//  Handle an incoming request.
	public function handle($request, Closure $next, $guard = null, $field = null)  {

		$user = auth()->user();

		$whitelist = [
			'59.152.241.226',		// Office IP
			'192.168.56.1',
		];
		$isWhiteList = false;

		//  Is in whitelist
		foreach ($whitelist as $ip)  {
			if ($request->ip() != $ip)  {
				continue;
			}
			$isWhiteList = true;
		}

		//  Is in LAN network
		$ipArray = explode(".", $request->ip());
		if ($ipArray[0] == "192" && $ipArray[1] == "168")  {
			$isWhiteList = true;
		}
		if ($ipArray[0] == "127" && $ipArray[1] == "0")  {
			$isWhiteList = true;
		}

		//  Ask for password if in dev or staging state
		if (\App::environment(['local', 'staging']) && !$isWhiteList)  {
			return $this->auth->guard($guard)->basic($field ?: 'email') ?: $next($request);
		}
		return $next($request);
	}
}
