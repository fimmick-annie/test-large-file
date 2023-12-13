<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		@include('campaigns/common/head')

		<link rel="stylesheet" href="{{ asset('offers/common/'.$offer->blade_folder.'/coupon.css') }}?v=5">
		<style>
			body  {
				background-color: #eeeeee;
			}

			#form  {
				margin-left: 5%;
				margin-right: 5%;
			}

			.coupon_qrcode  {
				padding-top: 2rem;
				padding-left: 20%;
				padding-right: 20%;
				text-align: center;
			}

			#warningText  {
				font-size: 14px;
				padding-top: 0.5rem;
				color: #707070;
			}

			.coupon__slide  {
				position: relative;
				width: 100%;
				height: 68px;
				max-width: 320px;
				margin: 0 auto 30px;
				border: 2px solid #dbdbdb;
				border-radius: 34px;
				-webkit-box-shadow: 0 0 3px rgba(0, 0, 0, 0.15) inset;
				box-shadow: 0 0 3px rgba(0, 0, 0, 0.15) inset;
				overflow: hidden;
			}
			.coupon__slide-btn  {
				position: absolute;
				top: 0;
				left: 0;
				z-index: 100000;
				width: 64px;
				height: 64px;
				display: -webkit-box;
				display: -ms-flexbox;
				display: flex;
				-webkit-box-align: center;
				-ms-flex-align: center;
				align-items: center;
				-webkit-box-pack: center;
				-ms-flex-pack: center;
				justify-content: center;
				background-color: #9a0000;
				border-radius: 50%;
			}
			.coupon__slide-btn::before  {
				content: '';
				display: block;
				width: 64px;
				height: 64px;
				background-image: url("{{ asset('offers/'.$offer->offer_name.'/coupon_button_lock.png') }}?v=4");
				background-size: cover;
				background-position: center;
				background-repeat: no-repeat;
			}
			.coupon__slide-text  {
				position: absolute;
				top: 50%;
				left: 0;
				width: 100%;
				color: #585858;
				background-color: #ffffff;
				font-size: 1.375rem;
				font-weight: bold;
				line-height: 1;
				text-align: center;
				-webkit-transform: translateY(-50%);
				-ms-transform: translateY(-50%);
				transform: translateY(-50%);
			}
			.coupon__slide-notice  {
				color: #9a0000;
				font-size: 1.125rem;
				font-weight: bold;
				line-height: 1.33;
				margin-bottom: 30px;
			}
			.coupon__progress  {
				position: absolute;
				top: 0;
				left: 0;
				z-index: 99999;
				height: 100%;
				background-color: #9a0000;
			}
		</style>
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
			analytics.load("{{ env('SEGMENT_ID') }}");
			analytics.page("{{ $offer->offer_name }}");
			}}();
		</script>

		<div class="wrapper">

			@include('campaigns/common/header')
			<div style="padding-top:46px;">
				<img src="{{ asset('offers/'.$offer->offer_name.'/coupon_countdown_kv.jpg') }}?v=4" alt="Coupon countdown key visual" />
			</div>

			<form id="form" class="form__redemption"><center>
				@csrf
				<input type="hidden" name="offerID" value="{{ $offer->id }}" />

				<div class="form__main" style="width:65%;">
					<div class="form__row">
						<label for="pickedRedemptionStoreCode" class="form__label">分店編碼</label>
						<input type="text" name="pickedRedemptionStoreCode" id="pickedRedemptionStoreCode" class="form__input">
						<p class="errormsg"></p>
					</div>
				</div>
				<div class="form__submitbox">
					<a id="submit" class="form__submit">
						<img src="{{ asset('offers/'.$offer->offer_name.'/coupon_submit_button.png') }}?v=1" alt="">
					</a>
				</div>

				<div id="warningText">注意：每張電子優惠券只可以使用一次，不可轉贈或轉移或借給他人使用</div>
			</center></form>
		</div>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js?v=4"></script>
		<script src="{{ asset('js/common.js') }}?v=4"></script>
		<script src="{{ asset('js/utils.js') }}?v=4"></script>
		<script type="text/javascript">
			var form = $('#form');
			var submit = $('#submit');
			var isMobile = isMobileFunc();

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
				}  else  {
					doSubmit();
				}
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
		</script>
	</body>
</html>