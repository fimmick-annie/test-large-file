@extends('foso.layouts.default')

@section('page_title', 'Members Detail')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.members.search.html") }}'>Members</a></li>
<li><i class='fa fa-angle-right'></i> Detail</li>
@endsection

@section('content')

<style>
	#refreshButton{
		font-size: 15px;
		color: #FFFFFF;
		width: 100%;
	}
</style>

<form id="form" name="form">
	@csrf

	<div class="card">
		<div class='card-body'>
			<div class="row">
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="createdAt">Create Time</label>
						<input class="form-control" type="text" value="{{ empty($record->created_at)?'':$record->created_at }}" id="createdAt" name="createdAt" disabled>
					</fieldset>
				</div>
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="updatedAt">Update Time</label>
						<input class="form-control" type="text" value="{{ empty($record->updated_at)?'':$record->updated_at }}" id="updatedAt" name="updatedAt" disabled>
					</fieldset>
				</div>
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="deletedAt">Delete Time</label>
						<input class="form-control" type="text" value="{{ empty($record->deleted_at)?'':$record->deleted_at }}" id="deletedAt" name="deletedAt" disabled>
					</fieldset>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6">
					<fieldset class="form-group">
						<label for="mobile">Mobile</label>
						<input class="form-control" type="text" value="{{ empty($record->mobile)?'':$record->mobile }}" id="mobile" name="mobile" disabled>
					</fieldset>
				</div>
				<div class="col-lg-6">
					<fieldset class="form-group">
						<label for="username">Username</label>
						<input class="form-control" type="text" value="{{ empty($record->username)?'':$record->username }}" id="username" name="username">
					</fieldset>
				</div>
			</div>
			<div class="row">
			<div class="col-lg-3">
                    <fieldset class="form-group">
                        <label for="pointBalance">Point Balance</label>
                        <input class="form-control" type="text" value="{{ $record->point_balance }}" id="pointBalance" name="pointBalance" disabled>
						<!-- <small class="form-text small-text-color">member balance</small> -->
                    </fieldset>
                </div>

                <div class="col-lg-3">
                    <fieldset class="form-group">
                        <label for="pointperiod1">Point @ Period 1</label>
                        <input class="form-control" type="text" value="{{ $record->period_1_points }}" id="pointperiod1" name="pointperiod1" disabled>
						<small class="form-text small-text-color"><i>Timeline : {{$period1}}</i></small>
                    </fieldset>
                </div>

                <div class="col-lg-3">
                    <fieldset class="form-group">
                        <label for="pointperiod2">Point @ Period 2</label>
                        <input class="form-control" type="text" value="{{ $record->period_2_points }}" id="pointperiod2" name="pointperiod2" disabled>
						<small class="form-text small-text-color"><i>Timeline: {{$period2}}</i></small>
                    </fieldset>
                </div>

                <div class="col-lg-3">
					<label class='d-none d-lg-block'>&nbsp;</label>
					<button class="btn btn-warning btn-sm" type="button" id="refreshButton" > Re-calculate</button>
					<small class="form-text small-text-color">Calculate the point reocrd of member</small>
				</div>

			</div>
			<div class="row">
				<div class="col-lg-6">
					<fieldset class="form-group">
						<label for="optout_at">Opt-Out At</label>
						<input class="form-control" type="text" value="{{ empty($record->optout_at)?'':$record->optout_at }}" id="optout_at" name="optout_at" disabled>
					</fieldset>
				</div>
				<div class='col-lg-6'>
					<fieldset class="form-group">
						<label class='d-none d-lg-block'>&nbsp;</label>
						<input style="width:auto;" class="form-control " type="button" value="Toggle Opt-Out/In" id="toggle_optout" name="toggle_optout" onclick="toggleOptout()">
					</fieldset>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6">
					<fieldset class="form-group">
						<label for="mute_until">Mute Until</label>
						<input class="form-control" type="text" value="{{ empty($record->mute_until)?'':$record->mute_until }}" id="mute_until" name="mute_until" disabled>
					</fieldset>
				</div>
				<div class='col-lg-6'>
					<fieldset class="form-group">
						<label class='d-none d-lg-block'>&nbsp;</label>
						<input style="width:auto;" class="form-control" type="button" value="Toggle Mute (1 Day)/Unmute" id="toggle_mute" name="toggle_mute" onclick="toggleMute()">
					</fieldset>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<fieldset class="form-group">
						<label for="mute_data">Mute Data</label>
						<textarea class="form-control" id="mute_data" name="mute_data" rows=10>{{ empty($record->mute_data)?'':$record->mute_data }}</textarea>
					</fieldset>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<fieldset class="form-group">
						<label for="offer_involved">Offer Involved</label>
						<textarea class="form-control" id="offer_involved" name="offer_involved" rows=10>{{ empty($record->offer_involved)?'':$record->offer_involved }}</textarea>
					</fieldset>
				</div>
			</div>
			<button type="button" class="btn btn-danger" id="saveButton">Save</button>
		</div>
	</div>
</form>

	<div class="card" style="margin-top:8px;">
		<div class='card-body'>
			<div class='row'>
				<div class='col-sm-12'>
					<fieldset class="form-group">
						<label for="member_events">Member Event Logs</label>
						<div class="table-responsive">

							<table class="table table-bordered table-hover" data-name="dataTable" id="dataTable">
								<thead>
									<tr>
										<th>ID</th>
										<th>Create At</th>
										<th>Create By</th>
										<th>User Id</th>
										<th>event</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th>Search</th>
										<th>Search</th>
										<th>Search</th>
										<th>Search</th>
										<th>Search</th>
									</tr>
								</tfoot>
							</table>

						</div>
					</fieldset>
				</div>
			</div>
		</div>
	</div>

	<div class="card" style="margin-top:8px;">
		<div class='card-body'>
			<div class='row'>
				<div class='col-sm-12'>
					<fieldset class="form-group">
						<label for="member_events">Member Point Transactions</label>
						<div class="table-responsive">
							<table class="table table-bordered table-hover" data-name="dataTableTransactions" id="dataTableTransactions">
								<thead><tr>
									<th>ID</th>
									<th>Create At</th>
									<th>Delta Points</th>
									<th>Valid At</th>
									<th>Expiry At</th>
									<th>Type</th>
									<th>Description</th>
								</tr></thead>
								<tfoot><tr>
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
					</fieldset>
				</div>
			</div>
		<hr>
	<form id="formPointAdjust" name="formPointAdjust">
			<fieldset class="form-group">
				<label for="adjust_point">Point adjustment</label>
				<br>
				<br>
				<div class="row">
					<div class="col-lg-4">
						<fieldset class="form-group">
							<label for="validDate">Valid Date</label>
							<input class="form-control" type="date" value="" id="validDate" name="validDate" required>
							<!-- <small class="form-text small-text-color">A available date</small> -->
						</fieldset>
					</div>
					<div class="col-lg-4">
						<fieldset class="form-group">
							<label for="validTime">Valid Time</label>
							<input class="form-control" type="time" value=" " id="validTime" name="validTime" required>
							<!-- <small class="form-text small-text-color">Redemption available time</small> -->
						</fieldset>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-4">
						<fieldset class="form-group">
							<label for="expiryDate">Expiry Date</label>
							<input class="form-control" type="date" value=" " id="expiryDate" name="expiryDate" >
							<small class="form-text small-text-color">Optional, generated automatically when not specified</small>
						</fieldset>
					</div>
					<div class="col-lg-4">
						<fieldset class="form-group">
							<label for="expiryTime">Expiry Time</label>
							<input class="form-control" type="time" value=" " id="expiryTime" name="expiryTime" >
							<small class="form-text small-text-color">Optional, set 23:59:59 when not specified</small>
						</fieldset>
					</div>
				</div>
			
				<div class="row">
					<div class="col-lg-4">
						<fieldset class="form-group">
							<label for="point_adjust">Point Adjustment</label>
							<input class="form-control" type="number" id="point_adjust" value="" max="100000" name="point_adjust" placeholder="0" maxlength="63" required>
							<small class="form-text small-text-color"> - sign is needed for deducting points</small>
						</fieldset>
					</div>
					<div class="col-lg-4">
						<fieldset class="form-group">
							<label for="adjust_type">Type</label>
							<select class="form-control" id="adjust_type" name="adjust_type">
								<option value="admin" selected>Admin adjustment</option> 
								<option value="take offer">Taking offer</option> 
								<option value="chat">Chating</option>
								<option value="referral">Referral</option> 
								<option value="special task">Special task</option> 
								<option value="offer hunting">Offer Hunting</option> 
								<option value="others">Others</option> 
							</select>
						</fieldset>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6">
						<fieldset class="form-group">
							<label for="description_adjust_en">Description(English)</label>
							<input class="form-control" type="text" id="description_adjust_en" value="" name="description_adjust_en" maxlength="63" required>
						</fieldset>
					</div>
					<div class="col-lg-6">
						<fieldset class="form-group">
							<label for="description_adjust_zh">Description(Chinese)</label>
							<input class="form-control" type="text" id="description_adjust_zh" value="" name="description_adjust_zh" maxlength="63" required>
						</fieldset>
					</div>

				</div>
				
				<button type="button" class="btn btn-danger" id="saveAdjustButton">Save adjustment</button>
			</fieldset>
		</form>
		</div>
	</div>

</form>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{ asset('assets/DataTables/datatables.min.js') }}?v=1"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="{{ asset('js/common.js') }}?v=1"></script>
<script>
	var _tableTransactions = null;
	var _modified = false;
	var _table = null;
	$(document).ready(function() {

		//  When leave page, show loading
		window.onbeforeunload = function(e) {
			showLoading();
		};

		$.validator.addMethod("time", function(value, element) {
			return this.optional(element) || /^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])(:([0-5]?[0-9]))?$/i.test(value);
		}, "Please enter a valid time.");

		$.validator.addMethod("alphanumeric", function(value, element) {
			return this.optional(element) || /^[\w._-]+$/i.test(value);
		}, "Only letters, numbers, hyphen and underscore is allowed.");

        $("#refreshButton").click(function(){

            $.ajax({
                type: 'GET',
				url: '{{ route("foso.members.refreshpoint.api", ["mobile"=>$mobile]) }}',
				success: function(result) {
					if (result.status != 0) {
						hideLoading();
						return;
					}
					location.reload();
				},
					error: function(XMLHttpRequest, textStatus, errorThrown) {
					hideLoading();
					alert("Oops...\n#" + textStatus + ": " + errorThrown); 
				},
            })
        });

		// save a "Point adjustment" record
		$("#saveAdjustButton").click(function() {

			// validation
			var basicRule = {
				rules:  {
					validDate:  {date:true},
					validTime:  {time:true},
					point_adjust:  {number:true},
					description_adjust_en:  {minlength:1},
					description_adjust_zh:  {minlength:1},
				},
				messages: {
					description_adjust_en:  {minlength:"Must consist of at least 1 characters"},
					description_adjust_zh:  {minlength:"Must consist of at least 1 characters"},
				}
			};

			var form2 = $("#formPointAdjust");
			form2.validate(basicRule);

			result = form2.valid();
			if (result == false) {
				return;
			}

			//  Form OK
			var formData2 = form2.serialize();

			$.ajax({
				type: "POST",
				data: formData2,
				dataType: "json",
				url: '{{ route("foso.members.pointadjust.api", ["mobile"=>$mobile]) }}',
				success: function(result) {

					alert(result.message);
					if (result.status != 0) {
						hideLoading();
						return;
					}
					location.reload();
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					hideLoading();
					alert("Oops...\n#" + textStatus + ": " + errorThrown);
				}
			});

		});

		$("#saveButton").click(function() {

			var basicRule = {
				rules: {},
				messages: {}
			};

			var form = $("#form");
			form.validate(basicRule);

			result = form.valid();
			if (result == false) {
				return;
			}

			//  Form OK
			var disabled = $("#form").find(":input:disabled").removeAttr("disabled");
			var formData = $("#form").serialize();
			disabled.attr("disabled", "disabled");

			showLoading();
			$.ajax({
				type: "POST",
				data: formData,
				dataType: "json",
				url: '{{ route("foso.members.detail.update.api", ["mobile"=>$mobile]) }}',
				success: function(result) {

					alert(result.message);
					if (result.status != 0) {
						hideLoading();
						return;
					}

					location.reload();
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					hideLoading();
					alert("Oops...\n#" + textStatus + ": " + errorThrown);
				}
			});
		});

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
				url: '{{ route("foso.member.events") }}?mobile={{ $mobile }}',
				dataSrc: 'data'
			},

			columns: [{
				data: 'id'
			}, {
				data: 'created_at'
			}, {
				data: 'create_by'
			}, {
				data: 'user_id'
			}, {
				data: 'event'
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

		$("#dataTableTransactions tfoot th").each(function() {
			var title = $(this).text();
			$(this).html('<input type="text" placeholder="' + title + '" style="width:100%;"/>');
		});

		_tableTransactions = $("#dataTableTransactions").DataTable({
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
				url: '{{ route("foso.members.detail.transaction.api", ["mobile"=>$mobile]) }}',
				dataSrc: 'data'
			},
		});

		_tableTransactions.columns().every(function() {
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

	function formatDate(date) {
		var d = new Date(date),
			month = '' + (d.getMonth() + 1),
			day = '' + d.getDate(),
			year = d.getFullYear();

		if (month.length < 2)
			month = '0' + month;
		if (day.length < 2)
			day = '0' + day;

		return [year, month, day].join('-');
	}

	const toggleOptout = () => {
		let optout_at = document.querySelector('#optout_at');
		if (optout_at.value == '') {
			const d = new Date();
			optout_at.value = formatDate(d) + " " + d.toTimeString().split(' ')[0];
		} else {
			optout_at.value = '';
		}
	}
	const toggleMute = () => {
		let mute_until = document.querySelector('#mute_until');
		if (mute_until.value == '') {
			// 86400 000 ms = 1day
			let d = new Date(Date.now() + 86400000);
			mute_until.value = formatDate(d) + " " + d.toTimeString().split(' ')[0];
		} else {
			mute_until.value = '';
		}
	}
</script>

@endsection
