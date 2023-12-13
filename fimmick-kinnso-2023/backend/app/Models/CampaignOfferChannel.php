<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use OwenIt\Auditing\Contracts\Auditable;

use App\Models\ChannelReceiptSample;

class CampaignOfferChannel extends Model implements Auditable
{
    //  Inform Model it is soft delete instead of real delete
	use SoftDeletes;

	use \OwenIt\Auditing\Auditable;

	//----------------------------------------------------------------------------------------
	//  The attributes that are mass assignable.
	protected $guarded = ['id'];

    protected $table = 'campaign_offer_channels';

    public static function checkOfferListWithChannel($list=null){

        if (empty($list)){return null;}
        $result = array();
        $now = date("Y-m-d H:i:s");

        foreach ($list as $f){
            $validSample = self::where('offer_id', $f)
                ->pluck('sample_id_involved');
            
            if (empty($validSample)){continue;}

            $result[] = [ $f => $validSample ];
        }
        return $result;
    }


    public static function checkValidChannel($offerid=null){

        if (is_null($offerid)){return false;}

        $now = date("Y-m-d H:i:s");

        $result = self::where('offer_id', $offerid)->first();
     
        if (is_null($result)){ return null;}
        if (!is_null($result->start_at) && $result->start_at > $now){ return null;}
        if (!is_null($result->end_at) && $result->end_at < $now){ return null;}
        
        return $result;
    }

    public static function getSampleURL($list=null){

        if (empty($list)){return null;}
        $result = array();
        $now = date("Y-m-d H:i:s");

        foreach ($list as $f){
            $validSample = self::where('offer_id', $f)
                ->pluck('sample_id_involved');
            
            if (empty($validSample)){continue;}

            $result[] = [ $f => $validSample ];
        }
        return $result;
    }

    public static function getOfferChannel($id=null, $dateTime=null){

        $record = self::where('offer_id', $id)
                    ->where(function ($query) use ($dateTime){
                        $query->where('end_at', ">=", $dateTime)
                        ->orWhereNull('end_at');
                    })
                    ->where(function ($query) use ($dateTime){
                        $query->where('start_at', "<=", $dateTime)
                        ->orWhereNull('start_at');
                    })
                    ->first();
        return $record;

    } 
    public static function getOfferTitleByChannelID($id=null){

        $record = self::where('offer_id', $id)
                    ->where(function ($query) use ($dateTime){
                        $query->where('end_at', ">=", $dateTime)
                        ->orWhereNull('end_at');
                    })
                    ->where(function ($query) use ($dateTime){
                        $query->where('start_at', "<=", $dateTime)
                        ->orWhereNull('start_at');
                    })
                    ->first();
        return $record;

    } 

    public static function getChannelListByOfferID($id=null){

        $record = self::where('offer_id', $id)->first();

        $result = ChannelReceiptSample::getChannelListForOffer($record->sample_id_involved);

        return $result;
    } 

}
