@extends('foso.layouts.default')

@section('page_title', 'Members Search')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.members.search.html") }}'>App</a></li>
<li><i class='fa fa-angle-right'></i>  user</li>
@endsection

@section('content')

<div class="card">
	<div class='card-body'>
		<div class="row">
			<div class="col-sm-6">
				coming soon.
			</div>
		</div>

	</div>

</div><br>

<script type="text/javascript" src="{{ asset('assets/DataTables/datatables.min.js') }}?v=1"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3.min.js"></script> -->

<script type="text/javascript" src="{{ asset('js/common.js') }}?v=1"></script>

<script>
	
</script>

@endsection