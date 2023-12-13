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

			<hr>
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
						<input class="form-control" type="text" value="{{ $couponID }}" id="couponID" name="couponID" required disabled>
						<small class="form-text small-text-color">Coupon will be given to user who answered this question</small>
						<small class="form-text small-text-color">0 means nothing</small>
					</fieldset>
				</div>

				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="giftID">Gift ID</label>
						<input class="form-control" type="text" value="{{ $giftID }}" id="giftID" name="giftID" required disabled>
						<small class="form-text small-text-color">Gift will be given to user who answered this question</small>
						<small class="form-text small-text-color">0 means nothing</small>
					</fieldset>
				</div>

				<div class="col-lg-4">
					<fieldset class="form-group">
						<label for="weight">Weight</label>
						<input class="form-control" type="text" value="{{ $weight }}" id="weight" name="weight" required>
						<small class="form-text small-text-color">Higher weight means bigger chance to select.</small>
						<small class="form-text small-text-color">Set 0 means SKIP this question to user.</small>
					</fieldset>
				</div>



			</div>

			<!--  -------------------------------------------------------------------------------  -->
			<hr>
			<div class="row">
				<div class="col-lg-10">
					<fieldset class="form-group">
						<label for="question">Question</label>
						<input class="form-control" type="text" value="{{ $question }}" id="question" name="question" placeholder="Shall we talk?" required>
						<small class="form-text small-text-color">'Main' means it will be selected in daily question</small>
						<small class="form-text small-text-color">Question text, no length limit</small>
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<label for="bladeFolder">Type</label>
						<select class="form-control" id="layer" name="layer">
							<option value="1" {{ ($layer=="1")?"selected":"" }}>Main</option>
							<option value="2" {{ ($layer=="2")?"selected":"" }}>Follow</option>
						</select>
					</fieldset>
				</div>
			
			</div>

			<div class="row">
				<div class="col-lg-12"><label for="Answer">Answer</label></div>
				<div class="col-lg-3">
					<fieldset class="form-group">
						<label>Option</label>
						<input class="form-control" type="text" id="answerA" name="answerA" placeholder="1. Yes" required>
					</fieldset>
				</div>
				<div class="col-lg-5">
					<fieldset class="form-group">
						<label>Label</label>
						<!-- <input class="form-control select2_multiple" type="text" id="answerALabel" name="answerALabel"> -->
						<select name="answerALabel" id='answerALabel' class="form-control select2_multiple" style="width:100%;"></select>
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<label>Point</label>
						<input class="form-control" type="text" id="answerAPoint" name="answerAPoint" value="0">
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<label>Next ID</label>
						<input class="form-control" type="text" id="answerANextID" name="answerANextID" value="0">
					</fieldset>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-3">
					<fieldset class="form-group">
						<input class="form-control" type="text" id="answerB" name="answerB" placeholder="2. No" required>
					</fieldset>
				</div>
				<div class="col-lg-5">
					<fieldset class="form-group">
						<!-- <input class="form-control" type="text" id="answerBLabel" name="answerBLabel"> -->
						<select name="answerBLabel" id='answerBLabel' class="form-control select2_multiple" style="width:100%;"></select>
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<input class="form-control" type="text" id="answerBPoint" name="answerBPoint" value="0">
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<input class="form-control" type="text" id="answerBNextID" name="answerBNextID" value="0">
					</fieldset>
				</div>
			
			</div>

			<div class="row">
				<div class="col-lg-3">
					<fieldset class="form-group">
						<input class="form-control" type="text" id="answerC" name="answerC" placeholder="3. I don't know (Optional)">
					</fieldset>
				</div>
				<div class="col-lg-5">
					<fieldset class="form-group">
						<!-- <input class="form-control" type="text" id="answerCLabel" name="answerCLabel"> -->
						<select name="answerCLabel" id='answerCLabel' class="form-control select2_multiple" style="width:100%;"></select>
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<input class="form-control" type="text" id="answerCPoint" name="answerCPoint" value="0">
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<input class="form-control" type="text" id="answerCNextID" name="answerCNextID" value="0">
					</fieldset>
				</div>
				
			</div>

			<div class="row">
				<div class="col-lg-3">
					<fieldset class="form-group">
						<input class="form-control" type="text" id="answerD" name="answerD" placeholder="4. You guess (Optional)">
					</fieldset>
				</div>
				<div class="col-lg-5">
					<fieldset class="form-group">
						<!-- <input class="form-control" type="text" id="answerDLabel" name="answerDLabel"> -->
						<select name="answerDLabel" id='answerDLabel' class="form-control select2_multiple" style="width:100%;"></select>
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<input class="form-control" type="text" id="answerDPoint" name="answerDPoint" value="0">
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<input class="form-control" type="text" id="answerDNextID" name="answerDNextID" value="0">
					</fieldset>
				</div>
	
			</div>

			<div class="row">
				<div class="col-lg-3">
					<fieldset class="form-group">
						<input class="form-control" type="text" id="answerE" name="answerE" placeholder="5. Won't tell you (Optional)">
					</fieldset>
				</div>
				<div class="col-lg-5">
					<fieldset class="form-group">
						<!-- <input class="form-control" type="text" id="answerELabel" name="answerELabel"> -->
						<select name="answerELabel" id='answerELabel' class="form-control select2_multiple" style="width:100%;"></select>
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<input class="form-control" type="text" id="answerEPoint" name="answerEPoint" value="0">
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<input class="form-control" type="text" id="answerENextID" name="answerENextID" value="0">
					</fieldset>
				</div>
		
			</div>

			<div class="row">
				<div class="col-lg-3">
					<fieldset class="form-group">
						<input class="form-control" type="text" id="answerF" name="answerF" placeholder="6. Both (Optional)">
					</fieldset>
				</div>
				<div class="col-lg-5">
					<fieldset class="form-group">
						<!-- <input class="form-control" type="text" id="answerFLabel" name="answerFLabel"> -->
						<select name="answerFLabel" id='answerFLabel' class="form-control select2_multiple" style="width:100%;"></select>
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<input class="form-control" type="text" id="answerFPoint" name="answerFPoint" value="0">
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<input class="form-control" type="text" id="answerFNextID" name="answerFNextID" value="0">
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-3">
					<fieldset class="form-group">
						<input class="form-control" type="text" id="answerG" name="answerG" placeholder="7. Secret (Optional)">
					</fieldset>
				</div>
				<div class="col-lg-5">
					<fieldset class="form-group">
						<!-- <input class="form-control" type="text" id="answerGLabel" name="answerGLabel"> -->
						<select name="answerGLabel" id='answerGLabel' class="form-control select2_multiple" style="width:100%;"></select>
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<input class="form-control" type="text" id="answerGPoint" name="answerGPoint" value="0">
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<input class="form-control" type="text" id="answerGNextID" name="answerGNextID" value="0">
					</fieldset>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-3">
					<fieldset class="form-group">
						<input class="form-control" type="text" id="answerH" name="answerH" placeholder="8. None (Optional)">
						<small class="form-text small-text-color">Answer text, syntax "KEY. ANSWER TEXT""</small>
						<small class="form-text small-text-color">No length limit</small>
					</fieldset>
				</div>
				<div class="col-lg-5">
					<fieldset class="form-group">
						<!-- <input class="form-control" type="text" id="answerHLabel" name="answerHLabel"> -->
						<select name="answerHLabel" id='answerHLabel' class="form-control select2_multiple" style="width:100%;"></select>
						<!-- <small class="form-text small-text-color">Separate with comma if more than one</small> -->
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
						<input class="form-control" type="text" id="answerHPoint" name="answerHPoint" value="0">
						<small class="form-text small-text-color">Point given when pick this answer</small>
					</fieldset>
				</div>
				<div class="col-lg-2">
					<fieldset class="form-group">
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
			<input id="answerALabelStr" name="answerALabelStr" type="hidden" value="">
			<input id="answerBLabelStr" name="answerBLabelStr" type="hidden" value="">
			<input id="answerCLabelStr" name="answerCLabelStr" type="hidden" value="">
			<input id="answerDLabelStr" name="answerDLabelStr" type="hidden" value="">
			<input id="answerELabelStr" name="answerELabelStr" type="hidden" value="">
			<input id="answerFLabelStr" name="answerFLabelStr" type="hidden" value="">
			<input id="answerGLabelStr" name="answerGLabelStr" type="hidden" value="">
			<input id="answerHLabelStr" name="answerHLabelStr" type="hidden" value="">
		</div>
	</div>
</form>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{ asset('js/ckeditor.js') }}?v=1"></script>
<script type="text/javascript" src="{{ asset('js/common.js') }}?v=1"></script>
<!-- select2 autocomplete -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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

			// change the value from select2 lib to string and then pass to backend 
			var index = 65;
			var id = "";
			var idStr = "";
			var labelStr = "";
			for (let i = 0; i < 8; i++) { // max :8 answer options
				id ='answer'+String.fromCharCode(index+i)+'Label';
				idStr = 'answer'+String.fromCharCode(index+i)+'LabelStr';
				labelStr = $('#'+id).select2('val').join(',');
				document.getElementById(idStr).value = labelStr;
			}
			
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
			// console.log( Object.values(item));
			var divName = "answer"+String.fromCharCode(index)+"Label";
			$('#'+divName).val(Object.values(item));
			$('#'+divName).trigger('change'); 
			// document.getElementById(divName).value = item;
			
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

<script type="text/javascript">

	// take the label list from DB
	var data = @json($tagArray);

	$('.select2_multiple').select2({
		placeholder: 'Select label(s)',
		multiple: true,
		theme: "classic",
		tags: true, // allow new tags
		data: data,
	});

</script>

@endsection
