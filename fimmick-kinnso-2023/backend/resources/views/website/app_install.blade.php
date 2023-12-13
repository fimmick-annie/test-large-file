<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		@include('website/common/head')

		<style>
			body {
				background-color: #ffffff;
			}

			.coupon_comingsoon_kv {
				padding-left: 10%;
				padding-right: 10%;
			}

			.form {
				padding: 20px 15px;
				margin-left: 2rem;
				margin-right: 2rem;
			}

			.form__row {
				display: flex;
				flex-wrap: wrap;
				align-items: flex-start;
				justify-content: space-between;
			}

			.form__row:nth-child(n + 2) {
				margin-top: 1rem;
			}

			.form__full {
				width: 100%;
			}

			.form__half {
				width: 100%;
				max-width: calc(50% - 5px);
			}

			.form__label {
				display: inline-block;
				margin-bottom: 0.5rem;
				color: #888888;
				text-align: center;
				width: 100%;
				font-size: 1.2rem;
			}

			.form__input {
				display: block;
				width: 100%;
				height: calc(1.5em + .75rem + 2px);
				padding: .375rem .75rem;
				font-size: 1rem;
				font-weight: 400;
				line-height: 1.5;
				color: #495057;
				background-color: #fff;
				border: 1px solid #9A8B86;
				border-style: solid;
				border-color: #000000;
				border-width: 0 0 1px;
			}

			.form__phonearea {
				width: 100%;
				max-width: calc((100% / 3) - 5px);
			}

			.form__select {
				display: block;
				width: 100%;
				height: calc(1.5em + .75rem + 2px);
				padding: .375rem .75rem;
				font-size: 1rem;
				font-weight: 400;
				line-height: 1.5;
				color: #495057;
				background-color: #fff;
				border: 1px solid #9A8B86;
				border-style: solid;
				border-color: #000000;
				border-width: 0 0 1px;
			}

			.form__select option[disabled] {
				color: #ccc;
			}

			.form__phone {
				width: 100%;
				max-width: calc(66.66% - 5px);
			}

			.form__row input[type="checkbox"] {
				display: none;
			}

			.form__row input[type="checkbox"]:checked+label::before {
				background-color: #9A8B86;
				border-color: #9A8B86;
			}

			.form__row input[type="checkbox"]:checked+label::after {
				content: '';
				position: absolute;
				top: calc(50% - 8px);
				left: 4px;
				width: 16px;
				height: 16px;
				background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23fff' d='M6.564.75l-3.59 3.612-1.538-1.55L0 4.26 2.974 7.25 8 2.193z'/%3e%3c/svg%3e");
			}

			.form__checklabel {
				position: relative;
				color: #495057;
				line-height: 2;
				padding-left: 2rem;
			}

			.form__checklabel::before {
				content: '';
				position: absolute;
				left: 0;
				top: calc(50% - 12px);
				width: 22px;
				height: 22px;
				border: 1px solid #adb5bd;
				-webkit-ion: all .4s ease;
				-moz-transition: all .4s ease;
				transition: all .4s ease;
			}

			.form__checklabel:hover::before {
				background-color: #9A8B86;
				border-color: #9A8B86;
			}

			.form__checklabel img {
				vertical-align: middle;
			}

			.form__submitbox {
				text-align: center;
				margin-top: 20px;
			}

			.offer__term {
				width: 100%;
				color: #888888;
				font-size: 1.2rem;
			}

			.offer__term--open .offer__term-btn::before {
				display: none;
			}

			.offer__term--open .offer__term-hidden {
				display: block;
			}

			.offer__term-btn {
				position: relative;
				display: block;
				color: #747474;
				font-size: 1rem;
				line-height: 1.5;
				padding: 6px 50px 6px 22px;
				border: 1px solid #747474;
				border-radius: 6px;
			}

			.offer__term-btn::before {
				content: '';
				position: absolute;
				top: calc(50% - 11px);
				right: 35px;
				width: 2px;
				height: 22px;
				background-color: #747474;
			}

			@media (max-width: 575px) {
				.offer__term-btn {
					font-size: 1.5rem;
				}
			}

			.offer__term-hidden {
				display: none;
				padding: 20px 0;
			}

			.offer__term-hidden ul {
				padding-left: 1.5em;
			}

			.offer__term-hidden ul li {
				color: #747474;
				font-size: 1rem;
				line-height: 1.3;
			}

			.form {
				padding-left: 0px !important;
				padding-right: 0px !important;
				margin-left: 4%;
				margin-right: 4%;
			}

			.offer__term {
				margin-bottom: 10px;
				color: #ffffff;
			}

			.offer__term-btn {
				color: #ffffff;
				background-color: #cf0d01;
				border: 1px solid #cf0d01 !important;
			}

			.offer__term-btn::before {
				background-color: #ffffff !important;
			}

			.offer__term-btn::after {
				background-color: #ffffff !important;
			}

			.offer__term_text {
				color: #747474;
				font-size: 14px;
			}
		</style>
	</head>

	<body>
		@include('website/common/tracking_body')

		<!--  Segment  -->
		<script>
			!function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","reset","group","track","ready","alias","debug","page","once","off","on","addSourceMiddleware","addIntegrationMiddleware","setAnonymousId","addDestinationMiddleware"];analytics.factory=function(e){return function(){var t=Array.prototype.slice.call(arguments);t.unshift(e);analytics.push(t);return analytics}};for(var e=0;e<analytics.methods.length;e++){var key=analytics.methods[e];analytics[key]=analytics.factory(key)}analytics.load=function(key,e){var t=document.createElement("script");t.type="text/javascript";t.async=!0;t.src="https://cdn.segment.com/analytics.js/v1/" + key + "/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(t,n);analytics._loadOptions=e};analytics._writeKey="W1yJ9TYonvWx2FlA6469eTyvX0xegGS3";analytics.SNIPPET_VERSION="4.13.2";
				analytics.load("{{ env('SEGMENT_ID') }}");
				analytics.page("app-install", {
					ip: "{{ $ipAddress }}",
					userAgent: "{{ $userAgent }}",
				});
			}}();
		</script>
		<!--  End Segment  -->

		<div class="wrapper">

			@include('website/common/header')
			<header class="header"></header>
			<div onclick="handleAppInstall()" style="cursor:pointer;" ><img src="{{ asset('website/appinstall_page.png') }}?v=4" alt="Key visual" /></div>

			@include('website/common/footer')
		</div>
		<script>
			function isiOS()  {
				return [
					'iPad Simulator',
					'iPhone Simulator',
					'iPod Simulator',
					'iPad',
					'iPhone',
					'iPod'
				].includes(navigator.platform)

				//  iPad on iOS 13 detection
				|| (navigator.userAgent.includes("Mac") && "ontouchend" in document)
			}

			function isAndroid()  {
				var userAgent = navigator.userAgent.toLowerCase();
				var result = userAgent.indexOf("android") > -1;
				return result;
			}

			function handleAppInstall()  {
				if (isiOS())  {
					window.location.href = "https://apps.apple.com/hk/app/pacific-coffee-hong-kong/id438641591";
					return;
				}

				if (isAndroid())  {
					window.location.href = "https://play.google.com/store/apps/details?id=com.gogoalsoft.pacificcoffee&hl=zh_HK&gl=US";
					return;
				}

				//  If desktop (not iOS & Android)
				window.location.href = "https://apps.apple.com/hk/app/pacific-coffee-hong-kong/id438641591";
			}
		</script>
	</body>
</html>
