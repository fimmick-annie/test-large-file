<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		@include('campaigns/common/head')

		<meta property="og:title" content="{!! nl2br($offer->offer_title) !!}" />
		<meta property="og:type" content="website" />
		<meta property="og:url" content="{{ route('campaign.offer.details.html', ['offer_code' => $offer->offer_code]) }}" />
		<meta property="og:image" content="{{ asset('offers/'.$offer->offer_name.'/offer_thumbnail.png') }}?v=2" />
		<meta property="og:description" content="{!! $description !!}" />

		<link rel="stylesheet" href="{{ asset('offers/common/'.$offer->blade_folder.'/offer_details.css') }}?v=1">
	</head>

	<body>
		@include('website/common/tracking_body')

@if (isset($gtm))
@foreach($gtm as $code)
@if ($code != "")

		<!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $code }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->
@endif
@endforeach
@endif

		<!--  Segment  -->
		<script>
			!function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","reset","group","track","ready","alias","debug","page","once","off","on","addSourceMiddleware","addIntegrationMiddleware","setAnonymousId","addDestinationMiddleware"];analytics.factory=function(e){return function(){var t=Array.prototype.slice.call(arguments);t.unshift(e);analytics.push(t);return analytics}};for(var e=0;e<analytics.methods.length;e++){var key=analytics.methods[e];analytics[key]=analytics.factory(key)}analytics.load=function(key,e){var t=document.createElement("script");t.type="text/javascript";t.async=!0;t.src="https://cdn.segment.com/analytics.js/v1/" + key + "/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(t,n);analytics._loadOptions=e};analytics._writeKey="W1yJ9TYonvWx2FlA6469eTyvX0xegGS3";analytics.SNIPPET_VERSION="4.13.2";
			analytics.load("{{ env('SEGMENT_ID') }}");
			analytics.page("{{ $offer->offer_code }}", {
				url: "{{ route('campaign.offer.details.html', ['offer_code'=>$offer->offer_code]) }}",
				offer_code: "{{ $offer->offer_code }}",
				offer_name: "{{ $offer->offer_name }}",
				offer_title: "{{ $offer->offer_title }}",
				offer_subtitle: "{{ $offer->offer_subtitle }}",
				ip: "{{ $ipAddress }}",
				userAgent: "{{ $userAgent }}",
			});
			}}();
		</script>

		<div class="wrapper">

			@include('campaigns/common/header')
			<header class="header">
				<img src="{{ asset('offers/common/header.jpg') }}?v=1" alt="Header" />
			</header>

			<form action="" id="form" class="form">
				@csrf

				<div class="coupon_link_header">請根據以下指示輸入，找回換領連結</div>
				<div class="form__main">
					<label for="mobile" class="form__label" style="margin-top:1rem">您曾登記的接收 WhatsApp 電話號碼</label>
					<div class="form__row" style="margin-top:0">
						<div class="form__phonearea">
							<select name="areaCode" id="areaCode" class="form__select">
								<option value="+852">+852</option>
								<option value="+853">+853</option>
							</select>
							<p class="errormsg"></p>
						</div>
						<div class="form__phone">
							<label for="mobile" style="display:none;">電話號碼</label>
							<input type="tel" name="mobile" id="mobile" class="form__input" maxlength="8">
							<p class="errormsg"></p>
						</div>
					</div>
				</div>

				<div id="errorMessage" class="coupon_link_error"></div>

				<div class="form__submitbox">
					<center><div>
						<a id="submit" class="form__submit">
							<img src="{{ asset('offers/'.$offer->offer_name.'/button_submit.png') }}?v=1" alt="">
						</a>
					</div></center>
				</div>
			</form>
		</div>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js?v=1"></script>
		<script src="{{ asset('js/common.js') }}?v=1"></script>
		<script src="{{ asset('js/utils.js') }}?v=1"></script>

		<script type="text/javascript">
			var form = $('#form');

			$('#submit').on('click', handleSubmit);

			function handleSubmit()  {
				var inputs = form.find('input[type="text"], input[type="tel"]');
				var errorArr = [];

				$.each(inputs, function() {
					var thisValue = $(this).val();
					var thisLabel = $(this).siblings('label').text();

					if (!utils.validate.validateEmpty(thisValue)) {
						errorArr.push({
							targetId: $(this).prop('id'),
							errorMsg: `請輸入${thisLabel}`
						});
						return;
					}

					if ($(this).prop('type') === 'tel') {
						switch($(this).closest('.form__row').find('select').val()) {

							case '+852':
								if (!utils.validate.validatePhoneHK(thisValue)) {
									errorArr.push({
										targetId: $(this).prop('id'),
										errorMsg: `電話格式錯誤`
									});
								}
								break;

							case '+853':
								if (!utils.validate.validatePhoneMacao(thisValue)) {
									errorArr.push({
										targetId: $(this).prop('id'),
										errorMsg: `電話格式錯誤`
									});
								}
								break;
						}
					}
				});

				if (errorArr.length !== 0) {
					utils.validate.error_checker(form, errorArr);
				} else {

					$('.errormsg').text('');
					$("#errorMessage").css("display", "none");
					showLoading();

					//  Form valid, submit now
					var formData = $("#form").serialize();
					$.ajax({
						type: "POST",
						data: formData,
						dataType: "json",
						url: "{{ route('campaign.offer.coupon.link.json', ['offer_code'=>$offer->offer_code]) }}",
						success: function (result)  {

							switch (result.status)  {
								case 0:  {
									location.href = result.couponLink;
									return;
								}

								case -20:  {
									$("#errorMessage").text("系統沒有這電話號碼紀錄，請輸入已登記的 WhatsApp 電話號碼作查找");
									$("#errorMessage").css("display", "block");
								}  break;

								case -30:  {
									$("#errorMessage").text("此電話號碼己達當日找回上限，請在明天重新輸入已登記的電話號碼");
									$("#errorMessage").css("display", "block");
								}  break;

								default:  {
									alert(result.message);
								}  break;
							}
							if (result.status < 0)  {hideLoading();}
						},
						error: function (XMLHttpRequest, textStatus, errorThrown)  {
							hideLoading();
							alert("Oops...\n#"+textStatus+": "+errorThrown);
						}
					});
				}
			}
		</script>

	</body>
</html>
