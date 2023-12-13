@extends('foso.layouts.default')

@section('page_title', 'Receipt Upload List')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i> Receipt Upload</li>
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
							<th>Created At</th>
							<th>Updated At</th>
							<th>Offer ID</th>
							<th>Offer Tilte</th>
							<th>Member ID</th>
							<th>Member mobile</th>
							<th>Purchase At</th>
							<th>Purchase Amount</th>
							<th>Channel</th>
							<th>Receipt Number</th>
							<th>Receipt image</th>
							<th>Status</th>
							<th>Handle At</th>
							<th>Rejected reason</th>
                            <th>Handler</th>
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
							<th>Search</th>
							<th>Search</th>
						</tr></tfoot>
					</table>

					<small class="form-text small-text-color">
						<!-- <span class="fimmick_hightlightedRow">This color</span> represent request is need to handle (approve/reject)<br> -->
						<span class="fimmick_dimmedRow">This color</span> represent need already handled<br>
						&nbsp;
					</small>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="{{ asset('assets/DataTables/datatables.min.js') }}?v=1"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
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
				url: '{{ route("foso.receipthandle.json") }}',
				data:  function(data)  {
					console.log(data);
					data.from = $("#from_date").val();
					data.to = $("#to_date").val();
				}
			},

			columnDefs: [
				{targets:0, width:"30px"},
				{targets:1, width:"90px"},
				{targets:2, visible:false},
				{targets:3, visible:false},
				{targets:4, width:"90px"},
				{targets:5, visible:false},
				{targets:6, width:"90px"},
				{targets:7, width:"80px"},
				{targets:8, width:"100px"},
				{targets:9, visible:false},
				{targets:10, visible:false},
				{targets:11, visible:false},
				{targets:12, width:"80px"},
				{targets:13, width:"50px"},
				{targets:14, width:"50px"},
				{targets:15, width:"50px"},
			],
			
			createdRow: function(row, data, dataIndex)  {
				var status = data[12];
				// console.log(data[1]);

				if(status == 'approved' || status == 'rejected'){
					$(row).addClass("fimmick_dimmedRow"); //  status: approved
                }
			},
		});

		_table.columns().every(function()  {
			var that = this;
			$('input', this.footer()).on('keyup change clear', function()  {
				if (that.search() !== this.value){
					that.search(this.value).draw();
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

	});
</script>

@endsection
