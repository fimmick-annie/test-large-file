<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class IncomeMediaRecord extends Model
{
    //  Inform Model it is soft delete instead of real delete
    use SoftDeletes;

    //----------------------------------------------------------------------------------------
    //  The attributes that are mass assignable.
    protected $guarded = ['id'];
    
    //----------------------------------------------------------------------------------------
    public static function addRecord($mobile, $size, $path, $caption, $status)
    {

        $record = new IncomeMediaRecord();
        $record->mobile = $mobile;
        $record->size = $size;
        $record->path = $path;
        $record->caption = $caption;
        $record->status = $status;


        $record->save();
        return $record;
    }
}


