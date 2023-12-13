@extends('foso.layouts.default')

@section('page_title', 'Campaign Offer '.$offerIDText.' Settings')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.campaigns.html") }}'>Campaigns</a></li>
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.campaigns.offer.html") }}'>Offer</a></li>
<li><i class='fa fa-angle-right'></i> Settings</li>
@endsection

@section('content')

<ul class="nav nav-tabs" role="tablist">
	<li class="nav-item"> <a class="nav-link active" href="#" role="tab"><span class="hidden-sm-up"><i class="fa fa-gear"></i></span> <span class="hidden-xs-down">Settings</span></a> </li>
@if (empty($offerCode) || $offerCode == "new")
@else
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.resources.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-file-image-o"></i></span> <span class="hidden-xs-down">Resources</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.rules.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-cubes"></i></span> <span class="hidden-xs-down">Rules</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.coupons.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-tags"></i></span> <span class="hidden-xs-down">Coupons</span></a> </li>
@if ($offer->coupon_type == "randomly-generated")
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.quotas.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-shopping-cart"></i></span> <span class="hidden-xs-down">Quotas</span></a> </li>
@else
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.couponpool.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-shopping-cart"></i></span> <span class="hidden-xs-down">Coupon Pool</span></a> </li>
@endif
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.whatsapp.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-comment"></i></span> <span class="hidden-xs-down">WhatsApp</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.customerjourney.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-plane"></i></span> <span class="hidden-xs-down">Journey</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.channel.sample.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-tablet"></i></span> <span class="hidden-xs-down">Channel</span></a> </li>	
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.ini.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-file-text-o"></i></span> <span class="hidden-xs-down">.ini</span></a> </li>
@endif
</ul>

<form id="form" name="form">
	@csrf

	<div class="card" style="margin-bottom:20px;">
		<div class="card-body">

			<div class="row">
				<div class="col-lg-9">
					<fieldset class="form-group">
						<label for="offerURL">Offer {{ $offerIDText }} URL</label>
						<p><a href="{{ $offerURL }}" id="offerURL" target="_blank">{{ $offerURL }}</a></p>
					</fieldset>
				</div>
				<div class="col-lg-3" id="">
					<div class="text-white btn btn-danger btn-block" id="clearWhiteList">Clear White List Record</div>
					<small class="form-text small-text-color">Whitelist could be found in .ini</small>
					<small class="form-text small-text-color">Both coupon and journey</small>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<fieldset class="form-group">
						<label for="couponRequestURL">Coupon Request URL</label>
						<p><a href="{{ $offerURL }}/coupon-link/" id="couponRequestURL" target="_blank">{{ $offerURL }}/coupon-link/</a></p>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-9">
					<fieldset class="form-group">
						<label for="triggerURL">Trigger URL</label>
						<p><a href="{{ $triggerURL }}" id="triggerURL" target="_blank">{{ $triggerURL }}</a></p>
					</fieldset>
				</div>
@if ($checkExist)
				<div class="col-lg-3" id="">
                    <div class="text-white btn btn-primary btn-block" id="zipoffer">Export the offer</div>
                    <small class="form-text small-text-color">All files in zip file</small>
                    <small class="form-text small-text-color">Click to download</small>
                </div>
@endif
			</div>
		</div>
	</div>

	<div class="card" style="margin-bottom:20px;">
		<div class="card-body">

			<div class="row">
				<div class="col-sm-12"><h4>Basic Offer Settings</h4></div>
			</div>

			<div class="row">
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="createdAt">Create Time</label>
						<input class="form-control" type="text" value="{{ empty($offer->created_at)?'':$offer->created_at }}" id="createdAt" name="createdAt" disabled>
					</fieldset>
				</div>
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="updatedAt">Update Time</label>
						<input class="form-control" type="text" value="{{ empty($offer->updated_at)?'':$offer->updated_at }}" id="updatedAt" name="updatedAt" disabled>
					</fieldset>
				</div>
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="deletedAt">Delete Time</label>
						<input class="form-control" type="text" value="{{ empty($offer->deleted_at)?'':$offer->deleted_at }}" id="deletedAt" name="deletedAt" disabled>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-3">
					<fieldset class="form-group">
						<label for="startDate">Start Date</label>
						<input class="form-control" type="date" value="{{ $startDate }}" id="startDate" name="startDate" required>
						<small class="form-text small-text-color">Offer available date</small>
					</fieldset>
				</div>
				<div class="col-lg-3">
					<fieldset class="form-group">
						<label for="startTime">Start Time</label>
						<input class="form-control" type="time" value="{{ $startTime }}" id="startTime" name="startTime" required>
						<small class="form-text small-text-color">Offer available time</small>
					</fieldset>
				</div>

				<div class="col-lg-3">
					<fieldset class="form-group">
						<label for="endDate">End Date</label>
						<input class="form-control" type="date" value="{{ $endDate }}" id="endDate" name="endDate" required>
						<small class="form-text small-text-color">Offer expiry date</small>
					</fieldset>
				</div>
				<div class="col-lg-3">
					<fieldset class="form-group">
						<label for="endTime">End Time</label>
						<input class="form-control" type="time" value="{{ $endTime }}" id="endTime" name="endTime" required>
						<small class="form-text small-text-color">Offer expiry time</small>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-5">
					<fieldset class="form-group">
						<label for="offerCode">Offer Code</label>
@if (empty($offerCode) || $offerCode == "new")
						<input class="form-control" type="text" value="{{ $randomCode }}" id="offerCode" name="offerCode" maxlength="32" required>
@else
						<input class="form-control" type="text" value="{{ $offerCode }}" id="offerCode" name="offerCode" maxlength="32" required>
@endif
						<small class="form-text small-text-color">Must be unique, used in offer URL, can be changed</small>
						<small class="form-text small-text-color">Only letters, numbers, hyphen is allowed</small>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-6">
					<fieldset class="form-group">
						<label for="offerName">Offer Media Folder Name</label>
@if (empty($offerCode) || $offerCode == "new")
						<input class="form-control" type="text" value="{{ empty($offer->offer_name)?'':$offer->offer_name }}" id="offerName" name="offerName" placeholder="special-offer" maxlength="32" required>
@else
						<input class="form-control" type="text" value="{{ empty($offer->offer_name)?'':$offer->offer_name }}" id="offerName" name="offerName" placeholder="special-offer" maxlength="32" required disabled>
@endif
						<small class="form-text small-text-color">Once created, <font color='red'><u>it cannot be changed</u></font> in CMS, have to ask developer</small>
						<small class="form-text small-text-color">Media folder name is for internal use.  Small letters, no space</small>
						<small class="form-text small-text-color">Only letters, numbers, hyphen and underscore is allowed</small>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<fieldset class="form-group">
						<label for="offerTitle">Offer Title</label>
						<input class="form-control" type="text" value="{{ empty($offer->offer_title)?'':$offer->offer_title }}" id="offerTitle" name="offerTitle" placeholder="全新蠟菊雙效眼部精華" maxlength="63" required>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<fieldset class="form-group">
						<label for="offerSubtitle">Offer Subtitle</label>
						<input class="form-control" type="text" value="{{ empty($offer->offer_subtitle)?'':$offer->offer_subtitle }}" id="offerSubtitle" name="offerSubtitle" placeholder="體驗套裝" maxlength="63">
					</fieldset>
				</div>
			</div>

			<div class="row" style="padding-bottom:15px;">
				<div class="col-lg-12">
					<fieldset class="form-group">
						<label for="bladeFolder">Template Folder</label>
						<select class="form-control" id="bladeFolder" name="bladeFolder">

							<!--  Generation-5 templates  2022 Dec-->
							<option value="60_product_sampling_venue" {{ ($bladeFolder=="60_product_sampling_venue")?"selected":"" }}>60. Product Sampling, venue only</option>
							<option value="61_product_sampling_venue_date" {{ ($bladeFolder=="61_product_sampling_venue_date")?"selected":"" }}>61. Product Sampling, venue and date</option>
							<option value="62_product_sampling_venue_date_timeslot" {{ ($bladeFolder=="62_product_sampling_venue_date_timeslot")?"selected":"" }}>62. Product Sampling, venue, date and time</option>

							<!--  Generation-4 templates  -->
							<option value="50_information_page" {{ ($bladeFolder=="50_information_page")?"selected":"" }}>50. Information page</option>

							<!--  Generation-3 templates  -->
							<option value="40_lazy_pack" {{ ($bladeFolder=="40_lazy_pack")?"selected":"" }}>40. Lazy Pack without social, no coupon site</option>
							<option value="41_lazy_pack_fb" {{ ($bladeFolder=="41_lazy_pack_fb")?"selected":"" }}>41. Lazy Pack with Facebook, no coupon site</option>
							<option value="42_lazy_pack_ig" {{ ($bladeFolder=="42_lazy_pack_ig")?"selected":"" }}>42. Lazy Pack with Instagram, no coupon site</option>
							<option value="43_lazy_pack_fb_ig" {{ ($bladeFolder=="43_lazy_pack_fb_ig")?"selected":"" }}>43. Lazy Pack with Facebook &amp; Instagram, no coupon site</option>
							<option value="44_lazy_pack_ig_couponsite" {{ ($bladeFolder=="44_lazy_pack_ig_couponsite")?"selected":"" }}>44. Lazy Pack with Instagram and coupon site</option>

							<!--  To-be-faded-out  -->

							<!--  Generation-2 templates  -->
							<option value="10_chatbot_coupon" {{ ($bladeFolder=="10_chatbot_coupon")?"selected":"" }}>10. Chatbot Coupon &gt; Slidebar (Depercated, for backward compatible)</option>
							<option value="15_chatbot_coupon_storecode" {{ ($bladeFolder=="15_chatbot_coupon_storecode")?"selected":"" }}>15. Chatbot Coupon &gt; Storecode (Depercated, for backward compatible)</option>
							<option value="20_chatbot_coupon_survey" {{ ($bladeFolder=="20_chatbot_coupon_survey")?"selected":"" }}>20. Chatbot Coupon + Survey &gt; Slidebar (Depercated, for backward compatible)</option>
							<option value="30_chatbot_coupon_referral_reward" {{ ($bladeFolder=="30_chatbot_coupon_referral_reward")?"selected":"" }}>30. Chatbot Coupon + Referral Reward &gt; Slidebar (Depercated, for backward compatible)</option>

							<!--  Generation-1 templates  -->
<!--
							<option value="kinnso_01_coupon_image" {{ ($bladeFolder=="kinnso_01_coupon_image")?"selected":"" }}>Kinnso #1 Coupon Image</option>
							<option value="kinnso_02_coupon_code" {{ ($bladeFolder=="kinnso_02_coupon_code")?"selected":"" }}>Kinnso #2 Coupon Code</option>
							<option value="kinnso_03_product_sampling" {{ ($bladeFolder=="kinnso_03_product_sampling")?"selected":"" }}>Kinnso #3 Product Sampling</option>
							<option value="kinnso_04_live_streaming_link" {{ ($bladeFolder=="kinnso_04_live_streaming_link")?"selected":"" }}>Kinnso #4 Live Streaming Link</option>
							<option value="kinnso_05_ccba_payment" {{ ($bladeFolder=="kinnso_05_ccba_payment")?"selected":"" }}>Kinnso #5 CCBA Payment</option>
							<option value="kinnso_06_ecommerce_payment" {{ ($bladeFolder=="kinnso_06_ecommerce_payment")?"selected":"" }}>Kinnso #6 eCommerce Payment</option>
 -->
						</select>
						<small class="form-text small-text-color">Means preset offer template, controlling offer and coupon layout</small>
						<small class="form-text small-text-color">Default journey will be set when creating new offer only</small>
					</fieldset>
				</div>
				<div class="col-lg-4">
					<small>Offer Landing Layout</small>
					<img class="img-responsive" id="bladeFolderLayoutOffer" alt="Offer landing page">
				</div>
				<div class="col-lg-4">
					<small>WhatsApp Layout</small>
					<img class="img-responsive" id="bladeFolderLayoutWhatsapp" alt="Whatsapp journey">
				</div>
				<div class="col-lg-4">
					<small>Coupon Countdown Layout</small>
					<img class="img-responsive" id="bladeFolderLayoutCouponCountdown" alt="Coupon countdown page">
				</div>
			</div>

			<div class="row" style="display:none;">
				<div class="col-lg-6">
					<fieldset class="form-group">
						<label for="brandName">Brand Name</label>
						<select class="form-control" name="brandName">

							<option value="kinnso" {{ isset($offer->ini['settings']['brand_name'])&&($offer->ini['settings']['brand_name']=="kinnso")?"selected":"" }}>Kinnso</option>

						</select>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-6">
					<fieldset class="form-group">
						<label for="couponType">Offer Coupon Type</label>
						<select class="form-control" id="couponType" name="couponType">
							<option value="randomly-generated" {{ isset($offer->coupon_type)&&($offer->coupon_type=="randomly-generated")?"selected":"" }}>Randomly generated</option>
							<option value="pre-generated" {{ isset($offer->coupon_type)&&($offer->coupon_type=="pre-generated")?"selected":"" }}>Pre-generated</option>
						</select>
						<small class="form-text small-text-color">Randomly-generated: generate upon new user registration</small>
						<small class="form-text small-text-color">Pre-generated: client-provided, retrieve coupon code from campaign_coupon_pool for each new user</small>
					</fieldset>
				</div>
				<div class="col-lg-6">
					<fieldset class="form-group">
						<label for="offerTags">Offer Tags</label>
						<input class="form-control" type="text" value="{{ empty($offer->tag)?'':$offer->tag }}" id="offerTags" name="offerTags" placeholder="new, hot, push, less" maxlength="32">
						<small class="form-text small-text-color">
							new = <img width="30px" src="{{ asset('website/offer-listing/tag_01.png') }}" > /
							hot = <img width="30px" src="{{ asset('website/offer-listing/tag_02.png') }}" > /
							push = <img width="30px" src="{{ asset('website/offer-listing/tag_03.png') }}" > /
							less = <img width="30px" src="{{ asset('website/offer-listing/tag_04.png') }}" ></small>
						<small class="form-text small-text-color">Comma separated, ex.: new, hot</small>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="quota">Quota</label>
						<input class="form-control" type="text" value="{{ empty($offer->quota)?'0':$offer->quota }}" id="quota" name="quota" disabled>
						<small class="form-text small-text-color">Value base on quota settings or coupon pool</small>
					</fieldset>
				</div>
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="quotaIssued">Quota Issued</label>
						<input class="form-control" type="text" value="{{ empty($offer->quota_issued)?'0':$offer->quota_issued }}" id="quotaIssued" name="quotaIssued" disabled>
					</fieldset>
				</div>
				<div class="col-lg-4" id="outOfQuotaButtonGroup" style="display:none;">
					<fieldset class="form-group">
						<label for="quotaIssued">Quota Action</label>
						<div class="text-white btn btn-danger btn-block" id="outOfQuotaButton">Set "Out of Quota"</div>
						<small class="form-text small-text-color">Set max quota = quota, no revert</small>
						<small class="form-text small-text-color">Please hold until finish</small>
					</fieldset>
				</div>
				<div class="col-lg-4" id="resumeQuotaButtonGroup" style="display:none;">
					<fieldset class="form-group">
						<label for="quotaIssued">Quota Action</label>
						<div class="text-white btn btn-warning btn-block" id="resumeQuotaButton">Resume Quota</div>
						<small class="form-text small-text-color">Reset to max quota</small>
						<small class="form-text small-text-color">Please hold until finish</small>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="bundledOffersID">Bundled Offers ID</label>
						<input class="form-control" type="text" value="{{ empty($offer->bundled_offers_id)?'':$offer->bundled_offers_id }}" id="bundledOffersID" name="bundledOffersID">
						<small class="form-text small-text-color">A coupon of these offers will be given to user</small>
						<small class="form-text small-text-color">Optional, comma separated, ex.: 1,2,3</small>
					</fieldset>
				</div>
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="likeCounter">Like Counter</label>
						<input class="form-control" type="text" value="{{ empty($offer->likeCounter)? 0:$offer->likeCounter }}" id="likeCounter" name="likeCounter">
					</fieldset>
				</div>
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="viewCounter">View Counter</label>
						<input class="form-control" type="text" value="{{ isset($statisticData['open'])? $statisticData['open']:0 }}" id="viewCounter" name="viewCounter">
					</fieldset>
				</div>
			</div>

		</div>
	</div>

	<!--  Detailed Offer Settings  -->
	<div class="card" style="margin-bottom:20px;">
		<div class="card-body">

			<div class="row">
				<div class="col-sm-12"><h4>Detailed Offer Settings</h4></div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<fieldset class="form-group">
						<label for="description">Offer Description</label>
						<textarea class="form-control" id="description" name="description" rows=10>{{ empty($description)?'':nl2br($description) }}</textarea>
						<small class="form-text small-text-color">Support line break and HTML tags.  If you want clickable link, please use <u>&lt;a href="url" target="_blank"&gt;url&lt;/a&gt;</u></small>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<fieldset class="form-group">
						<label for="tnc">Terms and Conditions</label>
						<textarea class="form-control" id="tnc" name="tnc" rows=10>{{ empty($offer->tnc)?'':$offer->tnc }}</textarea>
						<small class="form-text small-text-color">For offer website only.  If chatbot, please use journey and setup a message node</small>
						<small class="form-text small-text-color">One line one bullet point</small>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<fieldset class="form-group">
						<label for="sharingMessage">Sharing Message</label>
						<textarea class="form-control" id="sharingMessage" name="sharingMessage" rows=2>{{ empty($offer->ini['settings']['sharing_message'])?'':nl2br($offer->ini['settings']['sharing_message']) }}</textarea>
						<small class="form-text small-text-color">This is OG description, better within 140 characters</small>
					</fieldset>
				</div>
			</div>

			<!--  WhatsApp  -->
<!--
			<div class="row">
				<div class="col-lg-12">
					<fieldset class="form-group">
						<label for="whatsappMessageSettings">WhatsApp Message Settings</label>
						<br><div class="btn-group" role="group" id="whatsappMessageSettings">
							<button type="button" class="btn btn-primary" id="journeyButton">Journey</button>
							<button type="button" class="btn btn-outline-primary" id="embeddedButton">Embedded</button>
						</div>
					</fieldset>
				</div>
			</div>
 -->

			<div id="journeySection">
				<div class="row">
					<div class="col-lg-12">
						<fieldset class="form-group">
							<label for="whatsappTriggerMessage">WhatsApp Trigger Message</label>
							<textarea class="form-control" id="whatsappTriggerMessage" name="whatsappTriggerMessage" rows=1>{{ empty($offer->ini['settings']['whatsapp_trigger_message'])?'':nl2br($offer->ini['settings']['whatsapp_trigger_message']) }}</textarea>
							<small class="form-text small-text-color">For referral, please include "<u>Ref:{referrerCode}</u>" at the end, for example "Ref:D4C3B2A1"</small>
							<small class="form-text small-text-color">It is pattern match, not whole message exactly match</small>
							<small class="form-text small-text-color">Don't use same trigger message with other offers</small>
							<small class="form-text small-text-color"><u>Single line</u> only, no line break</small>
						</fieldset>
					</div>
				</div>

				<div class="row" style="display:none;">
					<div class="col-lg-12">
						<fieldset class="form-group">
							<label for="whatsappNotificationMessage">WhatsApp Notification Message</label>
							<textarea class="form-control" id="whatsappNotificationMessage" name="whatsappNotificationMessage" rows=3>{{ empty($offer->ini['offer_thankyou']['notification_whatsapp_content'])?'':nl2br($offer->ini['offer_thankyou']['notification_whatsapp_content']) }}</textarea>
							<small class="form-text small-text-color">This message will be sent to end-users after registration complete, it support below dynamic fields:</small>
							<small class="form-text small-text-color"><font color="#cc9944">@{{link}}</font> = Coupon URL</small>
							<small class="form-text small-text-color"><font color="#cc9944">@{{referralCode}}</font> = Offer referral code</small>
							<small class="form-text small-text-color"><font color="#cc9944">@{{referralLink}}</font> = Offer referral URL</small>
							<small class="form-text small-text-color"><font color="#cc9944">@{{startDate}}</font> = Redemption start date</small>
							<small class="form-text small-text-color"><font color="#cc9944">@{{endDate}}</font> = Redemption end date</small>
							<small class="form-text small-text-color"><font color="#cc9944">@{{selectedRedemptionStore}}</font> = Selected redemption store</small>
							<small class="form-text small-text-color">and those parameters in offer registration form</small>
						</fieldset>
					</div>
				</div>

				<div class="row" style="display:none;">
					<div class="col-lg-12">
						<fieldset class="form-group">
							<label for="whatsappReferralNotificationMessage">WhatsApp Referral Notification Message</label>
							<textarea class="form-control" id="whatsappReferralNotificationMessage" name="whatsappReferralNotificationMessage" rows=3>{{ empty($offer->ini['offer_thankyou']['referral_notification_whatsapp_content'])?'':nl2br($offer->ini['offer_thankyou']['referral_notification_whatsapp_content']) }}</textarea>
							<small class="form-text small-text-color">This message will be sent to end-users after registration complete, it support below dynamic fields:</small>
							<small class="form-text small-text-color"><font color="#cc9944">@{{link}}</font> = Coupon URL</small>
							<small class="form-text small-text-color"><font color="#cc9944">@{{referralCode}}</font> = Offer referral code</small>
							<small class="form-text small-text-color"><font color="#cc9944">@{{referralLink}}</font> = Offer referral URL</small>
							<small class="form-text small-text-color"><font color="#cc9944">@{{startDate}}</font> = Redemption start date</small>
							<small class="form-text small-text-color"><font color="#cc9944">@{{endDate}}</font> = Redemption end date</small>
							<small class="form-text small-text-color"><font color="#cc9944">@{{selectedRedemptionStore}}</font> = Selected redemption store</small>
							<small class="form-text small-text-color">and those parameters in offer registration form</small>
						</fieldset>
					</div>
				</div>

				<div class="row" style="display:none;">
					<div class="col-lg-8">
						<fieldset class="form-group">
							<label for="whatsappReminderTime">WhatsApp Coupon Reminder Time</label>
							<input class="form-control" type="text" value="{{ empty($offer->ini['offer_thankyou']['reminder_notification_whatsapp_time'])?'+2 days':$offer->ini['offer_thankyou']['reminder_notification_whatsapp_time'] }}" id="whatsappReminderTime" name="whatsappReminderTime">
							<small class="form-text small-text-color">How long a reminder message will be sent after end-users get a coupon</small>
							<small class="form-text small-text-color">This is a <u>strtotime()</u> value</small>
						</fieldset>
					</div>
				</div>

				<div class="row" style="display:none;">
					<div class="col-lg-12">
						<fieldset class="form-group">
							<label for="whatsappReminderMessage">WhatsApp Coupon Reminder Message</label>
							<textarea class="form-control" id="whatsappReminderMessage" name="whatsappReminderMessage" rows=3>{{ empty($offer->ini['offer_thankyou']['reminder_notification_whatsapp_content'])?'':nl2br($offer->ini['offer_thankyou']['reminder_notification_whatsapp_content']) }}</textarea>
							<small class="form-text small-text-color">This message will be sent to end-users if coupon not yet redeemed after above period, it support below dynamic fields:</small>
							<small class="form-text small-text-color"><font color="#cc9944">@{{link}}</font> = Coupon URL</small>
							<small class="form-text small-text-color"><font color="#cc9944">@{{referralCode}}</font> = Offer referral code</small>
							<small class="form-text small-text-color"><font color="#cc9944">@{{referralLink}}</font> = Offer referral URL</small>
							<small class="form-text small-text-color"><font color="#cc9944">@{{startDate}}</font> = Redemption start date</small>
							<small class="form-text small-text-color"><font color="#cc9944">@{{endDate}}</font> = Redemption end date</small>
							<small class="form-text small-text-color"><font color="#cc9944">@{{selectedRedemptionStore}}</font> = Selected redemption store</small>
							<small class="form-text small-text-color">and those parameters in offer registration form</small>
						</fieldset>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-12">
						<fieldset class="form-group">
							<label for="whatsappOutOfQuotaMessage">WhatsApp Out Of Quota Message</label>
							<textarea class="form-control" id="whatsappOutOfQuotaMessage" name="whatsappOutOfQuotaMessage" rows=3>{{ empty($offer->ini['settings']['whatsapp_out_of_quota_message'])?'':nl2br($offer->ini['settings']['whatsapp_out_of_quota_message']) }}</textarea>
							<small class="form-text small-text-color">Also used by journey if out of quota node is empty</small>
						</fieldset>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-12">
						<fieldset class="form-group">
							<label for="whatsappExpiryMessage">WhatsApp Expiry Message</label>
							<textarea class="form-control" id="whatsappExpiryMessage" name="whatsappExpiryMessage" rows=5>{{ empty($offer->ini['coupon_expired']['whatsapp_expiry_message'])?'':nl2br($offer->ini['coupon_expired']['whatsapp_expiry_message']) }}</textarea>
							<small class="form-text small-text-color">Also used by journey if expiry node is empty</small>
						</fieldset>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-12">
						<fieldset class="form-group">
							<label for="journeyFinishMessage">WhatsApp Journey Finish Message</label>
							<textarea class="form-control" id="journeyFinishMessage" name="journeyFinishMessage" rows=5>{{ empty($offer->ini['settings']['journey_finish_message'])?'':nl2br($offer->ini['settings']['journey_finish_message']) }}</textarea>
							<small class="form-text small-text-color">Used by journey only</small>
						</fieldset>
					</div>
				</div>
			</div>

			<div id="embeddedSection" style="display:none;">
				<div class="row">
					<div class="col-lg-12">
						<fieldset class="form-group">
							<label for="whatsappNotificationMessage">WhatsApp Notification Message</label>
							<textarea class="form-control" id="whatsappNotificationMessage" name="whatsappNotificationMessage" rows=3>{{ empty($offer->ini['offer_thankyou']['notification_whatsapp_content'])?'':nl2br($offer->ini['offer_thankyou']['notification_whatsapp_content']) }}</textarea>
							<small class="form-text text-muted">This message will be sent to end-users after registration complete, it support below dynamic fields:</small>
							<small class="form-text text-muted"><font color="#cc9944">@{{link}}</font> = Coupon URL</small>
							<small class="form-text text-muted"><font color="#cc9944">@{{referralCode}}</font> = Offer referral code</small>
							<small class="form-text text-muted"><font color="#cc9944">@{{referralLink}}</font> = Offer referral URL</small>
							<small class="form-text text-muted"><font color="#cc9944">@{{startDate}}</font> = Redemption start date</small>
							<small class="form-text text-muted"><font color="#cc9944">@{{endDate}}</font> = Redemption end date</small>
							<small class="form-text text-muted"><font color="#cc9944">@{{selectedRedemptionStore}}</font> = Selected redemption store</small>
							<small class="form-text text-muted">and those parameters in offer registration form</small>
						</fieldset>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-12">
						<fieldset class="form-group">
							<label for="whatsappReferralNotificationMessage">WhatsApp Referral Notification Message</label>
							<textarea class="form-control" id="whatsappReferralNotificationMessage" name="whatsappReferralNotificationMessage" rows=3>{{ empty($offer->ini['offer_thankyou']['referral_notification_whatsapp_content'])?'':nl2br($offer->ini['offer_thankyou']['referral_notification_whatsapp_content']) }}</textarea>
							<small class="form-text text-muted">This message will be sent to end-users after registration complete, it support below dynamic fields:</small>
							<small class="form-text text-muted"><font color="#cc9944">@{{link}}</font> = Coupon URL</small>
							<small class="form-text text-muted"><font color="#cc9944">@{{referralCode}}</font> = Offer referral code</small>
							<small class="form-text text-muted"><font color="#cc9944">@{{referralLink}}</font> = Offer referral URL</small>
							<small class="form-text text-muted"><font color="#cc9944">@{{startDate}}</font> = Redemption start date</small>
							<small class="form-text text-muted"><font color="#cc9944">@{{endDate}}</font> = Redemption end date</small>
							<small class="form-text text-muted"><font color="#cc9944">@{{selectedRedemptionStore}}</font> = Selected redemption store</small>
							<small class="form-text text-muted">and those parameters in offer registration form</small>
						</fieldset>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-8">
						<fieldset class="form-group">
							<label for="whatsappReminderTime">WhatsApp Coupon Reminder Time</label>
							<input class="form-control" type="text" value="{{ empty($offer->ini['offer_thankyou']['reminder_notification_whatsapp_time'])?'+2 days':$offer->ini['offer_thankyou']['reminder_notification_whatsapp_time'] }}" id="whatsappReminderTime" name="whatsappReminderTime">
							<small class="form-text text-muted">How long a reminder message will be sent after end-users get a coupon</small>
							<small class="form-text text-muted">This is a <u>strtotime()</u> value</small>
						</fieldset>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-12">
						<fieldset class="form-group">
							<label for="whatsappReminderMessage">WhatsApp Coupon Reminder Message</label>
							<textarea class="form-control" id="whatsappReminderMessage" name="whatsappReminderMessage" rows=3>{{ empty($offer->ini['offer_thankyou']['reminder_notification_whatsapp_content'])?'':nl2br($offer->ini['offer_thankyou']['reminder_notification_whatsapp_content']) }}</textarea>
							<small class="form-text text-muted">This message will be sent to end-users if coupon not yet redeemed after above period, it support below dynamic fields:</small>
							<small class="form-text text-muted"><font color="#cc9944">@{{link}}</font> = Coupon URL</small>
							<small class="form-text text-muted"><font color="#cc9944">@{{referralCode}}</font> = Offer referral code</small>
							<small class="form-text text-muted"><font color="#cc9944">@{{referralLink}}</font> = Offer referral URL</small>
							<small class="form-text text-muted"><font color="#cc9944">@{{startDate}}</font> = Redemption start date</small>
							<small class="form-text text-muted"><font color="#cc9944">@{{endDate}}</font> = Redemption end date</small>
							<small class="form-text text-muted"><font color="#cc9944">@{{selectedRedemptionStore}}</font> = Selected redemption store</small>
							<small class="form-text text-muted">and those parameters in offer registration form</small>
						</fieldset>
					</div>
				</div>
			</div>

			<!--  Theme color  -->
			<div id="themeSection">
				<div class="row">
					<div class="col-lg-4">
						<fieldset class="form-group">
							<label for="offerThemeButtonColor">Theme Button Color</label>
							<input class="form-control" style="height:50px;" type="color" value="{{ empty($offer->ini['settings']['theme_button_color'])?'#FFAF19':$offer->ini['settings']['theme_button_color'] }}" id="offerThemeButtonColor" name="offerThemeButtonColor">
						</fieldset>
					</div>

					<div class="col-lg-4">
						<fieldset class="form-group">
							<label for="offerThemeButtonTextColor">Theme Button Text Color</label>
							<input class="form-control" style="height:50px;" type="color" value="{{ empty($offer->ini['settings']['theme_button_text_color'])?'#57524F':$offer->ini['settings']['theme_button_text_color'] }}" id="offerThemeButtonTextColor" name="offerThemeButtonTextColor">
						</fieldset>
					</div>

					<div class="col-lg-4">
						<fieldset class="form-group">
							<label for="offerThemeButtonHoverColor">Theme Button Hover Color</label>
							<input class="form-control" style="height:50px;" type="color" value="{{ empty($offer->ini['settings']['theme_button_hover_color'])?'#57524F':$offer->ini['settings']['theme_button_hover_color'] }}" id="offerThemeButtonHoverColor" name="offerThemeButtonHoverColor">
						</fieldset>
					</div>
				</div>
			</div>

			<!--  Machine learning related  -->
			<div id="machineLearningSection">
				<div class="row">
					<div class="col-lg-12">
						<fieldset class="form-group">
							<label for="machineLearningLabels">Machine Learning Labels</label>
							<input class="form-control" type="text" value="{{ empty($offer->ml_labels)?'':$offer->ml_labels }}" id="machineLearningLabels" name="machineLearningLabels" placeholder="Sports, Health,...etc." maxlength="127">
						</fieldset>
					</div>

					<div class="col-lg-12">
						<fieldset class="form-group">
							<label for="category">Category</label>
							<input class="form-control" type="text" value="{{ empty($offer->category)?'':$offer->category }}" id="category" name="category" placeholder="美食, 好去處, 優惠" maxlength="16">
						</fieldset>
					</div>

					<div class="col-lg-12">
						<fieldset class="form-group">
							<label for="filter">Filter</label>
							<input class="form-control" type="text" value="{{ empty($offer->filter)?'':$offer->filter }}" id="machineLearningLabels" name="filter" placeholder="生日蛋糕, 首飾" maxlength="48">
						</fieldset>
					</div>
				</div>
			</div>

		</div>
	</div>

	<div class="card" style="margin-bottom:20px;">
		<div class="card-body">

			<div class="row">
				<div class="col-sm-12"><h4>Tracking Code Settings</h4></div>
				<div class="col-lg-12">
					<small class="form-text small-text-color">If Segment has been included in template then Facebook Pixel, Google Analytics, GTM may also included</small><br>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<fieldset class="form-group">
						<label for="facebookPixel">Facebook Pixel</label>
						<input class="form-control" type="text" value="{{ isset($facebookPixel)?$facebookPixel:'' }}" id="facebookPixel" name="facebookPixel" placeholder="888888888888888">
						<small class="form-text small-text-color">Comma separated, ex.: 245159656125281, 1829230224036491</small>
					</fieldset>
				</div>
				<div class="col-lg-12">
					<fieldset class="form-group">
						<label for="googleAnalytics">Google Analytics</label>
						<input class="form-control" type="text" value="{{ isset($googleAnalytics)?$googleAnalytics:'' }}" id="googleAnalytics" name="googleAnalytics" placeholder="UA-888888888-8">
						<small class="form-text small-text-color">Comma separated, ex.: UA-888888888-8, UA-123456789-0</small>
					</fieldset>
				</div>
				<div class="col-lg-12">
					<fieldset class="form-group">
						<label for="gtm">GTM</label>
						<input class="form-control" type="text" value="{{ isset($gtm)?$gtm:'' }}" id="gtm" name="gtm" placeholder="GTM-XXXX">
						<small class="form-text small-text-color">Comma separated, ex.: GTM-XXXX, GTM-YYYY</small>
					</fieldset>
				</div>
			</div>

		</div>
	</div>

	<!--  Report Settings  -->
	<div class="card" style="margin-bottom:20px;">
		<div class="card-body">

			<div class="row">
				<div class="col-sm-12"><h4>Reports Settings</h4></div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<fieldset class="form-group">
						<label for="dailyReportRecipients">Daily Outbound Report Recipients</label>
						<input class="form-control" type="text" value="{{ empty($offer->ini['settings']['daily_outbound_report_recipients'])?'':nl2br($offer->ini['settings']['daily_outbound_report_recipients']) }}" id="dailyOutboundReportRecipient" name="dailyOutboundReportRecipients" placeholder="Email address, email address">
						<small class="form-text small-text-color">Comma separated email addresses</small>
						<small class="form-text small-text-color">Receive at 09:00 everyday</small>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<fieldset class="form-group">
						<label for="dailyCouponReportRecipients">Daily Coupon Report Recipients</label>
						<input class="form-control" type="text" value="{{ empty($offer->ini['settings']['daily_coupon_report_recipients'])?'':nl2br($offer->ini['settings']['daily_coupon_report_recipients']) }}" id="dailyCouponRecipient" name="dailyCouponReportRecipients" placeholder="Email address, email address">
						<small class="form-text small-text-color">Comma separated email addresses</small>
						<small class="form-text small-text-color">Receive at 08:40 everyday</small>
					</fieldset>
				</div>

				<div class="col-lg-12">
					<fieldset class="form-group">
						<label for="dailyCouponReportRecipients">Daily Report Password Recipients</label>
						<input class="form-control" type="text" value="{{ empty($offer->ini['settings']['daily_report_password_recipients'])?'':nl2br($offer->ini['settings']['daily_report_password_recipients']) }}" id="dailyReportPasswordRecipient" name="dailyReportPasswordRecipients" placeholder="Email address, email address">
						<small class="form-text small-text-color">Comma separated email addresses</small>
					</fieldset>
				</div>

				<div class="col-lg-12">
					<fieldset class="form-group">
						<label for="dailyCouponExtend">Report Grace Period</label>
						<input class="form-control" type="text" value="{{ empty($offer->ini['settings']['daily_coupon_date_extend'])?'':nl2br($offer->ini['settings']['daily_coupon_date_extend']) }}" id="dailyCouponDateExtend" name="dailyCouponDateExtend" placeholder="+7 days">
						<small class="form-text small-text-color">Records included # days after offer has been expired</small>
					</fieldset>
				</div>
			</div>

			<!--  Buttons  -->
			<div class="row">
				<div class="col-lg-6">
@if (empty($offerCode) || $offerCode == "new")
					<button type="button" class="btn btn-success" id="saveButton">Create</button>
@else
					<button type="button" class="btn btn-danger" id="saveButton">Save</button>
@endif
				</div>
				<div class="col-lg-6 text-right">
					<div class="text-white btn btn-outline-warning" id="cloneButton">Clone This Offer</div>
					<small class="form-text small-text-color">Please hold until finish</small>
				</div>
			</div>
		</div>
	</div>
</form>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{ asset('js/ckeditor.js') }}?v=2"></script>
<script type="text/javascript" src="{{ asset('js/common.js') }}?v=1"></script>

<script>
	var _baseURL = "{{ $baseURL }}";
	var _modified = false;

	function updateLayout()  {
		var select = $("#bladeFolder option:selected")[0];
		var baseURL = "/offers/common/"+select.value+"/";
		$("#bladeFolderLayoutOffer").attr("src", baseURL+"offer_landing.png");
		$("#bladeFolderLayoutWhatsapp").attr("src", baseURL+"whatsapp_journey.png");
		$("#bladeFolderLayoutCouponCountdown").attr("src", baseURL+"coupon_countdown.png");
	}

	$(document).ready(function()  {

		//  When leave page, show loading
		window.onbeforeunload = function(e)  {showLoading();};

		$("#offerCode").keyup(function()  {
			var offerCode = $("#offerCode").val();
			var url = _baseURL+offerCode;
			$("#offerURL").text(url);
			$("#offerURL").attr("href", url);

			var couponRequestURL = url+"/coupon-link/";
			$("#couponRequestURL").text(couponRequestURL);
			$("#couponRequestURL").attr("href", couponRequestURL);
		});

		$("#bladeFolder").change(updateLayout);
		updateLayout();

		$.validator.addMethod("time", function(value, element)  {
			return this.optional(element) || /^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])(:([0-5]?[0-9]))?$/i.test(value);
		}, "Please enter a valid time.");

		$.validator.addMethod("alphanumeric", function(value, element)  {
			return this.optional(element) || /^[\w._-]+$/i.test(value);
		}, "Only letters, numbers, hyphen and underscore is allowed.");

		$.validator.addMethod("nounderscore", function(value, element, param)  {
			return this.optional(element) || /^[a-zA-Z0-9.-]+$/i.test(value);
		}, "Underscore is not allowed.");

		$("#saveButton").click(function()  {

			var basicRule = {
				rules:  {
					startDate:  {date:true},
					startTime:  {time:true},
					endDate:  {date:true},
					endTime:  {time:true},
					offerCode:  {minlength:6, nounderscore:true},
					offerName:  {minlength:3, alphanumeric:true},
					offerTitle:  {minlength:1},
					quota:  {number:true},
				},
				messages: {
					offerCode:  {minlength:"Must consist of at least 6 characters"},
					offerName:  {minlength:"Must consist of at least 3 characters"},
					offerTitle:  {minlength:"Must consist of at least 1 characters"},
				}
			};

			var form = $("#form");
			form.validate(basicRule);

			result = form.valid();
			if (result == false)  {return;}

			//  Form OK
			CKEDITOR.instances.description.updateElement();
			CKEDITOR.instances.tnc.updateElement();

			var disabled = $("#form").find(":input:disabled").removeAttr("disabled");
			var formData = $("#form").serialize();
			disabled.attr("disabled", "disabled");

			showLoading();
			$.ajax({
				type: "POST",
				data: formData,
				dataType: "json",
				url: '{{ route("foso.campaigns.offer.settings.json", ["offer_code"=>$offerCode]) }}',
				success: function (result)  {

					alert(result.message);
					if (result.status != 0)  {
						hideLoading();
						return;
					}

@if (empty($offerCode) || $offerCode == "new")
					location.href = result.resourceURL;
@else
					location.href = '{{ route("foso.campaigns.offer.html") }}';
@endif
				},
				error: function (XMLHttpRequest, textStatus, errorThrown)  {
					hideLoading();
					alert("Oops...\n#"+textStatus+": "+errorThrown);
				}
			});

		});

// 		$("#journeyButton").click(function()  {
// 			$("#journeySection").show();
// 			$("#embeddedSection").hide();
// 			$("#journeyButton").removeClass("btn-outline-primary").addClass("btn-primary");
// 			$("#embeddedButton").removeClass("btn-primary").addClass("btn-outline-primary");
// 		});
//
// 		$("#embeddedButton").click(function()  {
// 			$("#journeySection").hide();
// 			$("#embeddedSection").show();
// 			$("#journeyButton").removeClass("btn-primary").addClass("btn-outline-primary");
// 			$("#embeddedButton").removeClass("btn-outline-primary").addClass("btn-primary");
// 		});

// 		$("#outOfQuotaButton").click(function()  {
// 			showLoading();
// 			$.ajax({
// 				type: "POST",
// 				data: formData,
// 				dataType: "json",
// 				url: '{{ route("foso.campaigns.offer.settings.json", ["offer_code"=>$offerCode]) }}',
// 				success: function (result)  {
//
// 					alert(result.message);
// 					if (result.status != 0)  {
// 						hideLoading();
// 						return;
// 					}
//
// 				},
// 				error: function (XMLHttpRequest, textStatus, errorThrown)  {
// 					hideLoading();
// 					alert("Oops...\n#"+textStatus+": "+errorThrown);
// 				}
// 			});
// 		});

		const handleOutOfQuotaButton = () => {
			showLoading();

			const api = '{{ route("foso.campaigns.offer.outofquota.json", ["offer_code"=>$offerCode]) }}'
			fetch(api, {
				method: 'post',
				headers: {'Content-Type': 'application/json'},
				body: JSON.stringify({
					offerCode: '{{$offerCode}}',
				})
			})
			.then(response=>response.json())
			.then(json => {

				if (json.status < 0)  {
					alert(json.message);
					return;
				}

				//  Reload if success
				location.href = '{{ route("foso.campaigns.offer.html") }}';
			});

			hideLoading();
		};

		const outOfQuotaButton = AnimatedBtn({
			domId: "outOfQuotaButton",
			transitionTime: 2000,
			yourFunction: handleOutOfQuotaButton,
			coverColor: 'rgba(128, 40, 40, 1)',
			baseColor: 'rgba(221, 75, 57, 1)',
		});

		const handleResumeQuotaButton = () => {
			showLoading();

			const api = '{{ route("foso.campaigns.offer.resumequota.json", ["offer_code"=>$offerCode]) }}'
			fetch(api, {
				method: 'post',
				headers: {'Content-Type': 'application/json'},
				body: JSON.stringify({
					offerCode: '{{$offerCode}}',
				})
			})
			.then(response=>response.json())
			.then(json => {

				if (json.status < 0)  {
					alert(json.message);
					return;
				}

				//  Reload if success
				location.href = '{{ route("foso.campaigns.offer.html") }}';
			});

			hideLoading();
		};

		const resumeQuotaButton = AnimatedBtn({
			domId: "resumeQuotaButton",
			transitionTime: 2000,
			yourFunction: handleResumeQuotaButton,
			coverColor: 'rgba(128, 64, 0, 1)',
			baseColor: 'rgba(224, 142, 11, 1)',
		});

		const handleClearWhiteList = async () => {
			showLoading();
			let _token = document.querySelector('[name=csrf-token]').content;
			await $.ajax({
				type: "POST",
				data: JSON.stringify({_token:_token}),
				dataType: "json",
				url: '{{ route("foso.campaigns.offer.clearwhitelisted.json", ["offer_code"=>$offerCode]) }}',
				success: function (result)  {
					alert(result.message);
					location.reload();
				},
				error: function (XMLHttpRequest, textStatus, errorThrown)  {
					hideLoading();
					alert("Oops...\n#"+textStatus+": "+errorThrown);
				}
			});
			location.reload();
		}
		const clearWhiteList = AnimatedBtn({
			domId: "clearWhiteList",
			transitionTime: 2000,
			yourFunction: handleClearWhiteList,
			coverColor: 'rgba(128, 40, 40, 1)',
			baseColor: 'rgba(221, 75, 57, 1)',
		});

		// export the offer in zip --------------------------------------------
        const handleZipOffer = async () => {
			showLoading();
			var link = $('<a>');
			link[0].href = '{{ route("foso.campaigns.offer.exportoffer.json", ["offer_code"=>$offerCode]) }}';
			link[0].click();
			hideLoading();
        }

        const zipoffer = AnimatedBtn({
            domId: "zipoffer",
            transitionTime: 2000,
            yourFunction: handleZipOffer,
            coverColor: 'rgba(2, 7, 93, 1)',
            baseColor: 'rgba(67, 119, 207, 1)',
        });


		// --------------------------------------------

@if (empty($offer) == false)
@if ($offer->quota > $offer->quota_issued)
		//  Still have quota, show "Out of Quota" button
		$("#outOfQuotaButtonGroup").show();
@else
		//  No quota, show "Resume Quota" button
		$("#resumeQuotaButtonGroup").show();
@endif
@endif

		CKEDITOR.replace("description");
		CKEDITOR.replace("tnc");
	});
</script>
<script src="/js/animatedBtn.js"></script>

<script>
	const handleCloneOffer = () => {
		showLoading();

		// const offerCode = location.href.split('/offer/')[1].split('/settings')[0];
		// const api = location.href.replace('settings','cloneOffer')
		const api = '{{ route("foso.campaigns.offer.cloneoffer.json", ["offer_code"=>$offerCode]) }}'
		fetch(api, {
			method:'post',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(
				{
					// offerCode: offerCode,
					offerCode: '{{$offerCode}}',
				}
			)
		})
		.then(response=>response.json())
		.then(json => {
			alert(json.message);
			if(json.message == 'ok') {
				setInterval(()=>{location.href = '/foso/campaigns/offer'}, 1000)
			}
		});

		hideLoading();
	}

	const cloneOfferButton = AnimatedBtn({
		domId: '#cloneButton',
		transitionTime: 2000,
		yourFunction: handleCloneOffer,
		coverColor: 'rgba(255, 193, 7, 0.5)',
		baseColor: 'rgba(255, 193, 7, 1)',
	});

</script>

<script>
	(function() {
		const selectBladeFolderEle = document.querySelector('select[name="bladeFolder"]');
		const inputOfferThemeButtonColorEle = document.querySelector('input[name="offerThemeButtonColor"]');
		const inputOfferThemeButtonTextColorEle = document.querySelector('input[name="offerThemeButtonTextColor"]');
		const inputOfferThemeButtonHoverColorEle = document.querySelector('input[name="offerThemeButtonHoverColor"]');

		if (selectBladeFolderEle) {
			let bladeFolders = ['40_lazy_pack', '41_lazy_pack_fb', '42_lazy_pack_ig', '43_lazy_pack_fb_ig'];
			selectBladeFolderEle.onchange = function() {
				if (bladeFolders.includes(this.value)) {
					inputOfferThemeButtonColorEle.value = '#ffaf19';
					inputOfferThemeButtonTextColorEle.value = '#57524F';
					inputOfferThemeButtonHoverColorEle.value = '#57524F';
				} else {
					inputOfferThemeButtonColorEle.value = '#ffaf19';
					inputOfferThemeButtonTextColorEle.value = '#ffffff';
					inputOfferThemeButtonHoverColorEle.value = '#ff6a00';
				}
			}
		}
	})();
</script>

@endsection
