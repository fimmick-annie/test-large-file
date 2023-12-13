@extends('foso.layouts.default')

@section('page_title', 'Channel Settings')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.channel.html") }}'>Channel receipt sample</a></li>
<li><i class='fa fa-angle-right'></i> Settings</li>
@endsection

@section('content')


<form id="form" name="form">
	@csrf

	<div class="card" style="margin-bottom:20px;">
		<div class="card-body">

			<div class="row">
				<div class="col-sm-12"><h4>Receipt Sample Basic Settings</h4></div>
			</div>

			<div class="row">
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="createdAt">Create Time</label>
						<input class="form-control" value="{{ empty($sample->created_at)? $createDateTime:$sample->created_at }}" type="text"  id="created_at" name="created_at" disabled> 
					</fieldset>
				</div>
@if($id != '0')
				<div class="col-lg-4">
					<fieldset class="form-group" >
						<label for="updatedAt">Update Time</label>
						<input class="form-control" value="{{ empty($sample->updated_at)? '' :$sample->updated_at }}"  type="text" id="updatedAt" name="updatedAt" disabled> 
					</fieldset>

				</div>
				<div class="col-lg-4">
					<fieldset class="form-group" >
						<label for="id">Sample ID</label>
						<input class="form-control" value="{{ empty($sample->id)? ' ' :$sample->id }}" type="text" id="id" name="id" disabled> 
					</fieldset>
				</div>
@endif
			</div>

			<div class="row">
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="startDate">Start Date</label>
						<input class="form-control" type="date" value="{{ $startDate }}" id="startDate" name="startDate" required>
						<small class="form-text small-text-color">Sample available date</small>
					</fieldset>
				</div>
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="startTime">Start Time</label>
						<input class="form-control" type="time" value="{{ $startTime }}" id="startTime" name="startTime" required>
						<small class="form-text small-text-color">Sample available time</small>
					</fieldset>
				</div>
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="nostart"> </label>
						<button type="button" class="btn btn-info" id="nostart"  style="width:100%" onclick="startreset();">No start date</button>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="endDate">End Date</label>
						<input class="form-control" type="date" value="{{ $endDate }}" id="endDate" name="endDate" required>
						<small class="form-text small-text-color">Sample expiry date</small>
					</fieldset>
				</div>
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="endTime">End Time</label>
						<input class="form-control" type="time" value="{{ $endTime }}" id="endTime" name="endTime" required>
						<small class="form-text small-text-color">Sample end time</small>
					</fieldset>
				</div>
				<div class="col-lg-4">
					<fieldset class="form-group">
					<label for="noend"> </label>
						<button type="button" class="btn btn-info" id="noend" style="width:100%" onclick="endreset();">No end date</button>
					</fieldset>
				</div>
			</div>


			<div class="row">
				<div class="col-lg-6">
					<fieldset class="form-group">
						<label for="channel">Channel</label>
						<input class="form-control" type="text" id="channel" value="{{ empty($sample->channel)?'':$sample->channel }}" name="channel" placeholder="Channel - Name" maxlength="63" required> 
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-6">
					<fieldset class="form-group">
						<label for="saveType">Sample Saving Type</label>
						<select class="form-control" id="saveType" name="saveType">
							<option value="url" {{ (!empty($sample->save_type)&&($sample->save_type=="url"))?"selected":"" }}>By URL</option> 
							<option value="local" {{ (!empty($sample->save_type)&&($sample->save_type=="local"))?"selected":"" }}>Upload Here</option> 
						</select>
				
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-8" >
					<fieldset class="form-group">
						<label>Receipt sample Image</label>

							<div id="previewUrl" class="input-group">
								<input class="form-control" type="text" id="urlInput" name="urlInput" value="{{ empty($sample->receipt_sample_url)? ' ' :$sample->receipt_sample_url }}">
								<div class="input-group-append">
									<button type="button" class="btn btn-primary" onclick="changPreview()" >Preview URL</button>
								</div>
								<div class="row">
									<img id="previewReceipt" src="{{ empty($sample->receipt_sample_url)? asset('redemptions/empty.png'):asset($sample->receipt_sample_url) }}" style="padding:5%; width:60%;"/>
								</div>
							</div>
							<div id="previewLocal">
								<div class="upload-area {{ $errors->has('sampleImg') ? 'error' : '' }}" >
									<input type="file" class="dropify" id="sampleImg" name="sampleImg" data-default-file="{{ empty($sample->receipt_sample_url)?  asset('redemptions/empty.png'):asset($sample->receipt_sample_url) }}" data-allowed-file-extensions='["png", "jpg", "jpeg"]'/>
								</div>
@error('sampleImg')
								<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							</div>

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

	<input class="form-control" type="hidden" value="" name="path" id="path">

</form>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{ asset('js/ckeditor.js') }}?v=1"></script>
<script type="text/javascript" src="{{ asset('js/common.js') }}?v=1"></script>
<script src="/js/animatedBtn.js"></script>

<script src="{{asset('assets/vendor/adminkit/plugins/dropify/dropify.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('assets/vendor/adminkit/plugins/dropify/dropify.min.css')}}" />

<script>

	function startreset(){
		document.getElementById("startTime").value = '';
		document.getElementById("startDate").value = '';
	}

	function endreset(){
		document.getElementById("endTime").value = '';
		document.getElementById("endDate").value = '';
	}

	function changPreview(){
		var url = $("#urlInput").val();
		console.log(url);
		document.getElementById('previewReceipt').src=url;
	}

	$('.dropify').dropify();

	$('.dropify').on('change', function (event)  {
		var fileObject = $(event.target).prop('files');

		if (fileObject[0] != undefined) {

			var file = fileObject[0];

			// var defaultFile    = $(event.target).data('default-file');
			// var defaultFile2   = defaultFile.split('.');
			// var checkExtension = defaultFile2[defaultFile2.length - 1];

			// var fileExtension  = (file.name.split('.'));
			// fileExtension      = fileExtension[fileExtension.length - 1];

			// if (fileExtension != checkExtension) {
			// 	var dropify = $(event.target).data('dropify');
			// 	dropify.resetPreview();
			// 	dropify.setPreview(dropify.isImage(), defaultFile);
			// 	alert('Please upload .' + checkExtension + ' file');
			// 	return false;
			// }

			if (! isNaN(file.size) && file.size > (10 * 1024 * 1024)) {
				alert('Please upload a file within 10 MB size.');
				return false;
			}

			var formdata = new FormData();
			formdata.append('_token', '{{ csrf_token() }}');
			formdata.append('filename', $(event.target).attr('name'));
			formdata.append('file', file);

			$.ajax({
				method: 'POST',
				url: '{{ route('foso.channel.receiptsample.upload', ['id'=>$id]) }}',
				data: formdata,
				dataType: 'json',
				processData: false,
				contentType: false,
				success: function(data, textStatus, jqXHR) {
					$(event.target).val('');
					if (data.status == 'ok') {
						alert('successfully uploaded.');
						// pass the temp filename for receipt to frontend
						document.getElementById("path").value = data.serverFilename;

					} else {
						alert('failed to upload, please try again.');
					}
				}
			});
		}
	});

	$(document).ready(function()  {

		if ($('#saveType').val() == "url"){
			$('#previewUrl').show();
			$('#previewLocal').hide();
		}else{
			$('#previewUrl').hide();
			$('#previewLocal').show();
		}
		
		window.onbeforeunload = function(e)  {showLoading();};

		$('#saveType').change(function(){
			if($(this).val() == "url"){
				$('#previewUrl').show();
				$('#previewLocal').hide();
			}else{
				$('#previewUrl').hide();
				$('#previewLocal').show();
			}
		});
		
		$("#saveButton").click(function()  {

			var form = $("#form");
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
				url: '{{ route("foso.channel.settings.json", ["id"=>$id])}}',
				success: function (result)  {
					alert(result.message);
					if (result.status != 0)  {
						hideLoading();
						return;
					}
					//back to redemption page to see the updated redemption record
					location.href = '{{ route("foso.channel.html")}}';

				},
				error: function (XMLHttpRequest, textStatus, errorThrown)  {
					hideLoading();
					alert("Oooops...\n#"+textStatus+": "+errorThrown);
				}
			});

		});

	});
	
</script>

@endsection
