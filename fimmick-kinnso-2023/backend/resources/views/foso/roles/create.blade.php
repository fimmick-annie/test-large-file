@extends('foso.layouts.default')

@section('page_title', 'User Administration Roles Management')

@section('breadcrumb_train')
<li><i class="fa fa-angle-right"></i><a href="{{ route('foso.roles.index') }}">Roles Management</a></li>
<li><i class="fa fa-angle-right"></i> Create Role</li>
@endsection

@section('content')

<div class="card">
	<div class="card-body">

		<div class="row">
			<div class=" col-sm-6">
				<h4 class="text-black">Create Role</h4>
				<p>Create a new role with specific permissions.</p>
			</div>
		</div>

		{{ Form::open(array('route' => ['foso.roles.store'])) }}

		<div class="form-group">
			{{ Form::label('name', 'Name') }}
			{{ Form::text('name', null, array('class' => 'form-control')) }}
		</div>

		{{ Form::label('name', 'Permissions') }}
		<div class='form-group'>
@foreach ($permissions as $permission)
			{{ Form::checkbox('permissions[]',  $permission->id, null, ['id' => ($permission->name)] ) }}
			{{ Form::label($permission->name, $permission->name) }}<br>
@endforeach
		</div>

		{{ Form::submit('Submit', array('class' => 'btn btn-success waves-effect waves-light m-r-10')) }}
		{{ Form::close() }}

	</div>
</div>

@endsection
