<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020.  All rights reserved.
//----------------------------------------------------------------------------------------

//  Force HTTPS for links
// URL::forceScheme('https');

//  Default authentication routes
// Auth::routes();

//  Default handler if route+method not found
Route::fallback(function()  {return redirect()->route('campaign.offer.listing.html');});

//----------------------------------------------------------------------------------------
//  Mobile App Related Routes
//----------------------------------------------------------------------------------------
Route::group([
	"prefix" => "/app",
],	function() {

	Route::get('/', 'AppController@root');
	Route::get('/getUserInfo', 'AppController@getUserInfo');
	Route::post('/login', 'AppController@loginAPI');
	// Route::post('/logout', 'AppController@loginAPI');
});


//----------------------------------------------------------------------------------------
//  FOSO Related Routes
//----------------------------------------------------------------------------------------
// $syntax = "[0-9A-Za-z-]+";
$syntax = "[0-9A-Za-z-]{8,}";
Route::prefix('/foso')
	->name('foso.')->middleware('foso.restriction.ip')
	->group(function () use ($syntax)  {

	//----------------------------------------------------------------------------------------
	//  For database migration use
	//  *** Please remark it immediately after use ***
	Route::get('/artisan/cache-clear', function()  {
		Artisan::call('cache:clear', []);
		dd(Artisan::output());
	});
	Route::get('/artisan/config-clear', function()  {
		Artisan::call('config:clear', []);
		dd(Artisan::output());
	});
	Route::get('/artisan/view-clear', function()  {
		Artisan::call('view:clear', []);
		dd(Artisan::output());
	});
	Route::get('/artisan/route-clear', function()  {
		Artisan::call('route:clear', []);
		dd(Artisan::output());
	});

// 	Route::get('/artisan/config-cache', function()  {
// 		Artisan::call('config:cache', []);
// 		dd(Artisan::output());
// 	});
// 	Route::get('/artisan/view-cache', function()  {
// 		Artisan::call('view:cache', []);
// 		dd(Artisan::output());
// 	});
// 	Route::get('/artisan/route-cache', function()  {
// 		Artisan::call('route:cache', []);
// 		dd(Artisan::output());
// 	});

	//  Migration
// 	Route::get('/artisan/migrate', function()  {
// 		Artisan::call('migrate', ['--force' => true]);
// 		dd(Artisan::output());
// 	});
// 	Route::get('/artisan/migrate-fresh', function()  {
// 		define('STDIN', fopen("php://stdin", "r"));
// 		Artisan::call('migrate:fresh', ["--force" => true, "--seed" => true]);
// 		dd(Artisan::output());
// 	});

	//  Data archive
	Route::get('/artisan/data-archive', function()  {
		define('STDIN', fopen("php://stdin", "r"));
		Artisan::call('cron:processDataArchive');
		dd(Artisan::output());
	});

	//  Laravel version
// 	Route::get('/artisan/info', function()  {
// 		phpinfo();
// 
// 		define('STDIN', fopen("php://stdin", "r"));
// 		Artisan::call('help', ["--version" => true]);
// 		dd(Artisan::output());
// 	});

	//  Debug & Testing
	Route::get('/artisan/copy', function()  {

// 		$resourcePath = resource_path();
// 		$sourcePath = $resourcePath."/views/campaigns/40_lazy_pack/medias/coupon_submit_button.png";
// 
// 		$publicPath = public_path();
// 		$targetPath = $publicPath."/offers/s-almond-dessert-2022/coupon_submit_button.png";
// 
// 		$result = copy($sourcePath, $targetPath);
// 		dump("Result: $result");
	});

	//  Kay 2023.02.09 recovery referal code
	Route::get('/artisan/recovery-task/referral-code', function()  {
		define('STDIN', fopen("php://stdin", "r"));
		Artisan::call('cron:referralCodeRecovery');
		dd(Artisan::output());
	});

	//  Kay 2023.03.27 convert all banner or KV in right size
	Route::get('/artisan/banner/resize', function()  {
		define('STDIN', fopen("php://stdin", "r"));
		Artisan::call('cron:checkAllBannerAndKV');
		dd(Artisan::output());
	});

	Route::get('/process-referral', 'CouponChatbotController@processJourneyReferralNode');

	//----------------------------------------------------------------------------------------
	//  Cronjobs
	Route::get('/cronjob/offerDailyReport', 'ReportController@processOfferWhatsAppDailyReport');
	Route::get('/cronjob/offerMonthlyReport', 'ReportController@processOfferWhatsAppMonthlyReport');
	Route::get('/cronjob/couponDailyReport', 'ReportController@processOfferCouponDailyReport');

	//----------------------------------------------------------------------------------------
	Route::name('main.')
		->group(function ()  {

		// Foso authentication page
		Route::get('/login', 'FOSOMainController@loginPage')->name('login.html');
		Route::post('/login', 'FOSOMainController@loginAPI');
		Route::post('/logout', 'FOSOMainController@logoutAPI')->name('logout.json');
	});

	Route::middleware('auth:foso')
		->group(function() use ($syntax)  {

		Route::resource('users', 'UserController');
		Route::resource('roles', 'RoleController');
		Route::resource('permissions', 'PermissionController');
		Route::resource('member_events', 'MemberEventsController', ["names"=>["index"=>"member.events"]]);

		//  FOSO home page
		Route::get('/', "FOSOMainController@homePage")->name('main.home.html');

		//----------------------------------------------------------------------------------------
		//  Campaigns FOSO CMS pages
		Route::prefix('/campaigns')
			->name('campaigns.')
			->group(function () use ($syntax)  {

			Route::get('/', function()  {return redirect()->route('foso.campaigns.offer.html');})->name('html');

			//----------------------------------------------------------------------------------------
			//  FOSO: Campaigns - Read me section
			Route::middleware([
				"role:Super-Administrator|Administrator"
			])->get('/read-me', "FOSOMainController@readMePage")->name('readme.html');

			Route::get('/whatsAppSimulator', "FOSOMainController@campaignsWhatsAppSimulatorPage")->name('whatsAppSimulator.html');

			//----------------------------------------------------------------------------------------
			//  FOSO: Campaigns - FAQ section
			Route::middleware([
			])->get('/faq', "FOSOMainController@faqPage")->name('faq.html');

			//----------------------------------------------------------------------------------------
			//  FOSO: Campaigns - Offer List Management section
			Route::prefix('/offerlist')
				->name('offerlist.')
				->group(function () {
					Route::get('/', "FOSOMainController@offerListingPage")->name('management.html');
					Route::get('/list', "FOSOMainController@offerListingPageGetList")->name('getlist.json');
					Route::post('/offers-by-list', "FOSOMainController@offerListingPageGetOffersByList")->name('getoffersbylist.json');
					Route::post('/new-list', "FOSOMainController@offerListingPageCreateNewList")->name('createnewlist.json');
					Route::post('/rearrange', "FOSOMainController@offerListingPageRearangeOffersPermutation")->name('rearangeofferspermutation.json');
					Route::post('/add-offer', "FOSOMainController@offerListingPageAddOfferIntoList")->name('addofferintolist.json');
					Route::post('/update-offer', "FOSOMainController@offerListingPageUpdateOfferIntoList")->name('updateofferintolist.json');

					Route::post('/remove-offer', "FOSOMainController@offerListingPageReniveOfferIntoList")->name('removeofferintolist.json');

			});

			Route::prefix('/offercollation')
				->name('offercollation.')
				->group(function () {
					Route::get('/', "FOSOMainController@offerCollationPage")->name('html');
					Route::get('/json', "FOSOMainController@offerCollationAPI")->name('json');
					Route::post('/clean-redundant-file', "FOSOMainController@offerDeleteInServeAPI")->name('redundantfile.json');
					Route::post('/clean-redundant-reocrd', "FOSOMainController@offerDeleteInRecordAPI")->name('redundantrecord.json');
			});

			//----------------------------------------------------------------------------------------
			//  FOSO: Campaigns - Offers section
			//  /campaigns/offer/ = Offer list
			//  /campaigns/offer/1234/ = Offer settings
			//  /campaigns/offer/1234/coupons = Offer coupon list
			//  /campaigns/offer/1234/coupons/download = Offer coupon CSV download
			Route::prefix('/offer')
				->name('offer.')
				->group(function () use ($syntax)  {

				Route::middleware([
					"role:Super-Administrator|Administrator"
				])->group(function()  {

					Route::get('/', "FOSOMainController@offerListPage")->name('html');
					Route::get('/json', "FOSOMainController@offerListAPI")->name('json');

					// Kay 2022.09.28
					Route::post('/import-file', "FOSOMainController@offerImportAPI")->name('importoffer.api');
					

				});

				$syntaxAlphaNumeric = "[0-9A-Za-z_-]+";
				Route::prefix('/{offer_code}')
					->group(function () use ($syntaxAlphaNumeric)  {

					Route::get('/', "FOSOMainController@offerPage")->where("offer_code", $syntaxAlphaNumeric)->name('root.html');

					Route::get('/settings', "FOSOMainController@offerSettingsPage")->where("offer_code", $syntaxAlphaNumeric)->name('settings.html');
					Route::post('/settings', "FOSOMainController@saveOfferSettingsAPI")->where("offer_code", $syntaxAlphaNumeric)->name('settings.json');
					Route::post('/settings/clone-offer', "FOSOMainController@cloneOfferAPI")->where("offer_code", $syntaxAlphaNumeric)->name('cloneoffer.json');
					Route::post('/settings/out-of-quota', "FOSOMainController@outOfQuotaAPI")->where("offer_code", $syntaxAlphaNumeric)->name('outofquota.json');
					Route::post('/settings/resume-quota', "FOSOMainController@resumeQuotaAPI")->where("offer_code", $syntaxAlphaNumeric)->name('resumequota.json');
					Route::post('/settings/clearwhitelisted', "FOSOMainController@clearAllWhitelistedAPI")->where("offer_code", $syntaxAlphaNumeric)->name('clearwhitelisted.json');

					// Kay 2022.09.28
 					Route::get('/settings/export-file', "FOSOMainController@exportOfferFileAPI")->where("offer_code", $syntaxAlphaNumeric)->name('exportoffer.json');

					Route::get('/resources', "FOSOMainController@offerResourcesPage")->where("offer_code", $syntaxAlphaNumeric)->name('resources.html');
					Route::post('/resources', "FOSOMainController@offerResourcesAPI")->where("offer_code", $syntaxAlphaNumeric)->name('resources.json');
					Route::post('/resources/upload', 'FOSOMainController@offerResourcesUploadAPI')->where("offer_code", $syntaxAlphaNumeric)->name('resources.upload');

					Route::get('/rules', "FOSOMainController@offerRulesPage")->where("offer_code", $syntaxAlphaNumeric)->name('rules.html');
					Route::post('/rules', "FOSOMainController@saveOfferRulesAPI")->where("offer_code", $syntaxAlphaNumeric)->name('rules.json');

					Route::prefix('/coupons')
						->name('coupons.')
						->group(function () use ($syntaxAlphaNumeric)  {

						Route::get('/', "FOSOMainController@offerCouponListPage")->where("offer_code", $syntaxAlphaNumeric)->name('html');
						Route::get('/json', "FOSOMainController@offerCouponListAPI")->where("offer_code", $syntaxAlphaNumeric)->name('json');
						Route::get('/csv', "FOSOMainController@offerCouponCSVDownloadAPI")->where("offer_code", $syntaxAlphaNumeric)->name('csv.file');

						Route::post('/clearwhitelisted', "FOSOMainController@offerCouponClearWhitelistedAPI")->where("offer_code", $syntaxAlphaNumeric)->name('clearwhitelisted.json');
						Route::get('/{unique_code}', "FOSOMainController@offerCouponDetailsPage")->where("offer_code", $syntaxAlphaNumeric)->where("unique_code", $syntaxAlphaNumeric)->name('uniquecode.html');
					});

					Route::get('/coupon-pool', "FOSOMainController@offerCouponPoolPage")->where("offer_code", $syntaxAlphaNumeric)->name('couponpool.html');
					Route::get('/coupon-pool/json', "FOSOMainController@offerCouponPoolListAPI")->where("offer_code", $syntaxAlphaNumeric)->name('couponpool.json');
					Route::post('/coupon-pool/json', "FOSOMainController@offerCouponPoolUploadAPI")->where("offer_code", $syntaxAlphaNumeric)->name('couponpool.upload.json');
					Route::get('/coupon-pool/confirm', "FOSOMainController@offerCouponPoolConfirmPage")->where("offer_code", $syntaxAlphaNumeric)->name('couponpool.confirm.html');
					Route::post('/coupon-pool/confirm', "FOSOMainController@offerCouponPoolConfirmAPI")->where("offer_code", $syntaxAlphaNumeric)->name('couponpool.confirm.json');
					Route::post('/coupon-pool/clearwhitelisted', "FOSOMainController@offerCouponPoolClearWhitelistedAPI")->where("offer_code", $syntaxAlphaNumeric)->name('couponpool.clearwhitelisted.json');
					Route::post('/coupon-pool/image-confirm/json', "FOSOMainController@offerCouponPoolImageUploadConfirmAPI")->where("offer_code", $syntaxAlphaNumeric)->name('couponpool.image.upload.confirm.json');
					Route::post('/coupon-pool/image/json', "FOSOMainController@offerCouponPoolImageUploadAPI")->where("offer_code", $syntaxAlphaNumeric)->name('couponpool.image.upload.json');

					Route::get('/quotas', "FOSOMainController@offerQuotasPage")->where("offer_code", $syntaxAlphaNumeric)->name('quotas.html');
					Route::get('/quotas/json', "FOSOMainController@offerQuotasListAPI")->where("offer_code", $syntaxAlphaNumeric)->name('quotas.json');
					Route::post('/quotas/upload', "FOSOMainController@offerQuotasUploadAPI")->where("offer_code", $syntaxAlphaNumeric)->name('quotas.upload.json');
					Route::get('/quotas/confirm', "FOSOMainController@offerQuotasConfirmPage")->where("offer_code", $syntaxAlphaNumeric)->name('quotas.confirm.html');
					Route::post('/quotas/confirm', "FOSOMainController@offerQuotasConfirmAPI")->where("offer_code", $syntaxAlphaNumeric)->name('quotas.confirm.json');

					Route::get('/whatsapp', "FOSOMainController@offerWhatsAppPage")->where("offer_code", $syntaxAlphaNumeric)->name('whatsapp.html');
					Route::get('/whatsapp/json', "FOSOMainController@offerWhatsAppAPI")->where("offer_code", $syntaxAlphaNumeric)->name('whatsapp.json');

					Route::get('/journey', "FOSOMainController@offerCustomerJourneyPage")->where("offer_code", $syntaxAlphaNumeric)->name('customerjourney.html');
					Route::get('/journey/report-csv', "FOSOMainController@offerJourneyReportCSVDownloadAPI")->where("offer_code", $syntaxAlphaNumeric)->name('customerjourney.report-csv.file');
					Route::get('/journey/csv', "FOSOMainController@offerJourneyCSVDownloadAPI")->where("offer_code", $syntaxAlphaNumeric)->name('customerjourney.csv.file');
					Route::post('/journey', "FOSOMainController@saveJourneyNodeAPI")->where("offer_code", $syntaxAlphaNumeric)->name('customerjourney.json');
					Route::post('/journey/clearwhitelisted', "FOSOMainController@offerJourneyClearWhiteListedRecordsAPI")->where("offer_code", $syntaxAlphaNumeric)->name('customerjourney.clearwhitelisted.json');
					Route::post('/journey/getnodes', "FOSOMainController@getJourneyNodesAPI")->where("offer_code", $syntaxAlphaNumeric)->name('customerjourney.getnodes.json');
					Route::post('/journey/upload', 'FOSOMainController@offerJourneyUploadAPI')->where("offer_code", $syntaxAlphaNumeric)->name('customerjourney.upload');
					Route::post('/journey/ordering', "FOSOMainController@offerJourneyUpdateOrderingAPI")->where("offer_code", $syntaxAlphaNumeric)->name('customerjourney.ordering.json');
					Route::post('/journey/archive', "FOSOMainController@offerJourneyArchiveAllRecordsAPI")->where("offer_code", $syntaxAlphaNumeric)->name('customerjourney.archive.json');
					Route::post('/journey/archive', "FOSOMainController@offerJourneyArchiveAllRecordsAPI")->where("offer_code", $syntaxAlphaNumeric)->name('customerjourney.archive.json');

					Route::post('/journey/quick-reply-template', 'FOSOMainController@getQuickReplyContentAPI')->where("offer_code", $syntaxAlphaNumeric)->name('customerjourney.quickreply.api');

					Route::get('/channel-sample', "FOSOMainController@offerChannelSapmlePage")->where("offer_code", $syntaxAlphaNumeric)->name('channel.sample.html');
					Route::post('/channel-sample', "FOSOMainController@saveofferChannelSapmlePage")->where("offer_code", $syntaxAlphaNumeric)->name('channel.save.json');

					Route::get('/ini', "FOSOMainController@offerINIPage")->where("offer_code", $syntaxAlphaNumeric)->name('ini.html');
					Route::post('/ini', "FOSOMainController@saveOfferINIAPI")->where("offer_code", $syntaxAlphaNumeric)->name('ini.json');

				});
			});

			//----------------------------------------------------------------------------------------
			//  FOSO: Campaigns - Manage section
			Route::prefix('/managetool')
				->name('managetool.')
				->middleware([
					"can:campaigns.manage-tool.access"
				])->group(function()  {

				Route::get('/', "FOSOMainController@manageToolPage")->name('html');
				Route::get('/search', "FOSOMainController@searchCouponAPI")->name('coupon.search.api');
			});

			//----------------------------------------------------------------------------------------
			//  FOSO: Campaigns - WhatsApp section
			Route::prefix('/whatsapp')
				->name('whatsapp.')
				->group(function ()  {

				Route::middleware([
					"can:campaigns.whatsapp-queue.read"
				])->group(function()  {
					Route::get('/', "FOSOMainController@whatsAppQueuePage");
					Route::get('/queue', "FOSOMainController@whatsAppQueuePage")->name('queue.html');
					Route::get('/json', "FOSOMainController@whatsAppQueueAPI")->name('queue.api');

					Route::post('/resend', "FOSOMainController@whatsAppQueueResendAPI")->name('queue.resend.api');
					Route::post('/cancel', "FOSOMainController@whatsAppQueueCancelAPI")->name('queue.cancel.api');
				});

				Route::middleware([
					"can:campaigns.whatsapp-inbound.read"
				])->group(function()  {
					Route::get('/inbound', "FOSOMainController@whatsAppInboundPage")->name('inbound.html');
					Route::get('/inbound/json', "FOSOMainController@whatsAppInboundAPI")->name('inbound.api');
				});
			});

			//----------------------------------------------------------------------------------------
			//  FOSO: Campaigns - Report section
			Route::prefix('/reports')
				->name('reports.')
				->middleware([
// 					"can:campaigns.coupon-reports.access"
				])->group(function()  {

				Route::get('/', "FOSOMainController@reportsAllCouponPage");
				Route::get('/all-coupons', "FOSOMainController@reportsAllCouponPage")->name('allcoupons.html');
			});
			// FOSO: Campaigns - Dashboard section
			Route::prefix('/dashboard')
				->name('dashboard.')
				->middleware([

				])->group(function(){
					Route::get('/',"FOSOMainController@dashboardPage")->name('index.html');
					Route::get('/offer.json',"FOSOMainController@getDashboardOfferDataApi");

					Route::get('/data.json',"FOSOMainController@getDashboardDataApi");
					$syntaxNumberOnly = "[0-9]";
					Route::prefix('/{offer_id}')
					->name('offer.')
					->middleware([

					])->group(function(){
						Route::get('/',"FOSOMainController@getDashboardDataByOfferIdApi");
						// Route::get('/',"FOSOMainController@checkDataCompletenessApi");
					});
			});

			//----------------------------------------------------------------------------------------
			//  FOSO: Campaigns - Landing section
			Route::prefix('/landing')
				->name('landing.')
				->group(function () use ($syntax)  {

				Route::get('/key-visuals', "FOSOMainController@keyVisualsPage")->name('keyvisuals.html');
				Route::get('/topics', "FOSOMainController@topicsPage")->name('topics.html');
				Route::get('/categories', "FOSOMainController@categoriesPage")->name('categories.html');
				Route::get('/banners', "FOSOMainController@bannersPage")->name('banners.html');
				Route::get('/hot-offers', "FOSOMainController@hotOffersPage")->name('hotoffers.html');

			});
		});


		//----------------------------------------------------------------------------------------
			//  FOSO: Redemption section ----2022.07.08 Kay
			Route::prefix('/redemption')
				->name('redemption.')
				->group(function () use ($syntax)  {

				Route::middleware([
					"role:Super-Administrator|Administrator"
				])->group(function()  {

					Route::get('/', "FOSOMainController@redemptionListPage")->name('html');
					Route::get('/json', "FOSOMainController@redemptionListAPI")->name('json'); //--foso.redemption.json

					Route::get('/settings', "FOSOMainController@redemptionSettingsPage")->name('settings.html');
					Route::post('/settings', "FOSOMainController@saveRedemptionSettingsAPI")->name('settings.json');
					Route::post('/settings/upload', 'FOSOMainController@redemptionResourcesUploadAPI')->name('resources.upload');
					Route::post('/settings/uploadcsv', 'FOSOMainController@redemptionCSVUploadAPI')->name('resources.upload.csv');

					Route::post('/settings/out-of-quota', "FOSOMainController@outOfRedemptionQuotaAPI")->name('outofredemptionquota.json');
					Route::post('/settings/resume-quota', "FOSOMainController@resumeRedemptionQuotaAPI")->name('resumeredemptionquota.json');
					
				});
			});
			
		//----------------------------------------------------------------------------------------
		//  FOSO Marketing pages
		Route::prefix('/marketing')
			->name('marketing.')
			->group(function () use ($syntax)  {

			Route::get('/', "FOSOMainController@marketingListPage")->name('list.html');
			Route::get('/search', "FOSOMainController@marketingListSearchAPI")->name('list.search.api');
			Route::get('/create', "FOSOMainController@marketingListCreatePage")->name('list.create.html');
			Route::post('/create', "FOSOMainController@marketingListCreateAPI")->name('list.create.api');
			Route::post('/upload', "FOSOMainController@marketingListUploadAPI")->name('list.upload.api');
			Route::post('/upload-check', "FOSOMainController@marketingListUploadCheckAPI")->name('list.upload.check.api');

			Route::get('/whatsapp/blast', "FOSOMainController@marketingWhatsAppBlastPage")->name('whatsapp.blast.html');
			Route::post('/whatsapp/blast', "FOSOMainController@marketingWhatsAppBlastAPI")->name('whatsapp.blast.api');
		});


		//----------------------------------------------------------------------------------------
		// FOSO Offer-hunting pages --- By Kay 2022.07.14
		Route::prefix('/offerhunting')
			->name('offerhunting.')
			->group(function () use ($syntax)  {

				Route::get('/', "FOSOMainController@offerHuntingListPage")->name('html');
				Route::get('/json', "FOSOMainController@offerHuntingListAPI")->name('json');

				Route::get('/settings', "FOSOMainController@offerHuntingSettingsPage")->name('settings.html');
				Route::post('/settings', "FOSOMainController@saveOfferHuntingSettingsAPI")->name('settings.json');
				Route::post('/settings/approve', "FOSOMainController@approveHuntingAPI")->name('settings.approve.json');
				Route::post('/settings/reject', "FOSOMainController@rejectHuntingAPI")->name('settings.reject.json');
				

		});

		//----------------------------------------------------------------------------------------
		//  FOSO Members pages
		Route::prefix('/members')
			->name('members.')
			->group(function () use ($syntax)  {

			Route::get('/', "FOSOMainController@membersSearchPage")->name('search.html');
			Route::get('/search', "FOSOMainController@membersSearchAPI")->name('search.api');

			Route::get('/refreshpoint', "FOSOToolsController@refreshPointAPI")->name('refreshpoint.api');
			Route::post('/adjustpoint', "FOSOToolsController@handleAjustmentPointAPI")->name('pointadjust.api');
			Route::post('/point-csv-uploads', "FOSOToolsController@adjustmentPointCSVuplodsAPI")->name('pointuploads.api');
			
			$syntaxNumberOnly = "[0-9]";
			Route::prefix('/{mobile}')
				->name('detail.')
				->group(function () use ($syntaxNumberOnly)  {

					Route::get('/', "FOSOMainController@membersDetailPage")->name('html');
					Route::post('/update', "FOSOMainController@membersDetailUpdateAPI")->name('update.api');
					Route::post('/unmute', "FOSOMainController@membersUnmuteAPI")->name('unmute.api');
					Route::post('/optin', "FOSOMainController@membersOptInAPI")->name('optin.api');

					Route::get('/transactions', "FOSOMainController@membersTransactionsAPI")->name('transaction.api');
				}
			);
		});

		//----------------------------------------------------------------------------------------
		//  FOSO Daily Question pages
		Route::prefix('/daily-question')
			->name('dailyquestion.')
			->group(function () use ($syntax)  {

			Route::get('/', "FOSODailyQuestionController@listPage")->name('list.html');
			Route::get('/json', "FOSODailyQuestionController@listAPI")->name('list.api');
			Route::post('/csv/upload', 'FOSODailyQuestionController@questionListUploadAPI')->name('question.upload');

			$syntaxNumeric = "[0-9]+";
			Route::prefix('/{question_id}')
				->group(function () use ($syntaxNumeric)  {

				Route::get('/', "FOSODailyQuestionController@questionPage")->where("question_id", $syntaxNumeric)->name('details.html');
				Route::post('/', "FOSODailyQuestionController@questionAPI")->where("question_id", $syntaxNumeric)->name('details.api');

			});

			Route::get('/report', "FOSODailyQuestionController@reportPage")->name('report.html');
			Route::get('/report/json', "FOSODailyQuestionController@reportAPI")->name('report.api');

		});

		//----------------------------------------------------------------------------------------
		//  FOSO Campaign Banner pages
		Route::prefix('/banner')
		->name('banner.')
		->middleware([

		])->group(function(){
			Route::get('/', "FOSOMainController@bannerlistPage")->name('bannerlist.html');
			Route::get('/json', "FOSOMainController@bannerlistAPI")->name('bannerlist.json');

			Route::get('/settings', "FOSOMainController@bannerSettingsPage")->name('settings.html');
			Route::post('/settings', "FOSOMainController@saveBannerSettingsAPI")->name('settings.json');
			Route::post('/settings/upload', 'FOSOMainController@bannerResourcesUploadAPI')->name('resources.upload');

			Route::post('/settings/stop-launch', "FOSOMainController@stopBannerLaunch")->name('stop.json');
			Route::post('/settings/resume-launch', "FOSOMainController@resumeBannerLaunch")->name('resume.json');
			
		});

		//----------------------------------------------------------------------------------------
		// FOSO receipt-upload pages --- By Kay 2022.11.21
		Route::prefix('/receipthandle')
			->name('receipthandle.')
			->group(function () use ($syntax)  {

				Route::get('/', "FOSOToolsController@receiptHandleListPage")->name('html');
				Route::get('/json', "FOSOToolsController@receiptHandleListAPI")->name('json');

				Route::get('/settings', "FOSOToolsController@receiptSettingsPage")->name('settings.html');

				// save the receipt upload case
				Route::post('/settings/save', "FOSOToolsController@saveReceiptAPI")->name('settings.save.json');
				// save to handle (approve or reject)
				Route::post('/settings/handle', "FOSOToolsController@handleReceiptAPI")->name('settings.savetohandle.json');
				
				// confirm page
				Route::get('/settings/confirm', "FOSOToolsController@comfirmSettingsReceiptPage")->name('settings.confirm.html');

				Route::post('/settings/edit', "FOSOToolsController@reeditReceiptAPI")->name('settings.reedit.json');
				Route::post('/settings/final', "FOSOToolsController@finalStatusReceiptAPI")->name('settings.final.json');
		});

		//----------------------------------------------------------------------------------------
		// FOSO Channel sapmle pages --- By Kay 2022.11.21
		Route::prefix('/channel')
			->name('channel.')
			->group(function () use ($syntax)  {

				Route::get('/', "FOSOToolsController@channelSampleListPage")->name('html');
				Route::get('/json', "FOSOToolsController@channelSampleListAPI")->name('json');

				Route::get('/settings', "FOSOToolsController@channelSampleSettingsPage")->name('settings.html');
				Route::post('/settings', "FOSOToolsController@saveReceiptSampleSettingsAPI")->name('settings.json');
				Route::post('/settings/upload', 'FOSOToolsController@receiptSampleUploadAPI')->name('receiptsample.upload');
				// Route::post('/settings', "FOSOToolsController@channelSampleSettingsAPI")->name('settings.json');
				// Route::post('/settings/approve', "FOSOToolsController@approveReceiptAPI")->name('settings.approve.json');
				// Route::post('/settings/reject', "FOSOToolsController@rejectReceiptAPI")->name('settings.reject.json');
		});


		//----------------------------------------------------------------------------------------
		//  Mobile app related
		Route::prefix('/app')
		->name('app.')
		->middleware([

		])->group(function(){
			Route::get('/user',"FOSOMainController@appUserPage")->name('user.html');
			Route::get('/user.api', "FOSOMainController@appUserApi")->name('user.json');

			Route::get('/user/create', "FOSOMainController@createAppUserPage")->name('createuser.html');
			Route::post('/user/create',"FOSOMainController@createAppUserApi")->name('createuser.json');

			Route::get('/user/{id}',"FOSOMainController@appUserDetailPage")->name('userdetail.html');
			Route::post('/user/{id}', "FOSOMainController@appUserDetailUpdateApi")->name('userdetailupdate.json');
			Route::post('/user/{id}/password', "FOSOMainController@appUserChangePassword")->name('changeuserpassword.json');
			Route::post('/user/{id}/delete', "FOSOMainController@appUserDelete")->name('deleteuser.json');

			Route::get('/scan-log',"FOSOMainController@scanLogPage")->name('scanlog.html');
			// Route::Resources([
			// 	'app-user' => \Rest\AppUserController::class,
			// 	// 'scan-log' => \Rest\
			// ]);
		});

		//----------------------------------------------------------------------------------------
		//  third party related
		Route::prefix('/thirdparty')
			->name('thirdparty.')
			->middleware([

		])->group(function(){
			Route::get('/eventlist',"FOSOMainController@thirdPartyEventPage")->name('eventlist.html');
			Route::get('/eventlist/uaf-imoney',"FOSOMainController@uaformCSVDownloadAPI")->name('uafimoney.csv.file');

		});

		//  FOSO: Reporting section ----2022.07.08 Kay
		Route::prefix('/reporting')
			->name('reporting.')
			->middleware([

		])->group(function(){

			Route::get('/point', "FOSOToolsController@reportPointPage")->name('point.html');
			Route::get('/point/json', "FOSOToolsController@reportPointListAPI")->name('point.api'); 

			// Route::get('/settings', "FOSOMainController@redemptionSettingsPage")->name('settings.html');
			// Route::post('/settings', "FOSOMainController@saveRedemptionSettingsAPI")->name('settings.json');
			// Route::post('/settings/upload', 'FOSOMainController@redemptionResourcesUploadAPI')->name('resources.upload');
			// Route::post('/settings/uploadcsv', 'FOSOMainController@redemptionCSVUploadAPI')->name('resources.upload.csv');

			// Route::post('/settings/out-of-quota', "FOSOMainController@outOfRedemptionQuotaAPI")->name('outofredemptionquota.json');
			// Route::post('/settings/resume-quota', "FOSOMainController@resumeRedemptionQuotaAPI")->name('resumeredemptionquota.json');

		});

		//----------------------------------------------------------------------------------------
		//  Show activity log
		Route::prefix('/activitylog')
			->name('activitylog.')
			->middleware([

		])->group(function(){
			Route::get('/list',"FOSOToolsController@activityLogListPage")->name('list.html');
			Route::get('/list/json',"FOSOToolsController@activityLogListAPI")->name('list.json');
		});


		//----------------------------------------------------------------------------------------
		//  Change password page
		Route::middleware([])
			->prefix('/changePassword')->group(function()  {
			Route::get('/', 'Auth\ChangePasswordController@showChangePasswordForm')->name('changePasswordForm');
			Route::post('/', 'Auth\ChangePasswordController@changePassword')->name('changePassword');
		});

		Route::middleware([
			"role:Super-Administrator|Administrator"
		])->group(function(){
			Route::resource('roles', 'Auth\AccessControl\RolesController');
			Route::resource('permissions', 'Auth\AccessControl\PermissionController');
			Route::resource('users', 'UserController');
		});
	});
});


//----------------------------------------------------------------------------------------
//  CSV download link for client
Route::group([
	"prefix" => "/csv-download",
	"middleware" => ["auth.basic"],
], function()  {

	Route::get("/", "FOSOMainController@clientCSVDownload");
});


//----------------------------------------------------------------------------------------
//  Campaign Related Routes
//----------------------------------------------------------------------------------------

Route::get("/sitemap.xml", "CampaignOfferController@sitemapPage");

//  QR code generator
Route::get("/qrcode", "CampaignCouponController@qrcodeAPI")->name("website.qrcode.image");

//  Barcode generator
Route::get('/barcode', "WebsiteController@barcodeGenerator")->name("website.barcode.image");

//----------------------------------------------------------------------------------------
//  Password protection for non-production site
// Route::middleware("uat_guard")->group(function() use ($syntax)  {
Route::group([], function() use ($syntax)  {

	//----------------------------------------------------------------------------------------
	//  App Install Pages
	Route::get("/app-install", "WebsiteController@appInstallPage")->name("app.install.html");

	Route::get("/coming-soon", "WebsiteController@comingSoonPage")->name("website.comingsoon.html");
	Route::get("/about-us", "WebsiteController@aboutUsPage")->name("website.aboutus.html");
	Route::get("/offer-hunting", "WebsiteController@offerHuntingPage")->name("website.offerhunting.html");
	Route::post("/offer-hunting", "WebsiteController@storeOfferHuntingPage")->name('website.store.offerhunting.html');
	Route::get("/offer-hunting/success", "WebsiteController@offerHuntingSuccessPage")->name("website.offerhunting.success.html");
	Route::get("/terms-and-conditions", "WebsiteController@termsAndConditionsPage")->name("website.termsandconditions.html");
	Route::get("/privacy", "WebsiteController@privacyPage")->name("website.privacy.html");
	Route::get('/partnership', 'WebsiteController@partnershipPage')->name('website.partnership.html');
	Route::get("/uaf_imoney_redemptionform", "WebsiteController@uaformPage")->name("website.uaform.html");
	Route::post("/uaf_imoney_redemptionform", "WebsiteController@storeUAformPage")->name('website.store.uaform.html');
	Route::get("/uaf_imoney_redemptionform/success", "WebsiteController@uaformSuccessPage")->name("website.uaform.success.html");

	Route::get("/shopline_enquiry", "WebsiteController@shoplineEnquiryPage")->name("website.shoplineenquiry.html");


	//  2022.06.06 Pacess
	//  Add new menu items
	Route::get('/kinnso-points', 'WebsiteController@kinnsoPointsPage')->name('website.kinnsopoints.html');
	Route::get('/my-rewards', 'WebsiteController@myRewardsPage')->name('website.myrewards.html');
	Route::get('/redemption', 'WebsiteController@redemptionPage')->name('website.redemption.html');
	//  2022.06.06 End

	Route::post('/redemption', 'WebsiteController@redeem')->name('website.redemption.json');
	Route::post('/my-rewards/detail', 'WebsiteController@myRewardDetailAPI')->name('website.myrewards.detail.json');

	//--------------Points History, redemption History  2022.06.17 Kay ------------------------------------------
	Route::get('/point-history', 'WebsiteController@PointsHistoryPage')->name('website.point_history.html');
	Route::get('/my-rewards/{listtype}', 'WebsiteController@redemptionHistoryRefresh')->name('website.myrewards.json');

	//--------------Offer Receipt 2022.10.19 Kay-------------------------------------------------------------
	Route::get('/receipt-login', 'LoginController@receiptLoginPage')->name('receipt.login.html');
	Route::get('/receipt-upload', 'WebsiteController@receiptUploadPage')->name('website.receiptupload.html');
	Route::get('/receipt-record/{offerid}', 'WebsiteController@offerChannelRecordApi')->name('website.offerchannel.api');
	Route::post('/receipt-image-upload', 'WebsiteController@receiptUploadApi')->name('website.receiptupload.api');
	Route::post('/receipt-submit', 'WebsiteController@saveReceiptPage')->name('website.savereceipt.api');
	Route::get('/record-listing', 'WebsiteController@receiptRecordListApi')->name('website.receiptreocrd.list');
	Route::get('/record-display/{receiptid}', 'WebsiteController@receiptRecordDisplayApi')->name('website.receiptdisplay.api');

	//----------------------------------------------------------------------------------------
	//  Offer Pages
 	Route::get("/", "CampaignOfferController@listingPage")->name("campaign.offer.listing.html");
	Route::get("/filter", "CampaignOfferController@filterPage")->name("campaign.offer.filter.html");

	Route::post("/getOfferList", "CampaignOfferController@listingPageApi")->name("campaign.offer.listing.json");
 	Route::get("/landing/json", "CampaignOfferController@landingPageAPI")->name("campaign.offer.landing.json");
 	Route::get("/filter/json", "CampaignOfferController@filterPageAPI")->name("campaign.offer.filter.json");

	Route::group([
		"prefix" => "/offer",
	], function() use ($syntax)  {

		//  Redirect '/offer' to '/'
		Route::redirect('/', '/');

		Route::group([
			"prefix" => "/{offer_code}",
			"middleware" => ["campaign.offer.exists"],
		], function() use ($syntax)  {

			//  Offer is exists, that means it can be either
			//  1. Not started yet
			//  2. Currently running
			//  3. Expired
			Route::group([
				"middleware" => ["campaign.offer.valid"],
			], function() use ($syntax)  {

				Route::get("/", "CampaignOfferController@offerDetailsPage")->where("offer_code", $syntax)->name("campaign.offer.details.html");
				Route::get("/thank-you", "CampaignOfferController@thankYouPage")->where("offer_code", $syntax)->name("campaign.offer.thankyou.html");
			});

			Route::get("/coming-soon", "CampaignOfferController@comingSoonPage")->where("offer_code", $syntax)->name("campaign.offer.comingsoon.html");
			Route::get("/expired", "CampaignOfferController@expiredPage")->where("offer_code", $syntax)->name("campaign.offer.expired.html");
			Route::get("/out-of-quota", "CampaignOfferController@outOfQuotaPage")->where("offer_code", $syntax)->name("campaign.offer.outofquota.html");

			Route::get("/coupon-link", "CampaignOfferController@getCouponLinkPage")->where("offer_code", $syntax)->name("campaign.offer.coupon.link.html");
			Route::post("/coupon-link", "CampaignOfferController@getCouponLinkAPI")->where("offer_code", $syntax)->name("campaign.offer.coupon.link.json");

			//----------------------------------------------------------------------------------------
			//  APIs
			Route::get("/timeslot", "CampaignOfferController@timeSlotAPI")->where("offer_code", $syntax)->name("campaign.offer.timeslot.json");
			Route::post("/register", "CampaignOfferController@registerAPI")->where("offer_code", $syntax)->name("campaign.offer.register.json");

			Route::post("/submit-form", "CampaignOfferController@submitFormAPI")->where("offer_code", $syntax)->name("campaign.offer.submitform.json");

			//  Exchange AID to a unique code
			Route::post("/aid", "CampaignOfferController@aidExchangeAPI")->where("offer_code", $syntax)->name("campaign.offer.aidexchange.json");

			Route::get("/recommends", "CampaignOfferController@recommendAPI")->where("offer_code", $syntax)->name("campaign.offer.recommend.json");
		});
	});

	//----------------------------------------------------------------------------------------
	//  Coupon Pages
	Route::group([
		"prefix" => "/{unique_code}",
		"middleware" => ["campaign.coupon.exists"],
	], function() use ($syntax)  {

		//  Unique code code is exists, that means it can be either
		//  1. Not started yet
		//  2. Currently running
		//  3. Expired
		Route::group([
			"middleware" => ["campaign.coupon.valid"],
		], function() use ($syntax)  {

			//  Uinque code is not used yet
			Route::get("/", "CampaignCouponController@landingPage")->where("unique_code", $syntax)->name("campaign.coupon.landing.html");
			Route::get("/countdown", "CampaignCouponController@countdownPage")->where("unique_code", $syntax)->name("campaign.coupon.countdown.html");

			//----------------------------------------------------------------------------------------
			//  APIs
			Route::post("/start", "CampaignCouponController@startCountDownAPI")->where("unique_code", $syntax)->name("campaign.coupon.start.json");
			Route::post("/void", "CampaignCouponController@voidCouponAPI")->where("unique_code", $syntax)->name("campaign.coupon.void.json");
		});

		Route::get("/coming-soon", "CampaignCouponController@comingSoonPage")->where("unique_code", $syntax)->name("campaign.coupon.comingsoon.html");
		Route::get("/expired", "CampaignCouponController@expiredPage")->where("unique_code", $syntax)->name("campaign.coupon.expired.html");
		Route::get("/thank-you", "CampaignCouponController@thankYouPage")->where("unique_code", $syntax)->name("campaign.coupon.thankyou.html");

		Route::get("/app", "CampaignCouponController@appPage")->where("offer_code", $syntax)->name("campaign.coupon.app.html");
		Route::post("/app", "CampaignCouponController@appAPI")->where("offer_code", $syntax)->name("campaign.coupon.app.json");
	});

	Route::get("/{unique_code}/invalid", "CampaignCouponController@invalidPage")->where("unique_code", $syntax)->name("campaign.coupon.invalid.html");

	//----------------------------------------------------------------------------------------
	//  WhatsApp Webhook
	Route::group([
		"prefix" => "/whatsapp",
	], function()  {

		Route::group([
			"prefix" => "/twilio",
		], function()  {

			//  Below is default offer handling in WhatsApp
			Route::post("/message", "WhatsAppController@webhookTwilioMessageComesInAPI")->name("campaign.whatsapp.webhook.message.comes.in.json");
			Route::post("/status", "WhatsAppController@webhookTwilioStatusCallbackAPI")->name("campaign.whatsapp.webhook.status.callback.json");
			Route::post("/fallback", "WhatsAppController@webhookTwilioFallbackAPI")->name("campaign.whatsapp.webhook.fallback.json");
		});

		Route::get("/process-queue", "WhatsAppController@processQueueAPI")->name("campaign.whatsapp.processqueue.json");
	});

});

//----------------------------------------------------------------------------------------
//  User login related routes
require __DIR__.'/web_login.php';

// Auth::routes();
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

