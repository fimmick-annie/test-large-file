@extends('foso.layouts.default')

@section('page_title', 'Campaign Offer Folder - Collation')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.campaigns.html") }}'>Campaigns</a></li>
<li><i class='fa fa-angle-right'></i> Offer folder</li>
@endsection

@section('content')

<div class='card'>
	<div class='card-body'>

		<div class='row'>
			<div class=' col-sm-12'>
				<div class="table-responsive">
					<table class="table table-bordered table-hover" data-name="cool-table" id="dataTable">
						<thead><tr>
							<th>Offer Name</th>
							<th>Status</th>
							<th>Delete Button</th>
						</tr></thead>
						<tfoot><tr>
							<th>Search</th>
							<th>Search</th>
							<th>Search</th>
						</tr></tfoot>
					</table>

					<small class="form-text small-text-color">
						<span class="fimmick_warningRow">This color</span> represent the offer file is in server but no record in DB<br>
						<span class="fimmick_dimmedRow">This color</span> represent offer record exist but no file in server<br>
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

<link rel="stylesheet" href="{{asset('assets/vendor/adminkit/plugins/dropify/dropify.min.css')}}" />
<script src="{{asset('assets/vendor/adminkit/plugins/dropify/dropify.min.js')}}"></script>
<script src="/js/animatedBtn.js"></script>

<script>
	var _table = null;

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
			dom: "Pfrtip",

			ajax: {
				url: '{{ route("foso.campaigns.offercollation.json") }}',
			},

			columnDefs: [
				{targets:0, width:"200px"},
				{targets:2, 
					render: function (data, type, row, meta)  {
						if (row[2] == '1'){return '<div id="'+row[0]+'" class="deletebtnServer text-white btn btn-block" >Delete in Server</div>';}
						if (row[2] == '2'){return '<div id="'+row[0]+'" class="deletebtnList text-white btn btn-block" >Delete in record</div>';}
						return "";
					}, 
				},
			],

			createdRow: function(row, data, dataIndex)  {
				var status = data[2];

				if (status == '2'){$(row).addClass("fimmick_dimmedRow");}
				if (status == '1'){$(row).addClass("fimmick_warningRow");}
				
			},

			drawCallback: function(){

				// delete button FOR redundant offer file in server --------------------------------------------
				const deleteOfferFileWithoutRecord = async (e) => {
					showLoading();

					var name =  e.target.id;
					await $.ajax({
						type: "POST",
						data: {name:name},
						dataType: "json",
						url: '{{ route("foso.campaigns.offercollation.redundantfile.json") }}',
						success: function (result)  {
							alert(result.message);
							location.reload();
						},
						error: function (XMLHttpRequest, textStatus, errorThrown)  {
							hideLoading();
							alert("Oops...\n#"+textStatus+": "+errorThrown);
						}
					});
				}
				const deletebtnServer = AnimatedBtn({
					domClass: "deletebtnServer",
					transitionTime: 2000,
					yourFunction: deleteOfferFileWithoutRecord,
					coverColor: 'rgba(128, 40, 40, 1)',
					baseColor: 'rgba(221, 75, 57, 1)',
				});

				// delete button FOR redundant offer record in database table --------------------------------------------
				const deleteOfferRecordWithoutFile = async (e) => {
					showLoading();

					var name =  e.target.id;
					await $.ajax({
						type: "POST",
						data: {name:name},
						dataType: "json",
						url: '{{ route("foso.campaigns.offercollation.redundantrecord.json")}}',
						success: function (result)  {
							alert(result.message);
							location.reload();
						},
						error: function (XMLHttpRequest, textStatus, errorThrown)  {
							hideLoading();
							alert("Oops...\n#"+textStatus+": "+errorThrown);
						}
					});
				}
				const deletebtnList = AnimatedBtn({
					domClass: "deletebtnList",
					transitionTime: 2000,
					yourFunction: deleteOfferRecordWithoutFile,
					coverColor: 'rgba(80, 80, 80, 1)',
					baseColor: 'rgba(163, 163, 163, 1)',
				});
			}
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

	});
</script>


@endsection
