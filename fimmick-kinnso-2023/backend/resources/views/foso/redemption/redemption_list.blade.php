@extends('foso.layouts.default')

@section('page_title', 'Redemption List')

@section('breadcrumb_train')
<!-- <li><i class='fa fa-angle-right'></i><a href='{{ route("foso.campaigns.html") }}'>Campaigns</a></li> -->
<li><i class='fa fa-angle-right'></i> Redemption</li>
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
							<th>Ordering</th>
							<th>Code Type</th>
                            <th>Filename</th>
							<th>Title</th>
							<th>Subtitle</th>
							<th>Quota</th>
							<th>Quota_issued</th>
							<th>Requied_issued</th>
							<th>Details</th>
							<th>Void Details</th>
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

					<small class="form-text small-text-color">
						<span class="fimmick_hightlightedRow">This color</span> represent redemption coming soon<br>
						<span class="fimmick_warningRow">This color</span> represent out of quota<br>
						<span class="fimmick_dimmedRow">This color</span> represent redemption is expired<br>
						&nbsp;
					</small>

				</div>
			</div>
		</div>

		<button type="button" class="btn btn-success" id="createButton">New</button>
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

		window.history.pushState(null, null, url);  //insert the date to url
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
				url: '{{ route("foso.redemption.json") }}', 
				data:  function(data)  {
					data.from = $("#from_date").val();
					data.to = $("#to_date").val();
				}
			},

			columnDefs: [
				{targets:0, width:"30px"},
				{targets:1, width:"80px"},
				{targets:2, width:"80px"},
				{targets:3, width:"50px"},
				{targets:4, width:"100px"},
                {targets:5, visible:false},
				{targets:[6,7], width:"230px"},
				{targets:8, width:"30px"},
				{targets:9, width:"30px"},
				{targets:10, visible:false},
				{targets:11, visible:false},
				{targets:12, visible:false},
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
				var quota = data[8];
				var quotaIssued = data[9];
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
			location.href = '{{ route("foso.redemption.settings.html",["id"=>"0"])}}';
		});

    });
    

</script>

@endsection
