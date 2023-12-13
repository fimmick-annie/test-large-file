@extends('foso.layouts.default')

@section('page_title', 'User Administration Permissions')

@section('breadcrumb_train')
<li><i class="fa fa-angle-right"></i><a href="{{ route('foso.permissions.index') }}">Permissions</a></li>
@endsection

@section('content')

<div class="card">
	<div class="card-body">

		<div class="row">
			<div class="col-sm-12">
				<p>FOSO Permissions List</p>
			</div>
		</div>

		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>ID</th>
						<th>Permissions</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
@foreach ($permissions as $permission)
					<tr>
						<th scope="row">{{ $permission->id }}</th>
						<td>{{ $permission->name }}</td>
						<td>
							<a href="{{ route('foso.permissions.edit', $permission->id) }}" ><i class="fa fa-pencil"></i></a>

							{!! Form::open(['method' => 'DELETE', 'route' => ['foso.permissions.destroy', $permission->id], 'class' => 'delete_form']) !!}
							<button><i class="fa fa-trash-o"></i></button>
							{!! Form::close() !!}

						</td>
					</tr>
@endforeach
				</tbody>
			</table>
		</div>

		<a href="{{ route('foso.permissions.create') }}" class="btn btn-success">New</a>
	</div>
</div>

@endsection
