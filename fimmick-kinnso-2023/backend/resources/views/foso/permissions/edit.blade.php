@extends('foso.layouts.default')

@section('page_title','User Administration')

@section('breadcrumb_train')
<li><i class="fa fa-angle-right"></i><a href="{{ route('foso.permissions.index') }}">Permissions List</a></li>
<li><i class="fa fa-angle-right"></i> Edit Permission</li>
@endsection

@section('content')

<div class="card">
    <div class="card-body">

        <div class="row">
            <div class=" col-sm-6">
                <h4 class="text-black">Edit Permission</h4>
                <p>Edit Permission : {{ $permission->name }}</p>
            </div>
            <div class="upper-button-group col-sm-6 text-right">
                <a href="{{ route('foso.permissions.index') }}" class="btn btn-primary"><i class="fa fa-step-backward"></i></a>
            </div>
        </div>

        {{ Form::model($permission, array('route' => array('foso.permissions.update', $permission->id), 'method' => 'PUT')) }}{{-- Form model binding to automatically populate our fields with permission data --}}

        <div class="form-group">
            {{ Form::label('name', 'Permission Name') }}
            {{ Form::text('name', null, array('class' => 'form-control')) }}
        </div>

        {{ Form::submit('Submit', array('class' => 'btn btn-success waves-effect waves-light m-r-10')) }}

        {{ Form::close() }}

    </div>
</div>

@endsection
