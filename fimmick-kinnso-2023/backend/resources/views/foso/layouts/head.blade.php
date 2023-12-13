	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- No index header (Deny indexing by search engine - Google, yahoo etc..)  -->
		<meta name="robots" content="noindex">
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<!-- Google2FA -->
		<meta name="google-signin-client_id" content="{{ env('GOOGLE_CLIENT_ID', '') }}">

		<title>FOSO for Kinnso</title>

		<!--  v4.0.0  -->
		<link rel="stylesheet" href="{{asset('assets/vendor/adminkit/bootstrap/css/bootstrap.min.css?v=1')}}">

		<!--  Favicon  -->
		<link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/foso/images/foso_icon_mobile.png?v=1')}}">

		<!--  Google Font  -->
		<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">

		<!--  Theme style  -->
		<link rel="stylesheet" href="{{asset('assets/vendor/adminkit/css/style.css?v=1')}}">
		<link rel="stylesheet" href="{{asset('assets/vendor/adminkit/css/font-awesome/css/font-awesome.min.css?v=1')}}">
		<link rel="stylesheet" href="{{asset('assets/vendor/adminkit/css/et-line-font/et-line-font.css?v=1')}}">
		<link rel="stylesheet" href="{{asset('assets/vendor/adminkit/css/themify-icons/themify-icons.css?v=1')}}">
		<link rel="stylesheet" href="{{asset('assets/vendor/adminkit/css/simple-lineicon/simple-line-icons.css?v=1')}}">
		<link rel="stylesheet" href="{{asset('assets/vendor/adminkit/plugins/popover/bootstrap-popover-x.css?v=1')}}">
@yield('plugin_css')
		<link rel="stylesheet" href="{{asset('assets/foso/css/main.css?v=8')}}">

		<!--  DataTables  -->
		<link rel="stylesheet" type="text/css" href="{{ asset('assets/DataTables/datatables.min.css?v=1')}}"/>
		<link rel="stylesheet" href="{{asset('assets/vendor/adminkit/plugins/dropify/dropify.min.css?v=1')}}"/>

		<style>
			.collapse  {
				padding-top: 10px;
			}
			.form-control::placeholder  {
				color: #d0d0d0;
				opacity: 1;
			}
		</style>

@yield('plugin_js_lead')
		<script src="{{asset('assets/vendor/adminkit/js/jquery.min.js?v=1')}}"></script>
		<script src="{{asset('assets/vendor/adminkit/bootstrap/js/bootstrap.min.js?v=1')}}"></script>
		<script src="{{asset('assets/vendor/adminkit/js/adminkit.js?v=1')}}"></script>
		<script src="{{asset('assets/vendor/adminkit/plugins/popover/bootstrap-popover-x.js?v=1')}}"></script>

		<!-- Google2FA + GoogleLogin -->
		<script src="https://apis.google.com/js/platform.js?onload=init" async defer></script>
		<script>
			// GoogleLogin
			function init() {
				gapi.load('auth2', function() {
					gapi.auth2.init({
					  client_id: "{{ env('GOOGLE_CLIENT_ID', '') }}"
					});
				});
			}
		</script>
@yield('plugin_js')
	</head>
