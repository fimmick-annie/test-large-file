<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		@include('website/common/head')

		<!--  Help to ensure the left menu in right size  -->
		<link rel="stylesheet" href="./offers/common/offer_listing.css?v=1" />

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
				/*background-image: url("{{ asset('website/about-us/background.png') }}?v=1");
				background-repeat: no-repeat;
				background-color: #ffaf19;
				background-size: cover;*/
			}
			.content  {
				width: 100%;
				text-align: center;
				font-size: 0.9rem;
				font-weight: 140;
				color: black;
				padding-bottom: 100px;
				padding-top: 100px;
				/* font-family: sans-serif; */
			}

			.content h2 {
				text-align: center;
				font-size: 1.4rem;
				font-weight: 700;
				/* font-family: sans-serif; */
				color:#F87424;
				margin-top: 2%;
			}

			.content h4 {
				text-align: center;
				font-size: 1.2rem;
				font-weight: 400;
				/* font-family: sans-serif; */
				color:#F87424;
			}
			
			.container{
				display: grid;
				position: relative;
  				text-align: center;
				margin-top: 3%;
			}

			.top-center_desktop {
				position: absolute;
				margin-left: auto;
				margin-right: auto;
				top: 3%;
				left: 0;
				right: 0;
				text-align: center;
			}

			.top-center_moblie {
				position: absolute;
				margin-left: auto;
				margin-right: auto;
				top: 1%;
				left: 0;
				right: 0;
				text-align: center;
			}

			.option1_moblie {
  				position: absolute;
  				top: 8%;
				left: 11%;
			}

			.option2_moblie {
				position: absolute;
  				top: 35%;
				left: 26%;
			}

			.option3_moblie {
				position: absolute;
  				top: 66%;
				left: 9%;
			}

			.option1_desktop {
  				position: absolute;
  				top: 23%;
				left: 20%;
			}

			.option2_desktop {
				position: absolute;
  				top: 43%;
				left: 39%;
			}

			.option3_desktop {
				position: absolute;
  				top: 23%;
				left: 60%;
			}

			.line01 {
				position: absolute;
  				top: 10%;
				left: 9%;
			}

			.line02 {
				position: absolute;
  				top: 65%;
				left: 32%;
			}

			.line03 {
				position: absolute;
  				top: 65%;
				left: 61%;
			}

			.line04 {
				position: absolute;
  				top: 12%;
				left: 78%;
			}
			
			@media (max-width: 800px)  {
				.logo__desktop  {
					display: none;
				}
			}
			@media (min-width: 800px) {
				.logo__mobile  {
					display: none;
				}
			}
			
			.default-font-family {
				font-family: var(--bs-font-sans-serif);
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

				<!--  Here is the verion for desktop size-->
				<div class="logo__desktop">
					<img src="{{ asset('website/about-us/desktop02.png') }}?v=1" alt="About us key visual" style="padding-bottom:20px; width:900px;"/><br>
					<h2><img src="{{ asset('website/about-us/icon01_about.png') }}?v=1" style="width:30px;"> 關於 Kinnso
					</h2>物價飛騰，生活成本節節上升。唔少人為開源節流，慳得一蚊得一蚊。Kinnso幫到你：只需一部電話，即時賺取現金券！
					<br>Kinnso創立於2021年6月，是集優惠資訊及生活娛樂於一身的現金劵回贈平台。
					<br>全港首創Whatsapp領取優惠/懶人包功能，即時資訊一覽無遺，邊接收優惠資訊同時賺取無上限現金券。
					<br>涵蓋美食、旅行、酒店Staycation、信用卡、時尚、生活方式、健身等等著數。
					<br>
					<br>Kinnso三大功能滲入讀者生活，日常資訊變成百寶天書，包括：
					<br>
					<h4><br>(1) 豐富優惠情報，即時掌握在手
					</h4>為你發掘城中「衣食住行」各大禮遇及情報，獻上會員精彩優惠，更能享用與Kinnso合作夥伴的獨家獎賞。
					<br>實體零售商及人氣品牌合作，涵蓋美食，酒店Staycation、信用卡、時尚、
					<br>生活方式、健身等最新著數。
					<br>
					<h4><br>(2) 一舉兩得，事半功倍
					</h4>接收優惠資訊同時賺取積分，積分累積愈多，即時兌換現金券。不費心力即能輕鬆換獎賞，零成本享盡吃、喝、玩、樂生活禮遇！
					<br>
					<h4><br>(3) 快速賺取積分捷徑，推動全面互動體驗
					</h4>完成簡單任務及互動，便可獲取額外積分。運用捷徑兌換獎品，領先一步換取心頭好！
					<br>
					<br>
					<div class="container">
						<img src="{{ asset('website/about-us/background_desktop.png') }}?v=1" alt="meun1" style="width:120%; float:left" >
						<img class="top-center_desktop" src="{{ asset('website/about-us/icon02.png') }}?v=1" style="width: 160px;" >
						<img class="line01" src="{{ asset('website/about-us/honey_line01.png') }}?v=1" style="width: 12%;" >
						<img class="line02" src="{{ asset('website/about-us/honey_line02.png') }}?v=1" style="width: 9%;" >
						<img class="line03" src="{{ asset('website/about-us/honey_line03.png') }}?v=1" style="width: 8%;" >
						<img class="line04" src="{{ asset('website/about-us/honey_line04.png') }}?v=1" style="width: 14%;" >
						<a href="{{ route('campaign.offer.listing.html') }}"><img class="option1_desktop" src="{{ asset('website/about-us/honey01_new.png') }}?v=1" alt="honey11" style="width:21%;"></a>
						<a href="{{ route('website.kinnsopoints.html') }}"><img class="option2_desktop" src="{{ asset('website/about-us/honey02_new.png') }}?v=1" alt="honey22" style="width:21%;"></a>
						<a href="{{ route('website.redemption.html') }}"><img class="option3_desktop" src="{{ asset('website/about-us/honey03_new.png') }}?v=1" alt="honey33" style="width:21%;"></a>
					</div>
				</div>

				<!--  Here is the verion for moblie size-->
				<div class="logo__mobile">
					<img src="{{ asset('website/about-us/mobile02.png') }}?v=1" alt="About us key visual22" style="padding-bottom:20px; width:100%" />
					<h2><img src="{{ asset('website/about-us/icon01_about.png') }}?v=1" style="width:30px;"> 關於 Kinnso
					</h2>物價飛騰，生活成本節節上升。
					<br>唔少人為開源節流，慳得一蚊得一蚊。
					<br>Kinnso幫到你：只需一部電話，即時賺取現金券！
					<br>
					<br>Kinnso創立於2021年6月，是集優惠資訊及生活娛樂
					<br>於一身的現金劵回贈平台。全港首創Whatsapp領取
					<br>優惠/懶人包功能，即時資訊一覽無遺，
					<br>邊接收優惠資訊同時賺取無上限現金券。
					<br>涵蓋美食、旅行、酒店Staycation、信用卡、
					<br>時尚、生活方式、健身等等著數。
					<br>
					<br>Kinnso三大功能滲入讀者生活，
					<br>日常資訊變成百寶天書，包括：
					<br>
					<h4><br>(1)豐富優惠情報，即時掌握在手
					</h4>為你發掘城中「衣食住行」各大禮遇及情報，
					<br>獻上會員精彩優惠，更能享用
					<br>與Kinnso合作夥伴的獨家獎賞。
					<br>
					<h4><br>(2)一舉兩得，事半功倍
					</h4>接收優惠資訊同時賺取積分，積分累積愈多，
					<br>即時兌換現金券。不費心力即能輕鬆換獎賞，
					<br>零成本享盡吃、喝、玩、樂生活禮遇！
					<br>
					<h4><br>(3)快速賺取積分捷徑，推動<br>全面互動體驗
					</h4>完成簡單任務及互動，便可獲取額外積分。
					<br>運用捷徑兌換獎品，領先一步換取心頭好！
					<br>
					<br>
					<div class="container">
						<img src="{{ asset('website/about-us/background_moblie.png') }}?v=1" alt="meun" style="width:100%;">
						<img class="top-center_moblie" src="{{ asset('website/about-us/icon02.png') }}?v=1" style="width: 160px;" >
						<a href="{{ route('campaign.offer.listing.html') }}"><img class="option1_moblie" src="{{ asset('website/about-us/honey01_new.png') }}?v=1" alt="honey11" style="width: 62%;"></a>
						<a href="{{ route('website.kinnsopoints.html') }}"><img class="option2_moblie" src="{{ asset('website/about-us/honey02_new.png') }}?v=1" alt="honey22" style="width: 62%;"></a>
						<a href="{{ route('website.redemption.html') }}"><img class="option3_moblie" src="{{ asset('website/about-us/honey03_new.png') }}?v=1" alt="honey33" style="width: 60%;"></a>
					</div>
				</div>
			</div>
		
			@include('website/common/footer')

		</div>
	</body>
</html>


