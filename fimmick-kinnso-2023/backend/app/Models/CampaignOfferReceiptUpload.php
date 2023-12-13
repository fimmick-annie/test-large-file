<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\CampaignOffer;

use OwenIt\Auditing\Contracts\Auditable;

class CampaignOfferReceiptUpload extends Model implements Auditable
{
    //  Inform Model it is soft delete instead of real delete
	use SoftDeletes;

	use \OwenIt\Auditing\Auditable;

	//----------------------------------------------------------------------------------------
	//  The attributes that are mass assignable.
	protected $guarded = ['id'];

	public function campaignOffer()
    {
        return $this->belongsTo(CampaignOffer::class, 'offer_id', 'id');
    }

	public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }

	public function channelReceiptSample()
    {
        return $this->belongsTo(ChannelReceiptSample::class, 'merchant_caption_id', 'id');
    }

	public static function getListByID($id, $columns=['*'])  {

		 $records = self::where('member_id', $id)
		 	->where("receipt_path", "!=" ,"NA")
		 	->with('campaignOffer:id,offer_title')
		 	->orderBy('created_at', 'desc')
		 	->get($columns);

		return $records;
	}
	public static function getRecordbyID($id, $columns=['*'])  {

		$record = self::where('id', $id)
			->where("receipt_path", "!=" ,"NA")
			->with('campaignOffer:id,offer_title')
			->first($columns);

	   return $record;
   }

	//--------------For FOSO -------------------------------------------------------------------
	public static function getList($fromDate = null, $toDate = null)  {

		$query = self::query()->with('member:id,mobile')->with('campaignOffer:id,offer_title');

		if ($fromDate != null)  {
			$query->where("created_at", ">=", $fromDate . " 00:00:00");
		}
		if ($toDate != null)  {
			$query->where("created_at", "<=", $toDate . " 23:59:59");
		}

		$dataArray = $query->orderBy("id", "desc")->get();
		return $dataArray;
	}

	//--------------For FOSO -------------------------------------------------------------------
	public static function getReceiptDetail($id = null)  {

		$record = self::where('id', $id)
				->with('member:id,mobile')
				->with('campaignOffer:id,offer_title')
				->with('channelReceiptSample:id,channel')
				->first();

		return $record;
	}


}
