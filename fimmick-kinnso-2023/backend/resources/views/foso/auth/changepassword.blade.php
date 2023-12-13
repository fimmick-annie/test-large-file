@extends('foso.layouts.default') 

@section('page_title','My Profile')

@section('breadcrumb_train')
<li><i class="fa fa-angle-right"></i> Change Password</li>
@endsection

@section('content')
<form role="form" id="member_form" method="post" action="{{ route('foso.changePassword') }}">

	{{csrf_field()}}
	{{ method_field('POST') }}

	<div class="row">
		<div class="col-lg-12">
			<div class="card card-outline">
				<div class="card-header">
					<h5 class="m-b-0">Change Password</h5>
				</div>
				<div class="card-body">
					<div class="row">
				
                        <div class="col-sm-12 col-md-8 offset-md-2">
                            <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
                                <label for="new-password" class="col-md-4 control-label">Current Password</label>

                                <div class="col-md-12">
                                    <input id="current-password" type="password" class="form-control" name="current-password" required>

                                    @if ($errors->has('current-password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('current-password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('new-password') ? ' has-error' : '' }}">
                                <label for="new-password" class="col-md-4 control-label">New Password</label>

                                <div class="col-md-12">
                                    <input id="new-password" type="password" class="form-control" name="new-password" required>

                                    @if ($errors->has('new-password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('new-password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="new-password-confirm" class="col-md-4 control-label">Confirm New Password</label>

                                <div class="col-md-12">
                                    <input id="new-password-confirm" type="password" class="form-control" name="new-password_confirmation" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col text-center">
                                    <button type="submit" class="btn btn-primary">
                                        Confirm Change Password
                                    </button>
                                </div>
                            </div>
						</div>
					
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

@endsection