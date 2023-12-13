<header class="main-header">

	<!-- Logo -->
	<a href="{{ route('foso.main.home.html') }}" class="logo blue-bg">

	<!-- mini logo for sidebar mini 50x50 pixels -->
	<span class="logo-mini"><img src="{{ asset('assets/foso/images/foso_icon_mobile.png') }}" alt=""></span>

	<!-- logo for regular state and mobile devices -->
	<span class="logo-lg"><img src="{{ asset('assets/foso/images/foso_icon.png') }}" alt=""></span> </a>

	<!-- Header Navbar -->
	<nav class="navbar blue-bg navbar-static-top">

		<!-- Sidebar toggle button-->
		<ul class="nav navbar-nav pull-left">
			<li><a class="sidebar-toggle" data-toggle="push-menu" href=""></a> </li>
		</ul>

		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">

@if ( Auth::user()->getGoogleLoginAttribute() === 0 )
				<!-- User Account  -->
				<li class="dropdown user user-menu p-ph-res"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <img src="{{ asset('assets/foso/images/default_profile_pic.png') }}" class="user-image" alt="User Image"> <span class="hidden-xs">{{ Auth::user()->name }}</span> </a>
				<ul class="dropdown-menu">
					<li class="user-header">
						<div class="pull-left user-img"><img src="{{ asset('assets/foso/images/default_profile_pic.png') }}" class="img-responsive img-circle" alt="User"></div>
						<p class="text-left">{{ Auth::user()->name }} <small>{{ Auth::user()->email }}</small> </p>
					</li>
					<li role="separator" class="divider"></li>
					<li><a href="{{ route('foso.changePasswordForm') }}"><i class="icon-gears"></i> Reset Password</a></li>
					<li role="separator" class="divider"></li>
					<li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i> Logout</a></li>
@else
				<!-- GoogleLogin Profile -->
				<li class="dropdown user user-menu p-ph-res"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <img src="{{ Auth::user()->getGooglePictureAttribute() }}" class="user-image" alt="User Image"> <span class="hidden-xs">{{ Auth::user()->getGoogleNameAttribute() }}</span> </a>
				<ul class="dropdown-menu">
					<li class="user-header">
						<div class="pull-left user-img"><img src="{{ Auth::user()->getGooglePictureAttribute() }}" class="img-responsive img-circle" alt="User"></div>
						<p class="text-left">{{ Auth::user()->getGoogleNameAttribute() }} <small>{{ Auth::user()->email }}</small> </p>
					</li>
					<li role="separator" class="divider"></li>
					<li><a href="#" onclick="event.preventDefault(); logout(); document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i> Logout</a></li>
@endif
					<form id="logout-form" action="{{ route('foso.main.logout.json') }}" method="POST" style="display: none;">
						@csrf
					</form>
				</ul>
				</li>
			</ul>
		</div>
	</nav>

	<script>
		// GoogleLogin
		function logout() {
			var auth2 = gapi.auth2.getAuthInstance();
			auth2.signOut().then(function () {
			});
		}
	</script>

</header>
