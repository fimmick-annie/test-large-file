<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\CampaignOfferChannel;

use OwenIt\Auditing\Contracts\Auditable;

class ChannelReceiptSample extends Model implements Auditable
{
    use SoftDeletes;

	use \OwenIt\Auditing\Auditable;
	//----------------------------------------------------------------------------------------
	//  The attributes that are mass assignable.
	protected $guarded = ['id'];

    public static function getChannelAndUrlbyIDs ($offerid=null){

        if (is_null($offerid)){return NULL;}
        
        $result = array();

        $channel = CampaignOfferChannel::where('offer_id', $offerid)->first();
        // Further develop -- if (count($channel)>1){ }
        $str = "";
        if (!is_null($channel)){$str = $channel->sample_id_involved;}
        
        if (!empty($str)){
            $sampleList = explode(",", $str );
            $item = array();
            foreach ($sampleList as $sampleid){
                // $item['sample_id']= $sample;
                $receiptSample = ChannelReceiptSample::where('id', $sampleid)->first();
                $result[] = array ( 
                        "sample_id" => $sampleid,
                        "channel" => $receiptSample->channel,
                        "start" => $receiptSample->start_at,
                        "url" => $receiptSample->receipt_sample_url,
                );
            }  
        }
        // dd($result);
        return $result;
    }


    //--------------For FOSO -------------------------------------------------------------------
	public static function getList($fromDate = null, $toDate = null)  {

        $dataArray = self::where(function ($query) use ($fromDate){
                            $query->where('end_at', ">=", $fromDate ." 00:00:00")
                            ->orWhereNull('end_at');
                        })
                        ->where(function ($query) use ($toDate){
                            $query->where('start_at', "<=", $toDate . " 23:59:59")
                            ->orWhereNull('start_at');
                        })
                        ->orderBy("id", "desc")
                        ->get();

		return $dataArray;
	}

    public static function getChannelSample ($id= null)  {

        $record = self::where('id', $id)
				->first();

		return $record;

    }

    public static function getSelectedChannelByID ($offerid=null){

        if (is_null($offerid)){return NULL;}
        
        $result = array();

        $channel = CampaignOfferChannel::where('offer_id', $offerid)->first();

        if (!$channel){return $result;}
        
        $str = "";
        if (!is_null($channel)){$str = $channel->sample_id_involved;}
        
        if (!empty($str)){
            $sampleList = explode(",", $str );
            $item = array();
            foreach ($sampleList as $sampleid){
                // $item['sample_id']= $sample;
                if (!ChannelReceiptSample::checkSampleValid($sampleid,date("Y-m-d H:i:s"))){continue;}

                $receiptSample = ChannelReceiptSample::where('id', $sampleid)->first();
                $result[] = array ( 
                        "id" => $sampleid,
                        "title"=>$receiptSample->channel,
                );
            }  

        }
        return $result;
    }

    public static function getNotSelectedChannelByID ($offerid=null){

        if (is_null($offerid)){return NULL;}
        
        $result = array();

        $allChannel = ChannelReceiptSample::getValidChannelID(date("Y-m-d H:i:s"));
        $channel = CampaignOfferChannel::where('offer_id', $offerid)->first();
        // Further develop -- if (count($channel)>1){ }
        $str = "";
        if (!is_null($channel)){$str = $channel->sample_id_involved;}

        if (strlen($str)>0){
            $sampleList = explode(",", $str );
        }else{
            $sampleList=[];
        }

        foreach ($allChannel as $sampleid){
                
            if (in_array($sampleid, $sampleList)){continue;}

            $receiptSample = ChannelReceiptSample::where('id', $sampleid)->first();
            $result[] = array ( 
                    "id" => $sampleid,
                    "title"=>$receiptSample->channel,
            );
        } 

        return $result;
    }

    public static function getValidChannelID($now=null)  {

        $idArray = self::where(function ($query) use ($now){
                            $query->where('end_at', ">=", $now )
                            ->orWhereNull('end_at');
                        })
                        ->where(function ($query) use ($now){
                            $query->where('start_at', "<=", $now)
                            ->orWhereNull('start_at');
                        })
                        ->pluck('id')->toArray();
        
		return $idArray;
	}

    public static function checkSampleValid($id=null, $now=null)  {

        $sample = self::where('id', $id)
                        ->where(function ($query) use ($now){
                            $query->where('end_at', ">=", $now )
                            ->orWhereNull('end_at');
                        })
                        ->where(function ($query) use ($now){
                            $query->where('start_at', "<=", $now)
                            ->orWhereNull('start_at');
                        })
                        ->first();
        if (!$sample){return false;}
		return true;
	}

    public static function getChannelListForOffer($str=null){

        if (empty($str)){return NULL;}
        
        $result = array();
        $sampleList = explode(",", $str );
    
        foreach ($sampleList as $id){
                
            $receiptSample = ChannelReceiptSample::where('id', $id)->first();
            $result[] = array ( 
                    "id" => $id,
                    "title"=>$receiptSample->channel,
            );
        } 
        return $result;
    }     
}
