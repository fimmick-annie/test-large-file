<style>
	@font-face {
		font-family: kinnsoFont;
		src: url("{{ asset('assets/gensen.ttf') }}?v=1");
	}
	.fs-45px {
		font-size: 1.5rem;
		font-family: kinnsoFont;
	}

	.fs-45px span{
		font-family: kinnsoFont;
	}

	.fs-30px {
		font-size: 1rem;
	}

	.grayLine {
		border-top: 1px solid black;
		opacity: 0.2;
		height: 1px;
		width: 100%;
	}

	.modal-menu {
		width: 300px;
		max-width: 300px;
		height: 100%;
		margin: 0;
		padding-top: 80px;
	}

	.modal-content {
		border-top-right-radius: 100px !important;
	}

	.menuBtn {
		z-index: 1600;
		position: absolute;
		top: 50%;
		left: 15px;
		color: orange;
		font-size: 28px;
		font-weight: 300!important;
		cursor: pointer;
		transform: translateY(-50%);
	}

	@media (min-width:1201px) {
		.menuBtn {
			left: calc(((100vw - 1200px) / 2 + 0px));
		}
	}

	.menu__links {
		font-family: kinnsoFont;
		display: -webkit-box;
		display: -ms-flexbox;
		display: flex;
		-ms-flex-wrap: wrap;
		flex-wrap: wrap;
		-webkit-box-orient: vertical;
		-webkit-box-direction: normal;
		-ms-flex-direction: column;
		flex-direction: column;
		-webkit-box-align: start;
		-ms-flex-align: start;
		align-items: flex-start;
		margin-top: 20px;
	}

	.menu__link {
		width: 100%;
		color: #414042;
		font-weight: 600;
		line-height: 1.45;
		letter-spacing: 0.6pt;
		border-style: solid;
		border-color: transparent;
		border-width: 1px 0;
		-webkit-transition: all .3s ease;
		-o-transition: all .3s ease;
		transition: all .3s ease;
		text-decoration: none;
		padding: 5px
	}

	.menu__link img {
		width: 24px;
	}

	.facebook-icon,
	.instagram-icon,
	.whatsapp-icon {
		position: absolute;
		right: 0;
		width: 31px;
	}
	.facebook-icon::before,
	.instagram-icon::before,
	.whatsapp-icon::before {
		content: ' ';
		padding-top: 100%;
		background-repeat: no-repeat;
		background-position: center center;
		background-size: contain;
		display: block;
	}
	{{--
	.facebook-icon {
		right: 95px;
	}
	.instagram-icon {
		right: 55px;
	}
	.whatsapp-icon {
		right: 15px;
	}
	--}}
	.facebook-icon {
		right: 55px;
	}
	.instagram-icon {
		right: 15px;
	}
	.facebook-icon::before {
		background-image: url("{{ asset('website/facebook_icon.png') }}");
	}
	.instagram-icon::before {
		background-image: url("{{ asset('website/instagram_icon.png') }}");
	}
	.whatsapp-icon::before {
		background-image: url("{{ asset('website/whatsapp_icon.png') }}");
	}
	@media (min-width:1201px) {
		{{--
		.facebook-icon {
			right: calc(((100vw - 1200px) / 2 + 80px));
		}
		.instagram-icon {
			right: calc(((100vw - 1200px) / 2 + 40px));
		}
		.whatsapp-icon {
			right: calc(((100vw - 1200px) / 2 + 0px));
		}
		--}}
		.facebook-icon {
			right: calc(((100vw - 1200px) / 2 + 40px));
		}
		.instagram-icon {
			right: calc(((100vw - 1200px) / 2 + 0px));
		}
	}
	@media (max-width: 767px) {
		.facebook-icon,
		.instagram-icon,
		.whatsapp-icon {
			display: none;
		}
	}
	#loading {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		z-index: 9999990;
		display: flex;
		align-items: center;
		justify-content: center;
		background-color: #FCCC08;
		background-image: url("{{ asset('website/LoadingPage_v2.gif') }}");
		background-repeat: no-repeat;
		background-position: center center;
		background-size: cover;
	}

	#list{
		display: none;
	}

	li{
		list-style-type: none;
		padding: 3px;
	}

	ul a +ul {
		max-height:0;
		overflow:hidden;
		transition:0.5s linear;
	}
	ul a:focus + ul {
		max-height:15em;
	}
	/* only select that link , here using the href attribute */
	a[href="nowhere"]:focus {
		pointer-events: none;
	}

	.rotate {
		color: grey;
		-moz-transition: all .3s ease-out;
		-webkit-transition: all .3s ease-out;
		transition: all .3s ease-out;
	}

	.rotate.down {
		-moz-transform:rotate(0.5turn);
		-webkit-transform:rotate(0.5turn);
		transform:rotate(0.5turn);
	}
	.list{
		display: none;
	}
	.list.showdown{
		display: block;
	}


</style>


@if ($_SERVER["SERVER_NAME"] != env('DOMAIN_PRODUCTION') && $_SERVER["SERVER_NAME"] != "kinnso.com")
<div class="developer" style="position:fixed;z-index:9999999;opacity:.75;top:0;left:0;width:100%;background-color:#ff0000;display:none">
	<h2 style="color:#ffffff;font-size:0.7rem;text-align:center;">Non Production Site</h2>
</div>

@endif
{{-- <div id="loading" style="position:fixed;z-index:9999990;top:0;left:0;width:100%;height:100%;display:flex;align-items:center;justify-content:center;background-color:rgba(0,0,0,.8);">
	<img src="{{ asset('offers/common/loading.gif') }}?v=1" alt="loading" style="max-width:200px;" />
</div> --}}
<div id="loading"></div>

<header class="header header__landing">
	<i id='menuBtn' onclick="showMenuModal()" class="fas fa-bars menuBtn"></i>

	<a href="{{ route('campaign.offer.listing.html') }}" class="logo">
		<img src="{{ asset('offers/common/kinnso.png') }}?v=1" class="logo__mobile" alt="logo" />
		<img src="{{ asset('offers/common/kinnso.png') }}?v=1" class="logo__desktop" alt="logo" />
	</a>

	<a class="facebook-icon" href="https://bit.ly/42K2Hcp" target="_blank"></a>
	<a class="instagram-icon" href="https://bit.ly/3LijD1r" target="_blank"></a>
	{{-- <a class="whatsapp-icon" href="#"></a> --}}
</header>

<!-- Button trigger modal -->
<button id='menuModalBtn' type="button" class="btn btn-primary d-none" data-bs-toggle="modal" data-bs-target="#menuModal"></button>

<!-- Modal -->
<div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-menu modal-fullscreen">
		<div class="modal-content">
			<div class="modal-body">
				<div class="menu__links">

					<a href="{{ route('website.aboutus.html') }}" class="menu__link p-2 fs-45px" onclick="menuClicked('read-about-us');">
						<span><img class='me-2' src='/website/menu/icon_about.png'>關於我們</span>
					</a>
					<a href="{{ route('campaign.offer.listing.html') }}" class="menu__link p-2 fs-45px" onclick="menuClicked('visit-offer-listing');">
						<span><img class='me-2' src='/website/menu/icon_offer.png'>瀏覽著數</span>
					</a>
					<a href="{{ route('website.kinnsopoints.html') }}" class="menu__link p-2 fs-45px" onclick="menuClicked('visit-kinnso-points');">
						<span><img class='me-2' src='/website/menu/icon_points.png'>Kinnso Points 簡介</span>
					</a>
					<div id="memberCenter" class="menu__link p-2 fs-45px" onclick="showList();">
						<span><img class='me-2' src='/website/menu/icon_member_centre.png'>會員中心 <div class="fa fa-chevron-down rotate"></div></span>
					</div>
						<ul class="list">
							<li style="width:60%;"><img src='/website/receipt_upload/line.png'><li>
							<li><a href="{{ route('website.myrewards.html') }}" class="menu__link p-2 fs-45px" onclick="menuClicked('visit-my-rewards');">
								<span>我的獎賞</span>
							</a><li>
							<li><a href="{{ route('website.offerhunting.html') }}" class="menu__link p-2 fs-45px" onclick="menuClicked('visit-offer-hunting');">
								<span>蜜探報料</span>
							</a><li>
							<li><a href="{{ route('website.receiptupload.html') }}" class="menu__link p-2 fs-45px" onclick="menuClicked('visit-receipt-uploads');">
								<span>上載收據及記錄</span>
							</a><li>
						</ul>
					<a href="{{ route('website.redemption.html') }}" class="menu__link p-2 fs-45px" onclick="menuClicked('visit-redemption');">
						<span><img class='me-2' src='/website/menu/icon_redemption.png'>獎賞中心</span>
					</a>
					<a href="{{ route('website.partnership.html') }}" class="menu__link p-2 fs-45px" onclick="menuClicked('read-partnership-us');">
						<span><img class='me-2' src='/website/menu/icon_partnership.png'>合作推廣</span>
					</a>

					<div class='my-3'></div>
					<a href="{{ route('website.termsandconditions.html') }}" class="menu__link fs-30px" onclick="menuClicked('read-tnc');">Kinnso 會員使用條款及細則</a>
					<a href="{{ route('website.privacy.html') }}" class="menu__link fs-30px" onclick="menuClicked('read-privacy');">Kinnso 私隱政策聲明</a>

				</div>
			</div>
		</div>
	</div>
</div>

<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
<script src="{{ asset('js/scripts/jquery-1.10.2.js') }}?v=1"></script>
<script>
	var _aid = null;

	const showMenuModal = () => {
		let menuModalBtn = document.querySelector('#menuModalBtn');
		menuModalBtn.click();
		let menuBtn = document.querySelector('.menuBtn');

		analytics.track("Hamburger button", {
@if (isset($ipAddress))
				ip: "{{ $ipAddress }}",
				userAgent: "{{ $userAgent }}",
@endif
		});
		if (_aid != null)  {
			window.Personica("Hamburger button", {
				'ExternalCookieID': _aid
			});
		}
	}

	function showList(){
		$('.rotate').toggleClass('down');
		$('.list').toggleClass('showdown');
	} 

	function menuClicked(menuID) {
		analytics.track("Hamburger button", {
@if (isset($ipAddress))
				ip: "{{ $ipAddress }}",
				userAgent: "{{ $userAgent }}",
@endif
		});
		if (_aid != null)  {
			window.Personica("Hamburger button", {
				'ExternalCookieID': _aid
			});
		}
	}

	window.addEventListener('load', () => {
		const loadingDiv = document.querySelector('#loading');
		loadingDiv.style.display = 'none';
	})
</script>
<script>
	(function(window, document, undefined) {
		const logoEle = document.querySelector('header a.logo');
		const fbIconEle = document.querySelector('header a.facebook-icon');
		const igIconEle = document.querySelector('header a.instagram-icon');

		if (logoEle) {
			logoEle.onclick = function(event) {
				event.preventDefault();

				analytics.track('click-home-button', {
					device: '{{ $device ?? "" }}',
					os: '{{ $operatingSystem ?? "" }}',
					url: this.href,
					ip: '{{ $ipAddress ?? "" }}',
					userAgent: '{{ $userAgent ?? "" }}',
					referrer: window.location.href
				});

				setTimeout(() => {
					window.location.href = this.href;
				}, 1000);
			}
		}

		if (fbIconEle) {
			fbIconEle.onclick = function(event) {
				event.preventDefault();

				analytics.track('click-kinnsofb-button', {
					device: '{{ $device ?? "" }}',
					os: '{{ $operatingSystem ?? "" }}',
					url: this.href,
					ip: '{{ $ipAddress ?? "" }}',
					userAgent: '{{ $userAgent ?? "" }}',
					referrer: window.location.href
				});

				setTimeout(() => {
					window.open(this.href);
				}, 1000);
			}
		}

		if (igIconEle) {
			igIconEle.onclick = function(event) {
				event.preventDefault();

				analytics.track('click-kinnsoig-button', {
					device: '{{ $device ?? "" }}',
					os: '{{ $operatingSystem ?? "" }}',
					url: this.href,
					ip: '{{ $ipAddress ?? "" }}',
					userAgent: '{{ $userAgent ?? "" }}',
					referrer: window.location.href
				});

				setTimeout(() => {
					window.open(this.href);
				});
			}
		}
	})(window, document, undefined);
</script>