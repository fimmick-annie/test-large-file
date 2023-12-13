<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	@include('campaigns/common/head')

	<meta property="og:title" content="Kinnso" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="https://www.kinnso.com/" />
	<meta property="og:image" content="https://www.kinnso.com/android-chrome-512x512.png?v=2" />
	<meta property="og:description" content="著數？唔使自己周圍搲嘅，有 Kinnso，著數自動送上門！" />

	<link rel="stylesheet" href="./offers/common/offer_listing.css?v=1" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/css/splide.min.css">

	@include('campaigns/components/css/listing_header')
	@include('campaigns/components/css/section_title')
	@include('campaigns/components/css/offer_card')
	@include('campaigns/components/css/kinnso_bear')
	@include('campaigns/components/css/hot_topic_and_filter')
	@include('campaigns/components/css/offer_list')
	<style>
		.splide__progress__bar {
			background: #ee5500;
		}
		.wrapper {
			overflow: visible;
		}
	</style>
</head>

<body>
	@include('website/common/tracking_body')

	<!--  Segment  -->
	<script>
		! function() {
			var analytics = window.analytics = window.analytics || [];
			if (!analytics.initialize)  {
				if (analytics.invoked) window.console && console.error && console.error("Segment snippet included twice.");
				else {
					analytics.invoked = !0;
					analytics.methods = ["trackSubmit", "trackClick", "trackLink", "trackForm", "pageview", "identify", "reset", "group", "track", "ready", "alias", "debug", "page", "once", "off", "on", "addSourceMiddleware", "addIntegrationMiddleware", "setAnonymousId", "addDestinationMiddleware"];
					analytics.factory = function(e) {
						return function() {
							var t = Array.prototype.slice.call(arguments);
							t.unshift(e);
							analytics.push(t);
							return analytics
						}
					};
					for (var e = 0; e < analytics.methods.length; e++) {
						var key = analytics.methods[e];
						analytics[key] = analytics.factory(key)
					}
					analytics.load = function(key, e) {
						var t = document.createElement("script");
						t.type = "text/javascript";
						t.async = !0;
						t.src = "https://cdn.segment.com/analytics.js/v1/" + key + "/analytics.min.js";
						var n = document.getElementsByTagName("script")[0];
						n.parentNode.insertBefore(t, n);
						analytics._loadOptions = e
					};
					analytics._writeKey = "W1yJ9TYonvWx2FlA6469eTyvX0xegGS3";
					analytics.SNIPPET_VERSION = "4.13.2";
					analytics.load("{{ env('SEGMENT_ID') }}");
					analytics.page("offer-listing", {
						ip: "{{ $ipAddress }}",
						userAgent: "{{ $userAgent }}",
					});
				}
			}
		}();
	</script>

	<!-- kinnso bear -->
	@include('campaigns/components/kinnso_bear')

	<!-- top banner -->
	<style>
		.top-banner-wrapper {
			margin-top: 70px;
		}
		#top-banner-splide .splide__slide {
			max-width: 1200px;
			margin-right: 36px;
			margin-bottom: 6px;
		}
		#top-banner-splide .splide__slide.is-active .top-banner {
			opacity: 1;
		}
		#top-banner-splide .splide__pagination {
			bottom: 4%;
		}
		#top-banner-splide .splide__pagination__page {
			background: #fff!important;
			border-radius: 4px;
			box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
			transition: width .5s;
		}
		#top-banner-splide .splide__pagination__page.is-active {
			width: 36px;
			transform: scale(1);
		}
		#top-banner-splide .splide__arrow {
			background: transparent;
			opacity: 1;
		}
		#top-banner-splide .splide__arrow[disabled] {
			display: none;
		}
		#top-banner-splide .splide__arrow--prev {
			left: 0px;
			filter: drop-shadow(0px 0px 4px rgba(0,0,0,.3));
		}
		#top-banner-splide .splide__arrow--next {
			right: 0px;
			filter: drop-shadow(0px 0px 4px rgba(0,0,0,.3));
		}
		#top-banner-splide .splide__arrow::before,
		#top-banner-splide .splide__arrow::after {
			content: ' ';
			position: absolute;
			top: 50%;
			left: 50%;
			width: 5px;
			height: 21px;
			background: #FFF;
			border-radius: 6px;
		}
		#top-banner-splide .splide__arrow--prev::before {
			transform: translate(-50%, -75%) rotate(55deg);
		}
		#top-banner-splide .splide__arrow--prev::after {
			transform: translate(-50%, -25%) rotate(125deg);
		}
		#top-banner-splide .splide__arrow--next::before {
			transform: translate(-50%, -75%) rotate(-55deg);
		}
		#top-banner-splide .splide__arrow--next::after {
			transform: translate(-50%, -25%) rotate(-125deg);
		}
		#top-banner-splide .splide__arrow svg {
			display: none;
		}
		@media (min-width:1400px) {
			#top-banner-splide .splide__arrow--prev {
				left: calc(50vw - (1200px / 2) - 100px);
			}
			#top-banner-splide .splide__arrow--next {
				right: calc(50vw - (1200px / 2) - 100px);
			}
		}
		.top-banner {
			max-width: 1200px;
			padding: 29.83% 36px 0 36px;
			margin: 20px 0 0 0;
			background-image: var(--background-image);
			background-repeat: no-repeat;
			background-position: center center;
			background-size: cover;
			border: 1px solid #DDDDDD;
			border-radius: 15px;
			box-shadow: 0px 3px 6px #00000029;
			opacity: 0.7;
			cursor: pointer;
		}
		@media (max-width:1200px) {
			.top-banner {
				margin: 20px 16px 0 16px;
			}
		}
		@media (max-width: 767px) {
			.top-banner {
				padding: 58.87% 36px 0 36px;
				background-image: var(--mobile-background-image);
			}
		}
	</style>
	<div class="top-banner-wrapper" class="p-0 w-100">
		<div id="top-banner-splide" class="splide">
			<div class="splide__track">
				<ul class="splide__list"></ul>
			</div>
		</div>
	</div>

	<div class="wrapper">

		<div class="offer">
			@include('campaigns/common/header')
		</div>
		<!-- hot topic and filter -->
		@include('campaigns/components/hot_topic_and_filter')
		<!-- campaign banner -->
		<style>
			.campaign-banner-wrapper {
				margin-bottom: 29.02px;
			}
			@media (max-width: 767px) {
				.campaign-banner-wrapper {
					padding: 0 8px;
				}
			}
			.campaign-banner {
				width: 300px;
				padding: 0 8px;
				margin-bottom: 6px;
			}
			.campaign-banner::before {
				content: ' ';
				padding-top: 44.89%;
				background-image: var(--background-image);
				background-repeat: no-repeat;
				background-position: center center;
				background-size: cover;
				box-shadow: 0px 3px 6px #00000029;
				border-radius: 8px;
				display: block;
			}
			#campaign-banner-splide .splide__arrow {
				background: rgba(251, 203, 8, .5);
				opacity: 1;
			}
			#campaign-banner-splide .splide__arrow[disabled] {
				opacity: 0.3;
				cursor: default;
			}
			#campaign-banner-splide .splide__arrow::before,
			#campaign-banner-splide .splide__arrow::after {
				content: ' ';
				position: absolute;
				top: 50%;
				left: 50%;
				width: 4px;
				height: 16px;
				background: #FFF;
				border-radius: 2px;
			}
			#campaign-banner-splide .splide__arrow--prev::before {
				transform: translate(-50%, -75%) rotate(55deg);
			}
			#campaign-banner-splide .splide__arrow--prev::after {
				transform: translate(-50%, -25%) rotate(125deg);
			}
			#campaign-banner-splide .splide__arrow--next::before {
				transform: translate(-50%, -75%) rotate(-55deg);
			}
			#campaign-banner-splide .splide__arrow--next::after {
				transform: translate(-50%, -25%) rotate(-125deg);
			}
			#campaign-banner-splide .splide__arrow--prev {
				left: -4px;
			}
			#campaign-banner-splide .splide__arrow--next {
				right: -4px;
			}
			#campaign-banner-splide .splide__arrow svg {
				display: none;
			}
			@media (max-width:1399px) {
				#campaign-banner-splide .splide__arrow {
					display: none;
				}
			}
			@media (min-width:1400px) {
				#campaign-banner-splide .splide__arrow--prev {
					left: -3rem;
				}
				#campaign-banner-splide .splide__arrow--next {
					right: -3rem;
				}
			}
		</style>
		<div class="campaign-banner-wrapper" class="p-0 w-100">
			<div id="campaign-banner-splide" class="splide">
				<div class="splide__track">
					<ul class="splide__list"></ul>
				</div>
			</div>
		</div>
		<!-- hot offer listing -->
		<style>
			.hot-offer-title .icon {
				width: 24.05px;
			}
			.hot-offer-title .icon::before {
				content: ' ';
				padding-top: 126%;
				background-image: url("{{ asset('website/hot_icon.png') }}");
				background-repeat: no-repeat;
				background-position: center center;
				background-size: contain;
				display: block;
			}

			.hot-offer-wrapper {
				margin-bottom: 29.02px;
			}
			@media (max-width: 767px) {
				.hot-offer-wrapper {
					padding: 0 8px;
				}
			}
			.hot-offer-wrapper .offer-card-wrapper {
				margin: 6px 0;
			}
			.hot-offer-wrapper .offer-card {
				width: 360px;
			}
			@media (min-width: 1200px) {
				.hot-offer-wrapper .offer-card {
					width: calc((1200px - 48px) / 3);
				}
			}
			@media (max-width: 1200px) {
				.hot-offer-wrapper .offer-card {
					width: calc(((100vw - 48px) / 3) - 6px);
				}
			}
			@media (max-width: 767px) {
				.hot-offer-wrapper .offer-card {
					width: calc(48.8vw - 16px - (17px / 2));
				}
			}
			.hot-offer-wrapper .offer-card .image::before {
				padding-top: 49%;
			}
			#hot-offer-splide .splide__slide {
				padding: 0 8px;
				display: flex;
			}
			#hot-offer-splide .splide__slide .offer-card-wrapper {
				flex: 1 1 auto;
			}
			#hot-offer-splide .splide__arrow {
				background: rgba(251, 203, 8, .5);
				opacity: 1;
			}
			#hot-offer-splide .splide__arrow[disabled] {
				opacity: 0.3;
				cursor: default;
			}
			#hot-offer-splide .splide__arrow::before,
			#hot-offer-splide .splide__arrow::after {
				content: ' ';
				position: absolute;
				top: 50%;
				left: 50%;
				width: 4px;
				height: 16px;
				background: #FFF;
				border-radius: 2px;
			}
			#hot-offer-splide .splide__arrow--prev::before {
				transform: translate(-50%, -75%) rotate(55deg);
			}
			#hot-offer-splide .splide__arrow--prev::after {
				transform: translate(-50%, -25%) rotate(125deg);
			}
			#hot-offer-splide .splide__arrow--next::before {
				transform: translate(-50%, -75%) rotate(-55deg);
			}
			#hot-offer-splide .splide__arrow--next::after {
				transform: translate(-50%, -25%) rotate(-125deg);
			}
			#hot-offer-splide .splide__arrow svg {
				display: none;
			}
			@media (max-width:767px) {
				#hot-offer-splide .splide__arrow--prev {
					left: -2px;
				}
				#hot-offer-splide .splide__arrow--next {
					right: -2px;
				}
			}
			@media (min-width:1400px) {
				#hot-offer-splide .splide__arrow--prev {
					left: -3rem;
				}
				#hot-offer-splide .splide__arrow--next {
					right: -3rem;
				}
			}
			#hot-offer-splide.no-result {
				visibility: visible;
			}
			#hot-offer-splide.no-result .splide__list {
				display: block;
			}
			#hot-offer-splide .no-result {
				padding: 8px 0;
				font-size: 'Helvetica Neue';
				letter-spacing: 3.73px;
				text-align: center;
				color: #57524F;
			}
			#hot-offer-splide .no-result .sleeping-bear {
				width: 180px;
				margin: 24px auto;
			}
			#hot-offer-splide .no-result .sleeping-bear::after {
				content: ' ';
				padding-top: 75.52%;
				background-image: url("{{ asset('website/sleeping_bear.png') }}");
				background-repeat: no-repeat;
				background-position: center center;
				background-size: contain;
				display: block;
			}
		</style>
		<div class="section-title hot-offer-title" data-text="人氣優惠">
			<div class="icon"></div>
		</div>
		<div class="hot-offer-wrapper" class="p-0 w-100">
			<div id="hot-offer-splide" class="splide">
				<div class="splide__track">
					<ul class="splide__list"></ul>
				</div>
			</div>
		</div>
		<!-- offer listing -->
		<style>
			.offer-title .icon {
				width: 35.49px;
			}
			.offer-title .icon::before {
				content: ' ';
				padding-top: 87.32%;
				background-image: url("{{ asset('website/coupon_icon.png') }}");
				background-repeat: no-repeat;
				background-position: center center;
				background-size: contain;
				display: block;
			}
		</style>
		<div class="section-title offer-title" data-text="優惠專區">
			<div class="icon"></div>
		</div>
		<div id="offer-list" class="offer-wrapper"></div>
	</div>

	<!-- top-banner-splide -->
	<script id="top-banner-splide-template" type="text/x-handlebars-template">
		@{{#each banners}}
		<li class="splide__slide">
			<div class="top-banner" data-url="@{{desktop.url}}" data-mobile-url="@{{mobile.url}}" style="--background-image:url('@{{desktop.image}}');--mobile-background-image:url(@{{mobile.image}})"></div>
		</li>
		@{{/each}}
	</script>
	<!-- campaign-banner-splide -->
	<script id="campaign-banner-splide-template" type="text/x-handlebars-template">
		@{{#each banners}}
		<li class="splide__slide">
			@{{#url}}<a href="@{{this}}">@{{/url}}
				<div class="campaign-banner" style="--background-image:url('@{{image}}')"></div>
			@{{#url}}</a>@{{/url}}
		</li>
		@{{/each}}
	</script>
	<!-- hot-offer-splide -->
	<script id="hot-offer-splide-template" type="text/x-handlebars-template">
		@{{#each offers}}
		<li class="splide__slide">
			<div class="offer-card-wrapper">
				<div class="label-wrapper">
					@{{#each tags}}
						@{{#ifEquals this "hot"}}
					<div class="label hot-label"></div>
						@{{/ifEquals}}
						@{{#ifEquals this "new"}}
					<div class="label new-label"></div>
						@{{/ifEquals}}
						@{{#ifEquals this "push"}}
					<div class="label kinso-label"></div>
						@{{/ifEquals}}
						@{{#ifEquals this "less"}}
					<div class="label litter-label"></div>
						@{{/ifEquals}}
						@{{#ifEquals this "soldout"}}
					<div class="label soldout-label"></div>
						@{{/ifEquals}}
						@{{#ifEquals this "end"}}
					<div class="label end-label"></div>
						@{{/ifEquals}}
					@{{/each}}
				</div>
				@{{#url}}<a href="@{{this}}">@{{/url}}
					<div class="offer-card">
						<div class="image" style="--background-image:url('@{{key-visual}}')"></div>
						<div class="tagging">
							<ul>
								@{{#each labels}}
								<li data-text="@{{text}}" style="color:@{{text-color}}"></li>
								@{{/each}}
							</ul>
						</div>
						<div class="title">@{{title}}</div>
					</div>
				@{{#url}}</a>@{{/url}}
			</div>
		</li>
		@{{else}}
		<div class="no-result">
			暫時未有相關優惠
			<div class="sleeping-bear"></div>
		</div>
		@{{/each}}
	</script>

	<script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
	<script src="{{ asset('assets/vendor/splide/splide.min.js') }}"></script>
	<script src="{{ asset('assets/vendor/handlebars/handlebars.js') }}"></script>
	@include('campaigns/components/js/init_handlebars')
	@include('campaigns/components/js/hot_topic_and_filter')
	@include('campaigns/components/js/offer_list')
	<script>
	let __splide = (function(window, document, undefined) {
		let slides = {};

		function init(id, options) {
			if (slides[id] instanceof Splide) {
				slides[id].destroy(true);
				delete slides[id];
			}
			if (slides[id] != undefined)
				return false;
			
			slides[id] = new Splide('#'+id, options);
			slides[id].mount();
		}

		return {
			init
		}
	})(window, document, undefined);
	(function(window, document, undefined) {
		// slide
		const topBannerSplideEle = document.getElementById('top-banner-splide');
		const campaignBannerSplideEle = document.getElementById('campaign-banner-splide');
		const hotOfferSplideEle = document.getElementById('hot-offer-splide');
		// template
		const topBannerSplideTemplate = Handlebars.compile(document.getElementById('top-banner-splide-template').innerHTML);
		const campaignBannerSplideTemplate = Handlebars.compile(document.getElementById('campaign-banner-splide-template').innerHTML);
		const hotOfferSplideTemplate = Handlebars.compile(document.getElementById('hot-offer-splide-template').innerHTML);
		// loading
		const loadingEle = document.getElementById('loading');

		async function getLandingData(callback) {
			fetch('{{ route("campaign.offer.landing.json") }}', {
					method: 'GET'
				})
				.then(response => response.json())
				.then(data => {
					let html = undefined;
					let ele = undefined;
					if (data['key-visuals']) {
						html = topBannerSplideTemplate({"banners": data['key-visuals']});
						ele = (topBannerSplideEle) ? topBannerSplideEle.querySelector('.splide__list') : undefined;
						if (ele) ele.innerHTML = html;

						__splide.init(topBannerSplideEle.id, {
							type: 'loop',
							autoplay: true,
							arrows: true,
							focus: 'center'
						});

						let eles = topBannerSplideEle.querySelectorAll('.splide__slide .top-banner');
						eles.forEach(function(ele) {
							ele.onclick = function() {
								let redirectUrl = undefined;
								if (/Android|webOS|iPhone|iPad|iPod|pocket|psp|kindle|avantgo|blazer|midori|Tablet|Palm|maemo|plucker|phone|BlackBerry|symbian|IEMobile|mobile|ZuneWP7|Windows Phone|Opera Mini/i.test(navigator.userAgent)) {
									redirectUrl = ele.dataset.mobileUrl;
								} else {
									redirectUrl = ele.dataset.url;
								}
								if (redirectUrl && redirectUrl != '' && analytics) {
									analytics.track('click-topbanner', {
										device: '{{ $device }}',
										os: '{{ $operatingSystem }}',
										url: redirectUrl,
										ip: '{{ $ipAddress }}',
										userAgent: '{{ $userAgent }}',
										referrer: window.location.href
									}).finally(() => {
										window.location.href = redirectUrl;
									});
								}
							}
						});
					}
					if (data.topics) {
						__hotTopicAndFilter.initHotTopic(data.topics);
					}
					// if (data.topics) {
					// 	html = offerFilterCategoryTemplate({"topics": data.topics});
					// 	if (offerFilterCategoryListEle) offerFilterCategoryListEle.innerHTML = html;
					// }
					if (data.categories) {
						__hotTopicAndFilter.initOfferCategory(data.categories);
					}
					if (data.banners) {
						html = campaignBannerSplideTemplate({"banners": data.banners});
						ele = (campaignBannerSplideEle) ? campaignBannerSplideEle.querySelector('.splide__list') : undefined;
						if (ele) ele.innerHTML = html;

						__splide.init(campaignBannerSplideEle.id, {
							type: 'slide',
							focus: 'center',
							autoWidth: true,
							arrows: true,
							pagination: false
						});
					}
					if (data['hot-offers']) {
						html = hotOfferSplideTemplate({"offers": data['hot-offers']});
						ele = (hotOfferSplideEle) ? hotOfferSplideEle.querySelector('.splide__list') : undefined;
						if (ele) ele.innerHTML = html;

						if (data['hot-offers'] instanceof Array && data['hot-offers'].length > 0) {
							hotOfferSplideEle.classList.remove('no-result');
							__splide.init(hotOfferSplideEle.id, {
								type: 'slide',
								autoWidth: true,
								arrows: true,
								pagination: false
							});
						} else {
							hotOfferSplideEle.classList.add('no-result');
						}
					}
					if (data.offers) {
						let offerListEleId = 'offer-list';
						__offerList.init(offerListEleId, {"offers": data.offers});
					}

					if (callback instanceof Function)
						callback();
				});
		}

		document.body.style.overflow = 'hidden';
		window.addEventListener('load', () => {
			loadingEle.style.display = 'block';
			getLandingData(() => {
				document.body.style.overflow = 'auto';
				loadingEle.style.display = 'none';

				if (window.location.hash == '#offer-listing') {
					const offerListEle = document.getElementById('offer-list');
					if (offerListEle) {
						$('html,body').animate({
							scrollTop: $(offerListEle).offset().top - 240
						}, 500);
					}
				}
			});
		});
	})(window, document, undefined);
	</script>
</body>

</html>