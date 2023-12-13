@extends('foso.layouts.default')

@section('page_title', 'App User')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.app.user.html") }}'>App</a></li>
<li><i class='fa fa-angle-right'></i> User</li>
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
								<th>Create At</th>
								<th>Update At</th>
								<th>Name</th>
								<th>Email</th>
								<th>Roles</th>
								<th>Api Token</th>
								<th>Token Expiry At</th>
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
								<th>Search</th>
								<th>Search</th>

							</tr>
						</tfoot>
					</table>

				</div>
			</div>
		</div>
		<a class='btn btn-success mt-3' href='{{ route("foso.app.createuser.html")}}'>Create New User</a>
	</div>
</div><br>

<script type="text/javascript" src="{{ asset('assets/DataTables/datatables.min.js') }}?v=1"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3.min.js"></script> -->

<script type="text/javascript" src="{{ asset('js/common.js') }}?v=1"></script>

<script>
	var _table = null;

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
			url: '{{ route("foso.app.user.json") }}',
			dataSrc: '',
		},
		columns: [{
				data: 'id'
			},
			{
				data: 'created_at'
			},
			{
				data: 'updated_at',
			},
			{
				data: 'name',
			},
			{
				data: 'email',
			},
			{
				data: 'roles',
			},
			{
				data: 'api_token',
			},
			{
				data: 'token_expiry_at',
			}
		]
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

	$("#dataTable tbody").on('click', 'tr', function() {
		let data = _table.row(this).data();
		location.href = `user/${data['id']}`;
		return;
	})
</script>

@endsection