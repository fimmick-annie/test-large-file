<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020.  All rights reserved.
//----------------------------------------------------------------------------------------

//  Node type:
//  > 100 = Message only node
//  > 200 = Question node
//  > 300 = Issue coupon node
//  > 400 = Date comparison node

namespace App\Models;

//----------------------------------------------------------------------------------------
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

//========================================================================================
class CampaignCustomerJourneyArchive extends CampaignCustomerJourney  {

	//----------------------------------------------------------------------------------------
	//  The attributes that are mass assignable, including id.
	protected $guarded = [];

}
