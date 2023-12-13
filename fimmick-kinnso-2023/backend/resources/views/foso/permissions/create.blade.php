@extends('foso.layouts.default') 

@section('page_title','User Administration')

@section('breadcrumb_train')
<li><i class="fa fa-angle-right"></i><a href="{{ route('foso.permissions.index') }}">Permissions List</a></li>
<li><i class="fa fa-angle-right"></i> Create Permission</li>
@endsection

@section('content')

<div class="card">
    <div class="card-body">

        <div class="row">
            <div class=" col-sm-6">
                <h4 class="text-black">Create Permission</h4>
                <p>Create permission for a foso user</p>
            </div>
            <div class="upper-button-group col-sm-6 text-right">
                <a href="{{ route('foso.permissions.index') }}" class="btn btn-primary"><i class="fa fa-step-backward"></i></a>
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
        {{ Form::open(array('route' => ['foso.permissions.store'])) }}
    
        <div class="form-group">
            {{ Form::label('name', 'Name') }}
            {{ Form::text('name', '', array('class' => 'form-control')) }}
        </div>

        {{ Form::submit('Create', array('class' => 'btn btn-success waves-effect waves-light m-r-10')) }}
    
        {{ Form::close() }}

    </div>
</div>

@endsection