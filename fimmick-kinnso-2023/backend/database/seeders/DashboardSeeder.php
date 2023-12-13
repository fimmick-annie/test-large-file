<?php
//----------------------------------------------------------------------------------------
//  Kinnso Pre-membership WhatsApp Offer Site
//----------------------------------------------------------------------------------------
//  Written by Johnson SHAN
//  Platform: Laravel 5.8 + PHP 7.1 + MySQL 8.0
//  Copyrights Fimmick Limited, 2020.  All rights reserved.
//----------------------------------------------------------------------------------------
namespace Database\Seeders;
use Illuminate\Database\Seeder;

use App\Models\DashboardGenericChart;
use App\Models\DashboardTimeChart;

//========================================================================================
class DashboardSeeder extends Seeder
{

    //----------------------------------------------------------------------------------------
    //  Run the database seeds.
    public function run()
    {

        //----------------------------------------------------------------------------------------
        //  Create default records for testing

        for ($x = 0; $x <= 10; $x++) {
            DashboardGenericChart::create([
                'offer_id' => 1,
                'record_date' => 'Dec ' . (10 + $x) . ' 2019',
                'number_of_users' => rand(0, 200),
                'number_of_coupons_issued' => rand(0, 200),
                'number_of_coupons_used' => rand(0, 200),
            ]);
        }

        for ($x = 0; $x <= 10; $x++) {
            DashboardGenericChart::create([
                'offer_id' => 2,
                'record_date' => 'Nov ' . (10 + $x) . ' 2018',
                'number_of_users' => rand(100, 300),
                'number_of_coupons_issued' => rand(100, 300),
                'number_of_coupons_used' => rand(100, 300),
            ]);
        }
        for ($x = 0; $x <= 10; $x++) {
            DashboardGenericChart::create([
                'offer_id' => 3,
                'record_date' => 'Nov ' . (10 + $x) . ' 2018',
                'number_of_users' => rand(100, 300),
                'number_of_coupons_issued' => rand(100, 300),
                'number_of_coupons_used' => rand(100, 300),
            ]);
        }
        for ($x = 0; $x <= 10; $x++) {
            DashboardGenericChart::create([
                'offer_id' => 4,
                'record_date' => 'Nov ' . (10 + $x) . ' 2018',
                'number_of_users' => rand(100, 300),
                'number_of_coupons_issued' => rand(100, 300),
                'number_of_coupons_used' => rand(100, 300),
            ]);
        }

        DashboardTimeChart::create([
            'offer_id' => 1,
            'time_slot_1' => rand(0, 50),
            'time_slot_2' => rand(0, 50),
            'time_slot_3' => rand(0, 50),
            'time_slot_4' => rand(0, 50),
            'time_slot_5' => rand(0, 50),
            'time_slot_6' => rand(0, 50),
            'time_slot_7' => rand(0, 50),
            'time_slot_8' => rand(0, 50),
            'time_slot_9' => rand(0, 50),
            'time_slot_10' => rand(0, 50),
            'time_slot_11' => rand(0, 50),
            'time_slot_12' => rand(0, 50),
            'time_slot_13' => rand(0, 50),
            'time_slot_14' => rand(0, 50),
            'time_slot_15' => rand(0, 50),
            'time_slot_16' => rand(0, 50),
            'time_slot_17' => rand(0, 50),
            'time_slot_18' => rand(0, 50),
            'time_slot_19' => rand(0, 50),
            'time_slot_20' => rand(0, 50),
            'time_slot_21' => rand(0, 50),
            'time_slot_22' => rand(0, 50),
            'time_slot_23' => rand(0, 50),
            'time_slot_24' => rand(0, 50),
        ]);
        DashboardTimeChart::create([
            'offer_id' => 2,
            'time_slot_1' => rand(0, 50),
            'time_slot_2' => rand(0, 50),
            'time_slot_3' => rand(0, 50),
            'time_slot_4' => rand(0, 50),
            'time_slot_5' => rand(0, 50),
            'time_slot_6' => rand(0, 50),
            'time_slot_7' => rand(0, 50),
            'time_slot_8' => rand(0, 50),
            'time_slot_9' => rand(0, 50),
            'time_slot_10' => rand(0, 50),
            'time_slot_11' => rand(0, 50),
            'time_slot_12' => rand(0, 50),
            'time_slot_13' => rand(0, 50),
            'time_slot_14' => rand(0, 50),
            'time_slot_15' => rand(0, 50),
            'time_slot_16' => rand(0, 50),
            'time_slot_17' => rand(0, 50),
            'time_slot_18' => rand(0, 50),
            'time_slot_19' => rand(0, 50),
            'time_slot_20' => rand(0, 50),
            'time_slot_21' => rand(0, 50),
            'time_slot_22' => rand(0, 50),
            'time_slot_23' => rand(0, 50),
            'time_slot_24' => rand(0, 50),
        ]);
        DashboardTimeChart::create([
            'offer_id' => 3,
            'time_slot_1' => rand(0, 50),
            'time_slot_2' => rand(0, 50),
            'time_slot_3' => rand(0, 50),
            'time_slot_4' => rand(0, 50),
            'time_slot_5' => rand(0, 50),
            'time_slot_6' => rand(0, 50),
            'time_slot_7' => rand(0, 50),
            'time_slot_8' => rand(0, 50),
            'time_slot_9' => rand(0, 50),
            'time_slot_10' => rand(0, 50),
            'time_slot_11' => rand(0, 50),
            'time_slot_12' => rand(0, 50),
            'time_slot_13' => rand(0, 50),
            'time_slot_14' => rand(0, 50),
            'time_slot_15' => rand(0, 50),
            'time_slot_16' => rand(0, 50),
            'time_slot_17' => rand(0, 50),
            'time_slot_18' => rand(0, 50),
            'time_slot_19' => rand(0, 50),
            'time_slot_20' => rand(0, 50),
            'time_slot_21' => rand(0, 50),
            'time_slot_22' => rand(0, 50),
            'time_slot_23' => rand(0, 50),
            'time_slot_24' => rand(0, 50),
        ]);
        DashboardTimeChart::create([
            'offer_id' => 4,
            'time_slot_1' => rand(0, 50),
            'time_slot_2' => rand(0, 50),
            'time_slot_3' => rand(0, 50),
            'time_slot_4' => rand(0, 50),
            'time_slot_5' => rand(0, 50),
            'time_slot_6' => rand(0, 50),
            'time_slot_7' => rand(0, 50),
            'time_slot_8' => rand(0, 50),
            'time_slot_9' => rand(0, 50),
            'time_slot_10' => rand(0, 50),
            'time_slot_11' => rand(0, 50),
            'time_slot_12' => rand(0, 50),
            'time_slot_13' => rand(0, 50),
            'time_slot_14' => rand(0, 50),
            'time_slot_15' => rand(0, 50),
            'time_slot_16' => rand(0, 50),
            'time_slot_17' => rand(0, 50),
            'time_slot_18' => rand(0, 50),
            'time_slot_19' => rand(0, 50),
            'time_slot_20' => rand(0, 50),
            'time_slot_21' => rand(0, 50),
            'time_slot_22' => rand(0, 50),
            'time_slot_23' => rand(0, 50),
            'time_slot_24' => rand(0, 50),
        ]);
    }
}
