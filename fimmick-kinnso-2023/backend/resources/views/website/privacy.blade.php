<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		@include('website/common/head')
		<link rel="stylesheet" href="./offers/common/offer_listing.css?v=1" />

		<style>
			body {
				background-color: #ffffff;
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
				padding: 35px 30px;
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
				max-width: 132px;
				display: block;
				-webkit-transform: translate(-50%, -50%);
				-ms-transform: translate(-50%, -50%);
				transform: translate(-50%, -50%);
			}

			.listing {
				color: #757575;
				font-size: 1rem;
				line-height: 1.57142857;
			}

			.heading {
				font-weight: bold;
				margin-top: 30px;
				margin-bottom: 10px;
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
		</style>
	</head>

	<body>
		@include('website/common/tracking_body')

		<!--  Segment  -->
		<script>
			!function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","reset","group","track","ready","alias","debug","page","once","off","on","addSourceMiddleware","addIntegrationMiddleware","setAnonymousId","addDestinationMiddleware"];analytics.factory=function(e){return function(){var t=Array.prototype.slice.call(arguments);t.unshift(e);analytics.push(t);return analytics}};for(var e=0;e<analytics.methods.length;e++){var key=analytics.methods[e];analytics[key]=analytics.factory(key)}analytics.load=function(key,e){var t=document.createElement("script");t.type="text/javascript";t.async=!0;t.src="https://cdn.segment.com/analytics.js/v1/" + key + "/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(t,n);analytics._loadOptions=e};analytics._writeKey="W1yJ9TYonvWx2FlA6469eTyvX0xegGS3";analytics.SNIPPET_VERSION="4.13.2";
				analytics.load("{{ env('SEGMENT_ID') }}");
				analytics.page("privacy", {
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

			<div class="listing">
				<div class="listing__header">
					<h2 style="padding-top:40px;">Kinnso 私隱政策聲明</h2>
				</div>
				<div class="listing__itembox" style="padding-top:40px; padding-bottom:40px;">
					Kinnso（以下簡稱本平台）為了讓閣下能夠安心的使用平台上的各項服務與資訊，請詳閱Kinnso私隱政策聲明（以下簡稱本私隱政策聲明）以保障你的權益:

					<div class="heading">第一條 適用範圍</div>
					Kinnso （以下簡稱本平台）私隱政策聲明如何處理閣下在使用網站服務時所蒐集的個人資料。私隱政策聲明不適用於本平台以外的網站，並且在未經閣下同意之下，本平台絕不會將閣下的個人資料提供給任何與本平台服務無關之第三人。

					<div class="heading">第二條 個人資料收集</div>
					閣下在註冊帳號、瀏覽網頁、參加網站活動時，本平台會蒐集閣下的個人識別資料，也可以從其他合作之第三方供應商，例如公共數據庫；聯合營銷合作夥伴；社交媒體平台等渠道，取得您的個人資料，包括閣下有可能在註冊或使用本平台的服務時所提供的姓名、電話、及電子郵件信箱、使用網路連線服務的 IP 位址、使用時間、瀏覽及點選資料記錄等資料。當閣下註冊成功並登入或使用本平台的服務後，本平台就會取得閣下的個人資料。
					<br>
					<br>本平台蒐集個人資料的目的：
					<br>本平台為了提供電子商務平台服務、客戶服務、技術維護服務、履行法定或契約義務、保護當事人及消費者之相關權益、行銷業務等目的。當閣下瀏覽本平台網站時系統會記錄相關資料及行徑，包括閣下的姓名、電子郵件信箱、地址、使用網路連線服務的 IP 位址、使用時間、瀏覽及點選資料記錄等。如果閣下選擇在本網站上進行購買和出售商品，本平台會蒐集閣下購買和出售商品的行為資料 ，其目的用作網路流量和行為調查之分析，所收集到的資料不會直接識別您的身份，但可能與您或特定設備相對應。

					<div class="heading">第三條 個人資料運用</div>
					本平台在適用法律允許的範圍內會收集、使用、披露及以其他方式處理閣下的個人信息，以供本平台內部及其他合作之第三方供應商使用，並且負永久保密義務，直至閣下終止訂閱Kinnso服務，才會刪除。
					<ul>
						<li>就閣下的查詢與閣下聯絡並經過閣下的同意發送訊息。
						<li>完成閣下的消費及/或捐贈，如適用，如處理閣下的付款，訂單送遞下，就閣下的消費與您溝通，並提供相關的客戶服務。
						<li>向閣下提供關於我們的產品、促銷活動、市場活動和項目的最新消息和公告，並通過網站向閣下發送參加特別項目的邀請。
						<li>若我們有一段時間未有閣下的消息，我們會與閣下聯繫。
						<li>我們以及我們的合作夥伴會向閣下發送廣告/促銷資訊。
						<li>為閣下於我們的網站帶來為個性化的產品和優惠資訊。
						<li>讓閣下通過網站向朋友發送消息。通過使用此功能，如同告訴我們閣下是有權使用並向我們提供閣下朋友的姓名、電子郵件地址、電話號碼或其他聯繫信息。
						<li>經適用網站的允許，讓閣下通過網站與其他用戶聯繫。
						<li>讓閣下參與社交分享活動，包括實時社交媒體動態。
						<li>為了我們的業務用途，如分析和管理我們的業務，市場研究，審計，開發新產品，提升我們的網站、服務和產品，分析趨勢，確定我們促銷活動的有效性，製定個人化內容，和測試客戶滿意度和提供客戶服務（包括故障排除及與客戶有關的問題）。
						<li>當我們認為有必要或適當時：（a）根據適用法律，包括閣下居住國以外的法律；（b）配合遵守法律程序；（c）響應來自公共和政府當局，包括閣下居住國以外的公共和政府當局的要求；（d）執行我們的條款及細則；（e）保護本公司及本公司任何附屬公司的業務；（f）保護我們、我們的附屬公司的業務、閣下或其他人的權利、私隱、安全或財產；並（g）允許我們尋求可用的補救措施或限制我們可能承受的損害。
					</ul>

					<div class="heading">第四條 披露及移轉個人資料</div>
					在適用法律允許的範圍內，本平台可能會與第三方分享您的個人資料，藉以幫助管理我們的業務並提供我們的服務。第三方包括：
					<ul>
						<li><b>我們的商業合作夥伴</b>，他們會根據他們制定的私隱政策使用閣下的個人資料，閣下應查閱他們的網站，以獲得他們私隱政策的相關資料。
						<li><b>第三方服務提供商</b>，如網站託管、數據分析、支付處理、履行訂單、提供基礎設施、資訊科技服務、活動管理、客戶服務、電子郵件投遞服務、信用卡處理、審計服務以及其他類似服務。
						<li><b>其他第三方</b>：在任何重組、合併、出售、合資企業、轉讓或其他處置我們的全部或任何部分業務、資產或股票（包括與任何破產或類似程序有關）的情況下，轉讓給第三方。
						<li><b>政府或非政府當局和/或監管機構</b>，根據任何適用於香港境內或境外的法律或法庭命令、司法程序、法律程序或規管性決定而須向執法機關及/或監管機構作出披露;
						<li><b>專業顧問</b>（包括我們的律師和核數師）。
					</ul>

					<div class="heading">第五條 第三方服務供應商</div>
					本平台會與第三方服務供應商合作，提供更多的服務選擇。例如，本平台會將網站的第三方支付服務、信用卡網路收單服務或物流配送委由其他第三方服務供應商來提供服務，第三方服務供應商得以根據本平台的要求提供本平台閣下服務。在特定情況下，第三方服務供應商會直接請閣下提供個人資料，閣下有權利決定是否提供給第三方服務供應商。

					<div class="heading">第六條 未成年條款</div>
					未滿 18 歲之使用者，應由父母或監護人同意後才能開始使用本平台提供之服務。本平台服務條款及網路開店服務條款也說明註冊之閣下身分認定，所以本平台不准許未成年在未經父母或監護人同意下註冊使用，也不會刻意蒐集兒童之個人資訊。

					<div class="heading">第七條 Cookies</div>
					本平台網站會使用 Cookie 技術，以便於提供閣下需要的服務。 閣下可以自行於使用中的瀏覽器變更瀏覽器對 Cookies 的接受程度，如果閣下選擇拒絕所有的 Cookies，你可能無法使用本平台部分的功能。

					<div class="heading">第八條 私隱政策聲明之修正權利</div>
					當閣下使用本平台網站所提供的服務時，本平台將視同你已閱讀且同意本私隱政策聲明。本平台保留隨時修正本私隱政策聲明之權利，修正後的條款將更新於本官方網站上，請隨時查詢最新資訊。如閣下於本條款作出修訂後，仍繼續使用本服務，即視為閣下已接受有關修訂並同意受經修訂的本條款約束。如閣下不接受有關修訂，應停止使用本服務。

				</div>
			</div>

			@include('website/common/footer')
		</div>
	</body>

</html>
