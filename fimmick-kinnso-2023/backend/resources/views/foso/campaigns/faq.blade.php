@extends('foso.layouts.default')

@section('page_title', 'Campaign FAQ')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.campaigns.html") }}'>Campaigns</a></li>
<li><i class='fa fa-angle-right'></i> FAQ</li>
@endsection

@section('content')

<div class='card' style="margin-bottom:10px">
	<div class='card-body'>
		<div class="row">
			<div class="col-sm-12">
				Frequently asked question.
			</div>
		</div>
	</div>
</div>

<div class='card' style="margin-bottom:10px">
	<div class='card-body'>
		<div class="row">
			<div class="col-sm-12">
				<h4>Questions About Offer</h4>

				<div class="card card-body">
					<a data-toggle="collapse" href="#faq_offer_1" role="button" aria-expanded="false" aria-controls="collapseExample">
						Is it possible to clone an offer?
					</a>
					<div class="collapse" id="faq_offer_1">
						Yes.  There is a "Clone This Offer" button at offer settings page.
						In order to prevent mistake, this button is called "Long-click" button.
						You have to click and hold until it execute the function.  Please note
						that only settings, images and journey will be cloned, but not coupons
						and referral records.
					</div>
				</div>

				<div class="card card-body">
					<a data-toggle="collapse" href="#faq_offer_2" role="button" aria-expanded="false" aria-controls="collapseExample">
						Is it possible to extend an offer period?
					</a>
					<div class="collapse" id="faq_offer_2">
						Yes.  Just goto offer settings page, change the end date or time to
						new value and save.
					</div>
				</div>

				<div class="card card-body">
					<a data-toggle="collapse" href="#faq_offer_3" role="button" aria-expanded="false" aria-controls="collapseExample">
						Is it possible to change a coupon period?
					</a>
					<div class="collapse" id="faq_offer_3">
						Um...it is not possible to do it in FOSO.  But you can ask for developer's
						support, they can update individual coupon period directly in database.
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<div class='card' style="margin-bottom:10px">
	<div class='card-body'>
		<div class="row">
			<div class="col-sm-12">
				<h4>Question About Quota</h4>

				<div class="card card-body">
					<a data-toggle="collapse" href="#faq_quota_1" role="button" aria-expanded="false" aria-controls="collapseExample">
						How can I know if an offer almost running out of quota?
					</a>
					<div class="collapse" id="faq_quota_1">
						Kinnso system will send out email notification to developer1@fimmick.com,
						pacessho@fimmick.com, nestayeung@fimmick.com and
						dp@fimmick.com, if quota reaches 50%, 75% and 100%.
					</div>
				</div>

				<div class="card card-body">
					<a data-toggle="collapse" href="#faq_quota_2" role="button" aria-expanded="false" aria-controls="collapseExample">
						How can I add quota?
					</a>
					<div class="collapse" id="faq_quota_2">
						In FOSO, pick an offer, goto "Quota" or "Coupon Pool" section.  It is
						depends on offer coupon type: "Randomly generated" or "Pre-generated".
						For "Quota" type, please upload a CSV with new quota and its period.
						For "Coupon Pool", type upload a CSV with new unique code (Not exists
						in database yet)
					</div>
				</div>

				<div class="card card-body">
					<a data-toggle="collapse" href="#faq_quota_3" role="button" aria-expanded="false" aria-controls="collapseExample">
						How can I remove some quota?
					</a>
					<div class="collapse" id="faq_quota_3">
						You can upload new CSV file in "Quota" or "Coupon Pool" section depends
						on your offer coupon type.  They are different format, please note.
						All the records in CSV will be replaced to database.  If you want
						to remove a store then you must set the quota to zero.  The record row
						of corresponding store must be set in CSV, otherwise, it changes nothing.
					</div>
				</div>

				<div class="card card-body">
					<a data-toggle="collapse" href="#faq_quota_4" role="button" aria-expanded="false" aria-controls="collapseExample">
						How to set the QR Code for pre-generated offer coupon type?
					</a>
					<div class="collapse" id="faq_quota_4">
						<table cellpadding="4">
							<tr><td width="60" valign="top">Step 1:</td><td>Select "Pre-generated" for Offer Coupon Type on Settings Page.</td></tr>
							<tr><td width="60" valign="top">Step 2:</td><td>Upload CSV of coupon codes in coupon pool.</td></tr>
							<tr><td width="60" valign="top">Step 3:</td><td>Upload coupon images in coupon pool. Name the images as the same as the coupon codes.</td></tr>
							<tr><td width="60" valign="top">Step 4:</td><td>
								Set 100 Message Node in Journey.
								<br>&nbsp;
								<br><b>QR Code:</b>
								<br>https://www.kinnso.com/qrcode/?c={content}&l={logoURL}
								<br>&nbsp;
								<br><b>Example:</b>
								<br><a href="https://www.kinnso.com/qrcode/?c=@{{uniqueCode}}&l=https://secure.gravatar.com/avatar/6ec09aa205bb46431dfee9b0412e77c7">
									https://www.kinnso.com/qrcode/?c=@{{uniqueCode}}&l=https://secure.gravatar.com/avatar/6ec09aa205bb46431dfee9b0412e77c7</a>
								<br>&nbsp;
								<br>Replace {content} with @{{uniqueCode}} and replace {logoURL} with the image you want to display in the center of QR Code.
								The inserted logoURL image will take up 25% of the QR Code, centered in the middle as below:
								<br><img src="https://www.kinnso.com/qrcode/?c=Please%20put%20your%20content%20here&l=https://secure.gravatar.com/avatar/6ec09aa205bb46431dfee9b0412e77c7">
							</td></tr>
						</table>
					</div>
				</div>

				<div class="card card-body">
					<a data-toggle="collapse" href="#faq_quota_5" role="button" aria-expanded="false" aria-controls="collapseExample">
						How to set QR Code for randomly generated offer coupon type?
					</a>
					<div class="collapse" id="faq_quota_5">
						<table cellpadding="4">
							<tr><td width="60" valign="top">Step 1:</td><td>Select "Randomly generated"
								for Offer Coupon Type on Settings Page</td></tr>
							<tr><td width="60" valign="top">Step 2:</td><td>Upload Quota CSV file </td></tr>
							<tr><td width="60" valign="top">Step 3:</td><td>Upload a generic
								QR Code in 100 Message Node. Or you can set the URL as follows
								to create a unique QR code for each coupon offer:
								<br>&nbsp;
								<br>https://www.kinnso.com/qrcode/?c=https%3A%2F%2Fwww.kinnso.com%2F@{{uniqueCode}}
								<br>&nbsp;
								<br>Then the unique QR code will be generated automatically.
							</td></tr>
						</table>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<div class='card' style="margin-bottom:10px">
	<div class='card-body'>
		<div class="row">
			<div class="col-sm-12">
				<h4>Question About Settings</h4>

				<div class="card card-body">
					<a data-toggle="collapse" href="#faq_settings_1" role="button" aria-expanded="false" aria-controls="collapseExample">
						What will happen when the "Set Out of Quota" button is pressed?
					</a>
					<div class="collapse" id="faq_settings_1">
						The amount of quota issued will equal the total amount of quota set.
						And the whatsapp button on the front end link will be disabled.
						This button is temporary and can be resumed to return to the previous
						"Quota" and "Quota Issued" numbers.
					</div>
				</div>

				<div class="card card-body">
					<a data-toggle="collapse" href="#faq_settings_2" role="button" aria-expanded="false" aria-controls="collapseExample">
						What is the function of "View Counter"?
					</a>
					<div class="collapse" id="faq_settings_2">
						The viewed number will not be shown in the front end. You can reset
						the number as zero when the offer is about to launch.
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<div class='card' style="margin-bottom:10px">
	<div class='card-body'>
		<div class="row">
			<div class="col-sm-12">
				<h4>Question About Daily Report</h4>

				<div class="card card-body">
					<a data-toggle="collapse" href="#faq_daily_report_1" role="button" aria-expanded="false" aria-controls="collapseExample">
						When is the cut-off time and when will recipients receive the report?
					</a>
					<div class="collapse" id="faq_daily_report_1">
						For all the Daily Report, the cut-off time is 23:59. System will
						send a Daily Coupon Report and Daily Report Password at 08:40
						everyday. Then it sends a Daily Outbound Report to recipients at
						09:00 everyday.
					</div>
				</div>

				<div class="card card-body">
					<a data-toggle="collapse" href="#faq_daily_report_2" role="button" aria-expanded="false" aria-controls="collapseExample">
						What is the Daily Outbound Report?
					</a>
					<div class="collapse" id="faq_daily_report_2">
						This daily report shows details of outbound messages for the individual offer.
					</div>
				</div>

				<div class="card card-body">
					<a data-toggle="collapse" href="#faq_daily_report_3" role="button" aria-expanded="false" aria-controls="collapseExample">
						What is the Daily Coupon Report?
					</a>
					<div class="collapse" id="faq_daily_report_3">
						This daily report shows details of issued coupons from the
						campaign start date for the individual offer.
					</div>
				</div>

				<div class="card card-body">
					<a data-toggle="collapse" href="#faq_daily_report_4" role="button" aria-expanded="false" aria-controls="collapseExample">
						What is the Daily Report Password?
					</a>
					<div class="collapse" id="faq_daily_report_4">
						This password is used for opening the Daily Coupon Report.
					</div>
				</div>

				<div class="card card-body">
					<a data-toggle="collapse" href="#faq_daily_report_5" role="button" aria-expanded="false" aria-controls="collapseExample">
						What is the Accumulate Report?
					</a>
					<div class="collapse" id="faq_daily_report_5">
						This monthly report shows details of all outbound messages for all offers.
					</div>
				</div>


				<div class="card card-body">
					<a data-toggle="collapse" href="#faq_daily_report_6" role="button" aria-expanded="false" aria-controls="collapseExample">
						What does the value in each column represent in 'Coupon Daily Report'?
					</a>
					<div class="collapse" id="faq_daily_report_6">
						<table border="1" cellpadding="4">
							<tr><td valign="top">created_at</td><td>The created date and time for each record in a row.</td></tr>
							<tr><td valign="top">offer_id</td><td>The id of the corresponding coupon offer.</td></tr>
							<tr><td valign="top">parent_offer_id</td><td>The original parenting coupon offer id.</td></tr>
							<tr><td valign="top">coupon_order</td><td>The ordering of the corresponding store of that issued coupon. </td></tr>
							<tr><td valign="top">unique_code</td><td>The unique code of the corresponding QR code issued.</td></tr>
							<tr><td valign="top">mobile</td><td>Mobile no. of the customer.</td></tr>
							<tr><td valign="top">email</td><td>Email of the customer.</td></tr>
							<tr><td valign="top">start_at</td><td>Start date and time of the coupon offer. </td></tr>
							<tr><td valign="top">use at</td><td>The date and time of the coupon being void.</td></tr>
							<tr><td valign="top">expired_at</td><td>The expiration date and time of the coupon.</td></tr>
							<tr><td valign="top">selected_channel</td><td>The channel selected to receive coupon messages.</td></tr>
							<tr><td valign="top">referrer_code</td><td>The code for the referrer to refer friends. Will be shown on the referral link.</td></tr>
							<tr><td valign="top">referral_code</td><td>The code for the referee to redeem coupons. This code will be created once the coupon record is created, even without a referral flow. </td></tr>
							<tr><td valign="top">form_data_email</td><td>Customer’s email filled in form.</td></tr>
							<tr><td valign="top">form_data_token</td><td>Will be created once received form data.</td></tr>
							<tr><td valign="top">form_data_mobile</td><td>Mobile no. that the customer used to redeem coupon</td></tr>
							<tr><td valign="top">form_data_offerID</td><td>Offer ID of the coupon.</td></tr>
							<tr><td valign="top">form_data_offerCode</td><td>The unique offer code in the offer URL.</td></tr>
							<tr><td valign="top">form_data_referrerCode</td><td>The code for the referrer to refer friends. Will be shown on the referral link.</td></tr>
							<tr><td valign="top">form_data_selectedChannel</td><td>The selected brand.</td></tr>
							<tr><td valign="top">form_data_confirmationMethod</td><td>The selected communication platform to get coupon messages.</td></tr>
							<tr><td valign="top">form_data_selectedRedemption Store</td><td>The specific store of the brand. If there is only 1 redemption store, it will be the Redemption Store entered in Issue Coupon Node in Journey, same as store code.</td></tr>
							<tr><td valign="top">form_data_pickedRedemptionStoreCode</td><td>The store code that the BA staff entered in the Coupon Countdown Page when the offer adopts the Storecode template. </td></tr>
							<tr><td valign="top">form_data_selectedRedemptionPeriodID</td><td>The ID of different specific redemption periods.</td></tr>
						</table>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<div class='card' style="margin-bottom:10px">
	<div class='card-body'>
		<div class="row">
			<div class="col-sm-12">
				<h4>Question About Journey</h4>

				<div class="card card-body">
					<a data-toggle="collapse" href="#faq_journey_1" role="button" aria-expanded="false" aria-controls="collapseExample">
						How to insert line breaks for whatsapp chatbot content?
					</a>
					<div class="collapse" id="faq_journey_1">
						Just insert line breaks in the text field as the same as you want
						to preview in mobile. It will not be shown in whatsapp simulator
						but rather in mobile.
					</div>
				</div>

				<div class="card card-body">
					<a data-toggle="collapse" href="#faq_journey_2" role="button" aria-expanded="false" aria-controls="collapseExample">
						Why can’t I add a new node in chatbot journey?
					</a>
					<div class="collapse" id="faq_journey_2">
						Remember to name the newly added node the same as the "Next Node Name"
						in the previous node.
					</div>
				</div>

				<div class="card card-body">
					<a data-toggle="collapse" href="#faq_journey_3" role="button" aria-expanded="false" aria-controls="collapseExample">
						Why can’t I upload the image in the uploading field for 100 message node?
					</a>
					<div class="collapse" id="faq_journey_3">
						The image size restriction is below 10MB.
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<div class='card' style="margin-bottom:10px">
	<div class='card-body'>
		<div class="row">
			<div class="col-sm-12">
				<h4>Question About .ini</h4>

				<div class="card card-body">
					<a data-toggle="collapse" href="#faq_ini_1" role="button" aria-expanded="false" aria-controls="collapseExample">
						How to add a whitelist mobile?
					</a>
					<div class="collapse" id="faq_ini_1">
						Only Super-Administrator can edit the ini.  Super-Administrator
						adds mobile no. following the following format:
						<br>&nbsp;
						<br>whitelist_mobile_@{{number in order}}=+852@{{mobile no.}}.
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<div class='card' style="margin-bottom:10px">
	<div class='card-body'>
		<div class="row">
			<div class="col-sm-12">
				<h4>Question About Marketing</h4>

				<div class="card card-body">
					<a data-toggle="collapse" href="#faq_marketing_1" role="button" aria-expanded="false" aria-controls="collapseExample">
						Are all fields compulsory for marketing list.csv?
					</a>
					<div class="collapse" id="faq_marketing_1">
						Only mobile fields are compulsory.  "Username", "A", "B" and "C" are optional.
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<div class='card' style="margin-bottom:10px">
	<div class='card-body'>
		<div class="row">
			<div class="col-sm-12">
				<h4>Question About Offer List Management</h4>

				<div class="card card-body">
					<a data-toggle="collapse" href="#faq_offer_list_management_1" role="button" aria-expanded="false" aria-controls="collapseExample">
						What is this page use for?
					</a>
					<div class="collapse" id="faq_offer_list_management_1">
						This is the page for you to schedule the launch period of coupon
						offers.  Click "Add new offer to the list", enter Offer ID and set
						the Start/ End date. The ordering of coupon offers will be shown
						WYSIWYG on the landing page of KINNSO.  "Create new list" button
						is for creating lists for other coupon platforms.
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<div class='card' style="margin-bottom:10px">
	<div class='card-body'>
		<div class="row">
			<div class="col-sm-12">
				<h4>Question About FOSO</h4>

				<div class="card card-body">
					<a data-toggle="collapse" href="#faq_foso_1" role="button" aria-expanded="false" aria-controls="collapseExample">
						How can I manually trigger message queue?
					</a>
					<div class="collapse" id="faq_foso_1">
						Sometimes we are not to set cronjob on non-production servers.
						It users want to receive scheduled message on WhatsApp, they need
						to trigger the message queue manually by following URL:
						<br><a href="{{ route('campaign.whatsapp.processqueue.json') }}" target="_blank">{{ route('campaign.whatsapp.processqueue.json') }}</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<div class='card' style="margin-bottom:10px">
	<div class='card-body'>
		<div class="row">
			<div class="col-sm-12">
				<h4>Question About Payment</h4>

				<div class="card card-body">
					<a data-toggle="collapse" href="#faq_payment_1" role="button" aria-expanded="false" aria-controls="collapseExample">
						What is CCBA payment settings and testing URL?
					</a>
					<div class="collapse" id="faq_payment_1">
						Please use payment settings and URL below.  It will be expired in 24 hours.
						<br>&nbsp;
						<table border="1" cellpadding="4">
							<tr><td valign="top">Branch ID</td><td>{{ $branchID }}</td></tr>
							<tr><td valign="top">Currency Code</td><td>{{ $currencyCode }}</td></tr>
							<tr><td valign="top">Merchant ID</td><td>{{ $merchantID }}</td></tr>
							<tr><td valign="top">POS ID</td><td>{{ $posID }}</td></tr>
							<tr><td valign="top">Public Key</td><td>{{ $publicKey }}</td></tr>
							<tr><td valign="top">Transaction Code</td><td>{{ $transactionCode }}</td></tr>
						</table>
						<br>Testing Credit Card:
						<table border="1" cellpadding="4">
							<tr><td valign="top">Card Number</td><td>4012001037141112</td></tr>
							<tr><td valign="top">Expiry Date</td><td>12/2027</td></tr>
							<tr><td valign="top">CVV</td><td>212</td></tr>
						</table>
						<br>Payment URL for Testing:
						<br><a href="{{ $paymentURLCCBA }}" target="_blank">{{ $paymentURLCCBA }}</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="{{ asset('js/common.js') }}?v=1"></script>
<script>
	$(document).ready(function()  {
	});
</script>

@endsection
