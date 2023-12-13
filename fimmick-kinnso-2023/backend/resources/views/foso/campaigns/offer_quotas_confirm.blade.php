@extends('foso.layouts.default')

@section('page_title', 'Campaign Offer #'.$offer->id.' Quotas Confirmation')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.campaigns.html") }}'>Campaigns</a></li>
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.campaigns.offer.html") }}'>Offer</a></li>
<li><i class='fa fa-angle-right'></i> Quotas</li>
@endsection

@section('content')

<ul class="nav nav-tabs" role="tablist">
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.settings.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-gear"></i></span> <span class="hidden-xs-down">Settings</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.resources.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-file-image-o"></i></span> <span class="hidden-xs-down">Resources</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.rules.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-cubes"></i></span> <span class="hidden-xs-down">Rules</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.coupons.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-tags"></i></span> <span class="hidden-xs-down">Coupons</span></a> </li>
	<li class="nav-item"> <a class="nav-link active" href='{{ route("foso.campaigns.offer.quotas.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-shopping-cart"></i></span> <span class="hidden-xs-down">Quotas</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.whatsapp.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-comment"></i></span> <span class="hidden-xs-down">WhatsApp</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.customerjourney.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-plane"></i></span> <span class="hidden-xs-down">Journey</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.channel.sample.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-tablet"></i></span> <span class="hidden-xs-down">Channel</span></a> </li>	
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.ini.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-file-text-o"></i></span> <span class="hidden-xs-down">.ini</span></a> </li>
</ul>

<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class='card-body'>

				<p>Please see if data correct and confirm import to database.</p>

				<div class='row'>
					<div class=' col-sm-12'>
						<div class="table-responsive">

							<table class="table table-bordered table-hover" data-name="cool-table" id="dataTable">
								<thead><tr>
									<th>Start At</th>
									<th>End At</th>
									<th>Store Code</th>
									<th>Ordering</th>
									<th>Quota</th>
									<th>Quota Issued</th>
									<th>Store Name</th>
									<th>Store Address</th>
									<th>Status</th>
								</tr></thead>
								<tbody>
@foreach($dataArray as $row)
									<tr>
										<td>{{ $row[0] }}</td>
										<td>{{ $row[1] }}</td>
										<td>{{ $row[2] }}</td>
										<td>{{ $row[3] }}</td>
										<td>{{ $row[4] }}</td>
										<td>{{ $row[7] }}</td>
										<td>{{ $row[5] }}</td>
										<td>{{ $row[6] }}</td>
										<td>{{ $row[8] }}</td>
									</tr>
@endforeach

								</tbody>
							</table>

							<small class="form-text small-text-color">
								<span class="existsRow">This color</span> represent existing records in database<br>
								<span class="errorRow">This color</span> represent (Quota < Issued, Invalid start, Invalid end) problem records in CSV
							</small>

						</div>
					</div>
				</div>

				<p>
					<div class=' col-sm-12'>
						<button type="button" class="btn btn-danger" id="importButton" {{ $confirmImportDisable }}>Confirm Import</button>
					</div>
				</p>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="{{ asset('assets/DataTables/datatables.min.js') }}?v=1"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script type="text/javascript" src="{{ asset('js/common.js') }}?v=1"></script>

<script>
	var _table;
	$(document).ready(function()  {

		//  When leave page, show loading
		window.onbeforeunload = function(e)  {showLoading();};

		_table = $("#dataTable").DataTable({
			info: true,
			paging: true,
			ordering: true,
			autoWidth: true,
			searching: true,
			lengthChange: false,

			columnDefs: [
				{targets:8, visible:false},
			],

			createdRow: function(row, data, dataIndex)  {
				var status = data[8];
				if (status == "error")  {$(row).addClass("errorRow");}
				if (status == "exists")  {$(row).addClass("existsRow");}
			},
		});

		$("#importButton").on('click', function()  {
			$.ajax({
				type: "POST",
				data: {_token:'{{csrf_token()}}'},
				dataType: "json",
				url: '{{ route("foso.campaigns.offer.quotas.confirm.json", ["offer_code"=>$offerCode]) }}?uniqid={{ $uniqid }}',
				success: function (result)  {

					switch (result.status)  {
						case 0:  {
							location.href = '{{ route("foso.campaigns.offer.quotas.html", ["offer_code"=>$offerCode]) }}';
							return;
						}

						default:  {
							alert(result.message);
						}  break;
					}
					if (result.status < 0)  {hideLoading();}
				},
				error: function (XMLHttpRequest, textStatus, errorThrown)  {
					hideLoading();
					alert("Oops...\n#"+textStatus+": "+errorThrown);
				}
			});
		});

	});
</script>

@endsection
