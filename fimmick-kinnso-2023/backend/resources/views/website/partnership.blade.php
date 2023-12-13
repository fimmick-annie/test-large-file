<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		@include('website/common/head')
		<link rel="stylesheet" href="./offers/common/offer_listing.css?v=1" />
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Noto+Sans&display=swap" rel="stylesheet">

		<script charset="utf-8" type="text/javascript" src="https://js.hsforms.net/forms/shell.js"></script>
	</head>
	<style>
		body {
			background-color: #ffffff;
			background-image: url("{{ asset('website/partnership/cityBGv2.svg') }}?v=1");
			background-repeat: repeat-x;
			background-size: auto 630px;
			background-position-y: min(calc( 100vw*0.3 + 250px ), 620px);
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
			/* background-image: url("{{ asset('website/partnership/cityBG.png') }}?v=1");
			background-position-y: 25%;
			background-size: 50%; */
		}

		.space{
			width: 100%;
			height: 80px;
			background: #FFFFFF;
		}

		.content {
			width: 100%;
			text-align: center;
			font-size: 20px;
			color: #707070;
			padding-bottom: 100px;
			padding-left: 10%;
			padding-right: 10%;
			padding-top: 100px;
			font-family: kinnsoFont;
		}

		.container{
			display: grid;
			text-align: center;
			margin-top: 3%;
			font-size: .9rem;
			color: #707070;
			justify-content: center;
		}

		.container h2 {
			text-align: center;
			font-weight: 700;
			color:#F87424;
			font-size: 1.5rem;
			letter-spacing: .1em;
			margin-top:3%;
			margin-bottom:30px;
			margin-left: -5%;
		}

		/* .container h4 {
			text-align: center;
			color:#F87424;
			font-weight: 200;
			font-size: 1.2rem;
			letter-spacing: .2em;
			margin-top:10%;
			margin-left: 7%;
		}

		.container h5 {
			text-align: center;
			color: #57524F;
			font-weight: 400;
			font-size: 1.8rem;
			font-family: Helvetica;
			letter-spacing: .05em;
			text-decoration: underline solid #FCCC08 4px;
			text-underline-offset: .3em;
			margin-bottom: 1.2em;
		} */

		.kinnsoBtn{
			background-color: #ffffff;
			border: 2px solid #fce088;
			margin-left:auto;
			margin-right:auto;
			margin-bottom: 20px;
			color: #f37720;
			text-align: center;
			width: min(350px, 80%);
			font-size: 18px;
			font-weight: 600;
			cursor: pointer;
			border-radius: 25px;
			padding-top: 10px;
			padding-bottom: 10px;
			letter-spacing: .2em;
			font-family: 'Noto Sans', sans-serif;
		}

		.rectangleContainer{
			position: relative;
			width: 800px;
			height: 400px;
			background: #FFFFFF;
			border-radius:50px;
		}

		.rectangle {
			position: absolute;
			width: 760px;
			height: 320px;
			top: 40px;
			left: 30px;
			background: #FFFFFF;
			overflow-y: scroll;
			scrollbar-width: auto;
		}

		.rectangle div.partnerlogo{
			position: absolute;
			left:10px;
		}

		@media (max-width: 800px) {
			.logo__desktop {
				display: none;
			}
			body {
				background-size: auto 530px;
				background-position-y: calc(100vw * (95/146) + 230px ) ;
			}
			.rectangleContainer{
				width: 360px;
				height: 320px;
			}
			.rectangle {
				width: 328px;
				height: 254px;
				top: 25px;
				left: 17px;
			}
			.kinnsoBtn{
				font-size: 16px;
				letter-spacing: .1em;
			}
		}

		@media (min-width: 801px) {
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
			scroll-margin-top: 80px;
		}

		.form>div {
			width: 100%;
			max-width: 1200px;
		}

		img{
  			text-shadow: 2px 2px 4px #000000;
		}
		.rectangle::-webkit-scrollbar {
			width: 3px;
		}
		.rectangle::-webkit-scrollbar-track {
			box-shadow: inset 0 0 10px #fff; 
			border-radius: 3px;
		}
		.rectangle::-webkit-scrollbar-thumb {
			background: rgb(193, 193, 193, 0.8);
			border-radius: 5px;
		}
		.rectangle::-webkit-scrollbar-thumb:hover {
			background: #fff; 
		}
	</style>

	<body>
		<!--  Segment  -->
		<script>
			!function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","reset","group","track","ready","alias","debug","page","once","off","on","addSourceMiddleware","addIntegrationMiddleware","setAnonymousId","addDestinationMiddleware"];analytics.factory=function(e){return function(){var t=Array.prototype.slice.call(arguments);t.unshift(e);analytics.push(t);return analytics}};for(var e=0;e<analytics.methods.length;e++){var key=analytics.methods[e];analytics[key]=analytics.factory(key)}analytics.load=function(key,e){var t=document.createElement("script");t.type="text/javascript";t.async=!0;t.src="https://cdn.segment.com/analytics.js/v1/" + key + "/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(t,n);analytics._loadOptions=e};analytics._writeKey="W1yJ9TYonvWx2FlA6469eTyvX0xegGS3";analytics.SNIPPET_VERSION="4.13.2";
			analytics.load("{{ env('SEGMENT_ID') }}");
			analytics.page("partnership", {
				ip: "{{ $ipAddress }}",
				name: "partnership",
				path: "/partnership",
				title: "Kinnso",
				url: "{{ route('website.partnership.html', []) }}",
				userAgent: "{{ $userAgent }}",
			});
			}}();
		</script>

		<div class="wrapper">

			<div class="offer">
				@include('campaigns/common/header')
			</div>


			<div class="logo__desktop">
				<div class="space"></div>
				<img src="{{ asset('website/partnership/desktopBanner.png') }}?v=1"  alt="Partnership kv desktop" style="border-radius: 50px"/><br>
				<div class="container">
					<h2><img src="{{ asset('website/partnership/icon_cooperative.png') }}?v=1" style="width:35px;"> 合作推廣</h2>
					立即成為我們的合作夥伴，一同分享美食及商品給更多的顧客。<br><br><br>
					<a href="#form"><button class="kinnsoBtn" type="button">我想成為Kinnso合作夥伴</button></a><br>
					<h2><img src="{{ asset('website/partnership/icon_people.png') }}?v=1" style="width:35px;"> 我們的合作夥伴</h2>
					<div class="rectangleContainer">
						<div class="rectangle">
							<div class="partnerlogo" ><img src="{{ asset('website/partnership/logolist_desktop_v2.svg') }}?v=1" style="width:740px"></div>
						</div>
					</div>
				</div>
			</div>

			<div class="logo__mobile">
				<img src="{{ asset('website/partnership/mobileBanner.png') }}?v=1" alt="Partnership kv mobile" style="padding-top:70px;"/><br>
				<div class="container">
					<h2><img src="{{ asset('website/partnership/icon_cooperative.png') }}?v=1" style="width:35px;"> 合作推廣</h2>
					立即成為我們的合作夥伴，<br>一同分享美食及商品給更多的顧客。<br><br><br>
					<a href="#form"><button class="kinnsoBtn" type="button">我想成為Kinnso合作夥伴</button></a><br>
					<h2><img src="{{ asset('website/partnership/icon_people.png') }}?v=1" style="width:35px;"> 我們的合作夥伴</h2>
					<div class="rectangleContainer">
						<div class="rectangle">
							<div class="partnerlogo" ><img src="{{ asset('website/partnership/logolist_mobile_v2.svg') }}?v=1" style="width:290px"></div>
						</div>
					</div>
				</div>
			</div>

			<div class='content' >
				<div class='form' id ='form'>
					<script>
						hbspt.forms.create({
							region: "na1",
							portalId: "7195943",
							formId: "5b501951-fdc8-4fad-89b2-e21bbd6ef5a3",
							onFormSubmit: function($form)  {
								analytics.track("click-partnership-CTA-button", {
									url: "{{ route('website.partnership.html', []) }}",
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