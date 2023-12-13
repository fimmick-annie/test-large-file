<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
class AppUser extends Authenticatable implements Auditable
{
    use Notifiable;
    use HasRoles;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    
    protected $guard = 'app';
	protected $guard_name = 'app';
	protected $table = 'app_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token', 'roles', 'deleted_at'
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

    public static function getUserByEmail($email) {
        return self::where('email', $email)->first();
    }
    public static function getUserByAuthToken($token) {
        return self::where('api_token', $token)->first();
    }
}
