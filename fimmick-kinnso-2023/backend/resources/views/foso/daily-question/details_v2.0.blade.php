@extends('foso.layouts.default')

@section('page_title', 'Daily Question #'.$questionID)

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.dailyquestion.list.html") }}'>Daily Question</a></li>
<li><i class='fa fa-angle-right'></i> Details</li>
@endsection

@section('content')

<form id="form" name="form">
	@csrf
	<input type="hidden" id="questionID" name="questionID" value="{{ $questionID }}" />

	<div class="card" style="margin-bottom:20px;">
		<div class="card-body">

			<div class="row">
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="startedAt">Started At</label>
						<input class="form-control" type="text" value="{{ $startedAt }}" id="startedAt" name="startedAt" required>
						<small class="form-text small-text-color">Question available date & time</small>
					</fieldset>
				</div>

				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="endedAt">Ended At</label>
						<input class="form-control" type="text" value="{{ $endedAt }}" id="endedAt" name="endedAt" required>
						<small class="form-text small-text-color">Question ended date & time</small>
					</fieldset>
				</div>

				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="answerExpiryAt">Answer Expiry At</label>
						<input class="form-control" type="text" value="{{ $answerExpiryAt }}" id="answerExpiryAt" name="answerExpiryAt" required>
						<small class="form-text small-text-color">User must reply within above time</small>
						<small class="form-text small-text-color">PHP strtotime() format</small>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="point">Point</label>
						<input class="form-control" type="text" value="{{ $point }}" id="point" name="point" required>
						<small class="form-text small-text-color">Points will be given to user who answered this question</small>
					</fieldset>
				</div>

				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="couponID">Coupon ID</label>
						<input class="form-control" type="text" value="{{ $couponID }}" id="couponID" name="couponID" required>
						<small class="form-text small-text-color">Coupon will be given to user who answered this question</small>
						<small class="form-text small-text-color">0 means nothing</small>
					</fieldset>
				</div>

				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="giftID">Gift ID</label>
						<input class="form-control" type="text" value="{{ $giftID }}" id="giftID" name="giftID" required>
						<small class="form-text small-text-color">Gift will be given to user who answered this question</small>
						<small class="form-text small-text-color">0 means nothing</small>
					</fieldset>
				</div>
			</div>

			<!--  -------------------------------------------------------------------------------  -->
			<hr>
			<div class="row">
				<div class="col-lg-12">
					<fieldset class="form-group">
						<label for="question">Question</label>
						<input class="form-control" type="text" value="{{ $question }}" id="question" name="question" placeholder="Shall we talk?" required>
						<small class="form-text small-text-color">Question text</small>
						<small class="form-text small-text-color">No length limit</small>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-3">
					<fieldset class="form-group">
						<label for="answerA">Answer A</label>
						<input class="form-control" type="text" id="answerA" name="answerA" placeholder="1. Yes" required>
						<small class="form-text small-text-color">Answer text, syntax "KEY. ANSWER TEXT""</small>
						<small class="form-text small-text-color">No length limit</small>
					</fieldset>
				</div>
				<div class="col-lg-5">
					<fieldset class="form-group">
						<label for="answerALabel">Label</label>
						<input class="form-control" type="text" id="answerALabel" name="answerALabel">
						<small class="form-text small-text-color">Separate with comma if more than one</small>
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<label for="answerAPoint" value="0">Point</label>
						<input class="form-control" type="text" id="answerAPoint" name="answerAPoint" value="0">
						<small class="form-text small-text-color">Point given when pick this answer</small>
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<label for="answerANextID" value="0">Next ID</label>
						<input class="form-control" type="text" id="answerANextID" name="answerANextID" value="0">
						<small class="form-text small-text-color">&gt;0 = Question ID</small>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-3">
					<fieldset class="form-group">
						<label for="answerB">Answer B</label>
						<input class="form-control" type="text" id="answerB" name="answerB" placeholder="2. No" required>
						<small class="form-text small-text-color">Answer text, syntax "KEY. ANSWER TEXT"</small>
						<small class="form-text small-text-color">No length limit</small>
					</fieldset>
				</div>
				<div class="col-lg-5">
					<fieldset class="form-group">
						<label for="answerBLabel">Label</label>
						<input class="form-control" type="text" id="answerBLabel" name="answerBLabel">
						<small class="form-text small-text-color">Separate with comma if more than one</small>
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<label for="answerBPoint" value="0">Point</label>
						<input class="form-control" type="text" id="answerBPoint" name="answerBPoint" value="0">
						<small class="form-text small-text-color">Point given when pick this answer</small>
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<label for="answerBNextID" value="0">Next ID</label>
						<input class="form-control" type="text" id="answerBNextID" name="answerBNextID" value="0">
						<small class="form-text small-text-color">&gt;0 = Question ID</small>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-3">
					<fieldset class="form-group">
						<label for="answerC">Answer C</label>
						<input class="form-control" type="text" id="answerC" name="answerC" placeholder="3. I don't know (Optional)" required>
						<small class="form-text small-text-color">Answer text, syntax "KEY. ANSWER TEXT"</small>
						<small class="form-text small-text-color">No length limit</small>
					</fieldset>
				</div>
				<div class="col-lg-5">
					<fieldset class="form-group">
						<label for="answerCLabel">Label</label>
						<input class="form-control" type="text" id="answerCLabel" name="answerCLabel">
						<small class="form-text small-text-color">Separate with comma if more than one</small>
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<label for="answerCPoint" value="0">Point</label>
						<input class="form-control" type="text" id="answerCPoint" name="answerCPoint" value="0">
						<small class="form-text small-text-color">Point given when pick this answer</small>
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<label for="answerCNextID" value="0">Next ID</label>
						<input class="form-control" type="text" id="answerCNextID" name="answerCNextID" value="0">
						<small class="form-text small-text-color">&gt;0 = Question ID</small>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-3">
					<fieldset class="form-group">
						<label for="answerD">Answer D</label>
						<input class="form-control" type="text" id="answerD" name="answerD" placeholder="4. You guess (Optional)">
						<small class="form-text small-text-color">Answer text, syntax "KEY. ANSWER TEXT"</small>
						<small class="form-text small-text-color">No length limit</small>
					</fieldset>
				</div>
				<div class="col-lg-5">
					<fieldset class="form-group">
						<label for="answerDLabel">Label</label>
						<input class="form-control" type="text" id="answerDLabel" name="answerDLabel">
						<small class="form-text small-text-color">Separate with comma if more than one</small>
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<label for="answerDPoint" value="0">Point</label>
						<input class="form-control" type="text" id="answerDPoint" name="answerDPoint" value="0">
						<small class="form-text small-text-color">Point given when pick this answer</small>
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<label for="answerDNextID" value="0">Next ID</label>
						<input class="form-control" type="text" id="answerDNextID" name="answerDNextID" value="0">
						<small class="form-text small-text-color">&gt;0 = Question ID</small>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-3">
					<fieldset class="form-group">
						<label for="answerE">Answer E</label>
						<input class="form-control" type="text" id="answerE" name="answerE" placeholder="5. Won't tell you (Optional)">
						<small class="form-text small-text-color">Answer text, syntax "KEY. ANSWER TEXT"</small>
						<small class="form-text small-text-color">No length limit</small>
					</fieldset>
				</div>
				<div class="col-lg-5">
					<fieldset class="form-group">
						<label for="answerELabel">Label</label>
						<input class="form-control" type="text" id="answerELabel" name="answerELabel">
						<small class="form-text small-text-color">Separate with comma if more than one</small>
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<label for="answerEPoint" value="0">Point</label>
						<input class="form-control" type="text" id="answerEPoint" name="answerEPoint" value="0">
						<small class="form-text small-text-color">Point given when pick this answer</small>
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<label for="answerENextID" value="0">Next ID</label>
						<input class="form-control" type="text" id="answerENextID" name="answerENextID" value="0">
						<small class="form-text small-text-color">&gt;0 = Question ID</small>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-3">
					<fieldset class="form-group">
						<label for="answerF">Answer F</label>
						<input class="form-control" type="text" id="answerF" name="answerF" placeholder="6. Both (Optional)">
						<small class="form-text small-text-color">Answer text, syntax "KEY. ANSWER TEXT"</small>
						<small class="form-text small-text-color">No length limit</small>
					</fieldset>
				</div>
				<div class="col-lg-5">
					<fieldset class="form-group">
						<label for="answerFLabel">Label</label>
						<input class="form-control" type="text" id="answerFLabel" name="answerFLabel">
						<small class="form-text small-text-color">Separate with comma if more than one</small>
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<label for="answerFPoint" value="0">Point</label>
						<input class="form-control" type="text" id="answerFPoint" name="answerFPoint" value="0">
						<small class="form-text small-text-color">Point given when pick this answer</small>
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<label for="answerFNextID" value="0">Next ID</label>
						<input class="form-control" type="text" id="answerFNextID" name="answerFNextID" value="0">
						<small class="form-text small-text-color">&gt;0 = Question ID</small>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-3">
					<fieldset class="form-group">
						<label for="answerE">Answer G</label>
						<input class="form-control" type="text" id="answerG" name="answerG" placeholder="7. Secret (Optional)">
						<small class="form-text small-text-color">Answer text, syntax "KEY. ANSWER TEXT"</small>
						<small class="form-text small-text-color">No length limit</small>
					</fieldset>
				</div>
				<div class="col-lg-5">
					<fieldset class="form-group">
						<label for="answerGLabel">Label</label>
						<input class="form-control" type="text" id="answerGLabel" name="answerGLabel">
						<small class="form-text small-text-color">Separate with comma if more than one</small>
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<label for="answerGPoint" value="0">Point</label>
						<input class="form-control" type="text" id="answerGPoint" name="answerGPoint" value="0">
						<small class="form-text small-text-color">Point given when pick this answer</small>
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<label for="answerGNextID" value="0">Next ID</label>
						<input class="form-control" type="text" id="answerGNextID" name="answerGNextID" value="0">
						<small class="form-text small-text-color">&gt;0 = Question ID</small>
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-3">
					<fieldset class="form-group">
						<label for="answerE">Answer H</label>
						<input class="form-control" type="text" id="answerH" name="answerH" placeholder="8. None (Optional)">
						<small class="form-text small-text-color">Answer text, syntax "KEY. ANSWER TEXT"</small>
						<small class="form-text small-text-color">No length limit</small>
					</fieldset>
				</div>
				<div class="col-lg-5">
					<fieldset class="form-group">
						<label for="answerHLabel">Label</label>
						<input class="form-control" type="text" id="answerHLabel" name="answerHLabel">
						<small class="form-text small-text-color">Separate with comma if more than one</small>
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<label for="answerHPoint" value="0">Point</label>
						<input class="form-control" type="text" id="answerHPoint" name="answerHPoint" value="0">
						<small class="form-text small-text-color">Point given when pick this answer</small>
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<label for="answerHNextID" value="0">Next ID</label>
						<input class="form-control" type="text" id="answerHNextID" name="answerHNextID" value="0">
						<small class="form-text small-text-color">&gt;0 = Question ID</small>
					</fieldset>
				</div>
			</div>

			<!--  Buttons  -->
			<div class="row">
				<div class="col-lg-6">
					<button type="button" class="btn btn-danger" id="saveButton">Save</button>
				</div>
			</div>

		</div>
	</div>
</form>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{ asset('js/ckeditor.js') }}?v=1"></script>
<script type="text/javascript" src="{{ asset('js/common.js') }}?v=1"></script>

<script>
	$(document).ready(function()  {

		//  When leave page, show loading
		window.onbeforeunload = function(e)  {showLoading();};

		$("#saveButton").click(function()  {

			var basicRule = {
// 				rules:  {
// 					startDate:  {date:true},
// 					startTime:  {time:true},
// 					endDate:  {date:true},
// 					endTime:  {time:true},
// 					offerCode:  {minlength:6, nounderscore:true},
// 					offerName:  {minlength:3, alphanumeric:true},
// 					offerTitle:  {minlength:1},
// 					quota:  {number:true},
// 				},
// 				messages: {
// 					offerCode:  {minlength:"Must consist of at least 6 characters"},
// 					offerName:  {minlength:"Must consist of at least 3 characters"},
// 					offerTitle:  {minlength:"Must consist of at least 1 characters"},
// 				}
			};

			var form = $("#form");
			form.validate(basicRule);

			result = form.valid();
			if (result == false)  {return;}

			var disabled = $("#form").find(":input:disabled").removeAttr("disabled");
			var formData = $("#form").serialize();
			disabled.attr("disabled", "disabled");

			showLoading();
			$.ajax({
				type: "POST",
				data: formData,
				dataType: "json",
				url: '{{ route("foso.dailyquestion.details.api", ["question_id"=>$questionID]) }}',
				success: function (result)  {

					alert(result.message);
					if (result.status != 0)  {
						hideLoading();
						return;
					}

					//  TODO: Save done
					location.href = '{{ route("foso.dailyquestion.list.html") }}';
				},
				error: function (XMLHttpRequest, textStatus, errorThrown)  {
					hideLoading();
					alert("Oops...\n#"+textStatus+": "+errorThrown);
				}
			});

		});

		//  Update values to input box
		var index = 65;
		var answerArray = @json($answerArray);
		answerArray.forEach(function(item)  {

			var divName = "answer"+String.fromCharCode(index);
			document.getElementById(divName).value = item;

			index++;
		});

		index = 65;
		var labelArray = @json($labelArray);
		labelArray.forEach(function(item)  {

			var divName = "answer"+String.fromCharCode(index)+"Label";
			document.getElementById(divName).value = item;

			index++;
		});

		index = 65;
		var pointArray = @json($pointArray);
		pointArray.forEach(function(item)  {

			var divName = "answer"+String.fromCharCode(index)+"Point";
			document.getElementById(divName).value = item;

			index++;
		});

		index = 65;
		var nextIDArray = @json($nextIDArray);
		nextIDArray.forEach(function(item)  {

			var divName = "answer"+String.fromCharCode(index)+"NextID";
			document.getElementById(divName).value = item;

			index++;
		});

	});
</script>

@endsection