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
		<style>
			#termsWarning  {
				padding-left: 26px;
				color: #ff0000;
				display: none;
			}
			#submitButton  {
				opacity: 0.5;
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
				padding: 23px 30px;
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
				color: #ffffff;
			}
			.offer__term-btn  {
				color: {{ $offer->ini['settings']['theme_button_text_color'] }};
				background-color: {{ $offer->ini['settings']['theme_button_color'] }};
				border: 1px solid {{ $offer->ini['settings']['theme_button_color'] }} !important;
			}
			.offer__term-btn::before  {
				background-color: {{ $offer->ini['settings']['theme_button_text_color'] }} !important;
			}
			.offer__term-btn::after  {
				background-color: {{ $offer->ini['settings']['theme_button_text_color'] }} !important;
			}
			.offer__term a:hover  {
				color: {{ $offer->ini['settings']['theme_button_hover_color'] }};
			}
			.offer__term_text  {
				color: #747474;
				font-size: 14px;
			}

			label  {
				cursor: pointer;
				display: inline-block;
				position: relative;
				padding-left: 25px;
				margin-right: 10px;
				padding-top: 10px;
				padding-bottom: 10px;
			}
			label:before  {
				content: "";
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
					padding-left: 30px;
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
				analytics.page("{{ $offer->offer_code }}", {
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
			}}();
		</script>

		<div class="wrapper">

			@include('campaigns/common/header')

			<div style="padding-top:46px;">
				<img src="{{ asset('offers/'.$offer->offer_name.'/offer_comingsoon_kv.jpg') }}?v=1" alt="Coming soon" />
			</div>

			@include('campaigns/common/footer')
		</div>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js?v=2"></script>
		<script src="{{ asset('js/common.js') }}?v=2"></script>
		<script src="{{ asset('js/utils.js') }}?v=2"></script>
		<script type="text/javascript">
		</script>
	</body>
</html>
