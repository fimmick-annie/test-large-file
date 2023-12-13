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

	<style>
		.listing__header {
			margin-top: 46px;
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
			padding: 23px 30px;
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
			max-width: 100px;
			display: block;
			-webkit-transform: translate(-50%, -50%);
			-ms-transform: translate(-50%, -50%);
			transform: translate(-50%, -50%);
		}

		@media (max-width: 1365px) {
			.logo__desktop {
				display: none;
			}
		}

		@media (min-width: 1366px) {
			.logo__mobile {
				display: none;
			}
		}

		.tagGroup {
			position: absolute;
			top: -15px;
			left: 15px;
			z-index: 1000;
		}
		.tagGroup img {
			max-width: 60px;
		}
		.btnGroup {
			position: absolute;
			bottom: 13%;
			right: 10%;
			z-index: 999;
		}

		.cursor-pointer {
			cursor: pointer;
		}

		.listing  {
			min-height: 100vh;
			background-color: #ffad00;
		}

		.listing__itembox {
			background-image: url('website/offer-listing/background_0623_v1@2x.png');
			background-size: cover;
			background-repeat: repeat;
			padding: 5px;
		}

		.listing__itembox * {
			text-decoration: none;
		}

		.listing_item {
			position: absolute;
			top: 10%;

		}

		.listing_image {
			position: absolute;
			top: 7%;
			left: 5%;
			width: 100%;
			padding: 5px;
		}

		.listing_text {
			position: absolute;
			top: 15%;
			left: 46%;
		}

		.listing_line {
			position: absolute;
			top: 10%;
			left: 46%;
		}

		.font-16 {
			font-size: 16px;
			color: #FF7F00;
		}

		.font-12 {
			font-size: 12px;
			color: #FF7F00;
		}

		.expiryDate {
			font-size: 12px;
			color: #B2B2B2;
		}

		.likeCounter {
			font-size: 12px;
			position: absolute;
			top: 45%;
			left: 50%;
			transform: translate(-50%, -50%);
			color: #f66003;
		}

		.shareBtn,
		.likeBtn {
			height: 30px;
			z-index: 999;
		}

		.splide__arrow {
			display: none !important;
		}

		.footer {
			color: #ffffff;
			width: 100%;
			text-align: center;
			font-size: 13px;
		}

		.wrapper {
			padding-top: 47px;
		}

		.alert {
			position: fixed !important;
			top: 10%;
			margin-left: 10%;
			margin-right: 10%;
			text-align: center;
			width: 80%;
			z-index: 2999;
			opacity: 0;
			transition: all 1s;
			pointer-events: none;
		}

		@media only screen and (min-width: 800px) {
			.font-16 {
				font-size: 30px;
			}

			.font-12 {
				font-size: 20px;
			}

			.expiryDate {
				font-size: 20px;
			}

			.likeCounter {
				font-size: 16px;

			}

			.shareBtn,
			.likeBtn,
			.shareBtnDesktop {
				height: 40px
			}

			.listing_image {
				position: absolute;
				top: 3%;
				left: 5%;
				width: 100%;
				padding: 10px;
			}

			.tagGroup img {
				max-width: 80px;
			}

		}
	</style>
</head>

	<body>
		@include('website/common/tracking_body')

		<div class="alert alert-success" role="alert">
			已複製優惠連結
		</div>

		<!--  Segment  -->
		<script>
			!function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","reset","group","track","ready","alias","debug","page","once","off","on","addSourceMiddleware","addIntegrationMiddleware","setAnonymousId","addDestinationMiddleware"];analytics.factory=function(e){return function(){var t=Array.prototype.slice.call(arguments);t.unshift(e);analytics.push(t);return analytics}};for(var e=0;e<analytics.methods.length;e++){var key=analytics.methods[e];analytics[key]=analytics.factory(key)}analytics.load=function(key,e){var t=document.createElement("script");t.type="text/javascript";t.async=!0;t.src="https://cdn.segment.com/analytics.js/v1/" + key + "/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(t,n);analytics._loadOptions=e};analytics._writeKey="W1yJ9TYonvWx2FlA6469eTyvX0xegGS3";analytics.SNIPPET_VERSION="4.13.2";
				analytics.load("{{ env('SEGMENT_ID') }}");
				analytics.page("offer-listing");
			}}();
		</script>

		<div class="wrapper">

			<div class="offer">
				@include('campaigns/common/header')
			</div>
			<div class="listing">
				<div class="p-0 w-100 " style='background-color:#f8ad03'>
					<div class="splide">
						<div class="splide__track">
							<ul class="splide__list">
								<li class="splide__slide">
									<img src='/website/offer-listing/banner01_0623_v1@2x.png'>
								</li>
								<li class="splide__slide">
									<img src='/website/offer-listing/banner02_0623_v1@2x.png'>
								</li>
								<li class="splide__slide">
									<img src='/website/offer-listing/banner03_0623_v1@2x.png'>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="listing__itembox">

					@include('website/common/footer')
				</div>
			</div>
		</div>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/js/splide.min.js"></script>
		<script>
			window.addEventListener('load', () => {
				getOfferList('default');
			})
			const getOfferList = (listName) => {
				fetch('{{ route("campaign.offer.listing.json") }}', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
					},
					body: JSON.stringify({
						listName: listName
					})
				})
				.then(res => res.json())
				.then(json => {
					let html = json.map(el => {
						return `
							<div class='w-100 d-flex flex-column justify-content-center align-items-center my-3 position-relative p-1' style='background:none;'>
								<img src='/website/offer-listing/coupon_0623_v1@2x.png'>
								<div class='tagGroup row'>
								${(el.tag != null && el.tag.includes('new')) ? `
									<img class='col cursor-pointer p-1' src="{{ asset('website/offer-listing/tag_01.png') }}" >
								` : ``}
								${(el.tag != null && el.tag.includes('hot')) ? `
									<img class='col cursor-pointer p-1' src="{{ asset('website/offer-listing/tag_02.png') }}" >
								` : ``}
								${(el.tag != null && el.tag.includes('push')) ? `
									<img class='col cursor-pointer p-1' src="{{ asset('website/offer-listing/tag_03.png') }}" >
								` : ``}
								${(el.tag != null && el.tag.includes('less')) ? `
									<img class='col cursor-pointer p-1' src="{{ asset('website/offer-listing/tag_04.png') }}" >
								` : ``}
								</div>
								<div class='btnGroup row'>
									<div class='col cursor-pointer d-block d-md-none'>
										<img class="shareBtn" data-title='${el.offer_title}' data-href="${ window.location.host + '/offer/' + el.offer_code}" src='/website/offer-listing/share_0623_v1@2x.png'>
									</div>
									<div class='col cursor-pointer d-none d-md-block m-0 p-0'>
										<a target='_black' href="${'https://www.facebook.com/sharer/sharer.php?u=' + window.location.host + '/offer/' + el.offer_code}">
											<img class="shareBtnDesktop" src='/website/offer-listing/fb_60x60@2x.png'>
										</a>
									</div>
									<div class='col cursor-pointer d-none d-md-block m-0 p-0'>
										<a target='_black' href="${'https://api.whatsapp.com/send?text=' + window.location.host + '/offer/' + el.offer_code}">
											<img class="shareBtnDesktop" src='/website/offer-listing/whatsapp_60x60@2x.png'>
										</a>
									</div>
									<div class='col cursor-pointer d-none d-md-block m-0 p-0'>
										<img class="shareBtnDesktop copyUrl" src='/website/offer-listing/copyurl_60x60@2x.png' data-href="${window.location.host + '/offer/' + el.offer_code}">
									</div>
									<div class='col cursor-pointer position-relative'>
										<img data-offerid="${el.id}" class='likeBtn unliked' src='/website/offer-listing/like01_0623_v1@2x.png'>
										<h5 style='pointer-events: none;' data-likecounterid="${el.id}" class='likeCounter text-center m-0'>${el.likeCounter}</h5>
									</div>
								</div>
								<a class='w-100 listing_image' href="${'/offer/' + el.offer_code}" data-offerName='${el.offer_name}' >
									<div class="col-5 d-flex justify-content-center align-items-center" style='padding-right:15px; border-right: 5px dashed #ffbc47;max-width:40%'>
										<img class='w-100' src="./offers/${el.offer_name}/offer_thumbnail.png?v=2" alt="Offer thumbnail" />
									</div>
								</a>

								<a class='w-100 listing_text' href="${'/offer/' + el.offer_code}" data-offerName='${el.offer_name}' >
									<div class='row ' style='padding-left:10px'>
										<div class="col-6">
											<p class='m-0 font-16'>${el.offer_title.replaceAll('\n', '<br/>')}</p>
											<p class="m-0 font-16">${el.offer_subtitle}</p>

											<p class="m-0 expiryDate">即日至${el.end_at.split('-')[0]}年${el.end_at.split('-')[1]}月${el.end_at.split('-')[2].split(' ')[0]}日</p>
										</div>
									</div>
								</a>

							</div>
						`;

					}).join('');
					document.querySelector('.listing__itembox').innerHTML = html;

					document.querySelectorAll('.shareBtn').forEach(el => {
						el.addEventListener('click', handleShare)
					})
					document.querySelectorAll('.listing_image, .listing_text').forEach(el => {
						el.addEventListener('click', viewOffer)
					})
					document.querySelectorAll('.copyUrl').forEach(el => {
						el.addEventListener('click', copyUrl)
					})
					document.querySelectorAll('.likeBtn').forEach(el => {
						el.addEventListener('click', handleLike);
					})
					document.querySelectorAll('[data-likecounterid]').forEach(el => {
					if (parseInt(el.textContent) >= 1000) {
						el.textContent = parseInt(el.textContent / 100) / 10 + 'K';
					}
					if (parseInt(el.textContent) >= 1000000) {
						el.textContent = parseInt(el.textContent / 100000) / 10 + 'M';
					}
					handleLikeHistory();
				})
				})
			}
		</script>
		<script>
			function viewOffer(e) {
				console.log('viewOffer');
				analytics.track("View offer", {
					plan: e.target.dataset.offerName,
				});
			}

			document.addEventListener('DOMContentLoaded', function() {
				new Splide('.splide').mount();
			});

			function handleLikeHistory()  {

				let likeHistory = document.cookie
					.split('; ')
					.find(row => row.startsWith('likeHistory='));

				if (likeHistory) {
					likedOfferArray = likeHistory.split('likeHistory=')[1].split(',')
					likedOfferArray.forEach(el => {
						if (el) {
							let likeBtn = document.querySelector(`[data-offerid="${el}"]`);
							if (likeBtn)  {

								likeBtn.classList.remove('unliked')
								likeBtn.classList.add('liked')
								likeBtn.src = '/website/offer-listing/like02_0623_v1@2x.png';
								let likeCounter = document.querySelector(`[data-likecounterid="${el}"]`);
								likeCounter.style.color = 'white';
							}
						}
					})

					document.querySelectorAll('[data-likecounterid]').forEach(el => {
						if (parseInt(el.textContent) >= 1000) {
							el.textContent = parseInt(el.textContent / 100) / 10 + 'K';
						}
						if (parseInt(el.textContent) >= 1000000) {
							el.textContent = parseInt(el.textContent / 100000) / 10 + 'M';
						}
					})
				} else {
					document.cookie = `likeHistory=`;
				}
			}

			// })
			const handleLike = (e) => {
				if (e.target.classList.contains('unliked')) {
					e.target.src = '/website/offer-listing/like02_0623_v1@2x.png'
					e.target.classList.remove('unliked')
					e.target.classList.add('liked')

					let likeHistory = document.cookie
						.split('; ')
						.find(row => row.startsWith('likeHistory='));

					likeHistory = likeHistory + e.target.dataset.offerid + ','
					document.cookie = likeHistory;
					fetch('/api/increaseLikeCounter', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json'
						},
						body: JSON.stringify({
							'offerId': e.target.dataset.offerid
						})
					});
					let likeCounter = document.querySelector(`[data-likecounterid="${e.target.dataset.offerid}"]`);
					likeCounter.style.color = 'white';

					if (likeCounter.textContent.includes('K') || likeCounter.textContent.includes('M')) {

					} else {
						likeCounter.textContent++

					}
				} else {
					e.target.src = '/website/offer-listing/like01_0623_v1@2x.png'
					e.target.classList.add('unliked')
					e.target.classList.remove('liked')

					let likeHistory = document.cookie
						.split('; ')
						.find(row => row.startsWith('likeHistory='));

					likeHistory = likeHistory.replace(`${e.target.dataset.offerid},`, '');

					document.cookie = likeHistory;
					fetch('/api/decreaseLikeCounter', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json'
						},
						body: JSON.stringify({
							'offerId': e.target.dataset.offerid
						})
					});
					let likeCounter = document.querySelector(`[data-likecounterid="${e.target.dataset.offerid}"]`);
					likeCounter.style.color = '#f66003';

					if (likeCounter.textContent.includes('K') || likeCounter.textContent.includes('M')) {

					} else {
						if (likeCounter.textContent > 0)  {likeCounter.textContent--;}
					}
				}

			}
			document.querySelectorAll('.likeBtn').forEach(el => {
				el.addEventListener('click', handleLike);
			})
			const handleShare = (e) => {
				if (navigator.share) {
					navigator.share({
							title: e.target.dataset.title,
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

			const copyUrl = (e) => {
				let text = e.target.dataset.href;
				navigator.clipboard.writeText(text).then(function() {
					console.log('Async: Copying to clipboard was successful!');
				}, function(err) {
					console.error('Async: Could not copy text: ', err);
				});
				let alert = document.querySelector('.alert');
				alert.style.opacity = 1;
				// alert.style.display = 'block';
				// alert.style.top = window.scrollY + 100 + 'px'
				setTimeout(() => {
					alert.style.opacity = 0;
					// alert.style.display = 'none';

				}, 3000)
			}
			document.querySelectorAll('.copyUrl').forEach(el => {
				el.addEventListener('click', copyUrl)
			})
		</script>
	</body>
</html>
