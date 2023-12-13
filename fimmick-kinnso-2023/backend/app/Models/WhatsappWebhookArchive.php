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
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

//========================================================================================
class WhatsappWebhookArchive extends WhatsappWebhook  {

	//----------------------------------------------------------------------------------------
	//  The attributes that are mass assignable, including id.
	protected $guarded = [];

}
