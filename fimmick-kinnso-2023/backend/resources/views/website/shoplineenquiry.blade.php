<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

	<head>
		@include('website/common/head')

			<!--  Help to ensure the left menu in right size  -->
			<link rel="stylesheet" href="./offers/common/offer_listing.css?v=1" />

		<script charset="utf-8" type="text/javascript" src="https://js.hsforms.net/forms/shell.js"></script>
	</head>

	<style>
		body {
			background-color: #ffffff;
		}

		@font-face {
			font-family: kinnsoFont;
			src: url("{{ asset('assets/gensen.ttf') }}?v=1");
		}

		.header {
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

		.header__landing {
			background-color: #ffffff;
		}

		.logo {
			position: absolute;
			left: 50%;
			top: 50%;
			width: 100%;
			max-width: 132px;
			display: block;
			-webkit-transform: translate(-50%, -50%);
			-ms-transform: translate(-50%, -50%);
			transform: translate(-50%, -50%);
		}

		.listing {
			color: #757575;
			font-size: 1rem;
			line-height: 1.57142857;
		}

		.heading {
			font-weight: bold;
			margin-top: 30px;
			margin-bottom: 10px;
		}

		.wrapper {
			/* background-image: url("{{ asset('website/about-us/background.png') }}?v=1"); */
			/* background-repeat: no-repeat; */
			/* background-color: #ffaf19; */
			/* background-size: cover; */
		}

		.content {
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

		@media (max-width: 1365px) {
			.logo__desktop {
				display: none;
			}
		}

		@media (min-width: 1366px) {
			.logo__mobile {
				display: none;
			}
		}

		.default-font-family {
			font-family: var(--bs-font-sans-serif);
		}

		.form {
			padding: 1rem;

			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			width: 100%;
		}

		.form>div {
			width: 100%;
			max-width: 1200px;
		}
	</style>

	<body>
		<!--  Segment  -->
		<script>
			!function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","reset","group","track","ready","alias","debug","page","once","off","on","addSourceMiddleware","addIntegrationMiddleware","setAnonymousId","addDestinationMiddleware"];analytics.factory=function(e){return function(){var t=Array.prototype.slice.call(arguments);t.unshift(e);analytics.push(t);return analytics}};for(var e=0;e<analytics.methods.length;e++){var key=analytics.methods[e];analytics[key]=analytics.factory(key)}analytics.load=function(key,e){var t=document.createElement("script");t.type="text/javascript";t.async=!0;t.src="https://cdn.segment.com/analytics.js/v1/" + key + "/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(t,n);analytics._loadOptions=e};analytics._writeKey="W1yJ9TYonvWx2FlA6469eTyvX0xegGS3";analytics.SNIPPET_VERSION="4.13.2";
			analytics.load("{{ env('SEGMENT_ID') }}");
			analytics.page("shoplineenquiry", {
				ip: "{{ $ipAddress }}",
				name: "shoplineenquiry",
				path: "/shopline_enquiry",
				title: "Kinnso",
				url: "{{ route('website.shoplineenquiry.html', []) }}",
				userAgent: "{{ $userAgent }}",
			});
			}}();
		</script>

		<div class="wrapper">

			<div class="offer">
				@include('campaigns/common/header')
			</div>
			<div class='content'>
				<div class='form'>
					<script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/v2.js"></script> 
					<script>
						hbspt.forms.create({
							region: "na1",
							portalId: "7195943",
							formId: "9406e39c-762f-4a90-bff2-8d78d95bfc2a",
							onFormSubmit: function($form)  {
								analytics.track("click-shoplineenquiry-CTA-button", {
									url: "{{ route('website.shoplineenquiry.html', []) }}",
									ip: "{{ $ipAddress }}",
									userAgent: "{{ $userAgent }}",
								});
							},
						});
					</script>
				</div>
			</div>

		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js?v=2"></script>
	</body>
</html>