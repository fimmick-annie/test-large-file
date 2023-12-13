<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		@include('campaigns/common/head')

		<link rel="stylesheet" href="{{ asset('offers/common/'.$offer->blade_folder.'/coupon.css') }}?v=4">
		<style>
			body  {
				background-color: #eeeeee;
			}

			.header  {
				position: fixed;
				top: 0;
				left: 0;
				width: 100%;
				z-index: 1500;
				display: -webkit-box;
				display: -ms-flexbox;
				display: flex;
				-ms-flex-wrap: wrap;
				flex-wrap: wrap;
				-webkit-box-align: center;
				-ms-flex-align: center;
				align-items: center;
				-webkit-box-pack: justify;
				-ms-flex-pack: justify;
				justify-content: space-between;
				padding: 35px 30px;
				background-color: #3d444e;
				-webkit-transition: background-color .3s ease;
				-o-transition: background-color .3s ease;
				transition: background-color .3s ease;

				-webkit-box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
				box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
			}
			.header__landing  {
				background-color: #ffffff;
			}
			.logo  {
				position: absolute;
				left: 50%;
				top: 50%;
				width: 100%;
				max-width: 100px;
				display: block;
				-webkit-transform: translate(-50%, -50%);
				-ms-transform: translate(-50%, -50%);
				transform: translate(-50%, -50%);
			}
			.coupon_qrcode  {
				padding-top: 2rem;
				padding-left: 20%;
				padding-right: 20%;
				text-align: center;
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

			#form  {
				margin-left: 5%;
				margin-right: 5%;
			}
			#warningText  {
				font-size: 14px;
				padding-top: 0.5rem;
				color: #707070;
			}

			@media (max-width: 1365px)  {
				.logo__desktop  {display: none;}
			}
			@media (min-width: 1366px) {
				.logo__mobile  {display: none;}
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
			analytics.load("W1yJ9TYonvWx2FlA6469eTyvX0xegGS3");
			analytics.page();
			}}();
		</script>

		<div class="wrapper">
			@include('campaigns/common/header')

			<div class="heroImage">
				<img src="{{ asset('offers/'.$offer->offer_name.'/coupon_countdown_kv.jpg') }}?v=4" alt="Coupon countdown key visual" />
			</div>

			<form id="form" class="form__redemption"><center>
				@csrf
				<input type="hidden" name="offerID" value="{{ $offer->id }}" />
				<input type="hidden" name="pickedRedemptionStoreCode" id="pickedRedemptionStoreCode" value="demo-store" />

				<div class="coupon__slide">
					<a class="coupon__slide-btn"></a>
					<div class="coupon__progress"></div>
					<p class="coupon__slide-text"><img src="{{ asset('offers/'.$offer->offer_name.'/coupon_slide_bar.gif') }}?v=1" /></p>
				</div>

				<div id="warningText">注意：每張電子優惠券只可以使用一次，不可轉贈或轉移或借給他人使用</div>
			</center></form>

			@include('campaigns/common/footer')
		</div>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js?v=4"></script>
		<script src="{{ asset('js/common.js') }}?v=4"></script>
		<script src="{{ asset('js/utils.js') }}?v=4"></script>
		<script type="text/javascript">
			var slideBtn = $('.coupon__slide-btn');
			var slideBox = $('.coupon__slide');
			var confirmProgress = $('.coupon__progress');
			var isMobile = isMobileFunc();

			function isMobileFunc() {
				return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
			}

			if(isMobile) {
				document.querySelector('.coupon__slide-btn').addEventListener('touchstart', mousedownHandler);
			} else {
				slideBtn.on('mousedown', null, mousedownHandler);
			}

			function mousedownHandler(ev) {
				if (isMobile) {
					downX = ev.changedTouches[0].clientX;
					window.addEventListener('touchmove', mousemoveHandler);
					window.addEventListener('touchend', mouseupHandler);
				} else {
					downX = ev.clientX;
					$(document).on('mousemove', null, mousemoveHandler);
					$(document).on('mouseup', null, mouseupHandler);
				}
			}

			function mouseupHandler(ev) {
				if (!isSuccess) {
					confirmProgress.animate({
						width: 32
					}, 100);
					slideBtn.animate({
						left: 0
					}, 100);
				} else {
					success();
				}
				if (isMobile) {
					window.removeEventListener('touchmove', mousemoveHandler);
					window.removeEventListener('touchend', mouseupHandler);
				} else {
					$(document).off('mousemove', null, mousemoveHandler);
					$(document).off('mouseup', null, mouseupHandler);
				}
			}

			function mousemoveHandler(ev) {
				ev.preventDefault();
				if (isMobile) {
					var moveX = ev.changedTouches[0].clientX;
				} else {
					var moveX = ev.clientX;
				}
				var diffX = getLimitNumber(moveX - downX, 0, slideBox.width() - slideBtn.width());
				confirmProgress.width(diffX + 32);
				slideBtn.css({
					left: diffX
				});
				if (diffX === slideBox.width() - slideBtn.width()) {
					isSuccess = true;
				} else {
					isSuccess = false;
				}
			}

			function getLimitNumber(num, min, max) {
				if (num > max) {
					num = max;
				} else if (num < min) {
					num = min;
				}
				return num;
			}

			function success() {

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
