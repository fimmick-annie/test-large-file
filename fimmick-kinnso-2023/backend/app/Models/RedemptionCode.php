<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Redemption;

class RedemptionCode extends Model
{
    use SoftDeletes;

    //----------------------------------------------------------------------------------------
	//  The attributes that are not mass assignable.
	protected $guarded = ['id'];

    public function redemption()
    {
        return $this->belongsTo(Redemption::class, 'redemption_id', 'id');
    }

    public function redemptionHistory()
    {
        return $this->belongsTo(Redemption::class, 'redemption_id', 'id');
    }

    //----------------------------------------------------------------------------------------
    public static function getAvailableCodeCount($redemptionID)  {

        $count = self::where('redemption_id', $redemptionID)
                    ->whereNull('redemption_history_id')
                    ->count();
                    
        return $count;
    }

    public static function getIssuedCodeCount($redemptionID)  {

        $count = self::where('redemption_id', $redemptionID)
                    ->whereNotNull('redemption_history_id')
                    ->count();

        return $count;
    }

    public static function getCodeCount($redemptionID)  {

        $count = self::where('redemption_id', $redemptionID)
                    // ->whereNull('redemption_history_id')
                    ->count();
                    
        return $count;
    }

    //----------------------------------------------------------------------------------------
	public static function assignCode($redemptionID, $redemptionHistoryID)  {

		$affectedRows = self::where('redemption_id', $redemptionID)
                            ->whereNull('redemption_history_id')
                            ->limit(1)
                            ->update([
                                'redemption_history_id' => $redemptionHistoryID,
                            ]);
        return $affectedRows;
	}

}
