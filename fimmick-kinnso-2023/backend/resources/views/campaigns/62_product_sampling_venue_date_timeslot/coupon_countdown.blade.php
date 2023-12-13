<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<!--  Fix IE JS problem  -->
		<script src="https://unpkg.com/core-js-bundle@3.15.2/minified.js"></script>
		@include('campaigns/common/head')

		<link rel="stylesheet" href="{{ asset('offers/common/'.$offer->blade_folder.'/coupon.css') }}?v=4">

		<link rel="stylesheet" href="{{ asset('offers/common/'.$offer->blade_folder.'/offer_details.css') }}?v=3">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/css/splide.min.css">
		@include('campaigns/components/css/offer_card')

		<style>
			:root {
				--theme-primary-color: {{ $offer->ini['settings']['theme_primary_color'] ?? '#FCCC08' }};
				--theme-secondary-color: {{ $offer->ini['settings']['theme_secondary_color'] ?? '#FFFAE6' }};
				--theme-header-color: {{ $offer->ini['settings']['theme_header_color'] ?? '#F37621' }};
			}

			body{
				background-color: #eeeeee;
				overflow-x: hidden!important;
				background-image: url("{{ asset('offers/common/background_60_desktop.svg') }}?v=1");
				background-position: bottom 13% right 50% ;
				background-size: 60%;
				background-repeat: no-repeat;
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
	
			.wrapper {
				max-width: 1200px;
			}
		
			.inputLabel  {
				display: inline-block;
				margin-bottom: 0.5rem;
				color: red;
				font-weight: bold;
				padding-right: 0px;
				padding-left: 0px;
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
			@media (max-width: 767px) {
				body{
					background-image: url("{{ asset('offers/common/background_60_mobile.svg') }}?v=1");
					background-position: bottom 10% right 50% ;
					background-size: 98%;
				}

				.wrapper{
					height: 100vh;
				}
			}
			@media (max-width: 1365px)  {
				.logo__desktop  {display: none;}
			}
			@media (min-width: 1366px) {
				.logo__mobile  {display: none;}
			}

			.form__input{
                background-color: #fdfae6;
                border-width: 0px 0 0px;
				max-width: 400px;
				display:block;
            }

		</style>
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
			analytics.page("{{ $offer->offer_name }}");
			}}();
		</script>

		<div class="wrapper">

			@include('campaigns/common/header')

			<style>
				#offer-image-splide .splide__pagination {
					bottom: 4%;
				}
				#offer-image-splide .splide__pagination__page {
					background: #fff!important;
					border-radius: 4px;
					box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
					transition: width .5s;
				}
				#offer-image-splide .splide__pagination__page.is-active {
					width: 36px;
					transform: scale(1);
				}
				.offer-image {
					padding-top: 49%;
					background-image: var(--background-image);
					background-repeat: no-repeat;
					background-position: center center;
					background-size: cover;
				}
				.title {
					margin-bottom: 24px;
					font: normal normal bold 21px/29px Noto Sans;
					letter-spacing: 2.1px;
					text-align: center;
					color: var(--theme-header-color);
				}
				@media (min-width: 1200px) {
					#offer-image-splide {
						width: 100vw;
						transform: translateX(calc((100vw - 1200px) / 2 * -1));
					}
				}
				@media (min-width: 767px) {
					#offer-image-splide li.splide__slide {
						max-width: 1200px;
						margin-right: 38px;
						overflow: hidden;
					}
					.offer-image {
						padding-top: 34.3%;
						background: initial;
					}
					.offer-image::before {
						content: ' ';
						position: absolute;
						left: 0; top: 0;
						width: 100%; height: 100%;
						background-image: var(--background-image);
						background-repeat: no-repeat;
						background-position: center center;
						background-size: cover;
						filter: blur(30px);
						display: block;
					}
					.offer-image > div {
						position: absolute;
						left: 50%;
						top: 0;
						width: 70%;
						z-index: 1;
						transform: translateX(-50%);
					}
					.offer-image > div::before {
						content: ' ';
						padding-top: 49%;
						background-image: var(--background-image);
						background-repeat: no-repeat;
						background-position: center center;
						background-size: cover;
						display: block;
					}
					.title {
						font: normal normal bold 28px/38px Noto Sans;
						letter-spacing: 2.8px;
					}
					.form__row{
						display: flex;
						flex-wrap: nowrap;
						flex-direction: column;
						align-items: center;
					}
				}
			</style>

			<div style="padding-top:70px;">
				<div id="offer-image-splide" class="splide">
					<div class="splide__track">
						<ul class="splide__list">
							@for($i = 1; $i <= 10; $i++)
								@if(file_exists(public_path('offers/'.$offer->offer_name.'/offer_details_kv_'.str_pad($i, 2, '0', STR_PAD_LEFT).'.jpg')))
							<li class="splide__slide">
								<div class="offer-image" style="--background-image:url('{{ asset('offers/'.$offer->offer_name.'/offer_details_kv_'.str_pad($i, 2, '0', STR_PAD_LEFT).'.jpg') }}')"><div></div></div>
							</li>
								@endif
							@endfor
						</ul>
					</div>
				</div>
			</div>

		
			<form id="form" class="form__redemption" onsubmit="return handleSubmit(event)"><center>
				@csrf
				<input type="hidden" name="offerID" value="{{ $offer->id }}" />
				
				<div class="form__main" style="width:65%;">

					<div class="form__row">
						<p class="title">{{$offer->offer_title}}</p>
					</div>

					<div class="form__row">
						<input type="text" name="pickedRedemptionStoreCode" id="pickedRedemptionStoreCode" class="form__input" >
						<label for="pickedRedemptionStoreCode" class="inputLabel">分店編碼(由店員填寫)</label>
						<p class="errormsg"></p>
					</div>

				</div>
				<br><br>
				<div class="form__submitbox">
					<a id="submit" class="form__submit">
						<img src="{{ asset('offers/'.$offer->offer_name.'/coupon_submit_button.png') }}?v=1" alt="" style="max-width: 200px; width: 65%;">
					</a>
				</div>

				<div id="warningText">注意：用戶只限換領乙次，數量有限，送完即止，不得退換或兌換現金。</div>
			</center></form>
		
		</div>
		
		@include('campaigns/common/footer')

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js?v=4"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
		<script src="{{ asset('assets/vendor/splide/splide.min.js') }}"></script>
		<script src="{{ asset('js/common.js') }}?v=4"></script>
		<script src="{{ asset('js/utils.js') }}?v=2"></script>

		<script>
			(function(window, document, undefined) {
				const offerImageSlide = new Splide('#offer-image-splide', {
											type: 'loop',
											autoplay: true,
											arrows: false,
											focus: 'center'
										});
				offerImageSlide.mount();

			})(window, document, undefined);
		</script>
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
