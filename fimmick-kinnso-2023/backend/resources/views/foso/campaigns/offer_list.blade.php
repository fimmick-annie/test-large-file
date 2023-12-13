@extends('foso.layouts.default')

@section('page_title', 'Campaign Offer List')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.campaigns.html") }}'>Campaigns</a></li>
<li><i class='fa fa-angle-right'></i> Offer</li>
@endsection

@section('content')

<div class='card'>
	<div class='card-body'>
		<div class="row">
			<div class="col-sm-12">
				Default showing record from 30 days before to 30 days later.
			</div>
		</div>
	</div>

	<div class='card-body'>
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
							<th>ID</th>
							<th>Start At</th>
							<th>End At</th>
							<th>Code</th>
							<th>Name</th>
							<th>Title</th>
							<th>Subtitle</th>
							<th>Template</th>
							<th>Code Type</th>
							<th>Channel Expiry</th>
							<th>Confirmation Method</th>
							<th>Quota</th>
							<th>Issued</th>
							<th>Viewed</th>
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
							<th>Search</th>
						</tr></tfoot>
					</table>

					<small class="form-text small-text-color">
						<span class="fimmick_hightlightedRow">This color</span> represent offer coming soon<br>
						<span class="fimmick_warningRow">This color</span> represent out of quota<br>
						<span class="fimmick_dimmedRow">This color</span> represent offer has ended<br>
						&nbsp;
					</small>

				</div>
			</div>
		</div>

		<br>
		<div class="row">
			<div class="col-sm-6">
			<button type="button" class="btn btn-success" id="createButton" style="width:100%;">Create from sketch</button>
			<br><br>
			</div>
			<div class="col-sm-6">
				<div class="upload-area {{ $errors->has('offerzip') ? 'error' : '' }}">
					<input type="file" class="dropify" id="offerzip" name="offerzip" data-default-file="offertemplate.zip" />
					<small class="form-text small-text-color">Please use this template<a href="{{ asset('assets/foso/offertemplate.zip') }}?v=1" target="_blank"> as zip file structure</a>.</small>
					<small class="form-text small-text-color">File name (filename.zip) = Offer Media Folder Name </small>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="{{ asset('assets/DataTables/datatables.min.js') }}?v=1"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script type="text/javascript" src="{{ asset('js/common.js') }}?v=1"></script>

<link rel="stylesheet" href="{{asset('assets/vendor/adminkit/plugins/dropify/dropify.min.css')}}" />
<script src="{{asset('assets/vendor/adminkit/plugins/dropify/dropify.min.js')}}"></script>


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
				url: '{{ route("foso.campaigns.offer.json") }}',
				data:  function(data)  {
					data.from = $("#from_date").val();
					data.to = $("#to_date").val();
				}
			},

			columnDefs: [
				{targets:0, width:"30px"},
				{targets:1, width:"100px"},
				{targets:2, width:"100px"},
				{targets:3, visible:false},
				{targets:4, visible:false},
				{targets:7, visible:false},
				{targets:8, visible:false},
				{targets:9, visible:false},
				{targets:10, visible:false},
				{targets:11, width:"50px"},
				{targets:12, width:"50px"},
				{targets:13, width:"50px"},
			],

			createdRow: function(row, data, dataIndex)  {
				var startAt = new Date(data[1]);
				var endAt = new Date(data[2]);
				var now = new Date();

				//  Coming soon
				if (startAt > now)  {$(row).addClass("fimmick_hightlightedRow");}

				//  Expired
				if (endAt < now)  {$(row).addClass("fimmick_dimmedRow");}

				//  Out of quota
				var quota = data[11];
				var quotaIssued = data[12];
				if (quota <= quotaIssued)  {
					$(row).addClass("fimmick_warningRow");
				}
			},

			
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
		$("#dataTable tbody").on("click", "tr", function()  {
			var table = $("#dataTable").DataTable();
			var data = table.row(this).data();
			var index = data.length-1;
			var url = data[index];
			location.href = url;
		});

		$("#from_date_update").click(function()  {reloadWithDateRange();});
		$("#to_date_update").click(function()  {reloadWithDateRange();});

		$("#createButton").click(function()  {
			location.href = '{{ route("foso.campaigns.offer.settings.html", ["offer_code"=>"new"]) }}';
		});

		// dropify -- for import zip offer file for creating new offer
		$('.dropify').dropify();
		$('#offerzip').on('change', function (event)  {

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
					url: '{{ route("foso.campaigns.offer.importoffer.api")}}',
					data: formData,
					dataType: 'json',
					processData: false,
					contentType: false,
					success: function(result, status, jqXHR)  {
						if (result.status < 0)  {
							//  Error
							alert(result.message);
							return;
						}else {
							location.href = result.url;
						}
					}
				});
			}
		});



	});
</script>

@endsection
