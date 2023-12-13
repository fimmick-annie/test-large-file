@extends('foso.layouts.default')

@section('page_title', 'Campaign Banner Settings')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.banner.bannerlist.html") }}'>Banner</a></li>
<li><i class='fa fa-angle-right'></i> Settings</li>
@endsection

@section('content')

<form id="form" name="form">
	@csrf

	<div class="card" style="margin-bottom:20px;">
		<div class="card-body">

			<div class="row">
				<div class="col-sm-12"><h4>Campaign Banner Settings</h4></div>
			</div>

			<div class="row">
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="createdAt">Create Time</label>
						<input class="form-control" value="{{ empty($campaignBanner->created_at)? '' :$campaignBanner->created_at }}" type="text"  id="createdAt" name="createdAt" disabled> 
					</fieldset>
				</div>
				<div class="col-lg-4">
@if($id != '0')
					<fieldset class="form-group" >
						<label for="updatedAt">Update Time</label>
						<input class="form-control" value="{{ empty($campaignBanner->updated_at)? '' :$campaignBanner->updated_at }}"  type="text" id="updatedAt" name="updatedAt" disabled> 
					</fieldset>
@endif
				</div>
				<div class="col-lg-4">
					<fieldset class="form-group" style="display:block;">
						<label for="id">Campaign Banner ID</label>
						<input class="form-control" value="{{ empty($campaignBanner->id)? ' ':$campaignBanner->id }}" type="text" id="id" name="id" disabled> 
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-3">
					<fieldset class="form-group">
						<label for="startedDate">Start Date</label>
						<input class="form-control" type="date" value="{{ $startedDate }}" id="startedDate" name="startedDate" required>
						<small class="form-text small-text-color">Banner available date</small>
					</fieldset>
				</div>
				<div class="col-lg-3">
					<fieldset class="form-group">
						<label for="startedTime">Start Time</label>
						<input class="form-control" type="time" value="{{ $startedTime }}" id="startedTime" name="startedTime" required>
						<small class="form-text small-text-color">Banner available time</small>
					</fieldset>
				</div>

				<div class="col-lg-3">
					<fieldset class="form-group">
						<label for="endedDate">End Date</label>
						<input class="form-control" type="date" value="{{ $endedDate }}" id="endedDate" name="endedDate" required>
						<small class="form-text small-text-color">Banner expiry date</small>
					</fieldset>
				</div>
				<div class="col-lg-3">
					<fieldset class="form-group">
						<label for="endedTime">End Time</label>
						<input class="form-control" type="time" value="{{ $endedTime }}" id="endedTime" name="endedTime" required>
						<small class="form-text small-text-color">Banner expiry time</small>
					</fieldset>
				</div>
			</div>


			<div class="row">
			
				<div class="col-lg-3">
					<fieldset class="form-group">
						<label for="bannerType">Campaign Banner Type</label>
						<select class="form-control" id="bannerType" name="bannerType">
							<option value="key-visuals" {{ (!empty($campaignBanner->type)&&($campaignBanner->type=="key-visuals"))?"selected":"" }}>key-visuals</option> 
							<option value="banners" {{ (!empty($campaignBanner->type)&&($campaignBanner->type=="banners"))?"selected":"" }} >banners</option>
						</select>
					</fieldset>
				</div>

				<div class="col-lg-3">
					<fieldset class="form-group">
						<label for="bannerweight">Weight</label>
						<input class="form-control" type="text" value="{{ $id == 0 ? '100': $currentweight }}" id="bannerweight"  name="bannerweight" maxlength="20" {{ $currentweight >= 0 ? "required":"disabled"}}>
						<small class="form-text small-text-color">Larger Weight number, higher ranking when display</small>
					</fieldset>
				</div>

			</div>

			<div class="row">
				<div class="col-lg-6">
					<fieldset class="form-group">
@if($bannerType == 'key-visuals' || $id == '0')
						<label id="lableImage1" for="image1">Image for moblie</label>
@else
						<label id="lableImage1" for="image1">Image</label>
@endif
						<div class="upload-area {{ $errors->has('image1') ? 'error' : '' }}" >
							<input type="file" class="dropify" id="image1" name="image1" data-default-file="{{ strlen($image1)<=0 ? asset('website/empty.png'):asset($image1) }}?v={{ now()->format('dHis') }}.png" data-max-file-size="1M"/>
						</div>
@error('image1')
						<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
						<!-- <small id="imageRemarkLeft" class="form-text text-muted">size TBC, PNG</small> -->

@if($bannerType == 'key-visuals' || $id == '0')
						<small id="imageRemarkLeft" class="form-text text-muted">284 x 128, PNG (< 1 MB) </small>	
@else
						<small id="imageRemarkLeft" class="form-text text-muted">448 x 282, PNG (< 1 MB) </small>
@endif
					</fieldset>
				</div>

				<div class="col-lg-6">
@if($bannerType == 'banners')
					<fieldset id="img2Area" class="form-group" style="display:none;">
@else
					<fieldset id="img2Area" class="form-group" >
@endif
						<label for="image2" >Image for Desktop</label>
						<div class="upload-area {{ $errors->has('image2') ? 'error' : '' }}" >
							<input type="file" class="dropify" id="image2" name="image2" data-default-file="{{ strlen($image2)<=0 ? asset('website/empty.png'):asset($image2)}}?v={{ now()->format('dHis') }}.png" data-max-file-size="1M" />
						</div>
@error('image2')
						<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
						<small class="form-text text-muted">1192 x 356, PNG (< 1 MB) </small>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-6">
					<fieldset class="form-group">
@if($bannerType == 'key-visuals' || $id == '0')
						<label id="lableTargeturl1" for="targeturl1">Target URL for mobile</label>
@else
						<label id="lableTargeturl1" for="targeturl1">Target URL</label>
@endif			
						<input class="form-control" type="text" value="{{ strlen($target1)<=0 ? '': $target1 }}" id="targeturl1"  name="targeturl1"  maxlength="200">
						<!-- <small class="form-text small-text-color">Larger ordering number, higher ranking when display</small> -->
					</fieldset>
				</div>

				<div class="col-lg-6">
@if($bannerType == 'banners')
					<fieldset id="target2Area"  class="form-group" style="display:none">
@else
					<fieldset id="target2Area"  class="form-group">
@endif
						<label for="targeturl2">Target URL for Desktop</label>
						<input class="form-control" type="text" value="{{ strlen($target2)<=0 ? '': $target2 }}"  id="targeturl2"  name="targeturl2" maxlength="200">
						<!-- <small class="form-text small-text-color">Larger ordering number, higher ranking when display</small> -->
					</fieldset>
				</div>
			</div>

			<div class="row" style="height:20px;"></div>

			<div class="row">
				<div class="col-lg-6">
					<fieldset class="form-group">
@if ($id == '0')			
							<button type="button" class="btn btn-success" id="saveButton" style="width:100px;">Create</button>
@else				
							<button type="button" class="btn btn-danger" id="saveButton" style="width:100px;" >Save</button>
@endif
					</fieldset>
				</div>
				<div class="col-lg-2"></div>

@if ($id != '0') 
@if ($campaignBanner->weight >= 0 )
				<div class="col-lg-4" id="stopButtonGroup" >
					<fieldset class="form-group">
						<div class="text-white btn btn-danger btn-block" id="stopButton">Stop launching</div>
						<small class="form-text small-text-color">Set the weight to negative number</small>
						<small class="form-text small-text-color">Please hold until finish</small>
					</fieldset>
				</div>
@else
				<div class="col-lg-4" id="resumeButtonGroup">
					<fieldset class="form-group">
						<div class="text-white btn btn-warning btn-block" id="resumeButton">Resume launching</div>
						<small class="form-text small-text-color">Reset to previous weight</small>
						<small class="form-text small-text-color">Please hold until finish</small>
					</fieldset>
				</div>
@endif
@endif
				</div>

			</div>

		</div>
	</div>
	</div>
	</div>

	<input class="form-control" type="hidden" value="" name="pathimg1" id="pathimg1">
	<input class="form-control" type="hidden" value="" name="pathimg2" id="pathimg2">

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

		var imagetype = $(event.target).attr('name');

		var formdata = new FormData();
		formdata.append('_token', '{{ csrf_token() }}');
		formdata.append('filename', imagetype);
		formdata.append('default', $(event.target).data('default-file'));
		formdata.append('bannertype', document.getElementById("bannerType").value);
		formdata.append('remove', true);

		$.ajax({
			method: 'POST',
			url: '{{ route('foso.banner.resources.upload', ['id'=>$id]) }}',
			data: formdata,
			dataType: 'json',
			processData: false,
			contentType: false,
			success: function(data, textStatus, jqXHR) {
				$(event.target).val('');
				console.log("Remove file status: "+data.status);

				if (imagetype == 'image1'){
					document.getElementById("pathimg1").value = "deleted";
				}else{
					document.getElementById("pathimg2").value = "deleted";
				}
			}
		});	

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

			var imagetype = $(event.target).attr('name');

			var formdata = new FormData();
			formdata.append('_token', '{{ csrf_token() }}');
			formdata.append('filename', imagetype);
			formdata.append('bannertype', document.getElementById("bannerType").value);
			formdata.append('file', file);

            $.ajax({
                method: 'POST',
                url: '{{ route('foso.banner.resources.upload', ['id'=>$id]) }}',
                data: formdata,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(data, textStatus, jqXHR) {
                    $(event.target).val('');
                    if (data.status == 'ok') {
                        alert('Successfully uploaded.');
						if (imagetype == 'image1'){
							document.getElementById("pathimg1").value = data.serverFilename;
						}else{
							console.log(data.serverFilename);
							document.getElementById("pathimg2").value = data.serverFilename;
						}
                    } else {
                        alert('failed to upload, please try again.');
                    }
                    
                    //  Reload if success
                    // location.href = '{{ route("foso.banner.bannerlist.html") }}'; 
                }
            });

		}
	});

	$(document).ready(function()  {

		//  When leave page, show loading
		window.onbeforeunload = function(e)  {showLoading();};

        $('#bannerType').change(function(){
            if($(this).val() == "key-visuals"){
				document.querySelector("#lableImage1").innerHTML="Image for moblie";
				document.querySelector("#lableTargeturl1").innerHTML="Target URL for mobile";
				document.querySelector("#imageRemarkLeft").innerHTML="448 x 282, PNG (< 1 MB)";				
				$('#target2Area').show();
				$('#img2Area').show();
            }else{
				document.querySelector("#lableImage1").innerHTML="Image";
				document.querySelector("#lableTargeturl1").innerHTML="Target URL";
				document.querySelector("#imageRemarkLeft").innerHTML="284 x 128, PNG  (< 1 MB) ";
				$('#target2Area').hide();
				$('#img2Area').hide();
            }
        });

		$.validator.addMethod("time", function(value, element)  {
			return this.optional(element) || /^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])(:([0-5]?[0-9]))?$/i.test(value);
		}, "Please enter a valid time.");

		$("#saveButton").click(function()  {

			var basicRule = {
				rules:  {
					startDate:  {date:true},
					startTime:  {time:true},
					endDate:  {date:true},
					endTime:  {time:true},
					weight:  {number:true},
				},
				messages: {
				}
			};

			var form = $("#form");
			form.validate(basicRule);
			result = form.valid();
			
			if (result == false)  {return;}

			//  Form OK

			// var disabled = $("#form").find(":input:disabled").removeAttr("disabled");
			// var formData = $("#form").serialize();
			var formData = form.serialize();
			// disabled.attr("disabled", "disabled");
			// console.log(formData);

			showLoading();

			$.ajax({
				type: "POST",
				data: formData,
				dataType: "json",
				url: '{{ route("foso.banner.settings.json", ["id"=>$id])}}',
				success: function (result)  {
					alert(result.message);
					if (result.status != 0)  {
						hideLoading();
						return;
					}
					location.href = '{{ route("foso.banner.bannerlist.html")}}';

				},
				error: function (XMLHttpRequest, textStatus, errorThrown)  {
					hideLoading();
					alert("Oooops...\n#"+textStatus+": "+errorThrown);
				}
			});
		});

		const handleStopButton = () => {
			showLoading();
			const api = '{{ route("foso.banner.stop.json", ["id"=>$id]) }}' 
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
				location.href = '{{ route("foso.banner.bannerlist.html") }}'; 
			});

			hideLoading();
		};

		const stopButton = AnimatedBtn({
			domId: "stopButton",
			transitionTime: 2000,
			yourFunction: handleStopButton,
			coverColor: 'rgba(128, 40, 40, 1)',
			baseColor: 'rgba(221, 75, 57, 1)',
		});

		const handleResumeButton = () => {
			showLoading();
			const api = '{{ route("foso.banner.resume.json", ["id"=>$id]) }}' 
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
				location.href = '{{ route("foso.banner.bannerlist.html") }}'; 
			});

			hideLoading();
		};

		const resumeButton = AnimatedBtn({
			domId: "resumeButton",
			transitionTime: 2000,
			yourFunction: handleResumeButton,
			coverColor: 'rgba(128, 64, 0, 1)',
			baseColor: 'rgba(224, 142, 11, 1)',
		});

@if (!empty($campaignBanner))
@if ($campaignBanner->weight >= 0 )
		//  Still have quota, show "Out of Quota" button
		$("#stopButtonGroup").show();
@else
		//  No quota, show "Resume Quota" button
		$("#resumeButtonGroup").show();
@endif
@endif


	});
	
</script>

@endsection
