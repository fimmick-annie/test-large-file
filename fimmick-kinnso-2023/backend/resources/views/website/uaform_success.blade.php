<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		@include('website/common/head')

		<!--  Help to ensure the left menu in right size  -->
		<link rel="stylesheet" href="./offers/common/offer_listing.css?v=1" />

		<style>
			:root {
				--theme-header-color: #F37621;
				--theme-pale-yellow: rgba(252, 204, 8, 0.1);
			}
			body {
				background-color: #ffffff;
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
				font-family: Arial, "Microsoft JhengHei Bold";
				color: #57524F;
				max-width: 100vw;
			}

			.content  {
				width: 100%;
				text-align: center;
				font-size: 21px;
				padding: 100px 15px 0 15px;
			}

			.content h1 {
				margin: 0;
				letter-spacing: 5px;
				font-size: calc(0.8rem + 1vw);
				display: inline-block;
				position: relative;
				padding: calc(1.45vw) calc(4.2vw);
			}

			.content h1::before {
				content: '“';
				font-family: Arial, "Microsoft JhengHei Bold";
				font-size: calc(1.3rem + 1.5vw);
				position: absolute;
				top: 0;
				left: 0;
			}

			.content h1>span:nth-child(2), .reward p>span{
				color: var(--theme-header-color);
			}

			.content h1::after {
				content: '“';
				font-family: Arial, "Microsoft JhengHei Bold";
				font-size: calc(1.3rem + 1.5vw);
				position: absolute;
				bottom: 0;
				right: 0;
				transform: scaleX(-1) scaleY(-1);
			}

			.reward{
				font-size: 14px;
				width: 100%;
				text-align: center;
				position: relative;
			}

			.reward .bear {
				width: 144px;
				position: relative;
				top: 5px;
			}

			.reward div {
				padding: 35px 0;
				background: var(--theme-pale-yellow);
			}

			.reward p, .form label {
				margin: 0;
				color: #707070;
				font-size: calc(14px + 0.2vw);
			}

			.reward p:nth-child(2)>a {
				border: 1px solid #F17630;
				padding: 1px 20px;
				border-radius: 30px;
				color: #F37621;
				text-decoration: none;
			}

			.reward p:nth-child(2)>a:hover {
				color: #F37621;
			}

			.success-msg h1{
				font-size: calc(30px + 0.2vw);
				text-align: center;
				margin: 50px;
			}

			.success-msg .button-wrapper{
				display: flex;
				justify-content: center;
				max-width: 1000px;
				margin: auto;
			}

			.success-msg a.submit-btn{
				display: inline-block;
				margin: auto;
				letter-spacing: 4.8px;
				background-color: #ffffff;
				color: #F37621;
				font-size: calc(14px + 0.2vw);
				border: 1px solid #FBCB30;
				border-radius: 31px;
				padding: 10px 0;
				cursor: pointer;
				text-align: center;
				min-width: 400px;
				text-decoration: none;
			}

			@media (max-width: 770px)  {
				.success-msg .button-wrapper {
					flex-direction: column;
					margin: 0 10px;
				}

				.success-msg a.submit-btn{
					min-width: 0;
					width: 90%;
					padding: 10px 0;
				}
			}
			@media (max-width: 1365px)  {
				.logo__desktop  {display: none;}
			}

			@media (min-width: 1366px) {
				.logo__mobile {
					display: none;
				}
			}
		</style>
	</head>

	<body>
	@include('website/common/tracking_body')

	<!--  Segment  -->
	<script>
		!function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","reset","group","track","ready","alias","debug","page","once","off","on","addSourceMiddleware","addIntegrationMiddleware","setAnonymousId","addDestinationMiddleware"];analytics.factory=function(e){return function(){var t=Array.prototype.slice.call(arguments);t.unshift(e);analytics.push(t);return analytics}};for(var e=0;e<analytics.methods.length;e++){var key=analytics.methods[e];analytics[key]=analytics.factory(key)}analytics.load=function(key,e){var t=document.createElement("script");t.type="text/javascript";t.async=!0;t.src="https://cdn.segment.com/analytics.js/v1/" + key + "/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(t,n);analytics._loadOptions=e};analytics._writeKey="W1yJ9TYonvWx2FlA6469eTyvX0xegGS3";analytics.SNIPPET_VERSION="4.13.2";
			analytics.load("{{ env('SEGMENT_ID') }}");
			analytics.page("about-us", {
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
			<h1><span>Kinnso獨家優惠！<br>UA iMoney全新客戶貸款額外送 </span><span>$500超市禮劵</span>*！</h1><br>
			<img src="{{ asset('website/form_ua/banner.png')  }}" alt="banner" class="bear" style="height:40%;">
		</div>

		<div class="success-msg">
			<h1>你已成功遞交申請。</h1>
			<br><br><br><br>

		</div>
	</div>
	</body>
</html>
