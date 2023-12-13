<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020.  All rights reserved.
//----------------------------------------------------------------------------------------

$syntaxToken = "[0-9A-Za-z-]{16}";

//----------------------------------------------------------------------------------------
//  User login related routes
Route::prefix("/login")->name("website.login")->group(function () use ($syntaxToken)  {

	//----------------------------------------------------------------------------------------
	Route::get("/", "LoginController@loginPage")->name(".html");
	Route::post("/request-token", "LoginController@requestLoginTokenAPI")->name(".requesttoken.api");

	Route::get("/token/{token}", "LoginController@voidLoginToken")
	->where("token", $syntaxToken)
	->name(".voidlogintoken.html");

});

