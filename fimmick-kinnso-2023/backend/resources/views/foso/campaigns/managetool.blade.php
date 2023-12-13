@extends('foso.layouts.default')

@section('page_title', 'Campaign Manage Tool')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.campaigns.html") }}'>Campaigns</a></li>
<li><i class='fa fa-angle-right'></i> Management Tool</li>
@endsection

@section('content')

<div class="card" id="inputCard">
	<div class="card-body">
		<div class="row">

			<div class="col-sm-12">
				You can search coupon record by user mobile number or referral code.
			</div>

			<div class="col-sm-6">
				<div class="form-group">
					<div class="input-group">
						<input class="form-control" type="text" id="text" name="text" placeholder="Mobile number or referral code here...">
						<div class="input-group-append">
							<button type="button" class="btn btn-primary" id="text_search">Search</button>
						</div>
					</div>
				</div>
			</div>

		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="table-responsive">

					<table class="table table-bordered table-hover" data-name="dataTable" id="dataTable">
						<thead><tr>
							<th>ID</th>
							<th>Create At</th>
							<th>Offer ID</th>
							<th>Title</th>
							<th>Unique Code</th>
							<th>Mobile</th>
							<th>Start At</th>
							<th>Use At</th>
							<th>Expiry At</th>
							<th>Form Data</th>
							<th>Referrer Code</th>
							<th>Referral Code</th>
							<th>Referral Data</th>
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

				</div>
			</div>
		</div>
	</div>
</div><br>

<div class="card" id="editCard">
	<div class="card-body">
		<div class="row">
			<div class="col-lg-12">
				<fieldset class="form-group">
					<label for="offerID">Offer ID</label>
					<input class="form-control" type="text" value="" id="offerID" name="offerID" disabled>
				</fieldset>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<fieldset class="form-group">
					<label for="offerID">Offer Title</label>
					<input class="form-control" type="text" value="" id="offerTitle" name="offerTitle" disabled>
				</fieldset>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4">
				<fieldset class="form-group">
					<label for="startAt">Start At</label>
					<input class="form-control" type="text" value="" id="startAt" name="startAt" disabled>
				</fieldset>
			</div>
			<div class="col-lg-4">
				<fieldset class="form-group">
					<label for="useAt">Use At</label>
					<input class="form-control" type="text" value="" id="useAt" name="useAt" disabled>
				</fieldset>
			</div>
			<div class="col-lg-4">
				<fieldset class="form-group">
					<label for="expiryAt">Expiry At</label>
					<input class="form-control" type="text" value="" id="expiryAt" name="expiryAt" disabled>
				</fieldset>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<fieldset class="form-group">
					<label for="mobile">Mobile Number</label>
					<input class="form-control" type="text" value="" id="mobile" name="mobile" disabled>
				</fieldset>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<fieldset class="form-group">
					<label for="couponURL">Coupon URL</label>
					<div class="input-group mb-3">
						<input class="form-control" type="text" value="" id="couponURL" name="couponURL" disabled>
						<div class="input-group-append">
							<button class="btn btn-primary" type="button" id="couponURLCopyButton">Copy</button>
						</div>
					</div>
				</fieldset>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<fieldset class="form-group">
					<label for="formData">Form Data</label>
					<textarea class="form-control" id="formData" name="formData" rows="4" disabled></textarea>
				</fieldset>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<fieldset class="form-group">
					<label for="referralData">Referral Data</label>
					<input class="form-control" type="text" value="" id="referralData" name="referralData" disabled>
				</fieldset>
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
	var _baseURL = "{{ $baseURL }}";

	function reloadWithDateRange()  {

		var text = $("#text").val();
		var url = "?filter="+text;

		window.history.pushState(null, null, url);

		_table.ajax.reload(null, false);
	}

	function copyTextToClipboard(copyText)  {
		var textArea = document.createElement("textarea");

		textArea.style.position = 'fixed';
		textArea.style.top = 0;
		textArea.style.left = 0;
		textArea.style.width = '2em';
		textArea.style.height = '2em';
		textArea.style.padding = 0;
		textArea.style.border = 'none';
		textArea.style.outline = 'none';
		textArea.style.boxShadow = 'none';
		textArea.style.background = 'transparent';
		textArea.value = copyText;
		document.body.appendChild(textArea);
		textArea.select();

		try  {
			document.execCommand("copy");
		}  catch (error)  {
			console.log("Oops, unable to copy text...");
		}

		document.body.removeChild(textArea);
	}

	function copyCouponURL()  {
		var copyText = $("#couponURL").val();
		copyTextToClipboard(copyText);
		alert("Coupon URL has been copied.");
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
			pageLength: 5,
			buttons: ["csv", "excel", "copy"],
			order: [[0, "desc"]],
			dom: "Bfrtip",

			ajax: {
				url: '{{ route("foso.campaigns.managetool.coupon.search.api") }}',
				data:  function(data)  {
					data.filter = $("#text").val();
				}
			},

			columnDefs: [
				{targets:0, visible:false},
				{targets:2, width:"100px"},
				{targets:6, visible:false},
				{targets:7, visible:false},
				{targets:8, visible:false},
				{targets:9, visible:false},
				{targets:10, visible:false},
				{targets:11, visible:false},
				{targets:12, visible:false},
			],
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

			var recordID = data[0];
			var offerID = data[2];
			var offerTitle = data[3];
			var uniqueCode = data[4];
			var mobile = data[5];
			var startAt = data[6];
			var useAt = data[7];
			var expiryAt = data[8];
			var formData = data[9];
			var referralCode = data[11];
			var referralData = data[12];

			$("#offerID").val(offerID);
			$("#offerTitle").val(offerTitle);

			$("#startAt").val(startAt);
			$("#useAt").val(useAt);
			$("#expiryAt").val(expiryAt);

			$("#mobile").val(mobile);

			var couponURL = _baseURL+uniqueCode;
			$("#couponURL").val(couponURL);
// 			$("#couponURLCopyText").text(couponURL);

// 			var referralURL = _baseURL+referralCode;
// 			$("#referralURL").text(referralURL);
// 			$("#referralURL").attr("href", referralURL);

			$("#formData").val(formData);

			$("#referralData").val(referralData);
		});

		$("#couponURLCopyButton").click(function()  {copyCouponURL();});
		$("#text_search").click(function()  {reloadWithDateRange();});
		$("#text").keypress(function(event)  {
			var keycode = (event.keyCode ? event.keyCode : event.which);
			if (keycode != '13')  {return;}
			reloadWithDateRange();
		});
	});
</script>

@endsection
