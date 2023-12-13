@extends('foso.layouts.default')

@section('page_title', 'Third Party Event')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.campaigns.html") }}'>Campaigns</a></li>
<li><i class='fa fa-angle-right'></i> Third Party Event</li>
@endsection

@section('content')
<style>
	#uaFormDownload{
		color: white;
		font-size:0.8em;
		white-space: normal;
		word-wrap: break-word;
	}
</style>

<div class='card'>
	<div class='card-body' style="padding-bottom:0px;">
		<div class="row">
			<div class="col-md-12">
				<h4>UA iMoney Redemption form</h4>
				<p style="margin-bottom:0px;">Description: 2022 Aug-Sep, CSV report file collection</p>
			</div>
		</div>
	</div>
<form id="form" name="form">
	<div class='card-body'>
		<div class="row">
	        <div class="col-md-4">
				<img src="{{ asset('website/uaform/UAbanner.png') }}?v=1" alt="uabanner" style="width:95%" />
			</div>

            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="from_date">From Date</label>
                            <div class="input-group">
                                <input class="form-control" type="date" id="fromDateUpdate" name="fromDateUpdate" value="{{ $fromDate }}">
                                <div class="input-group-append">
                                    <!-- <button type="button" class="btn btn-primary" id="from_date_update">Update</button> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="to_date">To Date</label>
                            <div class="input-group">
                                <input class="form-control" type="date" id="toDateUpdate" name="toDateUpdate" value="{{ $toDate }}">
                                <div class="input-group-append">
                                    <!-- <button type="button" class="btn btn-primary" id="to_date_update">Update</button> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="to_date" >Download report file</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-warning btn-block" id="uaFormDownload" >CSV Download</button>
                            </div>
                        </div>
                    </div>
                </div>
				<div class="row">
					<div class="col-md-12">
						<label>Download link</label>
						<text id = "fileLink"> </text>
					</div>
				</div>
            </div>
		</div>
	</div>
</from>
</div>

<script type="text/javascript" src="{{ asset('assets/DataTables/datatables.min.js') }}?v=1"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="{{ asset('js/common.js') }}?v=1"></script>

<script>
	
	$(document).ready(function()  {

		$("#uaFormDownload").click(function()  {

			var formData = $("#form").serialize();

			$.ajax({
				url: '{{ route("foso.thirdparty.uafimoney.csv.file") }}',
				data: formData, 
				dataType: "json",
				success: function (result)  {

					filePath = result.filepath;
					// alert(result.message);
					window.open(filePath, 'Download');
					if (filePath.includes("uafimoney_download")){
						var link = ` <a href="`;
						link += filePath;
						link += `" download>`;
						link += filePath;
						link += `<\a>`;

						document.getElementById("fileLink").innerHTML = link;
						document.getElementById("uaFormDownload").innerHTML = "Create another file";
					}

					if (result.status < 0)  {
						hideLoading();
						return;
					}

				},
				error: function (XMLHttpRequest, textStatus, errorThrown)  {
					hideLoading();
					alert("Oops...\n#"+textStatus+": "+errorThrown);
				}
			})
		})

	})

	
</script>

@endsection
