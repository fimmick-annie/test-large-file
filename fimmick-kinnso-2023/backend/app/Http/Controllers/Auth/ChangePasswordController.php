<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Auth;
use Hash;
use Validator;

class ChangePasswordController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Change Password Controller
	|--------------------------------------------------------------------------
	|
	| This controller is responsible for handling change password requests.
	|
	*/

	use AuthenticatesUsers;

	/**
	 * Where to redirect users after change password.
	 *
	 * @var string
	 */
	protected $redirectTo = '/logout';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth:foso');
	}

	public function showChangePasswordForm()
	{
		return view('foso.auth.changepassword');
	}

	public function changePassword(Request $request)
	{
		if(! (Hash::check($request->get('current-password'), Auth::user()->password)))
		{
			// The passwords does not matches
			return redirect()->back()->with('flash', [
				'message' => 'Your current password does not matches with the password you provided. Please try again.'
				, 'class' => 'alert-error'
			]);
		}

		if(strcmp($request->get('current-password'), $request->get('new-password')) == 0)
		{
			// Current password and new password are same
			return redirect()->back()->with('flash', [
				'message' => 'New Password cannot be same as your current password. Please choose a different password.'
				, 'class' => 'alert-error'
			]);
        }

		$validator = Validator::make($request->all(), [
			'current-password' => 'required',
			'new-password' => 'required|string|min:6|confirmed',
		]);

		if($validator->fails())
		{
			$error = $validator->errors()->first();
			return redirect()->back()->with('flash', [
				'message' => $error
				, 'class' => 'alert-error'
			]);
		}

		// Change Password after passing all checking
		$user = Auth::user();
		$user->password = $request->get('new-password');
		// $user->change_password_at = date('Y-m-d H:i:s');
		$user->save();

		// force login again
		Auth::logout();
		return redirect()->route('foso.main.login.html')->with('flash', [
			'message' => 'Password changed successfully! Please login again.'
			, 'class' => 'alert-success'
		]);
	}
}
