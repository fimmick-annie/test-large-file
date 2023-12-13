@extends('foso.layouts.default')

@section('page_title', 'Daily Question List')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.dailyquestion.list.html") }}'>Daily Question</a></li>
<li><i class='fa fa-angle-right'></i> List</li>
@endsection

@section('content')

<div class="card">
	<div class='card-body'>
		<div class="row">
			<div class="col-sm-12">
				<div class="table-responsive">

					<table class="table table-bordered table-hover" data-name="dataTable" id="dataTable">
						<thead><tr>
							<th>ID</th>
							<th>Layer</th>
							<th>Question</th>
							<th>Started At</th>
							<th>Ended At</th>
							<th>Point</th>
						</tr></thead>
						<tfoot><tr>
							<th>Search</th>
							<th>Search</th>
							<th>Search</th>
							<th>Search</th>
							<th>Search</th>
							<th>Search</th>
						</tr></tfoot>
					</table>
					<small>Remarks: This <span class="fimmick_dimmedRow">row style</span> mean the question is not in used (weight is 0).</small>
				</div>
			</div>
		</div>
		<button type="button" class="btn btn-success" id="createButton">New</button>
	</div>

</div><br>

<div class="card">
	<div class='card-body'>
		<div class="col-sm-12">
			<h4 class="text-black">Daily-question CSV File Upload</h4>
			<p>Content in CSV file will replace the existed question from first question, and insert new question after the list of existed question.<br> 
			<u>If you want to add muilt lable tags, please use "|" to separate the tags.</u></p>
			<div class="upload-area {{ $errors->has('daily_question') ? 'error' : '' }}">
				<input type="file" class="dropify" name="daily_question" data-default-file="daily_question.csv" data-show-remove="false"  />
				<small class="form-text small-text-color">
					Please use <a href="{{ asset('assets/foso/daily_question.csv') }}?v=1" target="_blank">this CSV structure</a>.
				</small>
			</div>
		</div>
	</div>
</div>
<br>

<script type="text/javascript" src="{{ asset('assets/DataTables/datatables.min.js') }}?v=1"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="{{ asset('js/common.js') }}?v=1"></script>

<link rel="stylesheet" href="{{asset('assets/vendor/adminkit/plugins/dropify/dropify.min.css')}}" />
<script src="{{asset('assets/vendor/adminkit/plugins/dropify/dropify.min.js')}}"></script>

<script>
	var _table = null;
	$(document).ready(function()  {

		//  When leave page, show loading
		window.onbeforeunload = function(e)  {
			showLoading();
		};

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
			pageLength: 25,
			buttons: ["csv", "excel", "copy"],
			order: [
				[0, "desc"]
			],
			dom: "Bfrtip",
			ajax: {
				url: '{{ route("foso.dailyquestion.list.api") }}',
			},
			columnDefs: [{
				targets: 0,
				width: "100px",
			}, {
				targets: 1,
				visible: false,
			}, {
				targets: 2,
				render: function (data, type, row, meta)  {
					if (row[1] == 1)  {return data;}
					return "<span class='badge bg-info text-light'>Follow-up</span> "+data;
				},
			}, {
				targets: 3,
				visible: false,
			}],

			createdRow: function(row, data, dataIndex)  {
				var weight = data[6];
				if (weight == 0)  {
					$(row).addClass("fimmick_dimmedRow");
				}
			},
		});

		_table.columns().every(function()  {
			var that = this;
			$('input', this.footer()).on('keyup change clear', function()  {
				if (that.search() !== this.value)  {
					that
						.search(this.value)
						.draw();
				}
			});
		});

		//  When click on a row
		$("#dataTable tbody").on("click", "tr", function()  {
			var table = $("#dataTable").DataTable();
			var data = table.row(this).data();
			var index = data.length-1;
			var id = data[0];
			location.href = "{{ route('foso.dailyquestion.list.html') }}/"+id;
		});

		$("#createButton").click(function()  {
			location.href = '{{ route("foso.dailyquestion.details.html", ["question_id"=>"0"]) }}';
		});

	});

	$('.dropify').dropify();

	$('.dropify').on('change', function (event)  {
		var fileObject = $(event.target).prop('files');

		if (fileObject[0] != undefined) {

			var file = fileObject[0];

			var defaultFile    = $(event.target).data('default-file');
			var defaultFile2   = defaultFile.split('.');
			var checkExtension = defaultFile2[defaultFile2.length - 1];

			var fileExtension  = (file.name.split('.'));
			fileExtension      = fileExtension[fileExtension.length - 1];

			if (fileExtension != checkExtension) {
				// var dropify = $(event.target).data('dropify');
				// dropify.resetPreview();
				// dropify.setPreview(dropify.isImage(), defaultFile);
				alert('Please upload .' + checkExtension + ' file');
				return false;
			}

			if (! isNaN(file.size) && file.size > (10 * 1024 * 1024)) {
				alert('Please upload a file within 10 MB size.');
				return false;
			}

			var formdata = new FormData();
			formdata.append('_token', '{{ csrf_token() }}');
			formdata.append('filename', $(event.target).attr('name'));
			formdata.append('file', file);


			$.ajax({
				method: 'POST',
				url: '{{ route('foso.dailyquestion.question.upload') }}',
				data: formdata,
				dataType: 'json',
				processData: false,
				contentType: false,
				success: function(data, textStatus, jqXHR) {
					$(event.target).val('');
					// if (data.status == 'ok') {
					// 	alert('CSV is successfully uploaded.');
					// } else {
					alert(data.message);
					// }
					
					//  Reload if success
					location.href = "{{ route('foso.dailyquestion.list.html') }}";
				}
			});
		}

	});
</script>

@endsection
