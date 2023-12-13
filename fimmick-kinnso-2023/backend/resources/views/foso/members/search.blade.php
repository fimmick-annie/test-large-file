@extends('foso.layouts.default')

@section('page_title', 'Members Search')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.members.search.html") }}'>Members</a></li>
<li><i class='fa fa-angle-right'></i> Search</li>
@endsection

@section('content')

<div class="card">
	<div class='card-body'>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="mobile">Mobile Number</label>
					<div class="input-group">
						<input class="form-control" type="text" id="mobile" name="mobile" placeholder="+85293101987" value="{{ $mobile }}">
						<div class="input-group-append">
							<button type="button" class="btn btn-primary" id="mobile_update">Search</button>
						</div>
					</div>
					<small class="form-text text-muted">Support partial search</small>
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
								<th>Modify At</th>
								<th>Mobile</th>
								<th>Username</th>
								<th>Opt-out At</th>
								<th>Mute Until</th>
								<th>Offer Involved</th>
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
	</div>

</div><br>

<div class="card">
	<div class='card-body'>
		<div class="row">
			<div class="col-sm-12">
				<h4>Point adjustment</h4>
				Content in CSV file will be added to record table directly and the point of memeber included would be re-calculated. <u></u>
				<div class="upload-area {{ $errors->has('point_file') ? 'error' : '' }}">
					<input type="file" class="dropify" id="point_file" name="point_file" data-allowed-file-extensions='["csv"]' data-default-file="point_adjustment.csv" />
				</div>
			</div>
		</div>
@error('point_file')
		<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
		<small class="form-text small-text-color">
			Please use <a href="{{ asset('assets/foso/point_adjustment.csv') }}?v=1" target="_blank">this CSV structure.</a><br><br>
			<u>Optional for transaction_type and description</u>, they would be set as below when not specified.<br>
			&#x2022; transaction_type = Admin<br>
			&#x2022; description = en : special mission , zh-HK : 特別任務<br>
		</small>
		<br>
	</div>
</div>

<script type="text/javascript" src="{{ asset('assets/DataTables/datatables.min.js') }}?v=1"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3.min.js"></script> -->
<script src="{{asset('assets/vendor/adminkit/plugins/dropify/dropify.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('assets/vendor/adminkit/plugins/dropify/dropify.min.css')}}" />

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
				url: '{{ route("foso.members.search.api") }}',
				data: function(data) {
					data.mobile = $("#mobile").val();
				}
			},

			columnDefs: [{
					targets: 0,
					width: "100px"
				},
				{
					targets: 2,
					visible: false
				},
			],
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



		function searchMobile() {
			var mobile = $("#mobile").val();
			var url = "?mobile=" + mobile;

			window.history.pushState(null, null, url);

			_table.ajax.reload(null, false);
		}

		$("#mobile_update").click(function() {
			searchMobile();
		});

		$("#mobile").keypress(function(event) {
			var keycode = (event.keyCode ? event.keyCode : event.which);
			if (keycode != '13') {
				return;
			}
			searchMobile();
		});

		$("#dataTable tbody").on('click', 'tr', function() {
			let data = _table.row(this).data();
			location.href = `members/${data[3]}`;
			return;
		})
	});

	$('.dropify').dropify();

	$('.dropify').on('change', function (event)  {

		var fileObject = $(event.target).prop('files');

		if (fileObject[0] != undefined) {

			var file = fileObject[0];

			// if (! isNaN(file.size) && file.size > (10 * 1024 * 1024)) {
			// 	alert('Please upload a file within 10 MB size.');
			// 	return false;
			// }

			var formdata = new FormData();
			formdata.append('_token', '{{ csrf_token() }}');
			formdata.append('filename', $(event.target).attr('name'));
			formdata.append('file', file);

			$.ajax({
				method: 'POST',
				url: '{{ route('foso.members.pointuploads.api') }}',
				data: formdata,
				dataType: 'json',
				processData: false,
				contentType: false,
				success: function(data, textStatus, jqXHR) {
					if (data.status > 0) {
						alert(data.message);
					} else {
						alert('failed to upload, please try again.');
					}
					
					//  Reload if success
					location.href = '{{ route("foso.members.search.html") }}'; 
				}
			});

		}
	});
</script>

@endsection