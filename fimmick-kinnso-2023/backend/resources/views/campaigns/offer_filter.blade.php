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
		body {
			overflow-x: hidden!important;
		}
		.wrapper {
			overflow: visible;
		}
		.offer-category-wrapper {
			position: relative;
			left: calc((100vw - 100%) / 2 * -1);
			width: 100vw;
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

    <div class="wrapper" style="padding-top:70px">

        <div class="offer">
			@include('campaigns/common/header')
		</div>

        <!-- hot topic and filter -->
		<style>
			#offer-category-list {
				padding: 20px 0;
				background: #FFFAE6;
			}
		</style>
        @include('campaigns/components/hot_topic_and_filter')
        <!-- offer list -->
		<style>
			.offer-title .icon {
				width: 35.49px;
			}
			.offer-title .icon::before {
				content: ' ';
				padding-top: 87.32%;
				background-image: url("{{ asset('website/search_results_icon.png') }}");
				background-repeat: no-repeat;
				background-position: center center;
				background-size: contain;
				display: block;
			}
		</style>
		<div class="section-title offer-title" data-text="搜尋結果">
			<div class="icon"></div>
		</div>
	    <div id="offer-list" class="offer-wrapper"></div>
		<!-- hot offer list -->
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
		</style>
		<div class="section-title hot-offer-title" data-text="人氣優惠">
			<div class="icon"></div>
		</div>
		<div id="hot-offer-list" class="offer-wrapper hot-offer"></div>
    </div>

    @include('campaigns/components/js/init_handlebars')
    @include('campaigns/components/js/hot_topic_and_filter')
	@include('campaigns/components/js/offer_list')
    <script>
    (function(window, document, undefined) {
        // loading
		const loadingEle = document.getElementById('loading');

        async function getFilterPageData(callback) {
            fetch('{{ route("campaign.offer.filter.json").$filterQuery }}', {
					method: 'GET'
				})
				.then(response => response.json())
				.then(data => {
                    let html = undefined;
					let ele = undefined;

                    if (data.topics) {
						__hotTopicAndFilter.initHotTopic(data.topics);
					}

                    if (data.categories) {
						__hotTopicAndFilter.initOfferCategory(data.categories);
					}

                    if (data.offers) {
						let offerListEleId = 'offer-list';
						__offerList.init(offerListEleId, {"offers": data.offers});
					}

					if (data['hot-offers']) {
						let hotOfferListEleId = 'hot-offer-list';
						__offerList.init(hotOfferListEleId, {"offers": data['hot-offers'], "is-hot-offer": true});
					}

                    if (callback instanceof Function)
						callback();
                });
        }

		document.body.style.overflow = 'hidden';
        window.addEventListener('load', () => {
			loadingEle.style.display = 'block';
			getFilterPageData(() => {
				document.body.style.overflow = 'auto';
				loadingEle.style.display = 'none';
			});
		});
    })(window, document, undefined);
    </script>
</body>
</html>