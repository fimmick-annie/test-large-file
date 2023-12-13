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

			.jumbotron{
				width:100%;
				height: 250px;
				background-color: #FCCC08;
			}

			.jumbotron .left_con{
				position: fixed;
				top: 70%;
				left: 0;
				width: 200px;
			}

			.jumbotron .right_con{
				position: fixed;
    			top: 80%;
				right: 0;
				width: 250px;
			}

			.content {
				width: 100%;
				text-align: center;
				font-size: 20px;
				color: #000000;
				padding-bottom: 100px;
				padding-left: 0%;
				padding-right: 0%;
				padding-top: 0;
				font-family: kinnsoFont;
				position: relative;
			}

			img{
				display:inline;
			}

			.prof_1{
				position: absolute;
				top: 48%;
				width: 150px;
				left: 50%;
				transform: translate(-50%, -0%);

			}
			.title_1{
				position: absolute;
				top: 35%;
				width: 54%;
				color: white;
				text-align: center;
				font-size: 1.3em;
				font-family: Noto Sans;
				font-weight: 350;
				left: 50%;
				transform: translate(-50%, -0%);
			}

			.content_1{
				position: absolute;
				top: 100%;
				left: 50%;
				width: 54%;
				color: #57524F;
				text-align: center;
				font-size: 1.2rem;
				font-family: Noto Sans;
				font-weight: 350;
				transform: translate(-50%, -0%);
			}

			.button_1 {
				position: absolute;
				top: 144%;
                background-color: #25D366;
                border: 3px solid #ffffff;
                color: #ffffff;
                text-align: center;
                width: 300px;
                height: 42px;
                font-size: 15px;
                border-radius: 20px;
				left: 50%;
				transform: translate(-50%, -0%);
            }

			@media (max-width: 700px) {
				.logo__desktop {
					display: none;
				}
				.title_1{
					font-size: 1em;
				}

			}

			@media (min-width: 700px) {
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

			#confirm_tnc-error1{
				height: 15px;
				font-size:11px;
				display: none;
			}

			#confirm_tnc-error2{
				height: 15px;
				font-size: 11px;
				display: none;
			}

			#tnc{
				position: absolute;
				top: 124%;
				left: 50%;
				transform: translate(-50%, -0%);
				font-family: Noto Sans;
				font-weight: 400;
			}

			label{
				font-size: 0.7rem;
				width: 270px;
				overflow:nowrap;
			}

			input[type="checkbox"]{
				display:none;
			}

			input[type="checkbox"] + label{
				/* background: #999; */
				height: 16px;
				display:inline-block;
				padding: 0 0 0 0px;

				background-image: url("{{ asset('website/receipt_upload/tnc_unchecked.png')}}");
				background-repeat: no-repeat;
				background-size: contain;
				padding-left: 4px;
			}

			input[type="checkbox"]:checked + label{
				/* background: #0080FF; */
				height: 16px;
				display:inline-block;
				padding: 0 0 0 0px;

				background-image: url("{{ asset('website/receipt_upload/tnc_checked.png')}}");
				background-repeat: no-repeat;
				background-size: contain;
				padding-left: 20px;
			}

			.footer{
				bottom: 0%;
    			position: fixed;
			}

		</style>

		<script charset="utf-8" type="text/javascript" src="https://js.hsforms.net/forms/shell.js"></script>

		<!--help to ensure the left menu in right size-->
		<link rel="stylesheet" href="./offers/common/offer_listing.css?v=1" />
	</head>

	<body>
		@include('website/common/tracking_body')

		<!--  Segment  -->
		<script>
			!function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","reset","group","track","ready","alias","debug","page","once","off","on","addSourceMiddleware","addIntegrationMiddleware","setAnonymousId","addDestinationMiddleware"];analytics.factory=function(e){return function(){var t=Array.prototype.slice.call(arguments);t.unshift(e);analytics.push(t);return analytics}};for(var e=0;e<analytics.methods.length;e++){var key=analytics.methods[e];analytics[key]=analytics.factory(key)}analytics.load=function(key,e){var t=document.createElement("script");t.type="text/javascript";t.async=!0;t.src="https://cdn.segment.com/analytics.js/v1/" + key + "/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(t,n);analytics._loadOptions=e};analytics._writeKey="W1yJ9TYonvWx2FlA6469eTyvX0xegGS3";analytics.SNIPPET_VERSION="4.13.2";
				analytics.load("{{ env('SEGMENT_ID') }}");
				analytics.page("login", {
					ip: "{{ $ipAddress }}",
					userAgent: "{{ $userAgent }}",
				});
			}}();
		</script>
		<!--  End Segment  -->

			<div class="offer">
				@include('campaigns/common/header')
			</div>

			<div class="content">
				<div class="jumbotron">
					<p class="title_1"><img src="{{ asset('website/login_page/login_member.png') }}?v=1" alt="member" style="width:35px;" /> Kinnso 會員中心</p>
					<img class="prof_1" src=" {{asset('website/login_page/login_profile.png') }}?v=1" alt="profile"/>
					<p class="content_1">請透過WhatsApp登入。</p>
					<!--whatsapp tracker-->
					<div id="tnc">
						{{-- <input type="checkbox" id="confirm_tnc1" name="confirm_tnc1"> --}}
						<label for="confirm_tnc1">如繼續領取優惠，即表示您同意Kinnso <a href="{{ route('website.termsandconditions.html') }}" target="_blank" style="color:#707070;">條款及細則</a> 和 <a href="{{ route('website.privacy.html') }}" target="_blank" style="color:#707070;">私隱政策聲明</a></label>
						<small class="error text-danger" id="confirm_tnc-error1"><br>必須同意以上條款及細則才能繼續</small>
					</div>
					<br><br>
					<button class="button_1" type="button" alt="" id="submitButton1" ><img src="{{ asset('website/receipt_upload/button01_whatsapp.png') }}?v=1" alt="whatsapp"/></button>
					<img class="left_con" src="{{ asset('website/login_page/background_left_con.png') }}?v=1" alt="bg1"/>
					<img class="right_con" src="{{ asset('website/login_page/background_right_con.png') }}?v=1" alt="bg2"/>
				</div>
			</div>

			@include('website/common/footer')


		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js?v=2"></script>
		<script src="{{ asset('js/common.js') }}?v=2"></script>
		<script src="{{ asset('js/utils.js') }}?v=2"></script>
		
		<script type="text/javascript">

				var sender = "{{ env("WHATSAPP_SENDER", "") }}";
				var final_sender = sender.replace("whatsapp:+", "");
				const confirmtnc1 = document.querySelector('#confirm_tnc1');
					
				document.getElementById("submitButton1").onclick = function () {
					$("#confirm_tnc-error1").hide();
					// const valid1 = isFormValidate();
					// if(!valid1){
					// 	return
					// };
					window.location.href = "https://wa.me/"+final_sender+"?text="+"我要登入Kinnso";
				}

				// function isFormValidate() {
				// 	let valid = true;
				// 	if (!confirmtnc1.checked){
				// 		valid = false;
				// 		$("#confirm_tnc-error1").show();
				// 	}
				// 	return valid;
				// }

		</script>
	</body>
</html>
