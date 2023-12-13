<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		@include('campaigns/common/head')
		<meta property="og:title" content="{!! nl2br($offer->offer_title) !!}" />
		<meta property="og:type" content="website" />
		<meta property="og:url" content="{{ route('campaign.offer.details.html', ['offer_code' => $offer->offer_code]) }}" />
		<meta property="og:image" content="{{ asset('offers/'.$offer->offer_name.'/offer_details_kv.jpg') }}?v=2" />
		<meta property="og:description" content="{!! $description !!}" />
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
				color: #ffffff;
				background-color: #cf0d01;
				border: 1px solid #cf0d01 !important;
			}
			.offer__term-btn::before  {
				background-color: #ffffff !important;
			}
			.offer__term-btn::after  {
				background-color: #ffffff !important;
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
			analytics.load("W1yJ9TYonvWx2FlA6469eTyvX0xegGS3");
			analytics.page();
			}}();
		</script>

		<div class="wrapper">

			@include('campaigns/common/header')

			<div>
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
							<div class="offer__term" >
								<a class="offer__term-btn">優惠細則及條款</a>
								<div class="offer__term-hidden offer__term_text">
									<ul>
@foreach( explode(PHP_EOL, $offer->tnc) as $key => $value )
								{{ $value }}<br>
@endforeach
									</ul>
								</div>
							</div>
						</div>
					</div>

					<div class="checkbox">
						{{-- <input type="checkbox" id="confirm_tnc" name="confirm_tnc"> --}}
						<label for="confirm_tnc">如繼續領取優惠，即表示您同意Kinnso會員使用<a href="{{ route('website.termsandconditions.html') }}" target="_blank">條款及細則</a>和<a href="{{ route('website.privacy.html') }}" target="_blank">私隱政策聲明</a>內所述之條款，包括 Kinnso 按照該聲明使用本人提供的個人資料。</label>
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

			var offerTermBtn = $('.offer__term-btn');
			offerTermBtn.on('click', handleOfferTerm);

			function handleOfferTerm() {
				$(this).parent().toggleClass('offer__term--open');
			}

			$(document).ready(function()  {

				$("input[type=checkbox]").change(function()  {

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

				$("#submitButton").click(function()  {
					// if (_checkedCount != _requiredCount)  {
					// 	$("#termsWarning").show();
					// 	return;
					// }

					$("#termsWarning").hide();
					$("#loading").css("display", "flex");
					window.location.href = "{{ $whatsappURL }}";
				});
			});

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
