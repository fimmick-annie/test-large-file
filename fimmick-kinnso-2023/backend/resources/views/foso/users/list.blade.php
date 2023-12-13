@extends('foso.layouts.default')

@section('page_title', 'User Administration FOSO Users')

@section('breadcrumb_train')
<li><i class="fa fa-angle-right"></i><a href="{{ route('foso.users.index') }}">FOSO Users</a></li>
@endsection

@section('content')

<div class="card">
	<div class="card-body">

		<div class="row">
			<div class="col-sm-12">
				<p>List of FOSO users.</p>
			</div>
		</div>

		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Email</th>
						<th>Create At</th>
						<th>Role</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($users as $user)
						<tr>
							<th scope="row">{{ $user->id }}</th>
							<td>{{ $user->name }}</td>
							<td>{{ $user->email }}</td>
							<td>{{ $user->created_at != '' ? $user->created_at->format('Y-m-d H:i:s') : '' }}</td>
							<td>{{ $user->roles()->pluck('name')->implode(',') }}</td>
							<td>
								<a href="{{ route('foso.users.edit', $user->id) }}"><i class="fa fa-pencil"></i></a>

								{!! Form::open(['method' => 'DELETE', 'route' => ['foso.users.destroy', $user->id], 'class' => 'delete_form' ]) !!}
								<button><i class="fa fa-trash-o"></i></button>
								{!! Form::close() !!}

							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>

		<a href="{{ route('foso.users.create') }}" class="btn btn-success">New</a>
	</div>
</div>

@endsection
