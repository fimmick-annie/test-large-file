@extends('foso.layouts.default')

@section('page_title', 'User Administration FOSO Users')

@section('breadcrumb_train')
<li><i class="fa fa-angle-right"></i><a href="{{ route('foso.users.index') }}">FOSO Users</a></li>
<li><i class="fa fa-angle-right"></i> Edit User</li>
@endsection

@section('content')

<div class="card">
	<div class="card-body">

		<div class="row">
			<div class="col-sm-12">
				<p>Editing user '{{ $user->name }}'.</p>
			</div>
		</div>

@if ($errors->any())
		<div class="alert alert-danger">
			<ul>
@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
@endforeach
			</ul>
		</div>
@endif
		{{ Form::model($user, array('route' => array('foso.users.update', $user->id), 'method' => 'PUT')) }}{{-- Form model binding to automatically populate our fields with user data --}}

		<div class="form-group">
			{{ Form::label('name', 'Name') }}
			{{ Form::text('name', null, array('class' => 'form-control')) }}
		</div>

		<div class="form-group">
			{{ Form::label('email', 'Email') }}
			{{ Form::email('email', null, array('class' => 'form-control')) }}
		</div>

		<div class="row">
			<div class="col-8">
				{{ Form::label('name', 'Role') }}
			</div>
			<div class="col-4 text-center">
				<!-- Google2FA -->
				{{ Form::label('name', 'Google 2FA Key') }}
			</div>
		</div>

		<div class="row">
			<div class="form-group col-8">
@foreach ($roles as $role)
			{{ Form::checkbox('roles[]',  $role->id, $user->roles, array('disabled')) }}
			{{ Form::label($role->name, ucfirst($role->name)) }}<br>
@endforeach
			</div>
			<div class="form-group col-4 text-center">
				<!-- Google2FA -->
				<img src="{{ $qrcode }}">
				<br>{{ $user->google_2fa_key }}
			</div>
		</div>

		<div class="form-group">
			<div id="resultTable"></div>
		</div>

		{{-- <a href="{{ url()->previous() }}" class="btn btn-default">Back</a> --}}
		{{ Form::submit('Submit', array('class' => 'btn btn-success waves-effect waves-light m-r-10', )) }}
		{{ Form::close() }}

	</div>
</div>

@endsection
