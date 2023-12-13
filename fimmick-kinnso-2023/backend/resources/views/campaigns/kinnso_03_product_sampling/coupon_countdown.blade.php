<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		@include('campaigns/common/head')

		<link rel="stylesheet" href="{{ asset('offers/common/'.$offer->blade_folder.'/coupon.css') }}?v=4">
		<style>
			body  {
				background-color: #eeeeee;
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
				padding: 23px 30px;
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
			.form__label  {
				color: #5d667e;
			}
			.form__input  {
				border-width: 1px;
				border-color: #dee5e7;
			}
			.form__input::placeholder  {
				color: #bfc0bf;
/* 				text-align: left; */
			}

			#form  {
				margin-left: 5%;
				margin-right: 5%;
			}
			#warningText  {
				font-size: 14px;
				padding-top: 0.5rem;
				color: #707070;
			}

			@media only screen and (max-width:640px)  {
				label  {
					padding-left: 30px;
				}
				label:before  {
					width: 15px;
					height: 15px;
				}
				.checkbox label:before  {
					height: 20px;
					width: 20px;
				}

				input[type=checkbox]:checked + label:before  {
					height: 20px;
					width: 20px;
				}
			}
			@media (max-width: 1365px)  {
				.logo__desktop  {display: none;}
			}
			@media (min-width: 1366px) {
				.logo__mobile  {display: none;}
			}

		</style>
	</head>

	<body>
@if (isset($gtm))
@foreach($gtm as $code)
@if ($code != "")

		<!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $code }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->
@endif
@endforeach
@endif

		<!--  Segment  -->
		<script>
			!function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","reset","group","track","ready","alias","debug","page","once","off","on","addSourceMiddleware","addIntegrationMiddleware","setAnonymousId","addDestinationMiddleware"];analytics.factory=function(e){return function(){var t=Array.prototype.slice.call(arguments);t.unshift(e);analytics.push(t);return analytics}};for(var e=0;e<analytics.methods.length;e++){var key=analytics.methods[e];analytics[key]=analytics.factory(key)}analytics.load=function(key,e){var t=document.createElement("script");t.type="text/javascript";t.async=!0;t.src="https://cdn.segment.com/analytics.js/v1/" + key + "/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(t,n);analytics._loadOptions=e};analytics._writeKey="W1yJ9TYonvWx2FlA6469eTyvX0xegGS3";analytics.SNIPPET_VERSION="4.13.2";
			analytics.load("{{ env('SEGMENT_ID') }}");
			analytics.page("{{ $offer->offer_name }}");
			}}();
		</script>

		<div class="wrapper">
			@include('campaigns/common/header')

			<div>
				<img src="{{ asset('offers/'.$offer->offer_name.'/coupon_countdown_kv.jpg') }}?v=4" alt="Coupon countdown key visual" />
			</div>

			<form id="form" class="form__redemption"><center>
				@csrf
				<input type="hidden" name="offerID" value="{{ $offer->id }}" />

				<div class="form__main" style="width:65%;">
					<div class="form__row">
						<label for="pickedRedemptionStoreCode" class="form__label">分店編碼</label>
						<input type="text" name="pickedRedemptionStoreCode" id="pickedRedemptionStoreCode" class="form__input">
						<p class="errormsg"></p>
					</div>
				</div>
				<div class="form__submitbox">
					<a id="submit" class="form__submit">
						<img src="{{ asset('offers/'.$offer->offer_name.'/coupon_submit_button.png') }}?v=1" alt="">
					</a>
				</div>

			</center></form>

			@include('campaigns/common/footer')
		</div>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js?v=4"></script>
		<script src="{{ asset('js/common.js') }}?v=4"></script>
		<script src="{{ asset('js/utils.js') }}?v=4"></script>
		<script type="text/javascript">
			var form = $('#form');
			var submit = $('#submit');
			var latitude = "{{ $latitude }}";
			var longitude = "{{ $longitude }}";

			var url = window.location.href;
			alert(url+"\n"+longitude+"\n"+latitude);

			if (latitude != 0 && longitude != 0)  {
				$("#pickedRedemptionStoreCode").val("("+latitude+", "+longitude+")");
			}

			submit.on('click', handleSubmit);
			function handleSubmit() {

				var inputs = form.find('input[type="text"]');
				var customerEmail = form.find('input[type="email"]');
				var errorArr = [];

				$.each(inputs, function() {
					var thisValue = $(this).val();
					var thisLabel = $(this).siblings('label').text();

					if (!utils.validate.validateEmpty(thisValue)) {
						errorArr.push({
							targetId: $(this).prop('id'),
							errorMsg: `請輸入${thisLabel}`
						});
					}
				});

				$.each(customerEmail, function() {
					var thisValue = $(this).val();
					var thisLabel = $(this).siblings('label').text();

					if (!utils.validate.validateEmail(thisValue)) {
						errorArr.push({
							targetId: $(this).prop('id'),
							errorMsg: '格式錯誤'
						});
					}
				});

				if (errorArr.length !== 0) {
					utils.validate.error_checker(form, errorArr);
				} else {

					$('.errormsg').text('');
					showLoading();

					//  Form valid, submit now
					var formData = $("#form").serialize();
					$.ajax({
						type: "POST",
						data: formData,
						dataType: "json",
						url: "{{ route('campaign.coupon.void.json', ['unique_code'=>$uniqueCode]) }}",
						async: true,
						headers:  {"cache-control": "no-cache"},
						success: function (result)  {

							switch (result.status)  {
								case 0:  {
									location.href = "{{ route('campaign.coupon.thankyou.html', ['unique_code'=>$uniqueCode]) }}";
									return;
								}

								case -1:  alert("優惠還未開始");  break;
								case -5:  alert("優惠已經結束");  break;
								case -10:  alert("優惠不能重複申請");  break;
								case -20:  {
									utils.validate.error_checker(form, [{
										targetId: $("#pickedRedemptionStoreCode").prop('id'),
										errorMsg: '此分店編碼無效'
									}]);
// 									alert('分店編碼無效');
								}  break;

								default:  {
									alert(result.message);
								}  break;
							}
							if (result.status < 0)  {hideLoading();}
						},
						error: function (XMLHttpRequest, textStatus, errorThrown)  {
							hideLoading();
							alert("Oops...\n#"+textStatus+": "+errorThrown);
						}
					});

				}
			}


		</script>
	</body>
</html>
