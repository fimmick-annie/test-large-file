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
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use GuzzleHttp;
use Session;
use App\Models\AppUser;
use Auth;
use Illuminate\Support\Str;

//========================================================================================
class AppController extends Controller  {

	//----------------------------------------------------------------------------------------
	public function root(Request $request)  {
		return 'ok';
	}

	//----------------------------------------------------------------------------------------
	public function getUserInfo(Request $request)  {
		$token = $request->input('token', '');
		$user = AppUser::getUserByAuthToken($token);
		if ($user)  {

			//  Need to login again 2 days before token expiry
			//  prevent token expiry when user logined and using that token
			if ($user->token_expiry_at > date('Y-m-d H:i:s', strtotime('+2days')))  {
				return ['ok' => true, 'user' => $user];
			}
		}
		return ['ok' => false];
	}

	//----------------------------------------------------------------------------------------
	public function loginAPI(Request $request)  {
		$email = $request->input('email', '');
		$user = AppUser::getUserByEmail($email);
		if (!$user)  {
			return ['status' => '-1','message' => 'Invalid username or password.'];
		}

		$password = $request->input('password', '');
		if (!password_verify($password, $user->password))  {
			return ['status' => '-2','message' => 'Invalid username or password.'];
		}

		//  Login in success
		$expiry = date('Y-m-d 23:59:59', strtotime('+30days'));
		for ($i=0; $i<20; $i++)  {

			//  Generate a token
			$token = Str::random(60);

			//  Check is alreay exist or not
			if (!AppUser::getUserByAuthToken($token))  {break;}

			if ($i > 15)  {
				return ['status' => '-3','message' => 'Please try later.'];
			}
		}
		$user->api_token = $token;
		$user->token_expiry_at = $expiry;
		$user->save();

		return ['status'=>0,'message'=>'Login success.', 'ok'=>true, 'user'=>$user];
	}

}
