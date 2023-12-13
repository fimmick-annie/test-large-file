<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020.  All rights reserved.
//----------------------------------------------------------------------------------------

namespace App\Http;

//----------------------------------------------------------------------------------------
use Illuminate\Foundation\Http\Kernel as HttpKernel;

//========================================================================================
class Kernel extends HttpKernel  {

	//----------------------------------------------------------------------------------------
	protected $middleware = [
		\App\Http\Middleware\CheckForMaintenanceMode::class,
		\Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
		\App\Http\Middleware\TrimStrings::class,
		\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
		\App\Http\Middleware\TrustProxies::class,
	];

	//----------------------------------------------------------------------------------------
	protected $middlewareGroups = [
		'web' => [
			\App\Http\Middleware\EncryptCookies::class,
			\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
			\Illuminate\Session\Middleware\StartSession::class,
			// \Illuminate\Session\Middleware\AuthenticateSession::class,
			\Illuminate\View\Middleware\ShareErrorsFromSession::class,
			\App\Http\Middleware\VerifyCsrfToken::class,
			\Illuminate\Routing\Middleware\SubstituteBindings::class,
		],

		'api' => [
			'throttle:60,1',
			'bindings',
		],
	];

	//----------------------------------------------------------------------------------------
	protected $routeMiddleware = [
		'auth' => \App\Http\Middleware\Authenticate::class,
		'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
		'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
		'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
		'can' => \Illuminate\Auth\Middleware\Authorize::class,
		'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
		'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
		'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
		'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
		'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
		'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
		'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,

		//  Offer related middleware
		'campaign.offer.exists' => \App\Http\Middleware\CampaignOfferExistsMiddleware::class,
		'campaign.offer.valid' => \App\Http\Middleware\CampaignOfferValidMiddleware::class,

		//  Coupon related middleware
		'campaign.coupon.exists' => \App\Http\Middleware\CampaignCouponExistsMiddleware::class,
		'campaign.coupon.valid' => \App\Http\Middleware\CampaignCouponValidMiddleware::class,

		//  FOSO related middleware
		'foso.restriction.ip' => \App\Http\Middleware\FOSOIPRestrictionMiddleware::class,

		//  UAT Guard Middleware
		'uat_guard' => \App\Http\Middleware\UATguard::class,
	];

	//----------------------------------------------------------------------------------------
	protected $middlewarePriority = [
		\Illuminate\Session\Middleware\StartSession::class,
		\Illuminate\View\Middleware\ShareErrorsFromSession::class,
		\App\Http\Middleware\Authenticate::class,
		\Illuminate\Session\Middleware\AuthenticateSession::class,
		\Illuminate\Routing\Middleware\SubstituteBindings::class,
		\Illuminate\Auth\Middleware\Authorize::class,
	];
}
