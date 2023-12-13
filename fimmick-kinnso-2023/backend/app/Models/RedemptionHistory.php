<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

use App\Models\Member;
use App\Models\Redemption;
use App\Models\RedemptionCode;

class RedemptionHistory extends Model
{
    use SoftDeletes;

    //----------------------------------------------------------------------------------------
	//  The attributes that are not mass assignable.
	protected $guarded = ['id'];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }

    public function redemption()
    {
        return $this->belongsTo(Redemption::class, 'redemption_id', 'id');
    }

    public function redemptionCode()
    {
        return $this->hasOne(RedemptionCode::class, 'redemption_history_id', 'id');
    }

    public static function getRedemptionHistoryDetail($memberID, $redemptionHistoryID)
    {
        $columns = ['id', 'redemption_id', 'void_at', 'expire_at'];
        $record = self::where('id', $redemptionHistoryID)
                    ->where('member_id', $memberID)
                    ->with('redemption:id,code_type,thumbnail_filename,title,subtitle,void_details')
                    ->with('redemptionCode:id,redemption_history_id,code')
                    ->first($columns);
        return $record;
    }

    public static function GetRedemptionHistoryByID($memberID, $recolumns=['*']){
        
        $record = self::has('redemption')->with('redemption')->where('member_id', $memberID)
        -> get($recolumns);
        
        return $record;
    }

    public static function GetRedemptionHistoryByID2($memberID, $recolumns=['*'], $listtype){
        
        
        $record = self::has('redemption')->with('redemption')->where('member_id', $memberID);

        $now = now();

        switch ($listtype){
            case "1":
                $record = $record->where(function($query) use ($now){
				    $query->where('expire_at', '>=' , $now)
                    ->orWhereNull('expire_at');
                });
				break;

			case "2":
				$record = $record->where('expire_at', '<' , $now);
				break;
        }
             
        return $record->get($recolumns);
    }

}
