@extends('foso.layouts.default')

@section('page_title', 'Activity log')

@section('breadcrumb_train')
<!-- <li><i class='fa fa-angle-right'></i><a href='{{ route("foso.members.search.html") }}'>Members</a></li> -->
<li><i class='fa fa-angle-right'></i> Activity log</li>
@endsection

@section('content')

<div class="card">
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
						<thead>
							<tr>
								<th>ID</th>
								<th>Create At</th>
								<th>Type</th>
								<th>User</th>
								<th>Remark</th>
								<th>Reference URL</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>Search</th>
								<th>Search</th>
								<th>Search</th>
								<th>Search</th>
								<th>Search</th>
								<th>Search</th>
							</tr>
						</tfoot>
					</table>

				</div>
			</div>
		</div>
	</div>

</div><br>

<style>
	#selectStyle{
		border: 1px solid #666f73;
		font-size: 15px;
		color: #666f73;
		border-radius: 2px;
		height: 28px;
	}
</style>
<script type="text/javascript" src="{{ asset('assets/DataTables/datatables.min.js') }}?v=1"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="{{ asset('js/common.js') }}?v=1"></script>
<script>
	var _table = null;

	$(document).ready(function() {

		//  When leave page, show loading
		window.onbeforeunload = function(e) {
			showLoading();
		};

		$("#dataTable tfoot th").each(function() {
			var title = $(this).text();
			$(this).html('<input type="text" placeholder="' + title + '" style="width:100%;"/>');
		});

		_table = $("#dataTable").DataTable({
			info: true,
			paging: true,
			ordering: true,
			autoWidth: true,
			searching: true,
			lengthChange: false,
			pageLength: 20,
			buttons: ["csv", "excel", "copy"],
			order: [
				[0, "desc"]
			],
			dom: "Bfrtip",

			ajax: {
				url: '{{ route("foso.activitylog.list.json") }}', 
				data:  function(data)  {
					data.from = $("#from_date").val();
					data.to = $("#to_date").val();
				}
			},

			columnDefs: [
				{targets:0, width:"30px"},
				{targets:1, width:"130px"},
				{targets:2, width:"80px"},
				{targets:3, width:"100px"},
				// {targets:4, width:"150px"},
                {targets:5, visible:false},
			],

			createdRow: function(row, data, dataIndex)  {
				// var startAt = new Date(data[1]);
				// var endAt = new Date(data[2]);
				// var now = new Date();

				// //  Coming soon
				// if (startAt > now)  {$(row).addClass("fimmick_hightlightedRow");}

				// //  Expired
				// if (endAt < now)  {$(row).addClass("fimmick_dimmedRow");}

				// //  Out of quota
				// var quota = data[8];
				// var quotaIssued = data[9];
				// if (quota <= quotaIssued)  {
				// 	$(row).addClass("fimmick_warningRow");
				// }

			},

			initComplete: function () {
				this.api()
					.columns(2)
					.every(function () {
						var column = this;
						var select = $('<select id="selectStyle"><option value="">Select...</option></select>')
							.appendTo($(column.footer()).empty())
							.on('change', function () {
								var val = $.fn.dataTable.util.escapeRegex($(this).val());
	
								column.search(val ? '^' + val + '$' : '', true, false).draw();
							});
	
						column
							.data()
							.unique()
							.sort()
							.each(function (d, j) {
								select.append('<option value="' + d + '">' + d + '</option>');
							});
					});
        	},
			
		});

		_table.columns().every(function() {
			var that = this;
			$('input', this.footer()).on('keyup change clear', function() {
				if (that.search() !== this.value) {
					that
						.search(this.value)
						.draw();
				}
			});
		});
	});


</script>

@endsection