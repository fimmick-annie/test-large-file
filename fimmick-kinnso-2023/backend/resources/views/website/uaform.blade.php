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

		.form {
			max-width: 800px;
			margin: auto;
		}

		.form label.necessary::after {
			content: '*';
			color: #F37621;
			padding-left: 2px;
		}

		.form small {
			font-size: calc(11px);
		}

		.form small.error {
			font-size: calc(13px);
		}

		.form .form-control{
			background: var(--theme-pale-yellow);
			border: 1px solid var(--theme-pale-yellow);
			padding: .2rem .75rem;
			border-radius: 5px;
		}

		.form textarea.form-control {
			background: #ffffff;
			border: 1px solid #F5CD47;
			border-radius: 8px;
			resize: none;
			height: 150px;
		}

		.form textarea.form-control::placeholder {
			letter-spacing: 2.6px;
			color: #6D6D6D;
			opacity: 50%;
		}


		.form .upload-label {
			background: var(--theme-pale-yellow);
			border: 1px solid var(--theme-pale-yellow);
			padding: 0.2rem 0.75rem;
			display: block;
			text-align: center;
			font-family: Arial, "Microsoft JhengHei Bold";
		 	letter-spacing: 2.8px;
		 	border-radius: 5px;
		}

		button.submit-btn{
			display: block;
			margin: auto;
			letter-spacing: 4.8px;
			background-color: #ffffff;
			color: #F37621;
			font-size: 16px;
			border: 1px solid #FBCB30;
			border-radius: 31px;
			padding: 10px 130px;
			cursor: pointer;
		}

		p{
			font-size: calc(10px);
			color: #707070;
			text-align: left;
		}

		@media (max-width: 1365px)  {
			.logo__desktop  {display: none;}
		}
		@media (min-width: 1366px) {
			.logo__mobile  {display: none;}

			.content h1 {
				font-size: calc(0.8rem + 1 * 13.66px);
				padding: calc(20px) calc(60px);
			}

			.content h1::after, .content h1::before {
				font-size: calc(1.5rem + 1 * 13.66px);
			}

			.reward p, .form label {
				margin: 0;
				font-size: calc(14px + 0.3 * 13px);
			}

			.form small {
				font-size: calc(14px);
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
				<img src="{{ asset('website/uaform/UAbanner.png')  }}" alt="banner" class="bear" style="max-width:70%; max: height 200px;">
			</div>

			<div class="reward">
				<div>
					<!-- <small>* 只適用於已成功批核並提取HK$10,000或以上的全新客戶。</small><br><br> -->
					<small>* 使用指定 <a href="https://bit.ly/3BxNcu8"> 連結 </a>於2022年8月31日前遞交申請表，<br>並於2022年9月6日或之前成功批核及提取HK$10,000或以上，<br>再於提取後7日內填悉此張表格。一經確認貸款由Kinnso申請，<br>
					Kinnso將會以WhatsApp將超市電子禮券傳送至閣下<br>於此表格所提供的WhatsApp電話號碼，回覆時間約需1個月。 </small><br><br>
					<small>* 如有其他查詢，請電郵至<a href="mailto: general@kinnso.com">general@kinnso.com</a>。</small>
				</div>
			</div>

			<form action="{{ route('website.store.uaform.html') }}" method="POST" class="form mt-4 px-4" enctype="multipart/form-data"> 
				<div class="form-question px-4">
					<div class="form-group mb-2 px-1">
						<label for="name" class="necessary mb-2">姓名</label>
						<input type="text" maxlength="50" class="form-control" id="name" data-error="name-error" name="name" value="{{ old('name') }}">
						<small class="error text-danger invisible" id="name-error">請填寫姓名(只包括英文或中文)</small>

					</div>

					<div class="form-group mb-2 px-1">
						<label for="whatsapp_number" class="necessary mb-2">WhatsApp 電話號碼</label>
						<input type="text" class="form-control" id="whatsapp_number" data-error="whatsapp_number-error" name="whatsapp_number" value="{{ old('whatsapp_number') }}" maxlength="8">
						<small class="error text-danger invisible" id="whatsapp_number-error">請填寫正確 WhatsApp 電話號碼</small>
					</div>

					<div class="form-group mb-2 px-1">
						<label for="account_number" class="necessary mb-2">貸款賬戶號碼 (例："5xxxxxxxxxx-01")</label>
						<input type="text" class="form-control" id="account_number" data-error="account_number-error" name="account_number" value="{{ old('account_number') }}" maxlength="14">
						<small class="error text-danger invisible" id="account_number-error">請填寫正確 貸款賬戶號碼</small>
					</div>

					<div class="form-group mb-4">
						<div class="row">
							<fieldset>
								<input type="checkbox" id="confirm_rightinfo" name="confirm_rightinfo" data-error="confirm_rightinfo-error" >
								<label for="confirm_rightinfo"><span>本人確定以上資料正確。如有填錯，將有機會未能領取超市禮劵。</span></label>
								<small class="error text-danger invisible" id="confirm_rightinfo-error"><br>提交前，閣下需確定以上資料正確</small>
							</fieldset>
							<fieldset>
								<input type="checkbox" id="accept_whatsappnotice" name="accept_whatsappnotice" data-error="accept_whatsappnotice-error" >
								<label for="accept_whatsappnotice"><span>本人同意以WhatsApp接收Kinnso最新優惠資訊！(日後可取消)</span></label>
								<small class="error text-danger invisible" id="accept_whatsappnotice-error"><br>請先選剔</small>
							</fieldset>
						</div>
					</div>

					<div class="form-group mb-4">
						<div class="row">
							<p>* 優惠期有限並受條款及細則約束，詳情請向亞洲聯合財務有限公司客戶服務主任查詢。<br>
							  亞洲聯合財務有限公司保留一切有關批核借貸申請之權利。<br>忠告：借錢梗要還，咪俾錢中介<br>
							  查詢及投訴熱線：2681 8888 放債人牌照號碼：1034/2021</p>
						</div>
					</div>
				</div>

				<input type="hidden" name="_token" value="{{ csrf_token() }}" />
				<button type="submit" class="submit-btn mb-5">提交</button>
			</form>

			<script>

				document.addEventListener('DOMContentLoaded', () => {
					// Error Handling
					@foreach($errors->getMessages() as $key => $message)
					if ('{{$key}}' === 'file') {
						const error = JSON.parse('{!! json_encode($message) !!}')[0];
						console.log(error);
					} else {
						showErrorMessage(document.getElementById('{{ $key }}'))
					}
					@endforeach

					const form = document.querySelector('form');
					const nameInput = document.getElementById('name');
					const whatsappNumberInput =  document.getElementById('whatsapp_number');
					const accountInput = document.getElementById('account_number');
					const rightinfo = document.getElementById('confirm_rightinfo');
					const whatsappnotice = document.getElementById('accept_whatsappnotice');
					const requiedInput = [nameInput, whatsappNumberInput, accountInput];

					form.addEventListener('submit', (event) => {
						event.preventDefault();
						const valid = isFormValidate();
						if(!valid){
							return;
						}
						form.submit();
					})

					requiedInput.forEach(ele => {
						const errorId = ele.getAttribute('data-error');
						const errorEle = document.getElementById(errorId);
						ele.addEventListener('keyup', event => {
							if (!errorEle.classList.contains('invisible') && event.target.value !== '') {
								hideErrorMessage(event.target);
							}
						})
					})

					nameInput.addEventListener('keypress', (event) => {
						const keyCode = event.which ? event.which : event.keyCode;
						// check the ascii code
						if (!((65 <= keyCode && keyCode <= 90)||(97 <= keyCode && keyCode <= 122) )){
							event.preventDefault();
							return;
						}
					})

					
					//---------------------
					whatsappNumberInput.addEventListener('keypress', (event) => {
						const keyCode = event.which ? event.which : event.keyCode;
						// check the ascii code
						if (!(keyCode >= 48 && keyCode <= 57)) {
							event.preventDefault();
							return;
						}

						// check the length
						if(whatsappNumberInput.value.length >= 8){
							event.preventDefault();
							return;
						}
					})

					whatsappNumberInput.addEventListener('paste', event => {
						if (whatsappNumberInput.value.length >= 8) {
							event.preventDefault();
							return;
						}

						window.setTimeout(() => {
							const pasteValue = event.target.value;
							if (!(/^\d+$/.test(pasteValue))) {
								event.target.value = event.target.value.replace(/\D/g, '');
							}

							if(whatsappNumberInput.value.length >= 8){
								event.target.value = event.target.value.slice(0, 8);
								return;
							}
						});
					})

					accountInput.addEventListener('keypress', (event) => {
						const keyCode = event.which ? event.which : event.keyCode;
						// check the ascii code
						if (!((keyCode >= 48 && keyCode <= 57 )|| keyCode == 45) ) {
							event.preventDefault();
							return;
						}

						// check the length
						if(accountInput.value.length >= 14){
							event.preventDefault();
							return;
						}
					})

					accountInput.addEventListener('paste', event => {
						if (accountInput.value.length >= 14) {
							event.preventDefault();
							return;
						}

						window.setTimeout(() => {
							const pasteValue = event.target.value;
							if (!(/^\d+$/.test(pasteValue))) {
								event.target.value = event.target.value.replace(/\D/g, '');
							}

							if(accountInput.value.length >= 14){
								event.target.value = event.target.value.slice(0, 14);
								return;
							}
						});
					})

					function isFormValidate() {

						let valid = true;

						requiedInput.forEach(ele => {
                            if(ele.value === '') {
                                valid = false;
                                showErrorMessage(ele);
                            }
                        })

						const numberReg0 = new RegExp('^[A-Za-z\u4e00-\u9fa5]+$');
						if (!numberReg0.test(nameInput.value)) {
                         valid = false;
                         showErrorMessage(nameInput);
                        }

                        const numberReg1 = new RegExp('^[4-9]\\d{7}$');
                        if (!numberReg1.test(whatsappNumberInput.value)) {
                         valid = false;
                         showErrorMessage(whatsappNumberInput);
                        }

						const numberReg2 = new RegExp('^[0-9\-]{14}$');
                        if (!numberReg2.test(accountInput.value)) {
                         valid = false;
                         showErrorMessage(accountInput);
                        }

						if (!rightinfo.checked){
							valid = false;
                         	showErrorMessage(rightinfo);
						}

						if (!whatsappnotice.checked){
							valid = false;
                         	showErrorMessage(whatsappnotice);
						}

						return valid;
					}

				});

				function showErrorMessage(ele) {
					document.getElementById(ele.getAttribute('data-error')).classList.remove('invisible');
				}

				function hideErrorMessage(ele){
					document.getElementById(ele.getAttribute('data-error')).classList.add('invisible');
				}
			</script>
		</div>
	</body>
</html>
