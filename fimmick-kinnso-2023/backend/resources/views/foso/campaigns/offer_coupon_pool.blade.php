@extends('foso.layouts.default')

@section('page_title', 'Campaign Offer #'.$offer->id.' Coupon Pool')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.campaigns.html") }}'>Campaigns</a></li>
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.campaigns.offer.html") }}'>Offer</a></li>
<li><i class='fa fa-angle-right'></i> Coupon Pool</li>
@endsection

@section('content')

<ul class="nav nav-tabs" role="tablist">
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.settings.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-gear"></i></span> <span class="hidden-xs-down">Settings</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.resources.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-file-image-o"></i></span> <span class="hidden-xs-down">Resources</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.rules.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-cubes"></i></span> <span class="hidden-xs-down">Rules</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.coupons.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-tags"></i></span> <span class="hidden-xs-down">Coupons</span></a> </li>
	@if ($offer->coupon_type == "randomly-generated")
	<li class="nav-item"> <a class="nav-link active" href='{{ route("foso.campaigns.offer.quotas.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-shopping-cart"></i></span> <span class="hidden-xs-down">Quotas</span></a> </li>
	@else
	<li class="nav-item"> <a class="nav-link active" href='{{ route("foso.campaigns.offer.couponpool.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-shopping-cart"></i></span> <span class="hidden-xs-down">Coupon Pool</span></a> </li>
	@endif
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.whatsapp.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-comment"></i></span> <span class="hidden-xs-down">WhatsApp</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.customerjourney.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-plane"></i></span> <span class="hidden-xs-down">Journey</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.channel.sample.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-tablet"></i></span> <span class="hidden-xs-down">Channel</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.ini.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-file-text-o"></i></span> <span class="hidden-xs-down">.ini</span></a> </li>
</ul>

<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class='card-body'>

				<p>This page allows you to import and manage pre-generated coupons.</p>

				<h5>Summary of Usage</h5>

				<div>
@foreach ($usage as $usage_per_store_code)
					<span
						class="col-lg-4 d-inline-block text-truncate border rounded h-25 py-2 text-center
						{{ "text-primary border-primary" }}
						{{ ($usage_per_store_code->used / $usage_per_store_code->total >= 0.5) ? "text-warning border-warning" : "" }}
						{{ ($usage_per_store_code->used / $usage_per_store_code->total >= 0.8) ? "text-danger border-danger" : "" }}
						"
						title="{{ $usage_per_store_code->store_code }} ({{ $usage_per_store_code->used }}/{{ $usage_per_store_code->total }}) {{ number_format($usage_per_store_code->used / $usage_per_store_code->total * 100, 2, '.', '') }}%"
						disabled
					>
						{{ $usage_per_store_code->store_code }} ({{ $usage_per_store_code->used }}/{{ $usage_per_store_code->total }}) {{ number_format($usage_per_store_code->used / $usage_per_store_code->total * 100, 2, '.', '') }}%
					</span>
@endforeach
				</div>

				<p></p>

				<div class='row'>
					<div class=' col-sm-12'>
						<div class="table-responsive">

							<table class="table table-bordered table-hover" data-name="cool-table" id="dataTable">
								<thead><tr>
									<th>Created At</th>
									<th>Created By</th>
									<th>Updated At</th>
									<th>Updated By</th>
									<th>Store Code</th>
									<th>Store Code</th>
									<th>Unique Code</th>
									<th>Mobile</th>
									<th>Unique Name</th>
									<th>Unique Name Link</th>
									<th>A (32)</th>
									<th>B (64)</th>
									<th>C (Text)</th>
								</tr></thead>
								<tfoot><tr>
									<th>Search</th>
									<th>Search</th>
									<th>Search</th>
									<th>Search</th>
									<th>Search</th>
									<th>Search</th>
									<th>Search</th>
									<th>Search</th>
									<th>Search</th>
									<th>Search</th>
									<th>Search</th>
									<th>Search</th>
									<th>Search</th>
								</tr></tfoot>
							</table>

							<form id="form" name="form">
								@csrf
								<button type="button" class="btn btn-danger" id="clearButton">Clear whitelisted records</button>
								<small class="form-text small-text-color">Whitelist could be found in .ini</small>
							</form>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row m-t-3">
	<div class="col-sm-12">
		<div class="card">
			<div class='card-body'>
				<div class="row">
					<div class="col-sm-12">
						<h3 class="text-black">Step 1: Coupon Codes</h3>

						<p></p>
						<h4>Upload CSV of coupon codes</h4>
						<p>Content in CSV file will replace record on server side if store
							code and unique code are the same.  <u>If you want to remove a coupon, please
						rename store code to something else.</u></p>
						<div class="upload-area {{ $errors->has('quota_file') ? 'error' : '' }}">
							<input type="file" class="dropify" id="quota_file" name="quota_file" data-default-file="coupon_pool.csv" />
						</div>
					</div>
				</div>
@error('quota_file')
				<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
				<small class="form-text small-text-color">
					Please use <a href="{{ asset('assets/foso/coupon_pool.csv') }}?v=1" target="_blank">this CSV structure</a>.
					<br><font color='red'>CAUTION:</font> Date time format must be "<b><u>YYYY-MM-DD HH:II:SS</u></b>".  For example: "{{ date('Y-m-d H:i:s') }}"
				</small>

<!--
				<p></p>
				<div class="row">
					<div class="col-sm-12">
						<h4>~~OR~~</h4>
					</div>
				</div>
				<p></p>
				<div class="row">
					<div class="col-sm-12">
						<h4>Generate coupon codes</h4>
					</div>
					<div class="col-sm-4">
						<fieldset class="form-group">
							<label for="couponCodeStore">Store Code</label>
							<input class="form-control" type="text" value="default" id="couponCodeStore" name="couponCodeStore" placeholder="default" maxlength="16">
							<small class="form-text small-text-color">Match with issue coupon node in journey</small>
						</fieldset>
					</div>
					<div class="col-sm-4">
						<fieldset class="form-group">
							<label for="couponCodePrefix">Unique Code Prefix</label>
							<input class="form-control" type="text" value="" id="couponCodePrefix" name="couponCodePrefix" placeholder="UAT-" maxlength="10">
							<small class="form-text small-text-color">Max. 10 bytes, leave it empty if no prefix</small>
						</fieldset>
					</div>
					<div class="col-sm-4">
						<fieldset class="form-group">
							<label for="couponCodeCount">Number of Codes</label>
							<input class="form-control" type="number" value="1000" id="couponCodeCount" name="couponCodeCount" placeholder="1000">
							<small class="form-text small-text-color">Max. 10000 codes</small>
						</fieldset>
					</div>
					<div class="col-sm-12">
						<button type="button" class="btn btn-warning btn-block" id="couponCodeGenerateButton">Generate and Import</button>
					</div>
				</div>
 -->

				<p></p>

				<div class="row">
					<div class="col-sm-12">
						<h3 class="text-black">Step 2: Upload Coupon Images</h3>
						<p>Please name the images according to their coupon code (e.g. {coupon code}.png). Images will only be associated with
							the coupon code if they have matching names. Images that cannot be identified will be ignored. <u>Please do NOT zip the images.</u>
						</p>
						<div class="upload-area">
							<input type="file" class="dropify" id="coupon_images" name="coupon_images" data-default-file="COUPON_CODE1.png, COUPON_CODE2.png, ..." multiple
								data-allowed-file-extensions="png"
							/>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="{{ asset('assets/DataTables/datatables.min.js') }}?v=1"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<link rel="stylesheet" href="{{asset('assets/vendor/adminkit/plugins/dropify/dropify.min.css')}}" />
<script src="{{asset('assets/vendor/adminkit/plugins/dropify/dropify.min.js')}}"></script>

<script type="text/javascript" src="{{ asset('js/common.js') }}?v=1"></script>

<script>
	var _table = null;

	$(document).ready(function()  {

		//  When leave page, show loading
		window.onbeforeunload = function(e)  {showLoading();};

		$("#dataTable tfoot th").each(function()  {
			var title = $(this).text();
			$(this).html('<input type="text" placeholder="'+title+'" style="width:100%;"/>');
		});

		$("#clearButton").click(function()  {

			var formData = $("#form").serialize();

			showLoading();
			$.ajax({
				type: "POST",
				data: formData,
				dataType: "json",
				url: '{{ route("foso.campaigns.offer.couponpool.clearwhitelisted.json", ["offer_code"=>$offerCode]) }}',
				success: function (result)  {
					alert(result.message);
					location.reload();
				},
				error: function (XMLHttpRequest, textStatus, errorThrown)  {
					hideLoading();
					alert("Oops...\n#"+textStatus+": "+errorThrown);
				}
			});
		});


		_table = $("#dataTable").DataTable({
			info: true,
			paging: true,
			ordering: true,
			autoWidth: true,
			searching: true,
			lengthChange: false,
			buttons: ["csv", "excel", "copy"],
			order: [[0, "desc"]],
			dom: "Bfrtip",

			ajax: {
				url: '{{ route("foso.campaigns.offer.couponpool.json", ["offer_code"=>$offerCode]) }}',
				data: null,
			},

			columnDefs: [
				{targets:0, visible:false},
				{targets:1, visible:false},
				{targets:2, visible:true},
				{targets:3, visible:false},
				{targets:4, visible:false},
				{targets:5, visible:true},
				{targets:6, visible:true},
				{targets:7, visible:true},
				{targets:8, visible:true},
				{targets:9, visible:false},
			],

		});

		$("#dataTable tbody").on("click", "tr", function()  {
			var table = $("#dataTable").DataTable();
			var data = table.row(this).data();
			var url = data[9];
			if (url != "") {window.open(url, "_blank");}
		});

		_table.columns().every(function()  {
			var that = this;
			$('input', this.footer()).on('keyup change clear', function()  {
				if (that.search() !== this.value)  {
					that
					.search( this.value )
					.draw();
				}
			});
		});

		_table.ajax.reload(null, false);

		$('.dropify').dropify();
		$('#quota_file').on('change', function (event)  {

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

				var formdata = new FormData();
				formdata.append('_token', '{{ csrf_token() }}');
				formdata.append('filename', $(event.target).attr('name'));
				formdata.append('file', file);

				$.ajax({
					method: 'POST',
					url: '{{ route("foso.campaigns.offer.couponpool.upload.json", ["offer_code"=>$offerCode]) }}',
					data: formdata,
					dataType: 'json',
					processData: false,
					contentType: false,
					success: function(result, status, jqXHR)  {
						$(event.target).val('');

						if (result.status >= 0)  {
							location.href = "{{ route('foso.campaigns.offer.couponpool.confirm.html', ['offer_code'=>$offerCode]) }}?uniqid="+result.data.uniqid;
							return;
						}

						//  Error
						alert(result.message);
					}
				});
			}
		});


		$('#coupon_images').on('change', function (event) {

			const MAX_NUMBER_OF_FILES_ALLOWED = 10;

			if (event.target.files.length > MAX_NUMBER_OF_FILES_ALLOWED) {
				alert(`Please upload less than ${MAX_NUMBER_OF_FILES_ALLOWED} files at once.`)
				location.reload();
				return;
			}

			filenameList = [];
			for (file of event.target.files) {
				filenameList.push(file.name);
			}

			$('.row').css("display", "none");

			$.ajax({
				method: 'POST',
				url: '{{ route("foso.campaigns.offer.couponpool.image.upload.confirm.json", ["offer_code"=>$offerCode]) }}',
				data: {
					filenameJSON: JSON.stringify(filenameList),
				},
				success: function(res) {

					if (res.status < 0) {
						alert (res.message);
						return;
					}

					var dataHTML = "";
					for (item of res?.dataArray) {
						dataHTML += `<tr>
							<td>${item?.code}</td>
							<td class="
								${item?.status=="not found" && "text-red"}
								${item?.status=="modify" && "text-blue"}
								${item?.status=="insert" && "text-green"}
							">
								${item?.status}
							</td>
						</tr>`
					}
					$('.content').append(`
						<div class="row m-t-3">
							<div class="col-sm-12">
								<div class="card">
									<div class='card-body'>
										<div class="row">
											<div class="col-sm-12">
												<h3>Image Upload Preview</h3>
											</div>
										</div>
										<table class="table table-bordered table-hover">
											<thead>
												<th>Coupon Code</th>
												<th>Action</th>
											</thead>
											<tdata>${dataHTML}</tdata>
										</table>

										<button type="button" class="btn btn-danger" onclick="confirmUploadImageBtnOnClick()">Confirm Upload</button>
									</div>
								</div>
							</div>
						</div>

					`);
				},
				error: function(err) {
					alert(err?.responseJSON?.message);
					location.reload();
				}
			})
		});

		$("#couponCodeGenerateButton").click(function()  {
		});

	});


	function confirmUploadImageBtnOnClick(event) {

		var _image_input = $('#coupon_images')[0];
		var files = _image_input?.files;
		var formData = new FormData();

		var count = 0;
		for (file of files) {
			formData.append('files['+count+']', file);
			count++;
		}

		$.ajax({
			method: "POST",
			url: '{{ route("foso.campaigns.offer.couponpool.image.upload.json", ["offer_code"=>$offerCode]) }}',
			data: formData,
			contentType: false,
    	processData: false,
			success: function(res) {
				location.reload();
			},

		});
	}
</script>

@endsection
