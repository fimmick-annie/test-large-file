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
				text-align: center;
				padding-top: 100px;
				font-family: Noto Sans;
				/* padding-left: 0px;
				padding-right: 0px; */
			}

			.container-fluid {
				background: #FDFAE6;
				text-align: center;
				justify-content: center;
				min-height: 60vh;
				/* width:100%; */
			}

			.row #first_show{
				justify-content: center;
				display: block;
				/* position: absolute; */
			}

			#nth{
				transform: translate(0%, 3%);
			}

			#base {
				background: #FCCC08;
				width: 100%;
				min-height: 40vh;  
			}

			img { 
				position: inline;
			}

			.tag_container{
				display: flex;
				flex-direction: row;
				justify-content: center;
				align-items: center;
				position: absolute;
			}

			.tag{
				position: relative;
				width: 75px;
				top: -27px;
				/* left: 35%; */
				justify-content: top;
			}

			.tag_s {
				position:absolute;
				width: 75px;
				left:0;
				top:0px;
			}

			.tag_ss{
				display: none;
				position:absolute;
				width: 75px;
				left:0;
				top:0px;
			}

			.content h1{
				color:#F37621;
				text-align: center;
				font-size: 20px;
			}

			.content p{
				/* align-content: center;
				color: #6D6D6D;
				font-size: 25px;
				margin-bottom: 2px; */
				align-content: center;
                color: #6D6D6D;
                font-size: 1.4em;
                font-weight: 400;
                min-width: 100px;
                max-width: 200px;
                margin-bottom: 2px;
                display: flex;
                justify-content: center;
                align-items: center;
			}

			i{
				position:relative;
				/* text-align: left; */
				color: #57524F;
				font-size: 16px;
				font-weight: 200;
				font-style: normal;
				font-family: Helvetica Neue;
				letter-spacing:1.5px;
				line-height: 40px;
			}

			p.solid {
				/* border-style: solid; 
				border-color: #FCCC08;
				border-radius: 5%;
				min-width: 60px; */
				min-width: 100px;   
                max-width: 200px;
                height: 35px;
                padding: 0 18px;
                border: 1px solid #FCD533;
                border-radius: 6px;
                letter-spacing: 0.4px;
                display: flex;
                justify-content: center;
                align-items: center;
			}

			.buttonLong {
				background-color: #ffffff;
				border: 1px solid #FCB108;
				color: #F37621;
				text-align: center;
				letter-spacing: 1.5px;
				width: 100%;
				height: 37px;
				font-size: 15px;
				border-radius: 20px;
			}

			.t1{
				font-family: Noto Sans;
				font-size : 14px;
				color: grey;
			}

			.coupon_ticket{
				background-image: url("{{ asset('website/redemption_history/coupon_bg.png') }}");
				background-repeat: no-repeat;
				background-position: center;
				background-size: 80% 95%;
				padding-top: 2%;
				margin-bottom: 10px;
				min-height: 120px;
				min-width: 340px;
				position: relative;
				/* background-size: 100%;
				padding-top: 15%;
				position: relative;
				height: 25.6%; */
			}

			.coupon_ticket .coupon_brand{
				position: absolute;
				max-height: 80%;
				max-width: 13%;
				left: 13%;
				top: 9%;
			}

			.coupon_ticket .void_icon{
				position: absolute;
				width: 100px;
				left: 70%;
				top: 60%;
			}

			.coupon_ticket .valid_icon{
				position: absolute;
				width: 100px;
				left: 70%;
				top: 60%;
			}


			.coupon_ticket .description{
				position: absolute;
				top: 16%;
    			left: 33%;
				width: 60%;
				text-align: left;
			}

			.coupon_ticket .description .title{
				/* position: absolute; */
				font-family: Noto Sans;
				font-size: 18px;
				color: #57524F;
				text-align: left;
				display: inline-block;
			}

			.coupon_ticket .description .subtitle{
				font-family: Noto Sans;
				font-size: 13px;
				color: #57524F;
				text-align: left;
				display: inline-block;
			}

			#bear{
				display: none;
			}

			.popup_modal {
				display: none; /* Hidden by default */
				position: fixed; /* Stay in place */
				z-index: 1; /* Sit on top */
				padding-top: 100px; /* Location of the box */
				left: 0;
				top: 0;
				width: 100%; /* Full width */
				height: 100%; /* Full height */
				overflow: auto; /* Enable scroll if needed */
				background-color: rgb(0,0,0); /* Fallback color */
				background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
			}

			.popup_modal #popup_window{
				background-image: url("{{ asset('website/redemption_history/popup_bg.png') }}?v=1");
				background-repeat: no-repeat;
				background-size: contain;
				width: 200px;      

			}

			.popup_window #close_button{
				background-image: url("{{ asset('website/redemption_history/popup_clsoe.png') }}?v=1");
				background-repeat: no-repeat;   
				left: 50px;
				top: 50px;
			}

			.popup_window #content{
				overflow: auto;
				font-family: Noto Sans;
				font-size: 18px;
				color: #57524F;
			}

			#upper{
				max-width: 700px;
			}


			@media(max-width: 800px){
				#upper{
					max-width: 90%;
				}

				.content .solid{
					font-size: 0.8em;
				}
			}

			@media (max-width: 1365px) {
				.logo__desktop {
					display: none;
				}

				.content .solid{
					font-size: 2vh;
				}

				.t1{
					font-size : 12px;
				}

				.tag{
					left:5%;
				}

				.coupon_ticket{
					background-image: url("{{ asset('website/redemption_history/coupon_m_bg.png') }}?v=1");
					/* left: 5%; */
					background-size: 85% 95%;
				}

				.coupon_ticket .coupon_brand{
					left: 10%;
					top: 13%;
					min-height: 40%;
					min-width: 82px;
				}

				.coupon_ticket .description{
    				left: 38%;
				}

				.coupon_ticket .description .title{
					text-align: left;
					left: 38%;
					white-space: normal;
					line-height: 23px;
					width: 84%;
				}

				.coupon_ticket .description .subtitle{
					line-height: 23px;
					display:flex;
				}

				.coupon_ticket .void_icon{
					width: 89px;
					left: 63%;
					top: 62%;
				}

				.coupon_ticket .valid_icon{
					width: 89px;
					left: 63%;
					top: 62%;
				}

				#pointIcon{
					width:10px;
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

			/* #tag04  {
				display: none;
			} */

			/* for alert box style */
			.notify{  
				position:fixed;
				top:68px;
				width:100%;
				height:0;
				box-sizing:border-box;
				color:white;  
				text-align:center;
				background:rgba(252, 204, 8, 0.7);
				overflow:hidden;
				box-sizing:border-box;
				transition: height .3s;
			}

			#notifyType:before{
				display:block;
				margin-top:12px; 
			}

			.active{  
				height:42px;
			}

			.copylink:before{
				Content:"已複製換領碼";
			}
		</style>

		<script charset="utf-8" type="text/javascript" src="https://js.hsforms.net/forms/shell.js"></script>

		<!--help to ensure the left menu in right size-->
		<link rel="stylesheet" href="./offers/common/offer_listing.css?v=1" />

	</head>

	<body>
		@include('website/common/tracking_body')

		<!--  Segment  -->
	{{--	<script>
			!function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","reset","group","track","ready","alias","debug","page","once","off","on","addSourceMiddleware","addIntegrationMiddleware","setAnonymousId","addDestinationMiddleware"];analytics.factory=function(e){return function(){var t=Array.prototype.slice.call(arguments);t.unshift(e);analytics.push(t);return analytics}};for(var e=0;e<analytics.methods.length;e++){var key=analytics.methods[e];analytics[key]=analytics.factory(key)}analytics.load=function(key,e){var t=document.createElement("script");t.type="text/javascript";t.async=!0;t.src="https://cdn.segment.com/analytics.js/v1/" + key + "/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(t,n);analytics._loadOptions=e};analytics._writeKey="W1yJ9TYonvWx2FlA6469eTyvX0xegGS3";analytics.SNIPPET_VERSION="4.13.2";
				analytics.load("{{ env('SEGMENT_ID') }}");
				analytics.page("login", {
					ip: "{{ $ipAddress }}",
					userAgent: "{{ $userAgent }}",
				});
			}}();
		</script>--}}
		<!--  End Segment  -->

		<!-- <div class="wrapper"> -->

			<div class="offer">
				@include('campaigns/common/header')
			</div>

			<div class="content">

				<div id="upper" class="container">
					<div class="row" style="padding-bottom:30px; ">
						<div class="col"></div>
						<div class="col-4"><img src="{{ asset('website/point_history/icon_profile.png') }}" alt="icon_profile" style="width:100px; padding:0px;" /></div>
						<div class="col-4 justify-content-center">
								<p style="font-size:20px">我的積分</p>
								<p class="solid">{{ $pointBalance<0? 0:$pointBalance }} <img id="pointIcon" src="{{ asset('website/point_history/icon_point_p.png') }}" alt="p01" style="width:15%;min-width:20px;" /></p>
						</div>
						<div class="col"></div>
					</div>
					<div class="row">
						<!-- <div class="col-1"></div> -->
						<div class="col">
							<a href="{{ route('website.point_history.html') }}"> 
								<button class="buttonLong" type="button" id="submitButton1" >我的積分紀錄</button>
							</a>
						</div>
						<div class="col">
							<a href="{{ route('website.redemption.html') }}"> 
								<button class="buttonLong" type="button" id="submitButton2" >立即兌換獎賞</button>
							</a>
						</div>
						<!-- <div class="col-1"></div> -->
					</div>
					<br>
					<h1><img src="{{ asset('website/redemption_history/icon_flower_pt.png') }}" alt="p01" style="width:32px;" /> 我的獎賞記錄</h1>
					<br>
					<br>
						<div class="tag_container">
							<div class="tag" id="tag01" listtype="0">
								<img src="{{ asset('website/redemption_history/tags01.png') }}" alt="tag01"/>
								<div class="tag_s" id="tag01_s"><img src="{{ asset('website/redemption_history/tags01_selected.png') }}" alt="tag01s"/></div>
							</div>
							<div class="tag" id="tag02" listtype="1">
								<img src="{{ asset('website/redemption_history/tags02.png') }}" alt="tag02"/>
								<div class="tag_ss" id="tag02_s"><img src="{{ asset('website/redemption_history/tags02_selected.png') }}" alt="tag02s"/></div>
							</div>
							<div class="tag" id="tag04" listtype="2">
								<img src="{{ asset('website/redemption_history/tags04.png') }}" alt="tag04"/>
								<div class="tag_ss" id="tag04_s"><img src="{{ asset('website/redemption_history/tags04_selected.png') }}" alt="tag04s"/></div>
							</div>
						</div>

				</div>

			</div>

	<div class="container-fluid" >
		<br>
		<div class="row" >
			<div class="col-md" ></div>
			<div class="col-md-6 justify-content-center" >
				<div id="first_show" class="list">
<!--the first time to load the page if having redemption coupon-->
@foreach( $redemptionHistoryArray as $redemptionHistory )
					<div class="coupon_ticket" data-id="{{ $redemptionHistory->id }}">
						<!-- <img id="coupon_brand" src="{{asset('redemptions/'.$redemptionHistory->redemption->thumbnail_filename ) }}" /> -->
						<img class="coupon_brand" src="{{empty($redemptionHistory->redemption->thumbnail_filename)? asset('redemptions/empty.png'):asset('redemptions/'.$redemptionHistory->redemption->thumbnail_filename) }}" />
						<div class="description">
							<div class="title">{{ $redemptionHistory->redemption->title["zh-HK"]?? '' }}</div>
							<div class="subtitle">{{ $redemptionHistory->redemption->subtitle["zh-HK"] ?? '' }}</div>
						</div>
@if(strtotime($redemptionHistory->expire_at) > time() || !isset($redemptionHistory->expire_at))
						<img id="showRedemptionDetail" redemp-id="{{ $redemptionHistory->redemption_id }}" class="valid_icon" src="{{ asset('website/redemption_history/coupon_valid.png') }}" />  			
@else  
						<img class="void_icon" src="{{ asset('website/redemption_history/coupon_expired.png') }}"/> 					
@endif                         
					</div>                 
@endforeach                       
				</div>
			</div>
			<div class="col-md"></div>

		</div>
<!--the first time to load the page if no redemption coupon-->
@if(isset($redemptionHistoryArray) && count($redemptionHistoryArray)== 0)
			<div id="once_bear">
				<div class="row">
					<br><i>尚未有獎賞記錄，快啲去兌換啦﹗<br>
					<img src="{{ asset('website/redemption_history/icon_bear.png') }}" alt="nth" id="nth" style="width:300px; " /></i>
				</div>
				<p id="base"></p>
			</div>
@endif

<!--for JS part to load the bear if no redemption coupon-->
			<div id="bear">
				<div class="row">
					<br><i>尚未有獎賞記錄，快啲去兌換啦﹗<br>
					<img src="{{ asset('website/redemption_history/icon_bear.png') }}" alt="nth" id="nth" style="width:300px; " /></i>
				</div>
				<p id="base"></p>
			</div>

	</div>


		<style>
			.gift {
				margin-bottom: 46px;
				display: flex;
				justify-content: center;
				align-items: center;
			}
			@media (max-width: 600px) {
				.gift {
					min-width: initial;
					width: 100%;
				}
			}
			.gift .image {
				width: 104px; height: 104px;
				background-image: var(--background-image);
				background-repeat: no-repeat;
				background-size: contain;
				padding-right: 20px;
				border-right: 1px solid #F3884B;
				box-sizing: initial;
			}
			.gift .detail {
				min-width: 320px;
				min-height: 104px;
				padding: 14px 0 14px 20px;
				letter-spacing: 1.1px;
				display: flex;
				align-items: center;
			}
			@media (max-width: 600px) {
				.gift .image {
					padding-right: 6%;
				}
				.gift .detail {
					min-width: initial;
					padding-left: 6%;
				}
			}
			.gift .detail .info {
				grid-column: 1 / span 2;
				grid-row: 1;
			}
			.gift .detail .info .title {
				font: normal normal 800 18px/28px Noto Sans;
			}
			.gift .detail .info .subtitle {
				margin-bottom: 4px;
				font: normal normal 800 16px/22px Noto Sans;
			}
			@media (max-width: 600px) {
				.gift .detail .info .title,
				.gift .detail .info .subtitle {
					display: inline-block;
				}
				.gift .detail .info .title {
					font: normal normal 800 16px/22px Noto Sans;
				}
				.gift .detail .info .subtitle {
					font: normal normal 800 14px/18px Noto Sans;
				}
			}
		</style>
		<style>
			.popup {
				position: fixed;
				left: 0; top: 0;
				width: 100vw;
				height: 100%;
				background: rgba(0, 0, 0, 0.3);
				z-index: 2;
				display: none;
			}
			.popup.gift-detail .popup-wrapper {
				position: absolute;
				left: 50%; top: 50%;
				transform: translate(-50%, -50%);
				max-width: 820px;
				width: 80%;
				height: 510px;
				margin-top: 50px;
				background: #FFF;
				border: 4px solid #F5CD47;
				border-radius: 64px;
			}
			.popup.gift-detail .popup-wrapper::before {
				content: ' ';
				position: absolute;
				left: 50px; top: -75px;
				width: 118.5px;
				height: 75.5px;
				background: url("{{ asset('website/filter_bear_v1.png') }}") no-repeat center center;
				background-size: contain;
				display: inline-block;
			}
			@media (max-width: 768px) {
				.popup.gift-detail .popup-wrapper {
					left: 0; top: initial; bottom: 0;
					transform: translate(0, 0);
					max-width: initial;
					width: 100%;
					height: 75%;
					padding-top: 50px;
					border: 0;
					border-radius: 64px 64px 0 0;
				}
				.popup.gift-detail .popup-wrapper::before {
					top: -70px;
				}
			}
			.popup.gift-detail .popup-wrapper .close-button {
				position: absolute;
				right: -17px; top: -17px;
				width: 35px; height: 35px;
				background: url("{{ asset('website/redemption/redemption_centre_after_login_cross_button.png') }}") no-repeat center center;
				background-size: contain;
			}
			@media (max-width: 768px) {
				.popup.gift-detail .popup-wrapper .close-button {
					right: 39px; top: 12px;
				}
			}
			.popup.gift-detail .popup-wrapper .detail-wrapper {
				height: 100%;
				border-radius: 60px;
				display: flex;
				flex-direction: column;
				overflow: hidden;
			}
			@media (max-width: 768px) {
				.popup.gift-detail .popup-wrapper .detail-wrapper {
					border-radius: 0;
				}
			}
			.popup.gift-detail .popup-wrapper .gift {
				margin: 36px auto 18px auto;
				max-width: 90%;
			}
			.popup .popup-wrapper .gift .image {
				padding-right: 40px;
			}
			.popup .popup-wrapper .gift .detail {
				padding: 4px 0 4px 20px;
				flex: 1 1 0;
			}
			@media (max-width: 800px) {
				.popup .popup-wrapper .gift {
					width: 90%;
				}
				.popup .popup-wrapper .gift .image {
					padding-right: 20px;
				}
				.popup .popup-wrapper .gift .detail {
					min-width: initial;
				}
			}
			.popup .popup-wrapper .gift .detail .info .title,
			.popup .popup-wrapper .gift .detail .info .subtitle {
				color: #707070;
				display: inline-block;
			}
			.popup .popup-wrapper .gift .detail .info .title {
				font: normal normal 800 18px/24px Noto Sans;
			}
			.popup .popup-wrapper .gift .detail .info .subtitle {
				font: normal normal 800 16px/20px Noto Sans;
			}
			@media (max-width: 560px) {
				.popup .popup-wrapper .gift .detail .info .title {
					font: normal normal 800 4.6vw/1.5 Noto Sans;
				}
				.popup .popup-wrapper .gift .detail .info .subtitle {
					font: normal normal 800 4vw/1.5 Noto Sans;
				}
			}
			.popup.gift-detail .popup-wrapper .content {
				padding: 20px;
				margin: 0 10px 10px 10px;
				text-align: left;
				overflow-y: scroll;
				scrollbar-width: thin;
				scrollbar-color: rgba(243, 118, 33, 0.7) #F8F8F8;
				flex: 1 1 0;
			}
			.popup.gift-detail .popup-wrapper .content p {
				font-size: initial;
			}
			.popup.gift-detail .popup-wrapper .redemption {
				margin-bottom: 24px;
			}
			.popup.gift-detail .popup-wrapper .redemption .section-title {
				margin-bottom: 16px;
				text-align: left;
			}
			.popup.gift-detail .popup-wrapper .redemption .code {
				padding: 10px 0;
				text-align: -webkit-center;
				background: #FFFAE6;
			}
			.popup.gift-detail .popup-wrapper .redemption .copybutton {
				height: 35px;
    			width: 35px;
				background: url("{{ asset('website/redemption_history/button_copycode.png') }}") no-repeat ;
				background-size:  23px 25px;
				float: right;
				border: 0px;
			}
			.popup.gift-detail .popup-wrapper .content::-webkit-scrollbar {
				width: 4px;
				height: 4px;
			}
			.popup.gift-detail .popup-wrapper .content::-webkit-scrollbar-track {
				box-shadow: inset 0 0 5px #EEE;
				border-radius: 10px;
			}
			.popup.gift-detail .popup-wrapper .content::-webkit-scrollbar-thumb {
				background: #CCC;
				border-radius: 10px;
			}
		</style>

		<div class="popup gift-detail">

			<!-- alert msg -->
			<div class="notify"><span id="notifyType" class=""></span></div>
			
			<div class="popup-wrapper">
				<a href="javascript:void(0)" class="close-button"></a>
				<div class="detail-wrapper">
				
				</div>
			</div>
			</div>
			<script id="gift-detail-popup-template" type="text/x-handlebars-template">
			<div class="gift">
				<div class="image" style="--background-image:url('{{ asset('redemptions') }}/@{{thumbnail_filename}}')"></div>
				<div class="detail">
					<div class="info">
						<div class="title">@{{title.zh-HK}}</div>
						<div class="subtitle">@{{subtitle.zh-HK}}</div>
					</div>
				</div>
			</div>
			<div class="content">
				<div class="redemption">
					<div class="section-title">換領碼﹕</div>
					<!--Showing QR code or Barcode or text code HERE-->
					<div id="result_code" class="code">__ResultCode___</div>
				</div>
				<br>
			@{{{void_details.zh-HK}}} 
		</div>

		</script>
		<script src="{{ asset('assets/vendor/handlebars/handlebars.js') }}"></script>
		<script>
			var _textCode = "";

			(function(document, window, undefined) {
				const giftDetailPopupEle = document.querySelector('.popup.gift-detail');
				const popupDetailCloseButtonEle = giftDetailPopupEle.querySelector('a.close-button');
				popupDetailCloseButtonEle.onclick = function() {
					giftDetailPopupEle.style.display = 'none';
			}
			})(document, window, undefined);
			</script>


			<!-- @include('website/common/footer') -->
			<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script> 
			<script type="text/javascript">

			$(document).ready(function() {

			let isLoadingGiftDetail = false;
			const giftDetailPopupEle = document.querySelector('.popup.gift-detail');
			const giftDetailPopupTemptlate = Handlebars.compile(document.getElementById('gift-detail-popup-template').innerHTML);

			function initCouponTicketEvent() {
				let couponTicketEles = document.querySelectorAll('.coupon_ticket');
				couponTicketEles.forEach(couponTicketEle => {
					couponTicketEle.onclick = function() {

					if (isLoadingGiftDetail) return false;
					isLoadingGiftDetail = true;

					const id = this.dataset.id; // id = redemption history id 

				
					fetch('{{ route("website.myrewards.detail.json") }}', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json',
							'X-CSRF-TOKEN': '{{ csrf_token() }}'
						},
						body: JSON.stringify({"id": id})
					})
					.then(response => response.json())
					.then(data => {
						if (data.status == 'success') {
						
							let html = giftDetailPopupTemptlate(data.data);

							var data_temp = JSON.stringify(data.data);
							obj_data = JSON.parse(data_temp);
							// console.log(obj_data);
						
							let obj_code = obj_data.code;
							let void_status = obj_data.void_at;
							// console.log(obj_code);

							if (void_status == null){

								if (obj_data.code_type == 'qrcode'){
									let url = '{{ route("website.qrcode.image") }}';
									var temp_qrcode = `<img src="${url}?c=${obj_code}" style="width:200px; height:200px;">`;
									html = html.replace('__ResultCode___', temp_qrcode);

								}else if (obj_data.code_type == 'barcode'){
									let url = '{{ route("website.barcode.image") }}';
									var temp_barcode = `<img src="${url}?c=${obj_code}" style="width:300px; height:130px;">`;
									html = html.replace('__ResultCode___', temp_barcode);

								}else if (obj_data.code_type == 'url'){
									var code = obj_code;
									let result = code.includes("http", 0);
									if (result == false){code = "//" + code;}
									var url = `<a href="${code}" target="_blank">${obj_code}<\a>`;
									html = html.replace('__ResultCode___', url);

								}else{
									_textCode = obj_code;
									var copyBtn = obj_code + `<button class="copybutton" onclick=copyCode(); ></button>`;
									html = html.replace('__ResultCode___', copyBtn);
								}

							}else{	
									html = html.replace('__ResultCode___', "<p style=\"color:red;\">優惠已用</p>");
							}

							const detailWrapperEle = giftDetailPopupEle.querySelector('.detail-wrapper');
							if ( !detailWrapperEle ) return false;

							detailWrapperEle.innerHTML = html;
							giftDetailPopupEle.style.display = 'block';
						} else {
							alert('發生錯誤，請稍後再試。(#90)');
						}
					})
					.catch((error) => {
						alert('發生錯誤，請稍後再試。(#91)');
					})
					.finally(() => {
						isLoadingGiftDetail = false;
					});
				}
			});
		}

		initCouponTicketEvent();

		$(".tag").click(function(){

				var selecttype = $(this).attr("listtype");
			
				let renew = '';
				let brandimgpath ='';
				let rid = '';
			
				$.ajax({
					type: 'GET',
					// attr for selecttype: 0 - select all redemption, 1 - select the valid redemption, 2 - select the expired redemption
					url: '/my-rewards/' + selecttype, 
				
					success: function(response)
					{   
						// console.log(response);
						var temp = JSON.stringify(response);
						obj = JSON.parse(temp);
						renew = '';
						var nowDate = new Date();

						if (Object.keys(obj.redemptionHistoryArray).length>0){
							for (var i=0; i <Object.keys(obj.redemptionHistoryArray).length; i++){console.log(obj.redemptionHistoryArray[i]);
							
								//check the thumbnail exist, show empty.png if not
								if ((obj.redemptionHistoryArray[i].redemption.thumbnail_filename) != "" ){
									brandimgpath = 'redemptions' + '/' ;
									brandimgpath += obj.redemptionHistoryArray[i].redemption.thumbnail_filename ;
								}else{
									brandimgpath = 'redemptions/empty.png' ;
								}

								rid = obj.redemptionHistoryArray[i].redemption_id;
								
								renew += '<div class="coupon_ticket" data-id="' + obj.redemptionHistoryArray[i].id + '">';
								renew += `<img class="coupon_brand" src="{{asset('${brandimgpath}') }}" />`;
								renew += '<div class="description"><div class="title">' + obj.redemptionHistoryArray[i].redemption.title['zh-HK'] +`</div>`;
								renew += '<div class="subtitle">' + obj.redemptionHistoryArray[i].redemption.subtitle['zh-HK']+`</div>`;
								renew += `</div>`;

								if(obj.redemptionHistoryArray[i].expire_at == null){
									renew += `<img id="showRedemptionDetail" redemp-id="${rid}" class="valid_icon" src="{{ asset('website/redemption_history/coupon_valid.png') }}"/>`;
								}else{
									var expireAt = new Date(obj.redemptionHistoryArray[i].expire_at);
									if (expireAt > nowDate){
										renew += `<img id="showRedemptionDetail" redemp-id="${rid}" class="valid_icon" src="{{ asset('website/redemption_history/coupon_valid.png') }}"/>`;
									}else{
										renew += '<img class="void_icon" src="{{ asset('website/redemption_history/coupon_expired.png') }}"/>'; 
									}
								}
								renew += '</div>';
							}

							document.getElementById("first_show").innerHTML = renew;
							document.getElementById("first_show").style.display="block";
							document.getElementById("bear").style.display="none";

							initCouponTicketEvent();

						}else{

							document.getElementById("first_show").style.display="none";
							document.getElementById("bear").style.display="block"

						}
						document.getElementById("once_bear").style.display="none";
					}
				});

			});        

			const tag_01 = document.getElementById("tag01");
			const tag_02 = document.getElementById("tag02");
			const tag_04 = document.getElementById("tag04");

			tag_01.addEventListener("click", function(){
				document.getElementById("tag01_s").style.display="block";
				document.getElementById("tag02_s").style.display="none";
				document.getElementById("tag04_s").style.display="none";
			},false);

			tag_02.addEventListener("click", function(){
				document.getElementById("tag01_s").style.display="none";
				document.getElementById("tag02_s").style.display="block";
				document.getElementById("tag04_s").style.display="none";
			},false);

			tag_04.addEventListener("click", function(){
				document.getElementById("tag01_s").style.display="none";
				document.getElementById("tag02_s").style.display="none";
				document.getElementById("tag04_s").style.display="block";
			},false);

			});

			function copyCode(){

				navigator.clipboard.writeText(_textCode)
				.then(() => {
						$(".notify").toggleClass("active");
						$("#notifyType").toggleClass("copylink");
						setTimeout(function(){
							$(".notify").removeClass("active");
							$("#notifyType").removeClass("copylink");
						},1500);
					})
			}

			</script>
			
	</body>
</html>
