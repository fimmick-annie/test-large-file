@extends('foso.layouts.default')

@section('plugin_js_lead')
<script src="{{asset('js/popper.js')}}"></script>
@endsection

@section('plugin_js')
<script src="{{asset('js/raphael.js')}}?v=1"></script>
<script src="{{asset('js/flowchart.js')}}?v=1"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js?v=1"></script>
<script type="text/javascript" src="https://unpkg.com/canvg@3.0.4/lib/umd.js?v=1"></script>
@endsection

@section('page_title', 'Campaign Offer #'.$offer->id.' Chatbot Journey Settings')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.campaigns.html") }}'>Campaigns</a></li>
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.campaigns.offer.html") }}'>Offer</a></li>
<li><i class='fa fa-angle-right'></i> Chatbot Journey</li>
@endsection

@section('content')
<link rel="stylesheet" href="{{asset('assets/vendor/adminkit/plugins/dropify/dropify.min.css')}}" />
<script src="{{asset('assets/vendor/adminkit/plugins/dropify/dropify.min.js')}}"></script>
<style>
	.modal-title {
		font-size: 1.1em;
		font-weight: bolder;
	}
	.tooltip-inner {
		max-width: 500px;
		/* If max-width does not work, try using width instead */
		width: 500px;
	}
	#the-count {
		float: right;
		padding: 0.2rem 0.3rem 0 0;
		font-size: 0.78rem;
		color: #a0a0a0;
	}
</style>

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
	<li class="nav-item"> <a class="nav-link active" href='{{ route("foso.campaigns.offer.customerjourney.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-plane"></i></span> <span class="hidden-xs-down">Journey</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.channel.sample.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-tablet"></i></span> <span class="hidden-xs-down">Channel</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.ini.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-file-text-o"></i></span> <span class="hidden-xs-down">.ini</span></a> </li>
</ul>

<div class="classes d-none">
	<a href="#" class="list-group-item d-flex justify-content-between align-items-center list-group-item-action cj-node">
		<span class="sortable-handle d-flex justify-content-center mr-4">
			<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-grip-vertical" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
				<path d="M2 8a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
			</svg>
		</span>
		<span class="cj-node-name w-100"></span>
		<span class="badge badge-primary badge-pill cj-node-completion-percentage"></span>
	</a>
</div>

<div class="card">
	<div class="card-body">
		<div class="row">
			<div class="col-sm-4">
				<div class="row">
					<div class="col sm-12">
						<div class="input-group mb-3">
							<input type="text" class="form-control" id="mobile-to-search" placeholder="Mobile Number" aria-label="Mobile Number" aria-describedby="button-search">
							<div class="input-group-append">
								<button class="btn btn-primary" type="button" id="button-search">Search</button>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-12">
						Journey Nodes<br>
						<small class="form-text small-text-color">Drag handle to change save ordering</small>
					</div>
					<div class="col-sm-12">
						<div class="list-group cj-node-list"></div>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-12">
@hasanyrole('Super-Administrator')
{{-- @if (env("APP_ENV") != "production") --}}
						<button class="btn btn-danger col-sm-12" style="margin-top:10px;" type="button" id="button-clear-whitelisted-records">Clear whitelisted records</button>
						<button class="btn btn-outline-danger col-sm-12" style="margin-top:10px;" type="button" id="button-archive-all-records">Archive all records</button>
{{-- @endif --}}
@endhasanyrole
					</div>
				</div>

			</div>
			<div class="col-sm-8">
				<div class="row">
					<div class="dropdown">
						<button class="btn col-auto mx-1 btn-primary px-3" type="button" id="button-add" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fa fa-plus" aria-hidden="true"></i>
						</button>
						<div class="dropdown-menu" aria-labelledby="button-add">
							<a class="dropdown-item button-add-element" data-node-type="node-type-message" href="#">100 Message Node</a>
							<a class="dropdown-item button-add-element" data-node-type="node-type-question" href="#">200 Question Node</a>
							<a class="dropdown-item button-add-element" data-node-type="node-type-quick-reply" href="#">250 Quick-reply Node</a>
							<a class="dropdown-item button-add-element" data-node-type="node-type-issue-coupon" href="#">300 Issue Coupon Node</a>
							<a class="dropdown-item button-add-element" data-node-type="node-type-cancel-reminder" href="#">310 Cancel Reminder Node</a>
							<a class="dropdown-item button-add-element" data-node-type="node-type-cancel-journey" href="#">320 Cancel Journey Node</a>
							<a class="dropdown-item button-add-element" data-node-type="node-type-referral" href="#">330 Referral Node</a>
							<a class="dropdown-item button-add-element" data-node-type="node-type-referral-completion"  href="#" >335 Referral Completion Node</a>
							<a class="dropdown-item button-add-element" data-node-type="node-type-get-form-data" href="#">340 Get Form Data Node</a>
							<a class="dropdown-item button-add-element" data-node-type="node-type-date-comparison" href="#">400 Date Comparison Node</a>
							<a class="dropdown-item button-add-element" data-node-type="node-type-coupon-expiry-check" href="#">410 Coupon Expiry Check Node</a>
							<a class="dropdown-item button-add-element" data-node-type="node-type-payment" href="#">500 Payment Node</a>
<!-- 							<a class="dropdown-item button-add-element" data-node-type="node-type-payment-cancel" href="#">510 Cancel Payment Node</a> -->
							<a class="dropdown-item button-add-element" data-node-type="node-type-nft-redeem" href="#">600 Redeem NFT Node</a>
							<a class="dropdown-item button-add-element" data-node-type="node-type-issue-point" href="#">700 Issue Point Node</a>
						</div>
					</div>
					<button class="btn col-sm-2 mx-1 btn-primary" type="button" id="button-export">Export</button>
					<button class="btn col-sm-2 mx-1 btn-primary" type="button" id="button-report-csv">Report</button>
					<button class="btn col-sm-2 mx-1 btn-primary" type="button" id="button-csv">CSV</button>
				</div>
				<div class="row">
					<textarea id="flowchart-js-code" class="d-none"></textarea>
					<div id="flowchart-js-canvas" class="w-100 d-flex align-items-start"></div>
					<canvas class="d-none" id="canvas-for-export"></canvas>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal node-type-unknown" data-class="node-type-unknown" tabindex="-1" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Please Select a Node Type</h5>
				<button type="button" class="close closeCJModalBtn" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="list-group">
					<a href="#" class="list-group-item button-select-node-type" data-node-type="node-type-message">100 Message Node</a>
					<a href="#" class="list-group-item button-select-node-type" data-node-type="node-type-question">200 Question Node</a>
					<a href="#" class="list-group-item button-select-node-type" data-node-type="node-type-quick-reply">250 Quick-reply Node</a>
					<a href="#" class="list-group-item button-select-node-type" data-node-type="node-type-issue-coupon">300 Issue Coupon Node</a>
					<a href="#" class="list-group-item button-select-node-type" data-node-type="node-type-cancel-reminder">310 Cancel Reminder Node</a>
					<a href="#" class="list-group-item button-select-node-type" data-node-type="node-type-cancel-journey">320 Cancel Journey Node</a>
					<a href="#" class="list-group-item button-select-node-type" data-node-type="node-type-referral">330 Referral Node</a>
					<a href="#" class="list-group-item button-select-node-type" data-node-type="node-type-referral-completion">335 Referral Completion Node</a>
					<a href="#" class="list-group-item button-select-node-type" data-node-type="node-type-get-form-data">340 Get Form Data Node</a>
					<a href="#" class="list-group-item button-select-node-type" data-node-type="node-type-date-comparison">400 Date Comparison Node</a>
					<a href="#" class="list-group-item button-select-node-type" data-node-type="node-type-coupon-expiry-check">410 Coupon Expiry Check Node</a>
					<a href="#" class="list-group-item button-select-node-type" data-node-type="node-type-payment">500 Payment Node</a>
<!-- 					<a href="#" class="list-group-item button-select-node-type" data-node-type="node-type-payment-cancel">510 Cancel Payment Node</a> -->
					<a href="#" class="list-group-item button-select-node-type" data-node-type="node-type-nft-redeem">600 Redeem NFT Node</a>
					<a class="dropdown-item button-add-element" data-node-type="node-type-issue-point" href="#">700 Issue Point Node</a>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal node-type-message" data-class="node-type-message" tabindex="-1" data-backdrop="static" data-keyboard="false" data-default-parameterized-link-image='{"apple":"https://upload.wikimedia.org/wikipedia/commons/thumb/a/a1/Apple_I_Computer.jpg/1920px-Apple_I_Computer.jpg","coupon":"https://go.harborfreight.com/wp-content/uploads/2020/08/23637148-save-20-percent-at-harbor-freight-through-august-31-2020-b.png","___default":"https://d17mj6xr9uykrr.cloudfront.net/Pictures/2000x2000fit/2/1/6/67216_default_115473.jpg"}'>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">100 Message Node</h5>
				<button type="button" class="close closeCJModalBtn" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="node-type-message-form">
					<div class="form-group">
						<label for="node-type-message-node-name">Node Name</label>
						<input type="text" class="form-control node-type-node-name unique" id="node-type-message-node-name" name="node-type-message-node-name" placeholder="" required>
						<label id="node-type-message-node-name-unique-error" class="error d-none" for="node-type-message-node-name">This should be unique.</label>
						<small class="form-text small-text-color">Node name is case-sensitive</small>
					</div>
					<div class="form-group">
						<label for="node-type-message-sending-time">Scheduled Time</label>
						<input type="text" class="form-control" id="node-type-message-sending-time" name="node-type-message-sending-time" placeholder="">
						<small class="form-text small-text-color"><u>No floating point</u> value, integer only.  Ex: "+0.5 hour" should be "+30 minutes"</small>
						<small class="form-text small-text-color">Leave it empty if send out immediately</small>
					</div>
					<div class="form-group">
						<label for="node-type-message-image-path">Image</label>
						<input type="file" class="form-control dropify" data-for-id="node-type-message-image-path">
						<input type="text" class="form-control" id="node-type-message-image-path" name="node-type-message-image-path" placeholder="Image Link">
						<small class="form-text small-text-color">QR code: https://www.kinnso.com/qrcode/?c={content}
							<a data-toggle="tooltip" data-html="true" data-placement="top" title="
							<h5>Optional Parameters:</h5>
							&b={backgroundURL}
							<br>&l={logoURL}
							<br>&s={qrCodeSize:300}
							<br>&x={offsetX:0}
							<br>&y={offsetY:0}
							<br>&nbsp;
							<h5>Example:</h5>
							?c=qrcode-content-here&b=http://www.kinnso.com/frame.png&l=http://www.kinnso.com/logo.png&s=300&x=10&y=20
							"><i class="fa fa-info-circle"></i></a>
						</small>
						<small class="form-text small-text-color"></small>
						@include('foso/campaigns/common/dynamic_fields')
					</div>
					<div class="form-group">
						<label for="node-type-message-text">Text</label>
						<textarea class="form-control" id="node-type-message-text" name="node-type-message-text" rows="5"></textarea>
						<div id="the-count">
							<span id="current">0</span>
							<span id="maximum">／ 1600</span>
						</div>
						@include('foso/campaigns/common/dynamic_fields')
					</div>
					<div class="form-group">
						<label for="node-type-message-node-name-next">Next Node Name</label>
						<input type="text" class="form-control node-type-node-name next" id="node-type-message-node-name-next" name="node-type-message-node-name-next" placeholder="">
						<small class="form-text small-text-color">Leave it empty if ends</small>
					</div>
				</form>
				<div class="readonly">
					<hr />
					<h5>Customer Data</h5>
					<div class="node_data"></div>
				</div>
			</div>
			<div class="modal-footer d-flex justify-content-between flex-row-reverse">
				<button type="button" class="btn btn-danger button-save-node edit">Save</button>
				<button type="button" class="btn btn-outline-danger button-delete-node d-none edit">Delete</button>
			</div>
		</div>
	</div>
</div>

<div class="modal node-type-question" data-class="node-type-question" tabindex="-1" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">200 Question Node</h5>
				<button type="button" class="close closeCJModalBtn" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="node-type-question-form">
					<div class="form-group">
						<label for="node-type-question-node-name">Node Name</label>
						<input type="text" class="form-control node-type-node-name unique" id="node-type-question-node-name" name="node-type-question-node-name" placeholder="" required>
						<label id="node-type-question-node-name-unique-error" class="error d-none" for="node-type-question-node-name">This should be unique.</label>
						<small class="form-text small-text-color">Node name is case-sensitive</small>
					</div>
					<div class="form-group">
						<label for="node-type-question-sending-time">Scheduled Time</label>
						<input type="text" class="form-control" id="node-type-question-sending-time" name="node-type-question-sending-time" placeholder="">
						<small class="form-text small-text-color"><u>No floating point</u> value, integer only.  Ex: "+0.5 hour" should be "+30 minutes"</small>
						<small class="form-text small-text-color">Leave it empty if send out immediately</small>
					</div>
					<div class="form-group">
						<label for="node-type-question-image-path">Image</label>
						<input type="file" class="form-control dropify" data-for-id="node-type-question-image-path">
						<input type="text" class="form-control" id="node-type-question-image-path" name="node-type-question-image-path" placeholder="Image Link">
						@include('foso/campaigns/common/dynamic_fields')
					</div>
					<div class="form-group">
						<label for="node-type-question-text">Text</label>
						<textarea class="form-control" id="node-type-question-text" rows="5"></textarea>
						@include('foso/campaigns/common/dynamic_fields')
					</div>
					<div class="foso-repeat node-type-question-options" data-class="node-type-question-options" data-count="1">
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label for="node-type-question-option-{count}">Option {count}</label>
									<input type="text" class="form-control node-type-question-option" id="node-type-question-option-{count}" name="node-type-question-option-{count}" placeholder="" required>
								</div>
							</div>
							<div class="col-sm-8">
								<div class="form-group">
									<label for="node-type-question-node-name-next-{count}">Next Node Name</label>
									<input type="text" class="form-control node-type-question-node-name-next node-type-node-name next" id="node-type-question-node-name-next-{count}" name="node-type-question-node-name-next-{count}" placeholder="">
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<button type="button" class="form-control btn btn-outline-primary btn-block btn-sm foso-repeat-add" data-class="node-type-question-options">Add an Option</button>
					</div>
					<small class="form-text small-text-color">* Can use "any" for option if any reply or free text</small>
					<small class="form-text small-text-color">* Option with longer node name will goes left in flow chart</small>
				</form>
				<div class="readonly">
					<hr />
					<h5>Customer Data</h5>
					<div class="node_data"></div>
				</div>
			</div>
			<div class="modal-footer d-flex justify-content-between flex-row-reverse">
				<button type="button" class="btn btn-danger button-save-node edit">Save</button>
				<button type="button" class="btn btn-outline-danger button-delete-node d-none edit">Delete</button>
			</div>
		</div>
	</div>
</div>

<div class="modal node-type-quick-reply" data-class="node-type-quick-reply" tabindex="-1" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">250 Quick-reply Node</h5>
				<button type="button" class="close closeCJModalBtn" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="node-type-quick-reply-form">
					<div class="form-group">
						<label for="node-type-quick-reply-node-name">Node Name</label>
						<input type="text" class="form-control node-type-node-name unique" id="node-type-quick-reply-node-name" name="node-type-quick-reply-node-name" placeholder="" required>
						<label id="node-type-quick-reply-node-name-unique-error" class="error d-none" for="node-type-quick-reply-node-name">This should be unique.</label>
						<small class="form-text small-text-color">Node name is case-sensitive</small>
					</div>
					<div class="form-group">
						<label for="node-type-quick-reply-sending-time">Scheduled Time</label>
						<input type="text" class="form-control" id="node-type-quick-reply-sending-time" name="node-type-quick-reply-sending-time" placeholder="">
						<small class="form-text small-text-color"><u>No floating point</u> value, integer only.  Ex: "+0.5 hour" should be "+30 minutes"</small>
						<small class="form-text small-text-color">Leave it empty if send out immediately</small>
					</div>
					<div class="form-group">
						<select class="form-control" id="node-type-quick-reply-template" name="node-type-quick-reply-template" required>
							<option value="" disabled selected>請選擇</option>
@if(isset($quickReplyTemplate))
@foreach($quickReplyTemplate as $template )
							<option value="{{ $template['id'] ?? ''}}" {{ ($selectedTemplate == $template['id'])? "selected":"" }}>{{$template['name'] ?? ''}} </option>
@endforeach
@endif
						</select>
					</div>
					<div class="form-group">
						<label for="node-type-quick-reply-text">Text</label>
						<textarea class="form-control" id="node-type-quick-reply-text" rows="5" readonly="readonly"></textarea>
						<small class="form-text small-text-color">* Edit not available, please edit in Quick Reply Template section</small>
						<!-- @include('foso/campaigns/common/dynamic_fields') -->
					</div>
					<div class="foso-repeat node-type-quick-reply-options" data-class="node-type-quick-reply-options" data-count="1">
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label for="node-type-quick-reply-option-1">Option 1</label>
									<input type="text" class="form-control node-type-quick-reply-option" id="node-type-quick-reply-option-1" name="node-type-quick-reply-option-1" data-code="" placeholder="" readonly="readonly">
								</div>
							</div>
							<div class="col-sm-8">
								<div class="form-group">
									<label for="node-type-quick-reply-node-name-next-1">Next Node Name</label>
									<input type="text" class="form-control node-type-quick-reply-node-name-next node-type-node-name next" id="node-type-quick-reply-node-name-next-option-1" name="node-type-quick-reply-node-name-next-option-1" placeholder="">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label for="node-type-quick-reply-option-2">Option 2</label>
									<input type="text" class="form-control node-type-quick-reply-option" id="node-type-quick-reply-option-2" name="node-type-quick-reply-option-2" data-code="" placeholder="" readonly="readonly">
								</div>
							</div>
							<div class="col-sm-8">
								<div class="form-group">
									<label for="node-type-quick-reply-node-name-next-2">Next Node Name</label>
									<input type="text" class="form-control node-type-quick-reply-node-name-next node-type-node-name next" id="node-type-quick-reply-node-name-next-option-2" name="node-type-quick-reply-node-name-next-option-2" placeholder="">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label for="node-type-quick-reply-option-3">Option 3</label>
									<input type="text" class="form-control node-type-quick-reply-option" id="node-type-quick-reply-option-3" name="node-type-quick-reply-option-3" data-code="" placeholder="" readonly="readonly">
								</div>
							</div>
							<div class="col-sm-8">
								<div class="form-group">
									<label for="node-type-quick-reply-node-name-next-3">Next Node Name</label>
									<input type="text" class="form-control node-type-quick-reply-node-name-next node-type-node-name next" id="node-type-quick-reply-node-name-next-option-3" name="node-type-quick-reply-node-name-next-option-3" placeholder="">
								</div>
							</div>
						</div>
					</div>
				</form>
				<div class="readonly">
					<hr />
					<h5>Customer Data</h5>
					<div class="node_data"></div>
				</div>
			</div>
			<div class="modal-footer d-flex justify-content-between flex-row-reverse">
				<button type="button" class="btn btn-danger button-save-node edit">Save</button>
				<button type="button" class="btn btn-outline-danger button-delete-node d-none edit">Delete</button>
			</div>
		</div>
	</div>
</div>

<div class="modal node-type-issue-coupon" data-class="node-type-issue-coupon" tabindex="-1" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">300 Issue Coupon Node</h5>
				<button type="button" class="close closeCJModalBtn" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="node-type-issue-coupon-form">
					<div class="form-group">
						<label for="node-type-issue-coupon-node-name">Node Name</label>
						<input type="text" class="form-control node-type-node-name unique" id="node-type-issue-coupon-node-name" name="node-type-issue-coupon-node-name" placeholder="" required>
						<label id="node-type-issue-coupon-node-name-unique-error" class="error d-none" for="node-type-issue-coupon-node-name">This should be unique.</label>
						<small class="form-text small-text-color">Node name is case-sensitive</small>
					</div>
					<div class="form-group">
						<label for="node-type-issue-coupon-redemption-period-id">Redemption Period ID</label>
						<input type="text" class="form-control" id="node-type-issue-coupon-redemption-period-id" name="node-type-issue-coupon-redemption-period-id" placeholder="" required>
						<small class="form-text small-text-color">Available only when Offer Coupon Type is Randomly-generated</small>
						<small class="form-text small-text-color">&gt; 0 = Specific store and period</small>
						<small class="form-text small-text-color">0 = Online store</small>
					</div>
					<div class="form-group">
						<label for="node-type-issue-coupon-redemption-store">Redemption Store</label>
						<input type="text" class="form-control" id="node-type-issue-coupon-redemption-store" name="node-type-issue-coupon-redemption-store" placeholder="" required>
						<small class="form-text small-text-color">use-form = Base on selected store in offer details form</small>
						<small class="form-text small-text-color">Otherwise, it is store code itself</small>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<label for="node-type-issue-coupon-node-name-next-success">Success</label>
						</div>
						<div class="col-sm-6">
							<input type="text" class="form-control node-type-node-name next" id="node-type-issue-coupon-node-name-next-success" name="node-type-issue-coupon-node-name-next-success" placeholder="Next Node Name">
						</div>
					</div>
					<br />
					<div class="row">
						<div class="col-sm-6">
							<label for="node-type-issue-coupon-node-name-next-out-of-quota">Out of Quota</label>
						</div>
						<div class="col-sm-6">
							<input type="text" class="form-control node-type-node-name next" id="node-type-issue-coupon-node-name-next-out-of-quota" name="node-type-issue-coupon-node-name-next-out-of-quota" placeholder="Next Node Name">
						</div>
					</div>
					<br />
					<div class="row">
						<div class="col-sm-6">
							<label for="node-type-issue-coupon-node-name-next-expiry">Expiry</label>
						</div>
						<div class="col-sm-6">
							<input type="text" class="form-control node-type-node-name next" id="node-type-issue-coupon-node-name-next-expiry" name="node-type-issue-coupon-node-name-next-expiry" placeholder="Next Node Name">
						</div>
					</div>
					<br />
					<div class="row">
						<div class="col-sm-6">
							<label for="node-type-issue-coupon-node-name-next-coupon-exists">Coupon Already Exists</label>
						</div>
						<div class="col-sm-6">
							<input type="text" class="form-control node-type-node-name next" id="node-type-issue-coupon-node-name-next-coupon-exists" name="node-type-issue-coupon-node-name-next-coupon-exists" placeholder="Next Node Name">
						</div>
					</div>
					<br />
					<div class="row">
						<div class="col-sm-6">
							<label for="node-type-issue-coupon-node-name-next-webhook-error">Webhook Error</label>
						</div>
						<div class="col-sm-6">
							<input type="text" class="form-control node-type-node-name next" id="node-type-issue-coupon-node-name-next-webhook-error" name="node-type-issue-coupon-node-name-next-webhook-error" placeholder="Next Node Name">
						</div>
					</div>
				</form>
				<div class="readonly">
					<hr />
					<h5>Customer Data</h5>
					<div class="node_data"></div>
				</div>
			</div>
			<div class="modal-footer d-flex justify-content-between flex-row-reverse">
				<button type="button" class="btn btn-danger button-save-node edit">Save</button>
				<button type="button" class="btn btn-outline-danger button-delete-node d-none edit">Delete</button>
			</div>
		</div>
	</div>
</div>

<div class="modal node-type-cancel-reminder" data-class="node-type-cancel-reminder" tabindex="-1" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">310 Cancel Reminder Node</h5>
				<button type="button" class="close closeCJModalBtn" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="node-type-cancel-reminder-form">
					<div class="form-group">
						<label for="node-type-cancel-reminder-node-name">Node Name</label>
						<input type="text" class="form-control node-type-node-name unique" id="node-type-cancel-reminder-node-name" name="node-type-cancel-reminder-node-name" placeholder="" required>
						<label id="node-type-cancel-reminder-node-name-unique-error" class="error d-none" for="node-type-cancel-reminder-node-name">This should be unique.</label>
						<small class="form-text small-text-color">Node name is case-sensitive</small>
					</div>
					<div class="form-group">
						<label for="node-type-cancel-reminder-node-name-cancel">Node Name to be Cancelled</label>
						<input type="text" class="form-control node-type-node-name" id="node-type-cancel-reminder-node-name-cancel" name="node-type-cancel-reminder-node-name-cancel" placeholder="">
					</div>
					<div class="form-group">
						<label for="node-type-cancel-reminder-node-name-next">Next Node Name</label>
						<input type="text" class="form-control node-type-node-name next" id="node-type-cancel-reminder-node-name-next" name="node-type-cancel-reminder-node-name-next" placeholder="">
						<small class="form-text small-text-color">Leave it empty if ends</small>
					</div>
				</form>
				<div class="readonly">
					<hr />
					<h5>Customer Data</h5>
					<div class="node_data"></div>
				</div>
			</div>
			<div class="modal-footer d-flex justify-content-between flex-row-reverse">
				<button type="button" class="btn btn-danger button-save-node edit">Save</button>
				<button type="button" class="btn btn-outline-danger button-delete-node d-none edit">Delete</button>
			</div>
		</div>
	</div>
</div>

<div class="modal node-type-cancel-journey" data-class="node-type-cancel-journey" tabindex="-1" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">320 Cancel Journey Node</h5>
				<button type="button" class="close closeCJModalBtn" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="node-type-cancel-journey-form">
					<div class="form-group">
						<label for="node-type-cancel-journey-node-name">Node Name</label>
						<input type="text" class="form-control node-type-node-name unique" id="node-type-cancel-journey-node-name" name="node-type-cancel-journey-node-name" placeholder="" required>
						<label id="node-type-cancel-journey-node-name-unique-error" class="error d-none" for="node-type-cancel-journey-node-name">This should be unique.</label>
						<small class="form-text small-text-color">Node name is case-sensitive</small>
					</div>
					<div class="form-group">
						<label for="node-type-cancel-journey-node-name-cancel">Node Name to be Cancelled</label>
						<input type="text" class="form-control node-type-node-name" id="node-type-cancel-journey-node-name-cancel" name="node-type-cancel-journey-node-name-cancel" placeholder="">
					</div>
					<div class="form-group">
						<label for="node-type-cancel-journey-node-name-next">Next Node Name</label>
						<input type="text" class="form-control node-type-node-name next" id="node-type-cancel-journey-node-name-next" name="node-type-cancel-journey-node-name-next" placeholder="">
						<small class="form-text small-text-color">Leave it empty if ends</small>
					</div>
				</form>
				<div class="readonly">
					<hr />
					<h5>Customer Data</h5>
					<div class="node_data"></div>
				</div>
			</div>
			<div class="modal-footer d-flex justify-content-between flex-row-reverse">
				<button type="button" class="btn btn-danger button-save-node edit">Save</button>
				<button type="button" class="btn btn-outline-danger button-delete-node d-none edit">Delete</button>
			</div>
		</div>
	</div>
</div>

<div class="modal node-type-referral" data-class="node-type-referral" tabindex="-1" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">330 Referral Node</h5>
				<button type="button" class="close closeCJModalBtn" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="node-type-referral-form">
					<div class="form-group">
						<label for="node-type-referral-node-name">Node Name</label>
						<input type="text" class="form-control node-type-node-name unique" id="node-type-referral-node-name" name="node-type-referral-node-name" placeholder="" required>
						<label id="node-type-referral-node-name-unique-error" class="error d-none" for="node-type-referral-node-name">This should be unique.</label>
						<small class="form-text small-text-color">Node name is case-sensitive</small>
					</div>
					<div class="form-group">
						<label for="node-type-referral-requirement">Referral Requirement</label>
						<input type="number" class="form-control node-type-requirement unique" id="node-type-referral-requirement" name="node-type-referral-requirement" placeholder="" value="1" required>
						<label id="node-type-referral-node-name-unique-error" class="error d-none" for="node-type-referral-node-name">This should be unique.</label>
					</div>
					<div class="form-group">
						<label for="node-type-referral-text">Done Text</label>
						<textarea class="form-control" id="node-type-referral-text" name="node-type-referral-text" rows="5"></textarea>
						<small class="form-text small-text-color">It will be sent when referral requirement has been achieved, the rest of journey will not run but due to 24 hours window</small>
						<small class="form-text small-text-color">This message is '<u>Template Message</u>'</small>
					</div>
					<div class="form-group">
						<label for="node-type-referral-in-progress-text">In Progress Text</label>
						<textarea class="form-control" id="node-type-referral-in-progress-text" name="node-type-in-progress-text" rows="5"></textarea>
						<small class="form-text small-text-color">When user send message to chatbot but requirement is not achieved yet</small>
						@include('foso/campaigns/common/dynamic_fields')
					</div>
					<div class="form-group">
						<label for="node-type-referral-node-name-next">Next Node Name</label>
						<input type="text" class="form-control node-type-node-name next" id="node-type-referral-node-name-next" name="node-type-referral-node-name-next" placeholder="">
						<small class="form-text small-text-color">Leave it empty if ends</small>
					</div>
				</form>
				<div class="readonly">
					<hr />
					<h5>Customer Data</h5>
					<div class="node_data"></div>
				</div>
			</div>
			<div class="modal-footer d-flex justify-content-between flex-row-reverse">
				<button type="button" class="btn btn-danger button-save-node edit">Save</button>
				<button type="button" class="btn btn-outline-danger button-delete-node d-none edit">Delete</button>
			</div>
		</div>
	</div>
</div>

<div class="modal node-type-referral-completion" data-class="node-type-referral-completion" tabindex="-1" data-backdrop="static" data-keyboard="false" data-default-parameterized-link-image='{"apple":"https://upload.wikimedia.org/wikipedia/commons/thumb/a/a1/Apple_I_Computer.jpg/1920px-Apple_I_Computer.jpg","coupon":"https://go.harborfreight.com/wp-content/uploads/2020/08/23637148-save-20-percent-at-harbor-freight-through-august-31-2020-b.png","___default":"https://d17mj6xr9uykrr.cloudfront.net/Pictures/2000x2000fit/2/1/6/67216_default_115473.jpg"}'>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">335 Referral Completion Node</h5>
				<button type="button" class="close closeCJModalBtn" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="node-type-referral-completion-form">
					<div class="form-group">
						<label for="node-type-referral-completion-node-name">Node Name</label>
						<input type="text" class="form-control node-type-referral-completion-node-name unique" id="node-type-referral-completion-node-name" name="node-type-referral-completion-node-name" placeholder="" required>
						<label id="node-type-referral-completion-node-name-unique-error" class="error d-none" for="node-type-referral-completion-node-name">This should be unique.</label>
						<small class="form-text small-text-color">Node name is case-sensitive</small>
					</div>
					<div class="form-group">
						<label for="node-type-referral-completion-node-name-next">Next Node Name</label>
						<input type="text" class="form-control node-type-node-name next" id="node-type-referral-completion-node-name-next" name="node-type-referral-completion-node-name-next" placeholder="">
						<small class="form-text small-text-color">Leave it empty if ends</small>
					</div>
				</form>
				<div class="readonly">
					<hr />
					<h5>Customer Data</h5>
					<div class="node_data"></div>
				</div>
			</div>
			<div class="modal-footer d-flex justify-content-between flex-row-reverse">
				<button type="button" class="btn btn-danger button-save-node edit">Save</button>
				<button type="button" class="btn btn-outline-danger button-delete-node d-none edit">Delete</button>
			</div>
		</div>
	</div>
</div>




<div class="modal node-type-get-form-data" data-class="node-type-get-form-data" tabindex="-1" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">340 Get Form Data Node</h5>
				<button type="button" class="close closeCJModalBtn" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="node-type-get-form-data-form">
					<div class="form-group">
						<label for="node-type-get-form-data-node-name">Node Name</label>
						<input type="text" class="form-control node-type-node-name unique" id="node-type-get-form-data-node-name" name="node-type-get-form-data-node-name" placeholder="" required>
						<label id="node-type-get-form-data-node-name-unique-error" class="error d-none" for="node-type-get-form-data-node-name">This should be unique.</label>
						<small class="form-text small-text-color">Node name is case-sensitive</small>
					</div>

					<div class="form-group">
						<label for="node-type-get-form-data-node-name-next">Next Node Name</label>
						<input type="text" class="form-control node-type-node-name next" id="node-type-get-form-data-node-name-next" name="node-type-get-form-data-node-name-next" placeholder="">
						<small class="form-text small-text-color">Leave it empty if ends</small>
					</div>

					<div class="form-group">
						<label for="node-type-get-form-data-node-name-fail">Fail Node Name</label>
						<input type="text" class="form-control node-type-node-name fail" id="node-type-get-form-data-node-name-fail" name="node-type-get-form-data-node-name-fail" placeholder="">
						<small class="form-text small-text-color">Leave it empty if ends</small>
					</div>
				</form>
				<div class="readonly">
					<hr />
					<h5>Customer Data</h5>
					<div class="node_data"></div>
				</div>
			</div>
			<div class="modal-footer d-flex justify-content-between flex-row-reverse">
				<button type="button" class="btn btn-danger button-save-node edit">Save</button>
				<button type="button" class="btn btn-outline-danger button-delete-node d-none edit">Delete</button>
			</div>
		</div>
	</div>
</div>

<div class="modal node-type-date-comparison" data-class="node-type-date-comparison" tabindex="-1" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">400 Date Comparison Node</h5>
				<button type="button" class="close closeCJModalBtn" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="node-type-date-comparison-form">
					<div class="form-group">
						<label for="node-type-date-comparison-node-name">Node Name</label>
						<input type="text" class="form-control node-type-node-name unique" id="node-type-date-comparison-node-name" name="node-type-date-comparison-node-name" placeholder="" required>
						<label id="node-type-date-comparison-node-name-unique-error" class="error d-none" for="node-type-date-comparison-node-name">This should be unique.</label>
						<small class="form-text small-text-color">Node name is case-sensitive</small>
					</div>
					<div class="row">
						<div class="col-sm-4">
							If now()
						</div>
						<div class="col-sm-4">
							<select class="form-control" id="node-type-date-comparison-condition" name="node-type-date-comparison-condition" required>
								<option value="">Condition</option>
								<option value="le">&lt;=</option>
								<option value="e">=</option>
								<option value="ge">&gt;=</option>
							</select>
						</div>
						<div class="col-sm-4">
							<input type="text" class="form-control" id="node-type-date-comparison-datetime" name="node-type-date-comparison-datetime" placeholder="Datetime" required>
						</div>
					</div>
					<br />
					<div class="row">
						<div class="col-sm-4">
							<label for="node-type-date-comparison-node-name-next-then">Then</label>
						</div>
						<div class="col-sm-8">
							<input type="text" class="form-control node-type-node-name next" id="node-type-date-comparison-node-name-next-then" name="node-type-date-comparison-node-name-next-then" placeholder="Next Node Name">
						</div>
					</div>
					<br />
					<div class="row">
						<div class="col-sm-4">
							<label for="node-type-date-comparison-node-name-next-out-of-quota">Else</label>
						</div>
						<div class="col-sm-8">
							<input type="text" class="form-control node-type-node-name next" id="node-type-date-comparison-node-name-next-else" name="node-type-date-comparison-node-name-next-else" placeholder="Next Node Name">
						</div>
					</div>
				</form>
				<div class="readonly">
					<hr />
					<h5>Customer Data</h5>
					<div class="node_data"></div>
				</div>
			</div>
			<div class="modal-footer d-flex justify-content-between flex-row-reverse">
				<button type="button" class="btn btn-danger button-save-node edit">Save</button>
				<button type="button" class="btn btn-outline-danger button-delete-node d-none edit">Delete</button>
			</div>
		</div>
	</div>
</div>

<div class="modal node-type-coupon-expiry-check" data-class="node-type-coupon-expiry-check" tabindex="-1" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">410 Coupon Expiry Check Node</h5>
				<button type="button" class="close closeCJModalBtn" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="node-type-coupon-expiry-check-form">
					<div class="form-group">
						<label for="node-type-coupon-expiry-check-node-name">Node Name</label>
						<input type="text" class="form-control node-type-node-name unique" id="node-type-coupon-expiry-check-node-name" name="node-type-coupon-expiry-check-node-name" placeholder="" required>
						<label id="node-type-coupon-expiry-check-node-name-unique-error" class="error d-none" for="node-type-coupon-expiry-check-node-name">This should be unique.</label>
						<small class="form-text small-text-color">Node name is case-sensitive</small>
					</div>
					<div class="row">
						<div class="col-sm-4">
							<label for="node-type-coupon-expiry-check-node-name-next-valid">Valid</label>
						</div>
						<div class="col-sm-8">
							<input type="text" class="form-control node-type-node-name next" id="node-type-coupon-expiry-check-node-name-next-valid" name="node-type-coupon-expiry-check-node-name-next-valid" placeholder="Next Node Name">
						</div>
					</div>
					<br />
					<div class="row">
						<div class="col-sm-4">
							<label for="node-type-coupon-expiry-check-node-name-next-expiry">Expiry</label>
						</div>
						<div class="col-sm-8">
							<input type="text" class="form-control node-type-node-name next" id="node-type-coupon-expiry-check-node-name-next-expiry" name="node-type-coupon-expiry-check-node-name-next-expiry" placeholder="Next Node Name">
						</div>
					</div>
				</form>
				<div class="readonly">
					<hr />
					<h5>Customer Data</h5>
					<div class="node_data"></div>
				</div>
			</div>
			<div class="modal-footer d-flex justify-content-between flex-row-reverse">
				<button type="button" class="btn btn-danger button-save-node edit">Save</button>
				<button type="button" class="btn btn-outline-danger button-delete-node d-none edit">Delete</button>
			</div>
		</div>
	</div>
</div>

<div class="modal node-type-payment" data-class="node-type-payment" tabindex="-1" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">500 Payment Node</h5>
				<button type="button" class="close closeCJModalBtn" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="node-type-payment-form">
					<div class="form-group">
						<label for="node-type-payment-node-name">Node Name</label>
						<input type="text" class="form-control node-type-node-name unique" id="node-type-payment-node-name" name="node-type-payment-node-name" placeholder="" required>
						<label id="node-type-payment-node-name-unique-error" class="error d-none" for="node-type-payment-node-name">This should be unique.</label>
						<small class="form-text small-text-color">Node name is case-sensitive</small>
					</div>
					<div class="form-group">
						<label for="node-type-payment-gateway">Payment Gateway</label>
						<select class="form-control" id="node-type-payment-gateway" name="node-type-payment-gateway" required>
							<option value="ccba" selected>CCBA</option>
						</select>
					</div>
					<div class="form-group">
						<label for="node-type-payment-message">Payment Message</label>
						<textarea class="form-control" id="node-type-payment-message" name="node-type-payment-message" rows="5"></textarea>
						@include('foso/campaigns/common/dynamic_fields_with_payment')
					</div>
					<div class="row">
						<div class="col-sm-6">
							<label for="node-type-payment-item-name">Item Name on Bill</label>
							<input type="text" class="form-control node-type-requirement unique" id="node-type-payment-item-name" name="node-type-payment-item-name" placeholder="Item name" value="" required>
						</div>
						<div class="col-sm-6">
							<label for="node-type-payment-item-price">HKD Price</label>
							<input type="number" class="form-control node-type-requirement unique" id="node-type-payment-item-price" name="node-type-payment-item-price" placeholder="1.20" value="1.00" required>
						</div>
					</div>
					<br />
					<div class="form-group">
						<label for="node-type-payment-expiry-time">Payment Link Expires In</label>
						<input type="text" class="form-control" id="node-type-payment-expiry-time" name="node-type-payment-expiry-time" placeholder="+24 hours" value="+24 hours">
						<small class="form-text small-text-color"><u>No floating point</u> value, integer only.  Ex: "+0.5 hour" should be "+30 minutes"</small>
					</div>
					<div class="form-group">
						<label for="node-type-payment-fail-message">Payment Fail Message</label>
						<textarea class="form-control" id="node-type-payment-fail-message" name="node-type-payment-fail-message" rows="3" required></textarea>
						<small class="form-text small-text-color">It will be sent when payment failed.  This is '<u>Template Message</u>'</small>
						@include('foso/campaigns/common/dynamic_fields_with_payment')
					</div>
					<div class="form-group">
						<label for="node-type-payment-node-name-next">Next Node Name</label>
						<input type="text" class="form-control node-type-node-name next" id="node-type-payment-node-name-next" name="node-type-payment-node-name-next" placeholder="">
						<small class="form-text small-text-color">When payment success, leave it empty if ends</small>
					</div>
				</form>
				<div class="readonly">
					<hr />
					<h5>Customer Data</h5>
					<div class="node_data"></div>
				</div>
			</div>
			<div class="modal-footer d-flex justify-content-between flex-row-reverse">
				<button type="button" class="btn btn-danger button-save-node edit">Save</button>
				<button type="button" class="btn btn-outline-danger button-delete-node d-none edit">Delete</button>
			</div>
		</div>
	</div>
</div>

<div class="modal node-type-nft-redeem" data-class="node-type-nft-redeem" tabindex="-1" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">600 Redeem NFT Node</h5>
				<button type="button" class="close closeCJModalBtn" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="node-type-nft-redeem-form">
					<div class="form-group">
						<label for="node-type-nft-redeem-node-name">Node Name</label>
						<input type="text" class="form-control node-type-node-name unique" id="node-type-nft-redeem-node-name" name="node-type-nft-redeem-node-name" placeholder="" required>
						<label id="node-type-nft-redeem-node-name-unique-error" class="error d-none" for="node-type-nft-redeem-node-name">This should be unique.</label>
						<small class="form-text small-text-color">Node name is case-sensitive</small>
					</div>
					<div class="form-group">
						<label for="node-type-nft-redeem-vendor">Vendor</label>
						<select class="form-control" id="node-type-nft-redeem-vendor" name="node-type-nft-redeem-vendor" required>
							<option value="amuro">Amuro</option>
						</select>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<label for="node-type-nft-redeem-node-name-next-success">Success</label>
						</div>
						<div class="col-sm-6">
							<input type="text" class="form-control node-type-node-name next" id="node-type-nft-redeem-node-name-next-success" name="node-type-nft-redeem-node-name-next-success" placeholder="Next Node Name">
						</div>
					</div>
					<br />
					<div class="row">
						<div class="col-sm-6">
							<label for="node-type-nft-redeem-node-name-next-failed">Failed</label>
						</div>
						<div class="col-sm-6">
							<input type="text" class="form-control node-type-node-name next" id="node-type-nft-redeem-node-name-next-failed" name="node-type-nft-redeem-node-name-next-failed" placeholder="Next Node Name">
						</div>
					</div>
				</form>
				<div class="readonly">
					<hr />
					<h5>Customer Data</h5>
					<div class="node_data"></div>
				</div>
			</div>
			<div class="modal-footer d-flex justify-content-between flex-row-reverse">
				<button type="button" class="btn btn-danger button-save-node edit">Save</button>
				<button type="button" class="btn btn-outline-danger button-delete-node d-none edit">Delete</button>
			</div>
		</div>
	</div>
</div>

<div class="modal node-type-issue-point" data-class="node-type-issue-point" tabindex="-1" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">700 Issue Point Node</h5>
				<button type="button" class="close closeCJModalBtn" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="node-type-issue-point-form">
					<div class="form-group">
						<label for="node-type-issue-point-node-name">Node Name</label>
						<input type="text" class="form-control node-type-node-name unique" id="node-type-issue-point-node-name" name="node-type-issue-point-node-name" placeholder="" required>
						<label id="node-type-issue-point-node-name-unique-error" class="error d-none" for="node-type-issue-point-node-name">This should be unique.</label>
						<small class="form-text small-text-color">Node name is case-sensitive</small>
					</div>
					<div class="form-group">
						<label for="node-type-issue-point-point">Point</label>
						<input type="text" class="form-control" id="node-type-issue-point-point" name="node-type-issue-point-point" placeholder="" required>
					</div>
					<div class="form-group">
						<input type="hidden" value="zh-HK" id="node-type-issue-point-description-lang">
						<label for="node-type-issue-point-description">Description</label>
						<textarea class="form-control" id="node-type-issue-point-description" name="node-type-issue-point-description" rows="5"></textarea>
					</div>
					<div class="form-group">
						<label for="node-type-message-node-name-next">Next Node Name</label>
						<input type="text" class="form-control node-type-node-name next" id="node-type-issue-point-node-name-next" name="node-type-issue-point-node-name-next" placeholder="">
						<small class="form-text small-text-color">Leave it empty if ends</small>
					</div>
				</form>
				<div class="readonly">
					<hr />
					<h5>Customer Data</h5>
					<div class="node_data"></div>
				</div>
			</div>
			<div class="modal-footer d-flex justify-content-between flex-row-reverse">
				<button type="button" class="btn btn-danger button-save-node edit">Save</button>
				<button type="button" class="btn btn-outline-danger button-delete-node d-none edit">Delete</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="{{ asset('js/common.js') }}?v=1"></script>

{{-- sortableJS lib --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-sortablejs@latest/jquery-sortable.js"></script>

<script>
	$(document).ready(function()  {

		//  When leave page, show loading
		window.onbeforeunload = function(e)  {
			showLoading();
		};

		$('[data-toggle="tooltip"]').tooltip();
	});

	$(function()  {
		var ui = $('<ui>');
		var net = $('<net>');
		var foso_data = $('<data>');
		foso_data.data('coupon_type', '{{ $offer->coupon_type }}');

		$('#flowchart-js-code').on('change', function()  {
			var chart = flowchart.parse($(this).val());
			$('#flowchart-js-canvas').html('');
			chart.drawSVG('flowchart-js-canvas', {
				'x': 0,
				'y': 0,
				'flowstate': {
					'completed': {
						'fill': 'grey'
					}
				}
			});

			//  Auto will cause error and export empty PNG
// 			$('#flowchart-js-canvas > svg').attr('width', '100%');
// 			$('#flowchart-js-canvas > svg').attr('height', 'auto');
			$('#flowchart-js-canvas > svg').attr('width', '1024');
			$('#flowchart-js-canvas > svg').attr('height', '100%');

			$('#flowchart-js-canvas > svg').css('max-width', '1024px');
		});

		$('.button-add-element').on('click touch', function()  {
			var node_type = $(this).data('node-type');
			var target_modal = $('.modal.' + node_type);
			target_modal.data('node', null);
			target_modal.modal('show');
		});

		$('#button-export').on('click touch', function()  {
			var $flowchart = $('#flowchart-js-canvas svg');
			var flowchartWidth = $flowchart.width();
			var aspectRatio = flowchartWidth / $flowchart.height();
			var $svg = $flowchart.clone();
			var svgWidth = 1024;
			if ( flowchartWidth > svgWidth ) svgWidth = flowchartWidth;
			$svg.width(svgWidth).height(svgWidth/aspectRatio);

			var svg_string = new XMLSerializer().serializeToString($svg[0]);
			const canvas = $('#canvas-for-export')[0];
			const context = canvas.getContext('2d');

			canvg.Canvg.fromString(context, svg_string).start();

			var link = $('<a>');
			link.attr('download', 'flowchart.png');
			link[0].href = canvas.toDataURL('image/png').replace(/^data:image\/[^;]/, 'data:application/octet-stream');
			link[0].href = canvas.toDataURL('image/png').replace(/^data:image\/[^;]/, 'data:application/octet-stream');
			link[0].click();

			hideLoading();
		});

		$('#button-report-csv').on('click touch', function()  {
			showLoading();

			var link = $('<a>');
			link[0].href = "{{ route('foso.campaigns.offer.customerjourney.report-csv.file', ['offer_code' => $offer->offer_code]) }}";
			link[0].click();

			hideLoading();
		});

		$('#button-csv').on('click touch', function()  {
			showLoading();

			var link = $('<a>');
			link[0].href = "{{ route('foso.campaigns.offer.customerjourney.csv.file', ['offer_code' => $offer->offer_code]) }}";
			link[0].click();

			hideLoading();
		});

		$('#button-clear-whitelisted-records').on('click touch', function()  {
			showLoading();
			$.ajax({
				type: "POST",
				data: {},
				dataType: "json",
				url: '{{ route("foso.campaigns.offer.customerjourney.clearwhitelisted.json", ["offer_code"=>$offerCode]) }}',
				success: function(data)  {
					alert('Done');
					net.trigger('get_all_nodes');
					hideLoading();
				}
			});
		});

		$('#button-archive-all-records').on('click touch', function()  {
			showLoading();
			$.ajax({
				type: "POST",
				data: {},
				dataType: "json",
				url: '{{ route("foso.campaigns.offer.customerjourney.archive.json", ["offer_code"=>$offerCode]) }}',
				success: function(data)  {
					alert('Done');
					location.reload();
				}
			});
		});

		//type=0
		$('.modal.node-type-unknown').each(function()  {
			var this_modal = $(this);

			$(this).find('.button-select-node-type').on('click touch', function()  {
				var node_type = $(this).data('node-type');
				this_modal.modal('hide');

				var target_modal = $('.modal.' + node_type);
				target_modal.data('node', this_modal.data('node'));
				target_modal.modal('show');
			});
		});

		//type=100
		$('.modal.node-type-message').each(function()  {
			var this_modal = $(this);

			$(this).on('shown.bs.modal', function()  {
				$(this).trigger('reset_fields');

				var node = $(this).data('node');
				if (node !== null)  {
					$(this).find('#node-type-message-node-name').val(node['node_name']);
					if (node['node_settings'])  {
						$(this).find('#node-type-message-sending-time').val(node['node_settings']['schedule'] ? node['node_settings']['schedule'] : '');
						$(this).find('#node-type-message-image-path').val(node['node_settings']['media']);
						$(this).find('#node-type-message-text').val(node['node_settings']['message']);
						$(this).find('#node-type-message-node-name-next').val(node['node_settings']['nextNode']);
						$(this).find('.dropify').data('image', node['node_settings']['media']);
						$(this).find('.dropify').trigger('show_image');
					}
					$(this).find('.button-delete-node').removeClass('d-none');
					var characterCount = $('#node-type-message-text').val().length;
					var current = $('#current');
					current.text(characterCount);
				}
			});

			$(this).on('reset_fields', function()  {
				$(this).find('#node-type-message-node-name').val('');
				$(this).find('#node-type-message-sending-time').val('');
				$(this).find('#node-type-message-image-path').val('');
				$(this).find('#node-type-message-text').val('');
				$(this).find('#node-type-message-node-name-next').val('');
				$(this).find('.button-delete-node').addClass('d-none');

				$(this).find('.dropify').trigger('reset');

				var characterCount = $('#node-type-message-text').val().length;
				var current = $('#current');
				current.text(characterCount);
			});

			$(this).find('.button-save-node').on('form_submit', function()  {
				if (this_modal.find('.node-type-message-form').valid() === false)  {

					console.log("### Message form not found...");
					return;
				}

				var data = {
					'node_type': 100,
					'node_name': this_modal.find('#node-type-message-node-name').val(),
					'node_settings': {
						'schedule': this_modal.find('#node-type-message-sending-time').val(),
						'media': this_modal.find('#node-type-message-image-path').val(),
						'message': this_modal.find('#node-type-message-text').val(),
						'nextNode': this_modal.find('#node-type-message-node-name-next').val(),
					}
				}
				$(this).data('data_submit', data);
			});

			$(this).find('#node-type-message-image-path').on('change', function()  {
				var input_value = $(this).val();
				var image = '';

				if (input_value.search(/[\{\}]/g) > -1)  {
					var default_image_set = this_modal.data('default-parameterized-link-image');
					for (var keyword in default_image_set)  {
						var default_image = default_image_set[keyword];
						if (input_value.search(new RegExp(keyword, 'g')) > -1)  {
							image = default_image;
						}
					}
					if (image === '')  {
						image = default_image_set['___default'];
					}
				} else {
					image = input_value;
				}

				this_modal.find('.dropify').trigger('reset');
				this_modal.find('.dropify').data('image', image);
				this_modal.find('.dropify').trigger('show_image');
			});
		});

		$('#node-type-message-text').keyup(function()  {

			var characterCount = $(this).val().length;
				current = $('#current');
				maximum = $('#maximum');
				theCount = $('#the-count');
		
			current.text(characterCount);
	
			if (characterCount >= 1000)  {
				maximum.css('color', 'red');
				current.css('color', 'red');
				theCount.css('font-weight','bold');
			} else {
				current.css('color','#a0a0a0');
				maximum.css('color','#a0a0a0');
				theCount.css('font-weight','normal');
			}
		});

		//type=200
		$('.modal.node-type-question').each(function()  {
			var this_modal = $(this);

			$(this).on('shown.bs.modal', function()  {
				$(this).trigger('reset_fields');

				var node = $(this).data('node');
				if (node !== null)  {
					$(this).find('#node-type-question-node-name').val(node['node_name']);
					if (node['node_settings'])  {

						$(this).find('#node-type-question-sending-time').val(node['node_settings']['schedule'] ? node['node_settings']['schedule'] : '');
						$(this).find('#node-type-question-image-path').val(node['node_settings']['media']);
						$(this).find('#node-type-question-text').val(node['node_settings']['message']);
						$(this).find('.dropify').data('image', node['node_settings']['media']);

						var options = node['node_settings']['options'];
						var jq_options = $(this).find('.foso-repeat.node-type-question-options');
						jq_options.data('count', Object.keys(options).length);
						jq_options.trigger('change');

						var o = 1;
						for (var option in options)  {
							var node_name_next = options[option];
							$(this).find('#node-type-question-option-'+o).val(option);
							$(this).find('#node-type-question-node-name-next-'+o).val(node_name_next);
							o++;
						}
					}
					$(this).find('.dropify').trigger('show_image');
					$(this).find('.button-delete-node').removeClass('d-none');
				}
			});

			$(this).on('reset_fields', function()  {
				$(this).find('#node-type-question-node-name').val('');
				$(this).find('#node-type-question-sending-time').val('');
				$(this).find('#node-type-question-image-path').val('');
				$(this).find('#node-type-question-text').val('');
				$(this).find('#node-type-question-node-name-next').val('');
				$(this).find('.button-delete-node').addClass('d-none');

				$(this).find('.foso-repeat.node-type-question-options').trigger('reset');

				$(this).find('.dropify').trigger('reset');
			});

			$(this).find('.button-save-node').on('form_submit', function()  {
				if (this_modal.find('.node-type-question-form').valid() === false)  {

					console.log("### Message form not found...");
					return;
				}

				var data_options = {};
				var i = 1;
				var jq_option = null;
				do  {
					jq_option = this_modal.find('#node-type-question-option-' + i);
					data_options[jq_option.val()] = this_modal.find('#node-type-question-node-name-next-' + i).val();
					i++;
				}  while (!(jq_option.length <= 0));

				var data = {
					'node_type': 200,
					'node_name': this_modal.find('#node-type-question-node-name').val(),
					'node_settings': {
						'schedule': this_modal.find('#node-type-question-sending-time').val(),
						'media': this_modal.find('#node-type-question-image-path').val(),
						'message': this_modal.find('#node-type-question-text').val(),
						'options': data_options
					}
				}
				$(this).data('data_submit', data);
			});
		});

		//type=250
		$("#node-type-quick-reply-template").on('change', function()  {

			var selectValue = $(this).val();
			var data = {'templateID': selectValue};
			$.ajax({
				method: 'POST',
				data: data,
				url: '{{ route("foso.campaigns.offer.customerjourney.quickreply.api", ["offer_code"=>$offerCode]) }}',
				success: function(response){

					$("#node-type-quick-reply-text").val(response.text);
					obj = response.reply;
					for (let i = 0; i < 3; i++)  {
						var j = i+1;
						$("#node-type-quick-reply-option-"+j).val("");
						$("#node-type-quick-reply-option-"+j).attr("code","");
					}

					for (let i = 0; i < obj.length; i++)  {
						var j = i+1;
						$("#node-type-quick-reply-option-"+j).val(obj[i].title);
						$("#node-type-quick-reply-option-"+j).attr("code",obj[i].id);

					}
				}
			});
		});

// 		$('.modal.node-type-quick-reply').each(function()  {
// 			var this_modal = $(this);
// 
// 			$(this).on('shown.bs.modal', function()  {
// 				$(this).trigger('reset_fields');
// 
// 				var node = $(this).data('node');
// 				if (node !== null)  {
// 					$(this).find('#node-type-quick-reply-node-name').val(node['node_name']);
// 					if (node['node_settings'])  {
// 
// 						$(this).find('#node-type-quick-reply-sending-time').val(node['node_settings']['schedule'] ? node['node_settings']['schedule'] : '');
// 						// $(this).find('#node-type-quick-reply-text').val(node['node_settings']['message']);
// 
// 						var options = node['node_settings']['options'];
// 						var optionsName = node['node_settings']['optionsName'];
// 						var jq_options = $(this).find('.foso-repeat.node-type-quick-reply-options');
// 						jq_options.data('count', Object.keys(options).length);
// 						jq_options.trigger('change');
// 
// 						var o = 0;
// 						for (var option in options)  {
// 							var node_name_next = options[option];
// 							var option_name = optionsName[option]
// 							$(this).find('#node-type-quick-reply-option-' + (parseInt(o) + 1)).attr("code", option);
// 							$(this).find('#node-type-quick-reply-option-' + (parseInt(o) + 1)).val(option_name);
// 							$(this).find('#node-type-questquick-replyion-node-name-next-' + (parseInt(o) + 1)).val(node_name_next);
// 							o++;
// 						}
// 					}
// 
// 					$(this).find('.button-delete-node').removeClass('d-none');
// 				}
// 			});
// 
// 			$(this).on('reset_fields', function()  {
// 				$(this).find('#node-type-quick-reply-node-name').val('');
// 				$(this).find('#node-type-quick-reply-sending-time').val('');
// 				$(this).find('#node-type-quick-reply-text').val('');
// 				$(this).find('#node-type-quick-reply-node-name-next').val('');
// 				$(this).find('.button-delete-node').addClass('d-none');
// 
// 				$(this).find('.foso-repeat.node-type-quick-reply-options').trigger('reset');
// 			});
// 
// 			$(this).find('.button-save-node').on('form_submit', function()  {
// 				if (this_modal.find('.node-type-quick-reply-form').valid() === false)  {
// 
// 					console.log("### Message form not found...");
// 					return;
// 				}
// 
// 				var data_options = {};
// 				var data_name_options = {};
// 				var i = 1;
// 				var jq_option = null;
// 				do  {
// 					jq_option = this_modal.find('#node-type-quick-reply-option-' + i);
// 					data_options[jq_option.attr("code")] = this_modal.find('#node-type-quick-reply-node-name-next-option-' + i).val();
// 					data_name_options[jq_option.attr("code")] = jq_option.val();
// 					i++;
// 				}  while (!(jq_option.length <= 0));
// 
// 				var data = {
// 					'node_type': 250,
// 					'node_name': this_modal.find('#node-type-quick-reply-node-name').val(),
// 					'node_settings': {
// 						'templateID': this_modal.find('#node-type-quick-reply-template').val(),
// 						'schedule': this_modal.find('#node-type-quick-reply-sending-time').val(),
// 						// 'message': this_modal.find('#node-type-quick-reply-text').val(), // TODO:parameter
// 						'options': data_options,
// 						'optionsName': data_name_options
// 					}
// 				}
// 				$(this).data('data_submit', data);
// 			});
// 		});
		$('.modal.node-type-quick-reply').each(function()  {
			var this_modal = $(this);

			$(this).on('shown.bs.modal', function()  {
				$(this).trigger('reset_fields');

				var node = $(this).data('node');
				if (node !== null)  {

					$(this).find('#node-type-quick-reply-node-name').val(node['node_name']);
					if (node['node_settings'])  {

						$(this).find('#node-type-quick-reply-sending-time').val(node['node_settings']['schedule'] ? node['node_settings']['schedule'] : '');
						$(this).find('#node-type-quick-reply-image-path').val(node['node_settings']['media']);
						$(this).find('#node-type-quick-reply-text').val(node['node_settings']['message']);
						$(this).find('.dropify').data('image', node['node_settings']['media']);

						var optionsDictionary = node['node_settings']['options'];
						var optionsNameDictionary = node['node_settings']['optionsName'];
// 						var jq_options = $(this).find('.foso-repeat.node-type-quick-reply-options');
// 						jq_options.data('count', Object.keys(optionsDictionary).length);
// 						jq_options.trigger('change');

						var o = 1;
						var keyArray = Object.keys(optionsDictionary);
						for (var index in keyArray)  {

							var key = keyArray[index];
							var node_name_next = optionsDictionary[key];
							var optionText = optionsNameDictionary[key];

							$(this).find('#node-type-quick-reply-option-'+o).val(optionText);
							$(this).find('#node-type-quick-reply-node-name-next-option-'+o).val(node_name_next);

							o++;
						}
					}
					$(this).find('.dropify').trigger('show_image');
					$(this).find('.button-delete-node').removeClass('d-none');
				}
			});

			$(this).on('reset_fields', function()  {
				$(this).find('#node-type-quick-reply-node-name').val('');
				$(this).find('#node-type-quick-reply-sending-time').val('');
				$(this).find('#node-type-quick-reply-image-path').val('');
				$(this).find('#node-type-quick-reply-text').val('');
				$(this).find('#node-type-quick-reply-node-name-next').val('');
				$(this).find('.button-delete-node').addClass('d-none');

				$(this).find('.foso-repeat.node-type-quick-reply-options').trigger('reset');

				$(this).find('.dropify').trigger('reset');
			});

			$(this).find('.button-save-node').on('form_submit', function()  {
				if (this_modal.find('.node-type-quick-reply-form').valid() === false)  {

					console.log("### Message form not found...");
					return;
				}

				var data_options = {};
				var data_name_options = {};
				var i = 1;
				var jq_option = null;
				do  {

					jq_option = this_modal.find('#node-type-quick-reply-option-' + i);
					data_options[jq_option.attr("code")] = this_modal.find('#node-type-quick-reply-node-name-next-option-' + i).val();
					data_name_options[jq_option.attr("code")] = jq_option.val();
					i++;

				}  while (!(jq_option.length <= 0));
				var data = {
					'node_type': 250,
					'node_name': this_modal.find('#node-type-quick-reply-node-name').val(),
					'node_settings': {
						'templateID': this_modal.find('#node-type-quick-reply-template').val(),
						'schedule': this_modal.find('#node-type-quick-reply-sending-time').val(),
						'message': this_modal.find('#node-type-quick-reply-text').val(),
						'options': data_options,
						'optionsName': data_name_options

					}
				}
				$(this).data('data_submit', data);
			});
		});

		//type=300
		$('.modal.node-type-issue-coupon').each(function()  {
			var this_modal = $(this);

			$(this).on('shown.bs.modal', function()  {
				$(this).trigger('reset_fields');

				var node = $(this).data('node');
				if (node !== null)  {
					$(this).find('#node-type-issue-coupon-node-name').val(node['node_name']);
					if (node['node_settings'])  {
						$(this).find('#node-type-issue-coupon-redemption-period-id').val(node['node_settings']['selectedRedemptionPeriodID']);
						$(this).find('#node-type-issue-coupon-redemption-store').val(node['node_settings']['selectedRedemptionStore']);
						$(this).find('#node-type-issue-coupon-node-name-next-success').val(node['node_settings']['nextNode']);
						$(this).find('#node-type-issue-coupon-node-name-next-out-of-quota').val(node['node_settings']['outOfQuotaNode']);
						$(this).find('#node-type-issue-coupon-node-name-next-expiry').val(node['node_settings']['expiryNode']);
						$(this).find('#node-type-issue-coupon-node-name-next-coupon-exists').val(node['node_settings']['alreadyExistsNode']);
						$(this).find('#node-type-issue-coupon-node-name-next-webhook-error').val(node['node_settings']['webhookErrorNode']);
					}
					$(this).find('.button-delete-node').removeClass('d-none');
				}
			});

			$(this).on('reset_fields', function()  {
				$(this).find('#node-type-issue-coupon-node-name').val('');
				$(this).find('#node-type-issue-coupon-redemption-period-id').val('');
				$(this).find('#node-type-issue-coupon-redemption-store').val('');
				$(this).find('#node-type-issue-coupon-node-name-next-success').val('');
				$(this).find('#node-type-issue-coupon-node-name-next-out-of-quota').val('');
				$(this).find('#node-type-issue-coupon-node-name-next-expiry').val('');
				$(this).find('#node-type-issue-coupon-node-name-next-coupon-exists').val('');
				$(this).find('#node-type-issue-coupon-node-name-next-webhook-error').val('');
				$(this).find('.button-delete-node').addClass('d-none');
			});

			$(this).on('disable_fields', function()  {
				if (foso_data.data('coupon_type') !== 'randomly-generated')  {
					$(this).find('#node-type-issue-coupon-redemption-period-id').val(0);
					$(this).find('#node-type-issue-coupon-redemption-period-id').prop('disabled', true);
				}
			});

			$(this).find('.button-save-node').on('form_submit', function()  {
				if (this_modal.find('.node-type-issue-coupon-form').valid() === false)  {

					console.log("### Coupon form not found...");
					return;
				}

				var data = {
					'node_type': 300,
					'node_name': this_modal.find('#node-type-issue-coupon-node-name').val(),
					'node_settings': {
						'selectedRedemptionPeriodID': this_modal.find('#node-type-issue-coupon-redemption-period-id').val(),
						'selectedRedemptionStore': this_modal.find('#node-type-issue-coupon-redemption-store').val(),
						'nextNode': this_modal.find('#node-type-issue-coupon-node-name-next-success').val(),
						'outOfQuotaNode': this_modal.find('#node-type-issue-coupon-node-name-next-out-of-quota').val(),
						'expiryNode': this_modal.find('#node-type-issue-coupon-node-name-next-expiry').val(),
						'alreadyExistsNode': this_modal.find('#node-type-issue-coupon-node-name-next-coupon-exists').val(),
						'webhookErrorNode': this_modal.find('#node-type-issue-coupon-node-name-next-webhook-error').val(),
					}
				}

				$(this).data('data_submit', data);
			});
		});

		//type=310
		$('.modal.node-type-cancel-reminder').each(function()  {
			var this_modal = $(this);

			$(this).on('shown.bs.modal', function()  {
				$(this).trigger('reset_fields');

				var node = $(this).data('node');
				if (node !== null)  {
					$(this).find('#node-type-cancel-reminder-node-name').val(node['node_name']);
					if (node['node_settings'])  {
						$(this).find('#node-type-cancel-reminder-node-name-cancel').val(node['node_settings']['nodeName']);
						$(this).find('#node-type-cancel-reminder-node-name-next').val(node['node_settings']['nextNode']);
					}
					$(this).find('.button-delete-node').removeClass('d-none');
				}
			});

			$(this).on('reset_fields', function()  {
				$(this).find('#node-type-cancel-reminder-node-name').val('');
				$(this).find('#node-type-cancel-reminder-node-name-cancel').val('');
				$(this).find('#node-type-cancel-reminder-node-name-next').val('');
				$(this).find('.button-delete-node').addClass('d-none');
			});

			$(this).find('.button-save-node').on('form_submit', function()  {
				var data = {};

				if (this_modal.find('.node-type-cancel-reminder-form').valid() === false)  {
					return;
				}

				data = {
					'node_type': 310,
					'node_name': this_modal.find('#node-type-cancel-reminder-node-name').val(),
					'node_settings': {
						'nodeName': this_modal.find('#node-type-cancel-reminder-node-name-cancel').val(),
						'nextNode': this_modal.find('#node-type-cancel-reminder-node-name-next').val(),
					}
				}

				$(this).data('data_submit', data);
			});
		});

		//type=320
		$('.modal.node-type-cancel-journey').each(function()  {
			var this_modal = $(this);

			$(this).on('shown.bs.modal', function()  {
				$(this).trigger('reset_fields');

				var node = $(this).data('node');
				if (node !== null)  {
					$(this).find('#node-type-cancel-journey-node-name').val(node['node_name']);
					if (node['node_settings'])  {
						$(this).find('#node-type-cancel-journey-node-name-cancel').val(node['node_settings']['nodeName']);
						$(this).find('#node-type-cancel-journey-node-name-next').val(node['node_settings']['nextNode']);
					}
					$(this).find('.button-delete-node').removeClass('d-none');
				}
			});

			$(this).on('reset_fields', function()  {
				$(this).find('#node-type-cancel-journey-node-name').val('');
				$(this).find('#node-type-cancel-journey-node-name-cancel').val('');
				$(this).find('#node-type-cancel-journey-node-name-next').val('');
				$(this).find('.button-delete-node').addClass('d-none');
			});

			$(this).find('.button-save-node').on('form_submit', function()  {
				if (this_modal.find('.node-type-cancel-journey-form').valid() === false)  {

					console.log("### Journey form not found...");
					return;
				}

				var data = {
					'node_type': 320,
					'node_name': this_modal.find('#node-type-cancel-journey-node-name').val(),
					'node_settings': {
						'nodeName': this_modal.find('#node-type-cancel-journey-node-name-cancel').val(),
						'nextNode': this_modal.find('#node-type-cancel-journey-node-name-next').val(),
					}
				}

				$(this).data('data_submit', data);
			});
		});

		//type=330
		$('.modal.node-type-referral').each(function()  {
			var this_modal = $(this);

			$(this).on('shown.bs.modal', function()  {
				$(this).trigger('reset_fields');

				var node = $(this).data('node');
				if (node !== null)  {
					$(this).find('#node-type-referral-node-name').val(node['node_name']);
					if (node['node_settings'])  {
						$(this).find('#node-type-referral-text').val(node['node_settings']['message']);
						$(this).find('#node-type-referral-in-progress-text').val(node['node_settings']['inProgressMessage']);
						$(this).find('#node-type-referral-requirement').val(node['node_settings']['referralRequirement']);
						$(this).find('#node-type-referral-node-name-next').val(node['node_settings']['nextNode']);
					}
					$(this).find('.button-delete-node').removeClass('d-none');
				}
			});

			$(this).on('reset_fields', function()  {
				$(this).find('#node-type-referral-node-name').val('');
				$(this).find('#node-type-referral-requirement').val('');
				$(this).find('#node-type-referral-sending-time').val('');
				$(this).find('#node-type-referral-image-path').val('');
				$(this).find('#node-type-referral-text').val('');
				$(this).find('#node-type-referral-in-progress-text').val('');
				$(this).find('#node-type-referral-node-name-next').val('');
				$(this).find('.button-delete-node').addClass('d-none');

				$(this).find('.dropify').trigger('reset');
			});

			$(this).find('.button-save-node').on('form_submit', function()  {
				if (this_modal.find('.node-type-referral-form').valid() === false)  {

					console.log("### Referral form not found...");
					return;
				}

				var data = {
					'node_type': 330,
					'node_name': this_modal.find('#node-type-referral-node-name').val(),
					'node_settings': {
						'message': this_modal.find('#node-type-referral-text').val(),
						'inProgressMessage': this_modal.find('#node-type-referral-in-progress-text').val(),
						'referralRequirement': this_modal.find('#node-type-referral-requirement').val(),
						'nextNode': this_modal.find('#node-type-referral-node-name-next').val(),
					}
				}
				$(this).data('data_submit', data);
			});

			$(this).find('#node-type-message-image-path').on('change', function()  {
				var input_value = $(this).val();
				var image = '';

				if (input_value.search(/[\{\}]/g) > -1)  {
					var default_image_set = this_modal.data('default-parameterized-link-image');
					for (var keyword in default_image_set)  {
						var default_image = default_image_set[keyword];
						if (input_value.search(new RegExp(keyword, 'g')) > -1)  {
							image = default_image;
						}
					}
					if (image === '')  {
						image = default_image_set['___default'];
					}
				} else {
					image = input_value;
				}

				this_modal.find('.dropify').trigger('reset');
				this_modal.find('.dropify').data('image', image);
				this_modal.find('.dropify').trigger('show_image');
			});
		});

		//type=335
		$('.modal.node-type-referral-completion').each(function()  {
			var this_modal = $(this);

			$(this).on('shown.bs.modal', function()  {
				$(this).trigger('reset_fields');

				var node = $(this).data('node');
				if (node !== null)  {
					$(this).find('#node-type-referral-completion-node-name').val(node['node_name']);
					if (node['node_settings'])  {
						$(this).find('#node-type-referral-completion-node-name-next').val(node['node_settings']['nextNode']);
					}
					$(this).find('.button-delete-node').removeClass('d-none');
				}
			});

			$(this).on('reset_fields', function()  {
				$(this).find('#node-type-referral-completion-node-name').val('');
				$(this).find('#node-type-referral-completion-node-name-next').val('');
				$(this).find('.button-delete-node').addClass('d-none');
			});

			$(this).find('.button-save-node').on('form_submit', function()  {
				if (this_modal.find('.node-type-referral-completion-form').valid() === false)  {

					console.log("### Message form not found...");
					return;
				}

				var data = {
					'node_type': 335,
					'node_name': this_modal.find('#node-type-referral-completion-node-name').val(),
					'node_settings': {
						'nextNode': this_modal.find('#node-type-referral-completion-node-name-next').val(),
					}
				}
				$(this).data('data_submit', data);
			});
		});

		//type=340
		$('.modal.node-type-get-form-data').each(function()  {
			var this_modal = $(this);

			$(this).on('shown.bs.modal', function()  {
				$(this).trigger('reset_fields');

				var node = $(this).data('node');
				if (node !== null)  {
					$(this).find('#node-type-get-form-data-node-name').val(node['node_name']);
					if (node['node_settings'])  {

						$(this).find('#node-type-get-form-data-node-name-next').val(node['node_settings']['nextNode']);
						$(this).find('#node-type-get-form-data-node-name-fail').val(node['node_settings']['failNode']);
					}
					$(this).find('.button-delete-node').removeClass('d-none');
				}
			});

			$(this).find('.button-save-node').on('form_submit', function()  {
				if (this_modal.find('.node-type-get-form-data-form').valid() === false)  {

					console.log("### Get form data form not found...");
					return;
				}

				var data = {
					'node_type': 340,
					'node_name': this_modal.find('#node-type-get-form-data-node-name').val(),
					'node_settings': {
						'nextNode': this_modal.find('#node-type-get-form-data-node-name-next').val(),
						'failNode': this_modal.find('#node-type-get-form-data-node-name-fail').val(),
					}
				}
				$(this).data('data_submit', data);
			});
		});

		//type=400
		$('.modal.node-type-date-comparison').each(function()  {
			var this_modal = $(this);

			$(this).on('shown.bs.modal', function()  {
				$(this).trigger('reset_fields');

				var node = $(this).data('node');
				if (node !== null)  {
					$(this).find('#node-type-date-comparison-node-name').val(node['node_name']);
					if (node['node_settings'])  {
						$(this).find('#node-type-date-comparison-condition').val(node['node_settings']['condition']);
						$(this).find('#node-type-date-comparison-datetime').val(node['node_settings']['datetime']);
						$(this).find('#node-type-date-comparison-node-name-next-then').val(node['node_settings']['nextNode']);
						$(this).find('#node-type-date-comparison-node-name-next-else').val(node['node_settings']['nextNodeElse']);
					}
					$(this).find('.button-delete-node').removeClass('d-none');
				}
			});

			$(this).on('reset_fields', function()  {
				$(this).find('#node-type-date-comparison-node-name').val('');
				$(this).find('#node-type-date-comparison-condition').val('');
				$(this).find('#node-type-date-comparison-datetime').val('');
				$(this).find('#node-type-date-comparison-node-name-next-then').val('');
				$(this).find('#node-type-date-comparison-node-name-next-else').val('');
				$(this).find('.button-delete-node').addClass('d-none');
			});

			$(this).find('.button-save-node').on('form_submit', function()  {
				if (this_modal.find('.node-type-date-comparison-form').valid() === false)  {

					console.log("### Comparison form not found...");
					return;
				}

				var data = {
					'node_type': 400,
					'node_name': this_modal.find('#node-type-date-comparison-node-name').val(),
					'node_settings': {
						'condition': this_modal.find('#node-type-date-comparison-condition').val(),
						'datetime': this_modal.find('#node-type-date-comparison-datetime').val(),
						'nextNode': this_modal.find('#node-type-date-comparison-node-name-next-then').val(),
						'nextNodeElse': this_modal.find('#node-type-date-comparison-node-name-next-else').val(),
					}
				}

				$(this).data('data_submit', data);
			});
		});

		//type=410
		$('.modal.node-type-coupon-expiry-check').each(function()  {
			var this_modal = $(this);

			$(this).on('shown.bs.modal', function()  {
				$(this).trigger('reset_fields');

				var node = $(this).data('node');
				if (node !== null)  {
					$(this).find('#node-type-coupon-expiry-check-node-name').val(node['node_name']);
					if (node['node_settings'])  {
						$(this).find('#node-type-coupon-expiry-check-condition').val(node['node_settings']['condition']);
						$(this).find('#node-type-coupon-expiry-check-datetime').val(node['node_settings']['datetime']);
						$(this).find('#node-type-coupon-expiry-check-node-name-next-valid').val(node['node_settings']['nextNode']);
						$(this).find('#node-type-coupon-expiry-check-node-name-next-expiry').val(node['node_settings']['nextNodeExpiry']);
					}
					$(this).find('.button-delete-node').removeClass('d-none');
				}
			});

			$(this).on('reset_fields', function()  {
				$(this).find('#node-type-coupon-expiry-check-node-name').val('');
				$(this).find('#node-type-coupon-expiry-check-condition').val('');
				$(this).find('#node-type-coupon-expiry-check-datetime').val('');
				$(this).find('#node-type-coupon-expiry-check-node-name-next-valid').val('');
				$(this).find('#node-type-coupon-expiry-check-node-name-next-expiry').val('');
				$(this).find('.button-delete-node').addClass('d-none');
			});

			$(this).find('.button-save-node').on('form_submit', function()  {
				if (this_modal.find('.node-type-coupon-expiry-check-form').valid() === false)  {

					console.log("### Coupon expiry check form not found...");
					return;
				}

				var data = {
					'node_type': 410,
					'node_name': this_modal.find('#node-type-coupon-expiry-check-node-name').val(),
					'node_settings': {
						'condition': this_modal.find('#node-type-coupon-expiry-check-condition').val(),
						'datetime': this_modal.find('#node-type-coupon-expiry-check-datetime').val(),
						'nextNode': this_modal.find('#node-type-coupon-expiry-check-node-name-next-valid').val(),
						'nextNodeExpiry': this_modal.find('#node-type-coupon-expiry-check-node-name-next-expiry').val(),
					}
				}

				$(this).data('data_submit', data);
			});
		});

		//type=500
		$('.modal.node-type-payment').each(function()  {
			var this_modal = $(this);

			$(this).on('shown.bs.modal', function()  {
				$(this).trigger('reset_fields');

				var node = $(this).data('node');
				if (node !== null)  {
					$(this).find('#node-type-payment-node-name').val(node['node_name']);
					if (node['node_settings'])  {
						$(this).find('#node-type-payment-gateway').val(node['node_settings']['gateway']);
						$(this).find('#node-type-payment-message').val(node['node_settings']['message']);
						$(this).find('#node-type-payment-item-name').val(node['node_settings']['itemName']);
						$(this).find('#node-type-payment-item-price').val(node['node_settings']['itemPrice']);
						$(this).find('#node-type-payment-expiry-time').val(node['node_settings']['expiryTime']);
						$(this).find('#node-type-payment-fail-message').val(node['node_settings']['failMessage']);
						$(this).find('#node-type-payment-node-name-next').val(node['node_settings']['nextNode']);
					}
					$(this).find('.button-delete-node').removeClass('d-none');
				}
			});

			$(this).on('reset_fields', function()  {
				$(this).find('#node-type-payment-node-name').val('');
				$(this).find('#node-type-payment-gateway').val('');
				$(this).find('#node-type-payment-message').val('');
				$(this).find('#node-type-payment-item-name').val('');
				$(this).find('#node-type-payment-expiry-time').val('');
				$(this).find('#node-type-payment-fail-message').val('');
				$(this).find('#node-type-payment-node-name-next').val('');
				$(this).find('.button-delete-node').addClass('d-none');
			});

			$(this).find('.button-save-node').on('form_submit', function()  {
				if (this_modal.find('.node-type-payment-form').valid() === false)  {

					console.log("### Comparison form not found...");
					return;
				}

				var data = {
					'node_type': 500,
					'node_name': this_modal.find('#node-type-payment-node-name').val(),
					'node_settings': {
						'gateway': this_modal.find('#node-type-payment-gateway').val(),
						'message': this_modal.find('#node-type-payment-message').val(),
						'itemName': this_modal.find('#node-type-payment-item-name').val(),
						'itemPrice': this_modal.find('#node-type-payment-item-price').val(),
						'expiryTime': this_modal.find('#node-type-payment-expiry-time').val(),
						'failMessage': this_modal.find('#node-type-payment-fail-message').val(),
						'nextNode': this_modal.find('#node-type-payment-node-name-next').val(),
					}
				}

				$(this).data('data_submit', data);
			});
		});

		//type=510
// 		$('.modal.node-type-payment-cancel').each(function()  {
// 		});

		//type=600
		$('.modal.node-type-nft-redeem').each(function()  {
			var this_modal = $(this);

			$(this).on('shown.bs.modal', function()  {
				$(this).trigger('reset_fields');

				var node = $(this).data('node');
				if (node !== null)  {
					$(this).find('#node-type-nft-redeem-node-name').val(node['node_name']);
					if (node['node_settings'])  {

						$(this).find('#node-type-nft-redeem-vendor').val(node['node_settings']['vendor']);
						$(this).find('#node-type-nft-redeem-node-name-next-success').val(node['node_settings']['nextNode']);
						$(this).find('#node-type-nft-redeem-node-name-next-failed').val(node['node_settings']['failNode']);

// 						var options = node['node_settings']['options'];
// 						var jq_options = $(this).find('.foso-repeat.node-type-nft-redeem-options');
// 						jq_options.data('count', Object.keys(options).length);
// 						jq_options.trigger('change');
					}
					$(this).find('.button-delete-node').removeClass('d-none');
				}
			});

			$(this).on('reset_fields', function()  {
				$(this).find('#node-type-nft-redeem-node-name').val('');
				$(this).find('#node-type-nft-redeem-node-name-next-success').val('');
				$(this).find('#node-type-nft-redeem-node-name-next-failed').val('');
				$(this).find('.button-delete-node').addClass('d-none');
			});

			$(this).find('.button-save-node').on('form_submit', function()  {
				if (this_modal.find('.node-type-nft-redeem-form').valid() === false)  {

					console.log("### Message form not found...");
					return;
				}

				var data_options = {};
				var i = 1;

				var data = {
					'node_type': 600,
					'node_name': this_modal.find('#node-type-nft-redeem-node-name').val(),
					'node_settings': {
						'vendor': this_modal.find('#node-type-nft-redeem-vendor').val(),
						'nextNode': this_modal.find('#node-type-nft-redeem-node-name-next-success').val(),
						'failNode': this_modal.find('#node-type-nft-redeem-node-name-next-failed').val()
					}
				}
				$(this).data('data_submit', data);
			});
		});

		//type=700
		$('.modal.node-type-issue-point').each(function()  {
			var this_modal = $(this);

			$(this).on('shown.bs.modal', function()  {
				$(this).trigger('reset_fields');

				var node = $(this).data('node');
				if (node !== null)  {
					$(this).find('#node-type-issue-point-node-name').val(node['node_name']);
					if (node['node_settings'])  {
						$(this).find('#node-type-issue-point-point').val(node['node_settings']['point']);
						$(this).find('#node-type-issue-point-description').val(node['node_settings']['description']['zh-HK']);
						$(this).find('#node-type-issue-point-node-name-next').val(node['node_settings']['nextNode']);
					}
					$(this).find('.button-delete-node').removeClass('d-none');
				}
			});

			$(this).on('reset_fields', function()  {
				$(this).find('#node-type-issue-point-node-name').val('');
				$(this).find('#node-type-issue-point-point').val('');
				$(this).find('#node-type-issue-point-description').val('');
				$(this).find('#node-type-issue-point-node-name-next').val('');
				$(this).find('.button-delete-node').addClass('d-none');
			});

			$(this).find('.button-save-node').on('form_submit', function()  {
				if (this_modal.find('.node-type-issue-point-form').valid() === false)  {
					console.log("### Message form not found...");
					return;
				}

				var data_options = {};
				var i = 1;
				var data = {
					'node_type': 700,
					'node_name': this_modal.find('#node-type-issue-point-node-name').val(),
					'node_settings': {
						'point': this_modal.find('#node-type-issue-point-point').val(),
						'description': {[this_modal.find('#node-type-issue-point-description-lang').val()]: this_modal.find('#node-type-issue-point-description').val()},
						'nextNode': this_modal.find('#node-type-issue-point-node-name-next').val(),
					}
				}
				$(this).data('data_submit', data);
			});
		});

		//type=all, this has to be placed after other $('.modal.xxxxxxx')
		$('.modal').each(function()  {
			var this_modal = $(this);

			$(this).on('shown.bs.modal', function()  {
				var node = $(this).data('node');
				$(this).find('.node_data').text("");
				if (node && 'node_data' in node)  {

					var text = node['node_data'];
					if (text == null)  {text = "";}
					$(this).find('.node_data').text(text);
				}

				window.cjModalEdited = false;

				var mode = foso_data.data('mode');
				switch (mode)  {
					case 'edit':
						$(this).find('.form-control').prop('disabled', false);
						$(this).find('.dropify').trigger('enable');
						$(this).find('.edit').not('.button-delete-node').removeClass('d-none');
						$(this).find('.readonly').addClass('d-none');
						break;
					case 'readonly':
						//readonly when displaying specific mobile state
						$(this).find('.form-control').prop('disabled', true);
						$(this).find('.dropify').trigger('disbale');
						$(this).find('.edit').addClass('d-none');
						$(this).find('.readonly').removeClass('d-none');
						break;
				}

				$(this).trigger('disable_fields');
			});

			$(this).find('.foso-repeat').each(function()  {
				$(this).data('html', $(this).html());
				$(this).text('');
				$(this).on('change', function()  {
					var count_created = $(this).data('count_created');
					if (!count_created)  {
						count_created = 0;
					}
					var count = $(this).data('count');
					for (var i = count_created + 1; i <= count; i++)  {
						var html = $(this).data('html');
						html = html.replace(/{count}/g, i);
						$(this).append($(html));

						$(this).data('count_created', i);
					}
				});
				$(this).on('reset', function()  {
					$(this).text('');
					$(this).data('count_created', 0);
					$(this).data('count', 1);
					$(this).trigger('change');
				});
				$(this).trigger('change');
			});

			$(this).find('.foso-repeat-add').on('click touch', function()  {
				var class_name = $(this).data('class');
				var target = $('.foso-repeat.' + class_name);
				var count = target.data('count');
				target.data('count', count + 1);
				target.trigger('change');
			});

			$(this).find('.button-save-node').on('click touch', function()  {
				//custom validation
				if (this_modal.find('.error').not('.error.d-none').length > 0)  {
					return;
				}

				$(this).data('data_submit', null);
				$(this).trigger('form_submit');
				var data = $(this).data('data_submit');

				if (data !== null)  {
					var node = this_modal.data('node');
					if (node)  {
						data['node_id'] = node['id'];

						//delete node
						var is_deleted = this_modal.data('is_deleted');
						if (is_deleted === true)  {
							data['is_deleted'] = true;
							this_modal.data('is_deleted', false);
						}

						//update node name
						var node_name_old = node['node_name'];
						if (data['node_name'] !== node_name_old)  {
							data['node_name_old'] = node_name_old;
						}
					}

					var nodes = foso_data.data('nodes');
					var new_node_names = [];
					var jq_next_node_name = this_modal.find('.node-type-node-name.next');
					jq_next_node_name.each(function()  {
						var next_node_name = $(this).val();
						if (next_node_name !== '' && next_node_name !== data['node_name'])  {
							var is_next_node_name_exists = false;
							for (var i in nodes)  {
								var node_checking = nodes[i];
								if (node_checking['node_name'] === next_node_name)  {
									is_next_node_name_exists = true;
								}
							}
							if (!is_next_node_name_exists)  {
								new_node_names.push(next_node_name);
							}
						}
					});
					if (new_node_names.length > 0)  {
						data['new_node_names'] = new_node_names;
					}
					console.log(data);
					$.ajax({
						type: "POST",
						data: data,
						dataType: "json",
						url: '{{ route("foso.campaigns.offer.customerjourney.json", ["offer_code"=>$offerCode]) }}',
						success: function(data)  {
							net.trigger('get_all_nodes');
						}
					});

					this_modal.modal('hide');
				}
			});

			$(this).find('.button-delete-node').on('click touch', function()  {
				var is_deleted = confirm('Confirm to delete?');
				if (is_deleted)  {
					this_modal.data('is_deleted', true);
					this_modal.find('.button-save-node').trigger('click');
				}
			});

			$(this).on('change keyup', '.node-type-node-name', function(event)  {
				var input_string = $(this).val().replace(' ', '-');

				var return_string = '';
				if (event.type === 'change')  {
					var matches = input_string.matchAll(/[a-zA-Z0-9_\-]+/g);
					for (const match of matches)  {
						return_string += match[0];
					}
				} else {
					return_string = input_string;
				}
				$(this).val(return_string);
			});

			$(this).find('.node-type-node-name.unique').on('change keyup', function()  {
				var node = this_modal.data('node');
				var nodes = foso_data.data('nodes');
				var node_name = $(this).val();
				var id = $(this).attr('id');

				this_modal.find('#' + id + '-unique-error').addClass('d-none').css({
					'display': ''
				});
				for (var i in nodes)  {
					var node_checking = nodes[i];
					if (node_name === node_checking['node_name'] && (node ? node_name !== node['node_name'] : true))  {
						this_modal.find('#' + id + '-unique-error').removeClass('d-none').css({
							'display': ''
						});
					}
				}
			});
		});

		$('#button-search').on('click touch', function()  {
			var mobile_to_search = $('#mobile-to-search').val();
			net.trigger('get_all_nodes', [mobile_to_search]);
		});

		$('#mobile-to-search').on('keydown', function(event)  {
			if (event.which === 13)  {
				$('#button-search').trigger('click');
			}
		});

		ui.each(function()  {
			$(this).on('draw_chart', function()  {

				var endCount = 0;
				function endIfNextIsEmpty(next)  {
					if (next === '' || next === null || next === undefined)  {
						endCount++;
						return '___end_of_journey_'+endCount;
					}
					return next;
				}

				function getClickCommand(node_name)  {
					return ':>javascript:jQuery(\'.cj-node-list .cj-node.' + node_name + '\').click();';
				}

				function getStateCommand($state)  {
					return '|' + $state;
				}

				var customer_journey = foso_data.data('nodes');
				var declaration = '';
				var flow = '';

				var nodeCount = 0;
				var completedNodeCount = 0;
				for (var i in customer_journey)  {

					nodeCount++;
					var node = customer_journey[i];
					var is_completed = ('is_completed' in node) ? node['is_completed'] : false;
					var command_completed = is_completed ? getStateCommand('completed') : '';

					if (command_completed != '')  {completedNodeCount++;}
					if (flow == '')  {flow = '___start_of_journey->'+node['node_name']+'\n';}

					switch (node['node_type'])  {

						//----------------------------------------------------------------------------------------
						case 100:
							var next = node['node_settings']['nextNode'];
							declaration += node['node_name'] + '=>operation: ' + node['node_name'] + command_completed + getClickCommand(node['node_name']) + '\n';
							flow += node['node_name'] + '->' + endIfNextIsEmpty(next) + '\n';
							break;

						//----------------------------------------------------------------------------------------
						case 200:  {
							var declaration_temp = '';
							var flow_temp = '';

							//group options
							var node_name_next_grouped_by_answer = {};
							var options_grouped = [];

							var options = node['node_settings']['options'];
							const optionKeysArray = Object.keys(options);
							optionKeysArray.sort(function(x, y)  {
								var nodeX = options[x];
								var nodeY = options[y];
								if (nodeX == null || nodeX == "")  {return 1;}
								if (nodeY == null || nodeY == "")  {return -1;}

								if (nodeX.length > nodeY.length)  {return -1;}
								if (nodeX.length < nodeY.length)  {return 1;}

								return 0;
							});

							for (var x in optionKeysArray)  {
								var option = optionKeysArray[x];

								var node_name_next = options[option];
								var option_next = node_name_next === null ? '' : node_name_next;

								if (!(option_next in node_name_next_grouped_by_answer))  {
									node_name_next_grouped_by_answer[option_next] = [];
								}
								node_name_next_grouped_by_answer[option_next].push(option);
							}

							for (var option_next in node_name_next_grouped_by_answer)  {
								var option_name_array = node_name_next_grouped_by_answer[option_next];
								options_grouped[option_name_array.join(' or ')] = option_next;
							}

							//normal flow
							declaration_temp += node['node_name'] + '=>subroutine: ' + node['node_name'] + command_completed + getClickCommand(node['node_name']) + '\n';

							var option_name_previous = '';
							for (var option in options_grouped)  {
								var node_name_next = options_grouped[option];
								var option_name = node['node_name'] + '_option_' + option.split(' ').join('_');
								var option_next = node_name_next;

								var command_completed_for_this_answer = '';
								if (is_completed)  {
									var node_data = node['node_data'];
									var obj_node_data = JSON.parse(node_data);
									var answer = obj_node_data['answer'];
									if (option.search(answer) > -1)  {
										command_completed_for_this_answer = command_completed;
									}
								}

								declaration_temp += option_name + '=>condition: Answer '+option+" ?"+command_completed_for_this_answer + getClickCommand(node['node_name']) + '\n';
								if (option_name_previous === '')  {
									flow_temp += node['node_name'] + '->' + endIfNextIsEmpty(option_name) + '\n';
								} else {
									flow_temp += option_name_previous + '(no)->' + endIfNextIsEmpty(option_name) + '\n';
								}
								flow_temp += option_name + '(yes)->' + endIfNextIsEmpty(option_next) + '\n';
								option_name_previous = option_name;
							}
							flow_temp += option_name_previous + '(no)->' + endIfNextIsEmpty('') + '\n';

							//  If have only one grouped option, overwrite flow_temp and declaration_temp
							if (options_grouped.length === 1)  {
								declaration_temp = node['node_name'] + '=>subroutine: ' + node['node_name'] + command_completed + getClickCommand(node['node_name']) + '\n';
								flow_temp = node['node_name'] + '->' + endIfNextIsEmpty(options_grouped[0]['node_name_next']) + '\n';
							}

							declaration += declaration_temp;
							flow += flow_temp;
						}  break;
					
						//----------------------------------------------------------------------------------------
						case 250:  {
							var declaration_temp = '';
							var flow_temp = '';

							//group options
							var node_name_next_grouped_by_answer = {};
							var options_grouped = [];

							var optionsDictionary = node['node_settings']['options'];
							const optionKeysArray = Object.keys(optionsDictionary);
							optionKeysArray.sort(function(x, y)  {

								var nodeX = optionsDictionary[x];
								var nodeY = optionsDictionary[y];
								if (nodeX == null || nodeX == "")  {return 1;}
								if (nodeY == null || nodeY == "")  {return -1;}

								if (nodeX.length > nodeY.length)  {return -1;}
								if (nodeX.length < nodeY.length)  {return 1;}

								return 0;
							});

							var optionsNameDictionary = node['node_settings']['optionsName'];
							for (var x in optionKeysArray)  {

								var optionKey = optionKeysArray[x];
								var optionText = optionsNameDictionary[optionKey];

								var node_name_next = optionsDictionary[optionKey];
								var option_next = node_name_next === null ? '' : node_name_next;

								if (!(option_next in node_name_next_grouped_by_answer))  {
									node_name_next_grouped_by_answer[option_next] = [];
								}
								node_name_next_grouped_by_answer[option_next].push(optionText);
							}

							for (var option_next in node_name_next_grouped_by_answer)  {
								var option_name_array = node_name_next_grouped_by_answer[option_next];
								options_grouped[option_name_array.join(' or ')] = option_next;
							}

							//normal flow
							declaration_temp += node['node_name'] + '=>subroutine: ' + node['node_name'] + command_completed + getClickCommand(node['node_name']) + '\n';

							var option_name_previous = '';
							for (var option in options_grouped)  {
								var node_name_next = options_grouped[option];
								var option_name = node['node_name'] + '_option_' + option.split(' ').join('_');
								var option_next = node_name_next;

								var command_completed_for_this_answer = '';
								if (is_completed)  {
									var node_data = node['node_data'];
									var obj_node_data = JSON.parse(node_data);
									var answer = obj_node_data['answer'];
									if (option.search(answer) > -1)  {
										command_completed_for_this_answer = command_completed;
									}
								}

								declaration_temp += option_name + '=>condition: Answer '+option+" ?"+command_completed_for_this_answer + getClickCommand(node['node_name']) + '\n';
								if (option_name_previous === '')  {
									flow_temp += node['node_name'] + '->' + endIfNextIsEmpty(option_name) + '\n';
								} else {
									flow_temp += option_name_previous + '(no)->' + endIfNextIsEmpty(option_name) + '\n';
								}
								flow_temp += option_name + '(yes)->' + endIfNextIsEmpty(option_next) + '\n';
								option_name_previous = option_name;
							}
							flow_temp += option_name_previous + '(no)->' + endIfNextIsEmpty('') + '\n';

							//  If have only one grouped option, overwrite flow_temp and declaration_temp
							if (options_grouped.length === 1)  {
								declaration_temp = node['node_name'] + '=>subroutine: ' + node['node_name'] + command_completed + getClickCommand(node['node_name']) + '\n';
								flow_temp = node['node_name'] + '->' + endIfNextIsEmpty(options_grouped[0]['node_name_next']) + '\n';
							}

console.log("declaration_temp: "+declaration_temp);
console.log("flow_temp: "+flow_temp);

							declaration += declaration_temp;
							flow += flow_temp;
						}  break;

						//----------------------------------------------------------------------------------------
						case 300:
							var success_next = node['node_settings']['nextNode'];
							var out_of_quota_next = node['node_settings']['outOfQuotaNode'];
							var expiry_next = node['node_settings']['expiryNode'];
							var exists_next = node['node_settings']['alreadyExistsNode'];
							var webhook_error_next = node['node_settings']['webhookErrorNode'];

							declaration += node['node_name'] + '=>condition: ' + node['node_name'] + '\nis valid?' + command_completed + getClickCommand(node['node_name']) + '\n';
							declaration += node['node_name'] + '_exists=>condition: ' + node['node_name'] + '\ncoupon exists?' + command_completed + getClickCommand(node['node_name']) + '\n';
							declaration += node['node_name'] + '_out_of_quota=>condition: ' + node['node_name'] + '\nhave quota?' + command_completed + getClickCommand(node['node_name']) + '\n';
							declaration += node['node_name'] + '_webhook_error=>condition: ' + node['node_name'] + '\nwebhook ok?' + command_completed + getClickCommand(node['node_name']) + '\n';
							declaration += node['node_name'] + '_success=>inputoutput: ' + node['node_name'] + command_completed + getClickCommand(node['node_name']) + '\n';

							flow += node['node_name'] + '(no)->' + endIfNextIsEmpty(expiry_next) + '\n';
							flow += node['node_name'] + '(yes)->' + endIfNextIsEmpty(node['node_name'] + '_exists') + '\n';
							flow += node['node_name'] + '_exists(no)->' + endIfNextIsEmpty(exists_next) + '\n';
							flow += node['node_name'] + '_exists(yes)->' + endIfNextIsEmpty(node['node_name'] + '_out_of_quota') + '\n';
							flow += node['node_name'] + '_out_of_quota(no)->' + endIfNextIsEmpty(out_of_quota_next) + '\n';
							flow += node['node_name'] + '_out_of_quota(yes)->' + endIfNextIsEmpty(node['node_name'] + '_webhook_error') + '\n';
							flow += node['node_name'] + '_webhook_error(no)->' + endIfNextIsEmpty(webhook_error_next) + '\n';
							flow += node['node_name'] + '_webhook_error(yes)->' + endIfNextIsEmpty(node['node_name'] + '_success') + '\n';
							flow += node['node_name'] + '_success->' + endIfNextIsEmpty(success_next) + '\n';
							break;

						//----------------------------------------------------------------------------------------
						case 310:
							var next = node['node_settings']['nextNode'];
							declaration += node['node_name'] + '=>operation: ' + node['node_name'] + command_completed + getClickCommand(node['node_name']) + '\n';
							flow += node['node_name'] + '->' + endIfNextIsEmpty(next) + '\n';
							break;

						//----------------------------------------------------------------------------------------
						case 320:
							var next = node['node_settings']['nextNode'];
							declaration += node['node_name'] + '=>operation: ' + node['node_name'] + command_completed + getClickCommand(node['node_name']) + '\n';
							flow += node['node_name'] + '->' + endIfNextIsEmpty(next) + '\n';
							break;

						//----------------------------------------------------------------------------------------
						case 330:
							var next = node['node_settings']['nextNode'];
							declaration += node['node_name'] + '=>inputoutput: ' + node['node_name'] + command_completed + getClickCommand(node['node_name']) + '\n';
							flow += node['node_name'] + '->' + endIfNextIsEmpty(next) + '\n';
							break;
						
						//----------------------------------------------------------------------------------------
						case 335:
							var next = node['node_settings']['nextNode'];
							declaration += node['node_name'] + '=>inputoutput: ' + node['node_name'] + command_completed + getClickCommand(node['node_name']) + '\n';
							flow += node['node_name'] + '->' + endIfNextIsEmpty(next) + '\n';
							break;

						//----------------------------------------------------------------------------------------
						case 340:
							var next = node['node_settings']['nextNode'];
							var fail = node['node_settings']['failNode'];

							declaration += node['node_name'] + '=>condition: Form loaded?' + command_completed + getClickCommand(node['node_name']) + '\n';
							flow += node['node_name'] + '(yes)->' + endIfNextIsEmpty(next) + '\n';
							flow += node['node_name'] + '(no)->' + endIfNextIsEmpty(fail) + '\n';

// 							declaration += node['node_name']+"_data" + '=>condition: Have form data?' + command_completed + getClickCommand(node['node_name']) + '\n';
// 							flow += node['node_name']+"_data" + '(yes)->' + endIfNextIsEmpty(next) + '\n';
// 							flow += node['node_name']+"_data" + '(no)->' + endIfNextIsEmpty(noFormData) + '\n';
							break;

						//----------------------------------------------------------------------------------------
						case 400:
							var condition_wordings = {
								'e': '=',
								'le': '<=',
								'ge': '>='
							};
							var condition = node['node_settings']['condition'];
							var datetime = node['node_settings']['datetime'];
							var then_next = node['node_settings']['nextNode'];
							var else_next = node['node_settings']['nextNodeElse'];

							declaration += node['node_name'] + '=>condition: Is ' + condition_wordings[condition] + datetime + '?' + command_completed + getClickCommand(node['node_name']) + '\n';
							flow += node['node_name'] + '(yes)->' + endIfNextIsEmpty(then_next) + '\n';
							flow += node['node_name'] + '(no)->' + endIfNextIsEmpty(else_next) + '\n';
							break;

						//----------------------------------------------------------------------------------------
						case 410:
							var valid_next = node['node_settings']['nextNode'];
							var expiry_next = node['node_settings']['nextNodeExpiry'];

							declaration += node['node_name'] + '=>condition: Is coupon valid?' + command_completed + getClickCommand(node['node_name']) + '\n';
							flow += node['node_name'] + '(yes)->' + endIfNextIsEmpty(valid_next) + '\n';
							flow += node['node_name'] + '(no)->' + endIfNextIsEmpty(expiry_next) + '\n';
							break;

						//----------------------------------------------------------------------------------------
						//  Payment node
						case 500:
							var next = node['node_settings']['nextNode'];
							declaration += node['node_name'] + '=>inputoutput: ' + node['node_name'] + command_completed + getClickCommand(node['node_name']) + '\n';
							flow += node['node_name'] + '->' + endIfNextIsEmpty(next) + '\n';
							break;

						//----------------------------------------------------------------------------------------
						case 600:
							var next = node['node_settings']['nextNode'];
							var fail = node['node_settings']['failNode'];

							declaration += node['node_name'] + '=>condition: Redeem NFT\nsuccess?' + command_completed + getClickCommand(node['node_name']) + '\n';
							flow += node['node_name']+'(yes)->'+endIfNextIsEmpty(next)+'\n';
							flow += node['node_name']+'(no)->'+endIfNextIsEmpty(fail)+'\n';
							break;
						case 700:
							var next = node['node_settings']['nextNode'];
							declaration += node['node_name'] + '=>operation: ' + node['node_name'] + command_completed + getClickCommand(node['node_name']) + '\n';
							flow += node['node_name'] + '->' + endIfNextIsEmpty(next) + '\n';
							break;
					}
				}

				var startCompleted = '';
				if (completedNodeCount > 0)  {startCompleted = '|completed';}
				declaration += "___start_of_journey=>start: {{ empty($trigger)?'Trigger Message':nl2br($trigger) }}"+startCompleted+"\n";
				declaration += '___end_of_journey=>end: End\n';

				for (var i=1; i<=endCount; i++)  {
					declaration += '___end_of_journey_'+i+'=>end: End\n';
				}

				if (nodeCount > 0)  {
					$('#flowchart-js-code').val(declaration+"\n"+flow);
					$('#flowchart-js-code').trigger('change');
				}
			});

			//----------------------------------------------------------------------------------------
			$(this).on('update_nodes', function()  {
				var jq_node_list = $('.cj-node-list');
				jq_node_list.html('');

				var nodes = foso_data.data('nodes');
				for (var i in nodes)  {
					var node = nodes[i];
					var node_id = node['id'];
					var node_name = node['node_name'];
					var node_type = node['node_type'];
					var completion_rate = node['completion_rate'];

					var node_type_maps_to_class = {
						0: 'node-type-unknown',
						100: 'node-type-message',
						200: 'node-type-question',
						250: 'node-type-quick-reply',
						300: 'node-type-issue-coupon',
						310: 'node-type-cancel-reminder',
						320: 'node-type-cancel-journey',
						330: 'node-type-referral',
						335: 'node-type-referral-completion',
						340: 'node-type-get-form-data',
						400: 'node-type-date-comparison',
						410: 'node-type-coupon-expiry-check',
						500: 'node-type-payment',
						600: 'node-type-nft-redeem',
						700: 'node-type-issue-point',
					};

					var jq_node = $('.classes .cj-node').clone();
					jq_node.find('.cj-node-name').html(node_name);
					jq_node.find('.cj-node-completion-percentage').html(completion_rate + '%');
					jq_node.data('node-type', node_type_maps_to_class[node_type]);
					jq_node.data('node', node);
					jq_node.addClass(node_name);
					jq_node.on('click', function()  {
						var node_type = $(this).data('node-type');
						var jq_modal = $('.modal.' + node_type);
						jq_modal.data('node', $(this).data('node'));
						jq_modal.modal('show');
					});
					if (node_type === 0)  {
						jq_node.addClass('bg-danger text-white');
					}
					if (completion_rate >= 50)  {
						jq_node.find('.cj-node-completion-percentage').removeClass('badge-primary').addClass('badge-warning');
					}
					if (completion_rate >= 80)  {
						jq_node.find('.cj-node-completion-percentage').removeClass('badge-warning').addClass('badge-danger');
					}
					jq_node_list.append(jq_node);
				}
			});

			$(this).on('initialize_dropify', function()  {
				$('.dropify').dropify();

				$('.dropify').on('reset', function()  {
					$(this).parents('.dropify-wrapper').find('.dropify-clear').click();
				});

				$('.dropify').on('show_image', function()  {
					var dropify = $(this).data('dropify');
					if (/^http/i.test($(this).data('image')))  {
						dropify.setPreview(true, $(this).data('image'));
					}
				});

				$('.dropify').on('change', function(event)  {
					var jq_this = $(this);
					var fileObject = $(event.target).prop('files');

					if (fileObject[0] != undefined)  {

						var file = fileObject[0];

						// var defaultFile    = $(event.target).data('default-file');
						// var defaultFile2   = defaultFile.split('.');
						// var checkExtension = defaultFile2[defaultFile2.length - 1];

						// var fileExtension  = (file.name.split('.'));
						// fileExtension      = fileExtension[fileExtension.length - 1];

						// if (fileExtension != checkExtension)  {
						// 	var dropify = $(event.target).data('dropify');
						// 	dropify.resetPreview();
						// 	dropify.setPreview(dropify.isImage(), defaultFile);
						// 	alert('Please upload .' + checkExtension + ' file');
						// 	return false;
						// }

						if (!isNaN(file.size) && file.size > (10 * 1024 * 1024))  {
							alert('Please upload a file within 10 MB size.');
							return false;
						}

						var formdata = new FormData();
						formdata.append('_token', '{{ csrf_token() }}');
						formdata.append('file', file);

						$.ajax({
							method: 'POST',
							url: "{{ route('foso.campaigns.offer.customerjourney.upload', ['offer_code' => $offer->offer_code]) }}",
							data: formdata,
							dataType: 'json',
							processData: false,
							contentType: false,
							success: function(data, textStatus, jqXHR)  {

								var status = data["status"];
								if (status != "ok")  {

									alert(status);
									return;
								}

								$(event.target).val('');
								var input_id = jq_this.data('for-id');
								$('#' + input_id).val("{{ asset('offers/'.$offer->offer_name.'/journey') }}/" + data['serverFilename']);
							}
						});
					}
				});
			});

			$(this).on('update_search', function(event, mobile_number)  {
				$('#mobile-to-search').val(mobile_number);
			});
		});

		net.each(function()  {
			var jq_this = $(this);

			$(this).on('get_all_nodes', function(event, for_mobile)  {
				var data_request = {};
				foso_data.data('mode', 'edit');

				if (for_mobile)  {
					data_request = {
						'mobile': for_mobile
					};
				}
				$.ajax({
					type: "POST",
					data: data_request,
					dataType: "json",
					url: '{{ route("foso.campaigns.offer.customerjourney.getnodes.json", ["offer_code"=>$offerCode]) }}',
					success: function(data)  {
						foso_data.data('nodes', data['master']);
						if ('customer' in data)  {
							if (data['customer'].length > 0)  {
								foso_data.data('mode', 'readonly');
								jq_this.trigger('preprocess_nodes', [data['customer']]);
							}
						}
						if ('mobile' in data)  {
							ui.trigger('update_search', [data['mobile']]);
						}
						ui.trigger('update_nodes');
						ui.trigger('draw_chart');
					}
				});
			});

			$(this).on('preprocess_nodes', function(event, nodes_customer)  {
				var nodes = foso_data.data('nodes');

				function markNodeAsCompleted(node_customer)  {
					var node_name = node_customer['node_name'];
					var node_data = node_customer['node_data'];
					for (var m in nodes)  {
						var node = nodes[m];
						if (node['node_name'] === node_name)  {
							nodes[m]['is_completed'] = true;
							nodes[m]['node_data'] = node_data;
						}
					}
				}

				for (var c in nodes_customer)  {
					var node_customer = nodes_customer[c];
					if (node_customer['completed_at'] !== null)  {
						markNodeAsCompleted(node_customer);
					}
				}

				foso_data.data('nodes', nodes);
			});
		});

		function initialize()  {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			net.trigger('get_all_nodes');
			ui.trigger('initialize_dropify');
		}
		initialize();

		$(".cj-node-list").sortable({
			handle: '.sortable-handle',
			onEnd: function(e)  {
				var nodeName = $(e.item).find('.cj-node-name').text();
				var oldIndex = e.oldIndex;
				var newIndex = e.newIndex;
				$.ajax({
					method: "POST",
					url: '{{ route("foso.campaigns.offer.customerjourney.ordering.json", ["offer_code"=>$offerCode]) }}',
					data: {nodeName, oldIndex, newIndex},
					success: function(res)  {
						net.trigger('get_all_nodes');
						ui.trigger('initialize_dropify');
					},
					error: function(res)  {
						alert(res);
					}
				});
			}
		});

		$("input, textarea").change(function()  {
			window.cjModalEdited = true;
		});

		$(".closeCJModalBtn").click(function()  {
			if (window.cjModalEdited)  {
				var answer = confirm("You have unsaved changes. Leave?");
				if (answer)  {
					$(".modal").modal('hide');
				}
			} else {
				$(".modal").modal('hide');
			}
		});

	});
</script>

@endsection
