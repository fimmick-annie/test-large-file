@extends('foso.layouts.default')

@section('page_title', 'Receipt upload #'.$id.' Settings')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.receipthandle.html") }}'> Receipt Upload</a></li>
<li><i class='fa fa-angle-right'></i> Confirmation</li>
@endsection

@section('content')
<style>


fieldset{
    display:flex;
    flex-direction: row;
    justify-content: center;
    align-items: center
}

fieldset label{
	min-width: 195px;
    padding-left: 4%;
}
fieldset input{
	min-width: 300px;
}

* {box-sizing: border-box;}

.img-zoom-container {
	position: relative;
	display: flex;
	justify-content: flex-start;
}

.img-zoom-lens {
	position: absolute;
	border: 1px solid #d4d4d4;
	/*set the size of the lens:*/
	width: 40px;
	height: 40px;
}

.img-zoom-result {
	border: 1px solid #d4d4d4;
	/*set the size of the result div:*/
	width: 280px;
	height: 280px;
	padding:5%;
}

#reasonArea{
	visibility:hidden;
}
</style>


<form id="form" name="form">
	@csrf

	<div class="card">
		<div class="card-body">

			<div class="row">
				<div class="col-sm-12" style="height:50px;"><h4>Receipt upload Confirmation Page</h4></div>
			</div>


            <div class="row">
                <!-- display the inforamtion of the record -->
                <div class="col-sm-5">

@if (!empty($receipt->status) && $receipt->status != 'checked')
					<div class="row">
						<fieldset class="form-group">
							<label for="status">Receipt Status</label>
@if ($receipt->status == 'approved')
							<span class="form-control badge badge-success" type="text" id="status" name="status" style="line-height: 2;" >Approved</sapn>
@else
                    		<span class="form-control badge badge-secondary" type="text" id="status" name="status" style="line-height: 2;" >Rejected</span>
@endif
						</fieldset>
					</div>
@endif

                    <div class="row">
                        <fieldset class="form-group">
                            <label for="id">Receipt upload ID</label>
                            <input class="form-control" type="text" value="{{ empty($id)?'':$id }}" id="id" name="id" disabled>
                        </fieldset>
                    </div>

                    <div class="row">
                        <fieldset class="form-group">
                            <label for="createDate">Create Date</label>
                            <input class="form-control" type="text" value="{{ empty($createDate)?'':$createDate}}" id="createDate" name="createDate" disabled>
                        </fieldset>
                    </div>

                    <div class="row">
                        <fieldset class="form-group">
                            <label for="updateDate">Submission Date</label>
                            <input class="form-control" type="text" value="{{ empty($updateDate)?'':$updateDate }}" id="updateDate" name="updateDate" disabled>
                        </fieldset>
                    </div>

                    <div class="row">
                        <fieldset class="form-group">
                            <label for="updateTime">Submission Time</label>
                            <input class="form-control" type="text" value="{{ empty($updateTime)?'': $updateTime }}" id="updateTime" name="updateTime" disabled>
                        </fieldset>
                    </div>

                    <div class="row">
                        <fieldset class="form-group">
                            <label for="purchaseAt">Purchase Date</label>
                            <input class="form-control" type="text" value="{{ empty($receipt->purchase_date)?'':$receipt->purchase_date }}" id="purchaseAt" name="purchaseAt" disabled>
                        </fieldset>
                    </div>

                    <div class="row">
                        <fieldset class="form-group">
                            <label for="mobile">Member Mobile</label>
                            <input class="form-control" type="text" value="{{ empty($receipt->member->mobile)?'':$receipt->member->mobile }}" id="mobile" name="mobile" disabled>
                        </fieldset>
                    </div>

                    <div class="row">
                        <fieldset class="form-group">
                            <label for="offer">Offer</label>
                            <input class="form-control" type="text" value="{{ empty($receipt->campaignOffer->offer_title)?'':$receipt->campaignOffer->offer_title}}" id="offer" name="offer" disabled>
                        </fieldset>
                     </div>

                    <div class="row">
                        <fieldset class="form-group">
                            <label for="channel">Merchant selected</label>
                            <input class="form-control" type="text" value="{{ empty($receipt->channelReceiptSample->channel)?'': $receipt->channelReceiptSample->channel}}" id="channel" name="channel" disabled>
                        </fieldset>
                     </div>

                    <div class="row">
                        <fieldset class="form-group">
                            <label for="invoice">Receipt Number</label>
                            <input class="form-control" type="text" value="{{ empty($receipt->invoice_number)?'':$receipt->invoice_number}}" id="invoice" name="invoice" disabled>
                        </fieldset>
                    </div>

                    <div class="row">
                        <fieldset class="form-group">
                            <label for="amount">Purchase Amount</label>
                            <input class="form-control" type="text" value="{{ empty($receipt->purchase_amount)?'$':'$ '.$receipt->purchase_amount }}" id="amount" name="amount" disabled>
                        </fieldset>
                    </div>
@if ( !empty($receipt->status) && $receipt->status != "rejected")	
                    <div class="row" style="padding-bottom:5%;">   
                        <fieldset class="form-group">
                            <label for="approvePoint">Approval point</label>
                            <input class="form-control" type="text" value="{{ empty($receipt->approve_point)?' ':$receipt->approve_point }}" id="approvePoint" name="approvePoint" disabled>
                        </fieldset>
                    </div>
@endif
@if ( !empty($receipt->status) && $receipt->status == "rejected")	
                    <div class="row" style="padding-bottom:5%;">   
                        <fieldset class="form-group">
                            <label for="rejectReason">Rejected reason</label>
                            <input class="form-control" type="text" value="{{ empty($reason)?' ':$reason }}" id="rejectReason" name="rejectReason" disabled>
                        </fieldset>
                    </div>
@endif

                    <div class="row">
                        <fieldset class="form-group">
@if ( !empty($receipt->status) && $receipt->status == "checked")					
                            <div id="handleGroup" class="col-sm-6" style="display:flex;">
                                <button id="approveBtn" value="approve" type="button" class="btn btn-outline-success" style="width:180px; margin-right:10px;" >Approve</button>
                                <button id="rejectBtn" value="reject" type="button" class="btn btn-outline-danger" style="width:180px;">Reject</button>
                            </div>
@endif
@if ( !empty($receipt->status) && $receipt->status != "approved")		
							<div class="col-sm-6" id="reasonArea" style="margin-top:40px;">
								<select class="form-control" id="reason" name="reason" {{ ($receipt->status!="checked")?  "disabled": ""}}>
									<option value="" {{ empty($receipt->reject_reason)?"selected":"" }}>Please select "reject reason"...</option> 
									<option value="unqualified_shopping" {{ (!empty($receipt->reject_reason)&&($receipt->reject_reason=="unqualified_shopping"))?"selected":"" }}>不合資格消費</option> 
									<option value="expired" {{ (!empty($receipt->reject_reason)&&($receipt->reject_reason=="expired"))?"selected":"" }}>過期</option> 
									<option value="repeat" {{ (!empty($receipt->reject_reason)&&($receipt->reject_reason=="repeat"))?"selected":"" }}>重複上載</option>
									<option value="image_problem" {{ (!empty($receipt->reject_reason)&&($receipt->reject_reason=="image_problem"))?"selected":"" }}>圖片模糊</option> 
									<option value="unqualified" {{ (!empty($receipt->reject_reason)&&($receipt->reject_reason=="unqualified"))?"selected":"" }}>不符合資格</option> 
								</select>
							</div>
@endif
                        </fieldset>
                    </div>
                </div>

                <!-- preview receipt sample with zoom in and zoom out -->
                <div class="col-sm-7">
					<label for="receiptImage" rows=15>Receipt Image</label><br>
					<fieldset class="form-group img-zoom-container">
						<img id="receiptImage" src="{{asset('storage/'.$receipt->receipt_path)}}">
						<div id="myresult" class="img-zoom-result"></div>
					</fieldset>
					<a href="{{asset('storage/'.$receipt->receipt_path)}}" >{{asset('storage/'.$receipt->receipt_path)}}</a>
                </div>

            </div>      
               
			<style>
				#receiptImage{
					max-width:450px;
					max-height:500px;
					width: auto !important;
					height: auto !important;
				}
				.btn{
					width:100%;
					margin-top:40px;
				}
			</style>
@if ( !empty($receipt->status) && $receipt->status == "checked")
            <div class="row">

				<div class="col-sm-4">
                    <button type="button" value="sumbit" class="text-white btn btn-warning" id="submitButton">Submit</button>
					<small>Select approve or reject, then long press "Submit" to finish action</small>
                </div>

				<div class="col"></div>

				<div class="col-sm-4">
                    <button type="button" value="edit" class="btn btn-primary" id="editButton">Back to edit</button>
					<small>Back to the setting page to update information</small>
                </div>

			</div>
@else
			<div class="row">
				<fieldset class="form-group">
					<label for="handle">Handler</label>
					<input class="form-control" type="text" value="{{ empty($receipt->handler)?'':$receipt->handler }}" id="handle" name="handle" disabled>
				</fieldset>
			</div>
			<div class="row">
				<fieldset class="form-group">
					<label for="handleAt">Handle At</label>
					<input class="form-control" type="text" value="{{ empty($receipt->handle_date)?'':$receipt->handle_date }}" id="handleAt" name="handleAt" disabled>
				</fieldset>
			</div>
@endif
        </div>
    </div>
</form>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{ asset('js/ckeditor.js') }}?v=1"></script>
<script type="text/javascript" src="{{ asset('js/common.js') }}?v=1"></script>
<script src="/js/animatedBtn.js"></script>

<script>

	$("#approveBtn").click(function() {
		$("#approveBtn").addClass("active");
		$("#rejectBtn").removeClass("active");
		document.getElementById("reasonArea").style.visibility = "hidden";
	});

	$("#rejectBtn").click(function() {
		$("#rejectBtn").addClass("active");
		$("#approveBtn").removeClass("active");
		document.getElementById("reasonArea").style.visibility = "visible";
	});

	$(document).ready(function()  {

		window.onbeforeunload = function(e)  {showLoading();};

		imageZoom("receiptImage", "myresult");

		$("#editButton").click(function()  {

			var form = $("#form");
			var disabled = $("#form").find(":input:disabled").removeAttr("disabled");
			var formData = form.serialize();
			disabled.attr("disabled", "disabled");

			showLoading();

			$.ajax({
				type: "POST",
				data: formData,
				dataType: "json",
				url: '{{ route("foso.receipthandle.settings.reedit.json", ["id"=>$id])}}',
				success: function (result)  {
					alert(result.message);
					if (result.status != 0)  {
						hideLoading();
						return;
					}
					//back to redemption page to see the updated redemption record
					location.href = '{{ route("foso.receipthandle.settings.html", ["id"=>$id])}}';
				},
				error: function (XMLHttpRequest, textStatus, errorThrown)  {
					hideLoading();
					alert("Oooops...\n#"+textStatus+": "+errorThrown);
				}
			});

		});


		const handleSubmtButton = () => {
			
			showLoading();
			const finalStatus = document.getElementById("handleGroup").getElementsByClassName("active")[0].value;
			const reasonReject = document.getElementById("reason").value;
			const api = '{{ route("foso.receipthandle.settings.final.json", ["id"=>$id]) }}';
			fetch(api, {
				method: 'post',
				headers: {'Content-Type': 'application/json'},
				body: JSON.stringify({
                    id: '{{$id}}',
					'finalStatus':finalStatus,
					'reason':reasonReject,
				})
			})
			.then(response=>response.json())
			.then(json => {

				if (json.status < 0)  {
					alert(json.message);
					hideLoading();
					return;
				}
				//  Reload if success
				location.href = '{{ route("foso.receipthandle.settings.confirm.html", ["id"=>$id]) }}';
			});

		};

		const submit = AnimatedBtn({
			domId: "submitButton",
			transitionTime: 2000,
			yourFunction: handleSubmtButton,
			coverColor: 'rgba(183, 187, 24, 0.5)',
			baseColor: 'rgba(234, 184, 55, 1)',
		});
	});

</script>

<script>

function imageZoom(imgID, resultID) {

	var img, lens, result, cx, cy;
	img = document.getElementById(imgID);
	result = document.getElementById(resultID);
	lens = document.createElement("DIV");
	lens.setAttribute("class", "img-zoom-lens");
	img.parentElement.insertBefore(lens, img);
	cx = result.offsetWidth / lens.offsetWidth;
	cy = result.offsetHeight / lens.offsetHeight;
	result.style.backgroundImage = "url('" + img.src + "')";
	result.style.backgroundSize = (img.width * cx) + "px " + (img.height * cy) + "px";
	lens.addEventListener("mousemove", moveLens);
	img.addEventListener("mousemove", moveLens);
	lens.addEventListener("touchmove", moveLens);
	img.addEventListener("touchmove", moveLens);

	function moveLens(e) {
		var pos, x, y;
		e.preventDefault();
		pos = getCursorPos(e);
		x = pos.x - (lens.offsetWidth / 2);
		y = pos.y - (lens.offsetHeight / 2);
		if (x > img.width - lens.offsetWidth) {x = img.width - lens.offsetWidth;}
		if (x < 0) {x = 0;}
		if (y > img.height - lens.offsetHeight) {y = img.height - lens.offsetHeight;}
		if (y < 0) {y = 0;}
		lens.style.left = x + "px";
		lens.style.top = y + "px";
		result.style.backgroundPosition = "-" + (x * cx) + "px -" + (y * cy) + "px";
	}

	function getCursorPos(e) {
		var a, x = 0, y = 0;
		e = e || window.event;
		a = img.getBoundingClientRect();
		x = e.pageX - a.left;
		y = e.pageY - a.top;
		x = x - window.pageXOffset;
		y = y - window.pageYOffset;
		return {x : x, y : y};
	}
}

</script>

@endsection
