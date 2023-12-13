@extends('foso.layouts.default')

@section('page_title', 'Campaign Offer #'.$offer->id.' Resources')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.campaigns.html") }}'>Campaigns</a></li>
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.campaigns.offer.html") }}'>Offer</a></li>
<li><i class='fa fa-angle-right'></i> Resources</li>
@endsection

@section('content')

<ul class="nav nav-tabs" role="tablist">
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.settings.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-gear"></i></span> <span class="hidden-xs-down">Settings</span></a> </li>
	<li class="nav-item"> <a class="nav-link active" href='{{ route("foso.campaigns.offer.resources.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-file-image-o"></i></span> <span class="hidden-xs-down">Resources</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.rules.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-cubes"></i></span> <span class="hidden-xs-down">Rules</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.coupons.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-tags"></i></span> <span class="hidden-xs-down">Coupons</span></a> </li>
@if ($offer->coupon_type == "randomly-generated")
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.quotas.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-shopping-cart"></i></span> <span class="hidden-xs-down">Quotas</span></a> </li>
@else
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.couponpool.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-shopping-cart"></i></span> <span class="hidden-xs-down">Coupon Pool</span></a> </li>
@endif
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.whatsapp.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-comment"></i></span> <span class="hidden-xs-down">WhatsApp</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.customerjourney.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-plane"></i></span> <span class="hidden-xs-down">Journey</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.channel.sample.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-tablet"></i></span> <span class="hidden-xs-down">Channel</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.offer.ini.html", ["offer_code"=>$offerCode]) }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-file-text-o"></i></span> <span class="hidden-xs-down">.ini</span></a> </li>
</ul>

<div class="card">
	<div class="card-body">
		<form id="form" name="form">
@include('foso.campaigns.resources.'.$offer->blade_folder)
		</form>
	</div>
</div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{ asset('js/ckeditor.js') }}?v=1"></script>
<script type="text/javascript" src="{{ asset('js/common.js') }}?v=1"></script>
<script src="{{asset('assets/vendor/adminkit/plugins/dropify/dropify.min.js')}}"></script>

<link rel="stylesheet" href="{{asset('assets/vendor/adminkit/plugins/dropify/dropify.min.css')}}" />

<script>
	$('.dropify').dropify({
		error: {
			'fileSize': 'The file size is too big...',
		}
	});

	$('.dropify').on('dropify.afterClear', function (event)  {

		var formdata = new FormData();
		formdata.append('_token', '{{ csrf_token() }}');
		formdata.append('filename', $(event.target).attr('name'));
		formdata.append('default', $(event.target).data('default-file'));
		formdata.append('remove', true);

		$.ajax({
			method: 'POST',
			url: '{{ route('foso.campaigns.offer.resources.upload', ['offer_code' => $offer->offer_code]) }}',
			data: formdata,
			dataType: 'json',
			processData: false,
			contentType: false,
			success: function(data, textStatus, jqXHR) {
				$(event.target).val('');
				console.log("Remove file status: "+data.status);
				console.log(data.stringify());
			}
		});
	});

	$('.dropify').on('change', function (event)  {
		var fileObject = $(event.target).prop('files');

		if (fileObject[0] != undefined) {

			var file = fileObject[0];

			var defaultFile    = $(event.target).data('default-file');
			var defaultFile2   = defaultFile.split('.');
			var checkExtension = defaultFile2[defaultFile2.length - 1];

			var fileExtension  = (file.name.split('.'));
			fileExtension      = fileExtension[fileExtension.length - 1];

			if (fileExtension != checkExtension) {
				var dropify = $(event.target).data('dropify');
				dropify.resetPreview();
				dropify.setPreview(dropify.isImage(), defaultFile);
				alert('Please upload .' + checkExtension + ' file');
				return false;
			}

			var maxFileSize = $(event.target).data('max-file-size');
			maxFileSize = maxFileSize.replace("M", "000000");
			if (!isNaN(file.size) && file.size > parseInt(maxFileSize)) {
				alert('Please upload a file within size of '+maxFileSize+' bytes.');
				return false;
			}

			var formdata = new FormData();
			formdata.append('_token', '{{ csrf_token() }}');
			formdata.append('filename', $(event.target).attr('name'));
			formdata.append('file', file);

			$.ajax({
				method: 'POST',
				url: '{{ route('foso.campaigns.offer.resources.upload', ['offer_code' => $offer->offer_code]) }}',
				data: formdata,
				dataType: 'json',
				processData: false,
				contentType: false,
				success: function(data, textStatus, jqXHR) {
					$(event.target).val('');
					if (data.status == 'ok') {
						alert('successfully uploaded.');
					} else {
						alert('failed to upload, please try again.');
					}
				}
			});
		}
	});
</script>

@endsection
