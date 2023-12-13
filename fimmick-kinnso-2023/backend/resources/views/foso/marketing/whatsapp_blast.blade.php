@extends('foso.layouts.default')

@section('page_title', 'Marketing WhatsApp Blasting')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.marketing.list.html") }}'>Marketing</a></li>
<li><i class='fa fa-angle-right'></i> WhatsApp Blasting</li>
@endsection

@section('content')

<div class="card">
	<div class="card-body">
		<form id="form" name="form">
			@csrf

			<div class="row">
				<div class="col-lg-8">
					<fieldset class="form-group">
						<label for="listName">List Name</label>
						<select class="form-control" id="listName" name="listName">
@foreach ($dataArray as $row)
							<option value="{{ $row->list_name }}" data-count="{{ $row->count }}">{{ $row->list_name }} ({{ $row->count }} numbers)</option>
@endforeach
						</select>
						<small class="form-text small-text-color">Confirm popup will be shown if blasting more than 50 numbers</small>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<fieldset class="form-group">
						<label for="message">Message</label>
						<textarea class="form-control" id="message" name="message" rows=10 placeholder="Hello @{{username}}, thank you for joining us!" required></textarea>
						<small class="form-text small-text-color">Support dynamic fields: @{{mobile}}, @{{username}}, @{{parameter_a}}, @{{parameter_b}}, @{{parameter_c}}</small>
						<small class="form-text small-text-color">Please note that content should match with <b><u>template message</u></b> on Twilio platform</small>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="scheduleTime">Schedule Time</label>
						<input class="form-control" type="text" id="scheduleTime" name="scheduleTime" placeholder="+1 day">
						<small class="form-text small-text-color">Optional.  Leave it empty if send immediately</small>
						<small class="form-text small-text-color">Value could be "{{ date('Y-m-d H:i:s') }}"</small>
						<small class="form-text small-text-color">Value could be "+12 hours"</small>
					</fieldset>
				</div>
			</div>

			<button type="button" class="btn btn-danger" id="saveButton" data-toggle="popover-x" data-target="#blastWarning" data-placement="top">Add to queue</button>

			<div id="blastWarning" class="popover popover-danger popover-x in top" style="top: 1096px; left: 391.281px; display: none; z-index: 1050; padding-top: 0px; padding-bottom: 0px;" aria-hidden="true">
				<div class="arrow"></div>
				<h3 class="popover-header popover-title"><span class="close pull-right" data-dismiss="popover-x">×</span>Warning!</h3>
				<div class="popover-body popover-content">
					<p>You are going to send a large amount of WhatsApp message.  In order to continue the action, please input "<span id="password" style="font-weight:bold;">{{ $password }}</span>" in the box below:</p>
					<fieldset class="form-group">
						<input class="form-control" type="text" id="passwordInput" name="passwordInput">
						<p id="passwordInputError" style="color:#ff0000; display:none;">Keyword mismatch</p>
						<button type="button" class="btn btn-danger" id="confirmButton" style="margin-top:10px;">Confirm</button>
					</fieldset>
				</div>
			</div>
			<div class="popover-x-marker" style="display: none;"></div>

		</form>
	</div>
</div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{ asset('js/common.js') }}?v=1"></script>

<script>
	var _password = "{{ $password }}";
	$(document).ready(function()  {

		//  When leave page, show loading
		window.onbeforeunload = function(e)  {showLoading();};

		function blastNow()  {
			var formData = $("#form").serialize();

			var disabled = $("#form").find(":input:disabled").removeAttr("disabled");
			disabled.attr("disabled", "disabled");

			showLoading();
			$.ajax({
				type: "POST",
				data: formData,
				dataType: "json",
				url: '{{ route("foso.marketing.whatsapp.blast.api") }}',
				success: function (result)  {

					alert(result.message);
					hideLoading();
					if (result.status != 0)  {
						return;
					}

					//  Clear message to prevent double send
					$("#message").val("");
					$("#scheduleTime").val("");
				},
				error: function (XMLHttpRequest, textStatus, errorThrown)  {
					hideLoading();
					alert("Oops...\n#"+textStatus+": "+errorThrown);
				}
			});
		}

		$("#confirmButton").click(function()  {
			$("#passwordInputError").hide();

			var password = $("#passwordInput").val();
			if (password != _password)  {
				$("#passwordInputError").show();
				return
			}

			//  Correct
			$("#passwordInputError").hide();
			$("#blastWarning").hide();
			blastNow();
		});

		$("#saveButton").click(function()  {

			var basicRule = {
				rules:  {
					message:  {minlength:10},
				},
				messages: {
					message:  {minlength:"Must consist of at least 10 characters"},
				}
			};

			var form = $("#form");
			form.validate(basicRule);

			result = form.valid();
			if (result == false)  {

				$("#blastWarning").hide();
				return;
			}

			//  Form OK
			var mobileNumberCount = $("#listName").find(":selected").data("count");
			if (mobileNumberCount < 50)  {

				//  Count is less, can do it immediately
				$("#blastWarning").hide();
				blastNow();
				return;
			}

			//  Mobile number count too much, need to double confirm
			$("#passwordInput").val("")
			$("#passwordInputError").hide();
			$("#blastWarning").show();
		});

	});
</script>

@endsection
