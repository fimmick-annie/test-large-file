<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020.  All rights reserved.
//----------------------------------------------------------------------------------------

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


//----------------------------------------------------------------------------------------
//  Webhooks
Route::group([
	"prefix" => "/webhook",
], function()  {

	Route::post('/offerRegistration', "CampaignWebhookController@offerRegistrationAPI")->name('webhook.offer.registration.json');
	Route::post('/couponActivation', "CampaignWebhookController@couponActivationAPI")->name('webhook.coupon.activation.json');

});

//----------------------------------------------------------------------------------------
//  FOSO Related Routes
//----------------------------------------------------------------------------------------
$syntax = "[0-9A-Za-z]+";

Route::prefix('/foso')
	->name('foso.')->middleware([
		'foso.restriction.ip',
		'auth:api',
	])
	->group(function () use ($syntax) {

	Route::prefix('/campaigns')
		->name('campaigns.')
		->group(function () use ($syntax) {

		//----------------------------------------------------------------------------------------
		//  FOSO: Campaigns - WhatsApp section
		Route::prefix('/whatsapp')
			->name('whatsapp.')
			->group(function ()  {

			Route::post('/resend', "FOSOMainController@whatsAppQueueResendAPI")->name('queue.resend.api');
			Route::post('/cancel', "FOSOMainController@whatsAppQueueCancelAPI")->name('queue.cancel.api');
		});
	});
});

//----------------------------------------------------------------------------------------
// Route::post("/scan-logs", function(Request $request)  {
// 	$array = $request->all();
// // 	$filePath = asset(date("Ymd").".log");
// // 	file_put_contents($filePath, json_encode($array)."\n", FILE_APPEND);
// 	Log::error(json_encode($array));
// 	echo("Done!");
// });


//----------------------------------------------------------------------------------------
//  Chatbot Related Routes
//----------------------------------------------------------------------------------------
Route::group([
	"prefix" => "/chatbot",
], function()  {

	//  WhatsApp Simulator
	Route::post('whatsAppSimulator', "ChatbotController@whatsAppSimulator" );

	//----------------------------------------------------------------------------------------
	//  Twilio
	Route::group([
		"prefix" => "/twilio",
	], function()  {

		Route::post("/message", "ChatbotController@webhookTwilioMessageComesInAPI")->name("chatbot.twillio.message.json");
		Route::post("/status", "ChatbotController@webhookTwilioStatusCallbackAPI")->name("chatbot.twillio.status.json");
		Route::post("/fallback", "ChatbotController@webhookTwilioFallbackAPI")->name("chatbot.twillio.fallback.json");
	});

	//----------------------------------------------------------------------------------------
	//  Facebook Messenger
	Route::group([
		"prefix" => "/facebook",
	], function()  {

		Route::post("/message", "ChatbotController@webhookFacebookMessageComesInAPI")->name("chatbot.facebook.message.json");
	});

	//----------------------------------------------------------------------------------------
	//  LINE
	Route::group([
		"prefix" => "/line",
	], function()  {

		Route::post("/message", "ChatbotController@webhookLineMessageComesInAPI")->name("chatbot.line.message.json");
	});
});

// Route::resource('member_events', 'MemberEventsController');


//----------------------------------------------------------------------------------------
Route::group([
	"prefix" => "/payment",
], function()  {

	//  Facebook Messenger
	Route::group([
		"prefix" => "/ccba",
	], function()  {

		Route::get("/webhook", "PaymentController@webhookCCBAAPI")->name("payment.ccba.json.get");
		Route::post("/webhook", "PaymentController@webhookCCBAAPI")->name("payment.ccba.json");
	});

});

Route::post('/increaseLikeCounter', 'CampaignOfferController@increaseLikeCounter');
Route::post('/decreaseLikeCounter', 'CampaignOfferController@decreaseLikeCounter');
