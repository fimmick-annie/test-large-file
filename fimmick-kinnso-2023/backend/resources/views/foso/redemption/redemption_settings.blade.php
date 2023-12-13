@extends('foso.layouts.default')

@section('page_title', 'Redemption Settings')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.redemption.html") }}'>Redemption</a></li>
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
				<div class="col-sm-12"><h4>Basic Redemption Settings</h4></div>
			</div>

			<div class="row">
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="createdAt">Create Time</label>
						<input class="form-control" value="{{ empty($redemption->created_at)? $createDateTime:$redemption->created_at }}" type="text"  id="created_at" name="created_at" disabled> 
					</fieldset>
				</div>
				<div class="col-lg-4">
@if($id != '0')
					<fieldset class="form-group" >
						<label for="updatedAt">Update Time</label>
						<input class="form-control" value="{{ empty($redemption->updated_at)? '' :$redemption->updated_at }}"  type="text" id="updatedAt" name="updatedAt" disabled> 
					</fieldset>
@endif
				</div>
				<div class="col-lg-4">
					<fieldset class="form-group" style="display:none;">
						<label for="id">Redemption ID</label>
						<input class="form-control" value="{{ empty($redemption->id)? $id:$redemption->id }}" type="text" id="id" name="id" > 
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-3">
					<fieldset class="form-group">
						<label for="startDate">Start Date</label>
						<input class="form-control" type="date" value="{{ $startDate }}" id="startDate" name="startDate" required>
						<small class="form-text small-text-color">Redemption available date</small>
					</fieldset>
				</div>
				<div class="col-lg-3">
					<fieldset class="form-group">
						<label for="startTime">Start Time</label>
						<input class="form-control" type="time" value="{{ $startTime }}" id="startTime" name="startTime" required>
						<small class="form-text small-text-color">Redemption available time</small>
					</fieldset>
				</div>

				<div class="col-lg-3">
					<fieldset class="form-group">
						<label for="endDate">End Date</label>
						<input class="form-control" type="date" value="{{ $endDate }}" id="endDate" name="endDate" required>
						<small class="form-text small-text-color">Redemption expiry date</small>
					</fieldset>
				</div>
				<div class="col-lg-3">
					<fieldset class="form-group">
						<label for="endTime">End Time</label>
						<input class="form-control" type="time" value="{{ $endTime }}" id="endTime" name="endTime" required>
						<small class="form-text small-text-color">Redemption expiry time</small>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="title">Redemption Title</label>
						<input class="form-control" type="text" id="title" value="{{ empty($redemption->title)?'':$zhTitle }}" name="title" placeholder="優惠券HKD150" maxlength="63" required> 
					</fieldset>
				</div>

				<div class="col-lg-6">
					<fieldset class="form-group">
						<label for="subtitle">Redemption Subtitle</label>
						<input class="form-control" type="text" id="subtitle" value="{{ empty($redemption->subtitle)?'':$zhSubtitle }}" name="subtitle" placeholder="(價值HKD150)" maxlength="63">
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="ordering">Ordering</label>
						<input class="form-control" type="text" value="{{ empty($redemption->ordering)?'100':$redemption->ordering }}" id="ordering"  name="ordering" placeholder="100" maxlength="63">
						<small class="form-text small-text-color">Larger ordering number, higher ranking when display</small>
					</fieldset>
				</div>

				<div class="col-lg-6">
					<fieldset class="form-group">
						<label for="redemptionPath">Redemption Path</label>
						<input class="form-control" type="text" value="{{ $randomCode }}" id="redemptionPath" name="redemptionPath" maxlength="32" {{ ($id != '0')? "disabled":"required"}}>
						<small class="form-text small-text-color">Must be unique, it can be changed at the first time in setting page</small>
						<small class="form-text small-text-color">Only letters, numbers, hyphen is allowed</small>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-6">
					<fieldset class="form-group">
						<label for="thumbnailFilename">Thumbnail File</label>
							<div class="upload-area {{ $errors->has('thumbnail_filename') ? 'error' : '' }}" >
								<input type="file" class="dropify" name="thumbnailFilename" data-default-file="{{ empty($redemption->thumbnail_filename)? asset('redemptions/empty.png'):asset('redemptions/'.$redemption->thumbnail_filename) }}?v={{ now()->format('dHis') }}.png" />
							</div>
@error('thumbnail_filename')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">104 x 104, PNG</small>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-6">
					<fieldset class="form-group">
						<label for="codeType">Redemption Coupon Type</label>
						<select class="form-control" id="codeType" name="codeType">
							<option value="barcode" {{ (!empty($redemption->code_type)&&($redemption->code_type=="barcode"))?"selected":"" }}>Barcode</option> 
							<option value="qrcode" {{ (!empty($redemption->code_type)&&($redemption->code_type=="qrcode"))?"selected":"" }}>QR code</option> 
							<option value="promocode" {{ (!empty($redemption->code_type)&&($redemption->code_type=="promocode"))?"selected":"" }}>Promocode</option>
							<option value="url" {{ (!empty($redemption->code_type)&&($redemption->code_type=="url"))?"selected":"" }}>URL</option> 
						</select>
				
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="requiredPoints">Required Point</label>
						<input class="form-control" value="{{ empty($redemption->required_points)?'100':$redemption->required_points }}" type="text" id="requiredPoints" name="requiredPoints" required> 
						<small class="form-text small-text-color">The point for once redemption exchange</small>
					</fieldset>
				</div>
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="quota">Quota</label>
@if ($id != '0' && $redemption->quota == $redemption->quota_issued)
						<small class="form-text text-danger" style="display:inline;">(Out of quota)</small>
@endif
						<input class="form-control" value="{{ empty($redemption->quota)?'0':$redemption->quota }}" type="text" id="quota" name="quota" disabled>
						<small class="form-text small-text-color">Value base on quota settings or coupon pool</small>
					</fieldset>
				</div>
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="quotaIssued">Quota Issued</label>
						<input class="form-control" value="{{ empty($redemption->quota_issued)?'0':$redemption->quota_issued }}" type="text" id="quotaIssued" name="quotaIssued" disabled> 
					</fieldset>
				</div>
				<div class="col-lg-8"></div>
@if ($id != '0' && ($redemption->quota != 0 || $redemption->quota != $redemption->quota_issued)) 
@if ($redemption->quota > $redemption->quota_issued)
				<div class="col-lg-4" id="outOfQuotaButtonGroup" >
					<fieldset class="form-group">
						<label for="quotaIssued">Quota Action</label>
						<div class="text-white btn btn-danger btn-block" id="outOfQuotaButton">Set "Out of Quota"</div>
						<small class="form-text small-text-color">Set quota to negative number, no revert</small>
						<small class="form-text small-text-color">Please hold until finish</small>
					</fieldset>
				</div>
@elseif($redemption->quota < 0)
				<div class="col-lg-4" id="resumeQuotaButtonGroup">
					<fieldset class="form-group">
						<label for="quotaIssued">Quota Action</label>
						<div class="text-white btn btn-warning btn-block" id="resumeQuotaButton">Resume Quota</div>
						<small class="form-text small-text-color">Reset to previous quota amount</small>
						<small class="form-text small-text-color">Please hold until finish</small>
					</fieldset>
				</div>
@endif
@endif
			</div>

		</div>
	</div>

	<!--  Detailed Offer Settings  -->
	<div class="card" style="margin-bottom:20px;">
		<div class="card-body">

			<div class="row">
				<div class="col-sm-12"><h4>Detailed Redemption Settings</h4></div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<fieldset class="form-group">
						<label for="details">Details</label>
						<textarea class="form-control" id="details" name="details" rows=6>{{ empty($redemption->details)?'':nl2br($zhDetails) }}</textarea>
						<small class="form-text small-text-color">Support line break and HTML tags.  If you want clickable link, please use <u>&lt;a href="url" target="_blank"&gt;url&lt;/a&gt;</u></small>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<fieldset class="form-group">
						<label for="voidDetails">Void Details</label>
						<textarea class="form-control" id="voidDetails" name="voidDetails" rows=6>{{ empty($redemption->void_details)?'':nl2br($zhVoidDetails) }}</textarea>
						<small class="form-text small-text-color">Support line break and HTML tags.  If you want clickable link, please use <u>&lt;a href="url" target="_blank"&gt;url&lt;/a&gt;</u></small>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-6">
@if ($id == '0')
					<button type="button" class="btn btn-success" id="saveButton">Create</button>
					
@else				
					<button type="button" class="btn btn-danger" id="saveButton">Save</button>
@endif 
				</div>

			</div>

		</div>

		
	</div>
@if ($id !=0)
	<div class="card">
			<div class='card-body'>
				<div class="row">
					<div class="col-sm-12">
						<!-- <h3 class="text-black">Redemption code adding</h3> -->
						<h3>Upload CSV of redemption codes</h3> 
						<p></p>
						<!-- <h4>Upload CSV of redemption codes</h4> -->
						<p>Content in CSV file will replace record on server side if store
							code and unique code are the same.  <u>If you want to remove a redemption code, please
						rename store code to something else.</u></p>
						<div class="upload-area {{ $errors->has('quota_file') ? 'error' : '' }}">
							<input type="file" class="dropify" id="quota_file" name="quota_file" data-default-file="redemption_code_pool.csv"  {{($redemption->quota < 0)? "disabled":""}}>
						</div>
					</div>
				</div>
@error('quota_file')
				<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
				<small class="form-text small-text-color">
					Please use <a href="{{ asset('assets/foso/redemption_code_pool.csv') }}?v=1" target="_blank">this CSV structure</a>.
					<br>
				</small>
		</div>
	</div>
@endif
	<input class="form-control" type="hidden" value="" name="path" id="path">

</form>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{ asset('js/ckeditor.js') }}?v=1"></script>
<script type="text/javascript" src="{{ asset('js/common.js') }}?v=1"></script>
<script src="/js/animatedBtn.js"></script>

<script src="{{asset('assets/vendor/adminkit/plugins/dropify/dropify.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('assets/vendor/adminkit/plugins/dropify/dropify.min.css')}}" />

<script>
	$('.dropify').dropify();

	$('.dropify').on('dropify.afterClear', function (event)  {

		var formdata = new FormData();
		formdata.append('_token', '{{ csrf_token() }}');
		formdata.append('filename', $(event.target).attr('name'));
		formdata.append('default', $(event.target).data('default-file'));
		formdata.append('remove', true);

		if(document.getElementById("id").value == '0'){
			document.getElementById("path").value = '';
			
		}else{

			$.ajax({
				method: 'POST',
				url: '{{ route('foso.redemption.resources.upload', ['id'=>$id]) }}',
				data: formdata,
				dataType: 'json',
				processData: false,
				contentType: false,
				success: function(data, textStatus, jqXHR) {
					$(event.target).val('');
					console.log("Remove file status: "+data.status);
					console.log(data);
				}
			});	

		}

	});

	$('.dropify').on('change', function (event)  {
		var fileObject = $(event.target).prop('files');

		if (fileObject[0] != undefined) {

			var file = fileObject[0];

			var defaultFile    = $(event.target).data('default-file');
			var defaultFile2   = defaultFile.split('.');
			var checkExtension = defaultFile2[defaultFile2.length - 1];

			var fileExtension  = (file.name.split('.'));
			fileExtension      = fileExtension[fileExtension.length - 1];

			if (fileExtension != checkExtension) {
				var dropify = $(event.target).data('dropify');
				dropify.resetPreview();
				dropify.setPreview(dropify.isImage(), defaultFile);
				alert('Please upload .' + checkExtension + ' file');
				return false;
			}

			if (! isNaN(file.size) && file.size > (10 * 1024 * 1024)) {
				alert('Please upload a file within 10 MB size.');
				return false;
			}

			var formdata = new FormData();
			formdata.append('_token', '{{ csrf_token() }}');
			formdata.append('filename', $(event.target).attr('name'));
			formdata.append('file', file);

			if (fileExtension == 'csv'){

				$.ajax({
					method: 'POST',
					url: '{{ route('foso.redemption.resources.upload.csv', ['id'=>$id]) }}',
					data: formdata,
					dataType: 'json',
					processData: false,
					contentType: false,
					success: function(data, textStatus, jqXHR) {
						$(event.target).val('');
						if (data.status == 'ok') {
							alert('CSV is successfully uploaded.');
						} else {
							alert('failed to upload, please try again.');
						}
						
						//  Reload if success
						location.href = '{{ route("foso.redemption.html") }}'; 
					}
				});

			}else{

				$.ajax({
					method: 'POST',
					url: '{{ route('foso.redemption.resources.upload', ['id'=>$id]) }}',
					data: formdata,
					dataType: 'json',
					processData: false,
					contentType: false,
					success: function(data, textStatus, jqXHR) {
						$(event.target).val('');
						if (data.status == 'ok') {
							alert('successfully uploaded.');
							// pass the temp filename for thumbnail to frontend
							document.getElementById("path").value = data.serverFilename;

						} else {
							alert('failed to upload, please try again.');
						}
					}
				});
			}

		}
	});


	$(document).ready(function()  {

		//  When leave page, show loading
		window.onbeforeunload = function(e)  {showLoading();};

		$.validator.addMethod("time", function(value, element)  {
			return this.optional(element) || /^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])(:([0-5]?[0-9]))?$/i.test(value);
		}, "Please enter a valid time.");

		$.validator.addMethod("alphanumeric", function(value, element)  {
			return this.optional(element) || /^[\w._-]+$/i.test(value);
		}, "Only letters, numbers, hyphen and underscore is allowed.");

		$.validator.addMethod("nounderscore", function(value, element, param)  {
			return this.optional(element) || /^[a-zA-Z0-9.-]+$/i.test(value);
		}, "Underscore is not allowed.");

		$("#saveButton").click(function()  {

			var basicRule = {
				rules:  {
					startDate:  {date:true},
					startTime:  {time:true},
					endDate:  {date:true},
					endTime:  {time:true},
					title:  {minlength:1},
					subtitle:  {minlength:1},
					ordering:  {number:true},
					quota:  {number:true},
					requiredPoints:  {number:true},
					details: {minlength:1},
					voidDetails: {minlength:1},
				},
				messages: {
					title:  {minlength:"Must consist of at least 1 characters"},
					subtitle:  {minlength:"Must consist of at least 1 characters"},
					details:  {minlength:"Must consist of at least 1 characters"},
					voidDetails:  {minlength:"Must consist of at least 1 characters"},
					thumbnailFilename:  {minlength:"Must consist of at least 1 characters"},
				}
			};

			var form = $("#form");
			form.validate(basicRule);
			result = form.valid();
			
			if (result == false)  {return;}

			//  Form OK
			CKEDITOR.instances.details.updateElement();
			CKEDITOR.instances.voidDetails.updateElement();

			var disabled = $("#form").find(":input:disabled").removeAttr("disabled");
			// var formData = $("#form").serialize();
			var formData = form.serialize();
			disabled.attr("disabled", "disabled");
			console.log(formData);

			showLoading();

			$.ajax({
				type: "POST",
				data: formData,
				dataType: "json",
				url: '{{ route("foso.redemption.settings.json", ["id"=>$id])}}',
				success: function (result)  {
					alert(result.message);
					if (result.status != 0)  {
						hideLoading();
						return;
					}
					//back to redemption page to see the updated redemption record
					location.href = '{{ route("foso.redemption.html")}}';

				},
				error: function (XMLHttpRequest, textStatus, errorThrown)  {
					hideLoading();
					alert("Oooops...\n#"+textStatus+": "+errorThrown);
				}
			});

		});

		const handleOutOfQuotaButton = () => {
			showLoading();

			const api = '{{ route("foso.redemption.outofredemptionquota.json", ["id"=>$id]) }}' //->redemption
			fetch(api, {
				method: 'post',
				headers: {'Content-Type': 'application/json'},
				body: JSON.stringify({
					id: '{{$id}}',
				})
			})
			.then(response=>response.json())
			.then(json => {

				if (json.status < 0)  {
					alert(json.message);
					return;
				}

				//  Reload if success
				location.href = '{{ route("foso.redemption.html") }}'; 
			});

			hideLoading();
		};

		const outOfQuotaButton = AnimatedBtn({
			domId: "outOfQuotaButton",
			transitionTime: 2000,
			yourFunction: handleOutOfQuotaButton,
			coverColor: 'rgba(128, 40, 40, 1)',
			baseColor: 'rgba(221, 75, 57, 1)',
		});

		const handleResumeQuotaButton = () => {
			showLoading();

			const api = '{{ route("foso.redemption.resumeredemptionquota.json", ["id"=>$id]) }}' //->redemption
			fetch(api, {
				method: 'post',
				headers: {'Content-Type': 'application/json'},
				body: JSON.stringify({
					id: '{{$id}}',
				})
			})
			.then(response=>response.json())
			.then(json => {

				if (json.status < 0)  {
					alert(json.message);
					return;
				}

				//  Reload if success
				location.href = '{{ route("foso.redemption.html") }}'; 
			});

			hideLoading();
		};

		const resumeQuotaButton = AnimatedBtn({
			domId: "resumeQuotaButton",
			transitionTime: 2000,
			yourFunction: handleResumeQuotaButton,
			coverColor: 'rgba(128, 64, 0, 1)',
			baseColor: 'rgba(224, 142, 11, 1)',
		});

@if (empty($redemption) == false)
@if ($redemption->quota > $redemption->quota_issued)
		//  Still have quota, show "Out of Quota" button
		$("#outOfQuotaButtonGroup").show();
@else
		//  No quota, show "Resume Quota" button
		$("#resumeQuotaButtonGroup").show();
@endif
@endif

		CKEDITOR.replace("details");
		CKEDITOR.replace("voidDetails");

	});
	
</script>

@endsection
