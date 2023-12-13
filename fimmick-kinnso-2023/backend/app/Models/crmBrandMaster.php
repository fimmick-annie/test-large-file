<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class crmBrandMaster extends Model
{
    //  Inform Model it is soft delete instead of real delete
	use SoftDeletes;

	//----------------------------------------------------------------------------------------
	//  The attributes that are mass assignable.
	protected $guarded = ['id'];

	public $timestamps = false;

	public static function getByName($name) {
		return self::where('brand_name', $name)
			->first();
	}
}
