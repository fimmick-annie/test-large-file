@extends('foso.layouts.default')

@section('page_title', 'Daily Question Report')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.dailyquestion.list.html") }}'>Daily Question</a></li>
<li><i class='fa fa-angle-right'></i> Report</li>
@endsection

@section('content')

<div class="card">
	<div class='card-body'>
		<div class="row">
			<div class="col-sm-12">
				<div class="table-responsive">

					<table class="table table-bordered table-hover" data-name="dataTable" id="dataTable">
						<thead>
							<tr>
								<th>ID</th>
								<th>Answer Date</th>
								<th>Mobile</th>
								<th>Question ID</th>
								<th>Answer</th>
								<th>Label</th>
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
			pageLength: 5,
			buttons: ["csv", "excel", "copy"],
			order: [
				[0, "desc"]
			],
			dom: "Bfrtip",
			ajax: {
				url: '{{ route("foso.dailyquestion.report.api") }}',
			},
			columnDefs: [{
				targets: 0,
				width: "100px",
			}],
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
