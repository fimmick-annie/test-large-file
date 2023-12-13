@extends('foso.layouts.default')

@section('page_title', 'User Administration Roles Management')

@section('breadcrumb_train')
<li><i class="fa fa-angle-right"></i><a href="{{ route('foso.roles.index') }}">Roles Management</a></li>
<li><i class="fa fa-angle-right"></i> Edit Role</li>
@endsection

@section('content')

<div class="card">
	<div class="card-body">

		<div class="row">
			<div class="col-sm-12">
				<p>Editing role '{{ $role->name }}'.</p>
			</div>
		</div>

		{{ Form::model($role, array('route' => array('foso.roles.update', $role->id), 'method' => 'PUT')) }}

		<div class="form-group">
			{{ Form::label('name', 'Role Name') }}
			{{ Form::text('name', null, array('class' => 'form-control')) }}
		</div>

		{{ Form::label('name', 'Permission') }}
		<div class="form-group">
@foreach ($permissions as $permission)
			{{Form::checkbox('permissions[]',  $permission->id, $role->permissions ) }}
			{{Form::label($permission->name, ucfirst($permission->name)) }}<br>
@endforeach
		</div>

		{{ Form::submit('Submit', array('class' => 'btn btn-success waves-effect waves-light m-r-10')) }}
		{{ Form::close() }}

	</div>
</div>

@endsection
