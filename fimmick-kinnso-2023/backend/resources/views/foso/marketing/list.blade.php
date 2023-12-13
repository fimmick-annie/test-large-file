@extends('foso.layouts.default')

@section('page_title', 'Marketing List')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.marketing.list.html") }}'>Marketing</a></li>
<li><i class='fa fa-angle-right'></i> Marketing List</li>
@endsection

@section('content')

<div class="card" id="inputCard">
	<div class='card-body'>
		<div class="row">
			<div class="col-sm-12">
				Default showing record from below day range.
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

		<div class="row">
			<div class="col-sm-12">
				<div class="table-responsive">

					<table class="table table-bordered table-hover" data-name="dataTable" id="dataTable">
						<thead><tr>
							<th>ID</th>
							<th>Create At</th>
							<th>Modify At</th>
							<th>List Name</th>
							<th>Mobile</th>
							<th>Username</th>
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
						</tr></tfoot>
					</table>

				</div>
			</div>
		</div>

		<button type="button" class="btn btn-success" id="createButton">New</button>
	</div>
</div><br>

<script type="text/javascript" src="{{ asset('assets/DataTables/datatables.min.js') }}?v=1"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script type="text/javascript" src="{{ asset('js/common.js') }}?v=1"></script>

<script>
	var _table = null;
	var _baseURL = "";

	function reloadWithDateRange()  {

		var text = $("#text").val();
		var url = "?filter="+text;

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
			pageLength: 5,
			buttons: ["csv", "excel", "copy"],
			order: [[0, "desc"]],
			dom: "Bfrtip",

			ajax: {
				url: '{{ route("foso.marketing.list.search.api") }}',
				data:  function(data)  {
					data.from = $("#from_date").val();
					data.to = $("#to_date").val();
				}
			},

			columnDefs: [
				{targets:0, visible:false},
				{targets:2, visible:false},
				{targets:4, width:"100px"},
			],
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
			var mobile = data[4];
			location.href = '{{ route("foso.members.search.html") }}?mobile='+mobile;
		});

		$("#from_date_update").click(function()  {reloadWithDateRange();});
		$("#to_date_update").click(function()  {reloadWithDateRange();});

		$("#createButton").click(function()  {
			location.href = '{{ route("foso.marketing.list.create.html") }}';
		});
	});
</script>

@endsection
