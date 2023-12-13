@extends('foso.layouts.default')

@section('page_title', 'Campaign Offer #'.$offer->id.' Rules')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.campaigns.html") }}'>Campaigns</a></li>
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.campaigns.offer.html") }}'>Offer</a></li>
<li><i class='fa fa-angle-right'></i> Channel</li>
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
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.whatsapp.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-comment"></i></span> <span class="hidden-xs-down">WhatsApp</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.customerjourney.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-plane"></i></span> <span class="hidden-xs-down">Journey</span></a> </li>
    <li class="nav-item"> <a class="nav-link  active" href='{{ route("foso.campaigns.offer.channel.sample.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-tablet"></i></span> <span class="hidden-xs-down">Channel</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.ini.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-file-text-o"></i></span> <span class="hidden-xs-down">.ini</span></a> </li>
</ul>

<div class="card">

	<div class="card-body">
		<form id="form" name="form">
			@csrf

			<div class="row">
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="startDate">Start Date</label>
						<input class="form-control" type="date" value="{{ $startDate }}" id="startDate" name="startDate" required>
						<small class="form-text small-text-color">Sample available date</small>
					</fieldset>
				</div>
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="startTime">Start Time</label>
						<input class="form-control" type="time" value="{{ $startTime }}" id="startTime" name="startTime" required>
						<small class="form-text small-text-color">Sample available time</small>
					</fieldset>
				</div>
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="nostart"> </label>
						<button type="button" class="btn btn-info" id="nostart"  style="width:100%" onclick="startreset();">No start date</button>
                        <small class="form-text small-text-color">No set mean keep valid without time limit</small>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="endDate">End Date</label>
						<input class="form-control" type="date" value="{{ $endDate }}" id="endDate" name="endDate" required>
						<small class="form-text small-text-color">Sample expiry date (Deflaut: after 30 days of offer end)</small>
					</fieldset>
				</div>
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="endTime">End Time</label>
						<input class="form-control" type="time" value="{{ $endTime }}" id="endTime" name="endTime" required>
						<small class="form-text small-text-color">Sample end time</small>
					</fieldset>
				</div>
				<div class="col-lg-4">
					<fieldset class="form-group">
					<label for="noend"> </label>
						<button type="button" class="btn btn-info" id="noend" style="width:100%" onclick="endreset();">No end date</button>
                        <small class="form-text small-text-color">No set mean keep valid without time limit</small>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="point">Aprroval Point</label>
						<input class="form-control" type="text" value="{{ empty($approvePoint)? '10': $approvePoint }}" id="point" name="point" required>
						<small class="form-text small-text-color">Deflaut: 10 points</small>
					</fieldset>
				</div>
			</div>

            <input type="hidden" id="final" name="final" value="" />
            </form>
            <!-- drag drop menu for channel sample -->
			<div class="row">
                <div class="col-lg-12">
                    <fieldset class="form-group">
                        <label for="fieldChooser">Channel Selection (display receipt sample)</label>
                        <div id="fieldChooser" tabIndex="1">

                            <div id="sourceFields">
@foreach( $unpickChannel as $channel )
                                <div id="{{$channel['id'] ?? '0'}}">{{$channel['title'] ?? ''}}</div>
@endforeach
                            </div>
                          
                            <div id="destinationFields">
@foreach( $pickChannel as $channel2 )
                                <div id="{{$channel2['id'] ?? '0'}}">{{$channel2['title'] ?? ''}}</div>
@endforeach
                            </div>

                        </div>
                    </fieldset>
					<span style="margin-left: 14px;font-size: small;">Please drag the <b style="color:green">support channel to <u>RIGHT</u> box.</b> </span>
                </div>
            </div>
			<br>
			<br>
			<button type="button" class="btn btn-danger" id="saveButton">Save</button>
	</div>
</div>



<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{ asset('js/common.js') }}?v=1"></script>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

<link rel="stylesheet" type="text/css" href="{{ asset('js/stylesheets/style.css') }}?v=1" />
<link rel="stylesheet" type="text/css" href="{{ asset('js/stylesheets/jquery-ui.css') }}?v=1" />
<script src="{{ asset('js/scripts/jquery-1.10.2.js') }}?v=1"></script>
<script src="{{ asset('js/scripts/jquery-ui.js') }}?v=1" ></script>
<script src="{{ asset('js/fieldChooser.js') }}?v=1" ></script>

<script>

    function startreset(){
		document.getElementById("startTime").value = '';
		document.getElementById("startDate").value = '';
	}

	function endreset(){
		document.getElementById("endTime").value = '';
		document.getElementById("endDate").value = '';
	}


	$(document).ready(function()  {

		//  When leave page, show loading
		window.onbeforeunload = function(e)  {showLoading();};

        var $sourceFields = $("#sourceFields");
        var $destinationFields = $("#destinationFields");
        var $chooser = $("#fieldChooser").fieldChooser(sourceFields, destinationFields);

		$("#saveButton").click(function()  {

            const obj = $destinationFields.children();
            var list = [];
            Object.keys(obj).forEach(key => {
                if(obj[key].id)
                    list.push(obj[key].id);
            });
            let text = list.toString();
            document.getElementById("final").value = text;
            
			var form = $("#form");
			var formData = $("#form").serialize();
            console.log (formData);
			showLoading();

			$.ajax({
				type: "POST",
				data: formData,
				dataType: "json",
				url: '{{ route("foso.campaigns.offer.channel.save.json", ["offer_code"=>$offerCode]) }}',
				success: function (result)  {
                    alert(result.message);
					location.reload();
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
