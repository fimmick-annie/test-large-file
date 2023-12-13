@extends('foso.layouts.default')

@section('page_title', 'Campaign Offer #'.$offer->id.' Quotas')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.campaigns.html") }}'>Campaigns</a></li>
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.campaigns.offer.html") }}'>Offer</a></li>
<li><i class='fa fa-angle-right'></i> Quotas</li>
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

				<p>This page allow you to manage quotas for specific stores and periods.</p>

				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="from_date">From Date</label>
							<div class="input-group">
								<input class="form-control" type="date" id="from_date" name="from_date" value="{{ $fromDate }}">
								<div class="input-group-append">
									<button type="button" class="btn btn-primary" id="from_date_update">Update</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label for="to_date">To Date</label>
							<div class="input-group">
								<input class="form-control" type="date" id="to_date" name="to_date" value="{{ $toDate }}">
								<div class="input-group-append">
									<button type="button" class="btn btn-primary" id="to_date_update">Update</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class='row'>
					<div class=' col-sm-12'>
						<div class="table-responsive">

							<table class="table table-bordered table-hover" data-name="cool-table" id="dataTable">
								<thead><tr>
									<th>Create At</th>
									<th>Create By</th>
									<th>Update At</th>
									<th>Update By</th>
									<th>Offer ID</th>
									<th>Start At</th>
									<th>End At</th>
									<th>Store Code</th>
									<th>Ordering</th>
									<th>Quota</th>
									<th>Quota Issued</th>
									<th>Store Name</th>
									<th>Store Address</th>
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
						<h4 class="text-black">Quota CSV File Upload</h4>
						<p>Content in CSV file will replace record on server side if same
						store code and same period.  <u>If you want to remove store, please
						include the store with same start date and end date but quota is
						zero.</u></p>
						<div class="upload-area {{ $errors->has('quota_file') ? 'error' : '' }}">
							<input type="file" class="dropify" name="quota_file" data-default-file="quotas.csv" />
						</div>
					</div>
				</div>
@error('quota_file')
				<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
				<small class="form-text small-text-color">
					Please use <a href="{{ asset('assets/foso/quota.csv') }}?v=1" target="_blank">this CSV structure</a>.
					<br><font color='red'>CAUTION:</font> Date time format must be "<b><u>YYYY-MM-DD HH:II:SS</u></b>".  For example: "2020-12-25 21:22:23"
				</small>
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

	function reloadWithDateRange()  {

		var fromDate = $("#from_date").val();
		var toDate = $("#to_date").val();

		var url = "?from="+fromDate+"&to="+toDate;

		window.history.pushState(null, null, url);

		_table.ajax.reload(null, false);
	}

	$(document).ready(function()  {

		//  When leave page, show loading
		window.onbeforeunload = function(e)  {showLoading();};

		$("#dataTable tfoot th").each(function()  {
			var title = $(this).text();
			$(this).html('<input type="text" placeholder="'+title+'" style="width:100%;"/>');
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
				url: '{{ route("foso.campaigns.offer.quotas.json", ["offer_code"=>$offerCode]) }}',
				data:  function(data)  {
					data.from = $("#from_date").val();
					data.to = $("#to_date").val();
				}
			},

			columnDefs: [
				{targets:0, visible:false},
				{targets:1, visible:false},
				{targets:2, visible:false},
				{targets:3, visible:false},
				{targets:8, visible:false},
				{targets:12, visible:false},
			],

// 			createdRow: function(row, data, dataIndex)  {
// 				var quota = data[10];
// 				var quotaIssued = data[11];
// 				if (quota <= quotaIssued)  {
// 					$(row).addClass("fimmick_deletedRow");
// 				}
// 			},
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

		//  When click on a row
// 		$("#dataTable tbody").on("click", "tr", function()  {
// 			var table = $("#dataTable").DataTable();
// 			var data = table.row(this).data();
// 			var uniqueCode = data[4];
// 			location.href = '{{ route("foso.campaigns.offer.coupons.html", ["offer_code"=>$offerCode]) }}/'+uniqueCode;
// 		});

		$("#from_date_update").click(function()  {reloadWithDateRange();});
		$("#to_date_update").click(function()  {reloadWithDateRange();});

		$('.dropify').dropify();
		$('.dropify').on('change', function (event)  {

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
					url: '{{ route("foso.campaigns.offer.quotas.upload.json", ["offer_code"=>$offerCode]) }}',
					data: formdata,
					dataType: 'json',
					processData: false,
					contentType: false,
					success: function(result, status, jqXHR)  {
						$(event.target).val('');

						if (result.status >= 0)  {
							location.href = "{{ route('foso.campaigns.offer.quotas.confirm.html', ['offer_code'=>$offerCode]) }}?uniqid="+result.data.uniqid;
							return;
						}

						//  Error
						alert(result.message);
					}
				});
			}
		});

	});
</script>

@endsection
