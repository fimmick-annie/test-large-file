@extends('foso.layouts.default')

@section('page_title', 'User Administration FOSO Users')

@section('breadcrumb_train')
<li><i class="fa fa-angle-right"></i><a href="{{ route('foso.users.index') }}">FOSO Users</a></li>
<li><i class="fa fa-angle-right"></i> Create User</li>
@endsection

@section('content')

<div class="card">
	<div class="card-body">

		<div class="row">
			<div class="col-sm-12">
				<p>Create user for a foso</p>
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
		{{ Form::open(array('route' => ['foso.users.store'])) }}

		<div class="form-group">
			{{ Form::label('name', 'Name') }}
			{{ Form::text('name', '', array('class' => 'form-control')) }}
		</div>

		<div class="form-group">
			{{ Form::label('email', 'Email') }}
			{{ Form::email('email', '', array('class' => 'form-control')) }}
		</div>

		{{ Form::label('name', 'Role') }}
		<div class='form-group'>
@foreach ($roles as $role)
			{{ Form::checkbox('roles[]',  $role->id ) }}
			{{ Form::label($role->name, ucfirst($role->name)) }}<br>
@endforeach
		</div>
		<!-- {{-- <div>8-16 Characters With Combination of <br/>At least 1 Lower Case, <br/>1 Upper Case, <br/>1 Symbols )(~_#?!@$%^&*- </div> --}}
		<div class="form-group">
			{{ Form::label('password', 'Password') }}<br>
			{{ Form::password('password', array('class' => 'form-control')) }}
		</div> -->

		<!-- <div class="form-group">
			{{ Form::label('password', 'Confirm Password') }}<br>
			{{ Form::password('password_confirmation', array('class' => 'form-control')) }}
		</div> -->

		{{ Form::submit('Submit', array('class' => 'btn btn-success waves-effect waves-light m-r-10')) }}

		{{ Form::close() }}
	</div>
</div>

@endsection
