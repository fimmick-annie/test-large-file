@extends('foso.layouts.default')

@section('page_title', 'Receipt upload #'.$id.' Settings')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.receipthandle.html") }}'> Receipt Upload</a></li>
<li><i class='fa fa-angle-right'></i> Settings</li>
@endsection

@section('content')
<style>
* {box-sizing: border-box;}

.img-zoom-container {
	position: relative;
	display:flex;
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
	width: 300px;
	height: 300px;
	padding:5%;
}
.input-symbol-money {
    position: relative;
}
.input-symbol-money input {
    padding-left:18px;
}
.input-symbol-money:before {
    position: absolute;
    top: 36px;
    content:" $";
    left: 7px;
}


</style>

<form id="form" name="form">
	@csrf

	<div class="card" style="margin-bottom:20px;">
		<div class="card-body">

			<div class="row">
				<div class="col-sm-12"><h4>Receipt upload Settings</h4></div>
			</div>

{{--           <div class="row">
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="status">Receipt Status</label>
@if (!empty($receipt->status) && $receipt->status == 'approved')
						<p class="form-control badge badge-secondary" type="text" value="{{ empty($receipt->status)?'':$receipt->status }}" id="status" name="status" style="line-height: 2;" disabled>Approved</p>
@elseif ( !empty($receipt->status) && $receipt->status == 'rejected')
                        <p class="form-control badge badge-secondary" type="text" value="{{ empty($receipt->status)?'':$receipt->status }}" id="status" name="status" style="line-height: 2;"disabled>Rejected</p>
@else
                        <p class="form-control badge badge-success" type="text" value="{{ empty($receipt->status)?'':$receipt->status }}" id="status" name="status" style="line-height: 2;"disabled>{{ empty($receipt->status)?'':$receipt->status }}</p>
@endif
					</fieldset>
				</div>
			</div>--}}

			<div class="row">
				<div class="col-lg-3">
					<fieldset class="form-group">
						<label for="createdAt">Create Time</label>
						<input class="form-control" type="text" value="{{ empty($receipt->created_at)?' ':$receipt->created_at }}" id="createdAt" name="createdAt" disabled>
					</fieldset>
				</div>
				<div class="col-lg-3">
					<fieldset class="form-group">
						<label for="updatedAt">Update Time</label>
						<input class="form-control" type="text" value="{{ empty($receipt->updated_at)?' ':$receipt->updated_at }}" id="updatedAt" name="updatedAt" disabled>
					</fieldset>
				</div>
                <div class="col"></div>
                {{-- <div class="col-lg-2">
                    <fieldset class="form-group">
						<label for="id">Receipt upload ID</label>
						<input class="form-control" type="text" value="{{ empty($receipt->id)?'':$receipt->id }}" id="id" name="id" disabled>
					</fieldset>
                </div> --}}
			</div>
			<div class="row">
				<div class="col-sm-12"><h4>Offer Details</h4></div>
			</div>
			<div class="row">
                
				<div class="col-lg-2">
					<fieldset class="form-group">
						<label for="offerID">Offer ID</label>
						<input class="form-control" type="text" value="{{ empty($receipt->offer_id)?' ':$receipt->offer_id  }}" id="offerID" name="offerID" maxlength="32" disabled>
					</fieldset>
				</div>
				<div class="col-lg-5">
					<fieldset class="form-group">
						<label for="offerTilte">Offer Title</label>
						<input class="form-control" type="text" value="{{ empty($receipt->campaignOffer->offer_title)?'':$receipt->campaignOffer->offer_title  }}" id="offerTitle" name="offerTitle" maxlength="32" disabled>
					</fieldset>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12"><h4>Member Detail</h4></div>
			</div>
            <div class="row">
				<div class="col-lg-2">
					<fieldset class="form-group">
						<label for="memberID">Member ID</label>
						<input class="form-control" type="text" value="{{ empty($receipt->member_id)?' ':$receipt->member_id }}" id="memberID" name="memberID" disabled>
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<label for="mobile">Member mobile</label>
						<input class="form-control" type="text" value="{{ empty($receipt->member->mobile)?' ':$receipt->member->mobile }}" id="mobile" name="mobile" disabled>
					</fieldset>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12"><h4>Purchase Detail</h4></div>
			</div>
            <div class="row">
				<div class="col-lg-2">
					<fieldset class="form-group">
						<label for="purchaseAt">Purchase Date</label>
						<input class="form-control" type="date" value="{{ empty($receipt->purchase_date)?' ':$receipt->purchase_date }}" id="purchaseAt" name="purchaseAt" {{ ($receipt->status!="pending")? "disabled":"required" }}>
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group input-symbol-money">
						<label for="amount">Purchase Amount</label>
						<input class="form-control" type="text" value="{{ empty($receipt->purchase_amount)?' ':$receipt->purchase_amount }}" id="amount" name="amount" {{ ($receipt->status!="pending")? "disabled":"required" }}>
					</fieldset>
				</div>
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="receiptNumber">Receipt Number</label>
						<input class="form-control" type="text" value="{{ empty($receipt->invoice_number)?' ':$receipt->invoice_number }}" id="receiptNumber" name="receiptNumber" {{ ($receipt->status!="pending" )? "disabled":"required" }}>
					</fieldset>
				</div>
			</div>


			<div class="row">
				<div class="col-sm-2">
                    <fieldset class="form-group">
                        <label for="approvePoint">Approvel Point</label>
                        <input class="form-control" type="text" value="{{ empty($approvePoint)? '10' :$approvePoint }}" id="approvePoint" name="approvePoint" {{ ($receipt->status!="pending") && ($receipt->status!="checked")? "disabled": ""}}>
                    </fieldset>
                </div>
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="channel">Merchant selected</label>
						<select class="form-control" id="channel" name="channel">
@if ( isset($channelDetail) && count($channelDetail)>0 )
@foreach ($channelDetail as $ch )
							<option value="{{ $ch['id'] ?? ''}}" {{ (!empty($receipt->channelReceiptSample->channel) && ($receipt->channelReceiptSample->id==$ch['id']))? "selected":"" }} > {{$ch['title'] ?? ''}}</option> 
@endforeach
@endif
						</select>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-8">
					<label for="receiptImage" rows=15>Receipt Image</label><br>
					<fieldset class="form-group img-zoom-container" style="margin-left:10%;">
						<img id="receiptImage" src="{{asset('storage/'.$receipt->receipt_path)}}" >
						<div id="myresult" class="img-zoom-result"></div>
					</fieldset>
					<a href="{{asset('storage/'.$receipt->receipt_path)}}" >{{asset('storage/'.$receipt->receipt_path)}}</a>
				</div>
			</div>
            

			<style>
				#receiptImage{
					max-width:500px;
					max-height:350px;
					width: auto !important;
					height: auto !important;
				}
				.btn{
					width:100%;
					margin-top:40px;
				}
			</style>

            <div class="row">
{{-- @if ( !empty($receipt->status) && $receipt->status == "pending") --}}
				<div class="col-sm-4">
                    <button type="button" value="save" class="text-white btn btn-warning" id="saveButton">Save</button>
					<small>Save the update only</small>
                </div>
				<div class="col"></div>
{{-- @elseif ( !empty($receipt->status) && $receipt->status == "checked") 
                <div class="col-sm-4">
                    <button type="button" value="approved" class="btn btn-success" id="approvedButton">Approve</button>
                </div>
				<div class="col"></div>
                <div class="col-sm-4">
                    <button type="button" value="rejected" class="btn btn-danger" id="rejectedButton">Reject</button>
                </div>
			</div>
			
			<div class="row"> --}}
				<div class="col-sm-4">
					<button type="button" value="gohandle" class="text-white btn btn-success" id="handleButton">Save to handle</button>
					<small>Go to approve or reject</small>
				</div>
{{-- @endif --}}
            </div>
        </div>
    </div>
</form>


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{ asset('js/ckeditor.js') }}?v=1"></script>
<script type="text/javascript" src="{{ asset('js/common.js') }}?v=1"></script>
<script src="/js/animatedBtn.js"></script>

<script>

	$(document).ready(function()  {

		window.onbeforeunload = function(e)  {showLoading();};

		imageZoom("receiptImage", "myresult");

		// the receipt issue date cannot smaller then the created date 
		$.validator.addMethod(
			"noAfterIssueDate",
			function(value, element) {
				var issueDate = new Date($("#createdAt").val());
				var selectedDate = new Date(value);
				return (selectedDate <= issueDate);
			},
			"You cannot select a date after issed day."
		);

		$("#saveButton").click(function()  {

			var basicRule = {
				rules:  {
					purchaseAt: {
						required: true,
						noAfterIssueDate: true,
					},
					receiptNumber: "required",
					approvePoint:{
						required: true,
						number: true,
						min:0,
					},
					amount:{
						required: true,
						number: true,
						min: 0.1,
					},
				},
				messages:{
					purchaseAt: {required:"Please enter the purchase date",noAfterIssueDate:"Please enter a valid purchase date"},
					receiptNumber: "Please enter receipt number",
					approvePoint: {required:"Not allow empty", number:"Only accpet number", min:"Negative amount is not accepted" },
					amount: {required:"Not allow empty", number:"Only accpet number", min:"Negative amount is not accepted" },
				}
			};

			var form = $("#form");
			form.validate(basicRule);
			result = form.valid();
			if (result == false)  {return;}

			var disabled = $("#form").find(":input:disabled").removeAttr("disabled");
			var formData = form.serialize();
			disabled.attr("disabled", "disabled");

			showLoading();

			$.ajax({
				type: "POST",
				data: formData,
				dataType: "json",
				url: '{{ route("foso.receipthandle.settings.save.json", ["id"=>$id])}}',
				success: function (result)  {
					alert(result.message);
					if (result.status != 0)  {
						hideLoading();
						return;
					}
					//back to setting page to see the updated record
					location.href = '{{ route("foso.receipthandle.settings.html", ["id"=>$id]) }}';

				},
				error: function (XMLHttpRequest, textStatus, errorThrown)  {
					hideLoading();
					alert("Oooops...\n#"+textStatus+": "+errorThrown);
				}
			});
		});

		$("#handleButton").click(function()  {

			var basicRule = {
				rules:  {
					purchaseAt: {
						required: true,
						noAfterIssueDate: true,
					},
					receiptNumber: "required",
					approvePoint:{
						required: true,
						number: true,
					},
					amount:{
						required: true,
						number: true,
						min: 0.1,
					},
				},
				messages:{
					purchaseAt: {required:"Please enter the purchase date",noAfterIssueDate:"Please enter a valid purchase date"},
					receiptNumber: "Please enter receipt number",
					approvePoint: "Please enter the number",
					amount: {required:"Not allow empty", number:"Only accpet number", min:"Negative amount is not accepted" },
				}
			};

			var form = $("#form");
			form.validate(basicRule);
			result = form.valid();
			if (result == false)  {return;}

			var disabled = $("#form").find(":input:disabled").removeAttr("disabled");
			var formData = form.serialize();
			disabled.attr("disabled", "disabled");

			showLoading();

			$.ajax({
				type: "POST",
				data: formData,
				dataType: "json",
				url: '{{ route("foso.receipthandle.settings.savetohandle.json", ["id"=>$id])}}',
				success: function (result)  {
					alert(result.message);
					if (result.status < 0)  {
						return;
					}
					location.href = '{{ route("foso.receipthandle.settings.confirm.html", ["id"=>$id] ) }}';
				},
				error: function (XMLHttpRequest, textStatus, errorThrown)  {
					hideLoading();
					alert("Oooops...\n#"+textStatus+": "+errorThrown);
				}
			});

		});

	});
</script>
<script>

function imageZoom(imgID, resultID) {

	var img, lens, result, cx, cy;
	img = document.getElementById(imgID);
	result = document.getElementById(resultID);
	// create lens:
	lens = document.createElement("DIV");
	lens.setAttribute("class", "img-zoom-lens");
	// insert lens:
	img.parentElement.insertBefore(lens, img);
	// calculate the ratio between result DIV and lens:
	cx = result.offsetWidth / lens.offsetWidth;
	cy = result.offsetHeight / lens.offsetHeight;
	// set background properties for the result DIV:
	result.style.backgroundImage = "url('" + img.src + "')";
	result.style.backgroundSize = (img.width * cx) + "px " + (img.height * cy) + "px";
	// execute a function when someone moves the cursor over the image, or the lens:
	lens.addEventListener("mousemove", moveLens);
	img.addEventListener("mousemove", moveLens);
	// and also for touch screens:
	lens.addEventListener("touchmove", moveLens);
	img.addEventListener("touchmove", moveLens);

	function moveLens(e) {
		var pos, x, y;
		// prevent any other actions that may occur when moving over the image:
		e.preventDefault();
		// get the cursor's x and y positions:
		pos = getCursorPos(e);
		// calculate the position of the lens:
		x = pos.x - (lens.offsetWidth / 2);
		y = pos.y - (lens.offsetHeight / 2);
		// prevent the lens from being positioned outside the image:
		if (x > img.width - lens.offsetWidth) {x = img.width - lens.offsetWidth;}
		if (x < 0) {x = 0;}
		if (y > img.height - lens.offsetHeight) {y = img.height - lens.offsetHeight;}
		if (y < 0) {y = 0;}
		// set the position of the lens:
		lens.style.left = x + "px";
		lens.style.top = y + "px";
		// display what the lens "sees":
		result.style.backgroundPosition = "-" + (x * cx) + "px -" + (y * cy) + "px";
	}

	function getCursorPos(e) {
		var a, x = 0, y = 0;
		e = e || window.event;
		// get the x and y positions of the image:
		a = img.getBoundingClientRect();
		// calculate the cursor's x and y coordinates, relative to the image:
		x = e.pageX - a.left;
		y = e.pageY - a.top;
		// consider any page scrolling:
		x = x - window.pageXOffset;
		y = y - window.pageYOffset;
		return {x : x, y : y};
	}
}
</script>

@endsection
