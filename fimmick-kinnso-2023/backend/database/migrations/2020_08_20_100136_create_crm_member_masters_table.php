<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020.  All rights reserved.
//----------------------------------------------------------------------------------------

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

//========================================================================================
//  This table is used by CRM Data Team
class CreateCrmMemberMastersTable extends Migration  {

	//----------------------------------------------------------------------------------------
	//  Run the migrations.
	public function up()  {
		Schema::create('crm_member_masters', function (Blueprint $table) {

			//  Default columns
			$table->bigIncrements('id');
			$table->timestamps();
			$table->softDeletes();

			//----------------------------------------------------------------------------------------
			//  Columns for this class
			$table->string('created_by', 40);
			$table->string('updated_by', 40)->nullable();
			$table->string('deleted_by', 40)->nullable();

			$table->string('mobile_region', 24);
			$table->integer('mobile_num');
			$table->string('record_status', 16);
			$table->string('master_id', 48);
			$table->timestamp('merge_at')->nullable();
			$table->timestamp('register_at')->nullable();
			$table->timestamp('verify_at')->nullable();
			$table->integer('birth_year')->default(0);
			$table->integer('birth_month')->default(0);
			$table->integer('birth_day')->default(0);
			$table->string('email', 32)->nullable();
			$table->string('is_optout_email', 8)->default("-");
			$table->string('is_optout_sms', 8)->default("-");
			$table->string('is_optout_phone', 8)->default("-");
			$table->string('is_optout_whatsapp', 8)->default("-");
			$table->string('is_optout_DM', 8)->default("-");
			$table->string('is_agree_tnc', 8)->default("-");
			$table->string('referral_code', 16)->nullable();
			$table->string('referrer_code', 16)->nullable();
			$table->string('referrer_id', 16)->nullable();
			$table->string('member_type', 16)->nullable();
			$table->integer('point_balance')->default(0);
			$table->string('remarks', 64)->nullable();
			$table->string('fb_id', 24)->nullable();
			$table->string('name', 24)->nullable();
			$table->string('gender', 8)->nullable();
			$table->string('age_group', 8)->nullable();
			$table->string('language', 8)->nullable();
			$table->string('lead_source', 32)->nullable();
			$table->integer('source_brand')->default(0);
			$table->integer('source_campaign')->default(0);
			$table->string('mobile_status', 16)->nullable();
			$table->string('email_status', 16)->nullable();

			$table->unique(["mobile_region", "mobile_num"]);
		});
	}

	//----------------------------------------------------------------------------------------
	//  Reverse the migrations.
	public function down()  {
		Schema::dropIfExists('crm_member_masters');
	}
}
