@extends('foso.layouts.default')

@section('page_title', 'Campaign Read Me')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.campaigns.html") }}'>Campaigns</a></li>
<li><i class='fa fa-angle-right'></i> Read Me</li>
@endsection

@section('content')

<div class='card' style="margin-bottom:10px">
	<div class='card-body'>
		<div class="row">
			<div class="col-sm-12">
				Important information you have to know.
			</div>
		</div>
	</div>
</div>

<div class='card' style="margin-bottom:10px">
	<div class='card-body'>
		<div class="row">
			<div class="col-sm-12">
				<h4>WhatsApp Chatbot</h4>
				<p>We are using Twilio as WhatsApp vendor.  Please set below URL at Twilio webhook page:</p>
				<div class="input-group mb-3">
					<input class="form-control" type="text" id="journeyWebhookURL" name="journeyWebhookURL" value='{{ route("chatbot.twillio.message.json") }}' disabled>
					<div class="input-group-append">
						<button class="btn btn-primary" type="button" id="journeyWebhookURLButton">Copy</button>
					</div>
				</div>

				<p>The program on this route will handle chatbot logic including customer journey.
				But if project using embedded WhatsApp flow, then please use below webhook:</p>
				<div class="input-group mb-3">
					<input class="form-control" type="text" id="embbedWebhookURL" name="embbedWebhookURL" value='{{ route("campaign.whatsapp.webhook.message.comes.in.json") }}' disabled>
					<div class="input-group-append">
						<button class="btn btn-primary" type="button" id="embbedWebhookURLButton">Copy</button>
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
				<h4>Cronjob Time</h4>
				<p>
					03:00 = Data team tables process<br>
					06:00 = Report cleanup process<br>
					07:00 = Data archive process<br>
					08:40 = Offer-based daily coupon report<br>
					08:50 = Accumerated daily WhatsApp inbound-outbound report (Monthly)<br>
					09:00 = Offer-based daily WhatsApp outbound report<br>
					09:30~22:00 = Chatbot referral checking<br>
					09:30~22:00 = Handle WhatsApp message queue
				</p>
			</div>
		</div>
	</div>
</div>

<div class='card' style="margin-bottom:10px">
	<div class='card-body'>
		<div class="row">
			<div class="col-sm-12">
				<h4>About Data Archive</h4>
				<p>System will archive <u><b>three</b></u> types of data:
					<li>Journey</li>
					<li>Inbound WhatsApp</li>
					<li>Outbound WhatsApp</li>
				</p>
				<p>For <b>journey</b> data, when the offer ends <u><b>7 days</b></u> ago will be marked deleted automatically.  And <u><b>1 days</b></u> later, records will be moved to archive table.</p>
				<p>For <b>inbound WhatsApp webhook</b> data, records that was created <u><b>30 days</b></u> ago will be mark deleted automatically.  And <u><b>3 days</b></u> later, records will be moved to archive table.</p>
				<p>For <b>outbound WhatsApp</b> data, records that was sent or canceled <u><b>30 days</b></u> ago will be mark deleted automatically.  And <u><b>3 days</b></u> later, records will be moved to archive table.</p>
			</div>
		</div>
	</div>
</div>

<div class='card' style="margin-bottom:10px">
	<div class='card-body'>
		<div class="row">
			<div class="col-sm-12">
				<h4>Offers & Coupons</h4>
				<p>One offer can have one coupon in this system, in order to achieve multiple
					coupons, we implemented '<u>Bundled Offers ID</u>'.  That means when user
					register current offer, system will also register bundled offer at the same
					time. Both offers will share same <u>unique code</u>.  The behaviour will
					become single URL but more than one offer.  The creation steps are:</p>
				<li>Create a new offer A (For example, offer ID #13)
				<li>Create a new offer B (For example, offer ID #17)
				<li>In offer A settings, enter ID of bundled offer IDs in 'Bundled Offers ID' field (in this case, offer B ID #17)
				<li>In offer B's bundled offer ID settings, leave it empty
				<br>&nbsp;

				<h4>User can only register either one of offers</h4>
				<p>As we all know, one offer can have one coupon only.  To leverage this, we need webook:</p>
				<li>Create a new offer A (For example, offer ID #13)
				<li>Create a new offer B (For example, offer ID #17)
				<li>Create a new offer C (For example, offer ID #18)
				<li>In offer A's rules settings, select "Internal rules" under Offer Registration Rules Webhook
				<li>Enter offer IDs in "Offer N Pick M", in this case enter "13, 17, 18"
				<li>Enter number of offer can be registered under "M Value", in this case enter 1
				<li>Repeat these steps for offer B and C with same values
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="{{ asset('js/common.js') }}?v=1"></script>
<script>
	function copyTextToClipboard(copyText)  {
		var textArea = document.createElement("textarea");

		textArea.style.position = 'fixed';
		textArea.style.top = 0;
		textArea.style.left = 0;
		textArea.style.width = '2em';
		textArea.style.height = '2em';
		textArea.style.padding = 0;
		textArea.style.border = 'none';
		textArea.style.outline = 'none';
		textArea.style.boxShadow = 'none';
		textArea.style.background = 'transparent';
		textArea.value = copyText;
		document.body.appendChild(textArea);
		textArea.select();

		try  {
			document.execCommand("copy");
		}  catch (error)  {
			console.log("Oops, unable to copy text...");
		}

		document.body.removeChild(textArea);
	}

	function copyURL(field)  {
		var copyText = $(field).val();
		copyTextToClipboard(copyText);
		alert("WhatsApp webhook URL has been copied.");
	}

	$(document).ready(function()  {
		$("#journeyWebhookURLButton").click(function()  {copyURL("#journeyWebhookURL");});
		$("#embbedWebhookURLButton").click(function()  {copyURL("#embbedWebhookURL");});
	});
</script>

@endsection
