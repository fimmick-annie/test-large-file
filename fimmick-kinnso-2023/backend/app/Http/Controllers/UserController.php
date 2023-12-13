<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020.  All rights reserved.
//----------------------------------------------------------------------------------------

namespace App\Http\Controllers;

//----------------------------------------------------------------------------------------
use PragmaRX\Google2FAQRCode\Google2FA;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Session;
use Auth;

use App\Models\FosoUser;
use App\Models\FosoActivityLog;
use App\Http\Controllers\EmailController;

//========================================================================================
class UserController extends Controller  {

	//----------------------------------------------------------------------------------------
	//  Display a listing of the resource.
	public function index()
	{
		$users = FosoUser::all();
		return view('foso.users.list')->with('users', $users);
	}

	//----------------------------------------------------------------------------------------
	//  Show the form for creating a new resource.
	public function create()  {

		//  Get all roles and pass it to the view
		$roles = Role::get();
		return view('foso.users.create', compact('roles'));
	}

	//----------------------------------------------------------------------------------------
	//  Store a newly created resource in storage.
	public function store(Request $request)  {

		//  Validate name, email and password fields
		$this->validate($request, [
			'name' => 'required|max:120',
			'email' => 'required|email|unique:foso_users',
			// 'password' => 'required|min:3|max:20',
			// 'password_confirmation' => 'required|min:3|max:20|same:password',
			// 'password'=>'required|min:8|max:16|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,16}$/'
		]);

		// $data = $request->only('email', 'name', 'password');
		$data = $request->only('email', 'name');
		$data['api_token'] = Str::random(60);
		$data['password'] = Str::random(8);

		//  Generate a new Google2FA secret key
		$google2FAEnabled = env("GOOGLE_TWOFA_ENABLED", false);
		if ($google2FAEnabled == true)  {

			$google2fa = new Google2FA();
			$secret_key = $google2fa->generateSecretKey();
			$data['google_2fa_key'] = $secret_key;
		}

		$user = FosoUser::create($data); //Retrieving only the email and password data
		$roles = $request['roles'];

		//  Checking if a role was selected
		if (isset($roles)) {
			foreach ($roles as $role) {

				$role_r = Role::where('id', '=', $role)->firstOrFail();
				$user->assignRole($role_r);
			}
		}

		//  TODO: Include 2FA QR code

		//----------------------------------------------------------------------------------------
		//  Send login information to user
		$email = $data['email'];
		EmailController::sendFosoUserInfoEmail($email, $data['password']);

		//  Activity log
		$adminUser = Auth::user();
		FosoActivityLog::addLog($adminUser->name, $adminUser->email, url()->full(), "Create a new FOSO user with email $email", "Admin");

		//----------------------------------------------------------------------------------------
		//  Redirect to the users.index view and display message
		return redirect()->route('foso.users.index')
			->with('flash_message', 'User successfully added.');
	}

	//----------------------------------------------------------------------------------------
	//  Display the specified resource.
	public function show($id)
	{
		return redirect('users');
	}

	//----------------------------------------------------------------------------------------
	//  Show the form for editing the specified resource.
	public function edit($id)  {

		$user = FosoUser::findOrFail($id);
		$roles = Role::get();

		//  Display Google2FA QR code using the secret key
		$qrcode = "";
		if (env("APP_ENV", "local") == "local")  {$qrcode = "";}
		else  {

			$google2FAEnabled = env("GOOGLE_TWOFA_ENABLED", false);
			if ($google2FAEnabled == true)  {

				//  Create 2FA key now if empty
				//  Somehow 2FA was not enabled, but it is enabled now, so no 2FA key.
				//  And we don't have a flow to create a key.  That's why create here.
				$google2fa = new Google2FA();
				if (empty($user->google_2fa_key))  {

					$secret_key = $google2fa->generateSecretKey();
					$user->google_2fa_key = $secret_key;
					$user->save();
				}

				//  Create QR code
				$appName = env("WHATSAPP_PREFIX", "").config('app.name');
				$qrcode = $google2fa->getQRCodeInline($appName, $user->email, $user->google_2fa_key);

				//  Activity log
				$adminUser = Auth::user();
				FosoActivityLog::addLog($adminUser->name, $adminUser->email, url()->full(), "Generate a new Google 2FA for FOSO user with ID #$id", "Admin");

			}
		}
		return view('foso.users.edit', compact('user', 'roles', 'qrcode'));
	}

	//----------------------------------------------------------------------------------------
	//  Update the specified resource in storage.
	public function update(Request $request, $id)  {

		$user = FosoUser::findOrFail($id);

		//  Validate name, email and password fields
		$this->validate($request, [
			'name' => 'required|max:120',
			'email' => 'required|email|unique:foso_users,email,' . $id,
			// 'password'=>'required|min:6|confirmed'
		]);

		$roles = $request['roles'];
		$user->name = $request->name;
		$user->email = $request->email;
		$user->save();

		if (isset($roles)) {
			$user->roles()->sync($roles);
		} else {
			$user->roles()->detach();
		}

		$email = $request->email;

		//  Activity log
		$adminUser = Auth::user();
		FosoActivityLog::addLog($adminUser->name, $adminUser->email, url()->full(), "Updated a FOSO user with ID #$id and email $email", "Admin");

		return redirect()->route('foso.users.index')
			->with('flash_message', 'User successfully edited.');
	}

	//----------------------------------------------------------------------------------------
	//  Remove the specified resource from storage.
	public function destroy($id)  {

		//  Find a user with a given id and delete
		$user = FosoUser::findOrFail($id);
		if ($user == null)  {

			return redirect()->route('foso.users.index')
				->with('flash_message', "Unable to delete user #$id...");
		}

		$email = $user->email;
		$user->delete();

		//  Activity log
		$adminUser = Auth::user();
		FosoActivityLog::addLog($adminUser->name, $adminUser->email, url()->full(), "Deleted a FOSO user with ID #$id and email $email", "Admin");

		return redirect()->route('foso.users.index')
			->with('flash_message', 'User successfully deleted.');
	}
}