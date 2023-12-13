<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\RedemptionCode;
use App\Models\RedemptionHistory;

use DB;

class Redemption extends Model
{
    use SoftDeletes;

    //----------------------------------------------------------------------------------------
	//  The attributes that are not mass assignable.
	protected $guarded = ['id'];

    protected $casts = [
        'title' => 'array',
        'subtitle' => 'array',
        'details' => 'array',
        'void_details' => 'array',
    ];

    public function redemptionCodes()
    {
        return $this->hasMany(RedemptionCode::class, 'redemption_id', 'id');
    }

    public function redemptionHistories()
    {
        return $this->hasMany(RedemptionHistory::class, 'redemption_id', 'id');
    }

    //----------------------------------------------------------------------------------------
    public static function getAvaiableGifts($columns=['*'])  {
        $currentDateTime = date('Y-m-d H:i:s');
        $records = self::where('start_at', '<=', $currentDateTime)
                        ->where('end_at', '>=', $currentDateTime)
                        ->orderBy('ordering', 'desc')
                        ->get($columns);
        return $records;
    }

    //----------------------------------------------------------------------------------------
    public static function getAvaiableGiftById($id, $columns=['*'])  {
        $currentDateTime = date('Y-m-d H:i:s');
        $record = self::where('id', $id)
                    ->where('start_at', '<=', $currentDateTime)
                    ->where('end_at', '>=', $currentDateTime)
                    ->first($columns);
        return $record;
    }

    //----------------------------------------------------------------------------------------
    public static function addQuotaIssued($id, $quantity)  {
        $affectedRows = self::where('id', $id)
                            ->whereRaw('quota_issued + ? <= quota', [$quantity])
                            ->update([
                                'quota_issued' => DB::raw('quota_issued + '.$quantity),
                            ]);
        return $affectedRows;
    }

    //--------------For FOSO -------------------------------------------------------------------
    public static function getList($fromDate = null, $toDate = null)  {
		$query = self::query();
		if ($fromDate != null)  {
			$query->where("end_at", ">=", $fromDate . " 00:00:00");
		}
		if ($toDate != null)  {
			$query->where("start_at", "<=", $toDate . " 23:59:59");
		}

		$dataArray = $query->orderBy("id", "desc")->get();
		return $dataArray;
	}

    public static function getRedemption($id = null)  {
		$query = self::where("id", $id);
		$dataArray = $query->first();
		return $dataArray;
	}

    public static function getRedemptionByRedemptionPath($redemption_path = null)  {
		$query = self::where("redemption_path", $redemption_path);
		$dataArray = $query->first();
		return $dataArray;
	}

    public static function getAvaiableQuatoById($id)  {

        $record = RedemptionCode::where('id', $id)
                    ->where('redemption_id', '=', $id)
                    ->where('RedemptionHistory', '=', null)
                    ->count();

        return $record;
    }

    public static function getIssuedQuatoById($id)  {

        $record = RedemptionCode::where('id', $id)
                    ->where('redemption_id', '=', $id)
                    ->where('RedemptionHistory', '!=', null)
                    ->count();

        return $record;
    }

}
