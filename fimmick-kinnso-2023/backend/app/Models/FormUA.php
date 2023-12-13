<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020.  All rights reserved.
//----------------------------------------------------------------------------------------

namespace App\Models;

//----------------------------------------------------------------------------------------
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

// use OwenIt\Auditing\Contracts\Auditable;


//========================================================================================
class FormUA extends Model  {

	//  Inform Model it is soft delete instead of real delete
	use SoftDeletes;

	protected $table = 'form_ua';

	// use \OwenIt\Auditing\Auditable;

	//----------------------------------------------------------------------------------------
	//  The attributes that are mass assignable.
	protected $guarded = ['id'];


	public static function getCSVPath($fromDate=null, $toDate=null){

		// NO symbol in name
		$from = preg_replace('/[^0-9]/', '', $fromDate);
		$to = preg_replace('/[^0-9]/', '', $toDate);

		$filename = 'uafimoney_download_'.$from.'_'.$to.'_'.time().'.csv';
		$subfolder = "app/public/foso/2022_uaf_imoney_csv/";
        $folder = storage_path($subfolder);

        //  Create CSV file
        $filePath = $folder.$filename;
        $handle = fopen($filePath, 'w');
		fputs($handle, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));

        $headerArray = array(
            "id","created_at", "updated_at", "name", "mobile", "ua_account",
        );
        fputcsv($handle, $headerArray);
        
		$query = self::query();
        $dataArray = $query->where('created_at','>=', $fromDate." 00:00:00")
			->where('created_at','<=', $toDate." 23:59:59")
			->withTrashed() //<--Will del after confirming alter the Database 
			->get();
			
        foreach ($dataArray as $a)  {
            $rowArray = array(
                $a->id, $a->created_at, $a->updated_at, 
                $a->name, $a->mobile, $a->ua_account, 
            );
            fputcsv($handle, $rowArray);
        }

        fclose($handle);

		$downloadLink = asset('/storage/foso/2022_uaf_imoney_csv/'.$filename);

		return $downloadLink;
	}

}
