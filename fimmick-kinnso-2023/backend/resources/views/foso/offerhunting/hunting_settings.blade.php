@extends('foso.layouts.default')

@section('page_title', 'Offer Hunting Settings')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.offerhunting.html") }}'> Offer hunting</a></li>
<li><i class='fa fa-angle-right'></i> Settings</li>
@endsection

@section('content')
{{--
<ul class="nav nav-tabs" role="tablist">
	<li class="nav-item"> <a class="nav-link active" href="#" role="tab"><span class="hidden-sm-up"><i class="fa fa-gear"></i></span> <span class="hidden-xs-down">Settings</span></a> </li>
@if (empty($offerCode) || $offerCode == "new")
@else
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.resources.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-file-image-o"></i></span> <span class="hidden-xs-down">Resources</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.rules.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-cubes"></i></span> <span class="hidden-xs-down">Rules</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.coupons.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-tags"></i></span> <span class="hidden-xs-down">Coupons</span></a> </li>
@if ($offer->coupon_type == "randomly-generated")
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.quotas.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-shopping-cart"></i></span> <span class="hidden-xs-down">Quotas</span></a> </li>
@else
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.couponpool.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-shopping-cart"></i></span> <span class="hidden-xs-down">Coupon Pool</span></a> </li>
@endif
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.whatsapp.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-comment"></i></span> <span class="hidden-xs-down">WhatsApp</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.customerjourney.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-plane"></i></span> <span class="hidden-xs-down">Journey</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.ini.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-file-text-o"></i></span> <span class="hidden-xs-down">.ini</span></a> </li>
@endif
</ul>
--}}

<form id="form" name="form">
	@csrf

	<div class="card" style="margin-bottom:20px;">
		<div class="card-body">

			<div class="row">
				<div class="col-sm-12"><h4>Basic Offer Hunting Settings</h4></div>
			</div>

            <div class="row">
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="status">Offer Hunting Status</label>
@if (!empty($offerhunting->status) && $offerhunting->status == 'approved')
						<p class="form-control badge badge-success" type="text" value="{{ empty($offerhunting->status)?'':$offerhunting->status }}" id="status" name="status" style="line-height: 2;" disabled>Approved</p>
@elseif ( !empty($offerhunting->status) && $offerhunting->status == 'rejected')
                        <p class="form-control badge badge-danger" type="text" value="{{ empty($offerhunting->status)?'':$offerhunting->status }}" id="status" name="status" style="line-height: 2;"disabled>Rejected</p>
@else
                        <p class="form-control badge badge-secondary" type="text" value="{{ empty($offerhunting->status)?'':$offerhunting->status }}" id="status" name="status" style="line-height: 2;"disabled>{{ empty($offerhunting->status)?'':$offerhunting->status }}</p>
@endif
					</fieldset>
				</div>
                <div class="col"></div>
                <div class="col-lg-2">
                    <fieldset class="form-group">
						<label for="id">Offer Hunting ID</label>
						<input class="form-control" type="text" value="{{ empty($offerhunting->id)?'':$offerhunting->id }}" id="id" name="id" disabled>
					</fieldset>
                </div>
			
			</div>

			<div class="row">
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="created_at">Create Time</label>
						<input class="form-control" type="text" value="{{ empty($offerhunting->created_at)?' ':$offerhunting->created_at }}" id="created_at" name="created_at" disabled>
					</fieldset>
				</div>
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="updated_at">Update Time</label>
						<input class="form-control" type="text" value="{{ empty($offerhunting->updated_at)?' ':$offerhunting->updated_at }}" id="updated_at" name="updated_at" disabled>
					</fieldset>
				</div>
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="deleted_at">Delete Time</label>
						<input class="form-control" type="text" value="{{ empty($offerhunting->deleted_at)?' ':$offerhunting->deleted_at }}" id="deleted_at" name="deleted_at" disabled>
					</fieldset>
				</div>
			</div>

            <div class="row">
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="created_by">Created By</label>
						<input class="form-control" type="text" value="{{ empty($offerhunting->created_by)?' ':$offerhunting->created_by }}" id="created_by" name="created_by" disabled>
					</fieldset>
				</div>
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="updated_by">Updated By</label>
						<input class="form-control" type="text" value="{{ empty($offerhunting->updated_by)?' ':$offerhunting->updated_by }}" id="updated_by" name="updated_by" disabled>
					</fieldset>
				</div>
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="deleted_by">Deleted By</label>
						<input class="form-control" type="text" value="{{ empty($offerhunting->deleted_by)?' ':$offerhunting->deleted_by }}" id="deleted_by" name="deleted_by" disabled>
					</fieldset>
				</div>
			</div>
            <hr>
			<div class="row">
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="name">Hunter Name</label>
						<input class="form-control" type="text" value="{{ empty($offerhunting->name)?' ':$offerhunting->name  }}" id="name" name="name" maxlength="32" required>
					</fieldset>
				</div>
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="mobile_num">Mobile number</label>
						<input class="form-control" type="text" value="{{ empty($offerhunting->mobile_num)?'':$offerhunting->mobile_num  }}" id="mobile_num" name="mobile_num" maxlength="32" {{ ($offerhunting->status == "approved")?"disabled":"required"}}>
					</fieldset>
				</div>
				<div class="col-lg-2"></div>
                <div class="col-lg-2">
					<fieldset class="form-group">
						<label for="name">Member ID</label>
						<input class="form-control" type="text" value="{{ empty($member_id)? '': $member_id }}" id="member_id" name="member_id" maxlength="32" disabled>
                        <small class="form-text small-text-color">If empty, member id would be created after hunting approved.</small>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-6">
					<fieldset class="form-group">
						<label for="discount_content" rows=10>Discount Content</label>
                        <textarea class="form-control" id="discount_content" name="discount_content" rows=10 disabled>{{ empty($offerhunting->discount_content)?'0': nl2br($offerhunting->discount_content)}}</textarea>
					</fieldset>
				</div>
				<div class="col-lg-6">
					<fieldset class="form-group">
						<label for="media" rows=10>Media</label><br>
<!-- check whether contain this file and the filetype is image or other -->
@if (!file_exists($offerhunting->media) || !exif_imagetype($offerhunting->media)) 
						<img src="{{asset('storage/foso/report-us/noPreview2.png')}}" style="max-height: 150px; margin-left: 20%;">
@else
						<img src="{{asset($offerhunting->media)}}" style="max-height:150px; margin-left: 20%;">
@endif
						<br><br>
						<a href="{{asset($offerhunting->media)}}" >{{asset($offerhunting->media)}}</a>
					</fieldset>
				</div>
				<!-- <div class="col-lg-4" id="outOfQuotaButtonGroup" style="display:none;">
					<fieldset class="form-group">
						<label for="quotaIssued">Quota Action</label>
						<div class="text-white btn btn-danger btn-block" id="outOfQuotaButton">Set "Out of Quota"</div>
						<small class="form-text small-text-color">Set max quota = quota, no revert</small>
						<small class="form-text small-text-color">Please hold until finish</small>
					</fieldset>
				</div>
				<div class="col-lg-4" id="resumeQuotaButtonGroup" style="display:none;">
					<fieldset class="form-group">
						<label for="quotaIssued">Quota Action</label>
						<div class="text-white btn btn-warning btn-block" id="resumeQuotaButton">Resume Quota</div>
						<small class="form-text small-text-color">Reset to max quota</small>
						<small class="form-text small-text-color">Please hold until finish</small>
					</fieldset>
				</div> -->
			</div>
        </div>

    </div>

    <div class="card" style="margin-bottom:20px;">
        <div class="card-body">

            <div class="row">
                <div class="col-sm-12"><h4>Approval section</h4></div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <fieldset class="form-group">
                        <label for="approved_point">Approved Point</label>
                        <input class="form-control" type="text" value="{{ empty($offerhunting->approved_point)?'50':$offerhunting->approved_point }}" id="approved_point" name="approved_point">
                        <small class="form-text small-text-color">Deflaut: 50 points</small>
                    </fieldset>
                </div>
                <div class="col-lg-4">
                    <fieldset class="form-group">
                        <label for="approved_by">Approved By</label>
                        <input class="form-control" type="text" value="{{ empty($offerhunting->approved_by)? '' :$offerhunting->approved_at }}" id="approved_at" name="approved_at" disabled>
                    </fieldset>
                </div>
                <div class="col-lg-4">
                    <fieldset class="form-group">
                        <label for="approved_at">Approved Date and Time</label>
                        <input class="form-control" type="text" value="{{ empty($offerhunting->approved_at)? '':$offerhunting->approved_at }}" id="approved_at" name="approved_at" disabled>
                    </fieldset>
                </div>
            </div>
        
			<style>
				.btn{
					width:100%;
					margin-top:40px;
				}
			</style>

            <div class="row">
@if ( !empty($offerhunting->status) && $offerhunting->status == "approved")
                <div class="col-lg-3">
                    <button type="button" value="approved" class="btn " id="approvedButton" disabled>Approved</button>
                </div>
				<div class="col"></div>
                <div class="col-lg-3">
                    <button type="button" value="rejected" class="btn btn-danger" id="rejectedButton">Reject</button>
                </div>
@elseif ( !empty($offerhunting->status) && $offerhunting->status == "rejected")
                <div class="col-lg-3">
                    <button type="button" value="approved" class="btn btn-success" id="approvedButton" >Approve</button>
                </div>
				<div class="col"></div>
                <div class="col-lg-3">
                    <button type="button" value="rejected" class="btn" id="rejectedButton" disabled>Rejected</button>
                </div>
@else
                <div class="col-lg-3">
                    <button type="button" value="approved" class="btn btn-success" id="approvedButton">Approve</button>
                </div>
				<div class="col"></div>
                <div class="col-lg-3">
                    <button type="button" value="rejected" class="btn btn-danger" id="rejectedButton">Reject</button>
                </div>
@endif
            </div>
@if ( !empty($offerhunting->status) && $offerhunting->status == "pending")
			<br>
            <div class="row">
                <div class="col"></div>
                <div class="col-lg-3">
                    <button class="btn btn-warning" id="saveButton">Save </button>
                    <small class="form-text small-text-color">Click to update the conetent of the hunting record but no change to status</small>
                </div>
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

	$(document).ready(function()  {

		window.onbeforeunload = function(e)  {showLoading();};

		$("#saveButton").click(function()  {

			// var basicRule = {
			// 	rules:  {
			// 		startDate:  {date:true},
			// 		startTime:  {time:true},
			// 		endDate:  {date:true},
			// 		endTime:  {time:true},
			// 		offerName:  {minlength:3, alphanumeric:true},
			// 		offerTitle:  {minlength:1},
			// 		quota:  {number:true},
			// 	},
			// 	messages: {
			// 		offerName:  {minlength:"Must consist of at least 3 characters"},
			// 		offerTitle:  {minlength:"Must consist of at least 1 characters"},
			// 	}
			// };

			// var form = $("#form");
			// form.validate(basicRule);

			// result = form.valid();
			// if (result == false)  {return;}

			var disabled = $("#form").find(":input:disabled").removeAttr("disabled");
			var formData = $("#form").serialize();
			disabled.attr("disabled", "disabled");

			showLoading();
			$.ajax({
				type: "POST",
				data: formData,
				dataType: "json",
				url: '{{ route("foso.offerhunting.settings.json", ["id"=>$id]) }}',
				success: function (result)  {

					alert(result.message);
					if (result.status != 0)  {
						hideLoading();
						return;
					}
					location.href = '{{ route("foso.offerhunting.html") }}';
				},
				error: function (XMLHttpRequest, textStatus, errorThrown)  {
					hideLoading();
					alert("Oops...\n#"+textStatus+": "+errorThrown);
				}
			});

		});


		const handleApprovedButton = () => {
			showLoading();

			const tempMobile = document.getElementById("mobile_num").value;
			const tempName = document.getElementById("name").value;

			const api = '{{ route("foso.offerhunting.settings.approve.json", ["id"=>$id]) }}';
			fetch(api, {
				method: 'post',
				headers: {'Content-Type': 'application/json'},
				body: JSON.stringify({
					id: '{{$id}}',
					'old_mobile_num': '{{$offerhunting->mobile_num}}',
					'mobile_num':tempMobile,
					'name':tempName,
				})
			})
			.then(response=>response.json())
			.then(json => {

				if (json.status < 0)  {
					alert(json.message);
					return;
				}

				//  Reload if success
				location.href = '{{ route("foso.offerhunting.html") }}';
			});

			hideLoading();
		};

		const approve_Button = AnimatedBtn({
			domId: "#approvedButton",
			transitionTime: 2000,
			yourFunction: handleApprovedButton,
			coverColor: 'rgba(80, 130, 55, 0.5)',
			baseColor: 'rgba(50, 191, 80, 1)',
		});

        const handleRejectedButton = () => {
			showLoading();

			const tempStatus = document.getElementById("status").value;

			const api = '{{ route("foso.offerhunting.settings.reject.json", ["id"=>$id]) }}';
			fetch(api, {
				method: 'post',
				headers: {'Content-Type': 'application/json'},
				body: JSON.stringify({
					id: '{{$id}}',
					'old_status': '{{$offerhunting->status}}',
					'mobile': '{{$offerhunting->mobile_num}}', 
				})
			})
			.then(response=>response.json())
			.then(json => {

				if (json.status < 0)  {
					alert(json.message);
					return;
				}

				//  Reload if success
				location.href = '{{ route("foso.offerhunting.settings.html",["id"=>$id]) }}';

			});

			hideLoading();
		};

		const reject_Button = AnimatedBtn({
			domId: "#rejectedButton",
			transitionTime: 2000,
			yourFunction: handleRejectedButton,
			coverColor: 'rgba(128, 40, 40, 1)',
			baseColor: 'rgba(221, 75, 57, 1)',
		});

		
	});

</script>



@endsection
