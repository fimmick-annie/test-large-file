<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020.  All rights reserved.
//----------------------------------------------------------------------------------------

//  Priority (Higher value, higher priority)
//  50 = Reminder, fulfillment
//  100 = Normal

namespace App\Models;

//----------------------------------------------------------------------------------------
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

//========================================================================================
class CampaignWhatsappMessageQueueArchive extends CampaignWhatsappMessageQueue  {

	//----------------------------------------------------------------------------------------
	//  The attributes that are mass assignable, including id.
	protected $guarded = [];

}
