<!DOCTYPE html>
<html lang="en">
	{{-- Include head --}}
	@include('foso.layouts.head')
	<body class="skin-blue sidebar-mini @yield('page_class')">

		<div id="loading" style="position:fixed;z-index:9999990;top:0;left:0;width:100%;height:100%;display:none;align-items:center;justify-content:center;background-color:rgba(0,0,0,.8);">
			<img src="{{ asset('offers/common/loading.gif') }}?v=1" alt="loading" style="max-width:200px;" />
		</div>

		<div class="wrapper boxed-wrapper">
			{{-- Include header --}}
			@include('foso.layouts.header')

			<!-- Left side column. contains the logo and sidebar -->
			{{-- Include sidebar --}}
			@include('foso.layouts.aside')

			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				{{-- Breadcrumb --}}
				@include('foso.layouts.breadcrumb')

				{{-- Where content place --}}
				<div class="content">
					@yield('content')
				</div>
			</div>
			<!-- /.content-wrapper -->

			{{-- Include footer --}}
			@include('foso.layouts.footer')
		</div>
		<!-- ./wrapper -->

		@yield('scripts')
	</body>
</html>
