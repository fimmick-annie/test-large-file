<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		@include('website/common/head')

		<!--  Help to ensure the left menu in right size  -->
		<link rel="stylesheet" href="./offers/common/offer_listing.css?v=1" />

		<style>
		:root {
			--theme-header-color: #F37621;
			--theme-pale-yellow: rgba(252, 204, 8, 0.1);
		}
		body {
			background-color: #ffffff;
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
			padding: 35px 30px;
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
		.listing  {
			color: #757575;
			font-size: 1rem;
			line-height: 1.57142857;
		}
		.heading  {
			font-weight: bold;
			margin-top: 30px;
			margin-bottom: 10px;
		}

		.wrapper  {
			font-family: Noto Sans;
			color: #57524F;
			max-width: 100vw;
			background: #fdfae6;
			position: relative;
		}

		.big_title{
			display: block;
			text-align: center;
			color: #F37621;
			font-size: 1.2rem;
			line-height: 4em;
			letter-spacing: .1rem;
			padding: 4.8em 4.8em 2.8em 4.8em;
			background: #ffffff;
		}

		.big_title img{
			max-height: 1.3em;
			width: auto;
			margin-bottom: .4em;
			margin-right: .4em;
		}

		.tab_left{
			position: absolute;
			margin-left: auto;
			margin-right: auto;
			left: 0;
			right: 0;
			width: 250px;
			transform: translate(-60%, -100%); 
	
		}

		.tab_right{
			position: absolute;
			margin-left: auto;
			margin-right: auto;
			left: 0;
			right: 0;
			width: 250px;
			transform: translate(60%, -100%); 
		}

		#upload01{
			display:none;
		}
		#record02{
			display:none;
		}
		.tab-2{
			display:none;
		}
		
		.form {
			max-width: 800px;
			margin: auto;
			background: #fdfae6;
		}

		.form label{
			font-size: 1rem;
			letter-spacing: .1rem;
			padding-top: 1rem;
		}

		.form label.necessary::after {
			content: '*';
			color: #F37621;
			padding-left: 2px;
		}

		.form small {
			font-size: calc(11px);
		}

		.form small.error {
			font-size: calc(13px);
		}

		.form .form-control{
			/* background: var(--theme-pale-yellow);
			border: 1px solid var(--theme-pale-yellow); */
			border: 0px;
			padding: .2rem .75rem;
			border-radius: 5px;
		}
		form input {
			height: 25px;
			line-height: 25px;
			padding-bottom: 0px;
			box-sizing: border-box;

		}
		.form textarea.form-control {
			background: #ffffff;
			/* border: 1px solid #F5CD47; */
			border-radius: 8px;
			resize: none;
			height: 150px;
		}

		.form textarea.form-control::placeholder {
			letter-spacing: 2.6px;
			color: #6D6D6D;
			opacity: 50%;
		}

		button.submit-btn{
			display: block;
			margin: auto;
			letter-spacing: 4.8px;
			background-color: #ffffff;
			color: #F37621;
			font-size: 16px;
			border: 1px solid #FBCB30;
			border-radius: 31px;
			padding: 10px 130px;
			cursor: pointer;
		}

		[type="date"] {
			background: url("{{ asset('website/receipt_upload/icon_calender.png')}}") 95% no-repeat  ;
			background-size: auto 80%;
		}
		[type="date"]::-webkit-inner-spin-button {display: none;}
		[type="date"]::-webkit-calendar-picker-indicator {opacity: 0;}

		.amount-input {
			position: relative;
		}
		.amount-input > p {
			position: absolute;
			display: block;
			transform: translate(0, -2%); 
			top: 50px;

			pointer-events: none;
			width: 25px;
			text-align: center;
			font-style: normal;
		}
		.amount-input > input {
			text-indent: 15px;
		}

		.modal01, .modal02, .modal03, .modal04{
			display: none;
			position: fixed; 
			z-index: 1; 
			padding-top: 100px; 
			left: 0;
			top: 0;
			width: 100%; 
			height: 100%;
			overflow: auto; 
			background-color: rgb(0,0,0);
			background-color: rgba(0,0,0,0.4);
		}

		/* Modal Content 01--------------- */
		.modal01-content {
			background-color: #fefefe;
			margin: auto;
			padding: 20px;
			border: 10px solid #FBCB30;
			border-radius: 20px;
			width: 80%;
			max-width:500px;
			min-height:500px;
			position: relative;
		}

		.modal01-content .bear01{
			position: absolute;
			top: 32%;
			margin-left: auto;
			margin-right: auto;
			left: 0;
			right: 0;
			text-align: center;
		}

		/* The Close Button */
		.modal01-content .close01 {
			float: right;
			position: absolute;
			right: -9%;
			width: 6%;
			top: -5%;
		}

		.modal01-content h3{
			position: absolute;
			top: 20%;
			left: 50%;
			transform: translate(-50%, -50%);
			color: grey;
			font-size: 1.5rem;
			line-height: 2em;
			letter-spacing: 0.2rem;
			white-space: nowrap;
		}

		/* Modal Content 02--------------- */
		.modal02-content {
			background-color: #fefefe;
			margin: auto;
			padding: 20px;
			border: 4px solid #FBCB30;
			border-radius: 20px;
			width: 90%;
			max-width:600px;
			min-height:450px;
			position: relative;

		}

		/* The Close Button */
		.modal02-content .close02 {
			position: absolute;
			float: right;
			right: -7%;
			top: -3%;
    		width: 5%;
		}

		.modal02-content .medal02-info{
			position: absolute;
			margin-left: auto;
			margin-right: auto;
			left: 0;
			right: 0;
			top: 10px;
		}

		.#modal02-receipt{
			position: absolute;
			top:10px;
			width: 90%;
		}

		/* Modal Content 03--------------- */
		.modal03-content {
			background-color: #fefefe;
			margin: auto;
			padding: 20px;
			border: 10px solid #FBCB30;
			border-radius: 20px;
			width: 90%;
			max-width:600px;
			min-height:500px;
			position: relative;
		}

		/* The Close Button */
		.modal03 .close03 {
			position: absolute;
			right: -9%;
			width: 5%;
			top: -5%;
		}

		.modal03-content h3{
			text-align: center;
		}

		.box{
			overflow-wrap: break-word;
			inline-size: 45%;
			padding-top:5%;
		}

		/* Modal Content 04--------------- */
		.modal04-content {
			background-color: #fefefe;
			margin: auto;
			padding: 20px;
			border: 5px solid #FBCB30;
			border-radius: 20px;
			width: 80%;
			max-width:500px;
			min-height:390px;
			position: relative;
		}

		.modal04-content .bear02{
			position: absolute;
			top: 32%;
			margin-left: auto;
			margin-right: auto;
			left: 0;
			right: 0;
			text-align: center;
		}

		/* The Close Button */
		.modal04-content .close04 {
			float: right;
			position: absolute;
			right: -9%;
			width: 6%;
			top: -5%;
		}

		.modal04-content h3{
			position: absolute;
			top: 18%;
			left: 50%;
			transform: translate(-50%, -50%);
			text-align:center;
			color: grey;
			font-size: 1.2rem;
			line-height: 1.5em;
			font-weight: 400;
			white-space: nowrap;
		}

		.modal04-content .backHome {
			float: right;
			position: absolute;
			top: 400px;
			left: 50%;
			transform: translate(-50%, -50%);
			height: 50px;
			width: 250px;
			background-color: #fefefe;
			border: 3px solid #FBCB30;
			border-radius: 30px;
		}
		
		.modal04-content .backHome span{
			position: absolute;
			top:15%;
			margin-left: auto;
			margin-right: auto;
			left: 0;
			right: 0;
			text-align: center;
			color: #F37621;
			font-size: 1.2rem;
			letter-spacing: .3rem;
		}

		.record_list{
			padding-top: 2em;
		}

		.right_side_color{
			border-right-style: solid;
			border-right-color: #FACD56;
			border-right-width: medium;
		}

		.table_header{
			border-right-style: solid;
			border-right-color: #FACD56;
			border-bottom-style: solid;
			border-bottom-color: #FACD56;
			text-align: left;
			font-family: Noto Sans;
			font-weight: 120px;
			border-right-width: medium;
			border-bottom-width: medium;
			display: flex;
			align-items: center;
			justify-content: left;
		}

		.table_header_right{
			border-bottom-style: solid;
			border-bottom-color: #FACD56;
			border-bottom-width: medium;
			text-align: left;
			font-family: Noto Sans;
			font-weight: 120px;
			display: flex;
			align-items: center;
			justify-content: left;
			white-space: nowrap;
		}

		.table_content{
			border-right-style: solid;
			border-right-color: #FACD56;
			border-right-width: medium;
			text-align: left;
			display: flex;
			align-items: center;
			justify-content: left;
			border-bottom-style: solid;
			border-bottom-color: rgba(240, 240, 240, 0.7);
			border-bottom-width: thin;
			justify-content: space-around;
		}

		.table_content_right{
			text-align: left;
			display: flex;
			align-items: center;
			justify-content: left;
			border-bottom-style: solid;
			border-bottom-color: rgba(240, 240, 240, 0.7);
			border-bottom-width: thin;
		}

		.table_content_topline{
			border-right-style: solid;
			border-right-color: #FACD56;
			border-right-width: thin;
		}

		.list-content-page{
			font-size: 0.7em;
			font-weight: 40px;
			line-height: 1rem;
			font-family: Noto Sans;
			margin-left: auto;
			margin-right: auto;
			left: 0;
			right: 0;
			min-width:350px;
		}
		.fimmick_warningRow  {
			color: #ff0000;
		}
		.fimmick_hightlightedRow  {
			color: #008000;
		}

		.horizontal_scroll{
			display: flex;
			flex-direction:row;
			justify-content: space-between;
			align-items: center;
			position: relative;
			overflow: hidden;
			min-height: 400px;

		}

		.cate_container{
			display: flex;
			flex-direction: row;
			justify-content: center;
			align-items: center;
			position: absolute;
			left: 0px;
			transition: 0.5s all;
		}

		 /* adjust the content insides the cate */
		.cate {
			position: relative;
			width: 350px;
			margin: 10px 10px 10px 10px;
			justify-content: top;
			scroll-behavior: smooth;
			padding:auto;
		}

		.cate img{
			text-align: center;
		}

		
		@media (max-width: 800px)  {
			.logo__desktop  {display: none;}
			.tab_left { 
				width:40%;
				/* transform: translate(0%, -100%);   */
			}
			.tab_right { 
				width:40%;
				/* transform: translate(0%, -100%);   */
			}
			.list-content-page{
				font-size: 0.5vh;
			}

			.table_content{
				font-size: x-small;
				padding: 10px auto ;
			}
			.table_header, .table_header_right{
				font-size: small;
			}
		}
		@media (min-width: 800px) {
			.logo__mobile  {display: none;}
			.form small {
				font-size: calc(14px);
			}
			.amount-input > p {
				transform: translate(0, -20%); 
				top: 53px;
			}
		}

		#reset{
			color: #F37621;
			padding-left: 85%;
		}

		form .error {
			color: #ff0000;
			padding: 0 0 0 0;
			font-size: 12px;
		}

		.invalid-feedback {
			display: none;
			position:absolute;
			left:10%;
			width: 100%;
			margin-top: 0.25rem;
			font-size: 80%;
			color: #dc3545;
		}
		#modal02-title{
			font-size: large;
			color: #939393;
			display:inline;
			overflow:nowrap;
		}
		#modal02-date, #modal02-offer{
			font-size: 0.9rem;
			color: #ADADAD;
			/* display:inline; */
			overflow:nowrap;

		}
		#modal02-status{
			font-size: 0.9rem;
			/* display:inline; */
			overflow:nowrap;

		}
	</style>

	</head>

	<body>
		@include('website/common/tracking_body')

		<!--  Segment  -->
		<script>
			!function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","reset","group","track","ready","alias","debug","page","once","off","on","addSourceMiddleware","addIntegrationMiddleware","setAnonymousId","addDestinationMiddleware"];analytics.factory=function(e){return function(){var t=Array.prototype.slice.call(arguments);t.unshift(e);analytics.push(t);return analytics}};for(var e=0;e<analytics.methods.length;e++){var key=analytics.methods[e];analytics[key]=analytics.factory(key)}analytics.load=function(key,e){var t=document.createElement("script");t.type="text/javascript";t.async=!0;t.src="https://cdn.segment.com/analytics.js/v1/" + key + "/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(t,n);analytics._loadOptions=e};analytics._writeKey="W1yJ9TYonvWx2FlA6469eTyvX0xegGS3";analytics.SNIPPET_VERSION="4.13.2";
				analytics.load("{{ env('SEGMENT_ID') }}");
				analytics.page("about-us", {
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
		</dix>


		<div class="wrapper">
			<div class="big_title">
				<img src="{{ asset('website/receipt_upload/icon02_upload.png') }}?v=1" alt="icon" />上載收據及記錄
			</div>
			<input id="memberID" name="memberID" type="hidden" value="{{$id}}">
			
				<div id="upload01" ><img class="tab_left" src="{{ asset('website/receipt_upload/tab1_upload.png') }}?v=1" alt="icon"/></div>
				<div id="upload02" ><img class="tab_left" src="{{ asset('website/receipt_upload/tab1_upload_selected.png') }}?v=1" alt="icon"/></div>
				<div id="record01" ><img class="tab_right" src="{{ asset('website/receipt_upload/tab2_record.png') }}?v=1" alt="icon"/></div>
				<div id="record02" ><img class="tab_right" src="{{ asset('website/receipt_upload/tab2_record_selected.png') }}?v=1" alt="icon"></div>

				<div class="tab-1">

					<form id="form" name="form" class="form mt-4 px-4">
						<div class="form-group">
							<div class="upload-area  {{ $errors->has('receipt_image') ? 'error' : '' }}">
								<input type="file" class="dropify" id="receipt_image" name="receipt_image" data-default-file="{{asset('website/receipt_upload/deflaut.png')}}" data-allowed-file-extensions='["jpg", "jpeg", "png"]' data-max-file-size="5M" data-errors-position="outside" data-show-remove="false" required>
							</div>
						</div>
@error('receipt_image')
						<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror

						<div class="form-group mb-2 px-1">

							<div class="row">

								<div class="col-md-6">
									<label for="offer" class="necessary mb-2">優惠</label>

									<select id="offer" name="offer" class="form-control" required>									
										<option value="" disabled selected>請選擇</option>
@if(isset($offerInfo))
@foreach( $offerInfo as $offerSel )
										<option value="{{ $offerSel['id'] ?? ''}}" data-start="{{ $offerSel['start_at'] ?? '' }}">{{$offerSel['title'] ?? ''}} </option>
@endforeach
@endif
									</select>  

								</div>

								<div class="col-md-6">
									<label for="channel" class="necessary mb-2 ">商戶</label>
									<select class="form-control"  id="channel" name="channel" required>
										<option value="" disabled selected>請選擇</option>
									</select>
								</div>

							</div>

							<div class="row">

								<div class="col-md-4">
									<label for="issueDate" class="necessary mb-2">購買日期</label>
									<input type="date" class="form-control" id="issueDate"  name="issueDate" value="{{ old('issueDate') }}" maxlength="10" required>
								</div>

								<div class="col-md-4 amount-input">
									<label for="amount" class="necessary mb-2">消費金額</label><p>$</p>
									<input type="text" class="form-control" id="amount"  name="amount" value="{{ old('amount') }}" maxlength="10" required>
									
								</div>

								<div class="col-md-4" >
									<label for="receiptNumber" class="necessary mb-2">收據號碼</label><img src="{{ asset('website/receipt_upload/buttom_question.png') }}?v=1" style="width:20px; display:inline;" onclick=getSample() required>
									<input type="text" class="form-control" id="receiptNumber"  name="receiptNumber" value="{{ old('receiptNumber') }}" maxlength="20">
									<span id="reset">重設</span>
								</div>

							</div>

						</div>

						<!-- <input type="hidden" name="_token" value="{{ csrf_token() }}" /> -->
						<input class="form-control" type="hidden" value="" name="path" id="path">
						<button id="submit" type="button" class="submit-btn mb-5">提交</button>
					</form>
				</div>

				<div class="tab-2">
					<div class="record_list" style="max-width: 650px;margin:auto;">
						<div class="row justify-content-center " style="height: 40px;">
							<div class="col-7 table_header">相關優惠</div>
							<div class="col-2 table_header_right">收據圖片 </div>
						</div>
						<div class="list-content">
							<div id="list-content-page" class="row justify-content-center" style="height: 40px;">
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>

		<!-- <input type="hidden" id="channelStartDate" name="channelStartDate" value=""> -->

		<div id="modal01" class="modal01">
			<!-- Modal content -->
			<div class="modal01-content">
				<img class="close01" src="{{ asset('website/receipt_upload/button_popup_close.png') }}?v=1" alt="close"/>
				<img class="bear01" src="{{ asset('website/receipt_upload/submit_bear.png') }}?v=1" style="width:60%;" alt="bear01"/>
				<h3>上載收據完成！<h3>
			</div>
		</div>

		<div id="modal02" class="modal02">
			<!-- Modal content -->
			<div class="modal02-content">
				<div class="row">
					<h3 style="text-align:center;">收據紀錄<h3>
				</div>
				<div class="row mode02-info" >
					<div class="col-sm-6 box" id="modal02-receipt">
					</div>
					<div class="col-sm-6 box">
						<div clas="row" style="display:flex;"><div class="col-md-4 modal02-title">提交日期：</div><div id="modal02-date" class="col-md-8 "></div></div>
						<div clas="row" style="display:flex;"><div class="col-md-4 modal02-title">批核狀態：</div><div id="modal02-status" class="col-md-8"></div></div>
						<div clas="row"><div class="col-md-4 modal02-title">相關優惠：</div><div id="modal02-offer" class="col-md-10"></div></div>
					</div>
				</div>
				<img class="close02" src="{{ asset('website/receipt_upload/button_popup_close.png') }}?v=2" alt="close02"/>
			</div>
		</div>

		<div id="modal03" class="modal03">
			<!-- Modal content -->
			<div class="modal03-content">
				<img class="close03" src="{{ asset('website/receipt_upload/button_popup_close.png') }}?v=2" alt="close03"/>
				<div class="row">
					<h3>收據號碼教學圖</h3>
				</div>
				<div class="row align-items-end">
					<div class="col">
						<img src="{{ asset('website/receipt_upload/btn_left.png') }}?v=2"  onclick="scroll_hor(1)"/>
					</div>
					<div class="col-8">
						<div class="horizontal_scroll">
							<div class="cate_container"></div>
						</div>
					</div>
					<div class="col">
						<img src="{{ asset('website/receipt_upload/btn_right.png') }}?v=2"  onclick="scroll_hor(-1)"/>
					</div>
				</div>
			</div>
		</div>

@if (empty($offerInfo))
		<div id="modal04" class="modal04">
			<!-- Modal content -->
			<div class="modal04-content">
				<img class="close04" src="{{ asset('website/receipt_upload/button_popup_close.png') }}?v=1" alt="close"/>
				<img class="bear02" src="{{ asset('website/receipt_upload/backhome_bear.svg') }}?v=1" style="width:60%;" alt="bear01"/>
				<h3>你未參加任上載收據活動呀！<br>快啲去拎優惠先啦﹗<h3>
				<div id="backHome" class="backHome"><span>回到主頁</span></div>
			</div>
		</div>
@endif

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
		<script type="text/javascript" src="{{ asset('js/common.js') }}?v=1"></script>
		<script src="{{asset('assets/vendor/adminkit/plugins/dropify/dropify.min.js')}}"></script>
		<link rel="stylesheet" href="{{asset('assets/vendor/adminkit/plugins/dropify/dropify.min.css')}}" />
	
		<script>
			// when selecting offer, the option of channel and the receipt sample would be changed
			var _offerStart = "" ;

			$("#offer").on('change', function() {

				var selectValue = $(this).val();
				var tempStart = $(this).find('option:selected').attr("data-start");
				_offerStart = new Date(tempStart);

				$.ajax({
					method: 'GET',
					url: '/receipt-record/' + selectValue,
					success: function(response){

						var num = (response.data).length;
						var channelInfo = JSON.stringify(response.data);
						obj = JSON.parse(channelInfo);
						
						if (num > 0){

							$("#channel").empty();
							$("#channel").append(`<option value="" disabled selected>請選擇</option>`);
							for (var i=0; i <num; i++){
								$("#channel").append("<option value='" + obj[i].sample_id + "'>" + obj[i].channel + "</option>")
							}

							$(".cate_container").empty();
							for (var k=0; k<num ;k++){
								$(".cate_container").append(`<div class="cate"><img src="`+obj[k].url+`"></div>`);
							}
						}
					}		
				});	
			});

			// 重設：reset all field
			$("#reset").on("click", function(){
				document.getElementById("form").reset();
				clearDropifyView();
			});

			// when click "upload" tag, show upload form
			$("#upload01, #upload02").click(function(){
				$("#upload02, #record01").show();
				$("#upload01, #record02").hide();
				$(".tab-1").show();
				$(".tab-2").hide();
			});

			// when click "record" tag, show record table
			$("#record01,#record02").click(function(){
				$("#upload01, #record02").show();
				$("#upload02, #record01").hide();
				$(".tab-2").show();
				$(".tab-1").hide();
				updateTab2();
			});

			// record table update
			function updateTab2(){

				$.ajax({
					method: 'GET',
					url: '{{ route("website.receiptreocrd.list", ["id"=>$id]) }}',
					success: function (result)  {

						var items = (result.data).length;
						var recordInfo = JSON.stringify(result.data);
						obj2 = JSON.parse(recordInfo);
						var rejectShow = `拒絕`;
						var pendingShow = `有待批核`;

						$("#list-content-page").empty();
						for (var i=0; i <items; i++){

							var statusNow = obj2[i].status;
							var statusShow = null;

							if (statusNow == "approved"){ statusShow = `<span class="fimmick_hightlightedRow">成功</sapn>`;
							}else if(statusNow == "rejected"){statusShow =`<span class="fimmick_warningRow">`+ rejectShow +` - `+obj2[i].reject_reason['zh-HK'] +`</sapn>`;
							}else{statusShow=`<span class="fimmick_warningRow">`+pendingShow+`</sapn>`;}

							$("#list-content-page").append(`<div class="col-7 table_content" onclick='getModalDisplay("`+obj2[i].id +`")'>`+
																`<div class="row justify-content-start" style="width:100%"><div class="col-6">`+obj2[i].created_at.substr(0,10)+`</div><div class="col-6 text-end" style="padding-left:0px;padding-right:0px;">`+ statusShow+`</div><div class="col-12">`+
																obj2[i].campaign_offer.offer_title +`</div></div></div><div class="col-2 table_content_right" onclick='getModalDisplay("`+obj2[i].id +`")'>`+ `<img src="{{ asset('storage/`+ obj2[i].receipt_path + `')}}" style="height:40px"></div>`);
						}
					},
					error: function (XMLHttpRequest, textStatus, errorThrown)  {
						alert("Oops...\n#"+textStatus+": "+errorThrown);
					}
				});
			}

			// reset the dropify
			function clearDropifyView(){
				var drEvent = $('.dropify').dropify();
				drEvent = drEvent.data('dropify');
				drEvent.resetPreview();
				drEvent.clearElement();
				drEvent.destroy();
				drEvent.init();
			}

			$('.dropify').dropify({
				messages: {
					'default': '',
      				'replace': '',
					'remove': '',
					'error': '',
				},
				error: {
					'fileSize': '上載檔案過大（需於5M以下)',
					'fileExtension': '上載檔案不符合要求，請重新上載。',
				},
				tpl: {
					filename: '<p class="dropify-filename"></p>',
				}
			});

			$('.dropify').on('change', function (event)  {
				var fileObject = $(event.target).prop('files');

				if (fileObject[0] != undefined) {

					var file = fileObject[0];

					var formdata = new FormData();
					formdata.append('_token', '{{ csrf_token() }}');
					formdata.append('filename', $(event.target).attr('name'));
					formdata.append('file', file);
					
					$.ajax({
						method: 'POST',
						url: '{{ route("website.receiptupload.api") }}',
						data: formdata,
						dataType: 'json',
						processData: false,
						contentType: false,
						success: function(data, textStatus, jqXHR) {
	
							if (data.status >0) {
								// alert(data.message);
								// pass the temp filename to frontend
								document.getElementById("path").value = data.path;

							} else {
								alert('Failed to upload, please try again.');
							}
						}
					});
				}
			});

			var modal01 = document.getElementById("modal01");
			var span01 = document.getElementsByClassName("close01")[0];
			var modal02 = document.getElementById("modal02");
			var span02 = document.getElementsByClassName("close02")[0];
			var modal03 = document.getElementById("modal03");
			var span03 = document.getElementsByClassName("close03")[0];

			modal01.style.display = "none";
			modal02.style.display = "none";
			modal03.style.display = "none";

			// modal 4 for no offer user
			if(document.getElementById("modal04") != null){
				var modal04 = document.getElementById("modal04");
				var span04 = document.getElementsByClassName("close04")[0];
				var goHome = document.getElementsByClassName("backHome")[0];
				modal04.style.display = "block";
			}

			$(document).ready(function()  {

				window.onbeforeunload = function(e)  {showLoading();};

				span01.onclick = function() {
					modal01.style.display = "none";					
				}
				span02.onclick = function() {
					modal02.style.display = "none";					
				}
				span03.onclick = function() {
					modal03.style.display = "none";
					document.querySelector(".dropify").removeAttribute("disabled");  // disable input
				}

				if(document.getElementById("modal04") != null){
					span04.onclick = function() {location.href = "{{ route('campaign.offer.listing.html') }}";}
					goHome.onclick = function() {location.href = "{{ route('campaign.offer.listing.html') }}";}
				}

				// the receipt issue date cannot greater today
				$.validator.addMethod(
					"noAfterToday",
					function(value, element) {
						var currentDate = new Date();
						var selectedDate = new Date(value);
						return (currentDate >= selectedDate);
					},
					"You cannot select a date greater than today."
				);

				$.validator.addMethod(
					"earilerThanValid",
					function(value, element) {
						var selectedDate = new Date(value);
						return (_offerStart <= selectedDate);
					},
					"You cannot select a date earlier than start day."
				);

				$("#submit").click(function()  {
					var basicRule = {
						rules:  {
							receipt_image: "required",
							offer: "required",
							channel: "required",
							issueDate:{
								required: true,
								date: true,
								noAfterToday: true,
								earilerThanValid: true,
							},
							amount:{
								required: true,
								number: true,
								min: 0.1,
							},
							receiptNumber: "required",
						},
						messages:{
							receipt_image: "未有上載檔案",
							offer: "請選擇優惠",
							channel: "請選擇商戶",
							issueDate: {required:"請填選購買日期", date:"請填選購買日期", noAfterToday:"請填選購買有效日期", earilerThanValid: "非於有效日期購買" },
							amount: {required:"請輸入消費金額", number:"請輸入消費金額", min:"請輸入正確消費金額" },
							receiptNumber: "請輸入收據號碼",
						}
					};

					var form = $("#form");
					form.validate(basicRule);
					result = form.valid();

					if (result == false)  {return;}

					var disabled = $("#form").find(":input:disabled").removeAttr("disabled");
					var formData = $("#form").serialize();
					disabled.attr("disabled", "disabled");

					$.ajax({
						type: "POST",
						data: formData,
						dataType : "json",
						url: '{{ route("website.savereceipt.api", ["id"=>$id]) }}',
						success: function (result)  {

							modal01.style.display = "block";
							document.getElementById("form").reset()
			
							$("#upload01, #record02").show();
							$("#upload02, #record01").hide();
							$(".tab-2").show();
							$(".tab-1").hide();
							clearDropifyView();
							updateTab2();
						},
						error: function (XMLHttpRequest, textStatus, errorThrown)  {
							alert("Oops...\n#"+textStatus+": "+errorThrown);
						}
					});
				});
			});

			// modal 3 -- when receipt sample popup, the model ready and the dropify disfunction the input 
			function getSample(){

				var dropArea = document.querySelector(".dropify")
				dropArea.setAttribute("disabled", "disabled");  // disable input

				modal03.style.display = "block";
				
				const hScroll = document.querySelector(".horizontal_scroll");
				var cates = document.querySelectorAll(".cate");
				var images = document.querySelectorAll(".cate img");

				for (var j=0; j<images.length ;j++){
					cates[j].style.width = hScroll.offsetWidth +"px";
					images[j].style.width = (hScroll.offsetWidth-20) +"px";
				}
					
			}

			// modal 2 -- show the detail of record
			function getModalDisplay(receiptID){

				$.ajax({
					method: 'GET',
					url: '/record-display/' + receiptID,

					success: function (result)  {

						modal02.style.display = "block";
						
						var recordInfo = JSON.stringify(result.data);
						obj3 = JSON.parse(recordInfo);

						var rejectShow = `拒絕`;
						var pendingShow = `有待批核`;

						var statusNow = obj3.status;
						var statusShow = null;

						if (statusNow == "approved"){ statusShow = `<span class="fimmick_hightlightedRow">成功批核</span>`;
						}else if(statusNow == "rejected"){statusShow =`<span class="fimmick_warningRow">`+ rejectShow +` - `+ obj3.reject_reason['zh-HK'] +`</span>`;
						}else{statusShow=`<span class="fimmick_warningRow">`+pendingShow+`</span>`;}

						document.getElementById("modal02-receipt").innerHTML = `<img src="{{ asset('storage/`+ obj3.receipt_path + `')}}" style="width:95%">`;
						document.getElementById("modal02-date").innerHTML = obj3.created_at.substr(0,10);
						document.getElementById("modal02-status").innerHTML = statusShow;
						document.getElementById("modal02-offer").innerHTML = obj3.campaign_offer.offer_title;
					
					},
					error: function (XMLHttpRequest, textStatus, errorThrown)  {
						alert("Oops...\n#"+textStatus+": "+errorThrown);
					}
				});
			};
		</script>

		<script>

			// for receipt sample scrolling
			let current_position_num = 0;

			function scroll_hor(val){

				const sCont = document.querySelector(".cate_container");
				const hScroll = document.querySelector(".horizontal_scroll");
				let scrollAmount = hScroll.offsetWidth + 20; /* one cate width with padding */
				let maxScroll = -sCont.offsetWidth + hScroll.offsetWidth;

				current_position_num += (val * scrollAmount);

				if (current_position_num >= 0){
					current_position_num = 0;
				}
				
				if (current_position_num <= maxScroll){
					current_position_num = maxScroll;
				}

				sCont.style.left = current_position_num + "px";
			}

		</script>
	</body>
</html>


