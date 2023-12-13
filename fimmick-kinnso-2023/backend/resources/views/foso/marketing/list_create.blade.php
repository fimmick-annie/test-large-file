@extends('foso.layouts.default')

@section('page_title', 'Create Marketing List')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.marketing.list.html") }}'>Marketing</a></li>
<li><i class='fa fa-angle-right'></i> Marketing List</li>
@endsection

@section('content')

<div class="card">
	<div class="card-body">
		<form id="form" name="form">
			@csrf

			<div class="row">
				<div class="col-lg-8">
					<fieldset class="form-group">
						<label for="listName">List Name</label>
						<input class="form-control" type="text" id="listName" name="listName" placeholder="Fimmick" maxlength="48" required value="{{ $listName }}">
						<small class="form-text small-text-color">Min.3 and max.48 characters, must be unique</small>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<div class="upload-area {{ $errors->has('marketingListFile') ? 'error' : '' }}">
						<input type="file" class="dropify" id="marketingListFile" name="marketingListFile" data-default-file="{{ $filename }}" />
						<small class="form-text small-text-color">Please use <a href="{{ asset('assets/foso/marketing_list.csv') }}?v=1" target="_blank">this CSV structure</a>.</small>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					&nbsp;<br>
					<button type="button" class="btn btn-danger" id="createButton"{{ $createButtonState }}>Create</button>
				</div>
			</div>
		</form>
	</div>
</div><br>

<script type="text/javascript" src="{{ asset('assets/DataTables/datatables.min.js') }}?v=1"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{ asset('js/common.js') }}?v=1"></script>

<link rel="stylesheet" href="{{asset('assets/vendor/adminkit/plugins/dropify/dropify.min.css')}}" />
<script src="{{asset('assets/vendor/adminkit/plugins/dropify/dropify.min.js')}}"></script>
<script>
	var _uniqueID = "{{ $uniqueID }}";
	$(document).ready(function()  {

		//  When leave page, show loading
		window.onbeforeunload = function(e)  {showLoading();};

		$('.dropify').dropify();
		$('#marketingListFile').on('change', function (event)  {

			var fileObject = $(event.target).prop('files');
			if (fileObject[0] != undefined)  {

				var file = fileObject[0];
				var defaultFile = $(event.target).data('default-file');
				var defaultFile2 = defaultFile.split('.');
				var checkExtension = defaultFile2[defaultFile2.length - 1];

				var fileExtension = (file.name.split('.'));
				fileExtension = fileExtension[fileExtension.length - 1];

				if (fileExtension != checkExtension)  {
					var dropify = $(event.target).data('dropify');
					dropify.resetPreview();
					dropify.setPreview(dropify.isImage(), defaultFile);
					alert('Please upload .' + checkExtension + ' file');
					return false;
				}

				if (!isNaN(file.size) && file.size > (10 * 1024 * 1024))  {
					alert('Please upload a file within 10 MB size.');
					return false;
				}

				var formData = new FormData();
				formData.append('_token', '{{ csrf_token() }}');
				formData.append('filename', $(event.target).attr('name'));
				formData.append('file', file);

				$.ajax({
					method: 'POST',
					url: '{{ route("foso.marketing.list.upload.check.api") }}',
					data: formData,
					dataType: 'json',
					processData: false,
					contentType: false,
					success: function(result, status, jqXHR)  {

						$(event.target).val('');
						if (result.status < 0)  {

							//  Error
							alert(result.message);
							return;
						}

						//  Success
						_uniqueID = result.data.uniqid;
						var filename = result.data.filename;
						var listName = $("#listName").val();

						location.href = "{{ route('foso.marketing.list.create.html') }}?uid="+_uniqueID+"&name="+listName+"&file="+filename;
					}
				});
			}
		});

		$("#createButton").click(function()  {

			var basicRule = {
				rules:  {
					listName:  {minlength:3},
				},
				messages: {
					message:  {minlength:"Must consist of at least 3 characters"},
				}
			};

			var form = $("#form");
			form.validate(basicRule);

			result = form.valid();
			if (result == false)  {return;}

			var listName = $("#listName").val();

			var formData = new FormData();
			formData.append('_token', '{{ csrf_token() }}');
			formData.append('uniqueID', _uniqueID);
			formData.append('listName', listName);

			//  Form OK
			$.ajax({
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				url: '{{ route("foso.marketing.list.upload.api") }}',
				success: function (result)  {

					if (result.status < 0)  {
						alert(result.message);
						return;
					}

					alert("Marketing list has been updated");
					location.href = "{{ route('foso.marketing.list.html') }}";
				},
				error: function (XMLHttpRequest, textStatus, errorThrown)  {
					hideLoading();
					alert("Oops...\n#"+textStatus+": "+errorThrown);
				}
			});

		});

	});
</script>

@endsection
