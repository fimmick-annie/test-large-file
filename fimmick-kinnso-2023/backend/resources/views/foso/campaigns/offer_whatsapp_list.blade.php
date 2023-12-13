@extends('foso.layouts.default')

@section('page_title', 'Campaign Offer #'.$offer->id.' WhatsApp')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.campaigns.html") }}'>Campaigns</a></li>
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.campaigns.offer.html") }}'>Offer</a></li>
<li><i class='fa fa-angle-right'></i> WhatsApp</li>
@endsection

@section('content')

<ul class="nav nav-tabs" role="tablist">
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.settings.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-gear"></i></span> <span class="hidden-xs-down">Settings</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.resources.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-file-image-o"></i></span> <span class="hidden-xs-down">Resources</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.rules.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-cubes"></i></span> <span class="hidden-xs-down">Rules</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.coupons.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-tags"></i></span> <span class="hidden-xs-down">Coupons</span></a> </li>
@if ($offer->coupon_type == "randomly-generated")
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.quotas.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-shopping-cart"></i></span> <span class="hidden-xs-down">Quotas</span></a> </li>
@else
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.couponpool.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-shopping-cart"></i></span> <span class="hidden-xs-down">Coupon Pool</span></a> </li>
@endif
	<li class="nav-item"> <a class="nav-link active" href='{{ route("foso.campaigns.offer.whatsapp.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-comment"></i></span> <span class="hidden-xs-down">WhatsApp</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.customerjourney.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-plane"></i></span> <span class="hidden-xs-down">Journey</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.channel.sample.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-tablet"></i></span> <span class="hidden-xs-down">Channel</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.ini.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-file-text-o"></i></span> <span class="hidden-xs-down">.ini</span></a> </li>
</ul>

<div class="card">
	<div class='card-body'>
		<div class="row">
			<div class="col-sm-12">
				Showing inbound and outbound WhatsApp message of this offer.  Default date range is from last 7 days.
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
							<th>Action</th>
							<th>Create At</th>
							<th><span title="Coupon ID">CID</span></th>
							<th>Mobile</th>
							<th>Message</th>
							<th>Schedule At</th>
							<th>Cancel At</th>
							<th>Send At</th>
							<th>Status</th>
							<th>Response</th>
							<th>Delivery Receipt</th>
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
						</tr></tfoot>
					</table>

					<small class="form-text small-text-color">
						<span class="fimmick_hightlightedRow">This color</span> represent future message<br>
						<span class="fimmick_dimmedRow">This color</span> represent canceled message<br>
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
				url: '{{ route("foso.campaigns.offer.whatsapp.json", ["offer_code"=>$offerCode]) }}',
				data:  function(data)  {
					data.from = $("#from_date").val();
					data.to = $("#to_date").val();
				}
			},

			columnDefs: [
				{targets:0, data:null, defaultContent:
					"<button class='btn btn-sm btn-dark resendButton'><i class='fa fa-envelope-o'></i> Resend</button><br>"+
					"<button class='btn btn-sm btn-danger cancelButton'><i class='fa fa-trash-o'></i> Cancel</button>"
				},
				{targets:1, visible:false},
				{targets:9, visible:false},
				{targets:10, visible:false},
			],

			createdRow: function(row, data, dataIndex)  {
				var cancelAt = data[6];
				var sendAt = data[7];

				if (cancelAt != null)  {$(row).addClass("fimmick_dimmedRow");}  else  {
					if (sendAt == null)  {$(row).addClass("fimmick_hightlightedRow");}
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

		//  Action buttons
		$("#dataTable tbody").on("click", "button.resendButton", function()  {
			var data = _table.row($(this).parents("tr")).data();
			var messageID = data[0];
			var parameters = {
				id:messageID,
				api_token:"{{ auth()->user()->api_token }}",
			};

			var url = "{{ route('foso.campaigns.whatsapp.queue.resend.api') }}";
			$.ajax({
				type: "POST",
				dataType: "json",
				data: parameters,
				url: url,
				success: function (result)  {
					if (result.status >= 0)  {
						_table.ajax.reload(null, false);
					}

					alert(result.message);
				},
				error: function (XMLHttpRequest, textStatus, errorThrown)  {
					alert("Oops...\n#"+textStatus+": "+errorThrown);
				}
			});
			return false;
		});

		$("#dataTable tbody").on("click", "button.cancelButton", function()  {
			var data = _table.row($(this).parents("tr")).data();
			var messageID = data[0];
			var parameters = {
				id:messageID,
				api_token:"{{ auth()->user()->api_token }}",
			};

			var url = "{{ route('foso.campaigns.whatsapp.queue.cancel.api') }}";
			$.ajax({
				type: "POST",
				dataType: "json",
				data: parameters,
				url: url,
				success: function (result)  {
					if (result.status >= 0)  {
						_table.ajax.reload(null, false);
					}

					alert(result.message);
				},
				error: function (XMLHttpRequest, textStatus, errorThrown)  {
					alert("Oops...\n#"+textStatus+": "+errorThrown);
				}
			});
			return false;
		});

		$("#from_date_update").click(function()  {reloadWithDateRange();});
		$("#to_date_update").click(function()  {reloadWithDateRange();});
	});
</script>

@endsection
