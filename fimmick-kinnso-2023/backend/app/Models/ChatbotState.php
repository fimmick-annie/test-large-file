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

//========================================================================================
class ChatbotState extends Model  {

	//  Inform Model it is soft delete instead of real delete
	use SoftDeletes;

	//----------------------------------------------------------------------------------------
	//  The attributes that are mass assignable.
	protected $fillable = [
		"mobile",
		"email",
		"unique_id",
		"channel",
		"branch",
		"chatbot_data",
	];

	//----------------------------------------------------------------------------------------
	public static function getState($mobile)  {
		$query = self::where("mobile", $mobile);
		$record = $query->first();
		return $record;
	}

//     static public function addOrNewIfNecessary($mobile_number, $service_provider, $state = null)
//     {
//         try {
//             DB::beginTransaction();
//             $rows = self::getChatbotStatesByMobileNumberAndServiceProvider($mobile_number, $service_provider);
//             if (count($rows) > 0) {
//                 foreach ($rows as $row) {
//                     if ($state !== null) {
//                         self::where("mobile_number", "=", $mobile_number)
//                             ->where("service_provider", "=", $service_provider)
//                             ->update(['states' => ($row->states . ">>>" . $state)]);
//                     }
//                 }
//             } else {
//                 $record = new ChatbotState();
//                 $record->mobile_number = $mobile_number;
//                 $record->service_provider = $service_provider;
//                 $record->states = ($state === null ? '' : $state);
//                 $record->save();
//             }
//             DB::commit();
//         } catch (Exception $e) {
//             DB::rollBack();
//         }
//
//         $rows = self::getChatbotStatesByMobileNumberAndServiceProvider($mobile_number, $service_provider);
//         return $rows[0];
//     }
//
//     public static function getChatbotStatesByMobileNumberAndServiceProvider($mobile_number, $service_provider)
//     {
//         $objects = self::where("mobile_number", "=", $mobile_number)
//             ->where("service_provider", "=", $service_provider)
//             ->get();
//         return $objects;
//     }
//
//     public static function getLatestState($mobile_number, $service_provider)
//     {
//         $object = self::addOrNewIfNecessary($mobile_number, $service_provider);
//         $state_array = explode('>>>', $object->states);
//         return end($state_array);
//     }
//
//     public static function pushState($mobile_number, $service_provider, $state = null)
//     {
//         self::addOrNewIfNecessary($mobile_number, $service_provider, $state);
//     }
//
//     public static function popState($mobile_number, $service_provider){
//
//     }
}
