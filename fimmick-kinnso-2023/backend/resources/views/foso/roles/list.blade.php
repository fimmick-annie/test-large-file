@extends('foso.layouts.default')

@section('page_title', 'User Administration Roles Management')

@section('breadcrumb_train')
<li><i class="fa fa-angle-right"></i><a href="{{ route('foso.roles.index') }}">Roles Management</a></li>
@endsection

@section('content')

<div class="card">
	<div class="card-body">

		<div class="row">
			<div class="col-sm-12">
				<p>List of FOSO roles and its permissions.</p>
			</div>
		</div>

		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>ID</th>
						<th>Role</th>
						<th>Permissions</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($roles as $role)
						<tr>
							<th scope="row">{{ $role->id }}</th>
							<td>{{ $role->name }}</td>
							<td>{!! str_replace(array('[', ']', '"', ','), array('', '', '', ' | '), $role->permissions()->pluck('name')) !!}</td>{{-- Retrieve array of permissions associated to a role and convert to string --}}
							<td>
								<a href="{{ route('foso.roles.edit', $role->id) }}" ><i class="fa fa-pencil"></i></a>

								{{-- {!! Form::open(['method' => 'DELETE', 'route' => ['foso.roles.destroy', $role->id], 'class' => 'delete_form']) !!}
								<button><i class="fa fa-trash-o"></i></button>
								{!! Form::close() !!} --}}

							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>

		<a href="{{ route('foso.roles.create') }}" class="btn btn-success">New</a>
	</div>
</div>

@endsection
