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
			font-family: Noto Sans;
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
			font-family: Helvetica Neue;
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
			font-family: Helvetica Neue;
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
		 	font-family: Helvetica Neue;
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
				<h1><span>人人都係蜜探 歡迎</span><span>分享優惠</span></h1>
			</div>

			<div class="reward">
				<img src="{{ asset('website/report-us/outdoor_bear.png')  }}" alt="outdoor_bear" class="bear">
				<div>
					<p>優惠一經採用，即刻獲得 <span>Kinnso {{ config('points.offer_hunting') }} points</span>!</p>
					<p>儲points <a href="{{ route('website.redemption.html') }}">換獎賞</a>！</p>
					{{-- <p>儲points <a href="{{ route('website.aboutus.html') }}">換獎賞</a>！</p>  --}}
				</div>
			</div>

			<form action="{{ route('website.store.offerhunting.html') }}" method="POST" class="form mt-4 px-4" enctype="multipart/form-data">
				<div class="form-question px-4">
					<div class="form-group mb-2 px-1">
						<label for="name" class="necessary mb-2">姓名</label>
						<input type="text" maxlength="50" class="form-control" id="name" data-error="name-error" name="name" value="{{ old('name') }}">
						<small class="error text-danger invisible" id="name-error">請填寫姓名</small>

					</div>

					<div class="form-group mb-2 px-1">
						<label for="whatsapp_number" class="necessary mb-2">WhatsApp 電話號碼</label>
						<input type="text" class="form-control" id="whatsapp_number" data-error="whatsapp_number-error" name="whatsapp_number" value="{{ old('whatsapp_number') }}" maxlength="8">
						<small class="error text-danger invisible" id="whatsapp_number-error">請填寫正確 WhatsApp 電話號碼</small>
					</div>

					<div class="form-group mb-2 px-1">
						<label for="file" class="mb-1">圖片/ 文件上載</label>
						<small class="form-text text-muted d-block mt-0 mb-1">( 檔案格式100MB, JPG, PNG, JPEG,DOC,DOCX )</small>
						<label for="file" class="upload-label"><span>上載</span></label>
						<small class="error text-danger invisible"><span>檔案未能上載，</span><span id="upload-error"></span></small>
						<input type="file" class="custom-file-input invisible" id="file" name="file">
					</div>

					<div class="form-group mb-4">
						<label for="discount_content" class="necessary mb-2 px-1 d-block">優惠內容 / 優惠網址連結</label>
						<textarea class="form-control" id="discount_content" placeholder="在此輸入..." data-error="discount_content-error" name="discount_content">{{ old('discount_content') }}</textarea>
						<small class="error text-danger invisible" id="discount_content-error">請填寫優惠內容 / 優惠網址連結</small>
						<img src="" alt="">
					</div>

					<div class="form-group mb-4">
						<label class="necessary text-wrap fs-6" for="confirm_tnc">
						{{-- <input type="radio" id="confirm_tnc" name="confirm_tnc" data-error="confirm_tnc-error"> --}}
						如繼續領取優惠，即表示您同意Kinnso <a href="{{ route('website.termsandconditions.html') }}" target="_blank" style="color:#707070;">條款及細則</a> 和 <a href="{{ route('website.privacy.html') }}" target="_blank" style="color:#707070;">私隱政策聲明</a></label>
						<small class="error text-danger invisible" id="confirm_tnc-error"><br>必須同意以上條款及細則才能繼續</small>
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
						switch(error) {
							case 'mimes':
								document.querySelector('.upload-label span').innerHTML = '請上傳正確檔案格式';
								document.querySelector('.upload-label span').classList.add('text-danger')
								break;
							case 'max':
								document.querySelector('.upload-label span').innerHTML = '檔案不可以大於 100MB';
								document.querySelector('.upload-label span').classList.add('text-danger')
								break
						}
					} else {
						showErrorMessage(document.getElementById('{{ $key }}'))
					}
					@endforeach

					const confirmtnc = document.getElementById('confirm_tnc');
					const form = document.querySelector('form');
					const nameInput = document.getElementById('name');
					const whatsappNumberInput =  document.getElementById('whatsapp_number');
					const fileInput = document.getElementById('file');
					const discountContentInput = document.getElementById('discount_content');
					const requiedInput = [nameInput, whatsappNumberInput, discountContentInput];
					const allowFileExtension = ['jpg', 'jpeg', 'png', 'doc', 'docx'];

					form.addEventListener('submit', (event) => {
						event.preventDefault()
						const valid = isFormValidate();
						if(!valid){
							return
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

					fileInput.addEventListener('change', (event) => {
						let filePath = event.target.value;

						if (filePath === ''){
							return;
						}

						if (!isFileExtensionValid(filePath) || !isFileSizeValid(event.target)) {
							event.target.value = '';
							document.querySelector('.upload-label span').innerHTML = '上載';
							return;
						}

						const file = filePath.split('\\').slice(-1)[0];
						const fileExtension = file.split('.').slice(-1)[0];
						const lastExtensionIndex = file.lastIndexOf(fileExtension);
						const fileName = file.slice(0, lastExtensionIndex - 1);
						document.querySelector('.upload-label span').innerHTML = fileName.slice(0, 12) + '...' + fileExtension;
						document.getElementById('upload-error').parentElement.classList.add('invisible')
					})

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
							return
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

					function isFormValidate() {
						let valid = true;
						requiedInput.forEach(ele => {
							if(ele.value === '') {
								valid = false;
								showErrorMessage(ele);
							}
						})

						// if (!confirmtnc.checked){
						// 	valid = false;
                        //  	showErrorMessage(confirmtnc);
						// }

						const numberReg = new RegExp('^[4-9]\\d{7}$')
						if (!numberReg.test(whatsappNumberInput.value)) {
							valid = false;
							showErrorMessage(whatsappNumberInput)
						}

						let filePath = fileInput.value;
						if (filePath !== '' && (!isFileExtensionValid(filePath) || !isFileSizeValid(fileInput))) {
							filePath = '';
							document.querySelector('.upload-label span').innerHTML = '上載';
							valid = false;
						}
						return valid;
					}

					function isFileExtensionValid(filePath){
						const file = filePath.split('\\').slice(-1)[0];
						const fileExtension = file.split('.').slice(-1)[0];

						if (allowFileExtension.includes(fileExtension.toLowerCase())) {
							return true;
						} else {
							document.getElementById('upload-error').innerHTML = '請上傳正確檔案格式';
							document.getElementById('upload-error').parentElement.classList.add('text-danger');
							document.getElementById('upload-error').parentElement.classList.remove('invisible');
							return false;
						}
					}

					function isFileSizeValid(ele) {
						const size = ele.files[0].size;
						if(size <= 104857600) {
							return true;
						} else {
							document.getElementById('upload-error').innerHTML = '檔案不可以大於 100MB';
							document.getElementById('upload-error').parentElement.classList.add('text-danger');
							document.getElementById('upload-error').parentElement.classList.remove('invisible');
							return false;
						}
					}
				})

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
