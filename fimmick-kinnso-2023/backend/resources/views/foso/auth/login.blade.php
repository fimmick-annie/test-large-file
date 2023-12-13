<!DOCTYPE html>
<html lang="en">
	@include('foso.layouts.head')
	<body class="hold-transition login-page sty1">
		<div class="login-box sty1">
			<div class="login-box-body sty1">
				<div class="login-logo">
					<a href="http://www.fimmickcrm.com/" target="_blank"><img src="{{asset('assets/foso/images/logo.png')}}" alt=""></a>
				</div>
				<p class="login-box-msg">Sign in to start your session</p>
@if(Session::has('flash'))
				<div class="alert {{ session('flash')['class'] }} alert-dismissible fade show" role="alert">
					{{ session('flash')['message'] }}
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
				</div>
@endif
				<form class="m-t-3" method="post" action="{{ route('foso.main.login.html') }}">
					@csrf

					<div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
						<input id="email" type="staff_num" class="form-control sty1" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
@if ($errors->has('email'))
						<label class="control-label" for="email">{{ $errors->first('email') }}</label>
@endif
					</div>

					<div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
						<input id="password" type="password" class="form-control sty1" name="password" placeholder="Password" required>
@if ($errors->has('password'))
						<label class="control-label" for="password">{{ $errors->first('password') }}</label>
@endif
					</div>

@if (Config::get('auth.google2fa'))
					<div class="form-group has-feedback{{ $errors->has('one_time_password') ? ' has-error' : '' }}">
						<input id="one_time_password" type="staff_num" class="form-control sty1" name="one_time_password" placeholder="Google 2FA" oninput="this.value=this.value.replace(/[^0-9]/g,'');" required>
@if ($errors->has('one_time_password'))
						<label class="control-label" for="one_time_password">{{ $errors->first('one_time_password') }}</label>
@endif
					</div>
@endif
					<div>
						<div class="col-xs-8">
							<div class="checkbox icheck">
								<label><input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me </label>
							</div>
						</div>
							<!-- /.col -->
						<div class="col-xs-4 m-t-1 m-b-2">
							<button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
						</div>
						<!-- /.col -->

@if (Config::get('auth.google_login'))
						<!-- GoogleLogin -->
						<div class="col-xs-8">
							<label>OR</label>
							<div class="g-signin2 m-t-1" data-onsuccess="onSignIn"></div>
							<div id="google_err"></div>
						</div>
@endif
					</div>
				</form>
			</div>
			<!-- /.login-box-body -->
		</div>
		<!-- /.login-box -->

		<script>
			// GoogleLogin
			window.onload = function() {
				var auth2 = gapi.auth2.getAuthInstance();
				auth2.signOut().then(function() {
					// console.log(111);
				});
			};
			function onSignIn(googleUser) {
				let id_token = googleUser.getAuthResponse().id_token;
				let csrfToken = $('meta[name="csrf-token"]').attr('content');
				let params = 'idtoken=' + id_token;
				let xhr = new XMLHttpRequest();
				xhr.open('POST', "{{ route('foso.main.login.html') }}" );
				xhr.setRequestHeader("x-csrf-token", csrfToken);
				xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				xhr.onload = function() {
					// Unauthorised user
					if(xhr.responseText.substring(0,6) != 'Login:') {
						$('#google_err').html(xhr.responseText);
					} else {
					// Authorised user
						window.location.href = "{{ route('foso.main.home.html') }}";
					}
				};
				xhr.send(params);
			}
		</script>

		@include('foso.layouts.scripts')
	</body>
</html>
