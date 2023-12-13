<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Johnson Shan
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020.  All rights reserved.
//----------------------------------------------------------------------------------------

namespace App\Models;

//----------------------------------------------------------------------------------------
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

use OwenIt\Auditing\Contracts\Auditable;

//========================================================================================
class DashboardGenericChart extends Model implements Auditable
{

    //  Inform Model it is soft delete instead of real delete
    use SoftDeletes;

    use \OwenIt\Auditing\Auditable;

    //----------------------------------------------------------------------------------------
    //  The attributes that are mass assignable.
    protected $guarded = ['id'];

    //----------------------------------------------------------------------------------------

    public static function getListByOfferId($offerId)
    {
        $query = self::query();
        $record = $query
            ->where('offer_id', $offerId)
            ->orderBy('id', 'DESC')
            ->take(14)
            ->get();
        return $record;
    }
}
