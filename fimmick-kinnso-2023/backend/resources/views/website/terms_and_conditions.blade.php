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
				analytics.page("terms-and-conditions", {
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
					<h2 style="padding-top:40px;">Kinnso 會員使用條款</h2>
				</div>
				<center>規定Kinnso及各項服務的必要事項。</center>
				<center style='padding-top:20px;'>  當閣下確定使用Kinnso所提供服務前，敬請先閱讀Kinnso會員使用條款（以下簡稱本會員條款）並同意受其及相關法律之約束。</center>
				<center>
					<div  class="heading">定義</div>
				</center>

				<div class="listing__itembox" style=" padding-bottom:40px;">
					本條款中使用的特定詞語具下列的意思：
					<ol>
						<li>「Kinnso」、「Kinnso Business」、「本平台」或「本服務」指 Kinnso 「Kinnso WhatsApp 訂閱服務」指根據客戶訂閱喜愛，不定時傳送産品優惠及活動資訊之電子訊息服務。</li>
						<li>「香港」指中華人民共和國香港特別行政區。
						<li>「包括」指包括但不限於。
						<li>「閣下」乃對本平台向其提供服務或交付商品的人士及須就本平台所交付的商品付款的人士的提述。
						<li>「第三方」、「第三方許可人」、「第三方供應商」包括但不限於商業合作夥伴、服務提供商如網站託管、數據分析、支付處理、履行訂單、提供基礎設施、資訊科技服務、活動管理、客戶服務、電子郵件投遞服務、信用卡處理、審計服務以及其他類似服務。
						<li>「第三方材料」指由第三方（包括商戶或服務供應商）提供、上載、傳送、提交或上傳的，或源自第三方（包括與第三方連結可獲得）的，軟件、文字、資料、圖像、照片、圖形、錄像、標記、標誌、材料或資訊。
						<li>「Kinnso WhatsApp」或「本服務」指通過WhatsApp 平台讓閣下與本平台溝通的全自動化虛擬服務，並包括使用 Kinnso WhatsApp 平台與閣下溝通的所有商業合作夥伴，及 Kinnso WhatsApp 訂閱服務。
						<li>“WhatsApp” 及 “WhatsApp Messenger” 指由WhatsApp Inc. 提供並由Facebook, Inc. 持有的即時通訊軟件。

					</ol>
					<center>
						<div class="heading">Kinnso 會員</div>
					</center>
					<ol>
						<li>閣下一旦透過Kinnso WhatsApp 領取任何優惠或參與活動，即會自動成為Kinnso會員。成為會員後，本平台會就閣下的查詢、瀏覽及點選等行為資料，提供個性化的產品和優惠資訊。
						<li>本平台會向會員發放關於我們及合作夥伴的產品、促銷活動、市場活動和項目的最新消息和公告，當閣下使用本平台網站所提供的服務時，本平台將視同你已閱讀且同意本條款及<a href='/privacy' target="_blank">私隱政策聲明</a>。會員應先細閱相關條款及政策，如閣下不接受有關約束，應停止使用本服務。
						<li>閣下必須以個人有效之手機號碼，在WhatsApp上登記領取優惠及參與活動，每個優惠及活動只可被登記一次，重複申請均不會被接納。
						<li>若閣下未滿18歲，除非其家長或監護人同意有關私隱政策，否則不得參與會員計劃。如家長或監護人不同意有關私隱政策，閣下必須退出會員計劃。如果我們在沒有得到家長同意的情況下收集了18歲或以下會員申請人的個人信息，我們會盡快刪除該個人信息。如果父母需要隨時查詢、更正或刪除會員申請人的個人資料，他們可以透過 <a href="mailto:general@kinnso.ai" target="_blank">general@kinnso.ai</a> 與我們聯絡。
						<li>會員必須確保所提供的會員登記資料全屬真實、正確、完整、沒有誤導及欺詐成份。
						<li>會員可透過 <a href="mailto:general@kinnso.ai" target="_blank">general@kinnso.ai</a> 與我們聯絡申請取消會籍，其申請將於40天內處理。
					</ol>

					<center>
						<div class="heading">Kinnso Points 條款及細則</div>
					</center>
					<ol>
						<li>用戶於Kinnso網站（下稱「網站」）成功透過WhatsApp領取優惠，即自動登記成為Kinnso會員（下稱「會員」）。
						<li>WhatsApp手機電話號碼必須為真實及有效，恕不接受以虛擬電話號碼登記帳戶。每個會員只可用一個WhatsApp手機號碼登記帳戶，所有重複電話號碼登記，或一人擁有多個帳戶均不會被接納，否則Kinnso有權立即暫停或終止該會員的資格，並會立即取消所有積分及換領任何獎賞的資格，而毋須事先通知。
						<li>如會員更改手機號碼，必須立即電郵至 <a href="mailto:general@kinnso.ai" target="_blank">general@kinnso.ai</a> 通知Kinnso，使用新的手機號碼重新領取優惠以登記Kinnso 會籍。如發現任何會員使用非由其本人實際擁有之手機號碼登記或使用Kinnso會籍，Kinnso 有權暫停或終止該會員的資格而不作另行通知。
						<li>會員必須年滿11歲。如顧客未滿 18 歲，成為 Kinnso 會員之前應先取得其父母或監護人同意。
						<li>Kinnso 概不承擔任何未能成功傳送的WhatsApp 短訊之責任。
						<li>Kinnso 有權決定會員的會籍是否有效及保留撤銷有關獎賞換領之權利而毋須另行通知。
						<li>Kinnso 會籍及積分只限會員本人使用，不得轉讓他人。
						<li>所有會員資料、積分及換領紀錄，均以Kinnso所存之紀錄為準。
						<li>任何會員如被發現盜用他人帳戶、以不誠實方法登記會籍或使用會員福利，Kinnso有權隨時凍結或終止該等人士的會籍而毋須另行通知。
						<li>Kinnso會員可透過電郵至 <a href="mailto:general@kinnso.ai" target="_blank">general@kinnso.ai</a> 申請取消會籍，其申請將於40天內處理。
						<li>Kinnso Point積分有效日期詳情如下：
							<ol>
								<li>第一輪有效日期：每年於1月1日- 6月30日賺取的積分，將於同年12月31日到期，即會員須於同年12月31日或之前必須使用積分換領獎賞，否則積分於下年1月1日起自動失效。
								<li>第二輪有效日期：每年於7月1日- 12月31日賺取的積分，有效期為下年6月30日，即會員須於下年6月30日或之前必須使用積分換領獎賞，否則積分將於下年7月1日起自動失效。
							</ol>
						<li>積分並無金錢價值，不能以任何方式兌換現金。積分亦不得出售、購買、轉讓或轉移。
						<li>若Kinnso 誤發任何積分予會員，Kinnso有權取消或扣減該等積分而毋須另行通知。
						<li>若會員對積分獎賞有任何爭議，Kinnso保留最終決定權。
					</ol>

					<center>
						<div class="heading">獎賞 條款及細則</div>
					</center>
					<ol>
						<li>以Kinnso Points換領獎賞前須細閲使用條款，換領一經確認，系統會即時從會員賬戶扣取相關Kinnso Points，有關換領不可更改或取消。
						<li>會員必須於指定時限內使用成功換領的獎賞，否則當自動放棄論，亦不會獲補發獎賞或Kinnso Points。以Kinnso Points換領獎賞必須在Kinnso網站内進行。
						<li>任何因網絡問題、系統故障、電話接收問題、被第三方應用程式攔截、提供的資訊不完整、不正確、或遺漏而引致所遞交或接收的換領資料有遲延、遺失、錯誤或無法辨識等情況，而導致換領未能完成或換領遭遇問題等情況，Kinnso及參與商戶/供應商概不承擔任何責任。
						<li>Kinnso不會因人為錯誤（如遺失或被盜）而重發獎賞予會員。
						<li>Kinnso保留所有權利自行決定隨時增加、更改、終止任何禮品，或以同等價值的其他禮品代替，恕不另行通知。會員須自行在兌換禮品前透過Kinnso網站審視禮品供應情況。
						<li>會員領取的任何獎賞和禮品均不得兌換現金，亦不得取消任何已領取的獎賞和禮品。任何已領取的獎賞和禮品和/或任何未兌換的Kinnso points，均不獲現金退款。任何根據本推廣規則領取的現金券及優惠券均不得兌換現金，此類現金券及優惠券的使用應進一步遵守提供此類現金券及優惠券的商戶的條款及細則。
					</ol>

					<center>
						<div class="heading">蜜探報料 條款及細則</div>
					</center>
					<ol>
						<li>資料提供人須保證所有遞交的報料資訊（內容及照片）中不存在色情、暴力、不良意識或商業和宗教宣傳成份，亦不會構成侵權、誹謗、脅迫、違法、猥褻、不雅、煽情、冒犯、或引起種族仇恨或歧視；如抵觸任何法律或規例，資料提供人需自行承擔所有責任，一切與Kinnso無關。</li>
						<li>Kinnso保留將所有提供的報料資訊應用於任何傳播及展示的權利，包括但不限於平面或電子渠道，毋須事先徵得資料提供人同意及支付任何版權費用。</li>
						<li>Kinnso有權修改或刪剪任何圖片或文字檔案及以任何形式及用途(包括商業用途)使用圖片或文字檔案而不須另行通知上傳者或付費予上傳者。</li>
						<li>如接獲相同優惠，第一個資料提供人將會獲得積分，其餘資料提供人將不會獲得積分，不作通知。</li>
						<li>資料提供人每次只可上傳一個優惠，若同時上傳多個優惠，經採用後，積分將不會重複計算，只計算一次。</li>
						<li>在「蜜探報料」頁面內，資料提供人須輸入報料資訊，包括WhatsApp電話號碼、上載圖片/文件和優惠內容/優惠網址連結。如輸入資料錯誤或不足，該申請將會被拒絕，不作通知。</li>
						<li>Kinnso將在21個工作天內核對所遞交之資料。經採用後，積分將存入資料提供人的會員帳戶內，請自行瀏覽「我的積分詳情」內之積分狀態（依日期排列）。</li>
						<li>Kinnso 保留權利拒絕給予積分予有問題之報料資訊，包括但並不限於上載之圖片不清楚、報料重覆、已超過每日兌換積分上限等，一切將以Kinnso之最終決定為準。</li>
						<li>請確保網絡穩定以便成功上載報料資訊，若網絡不穩而未能成功上載報料資訊，Kinnso概不負責。</li>
					</ol>

					<center>
						<div class="heading">上載收據及記錄 條款及細則</div>
					</center>
					<ol>
						<li>利用「上載收據」功能，會員必須上載有效商戶機印發票及相符之收據（簽賬者必須為會員本人）方可申請登記積分。</li>
						<li>Kinnso將在21個工作天內核對所遞交之資料（請保留商戶機印發票及相符之收據之正本作日後稽核之用）。經核實後，積分將存入會員帳戶內，會員可自行瀏覽「我的積分詳情」內之積分狀態（依日期排列）。</li>
						<li>會員每次只可上傳一張商戶機印發票及相符之收據，若同時上傳多張商戶機印發票及相符之收據，將不獲接納。</li>
						<li>在「上載收據」頁面內，會員須輸入消費資料，包括選擇相關優惠、商戶名稱、購買日期、消費金額和收據號碼。如輸入資料錯誤或不足，該積分登記申請將會被拒絕。</li>
						<li>Kinnso保留權利拒絕給予積分予有問題之收據，包括但並不限於上載之圖片不清楚、收據號碼重覆、已超過每日兌換積分上限等，一切將以Kinnso之最終決定為準。</li>
						<li>請確保網絡穩定以便成功上載商戶機印發票及相符之收據，同時為確保審核程序暢順，上載之商戶機印發票及相符之收據必須清晰及完整。由於網絡不穩而未能成功上載商戶機印發票及相符之收據，Kinnso概不負責。</li>
						<li>所有上傳收據之圖像只作積分登記及內部稽核之用，完成積分登記後，上述資料保留6個月-12個月。</li>
					</ol>

					<center>
						<div class="heading">Kinnso WhatsApp 的使用條款及細則</div>
					</center>
					<ol>
						<li>條款修訂<ol>
								<li>本平台可不時全權酌情修改本條款，而無須對閣下承擔責任。本平台會於WhatsApp上傳任何經修改後的本條款。如閣下於本條款作出修訂後，仍繼續使用本服務，即視為閣下已接受有關修訂並同意受經修訂的本條款約束。如閣下不接受有關修訂，應停止使用本服務。
							</ol>
						<li>許可<ol>
								<li>作為閣下同意遵守本條款的對價，本平台授予閣下不可轉讓、非專有、可撤銷的許可使用本服務，僅供閣下在香港境內於閣下的流動裝置上使用本服務。本服務僅供閣下作個人使用。
							</ol>
						<li>本平台的知識產權<ol>
								<li>閣下明白並同意，就本服務及其內容（包括與其有關的所有商標、服務商標、商號及標誌，任何文稿、圖像、連結及音響，及與第三方連結可獲得的內容）的所有權利、所有權、權益及知識產權，均由本平台或其第三方許可人獨享。閣下對本服務或其內容並無權利、所有權或權益，除上述有限許可外亦無權作出使用。閣下不得為任何目的使用本平台或其第三方許可人的任何商標、服務商標、商號或標誌，或任何其他知識產權。本條款中的條文均不得被詮釋為向閣下授予有關該等商標、服務商標、商號、標誌或知識產權的任何權利。
							</ol>
						<li>隱私權<ol>
								<li>閣下可以個人電話號碼登記使用本服務。閣下之電話號碼只會被本平台及其第三方許可人保存及使用於本服務及其改善和分析之用途。閣下不需向本平台提供任何其他個人資料以使用本服務。
								<li>Kinnso WhatsApp不會於對話時透露或要求閣下透露任何敏感個人資料、戶口資料、密碼及其他憑證。請避免透露任何個人資料、戶口資料、密碼及其他憑證。本平台亦提醒閣下切勿外傳及請保密你的裝置、個人資料、戶口資料、密碼及其他憑證。
								<li>當閣下同意使用本平台的服務後，本平台就會取得閣下的個人資料，包括閣下有可能在第三方供應商上註冊或使用本平台的服務時所提供的姓名、電話、電子郵件信箱、使用網路連線服務的 IP 位址、使用時間、瀏覽及點選資料記錄等資料。所取得的個人資料只會用作網路流量和行為調查之分析，不會直接識別閣下的身份，但可能與閣下或特定設備相對應。
								<li>使用本服務須受本平台私隱聲明當中所列出之條款規範。如你使用本服務及提供個人資料予 Kinnso ，即表示你接受並同意受該等條款約束。
								<li>閣下使用本服務須受WhatsApp 訂定的私隱政策及服務條款約束（請參考<a href='https://www.whatsapp.com/legal/' target="_blank">https://www.whatsapp.com/legal/</a>）。閣下有責任於使用本服務前自行了解及接受WhatsApp 的條款及細則。對於 WhatsApp 不時訂定的適用條款和政策，本平台沒有義務另行通知閣下，閣下應自行了解及遵守該等條款和政策。就WhatsApp 的任何行為或疏忽（包括WhatsApp 如何收集、使用、轉移或處理閣下的個人資料或閣下內容），本平台概不負責。
								<li>閣下提供的所有資料和對話内容將會被分析，用作提升本服務準確度及質素，以及提供購物服務之用。該等對話内容及資料將會被本平台及其第三方許可人保留及分析。
								<li>由閣下分享的網路定位數據只會用於解答詢問。網路定位數據將會被視作對話内容並保留及分析。
							</ol>
						<li>閣下的行為<br />閣下同意：<ol>
								<li>閣下須為違反本條款項下閣下的責任及為閣下的行動或遺漏自行負責，就該等事項本平台對閣下或任何其他人士均無責任；
								<li>閣下只會使用Kinnso WhatsApp或本服務及其內容作本條款明確允許的用途；
								<li>閣下不會拆解、解構或還原Kinnso WhatsApp或本服務；
								<li>閣下不會進行任何活動干擾或中斷Kinnso WhatsApp或本服務，或提供Kinnso WhatsApp的服務器及網絡；
								<li>閣下不會複製、修改、再造、下載、重新發布、出售、分發或轉售Kinnso WhatsApp、本服務或其內容（全部或部分），或從而製作衍生作品；
								<li>閣下不會安裝、上載或傳送（或允許安裝、上載或傳輸）任何能夠中斷、癱瘓、損壞、關閉、監控或未經授權取用Kinnso WhatsApp、本服務、或其他電訊或電腦系統或裝置或透過上述各項傳送或儲存的任何資料的任何病毒或指示、代碼、技術或裝置；
								<li>閣下不會採取任何行動或允許任何其他人士採取任何行動，讓閣下或任何其他人士可未經授權取用、監控、干預或使用本平台的任何電腦系統或網絡，或用以提供Kinnso WhatsApp的電腦系統或網絡；
								<li>閣下不會以任何非法，或違反任何適用法律，或促進非法活動的方式使用Kinnso WhatsApp、本服務或其內容；
								<li>閣下不會違反或侵犯任何其他人士的權利；
								<li>閣下不會以任何方式使用Kinnso WhatsApp、本服務或其內容發送、通訊或上載任何材料或內容，或作出任何行為或活動，而該等材料、內容、行為或活動的性質屬騷擾、破壞、侮辱、攻擊、威脅、不雅、誹謗、淫穢或恐嚇，亦不會發送非應邀的通訊、促銷、廣告或垃圾信息；及
								<li>閣下不會使用Kinnso WhatsApp、本服務或其內容冒充他人，或用其他方式就與其他人士或實體的聯繫作出失實表述意圖誤導、混淆或欺騙他人。
							</ol>
						<li>Kinnso WhatsApp 訂閱服務<ol>
								<li>本平台有權不時決定或規定 Kinnso WhatsApp 訂閱服務的服務範圍及功能，本平台亦可能隨時取消、撤回、暫緩、更改、增加或減少 Kinnso WhatsApp 訂閱服務而無須給予通知或原因。
								<li>為使用 Kinnso WhatsApp 訂閱服務，你應：<br />a) 持有WhatsApp 帳戶；及<br />b) 依本平台指示登記 Kinnso WhatsApp 訂閱服務
								<li>完成登記後，本平台會通過本平台的WhatsApp 帳戶向閣下傳送電子訊息。電子訊息的內容是由 Kinnso 公開網站生成。
								<li>任何以 Kinnso WhatsApp 訂閱服務向閣下或其他人士所傳送的電子訊息資料或對話不應被理解為任何產品或服務的要約或招攬。
							</ol>
						<li>第三方材料<ol>
								<li>Kinnso WhatsApp及其內容可能包含第三方材料。閣下明白並同意本平台無須對第三方材料負責， 亦無責任對第三方材料進行積極監控或施加控制。本平台未有就任何第三方材料作出認可、核實或任何保證或表述。閣下須自行承擔使用或依賴第三方材料的風險。
								<li>Kinnso WhatsApp可能可瀏覽這些本平台以外人士提供及/或刊發的第三方材料：一般理財及市場資訊、新聞服務、市場分析、產品資訊及市場推廣資訊（合稱「第三方資訊」），亦可能提供以任何形式、媒體或途徑的第三方資訊編輯而成的報告。第三方資訊可能於Kinnso WhatsApp提供或透過Kinnso WhatsApp中的連結從第三方網頁或資源（「第三方網頁」） 存取。第三方資訊提供的，或透過資訊中的連結提供的，或第三方網頁提供的內容、準確性、完整性、適時性或發表的意見或觀點，並未經由本平台調查、核實、監察或認可。本平台明確聲明不會對Kinnso WhatsApp中所提供或任何與Kinnso WhatsApp連結的第三方網頁的內容、其可供使用情況或第三方資訊的錯誤或遺漏承擔任何責任。
								<li>閣下與第三方網頁作任何聯線或離線瀏覽或進行交易前，須自行負責一切所需的諮詢或調查。閣下明白及接受，閣下透過或於Kinnso WhatsApp進行的所有活動，其風險概由閣下承擔。本平台並不對任何閣下可能透過第三方網頁提供或被要求提供予任何人士的資料的保安作出保證。閣下並被視為已不可撤回地放棄因透過Kinnso WhatsApp 瀏覽或接觸任何第三方網頁或與之有關而產生或蒙受的任何損失、損害或開支而對本平台的一切索償。
								<li>Kinnso WhatsApp 可能會包含連結至其他網站或e-shop（「公司網站及e-shop」），以方便閣下。公司網站及e-shop提供的產品及服務可能只限向身處或居於指定的司法管轄地區的人士提供。此外，公司網站及e-shop的內容可能在某些司法管轄地區被禁止或受到限制而不可發布，故此並無意向身處或居於該等地區的人士提供。Kinnso 提供的公司網站及e-shop的使用條款可能互有差異，閣下應先仔細查閱適用的使用條款，然後才使用或瀏覽有關的網站及e-shop。
							</ol>
						<li>法律責任限制<ol>
								<li>除第 8.2 段列明的條款外，閣下因下列情況或與之有關而可能招致或蒙受的任何種類的損失、損害或開支，本平台無須向閣下負責：<br />
									a) 閣下使用或未能使用Kinnso WhatsApp、本服務、Kinnso WhatsApp登載的任何內容或第三方資訊；<br />
									b) 本服務因任何原因發生的任何故障、中斷或延遲（包括任何因電腦、電子系統或設備的故障或錯誤）；<br />
									c) 閣下向本平台傳送或本平台向訊息閣下發出之訊息的任何延誤、丟失、轉向、被攔截、改動或訛誤；<br />
									d) 因或有關閣下使用本服務而引致閣下的數據、軟件、流動設備或其他設備有任何損失或損害；<br />
									e) 任何WhatsApp 故障或錯誤，或WhatsApp Inc. 的行為或遺漏。
								<li>若經證實於條款 8.1 列明之事件是由(a) 本平台(b) 本平台的代理(c) 本平台或其代理的職員或員工的疏忽或故意失責所引致，就該疏忽或故意失責而使閣下蒙受的任何直接及合理可預見之損失及損害，本平台會向閣下負責。
								<li>本平台向閣下提供的本服務出現任何中斷、延遲或故障（不論屬全面或局部），如屬於本平台或本平台的代理的合理控制以外的原因或情況造成，則本平台無須對閣下因而招致或蒙受的任何種類的任何損失、成本或損害負責。
							</ol>
						<li>保安<ol>
								<li>閣下同意本服務僅供閣下個人使用，並須採取一切合理的預防措施，防止任何未經授權或帶欺詐意圖而取用閣下的WhatsApp 帳戶或本服務提供閣下的訊息。
								<li>閣下須自行負責確保閣下的流動設備有足夠保護及製作資料及／或儀器的備份，包括採取合理及適當的預防措施。
							</ol>
						<li>一般事項免責聲明<ol>
								<li>Kinnso WhatsApp 及本服務僅提供有關 Kinnso 產品及服務的一般公開資料。Kinnso 公司網站亦可提供相同資訊。
								<li>Kinnso WhatsApp 主要旨在提供予任何人士於香港境內使用。 Kinnso 祗將 Kinnso WhatsApp 提及的產品及服務提供予當時在法律上合法容許的地區。本平台不擬將此等資料提供予置身或居住於該等在法律上限制本平台發放此等資料之地區的人士使用。閣下必須自行了解及遵守有關限制。
								<li>本平台可酌情提供或拒絕提供 Kinnso WhatsApp 內提述的任何資料、產品或服務予任何人士。本平台可酌情決定隨時撤回或修改Kinnso WhatsApp 內提述的任何資料、產品或服務，而無須事先通知。
								<li>閣下同意自行承擔閣下使用 Kinnso WhatsApp、本服務及Kinnso WhatsApp 內容的風險，而 Kinnso WhatsApp、本服務及 Kinnso WhatsApp 內容均是按「現況」及「現有」的基礎而提供。
								<li>在適用法律允許的最大範圍內，本平台明確表示不會就 Kinnso WhatsApp、本服務、Kinnso WhatsApp 內容或 Kinnso WhatsApp 提供的第三方材料，作出任何陳述及保證（不論任何性質或種類，亦不論是明示或默示），包括但不限於關於質量及對特定用途的適用性的任何默示陳述及保證。
								<li>本平台對於下述各項均不會作出任何陳述或保證：Kinnso WhatsApp、本服務、Kinnso WhatsApp 內容或第三方材料的準確性、質量、完整性、及時性、充分性、安全性、可靠性或有效性；閣下對 Kinnso WhatsApp、本服務、Kinnso WhatsApp內容或第三方材料的使用將不會中斷，並將會及時、安全、不帶任何錯誤或缺陷，亦不帶能夠中斷、癱瘓、損壞、關閉、監控或未經授權取用任何電訊或電腦系統或設備或透過上述電腦系統或設備傳送或儲存的任何資料的病毒、指示、代碼、技術或裝置；及 Kinnso WhatsApp、本服務、Kinnso WhatsApp 內容或第三方材料的運作或功能方面的缺陷將會被改正。
								<li>Kinnso WhatsApp帳戶於WhatsApp 開立，本平台無法就WhatsApp 的功能、品質、安全性或適用性做出任何類型的陳述、保證、擔保或約定。本平台無法擔保對Kinnso WhatsApp的瀏覽不會發生中斷、延遲或故障，或者WhatsApp 平台、用戶端等的問題不會導致閣下個人資訊洩露、交易失敗、資料錯誤等事故。如由於本平台合理控制範圍以外的因素，包括任何設備故障或失靈、WhatsApp 或任何第三方的行為或疏忽、電力中斷或設備、安裝或設施不足，而導致閣下無法瀏覽Kinnso WhatsApp所載之內容，或導致閣下蒙受任何種類的損失、損害或開支，本平台概不負責。
							</ol>
						<li>終止<ol>
								<li>本平台有權隨時酌情決定暫停或終止閣下取用及使用Kinnso WhatsApp，而無須給予通知或原因。
								<li>如有以下情形，本平台有權立即終止閣下瀏覽及使用Kinnso WhatsApp及其所載之內容的許可而無須給予閣下通知或原因： 閣下嚴重或屢次違反本條款中的任何條款；或<br />
									a) 本平台得悉或合理懷疑閣下進行或有意進行欺詐行為或任何非法或不當行為； 或<br />
									b) 閣下因任何原因無權或不符合資格使用Kinnso WhatsApp；或<br />
									c) WhatsApp 因任何原因撤銷Kinnso WhatsApp帳號或閣下的帳號。
								<li>閣下可藉停止使用Kinnso WhatsApp而隨時終止瀏覽及使用本服務及內容。閣下可藉<a href="mailto:general@kinnso.ai" target="_blank">general@kinnso.ai</a>與我們聯絡，申請取消會籍，更改訂閱選項而終止訂閱Kinnso WhatsApp 訂閱服務。本平台會在接受到閣下的要求時的合理期間內，盡最大努力去依照你的要求做更正或刪除。 刪除閣下於Kinnso的帳戶將不會自動刪除本平台所保存關於閣下的個人資訊。如果閣下希望本平台刪除所有關於閣下的個人資訊且註銷閣下的帳戶，請參考上述所提供的建議。 請注意從本平台的資料庫中刪除您的個人資訊將使我們無法再提供給閣下任何服務。
							</ol>
						<li>可分割性<ol>
								<li>如本條款中任何條文屬於或變成不合法、無效或不能強制執行，其他條文保持全面有效，不受該等不合法性、無效性或不能強制執行性影響。
							</ol>
						<li>整體協議<ol>
								<li>本條款包含閣下與本平台就相關事宜的整體協議。
							</ol>
						<li>放棄及採取補救方法的權利<ol>
								<li>本平台對本條款任何條文的放棄須以書面形式作出，並只限於放棄本平台書面通知內明確規定的任何該等條文。本平台未有或延遲行使任何權利、權力或採取補救方法的權利，並不會構成本平台放棄行使該等權利、權力或採取補救方法的權利。而本平台行使任何一項或部分的權利、權力或採取補救方法的權利，亦不會排除本平台行使何其他或進一步地行使權利、權力或採取補救方法的權利。本條款下的任何權利、權力或採取補救方法的權利應被視為除法律授予本平台外，本平台可享有的累積及額外的權利、權力或採取補救方法的權利。
							</ol>
						<li>轉讓<ol>
								<li>在未經本平台事先書面同意的情況下，閣下不得轉讓本條款下的任何權利或責任。本平台可無須閣下同意轉讓本平台的權利或責任予任何其他人士。
							</ol>
						<li>第三者權利<ol>
								<li>除閣下及本平台以外，並無其他人士有權按《合約（第三者權利）條例》強制執行本條款的任何條文，或享有本條款的任何條文下的利益。
							</ol>
						<li>資訊<ol>
								<li>若 Kinnso 官方網站和Kinnso WhatsApp之間所提供的資訊有任何抵觸或歧義，應以 Kinnso 官方網站為準。
							</ol>
						<li>管轄法律及司法管轄權<ol>
								<li>本條款受香港法律管轄，並按此詮釋。閣下接受香港法院的非專屬司法管轄權管轄。本條款可在任何具司法管轄權的法院强制執行。
							</ol>
					</ol>
					<center>
						<div class="heading">私隱政策</div>
					</center>
					<div>
						我們致力保護我們所持有的個人資料信息及其他信息。有關收集及使用閣下個人資料的實務及詳情請瀏覽 <a href='https://www.kinnso.ai/privacy' target="_blank">https://www.kinnso.ai/privacy</a>
					</div>
					<center>
						<div class="heading">一般條款</div>
					</center>
					<ol>
						<li>本平台保留權利更改、終止或暫停本計劃，或隨時更改有關之條款及細則，毋須亦沒有責任另行通知。本平台沒有義務預先通知會員本計劃將被終止或暫停、或有關條款及細則已被更新。
						<li>本平台如未能執行某條款或細則，並不代表本平台豁免該條款或細則。
						<li>所有條款均受香港特別行政區之法律約束和監管。
					</ol>
					<center>
						<div class="heading">免責聲明</div>
					</center>
					<ol>
						<li>會員須自行承擔對使用本平台的風險，會員因此而產生之任何損失或損害，我們概不負責，亦無賠償該損害之責任。我們因刪除會員資料；停止會員資格；停止、中斷、終止本獎賞計劃時，不需負擔任何損害賠償責任。
						<li>本平台及其網站所載的所有資料、商標、標誌、圖像、短片、聲音檔案、連結及其他資料等（以下簡稱「資料」），只供參考之用，我們將會隨時更改資料，並由我們決定而不作另行通知。我們會盡力確保所有資料的準確性，但我們不會明示或隱含保證該等資料均為準確無誤。我們不會對任何錯誤或遺漏承擔責任。
						<li>若有任何爭議，本平台保留最終決定權。
					</ol>
					<center>
						<div class="heading">聯繫我們</div>
						如果閣下對本會員使用條款有任何疑問或查詢，請透過 <a href="mailto:general@kinnso.ai" target="_blank">general@kinnso.ai</a> 與我們聯絡。
					</center>
				</div>

				@include('website/common/footer')
			</div>
		</div>
	</body>

</html>
