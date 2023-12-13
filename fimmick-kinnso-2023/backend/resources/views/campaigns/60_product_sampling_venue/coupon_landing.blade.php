<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		@include('campaigns/common/head')

		<link rel="stylesheet" href="{{ asset('offers/common/'.$offer->blade_folder.'/coupon.css') }}?v=1">
	</head>

	<body>
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
			analytics.load("W1yJ9TYonvWx2FlA6469eTyvX0xegGS3");
			analytics.page();
			}}();
		</script>

{{--	<div class="wrapper">

			@include('campaigns/common/header')
			<header class="header">
				<img src="{{ asset('offers/common/header.jpg') }}?v=1" alt="Header" />
			</header>

		</div>
--}}

		<div class="wrapper">

			@include('campaigns/common/header')
			<div style="padding-top:46px; text-align:center;">
				<img src="{{ asset('offers/'.$offer->offer_name.'/coupon_countdown_kv.jpg') }}?v=4" alt="Coupon countdown key visual" />
			</div>

			<form id="form" class="form__redemption" onsubmit="return handleSubmit(event)"><center>
				@csrf
				<input type="hidden" name="offerID" value="{{ $offer->id }}" />

				<div class="form__main" style="width:65%;">
					<div class="form__row">
						<label for="pickedRedemptionStoreCode" class="inputLabel">分店編碼（由店員填寫）</label>
						<input type="text" name="pickedRedemptionStoreCode" id="pickedRedemptionStoreCode" class="form__input">
						<p class="errormsg"></p>
					</div>
				</div>
				<div class="form__submitbox">
					<a id="submit" class="form__submit">
						<img src="{{ asset('offers/'.$offer->offer_name.'/coupon_submit_button.png') }}?v=1" alt="">
					</a>
				</div>

				<div id="warningText">注意：用戶只限換領乙次，數量有限，送完即止，不得退換或兌換現金。</div>
			</center></form>
		</div>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js?v=4"></script>
		<script src="{{ asset('js/common.js') }}?v=4"></script>
		<script src="{{ asset('js/utils.js') }}?v=4"></script>
		<script type="text/javascript">
			$(document).ready(function()  {

				var form = $('#form');
				var submit = $('#submit');
				var isMobile = isMobileFunc();

				form.submit(function(e)  {
					e.preventDefault();
					return handleSubmit();
				});

				function isMobileFunc() {
					return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
				}

				submit.on('click', handleSubmit);
				function handleSubmit()  {

					var inputs = form.find('input[type="text"]');
					var customerEmail = form.find('input[type="email"]');
					var errorArr = [];

					$.each(inputs, function() {
						var thisValue = $(this).val();
						var thisLabel = $(this).siblings('label').text();

						if (!utils.validate.validateEmpty(thisValue)) {
							errorArr.push({
								targetId: $(this).prop('id'),
								errorMsg: `請輸入${thisLabel}`
							});
						}
					});

					$.each(customerEmail, function() {
						var thisValue = $(this).val();
						var thisLabel = $(this).siblings('label').text();

						if (!utils.validate.validateEmail(thisValue)) {
							errorArr.push({
								targetId: $(this).prop('id'),
								errorMsg: '格式錯誤'
							});
						}
					});

					if (errorArr.length !== 0) {
						utils.validate.error_checker(form, errorArr);
						return false;
					}  else  {
						doSubmit();
						return true;
					}
					return false;
				}

				//----------------------------------------------------------------------------------------
				function doSubmit()  {

					//  AJAX
					showLoading();

					//  Form valid, submit now
					var formData = $("#form").serialize();
					$.ajax({
						type: "POST",
						data: formData,
						dataType: "json",
						url: "{{ route('campaign.coupon.void.json', ['unique_code'=>$uniqueCode]) }}",
						async: true,
						headers:  {"cache-control": "no-cache"},
						success: function (result)  {

							switch (result.status)  {
								case 0:  {
									location.href = "{{ route('campaign.coupon.thankyou.html', ['unique_code'=>$uniqueCode]) }}";
									return;
								}

								case -1:  alert("優惠還未開始");  break;
								case -5:  alert("優惠已經結束");  break;
								case -10:  alert("優惠不能重複申請");  break;
								case -20:  alert('分店編碼無效');  break;

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
				};

			});
		</script>




	</body>
</html>
