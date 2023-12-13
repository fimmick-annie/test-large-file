@extends('foso.layouts.default')

@section('page_title', 'Campaign Offer #'.$offer->id.' Rules')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.campaigns.html") }}'>Campaigns</a></li>
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.campaigns.offer.html") }}'>Offer</a></li>
<li><i class='fa fa-angle-right'></i> Rules</li>
@endsection

@section('content')

<ul class="nav nav-tabs" role="tablist">
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.settings.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-gear"></i></span> <span class="hidden-xs-down">Settings</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.resources.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-file-image-o"></i></span> <span class="hidden-xs-down">Resources</span></a> </li>
	<li class="nav-item"> <a class="nav-link active" href='{{ route("foso.campaigns.offer.rules.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-cubes"></i></span> <span class="hidden-xs-down">Rules</span></a> </li>
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
</ul>

<div class="card">

	<div class="card-body">
		<form id="form" name="form">
			@csrf

			<div class="row">
				<div class="col-lg-12">
					<fieldset class="form-group">
						<label for="offerRegistrationWebhookType">Offer Registration Rules Webhook</label>
						<select class="form-control" id="offerRegistrationWebhookType" name="offerRegistrationWebhookType">
							<option value="10">None</option>
							<option value="20">Internal rules</option>
							<option value="30">External rules webhook</option>
						</select>
						<small class="form-text small-text-color">When user submit form data, data will be pass to above webook URL and it will make decision for <font color="green"><u>approve</u></font> or <font color="red"><u>reject</u></font></small>
					</fieldset>
				</div>
			</div>

			<!--  Internal offer registration rules  -->
			<div class="row" id="offerRegistrationInternalRulesContainer" style="display:none;">
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="offerRegistrationNPickM">Offer N Pick M</label>
						<input class="form-control" type="text" value="{{ isset($offerRegistrationNPickM)?$offerRegistrationNPickM:'' }}" id="offerRegistrationNPickM" name="offerRegistrationNPickM" placeholder="1, 2, 3">
						<small class="form-text small-text-color">Please input offer ID, separate with comma</small>
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<label for="offerRegistrationM">M Value</label>
						<input class="form-control" type="text" value="{{ isset($offerRegistrationM)?$offerRegistrationM:'' }}" id="offerRegistrationM" name="offerRegistrationM" placeholder="1">
						<small class="form-text small-text-color">Number of offer</small>
					</fieldset>
				</div>
			</div>

			<!--  External offer registration webhook  -->
			<div class="row" id="offerRegistrationExternalRulesContainer" style="display:none;">
				<div class="col-lg-12">
					<fieldset class="form-group">
						<label for="offerRegistrationWebhookURL">Offer Registration Rules Webhook</label>
						<input class="form-control" type="text" value="{{ isset($offerRegistrationWebhookURL)?$offerRegistrationWebhookURL:'' }}" id="offerRegistrationWebhookURL" name="offerRegistrationWebhookURL" placeholder="{{ route('webhook.coupon.activation.json') }}">
					</fieldset>
				</div>
			</div>

			<hr>
			<div class="row">
				<div class="col-lg-12">
					<fieldset class="form-group">
						<label for="couponActivationWebhookType">Coupon Activation Rules Webhook</label>
						<select class="form-control" id="couponActivationWebhookType" name="couponActivationWebhookType">
							<option value="10">None</option>
							<option value="20">Internal rules</option>
							<option value="30">External rules webhook</option>
						</select>
						<small class="form-text small-text-color">When user visit coupon page, system will call above webhook URL and see if it can be <font color="green"><u>activated</u></font>.</small>
					</fieldset>
				</div>
			</div>

			<div class="row" id="couponActivationInternalRulesContainer" style="display:none;">
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="couponActivationReferralOfferID">Coupon Referral Offer ID</label>
						<input class="form-control" type="text" value="{{ isset($couponActivationReferralOfferID)?$couponActivationReferralOfferID:'' }}" id="couponActivationReferralOfferID" name="couponActivationReferralOfferID" placeholder="0">
					</fieldset>
				</div>
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="couponActivationReferralCount">Coupon Referral Count</label>
						<input class="form-control" type="text" value="{{ isset($couponActivationReferralCount)?$couponActivationReferralCount:'' }}" id="couponActivationReferralCount" name="couponActivationReferralCount" placeholder="0">
					</fieldset>
				</div>
				<div class="col-lg-12">
					<fieldset class="form-group">
						<label for="couponActivationMessage">Fulfillment Message</label>
						<textarea class="form-control" id="couponActivationMessage" name="couponActivationMessage" rows="3">{{ isset($couponActivationMessage)?$couponActivationMessage:'' }}</textarea>
						<small class="form-text small-text-color">The message will be sent out when user fulfill above requirement, it support below dynamic fields:</small>
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

			<div class="row" id="couponActivationExternalRulesContainer" style="display:none;">
				<div class="col-lg-12">
					<fieldset class="form-group">
						<label for="couponActivationWebhookURL">Coupon Activation Rules Webhook</label>
						<input class="form-control" type="text" value="{{ isset($couponActivationWebhookURL)?$couponActivationWebhookURL:'' }}" id="couponActivationWebhookURL" name="couponActivationWebhookURL" placeholder="{{ route('webhook.coupon.activation.json') }}">
					</fieldset>
				</div>
			</div>

			<hr>
			<button type="button" class="btn btn-danger" id="saveButton">Save</button>
		</form>
	</div>
</div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{ asset('js/common.js') }}?v=1"></script>

<script>
	function updateRegistrationWebhookInterface(webhookType)  {
		var value = parseInt(webhookType);
		$("#offerRegistrationWebhookType").val(value);
		switch (value)  {

			default:
			case 10:
				$("#offerRegistrationInternalRulesContainer").css('display', 'none');
				$("#offerRegistrationExternalRulesContainer").css('display', 'none');
				break;

			case 20:
				$("#offerRegistrationInternalRulesContainer").css('display', 'block');
				$("#offerRegistrationExternalRulesContainer").css('display', 'none');
				break;

			case 30:
				$("#offerRegistrationInternalRulesContainer").css('display', 'none');
				$("#offerRegistrationExternalRulesContainer").css('display', 'block');
				break;
		}
	}

	function updateActivationWebhookInterface(webhookType)  {
		var value = parseInt(webhookType);
		$("#couponActivationWebhookType").val(value);
		switch (value)  {

			default:
			case 10:
				$("#couponActivationInternalRulesContainer").css('display', 'none');
				$("#couponActivationExternalRulesContainer").css('display', 'none');
				break;

			case 20:
				$("#couponActivationInternalRulesContainer").css('display', 'block');
				$("#couponActivationExternalRulesContainer").css('display', 'none');
				break;

			case 30:
				$("#couponActivationInternalRulesContainer").css('display', 'none');
				$("#couponActivationExternalRulesContainer").css('display', 'block');
				break;
		}
	}

	$(document).ready(function()  {

		//  When leave page, show loading
		window.onbeforeunload = function(e)  {showLoading();};

		updateRegistrationWebhookInterface("{{ $offerRegistrationWebhookType }}");
		updateActivationWebhookInterface("{{ $couponActivationWebhookType }}");

		$("#offerRegistrationWebhookType").change(function()  {
			var value = parseInt($(this).val());
			updateRegistrationWebhookInterface(value);
		});

		$("#couponActivationWebhookType").change(function()  {
			var value = parseInt($(this).val());
			updateActivationWebhookInterface(value);
		});

		$("#saveButton").click(function()  {

			var basicRule = {
				rules:  {
// 					startDate:  {date:true},
// 					startTime:  {time:true},
// 					endDate:  {date:true},
// 					endTime:  {time:true},
// 					offerCode:  {minlength:6, alphanumeric:true},
// 					offerName:  {minlength:3, alphanumeric:true},
// 					offerTitle:  {minlength:3},
// 					quota:  {number:true},
				},
				messages: {
// 					offerCode:  {minlength:"Must consist of at least 6 characters"},
// 					offerName:  {minlength:"Must consist of at least 3 characters"},
// 					offerTitle:  {minlength:"Must consist of at least 3 characters"},
				}
			};

			var form = $("#form");
			form.validate(basicRule);

			result = form.valid();
			if (result == false)  {return;}

			//  Form OK
			var disabled = $("#form").find(":input:disabled").removeAttr("disabled");
			var formData = $("#form").serialize();
			disabled.attr("disabled", "disabled");

			showLoading();
			$.ajax({
				type: "POST",
				data: formData,
				dataType: "json",
				url: '{{ route("foso.campaigns.offer.rules.json", ["offer_code"=>$offerCode]) }}',
				success: function (result)  {

					alert(result.message);
					if (result.status != 0)  {
						hideLoading();
						return;
					}

					hideLoading();
				},
				error: function (XMLHttpRequest, textStatus, errorThrown)  {
					hideLoading();
					alert("Oops...\n#"+textStatus+": "+errorThrown);
				}
			});
		});

	});
</script>

@endsection
