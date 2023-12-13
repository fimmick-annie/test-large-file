<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		@include('campaigns/common/head')

		<link rel="stylesheet" href="{{ asset('offers/common/'.$offer->blade_folder.'/offer_details.css') }}?v=1">
		<style>
/*
			.linkButton  {
				background-color: #0e2c51;
				text-align: center;
				padding: 0.5rem;
				width: 50%;
				border-radius: 1rem;
				margin-top: 1rem;
			}
			.linkButton a  {
				font-size: 1.5rem;
				color: #ffffff;
				text-decoration: none;
			}
 */
			.container  {
				display: flex;
				justify-content: center;
				align-items: center;
			}
			#buttonBackgound  {
				position: absolute;
				z-index: 1;
			}
			#button  {
				position: absolute;
				margin: auto;
				z-index: 10;
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

		<div class="wrapper" style="background-color:#f9e546;">

			@include('campaigns/common/header')
			<header class="header">
				<img src="{{ asset('offers/common/header.jpg') }}?v=1" alt="Header" />
			</header>

			<div class="offer">

				<div class="thanks__main">
					<div class="thanks__banner">
						<img src="{{ asset('offers/'.$offer->offer_name.'/offer_thankyou_info.jpg') }}?v=1" alt="Thank you information" />
					</div>

					<div class="container">
						<img src="{{ asset('offers/'.$offer->offer_name.'/offer_thankyou_button_background.jpg') }}?v=1" alt="Button background" />
						<div id="button"><a href="{{ $whatsAppLink }}">
							<img src="{{ asset('offers/'.$offer->offer_name.'/offer_thankyou_whatsapp_button.png') }}?v=1" alt="Button" />
						</a></div>
					</div>

					<div class="thanks__banner">
						<img src="{{ asset('offers/'.$offer->offer_name.'/offer_thankyou_kv.jpg') }}?v=1" alt="Thank you KV" />
					</div>
				 </div>
			</div>
		</div>

		<script>
			setTimeout(function()  {window.location.href = "{{ $whatsAppLink }}";}, 2000);
		</script>
	</body>
</html>
