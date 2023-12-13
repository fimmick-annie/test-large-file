<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<!--  Fix IE JS problem  -->
		<script src="https://unpkg.com/core-js-bundle@3.15.2/minified.js"></script>

		@include('campaigns/common/head')

		<meta property="og:title" content="{!! nl2br($offer->offer_title) !!}" />
		<meta property="og:type" content="website" />
		<meta property="og:url" content="{{ route('campaign.offer.details.html', ['offer_code' => $offer->offer_code]) }}" />
		<meta property="og:image" content="{{ asset('offers/'.$offer->offer_name.'/offer_thumbnail.png') }}?v=2" />
		<meta property="og:description" content="{!! $sharingMessage !!}" />

		<link rel="stylesheet" href="{{ asset('offers/common/'.$offer->blade_folder.'/offer_details.css') }}?v=3">
		<style>
			#termsWarning  {
				padding-left: 26px;
				color: #ff0000;
				display: none;
			}
			/* #submitButton  {
				opacity: 0.5;
			} */

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
			.form  {
				padding-left: 0px !important;
				padding-right: 0px !important;
				margin-left: 4%;
				margin-right: 4%;
			}
			.offer__term  {
				margin-bottom: 10px;
				color: #ffffff;
			}
			.offer__term-btn  {
				color: {{ $offer->ini['settings']['theme_button_text_color'] }};
				background-color: {{ $offer->ini['settings']['theme_button_color'] }};
				border: 1px solid {{ $offer->ini['settings']['theme_button_color'] }} !important;
			}
			.offer__term-btn::before  {
				background-color: {{ $offer->ini['settings']['theme_button_text_color'] }} !important;
			}
			.offer__term-btn::after  {
				background-color: {{ $offer->ini['settings']['theme_button_text_color'] }} !important;
			}
			.offer__term a:hover  {
				color: {{ $offer->ini['settings']['theme_button_hover_color'] }};
			}
			.offer__term_text  {
				color: #747474;
				font-size: 14px;
			}

			label  {
				cursor: pointer;
				display: inline-block;
				position: relative;
				padding-left: 25px;
				margin-right: 10px;
				padding-top: 10px;
				padding-bottom: 10px;
			}
			label:before  {
				/* content: ""; */
				width: 15px;
				height: 15px;
				position: absolute;
				left: 0;
			}
			input[type=checkbox] {
				display: none !important;
			}
			.checkbox label:before  {
				background: url("{{ asset('offers/common/checkbox.png') }}?v=2") left top no-repeat;
				background-size: 100%;
				height: 20px;
				width: 20px;
			}

			input[type=checkbox]:checked + label:before  {
				background: url("{{ asset('offers/common/checkbox.png') }}?v=2") left bottom no-repeat;
				background-size: 100%;
				height: 20px;
				width: 20px;
			}

			/* for alert box style */
			.notify{  
				position:fixed;
				top:68px;
				width:100%;
				height:0;
				box-sizing:border-box;
				color:white;  
				text-align:center;
				background:rgba(255,175,25,0.7);
				overflow:hidden;
				box-sizing:border-box;
				transition: height .3s;
			}

			#notifyType:before{
				display:block;
				margin-top:15px; 
			}

			.active{  
				height:50px;
			}

			.copylink:before{
				Content:"已複製結鏈";
			}

			.sharelink:before{
				Content:"結鏈分享!";
			}
			/* End------------for alert box style */

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
		@include('website/common/tracking_body')

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
				let parameter = {
					url: "{{ route('campaign.offer.details.html', ['offer_code'=>$offer->offer_code]) }}",
					offer_code: "{{ $offer->offer_code }}",
					offer_name: "{{ $offer->offer_name }}",
					offer_title: "{{ $offer->offer_title }}",
					offer_subtitle: "{{ $offer->offer_subtitle }}",
					ip: "{{ $ipAddress }}",
					userAgent: "{{ $userAgent }}",
					os: "{{ $operatingSystem }}",
					device: "{{ $device }}",
				};

				var url = window.location.href.slice(window.location.href.indexOf('?')+1).split('&');
				for (var i=0; i<url.length; i++)  {
					var parameterArray = url[i].split('=');
					if (parameterArray[0] == "fbclid" || parameterArray[0] == "gclid")  {
						parameter[parameterArray[0]] = parameterArray[1];
					}
				}

				analytics.load("{{ env('SEGMENT_ID') }}");
				analytics.page("{{ $offer->offer_code }}", parameter);
			}}();
		</script>

		<div class="wrapper">

			@include('campaigns/common/header')

			<div style="padding-top:46px;">
				<img src="{{ asset('offers/'.$offer->offer_name.'/offer_details_kv.jpg') }}?v=2" alt="Product key visual" />
			</div>

			<div class="d-flex flex-row-reverse" style="padding-top:5px;">
				<img id="sharepage_share" src="{{asset('website/sharebtn/button_share.png')}}" style="width: 50px; padding: 10px;">
				<!-- <img id="sharepage_fb" src="{{asset('website/sharebtn/button_facebook.png')}}" style="width: 50px; padding: 10px;">
				<img id="sharepage_cpy" src="{{asset('website/sharebtn/button_copy_link.png')}}" style="width: 50px; padding: 10px;"> -->
			</div>

			<form action="" id="form" class="form">
				@csrf

				<input type="hidden" name="selectedChannel" value="{{ $selectedChannel }}" />
				<input type="hidden" name="confirmationMethod" value="whatsapp" />

				<div class="form__main">

					<div class="form__row">
						<div class="form__full">
							<div class='cursor-pointer d-block d-md-none' style="float:right; width:48px;">
								<img class="shareBtn" data-title='{!! nl2br($offer->offer_title) !!}' data-href="{{ route('campaign.offer.details.html', ['offer_code' => $offer->offer_code]) }}" src='/website/offer-listing/share_0623_v1@2x.png'>
							</div>
							<div style="overflow:hidden;">
{!! $description !!}
							</div>
						</div>
					</div>

					<div class="form__row">
						<div class="form__full">
							<div class="offer__term" >
								<a class="offer__term-btn" id="offerHowButton">如何領取著數</a>
								<div class="offer__term-hidden offer__term_text">
									<img src="{{ asset('offers/'.$offer->offer_name.'/offer_whatsapp_instruction.png') }}?v=2" alt="WhatsApp instruction" />
								</div>
							</div>
						</div>
					</div>

					<div class="form__row">
						<div class="form__full">
							<div class="offer__term" >
								<a class="offer__term-btn" id="offerTermButton">優惠細則及條款</a>
								<div class="offer__term-hidden offer__term_text">
{!! $offer->tnc !!}
								</div>
							</div>
						</div>
					</div>

					<div class="checkbox">
						{{-- <input type="checkbox" id="confirm_tnc" name="confirm_tnc"> --}}
						<label for="confirm_tnc">如繼續領取優惠，即表示您同意Kinnso <a href="{{ route('website.termsandconditions.html') }}" target="_blank">條款及細則</a>和<a href="{{ route('website.privacy.html') }}" target="_blank">私隱政策聲明</a>內所述之條款。</label>
					</div>

					<div id="termsWarning">必須同意以上條款及細則才能繼續</div>
				</div>

				<div class="form__submitbox">
					<center><div>
@if (strtotime($offer->end_at) >= time() && $offer->quota > $offer->quota_issued)
						<a id="submit" class="form__submit">
							<img src="{{ asset('offers/'.$offer->offer_name.'/offer_whatsapp_button.png') }}?v=2" alt="" id="submitButton">
						</a>
@else
						<img src="{{ asset('offers/'.$offer->offer_name.'/offer_no_quota_button.png') }}?v=2" alt="">
@endif
					</div></center>
				</div>

			</form>
			@include('campaigns/common/footer')
		</div>

		<!-- alert msg -->
		<div class="notify"><span id="notifyType" class=""></span></div>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js?v=2"></script>
		<script src="{{ asset('js/common.js') }}?v=2"></script>
		<script src="{{ asset('js/utils.js') }}?v=2"></script>
		<script type="text/javascript">
			var _checkedCount = 0;
			var _requiredCount = 1;
			var _aidToken = "";
			var aid = "";
			let referrerCode = '';
			let memberReferralCode = '';

			$(document).ready(function()  {

				var offerTermButton = $("#offerTermButton");
				offerTermButton.on('click', handleOfferTerm);
				function handleOfferTerm() {
					$(this).parent().toggleClass('offer__term--open');
					analytics.track("read-offer-terms", {
						url: "{{ route('campaign.offer.details.html', ['offer_code'=>$offer->offer_code]) }}",
						offer_code: "{{ $offer->offer_code }}",
						offer_name: "{{ $offer->offer_name }}",
						offer_title: "{{ $offer->offer_title }}",
						offer_subtitle: "{{ $offer->offer_subtitle }}",
						ip: "{{ $ipAddress }}",
						userAgent: "{{ $userAgent }}",
						os: "{{ $operatingSystem }}",
						device: "{{ $device }}",
					});
				}

				var offerHowButton = $("#offerHowButton");
				offerHowButton.on('click', handleOfferHow);
				function handleOfferHow() {
					$(this).parent().toggleClass('offer__term--open');
					analytics.track("read-how-to-get-offer", {
						url: "{{ route('campaign.offer.details.html', ['offer_code'=>$offer->offer_code]) }}",
						offer_code: "{{ $offer->offer_code }}",
						offer_name: "{{ $offer->offer_name }}",
						offer_title: "{{ $offer->offer_title }}",
						offer_subtitle: "{{ $offer->offer_subtitle }}",
						ip: "{{ $ipAddress }}",
						userAgent: "{{ $userAgent }}",
						os: "{{ $operatingSystem }}",
						device: "{{ $device }}",
					});
				}

				$("input[type=checkbox]").change(function()  {

					analytics.track("accept-terms-checkbox", {
						url: "{{ route('campaign.offer.details.html', ['offer_code'=>$offer->offer_code]) }}",
						offer_code: "{{ $offer->offer_code }}",
						offer_name: "{{ $offer->offer_name }}",
						offer_title: "{{ $offer->offer_title }}",
						offer_subtitle: "{{ $offer->offer_subtitle }}",
						ip: "{{ $ipAddress }}",
						userAgent: "{{ $userAgent }}",
						os: "{{ $operatingSystem }}",
						device: "{{ $device }}",
					});

					_checkedCount = 0;
					$("input[type=checkbox]").each(function()  {
						var checked = $(this).is(":checked");
						if (checked == true)  {_checkedCount++;}
					});

					if (_checkedCount == _requiredCount)  {
						$("#submitButton").fadeTo(100, 1.0, null);
					}  else  {
						$("#submitButton").fadeTo(100, 0.5, null);
					}
				});

				$("#submitButton").click(async function()  {
					// if (_checkedCount != _requiredCount)  {
					// 	$("#termsWarning").show();
					// 	return;
					// }

					analytics.track("get-offer-button", {
						url: "{{ route('campaign.offer.details.html', ['offer_code'=>$offer->offer_code]) }}",
						offer_code: "{{ $offer->offer_code }}",
						offer_name: "{{ $offer->offer_name }}",
						offer_title: "{{ $offer->offer_title }}",
						offer_subtitle: "{{ $offer->offer_subtitle }}",
						ip: "{{ $ipAddress }}",
						userAgent: "{{ $userAgent }}",
						os: "{{ $operatingSystem }}",
						device: "{{ $device }}",
					});
					referrerCode = getQueryParam('r');
                    memberReferralCode = getQueryParam('m');

                    rand = Math.floor(Math.random() * 1000) + 1;

                    await $.ajax({
                        type: "POST",
                        data: {aid:aid,referrerCode:referrerCode, memberReferralCode:memberReferralCode },
						dataType: "json",
						url: "{{ route('campaign.offer.aidexchange.json', ['offer_code'=>$offer->offer_code]) }}"+"?_v="+rand ,
						async: true,
						headers:  {"cache-control": "no-cache"},
						success: function (result)  {
							switch (result.status)  {
								case 0:  {
									//  我想領取免費「xxx」！(Reg no.: xxx)
									_aidToken = " Reg no.:"+result.aidToken;
									return;
								}

								default:  {
								}  break;
							}
						},
						error: function (XMLHttpRequest, textStatus, errorThrown)  {
						}
					});
					$("#termsWarning").hide();
					$("#loading").css("display", "flex");
					window.location.href = "{{ $whatsappURL }}"+_aidToken;
				});

				analytics.ready(function()  {
					//  Exchange AID with unique code
					aid = analytics.user().anonymousId();
				});

			});

			function getQueryParam(key) {
				if (this.location && this.location.search) {
					let value = this.location.search.split(key + '=')[1]
					if (value) {
						value = value.split('&')[0];
						return value;
					}
				}
				return '';
			}

			const handleShare = (e) => {

				analytics.track("click-share-offer-button", {
					offer_code: "{{ $offer->offer_code }}",
					offer_name: "{{ $offer->offer_name }}",
					offer_title: "{{ $offer->offer_title }}",
					offer_subtitle: "{{ $offer->offer_subtitle }}",
					url: "{{ route('campaign.offer.details.html', ['offer_code'=>$offer->offer_code]) }}",
					ip: "{{ $ipAddress }}",
					userAgent: "{{ $userAgent }}",
						os: "{{ $operatingSystem }}",
						device: "{{ $device }}",
				});

				if (navigator.share) {
					navigator.share({
// 						title: e.target.dataset.title,
// 						text: "*"+e.target.dataset.title+"*",
						url: e.target.dataset.href
					}).then(() => {
						console.log('Thanks for sharing!');
					})
					.catch(console.error);
				} else {
					// fallback
					console.log('Share not available!');
				}
			}
			document.querySelectorAll('.shareBtn').forEach(el => {
				el.addEventListener('click', handleShare)
			})

			// for share page button
			const shareBtn_share = document.querySelector("#sharepage_share");
			var shareUrl = document.location.href ;
			var shareText = "即拎 Kinnso 推介「 {{ $offer->offer_title }} 」🤩 快啲撳入以下網址：\n"; 

			if (shareUrl.includes('?')){
				var len = shareUrl.indexOf('?');
				shareUrl = shareUrl.substring(0, len);
			}

			const shareData = {
				title: document.title,
				text: shareText,
				url: shareUrl,
			};
			
			shareBtn_share.addEventListener("click", function () {
				
				if (navigator.share) {
					navigator.share(shareData)
					.then(() => {
						$(".notify").toggleClass("active");
						$("#notifyType").toggleClass("sharelink");
						setTimeout(function(){
							$(".notify").removeClass("active");
							$("#notifyType").removeClass("sharelink");
						},3000);
					})
				} else {
					// if no sharing function of device, copy link
					navigator.clipboard.writeText(shareText + shareUrl)
					.then(() => {
						$(".notify").toggleClass("active");
						$("#notifyType").toggleClass("copylink");
						setTimeout(function(){
							$(".notify").removeClass("active");
							$("#notifyType").removeClass("copylink");
						},3000);
					})
					// .catch(() => alert("Cannot copy"));
				}
			});

		</script>
	</body>
</html>
