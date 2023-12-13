<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		@include('website/common/head')

		<style>
			body {
				background-color: #ffffff;
			}

			@font-face {
				font-family: kinnsoFont;
				src: url("{{ asset('assets/gensen.ttf') }}?v=1");
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
			.listing  {
				color: #757575;
				font-size: 1rem;
				line-height: 1.57142857;
			}
			.heading  {
				font-weight: bold;
				margin-top: 30px;
				margin-bottom: 10px;
			}
			.wrapper  {
				background-image: url("{{ asset('website/coming-soon/background.png') }}?v=1");
				background-repeat: no-repeat;
				background-color: #ffaf19;
				background-size: cover;
			}
			.content  {
				width: 100%;
				text-align: center;
				font-size: 20px;
				color: #ffffff;
				padding-bottom: 100px;
				padding-left: 10%;
				padding-right: 10%;
				padding-top: 100px;
				font-family: kinnsoFont;
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

		<!--  Segment  -->
		<script>
			!function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","reset","group","track","ready","alias","debug","page","once","off","on","addSourceMiddleware","addIntegrationMiddleware","setAnonymousId","addDestinationMiddleware"];analytics.factory=function(e){return function(){var t=Array.prototype.slice.call(arguments);t.unshift(e);analytics.push(t);return analytics}};for(var e=0;e<analytics.methods.length;e++){var key=analytics.methods[e];analytics[key]=analytics.factory(key)}analytics.load=function(key,e){var t=document.createElement("script");t.type="text/javascript";t.async=!0;t.src="https://cdn.segment.com/analytics.js/v1/" + key + "/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(t,n);analytics._loadOptions=e};analytics._writeKey="W1yJ9TYonvWx2FlA6469eTyvX0xegGS3";analytics.SNIPPET_VERSION="4.13.2";
				analytics.load("{{ env('SEGMENT_ID') }}");
				analytics.page("{{ isset($offer->offer_name) ? $offer->offer_name : '' }}", {
					ip: "{{ $ipAddress }}",
					userAgent: "{{ $userAgent }}",
				});
			}}();
		</script>
		<!--  End Segment  -->

		<div class="wrapper">

			<div class="offer">
				@include('campaigns/common/header')
			</div>

			<div class="content">
				<img src="{{ asset('website/coming-soon/title.png') }}?v=1" alt="Coming soon key visual" style="padding-bottom:20px;" />
				<br>有冇諗過，日常消費點樣慳到盡？
				<br>有冇諗過，著數識得自動送上門？
				<br>有冇諗過，一個平台掃盡全港著數？
				<br>
				<br>全城熱切期待，
				<br>全新著數平台 Kinnso，
				<br>一連串至堅、至 So 著數
				<br>即將熱鬧送上！
			</div>

			@include('website/common/footer')
		</div>
	</body>
</html>
