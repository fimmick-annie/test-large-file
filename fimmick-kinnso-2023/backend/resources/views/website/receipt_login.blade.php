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

			.jumbotron{
				/* position: relative; */
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
				right: -2%;
				width: 200px;
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
				left: 50%;
				/*width: 54%; */
				color: white;
				text-align: center;
				font-size: 1.3rem;
				font-family: Noto Sans;
				font-weight: 350;
				transform: translate(-50%,-50%);
			}

			.content_1{
				position: absolute;
				top: 100%;
				left: 50%;
				transform: translate(-50%, -0%);
				/* width: 54%; */
				color: #57524F;
				text-align: center;
				font-size: 21px;
				font-family: Noto Sans;
				font-weight: 350;
			}

			#tnc{
				position: absolute;
				top: 150%;
				left: 50%;
				transform: translate(-50%, -0%);
			}


			label{
				font-size: 13px;
				width: 290px;
				overflow:nowrap;
			}

			.button_1 {
				position: absolute;
				top: 230%;
                background-color: #ffffff;
                border: 0px solid #ffffff;
                color: #ffffff;
                text-align: center;
                width: 300px;
				left: 50%;
				transform: translate(-50%, -0%);
            }

			
			@media (max-width: 700px) {
				.logo__desktop {
					display: none;
				}
				.jumbotron .left_con{
					position: absolute;
					top: 230%;
					left: 0;
					width: 150px;
				}

				.jumbotron .right_con{
					position: absolute;
					top: 250%;
					right: -2%;
					width: 20%;
					min-width: 200px;
				}

				.content_1 h5{
					font-size:1rem;
					width:120%;
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

			#confirm_tnc-error1{
				height: 15px;
				font-size:11px;
				display: none;
			}

			input[type="checkbox"]{
				display:none;
			}

			input[type="checkbox"] + label
			{
				/* background: #999; */
				height: 16px;
				display:inline-block;
				padding: 0 0 0 0px;

				background-image: url("{{ asset('website/receipt_upload/tnc_unchecked.png')}}");
				background-repeat: no-repeat;
				background-size: contain;
				padding-left: 20px;

			}

			input[type="checkbox"]:checked + label
			{
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
				left: 50%;
				transform: translate(-50%, -0%);
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
				<form>
					<div class="jumbotron" >
						<p class="title_1">
							<img src="{{ asset('website/receipt_upload/icon01_upload.png') }}?v=1" alt="member" style="width:20px;" /> 上載收據及記錄
						</p>
						<img class="prof_1" src=" {{asset('website/login_page/login_profile.png') }}?v=1" alt="profile"/>

						<div class="content_1">
							<h5 style="margin-bottom:10%;">請透過WhatsApp登入， <br>然後上載收據或查看記錄！<br></h5 >
							<p id="tnc" style="margin-bottom:10%;">
								{{-- <input type="checkbox" id="confirm_tnc1" name="confirm_tnc1"> --}}
								<label for="confirm_tnc1">如繼續領取優惠，即表示您同意Kinnso <a href="{{ route('website.termsandconditions.html') }}" target="_blank" style="color:#707070;">條款及細則</a> 和 <a href="{{ route('website.privacy.html') }}" target="_blank" style="color:#707070;">私隱政策聲明</a></label>
								<small class="error text-danger" id="confirm_tnc-error1"><br>必須同意以上條款及細則才能繼續</small>
							</p>
							<br>
							<button class="button_1" type="button" id="submitButton1" ><img src="{{ asset('website/receipt_upload/button01_whatsapp.png') }}?v=1" alt="whatsapp"/></button>
						</div>
						<img class="left_con" src="{{ asset('website/login_page/background_left_con.png') }}?v=1" alt="bg1"/>
						<img class="right_con" src="{{ asset('website/login_page/background_right_con.png') }}?v=1" alt="bg2"/>
						</div>

					</div>

					@include('website/common/footer')

				</form>

			</div>

			


		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js?v=2"></script>
		<script src="{{ asset('js/common.js') }}?v=2"></script>
		<script src="{{ asset('js/utils.js') }}?v=2"></script>


		<script type="text/javascript">
				var sender = "{{ env("WHATSAPP_SENDER", "") }}";
				var final_sender = sender.replace("whatsapp:+", "");
				const confirmtnc1 = document.querySelector('#confirm_tnc1');
				const confirmtnc2 = document.querySelector('#confirm_tnc2');
					
				document.getElementById("submitButton1").onclick = function () {
					$("#confirm_tnc-error1").hide();
					// const valid = isFormValidate();
					// if(!valid){
					// 	return
					// };

					window.location.href = "https://wa.me/"+final_sender+"?text="+"我想上載收據";
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
		
		<script>
			console.log("{{ $referrerURL }}");
		</script>
	</body>
</html>

