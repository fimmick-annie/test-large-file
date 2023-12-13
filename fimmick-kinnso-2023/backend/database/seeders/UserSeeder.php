<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Pacess HO
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020.  All rights reserved.
//----------------------------------------------------------------------------------------
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use App\Models\FosoUser;
use App\Models\AppUser;

//========================================================================================
class UserSeeder extends Seeder  {

	//----------------------------------------------------------------------------------------
	//  Run the database seeds.
	public function run()  {

		//----------------------------------------------------------------------------------------
		//  Permission come first
		$permissionAdminPermissionRead = Permission::create(['name' => 'admin.permission.read']);
		$permissionAdminPermissionWrite = Permission::create(['name' => 'admin.permission.write']);

		$permissionAdminRoleRead = Permission::create(['name' => 'admin.role.read']);
		$permissionAdminRoleWrite = Permission::create(['name' => 'admin.role.write']);

		$permissionAdminUserRead = Permission::create(['name' => 'admin.user.read']);
		$permissionAdminUserWrite = Permission::create(['name' => 'admin.user.write']);

		$permissionAdminSpecialFeatures = Permission::create(['name' => 'admin.special.features']);

		$permissionCampaignsOfferRead = Permission::create(['name' => 'campaigns.offer.read']);
		$permissionCampaignsOfferWrite = Permission::create(['name' => 'campaigns.offer.write']);

		$permissionCampaignsCouponReportsRead = Permission::create(['name' => 'campaigns.coupon-reports.read']);

		$permissionCampaignsWhatsappInboundRead = Permission::create(['name' => 'campaigns.whatsapp-inbound.read']);
		$permissionCampaignsWhatsappInboundDownload = Permission::create(['name' => 'campaigns.whatsapp-inbound.download']);

		$permissionCampaignsManageToolAccess = Permission::create(['name' => 'campaigns.manage-tool.access']);

		$permissionCampaignsWhatsappQueueRead = Permission::create(['name' => 'campaigns.whatsapp-queue.read']);
		$permissionCampaignsWhatsappQueueDownload = Permission::create(['name' => 'campaigns.whatsapp-queue.download']);

		$permissionMarketingListAccess = Permission::create(['name' => 'marketing.list.access']);

		$permissionMemberListAccess = Permission::create(['name' => 'member.list.access']);

		$permissionDashboardAccess = Permission::create(['name' => 'campaigns.dashboard.access']);

		$permissionOfferListdAccess = Permission::create(['name' => 'campaigns.offer-list.access']);
		$permissionCampaignLanding = Permission::create(['name' => 'campaigns.landing.access']);

		$permissionBannerListAccess = Permission::create(['name' => 'banner.list.access']); //kay 2022.08.15
		$permissionRedemptionListAccess = Permission::create(['name' => 'redemption.list.access']); //kay 2022.08.15
		$permissionReportingListAccess = Permission::create(['name' => 'reporting.list.access']); //kay 2022.10.14

		//----------------------------------------------------------------------------------------
		//  Role come second
		$role = Role::create(['name' => 'Super-Administrator']);
		$role->givePermissionTo($permissionAdminPermissionRead);
		$role->givePermissionTo($permissionAdminPermissionWrite);
		$role->givePermissionTo($permissionAdminRoleRead);
		$role->givePermissionTo($permissionAdminRoleWrite);
		$role->givePermissionTo($permissionAdminUserRead);
		$role->givePermissionTo($permissionAdminUserWrite);
		$role->givePermissionTo($permissionAdminSpecialFeatures);
		$role->givePermissionTo($permissionCampaignsOfferRead);
		$role->givePermissionTo($permissionCampaignsOfferWrite);
		$role->givePermissionTo($permissionCampaignsCouponReportsRead);
		$role->givePermissionTo($permissionCampaignsWhatsappInboundRead);
		$role->givePermissionTo($permissionCampaignsWhatsappInboundDownload);
		$role->givePermissionTo($permissionCampaignsManageToolAccess);
		$role->givePermissionTo($permissionCampaignsWhatsappQueueRead);
		$role->givePermissionTo($permissionCampaignsWhatsappQueueDownload);
		$role->givePermissionTo($permissionMarketingListAccess);
		$role->givePermissionTo($permissionMemberListAccess);
		$role->givePermissionTo($permissionDashboardAccess);
		$role->givePermissionTo($permissionOfferListdAccess);
		$role->givePermissionTo($permissionCampaignLanding);
		$role->givePermissionTo($permissionBannerListAccess); //kay 2022.08.15
		$role->givePermissionTo($permissionRedemptionListAccess);  //kay 2022.08.15
		$role->givePermissionTo($permissionReportingListAccess);  //kay 2022.10.14

		$role = Role::create(['name' => 'Administrator']);
		$role->givePermissionTo($permissionAdminPermissionRead);
		$role->givePermissionTo($permissionAdminPermissionWrite);
		$role->givePermissionTo($permissionAdminRoleRead);
		$role->givePermissionTo($permissionAdminRoleWrite);
		$role->givePermissionTo($permissionAdminUserRead);
		$role->givePermissionTo($permissionAdminUserWrite);
		$role->givePermissionTo($permissionCampaignsOfferRead);
		$role->givePermissionTo($permissionCampaignsOfferWrite);
		$role->givePermissionTo($permissionCampaignsCouponReportsRead);
		$role->givePermissionTo($permissionCampaignsWhatsappInboundRead);
		$role->givePermissionTo($permissionCampaignsWhatsappInboundDownload);
		$role->givePermissionTo($permissionCampaignsManageToolAccess);
		$role->givePermissionTo($permissionCampaignsWhatsappQueueRead);
		$role->givePermissionTo($permissionCampaignsWhatsappQueueDownload);
		$role->givePermissionTo($permissionMarketingListAccess);
		$role->givePermissionTo($permissionMemberListAccess);
		$role->givePermissionTo($permissionDashboardAccess);
		$role->givePermissionTo($permissionOfferListdAccess);
		$role->givePermissionTo($permissionCampaignLanding);
		$role->givePermissionTo($permissionBannerListAccess); //kay 2022.08.15
		$role->givePermissionTo($permissionRedemptionListAccess);  //kay 2022.08.15
		$role->givePermissionTo($permissionReportingListAccess);  //kay 2022.10.14

		$role = Role::create(['name' => 'Editorial']);
		$role->givePermissionTo($permissionCampaignsOfferRead);
		$role->givePermissionTo($permissionCampaignsOfferWrite);
		$role->givePermissionTo($permissionCampaignsCouponReportsRead);
		$role->givePermissionTo($permissionDashboardAccess);
		$role->givePermissionTo($permissionOfferListdAccess);
		$role->givePermissionTo($permissionBannerListAccess); //kay 2022.08.15
		$role->givePermissionTo($permissionRedemptionListAccess);  //kay 2022.08.15
		$role->givePermissionTo($permissionReportingListAccess);  //kay 2022.10.14

		$role = Role::create(['name' => 'Data Team']);
		$role->givePermissionTo($permissionCampaignsOfferRead);
		$role->givePermissionTo($permissionCampaignsCouponReportsRead);
		$role->givePermissionTo($permissionDashboardAccess);

		$role = Role::create(['name' => 'Client']);
		$role->givePermissionTo($permissionCampaignsManageToolAccess);
		$role->givePermissionTo($permissionDashboardAccess);

		//----------------------------------------------------------------------------------------
		//  User come last
		$user = FosoUser::create([
			'name' => 'Pacess HO',
			'email' => 'pacessho@fimmick.com',
			'password' => 'Q1w2e3r4t5y6u7i8'
		]);
		$user->assignRole('Super-Administrator');

		$user = FosoUser::create([
			'name' => 'Kinnso Admin',
			'email' => 'kinnso-admin@fimmick.com',
			'password' => 'Fimmick25152218'
		]);
		$user->assignRole('Administrator');

		$user = FosoUser::create([
			'name' => 'Fimmick Admin',
			'email' => 'admin@fimmick.com',
			'password' => 'fimmick2021',
		]);
		$user->assignRole('Administrator');

		$user = FosoUser::create([
			'name' => 'Kinnso Client',
			'email' => 'kinnso-client@fimmick.com',
			'password' => 'Hell0World'
		]);
		$user->assignRole('Client');

		//  Other colleagues
		$user = FosoUser::create([
			'name' => 'Johnson SHAN',
			'email' => 'johnsonshan@fimmick.com',
			'password' => 'Fimmick2021',
		]);
		$user->assignRole('Administrator');

		$user = FosoUser::create([
			'name' => 'Jena CHAN',
			'email' => 'jenachan@fimmick.com',
			'password' => 'Fimmick2021',
		]);
		$user->assignRole('Administrator');

		$user = FosoUser::create([
			'name' => 'Verna IP',
			'email' => 'vernaip@fimmick.com',
			'password' => '12345six',
		]);
		$user->assignRole('Administrator');

		$appUser = AppUser::create([
			'name' => 'Johnson SHAN',
			'email' => 'johnsonshan@fimmick.com',
			'password' => 'fimmick2021',
			'roles' => 'normal_user'
		]);
	}
}
