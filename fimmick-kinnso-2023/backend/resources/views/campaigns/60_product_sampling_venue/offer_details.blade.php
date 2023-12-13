<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<!--  Fix IE JS problem  -->
		<script src="https://unpkg.com/core-js-bundle@3.15.2/minified.js"></script>

		@include('campaigns/common/head')

		<meta property="og:title" content="{!! nl2br($offer->offer_title) !!}" />
		<meta property="og:type" content="website" />
		<meta property="og:url" content="{{ route('campaign.offer.details.html', ['offer_code' => $offer->offer_code]) }}" />
		<meta property="og:image" content="{{ asset('offers/'.$offer->offer_name.'/offer_thumbnail.png') }}?v=2" />
		<meta property="og:description" content="{!! $sharingMessage !!}" />

		<link rel="stylesheet" href="{{ asset('offers/common/'.$offer->blade_folder.'/offer_details.css') }}?v=3">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/css/splide.min.css">
		@include('campaigns/components/css/offer_card')
		<style>
			:root {
				--theme-primary-color: {{ $offer->ini['settings']['theme_primary_color'] ?? '#FCCC08' }};
				--theme-secondary-color: {{ $offer->ini['settings']['theme_secondary_color'] ?? '#FFFAE6' }};
				--theme-header-color: {{ $offer->ini['settings']['theme_header_color'] ?? '#F37621' }};
			}
			#termsWarning  {
				text-align: center;
				color: #ff0000;
				display: none;
			}
			@media (max-width: 767px) {
				#termsWarning  {
					font-size: 13px;
				}
			}
			/* #submitButton  {
				opacity: 0.5;
			} */

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
			.form  {
				padding-left: 0px !important;
				padding-right: 0px !important;
				margin-left: 4%;
				margin-right: 4%;
			}
			.offer__term  {
				margin-bottom: 10px;
				color: #57524F;
			}
			.offer__term-btn  {
				color: {{ $offer->ini['settings']['theme_button_text_color'] }};
				border: 1px solid {{ $offer->ini['settings']['theme_button_color'] }};
				border-radius: 30px;
				line-height: 23px;
				font-size: 16px;
				font-family: Noto Sans;
				text-decoration: none;
			}
			.offer__term-btn::before  {
				background-color: {{ $offer->ini['settings']['theme_button_text_color'] }} !important;
				display: none;
			}
			.offer__term-btn::after  {
				/* background-color: {{ $offer->ini['settings']['theme_button_text_color'] }} !important; */
				background-color: transparent!important;
				right: 25px;
				top: 50%;
				width: 12px;
				height: 12px;
				border: 2px solid {{ $offer->ini['settings']['theme_button_text_color'] }};
				border-top: 0;
				border-right: 0;
				transform: translateY(-75%) rotateZ(-45deg);
				transition: transform .5s;
			}
			.offer__term--open .offer__term-btn::after  {
				transform: translateY(-25%) rotateZ(-45deg) rotateX(-180deg) rotateY(180deg);
			}
			.offer__term a  {
				color: #57524F;
			}
			.offer__term a:hover  {
				color: #57524F;
				/* color: {{ $offer->ini['settings']['theme_button_hover_color'] }}; */
			}
			.offer__term_text  {
				color: #57524F;
				font-size: 14px;
			}
			.form__select{
                background-color: #fdfae6;
                border-width: 0px 0 0px;
				overflow:scroll;
            }
			.form__label{
				text-align: left;
				color: #57524F;
                font: normal normal bold 24px/33px Noto Sans;
    			letter-spacing: 1.4px;
			}
			label  {
				cursor: pointer;
				display: inline-block;
				position: relative;
				padding-left: 0px;
				margin-right: 0px;
				padding-top: 10px;
				padding-bottom: 10px;
			}
			label:before  {
				/* content: ""; */
				width: 15px;
				height: 15px;
				position: absolute;
				left: 0;
			}
			input[type=checkbox] {
				display: none !important;
			}
			.checkbox label:before  {
				background: url("{{ asset('offers/common/checkbox.png') }}?v=2") left top no-repeat;
				background-size: 100%;
				height: 20px;
				width: 20px;
			}

			input[type=checkbox]:checked + label:before  {
				background: url("{{ asset('offers/common/checkbox.png') }}?v=2") left bottom no-repeat;
				background-size: 100%;
				height: 20px;
				width: 20px;
			}

			@media only screen and (max-width:640px)  {
				label  {
					/* padding-left: 30px; */
				}
				label:before  {
					width: 15px;
					height: 15px;
				}
				.checkbox label:before  {
					height: 20px;
					width: 20px;
				}

				input[type=checkbox]:checked + label:before  {
					height: 20px;
					width: 20px;
				}
			}
			@media (max-width: 1365px)  {
				.logo__desktop  {display: none;}
			}
			@media (min-width: 1366px) {
				.logo__mobile  {display: none;}
			}
			.subtitle {
				padding: 0 6px;
				margin: 0 auto 24px auto;
				font: normal normal bold 21px/29px Noto Sans;
				letter-spacing: 2.1px;
				border-bottom: 2px solid var(--theme-primary-color);
				display: inline-block;
			}
			@media (min-width: 767px) {
				.subtitle {
					padding: 0;
					margin: 0 20px;
					font: normal normal bold 24px/33px Noto Sans;
					letter-spacing: 2.4px;
					text-align: left;
					border-bottom: 5px solid var(--theme-primary-color);
					color: #57524F;
				}
				.item-box-grid {
					margin-bottom: 80px;
					display: grid;
					grid-template-columns: repeat(5, 1fr);
					grid-gap: 24px;
				}
				.item-box .form__full {
					display: flex;
					flex-direction: column;
				}
				.item-box .box-title .offer__term-btn {
					background: transparent;
					padding: 0;
					margin: 0 20px;
					font: normal normal bold 24px/33px Noto Sans;
					letter-spacing: 2.4px;
					border: 0;
					border-bottom: 5px solid var(--theme-primary-color);
					border-radius: 0;
					color: #57524F;
					display: inline-block;
				}
				.item-box .box-title .offer__term-btn:hover {
					color: #57524F;
				}
				.item-box .box-title .offer__term-btn::after {
					display: none;
				}
				.item-box .box-content {
					padding: 24px 16px;
					border-radius: 20px;
				}
				.item-box .box-content {
					display: block!important;
				}
				.item-box .box-content img {
					max-width: 80%;
					max-height: 600px;
				}
				.item-box .box-content ul li {
					line-height: 30px;
				}
			}
		</style>
		<style>
			body {
				overflow-x: hidden!important;
			}
			.wrapper {
				max-width: 1200px;
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
				let parameter = {
					url: "{{ route('campaign.offer.details.html', ['offer_code'=>$offer->offer_code]) }}",
					offer_code: "{{ $offer->offer_code }}",
					offer_name: "{{ $offer->offer_name }}",
					offer_title: "{{ $offer->offer_title }}",
					offer_subtitle: "{{ $offer->offer_subtitle }}",
					ip: "{{ $ipAddress }}",
					userAgent: "{{ $userAgent }}",
					os: "{{ $operatingSystem }}",
					device: "{{ $device }}",
				};

				var url = window.location.href.slice(window.location.href.indexOf('?')+1).split('&');
				for (var i=0; i<url.length; i++)  {
					var parameterArray = url[i].split('=');
					if (parameterArray[0] == "fbclid" || parameterArray[0] == "gclid")  {
						parameter[parameterArray[0]] = parameterArray[1];
					}
				}

				analytics.load("{{ env('SEGMENT_ID') }}");
				analytics.page("{{ $offer->offer_code }}", parameter);
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

			<form action="" id="form" class="form">
				@csrf

				<input type="hidden" name="selectedChannel" value="{{ $selectedChannel }}" />
				<input type="hidden" name="confirmationMethod" value="whatsapp" />
				
				<div class="form__main">

					<style>
						/* for alert box style */
						.notify{  
							position:fixed;
							top:68px;
							width:100%;
							height:0;
							box-sizing:border-box;
							color:white;  
							text-align:center;
							background:rgba(255,175,25,0.7);
							overflow:hidden;
							box-sizing:border-box;
							transition: height .3s;
						}

						#notifyType:before{
							display:block;
							margin-top:15px; 
						}

						.active{  
							height:50px;
						}

						.copylink:before{
							Content:"已複製結鏈";
						}

						.sharelink:before{
							Content:"結鏈分享!";
						}
						/* End------------for alert box style */

						.subject {
							margin-bottom: 24px;
							font: normal normal bold 21px/29px Noto Sans;
							letter-spacing: 2.1px;
							text-align: center;
							color: var(--theme-header-color);
						}
						@media (min-width: 767px) {
							.subject {
								font: normal normal bold 28px/38px Noto Sans;
								letter-spacing: 2.8px;
							}
						}
						.first-paragraph {
							position: relative;
							color: #57524F;
						}
						.first-paragraph .read-more {
							position: absolute;
							bottom: 0;
							right: 0;
							padding-left: 30px;
							background: linear-gradient(to right, transparent, #FFF 20%);
							color: var(--theme-header-color);
						}
						.first-paragraph .read-more::after {
							content: attr(data-text);
						}
						.remaining-content {
							display: none;
						}
						@media (min-width: 767px) {
							.description-wrapper {
								padding: 20px;
							}
						}

						.shopSelect{
							position: relative;
						}
						.shopSelect #selectedRedemptionStore-error{
							position: absolute;
							color: red;
							left: 105px;
                            top: 5px;		
						}
		
					</style>
					<div class="form__row description-wrapper">
						<div class="form__full">
							{{--
							<div class='cursor-pointer d-block d-md-none' style="float:right; width:48px;">
								<img class="shareBtn" data-title='{!! nl2br($offer->offer_title) !!}' data-href="{{ route('campaign.offer.details.html', ['offer_code' => $offer->offer_code]) }}" src='/website/offer-listing/share_0623_v1@2x.png'>
							</div>
							--}}
							<div style="overflow:hidden;">
								<div class="d-flex flex-row-reverse" style="padding-top:5px;">
									<img id="sharepage_share" src="{{asset('website/sharebtn/button_share.png')}}" style="width: 50px; padding: 10px;">
									<!-- <img id="sharepage_fb" src="{{asset('website/sharebtn/button_facebook.png')}}" style="width: 50px; padding: 10px;">
									<img id="sharepage_cpy" src="{{asset('website/sharebtn/button_copy_link.png')}}" style="width: 50px; padding: 10px;"> -->
								</div>
								<div class="subject">
{{ $offer->ini['offer_details']['subject'] ?? '' }}
								</div>
								<div class="first-paragraph">
{!! $description !!}
									@if(isset($readMoreParagraph) and !empty($readMoreParagraph))
									<div class="read-more" data-text="閱讀更多…"></div>
									@endif
								</div>
								@if(isset($readMoreParagraph) and !empty($readMoreParagraph))
								<div class="remaining-content">
{!! $readMoreParagraph ?? '' !!}
								</div>
								@endif
							</div>
						</div>
					</div>

					<style>
						.highlight-paragraph-wrapper {
							text-align: center;
						}
						.highlight-paragraph  {
							color: #57524F;
						}
						@media (min-width: 767px) {
							.highlight-paragraph-wrapper .highlight-paragraph {
								padding: 20px;
							}
						}
					</style>
					<div class="form__row highlight-paragraph-wrapper">
						<div class="form__full">
							<div style="overflow:hidden;">
								<div class="subtitle">
{{ $offer->ini['offer_details']['highlight_subject'] ?? '' }}
								</div>
								<div class="highlight-paragraph">
{!! $highlightParagraph ?? '' !!} 
								</div>
							</div>
						</div>
					</div>

					<!-- selecting venue of offer  -->
					<div class="form__row">
                        <div class="form__full shopSelect">
                            <label for="selectedRedemptionStore" class="form__label" >換領店鋪</label>
                            <select name="selectedRedemptionStore" id="selectedRedemptionStore" class="form__select" style="max-height: 160px;overflow: auto;">
                                <option value="">請選擇</option>
@foreach ($storeArray as $store)
@if ($store->have_quota > 0)
                                <option value="{{ $store->store_name }}">{{ $store->store_name }}</option>
@else
                                <option value="" disabled>{{ $store->store_name }} (額滿)</option>
@endif
@endforeach
                            </select>
                        </div>
                    </div>

					<input type="hidden" name="selectedRedemptionPeriodID" id="selectedRedemptionPeriodID" value="" class="form__select">

					<div class="item-box-grid">

					<style>
						@media (min-width: 767px) {
							.terms-item-box {
								grid-column-start: 1;
								grid-column-end: 6;
								grid-row-start: 2;
							}
						}
						.offer__term-hidden ul li  {
							color: #57524F;
						}
					</style>
					<div class="form__row item-box terms-item-box">
						<div class="form__full">
							<div class="offer__term" >
								<div class="box-title">
									<a class="offer__term-btn" id="offerTermButton">優惠細則及條款</a>
								</div>
								<div class="offer__term-hidden offer__term_text box-content">
{!! $offer->tnc !!}
								</div>
							</div>
						</div>
					</div>
					
					<style>
						@media (min-width: 767px) {
							.instruction-item-box {
								grid-column-start: 1;
								grid-column-end: 6;
								grid-row-start: 3;
							}
						}
					</style>
					<div class="form__row item-box instruction-item-box">
						<div class="form__full">
							<div class="offer__term" >
								<div class="box-title">
									<a class="offer__term-btn" id="offerHowButton">如何領取著數</a>
								</div>
								<div class="offer__term-hidden offer__term_text box-content">
									<img src="{{ asset('offers/'.$offer->offer_name.'/offer_whatsapp_instruction.png') }}?v=2" alt="WhatsApp instruction" />
								</div>
							</div>
						</div>
					</div>

					</div>
				</div>
			</form>

			<style>
				.may-also-like-wrapper {
					padding: 60px 0;
					background: var(--theme-secondary-color);
				}
				@media (max-width: 767px) {
					.may-also-like-wrapper {
						padding: 16px 0 30px 0;
					}
				}
				.may-also-like-inner-wrapper {
					margin: 0 4%;
				}
				@media (min-width: 1200px)  {
					.may-also-like-wrapper {
						width: 100vw;
						transform: translateX(calc((100vw - 1200px) / 2 * -1));
						display: flex;
					}
					.may-also-like-inner-wrapper {
						width: 100%;
						max-width: 1200px;
						margin: 0 auto;
					}
				}
				.may-also-like-wrapper .section-title {
					margin: 12px 0;
					color: var(--theme-header-color);
					font-family: Noto Sans;
					font-size: 21px;
					font-weight: bold;
					line-height: 29px;
				}
				.may-also-like-wrapper .section-title::after {
					content: attr(data-text);
				}
				#may-also-like-splide .splide__slide {
					padding-right: 16px;
				}
				.may-also-like-wrapper .offer-card-wrapper {
					height: calc(100% - 12px);
					margin: 6px 0;
				}
				.may-also-like-wrapper .offer-card {
					width: 210px;
					background: #FFF;
				}
				.may-also-like-wrapper .offer-card .image::before {
					padding-top: 53.96%;
				}
			</style>
			<div class="may-also-like-wrapper">
				<div class="may-also-like-inner-wrapper">
					<div class="section-title" data-text="你可能還喜歡..."></div>
					<div id="may-also-like-splide" class="splide">
						<div class="splide__track">
							<ul class="splide__list"></ul>
						</div>
					</div>
				</div>
			</div>

			<style>
				.bottom-outer-wrapper {
					position: sticky;
					bottom: 0;
					left: 0;
					width: 100%;
					padding-bottom: 10px;
					background: var(--theme-primary-color);
					z-index: 2;
				}
				@media (min-width: 1200px)  {
					.bottom-outer-wrapper {
						width: 100vw;
						transform: translateX(calc((100vw - 1200px) / 2 * -1));
					}
				}
				.bottom-wrapper {
					max-width: 800px;
					margin: 0 auto;
				}
				.bottom-inner-wrapper {
					margin: 0 4%;
				}
				.bottom-wrapper .checkbox {
					font-family: Noto Sans;
					font-size: 16px;
					line-height: 30px;
					letter-spacing: 0.5px;
					color: #FFF;
					text-align: center;
				}
				.bottom-wrapper .checkbox a {
					text-underline-offset: 2px;
					color: #FFF;
				}
				.bottom-wrapper .checkbox label {
					padding-left: 30px;
					margin: 0 auto;
					font-size: 16px;
					line-height: 22px;
					letter-spacing: 0.8px;
				}
				@media (max-width: 767px) {
					.bottom-wrapper .checkbox label {
						font-size: 13px;
					}
				}
				.bottom-wrapper .checkbox label:before {
					/* content: ' '; */
					position: absolute;
					left: 0;
					top: 50%;
					width: 21px;
					height: 21px;
					background: #FFF;
					border-radius: 100%;
					transform: translateY(-50%);
				}
				input[type=checkbox]:checked + label:before {
					width: 21px;
					height: 21px;
					background: #FFF;
				}
				input[type=checkbox]:checked + label:after {
					content: ' ';
					position: absolute;
					left: 0;
					top: 50%;
					width: 14px;
					height: 7px;
					border: 3px solid var(--theme-primary-color);
					border-top: 0;
					border-right: 0;
					transform: rotate(-60deg) translate(6px, 0);
				}
				.bottom-wrapper .form__submitbox {
					margin-top: 10px;
				}
				.bottom-wrapper .form__submitbox img {
					max-width: 375px;
					width: 100%;
				}
				.bottom-wrapper .follow-warning {
					text-align: center;
				}
			</style>
			<div class="bottom-outer-wrapper">
				<div class="bottom-wrapper">
					<div class="bottom-inner-wrapper">
						<div class="checkbox">
							{{-- <input type="checkbox" id="confirm_tnc" name="confirm_tnc"> --}}
							<label for="confirm_tnc">如繼續領取優惠，即表示您同意Kinnso <a href="{{ route('website.termsandconditions.html') }}" target="_blank">條款及細則</a>和<a href="{{ route('website.privacy.html') }}" target="_blank">私隱政策聲明</a>。</label>
						</div>

						<div id="termsWarning">必須同意以上條款及細則才能繼續</div>

						<div class="form__submitbox">
							<center><div>
@if (strtotime($offer->end_at) >= time() && $offer->quota > $offer->quota_issued)
								<a id="submit" class="form__submit">
									<img src="{{ asset('offers/'.$offer->offer_name.'/offer_whatsapp_button.png') }}?v=2" alt="" id="submitButton" >
								</a>
@else
								<img src="{{ asset('offers/'.$offer->offer_name.'/offer_no_quota_button.png') }}?v=2" alt="">
@endif
							</div></center>
						</div>
					</div>
				</div>
			</div>
			@include('campaigns/common/footer')
		</div>

		<!-- alert msg -->
		<div class="notify"><span id="notifyType" class=""></span></div>

		<script id="may-also-like-offer-splide-template" type="text/x-handlebars-template">
			@{{#each offers}}
			<li class="splide__slide">
				<div class="offer-card-wrapper">
					<div class="label-wrapper">
						@{{#each tags}}
							@{{#ifEquals this "hot"}}
						<div class="label hot-label"></div>
							@{{/ifEquals}}
							@{{#ifEquals this "new"}}
						<div class="label new-label"></div>
							@{{/ifEquals}}
							@{{#ifEquals this "push"}}
						<div class="label kinso-label"></div>
							@{{/ifEquals}}
							@{{#ifEquals this "less"}}
						<div class="label litter-label"></div>
							@{{/ifEquals}}
						@{{/each}}
					</div>
					@{{#url}}<a href="@{{this}}">@{{/url}}
						<div class="offer-card">
							<div class="image" style="--background-image:url('@{{key-visual}}')"></div>
							<div class="tagging">
								<ul>
									@{{#each labels}}
									<li data-text="@{{text}}" style="color:@{{text-color}}"></li>
									@{{/each}}
								</ul>
							</div>
							<div class="title">@{{title}}</div>
						</div>
					@{{#url}}</a>@{{/url}}
				</div>
			</li>
			@{{/each}}
		</script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js?v=2"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
		<script src="{{ asset('assets/vendor/splide/splide.min.js') }}"></script>
		<script src="{{ asset('js/common.js') }}?v=2"></script>
		<script src="{{ asset('js/utils.js') }}?v=2"></script>
		@include('campaigns/components/js/init_handlebars')
		<script>
			(function(window, document, undefined) {
				function initMayAlsoLikeSlide() {
					const mayAlsoLikeSlide = new Splide('#may-also-like-splide', {
													autoWidth: true,
													perPage: 4,
													breakpoints: {
														970: {perPage: 3},
														720: {perPage: 2},
														480: {perPage: 1}
													},
													arrows: false,
													pagination: false
												});
					mayAlsoLikeSlide.mount();
				}

				const offerImageSlide = new Splide('#offer-image-splide', {
											type: 'loop',
											autoplay: true,
											arrows: false,
											focus: 'center'
										});
				offerImageSlide.mount();

				const readMoreEle = document.querySelector('.first-paragraph .read-more');
				const remainingContentEle = document.querySelector('.remaining-content');
				if (readMoreEle) {
					readMoreEle.onclick = () => {
						remainingContentEle.style.display = 'block';
						readMoreEle.style.display = 'none';
					}
				}

				let mayAlsoLikeSplideEle = document.getElementById('may-also-like-splide');
				const mayAlsoLikeOfferSplideTemplate = Handlebars.compile(document.getElementById('may-also-like-offer-splide-template').innerHTML);
				fetch('{{ route("campaign.offer.recommend.json", ["offer_code" => $offer->offer_code]) }}', {
						method: 'GET'
					})
					.then(response => response.json())
					.then(data => {
						if (data.recommends) {
							let html = mayAlsoLikeOfferSplideTemplate({"offers": data.recommends});
							let splideEle = mayAlsoLikeSplideEle.querySelector('.splide__list');
							if (splideEle) {
								splideEle.innerHTML = html;
								initMayAlsoLikeSlide();
							}
						}
					});
			})(window, document, undefined);
		</script>
		<script type="text/javascript">
			var _checkedCount = 0;
			var _followIG = 0;
			var _followFB = 0;
			var _formCode = 0;
			var _requiredCount = 1;
			var _aidToken = "";
			var aid = "";
			let referrerCode = '';
			let memberReferralCode = '';

			$(document).ready(function()  {

				var offerTermButton = $("#offerTermButton");
				offerTermButton.on('click', handleOfferTerm);
				function handleOfferTerm() {
					$(this).closest('.offer__term').toggleClass('offer__term--open');
					analytics.track("read-offer-terms", {
						url: "{{ route('campaign.offer.details.html', ['offer_code'=>$offer->offer_code]) }}",
						offer_code: "{{ $offer->offer_code }}",
						offer_name: "{{ $offer->offer_name }}",
						offer_title: "{{ $offer->offer_title }}",
						offer_subtitle: "{{ $offer->offer_subtitle }}",
						ip: "{{ $ipAddress }}",
						userAgent: "{{ $userAgent }}",
						os: "{{ $operatingSystem }}",
						device: "{{ $device }}",
					});
				}

				var offerHowButton = $("#offerHowButton");
				offerHowButton.on('click', handleOfferHow);
				function handleOfferHow() {
					$(this).closest('.offer__term').toggleClass('offer__term--open');
					analytics.track("read-how-to-get-offer", {
						url: "{{ route('campaign.offer.details.html', ['offer_code'=>$offer->offer_code]) }}",
						offer_code: "{{ $offer->offer_code }}",
						offer_name: "{{ $offer->offer_name }}",
						offer_title: "{{ $offer->offer_title }}",
						offer_subtitle: "{{ $offer->offer_subtitle }}",
						ip: "{{ $ipAddress }}",
						userAgent: "{{ $userAgent }}",
						os: "{{ $operatingSystem }}",
						device: "{{ $device }}",
					});
				}

				function checkAcceptTerms()  {
					let isCheckedTerms = (_checkedCount == _requiredCount) ? true : false;
					if (isCheckedTerms) {
						$("#submitButton").fadeTo(100, 1.0, null);
					}  else  {
						$("#submitButton").fadeTo(100, 0.5, null);
					}
				}

				$("input[type=checkbox]").change(function()  {

					analytics.track("accept-terms-checkbox", {
						url: "{{ route('campaign.offer.details.html', ['offer_code'=>$offer->offer_code]) }}",
						offer_code: "{{ $offer->offer_code }}",
						offer_name: "{{ $offer->offer_name }}",
						offer_title: "{{ $offer->offer_title }}",
						offer_subtitle: "{{ $offer->offer_subtitle }}",
						ip: "{{ $ipAddress }}",
						userAgent: "{{ $userAgent }}",
						os: "{{ $operatingSystem }}",
						device: "{{ $device }}",
					});

					_checkedCount = 0;
					$("input[type=checkbox]").each(function()  {
						var checked = $(this).is(":checked");
						if (checked == true)  {_checkedCount++;}
					});

					// checkAcceptTerms();
				});


	
				$("#submitButton").click(async function()  {

					$("#termsWarning").hide();
					// let isCheckedTerms = (_checkedCount == _requiredCount) ? true : false;
					// if (!isCheckedTerms)  {
					// 	$("#termsWarning").show();
					// 	return;
					// }
						
					var basicRule = {
						rules:  {
							selectedRedemptionStore:{required: true}
						},
						messages: {
							selectedRedemptionStore: {
								required: "*請選擇換領店舖",
							}
						}
					}
					var form = $("#form");
					form.validate(basicRule);
					result = form.valid();
					if (result == false)  {return;}

					analytics.track("get-offer-button", {
						url: "{{ route('campaign.offer.details.html', ['offer_code'=>$offer->offer_code]) }}",
						offer_code: "{{ $offer->offer_code }}",
						offer_name: "{{ $offer->offer_name }}",
						offer_title: "{{ $offer->offer_title }}",
						offer_subtitle: "{{ $offer->offer_subtitle }}",
						ip: "{{ $ipAddress }}",
						userAgent: "{{ $userAgent }}",
						os: "{{ $operatingSystem }}",
						device: "{{ $device }}",
					});
					referrerCode = getQueryParam('r');
					memberReferralCode = getQueryParam('m');

					rand = Math.floor(Math.random() * 1000) + 1;

					//  AJAX reserve offer and get reserve code
					var formData = $("#form").serialize();
					await $.ajax({
						type: "POST",
						data: formData,
						dataType: "json",
						url: "{{ route('campaign.offer.submitform.json', ['offer_code'=>$offer->offer_code]) }}",
						async: true,
						headers:  {"cache-control": "no-cache"},
						success: function (result)  {
							if (result.status < 0)  {

								alert(result.message);
								hideLoading();
								return;
							}

							_formCode = result.formCode;
						},
						error: function (XMLHttpRequest, textStatus, errorThrown)  {
						}
					});

					await $.ajax({
						type: "POST",
						data: {
							aid: aid,
							formCode: _formCode,
							referrerCode: referrerCode,
							memberReferralCode: memberReferralCode
						},
						dataType: "json",
						url: "{{ route('campaign.offer.aidexchange.json', ['offer_code'=>$offer->offer_code]) }}"+"?_v="+rand ,
						async: true,
						headers:  {"cache-control": "no-cache"},
						success: function (result)  {
							switch (result.status)  {

								case 0:  {
									//  我想領取免費「xxx」！(Reg no.: xxx)
									_aidToken = " Reg no.:"+result.aidToken;
									return;
								}

								default:  {
								}  break;
							}
						},
						error: function (XMLHttpRequest, textStatus, errorThrown)  {
						}
					});

					$("#loading").css("display", "flex");
					window.location.href = "{{ $whatsappURL }}"+_aidToken;
				});

				$("#selectedRedemptionStore").change(function()  {

					var storeName = $("#selectedRedemptionStore").val();
					var url = "{{ route('campaign.offer.timeslot.json', ['offer_code'=>$offer->offer_code]) }}?store_name="+storeName;
					$.ajax({
						type: "GET",
						dataType: "json",
						url: url,
						success: function (result)  {

							var array = result.periodArray;
							var row = array[0];
							var storePeriodValue = row["id"];
							// hiddent field of selectedRedemptionPeriodID 
							document.getElementById('selectedRedemptionPeriodID').value = storePeriodValue;
							
						},
						error: function (XMLHttpRequest, textStatus, errorThrown)  {
							alert("Oops...\n#"+textStatus+": "+errorThrown);
						}
					});
				});

				analytics.ready(function()  {
					//  Exchange AID with unique code
					aid = analytics.user().anonymousId();
				});

			});

			function getQueryParam(key) {
				if (this.location && this.location.search) {
					let value = this.location.search.split(key + '=')[1]
					if (value) {
						value = value.split('&')[0];
						return value;
					}
				}

				return '';
			}

			const handleShare = (e) => {

				analytics.track("click-share-offer-button", {
					offer_code: "{{ $offer->offer_code }}",
					offer_name: "{{ $offer->offer_name }}",
					offer_title: "{{ $offer->offer_title }}",
					offer_subtitle: "{{ $offer->offer_subtitle }}",
					url: "{{ route('campaign.offer.details.html', ['offer_code'=>$offer->offer_code]) }}",
					ip: "{{ $ipAddress }}",
					userAgent: "{{ $userAgent }}",
					os: "{{ $operatingSystem }}",
					device: "{{ $device }}",
				});

				if (navigator.share) {
					navigator.share({
// 						title: e.target.dataset.title,
// 						text: "*"+e.target.dataset.title+"*",
						url: e.target.dataset.href
					}).then(() => {
						console.log('Thanks for sharing!');
					})
					.catch(console.error);
				} else {
					// fallback
					console.log('Share not available!');
				}
			}
			{{--
			document.querySelectorAll('.shareBtn').forEach(el => {
				el.addEventListener('click', handleShare)
			})
			--}}

			// for share page button
			const shareBtn_share = document.querySelector("#sharepage_share");
			var shareUrl = document.location.href ;
			var shareText = "即拎 Kinnso 推介「 {{ $offer->offer_title }} 」🤩 快啲撳入以下網址：\n"; 

			if (shareUrl.includes('?')){
				var len = shareUrl.indexOf('?');
				shareUrl = shareUrl.substring(0, len);
			}

			const shareData = {
				title: document.title,
				text: shareText,
				url: shareUrl,
			};
			
			shareBtn_share.addEventListener("click", function () {
				
				if (navigator.share) {
					navigator.share(shareData)
					.then(() => {
						$(".notify").toggleClass("active");
						$("#notifyType").toggleClass("sharelink");
						setTimeout(function(){
							$(".notify").removeClass("active");
							$("#notifyType").removeClass("sharelink");
						},3000);
					})
				} else {
					// if no sharing function of device, copy link
					navigator.clipboard.writeText(shareText + shareUrl)
					.then(() => {
						$(".notify").toggleClass("active");
						$("#notifyType").toggleClass("copylink");
						setTimeout(function(){
							$(".notify").removeClass("active");
							$("#notifyType").removeClass("copylink");
						},3000);
					})
					// .catch(() => alert("Cannot copy"));
				}
			});
		</script>
	</body>
</html>
