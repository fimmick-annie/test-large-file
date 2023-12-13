<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Session;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class FosoUser extends Authenticatable implements Auditable
{
    use Notifiable;
    use HasRoles;
    use \OwenIt\Auditing\Auditable;

    protected $guard = 'foso';
	protected $guard_name = 'foso';
	protected $table = 'foso_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token',
        'google_2fa_key'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Attributes to exclude from the Audit.
     *
     * @var array
     */
    protected $auditExclude = [
        'password',
        'remember_token'
    ];

    /**
     * Should the timestamps be audited?
     *
     * @var bool
     */
    protected $auditTimestamps = true;

    public function setPasswordAttribute($password){
        $this->attributes['password'] = bcrypt($password);
    }

    // Google2FA
    public function getGoogle2faKeyByEmail($email){
        return FosoUser::where('email', $email)->first('google_2fa_key');
    }

    // GoogleLogin attributes
    public function setGoogleLoginAttribute($flag){
        Session::put('google_login', $flag);
    }

    public function getGoogleLoginAttribute(){
        return Session::get('google_login', 0);
    }

    public function setGoogleNameAttribute($name){
        Session::put('google_name', $name);
    }

    public function getGoogleNameAttribute(){
        return Session::get('google_name', null);
    }

    public function setGooglePictureAttribute($picture){
        Session::put('google_picture', $picture);
    }

    public function getGooglePictureAttribute(){
        return Session::get('google_picture', null);
    }
}
