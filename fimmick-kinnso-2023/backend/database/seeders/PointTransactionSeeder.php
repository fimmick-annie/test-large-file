<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

use App\Models\PointTransaction;

class PointTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $monthNow = date('m');
        $yearNow = date('Y');

        if ($monthNow <= 6){
            $lastPeriod = ($yearNow-1)."-12-31 23:59:59";
            $last1Period = ($yearNow-1)."-06-30 23:59:59";
            $last2Period = ($yearNow-2)."-12-31 23:59:59";
            $period1String = $yearNow."-06-30 23:59:59";
            $period2String = $yearNow."-12-31 23:59:59";
        }else{
            $last2Period = ($yearNow-1)."-06-30 23:59:59";
            $last1Period = ($yearNow-1)."-12-31 23:59:59";
            $lastPeriod = $yearNow."-06-30 23:59:59";
            $period1String = $yearNow."-12-31 23:59:59";
            $period2String = ($yearNow+1)."-06-30 23:59:59";
        }

        $now = date("Y-m-d H:i:s");

        //-----Add point
        //case 0: no expiry
        PointTransaction::create([
            'member_id' => '1',
            'delta_points' => 1,
            'valid_at' => "2021-01-01 00:00:00",
            'expiry_at' =>  date("Y-m-d 23:59:59", strtotime("+5 years")),
            // 'valid_at' => null,
            // 'expiry_at' =>  null,
            'transaction_type' => 'Offer hunting', 
            'description' => json_encode([
                "zh-HK" => "蜜探報料成功",
                "en" => "Hunting success",
            ])
        ]);

        //case 1: expire on last period
        PointTransaction::create([
            'member_id' => '1',
            'delta_points' => 2,
            'valid_at' => date("Y-m-d", strtotime("$last2Period -10 days")),
            'expiry_at' => $last1Period,
            'transaction_type' => 'Offer hunting', 
            'description' => json_encode([
                "zh-HK" => "蜜探報料成功",
                "en" => "Hunting success",
            ])
        ]);

        //case 2: valid before last period, expire on period 1
        PointTransaction::create([
            'member_id' => '1',
            'delta_points' => 4,
            'valid_at' => $last2Period,
            'expiry_at' => $lastPeriod,
            'transaction_type' => 'Offer hunting', 
            'description' => json_encode([
                "zh-HK" => "蜜探報料成功",
                "en" => "Hunting success",
            ])
        ]);

        //case 3: valid before last period, expire on period 1
        PointTransaction::create([
            'member_id' => '1',
            'delta_points' => 8,
            'valid_at' => date("Y-m-d", strtotime("$last1Period -10 days")),
            'expiry_at' => $lastPeriod,
            'transaction_type' => 'Offer hunting', 
            'description' => json_encode([
                "zh-HK" => "蜜探報料成功",
                "en" => "Hunting success",
            ])
        ]);

        //case 4: valid on last period, expire on period 2
        PointTransaction::create([
            'member_id' => '1',
            'delta_points' => 16,
            'valid_at' => $last1Period,
            'expiry_at' => $period1String,
            'transaction_type' => 'Offer hunting', 
            'description' => json_encode([
                "zh-HK" => "蜜探報料成功",
                "en" => "Hunting success",
            ])
        ]);

        //case 5: valid after last period & before now, expire after period 2
        PointTransaction::create([
            'member_id' => '1',
            'delta_points' => 32,
            'valid_at' => date("Y-m-d", strtotime("$lastPeriod -10 days")),
            'expiry_at' => $period1String,
            'transaction_type' => 'Referral', 
            'description' => json_encode([
                "zh-HK" => "成功推介獎勵",
                "en" => "Referral success",
            ])
        ]);

        //case 6: valid after now , expire after period 1
        PointTransaction::create([
            'member_id' => '1',
            'delta_points' => 64,
            'valid_at' => $lastPeriod,
            'expiry_at' => $period2String,
            'transaction_type' => 'Referral', 
            'description' => json_encode([
                "zh-HK" => "成功推介獎勵",
                "en" => "Referral success",
            ])
        ]);

        //case 7: valid after period 1 , expire after period 2
        PointTransaction::create([
            'member_id' => '1',
            'delta_points' => 128,
            'valid_at' => date("Y-m-d 00:00:00", strtotime("$now -2 days")),
            'expiry_at' => $period2String,
            'transaction_type' => 'Referral', 
            'description' => json_encode([
                "zh-HK" => "成功推介獎勵",
                "en" => "Referral success",
            ])
        ]);

        //case 8: cross last period  
        PointTransaction::create([
            'member_id' => '1',
            'delta_points' => 256,
            'valid_at' => date("Y-m-d 00:00:00", strtotime("$now +2 days")),
            'expiry_at' => $period2String,
            'transaction_type' => 'Offer hunting', 
            'description' => json_encode([
                "zh-HK" => "蜜探報料成功",
                "en" => "Hunting success",
            ])
        ]);

        //case 9: cross today 
        PointTransaction::create([
            'member_id' => '1',
            'delta_points' => 512,
            'valid_at' => $period1String,
            'expiry_at' => date("Y-m-d 23:59:59", strtotime("$period1String +1 year")),
            'transaction_type' => 'Offer hunting', 
            'description' => json_encode([
                "zh-HK" => "蜜探報料成功",
                "en" => "Hunting success",
            ])
        ]);

        //case 10: cross period 1
        PointTransaction::create([
            'member_id' => '1',
            'delta_points' => 1024,
            'valid_at' => date("Y-m-d 00:00:00", strtotime("$last2Period -3 days")),
            'expiry_at' => date("Y-m-d 23:59:59", strtotime("$last2Period +3 days")),
            'transaction_type' => 'Referral', 
            'description' => json_encode([
                "zh-HK" => "成功推介獎勵",
                "en" => "Referral success",
            ])
        ]);
        
        //case 11: cross period 2
        PointTransaction::create([
            'member_id' => '1',
            'delta_points' => 2048,
            'valid_at' => date("Y-m-d 00:00:00", strtotime("$last2Period +10 days")),
            'expiry_at' => date("Y-m-d 23:59:59", strtotime("$last2Period +17 days")),
            'transaction_type' => 'Referral', 
            'description' => json_encode([
                "zh-HK" => "成功推介獎勵",
                "en" => "Referral success",
            ])
        ]);

        //case 12: extra : before last period 
        PointTransaction::create([
            'member_id' => '1',
            'delta_points' => 4096,
            'valid_at' => date("Y-m-d 00:00:00", strtotime("$last1Period -3 days")),
            'expiry_at' => date("Y-m-d 23:59:59", strtotime("$last1Period +3 days")),
            'transaction_type' => 'Referral', 
            'description' => json_encode([
                "zh-HK" => "成功推介獎勵",
                "en" => "Referral success",
            ])
        ]);

        //case 13: extra : last period - now
        PointTransaction::create([
            'member_id' => '1',
            'delta_points' => 8192,
            'valid_at' => date("Y-m-d 00:00:00", strtotime("$last1Period +40 days")),
            'expiry_at' => date("Y-m-d 00:00:00", strtotime("$last1Period +47 days")),
            'transaction_type' => 'Referral', 
            'description' => json_encode([
                "zh-HK" => "成功推介獎勵",
                "en" => "Referral success",
            ])
        ]);

        //case 14: extra : now - period 1
        PointTransaction::create([
            'member_id' => '1',
            'delta_points' => 16384,
            'valid_at' => date("Y-m-d 00:00:00", strtotime("$lastPeriod -3 days")),
            'expiry_at' => date("Y-m-d 23:59:59", strtotime("$lastPeriod +3 days")),
            'transaction_type' => 'Referral', 
            'description' => json_encode([
                "zh-HK" => "成功推介獎勵",
                "en" => "Referral success",
            ])
        ]);

        //case 15: extra : period 1 - period 2
        PointTransaction::create([
            'member_id' => '1',
            'delta_points' => 32768,
            'valid_at' => date("Y-m-d 00:00:00", strtotime("$now -1 days")),
            'expiry_at' => date("Y-m-d 23:59:59", strtotime("$now +1 days")),
            'transaction_type' => 'Referral', 
            'description' => json_encode([
                "zh-HK" => "成功推介獎勵",
                "en" => "Referral success",
            ])
        ]);

        //case 16: extra : after period 2
        PointTransaction::create([
            'member_id' => '1',
            'delta_points' => 65536,
            'valid_at' => date("Y-m-d 00:00:00", strtotime("$now +10 days")),
            'expiry_at' => date("Y-m-d 23:59:59", strtotime("$now +14 days")),
            'transaction_type' => 'Referral', 
            'description' => json_encode([
                "zh-HK" => "成功推介獎勵",
                "en" => "Referral success",
            ])
        ]);

         //case 17: extra :yesterday expiry
         PointTransaction::create([
            'member_id' => '1',
            'delta_points' => 131072,
            'valid_at' => date("Y-m-d 00:00:00", strtotime("$period1String -3 days")),
            'expiry_at' => date("Y-m-d 23:59:59", strtotime("$period1String +3 days")),
            'transaction_type' => 'Referral', 
            'description' => json_encode([
                "zh-HK" => "成功推介獎勵",
                "en" => "Referral success",
            ])
        ]);

        //case 18
        PointTransaction::create([
            'member_id' => '1',
            'delta_points' => 262144,
            'valid_at' => date("Y-m-d 00:00:00", strtotime("$period1String +10 days")),
            'expiry_at' => date("Y-m-d 23:59:59", strtotime("$period1String +14 days")),
            'transaction_type' => 'Referral', 
            'description' => json_encode([
                "zh-HK" => "成功推介獎勵",
                "en" => "Referral success",
            ])
        ]);

        // ----- deduct point ----------------------------------

        // $today = date("Y-m-d");
        // $dayHalfYearBefore =  date("Y-m-d", strtotime("-180 days"));
        // $oneMonthBeforeEnd = substr($oneMonthBefore, 0, 10)." 23:59:59";

        // $oneMonthBeforePeriod1End = substr($oneMonthBeforePeriod1, 0 , 10)." 23:59:59";
        // $period1StringStart = substr($period1String,0,10)." 00:00:00";
        // $oneMonthBeforePeriod2End = substr($oneMonthBeforePeriod2, 0, 10)." 23:59:59";
        // $period2StringStart = substr($period2String, 0, 10)." 00:00:00";
        // $oneMonthAfterperiod2Start = substr($oneMonthAfterperiod2, 0, 10)." 00:00:00";

        $last1Periodstart = substr($last1Period, 0 , 10)." 00:00:00";
        $last1Periodend = substr($last1Period, 0 , 10)." 23:59:59";

        //case A: half year before today
        PointTransaction::create([
            'member_id' => '1',
            'delta_points' => -1000,
            'valid_at' => date("Y-m-d 00:00:00", strtotime("$last2Period +2 days")),
            'expiry_at' => date("Y-m-d 23:59:59", strtotime("$last2Period +2 days")),
            'transaction_type' => 'Redemption', 
            'description' => json_encode([
                "zh-HK" => "兌換獎賞",
                "en" => "Redemption success",
            ])
        ]);

        //case B: on last period 
        PointTransaction::create([
            'member_id' => '1',
            'delta_points' => -2000,
            'valid_at' => date("Y-m-d 00:00:00", strtotime("$last2Period +13 days")),
            'expiry_at' => date("Y-m-d 23:59:59", strtotime("$last2Period +13 days")),
            'transaction_type' => 'Redemption', 
            'description' => json_encode([
                "zh-HK" => "兌換獎賞",
                "en" => "Redemption success",
            ])
        ]);

        
        //case C: last period - now (less point) 
        PointTransaction::create([
            'member_id' => '1',
            'delta_points' => -4090,
            'valid_at' => $last1Periodstart,
            'expiry_at' => $last1Periodend,
            'transaction_type' => 'Redemption', 
            'description' => json_encode([
                "zh-HK" => "兌換獎賞",
                "en" => "Redemption success",
            ])
        ]);

         //case D: last period - now (more point) 
        PointTransaction::create([
            'member_id' => '1',
            'delta_points' => -8190,
            'valid_at' => date("Y-m-d 00:00:00", strtotime("$last1Period +42 days")),
            'expiry_at' => date("Y-m-d 23:59:59", strtotime("$last1Period +42 days")),
            'transaction_type' => 'Redemption', 
            'description' => json_encode([
                "zh-HK" => "兌換獎賞",
                "en" => "Redemption success",
            ])
        ]);

        //case E: within today
        PointTransaction::create([
            'member_id' => '1',
            'delta_points' => -60,  // Kill 2,3,4,5 = 60 pt
            'valid_at' => date("Y-m-d 00:00:00", strtotime("$lastPeriod -7 days")),
            'expiry_at' => date("Y-m-d 23:59:59", strtotime("$lastPeriod -7 days")),
            'transaction_type' => 'Redemption', 
            'description' => json_encode([
                "zh-HK" => "兌換獎賞",
                "en" => "Redemption success",
                ])
        ]);

         //case F: now - period 1
         PointTransaction::create([
            'member_id' => '1',
            'delta_points' => -384,
            'valid_at' => date("Y-m-d 00:00:00", strtotime("$lastPeriod -1 day")),
            'expiry_at' => date("Y-m-d 23:59:59", strtotime("$lastPeriod -1 day")),
            'transaction_type' => 'Redemption', 
            'description' => json_encode([
                "zh-HK" => "兌換獎賞",
                "en" => "Redemption success",
                ])
        ]);

        //case G: 
         PointTransaction::create([
            'member_id' => '1',
            'delta_points' => -15000,
            'valid_at' => date("Y-m-d 00:00:00", strtotime("$lastPeriod +1 days")),
            'expiry_at' => date("Y-m-d 23:59:59", strtotime("$lastPeriod +1 days")),
            'transaction_type' => 'Redemption', 
            'description' => json_encode([
                "zh-HK" => "兌換獎賞",
                "en" => "Redemption success",
                ])
        ]);

        // //case H: period 1 - period 2
        //  PointTransaction::create([
        //     'member_id' => '1',
        //     'delta_points' => -1,
        //     'valid_at' => $oneMonthBeforePeriod2,
        //     'expiry_at' => $oneMonthBeforePeriod2End,
        //     'transaction_type' => 'Redemption', 
        //     'description' => json_encode([
        //         "zh-HK" => "兌換獎賞",
        //         "en" => "Redemption success",
        //     ])
        // ]);

        // //case I: on period 2
        // PointTransaction::create([
        //     'member_id' => '1',
        //     'delta_points' => -1,
        //     'valid_at' => $period2StringStart,
        //     'expiry_at' => $period2String,
        //     'transaction_type' => 'Redemption', 
        //     'description' => json_encode([
        //         "zh-HK" => "兌換獎賞",
        //         "en" => "Redemption success",
        //     ])
        // ]);


        // //case J: after period 2
        // PointTransaction::create([
        //     'member_id' => '1',
        //     'delta_points' => -1,
        //     'valid_at' => $oneMonthAfterperiod2Start,
        //     'expiry_at' => $oneMonthAfterperiod2,
        //     'transaction_type' => 'Redemption', 
        //     'description' => json_encode([
        //         "zh-HK" => "兌換獎賞",
        //         "en" => "Redemption success",
        //     ])
        // ]);

        // //------ Pretend: pass cron job
        // //before last period (cancel case 12)
        // // PointTransaction::create([
        // //     'member_id' => '1',
        // //     'delta_points' => -4096,
        // //     'valid_at' => date("Y-m-d 00:00:00", strtotime("$lastPeriod -10 days")),
        // //     'expiry_at' => date("Y-m-d 23:59:59", strtotime("$lastPeriod -10 days")),
        // //     'transaction_type' => 'Cron Job', 
        // //     'description' => json_encode([
        // //         "zh-HK" => "模擬消分",
        // //         "en" => "Cancel unused expired point",
        // //     ])
        // // ]);

        // // // between last period to now  (cancel case 13)
        // // PointTransaction::create([
        // //     'member_id' => '1',
        // //     'delta_points' => -8181, // 8192-1[case C]-10[case D] = 8181
        // //     'valid_at' => date("Y-m-d 00:00:00", strtotime("$lastPeriod +17 days")),
        // //     'expiry_at' => date("Y-m-d 23:59:59", strtotime("$lastPeriod +17 days")),
        // //     'transaction_type' => 'Cron Job', 
        // //     'description' => json_encode([
        // //         "zh-HK" => "模擬消分",
        // //         "en" => "Cancel unused expired point",
        // //     ])
        // // ]);

        // // // between last period to now
        // // // cross last period (cancel case 8)
        // // PointTransaction::create([
        // //     'member_id' => '1',
        // //     'delta_points' => -256,
        // //     'valid_at' => date("Y-m-d 00:00:00", strtotime("$lastPeriod +3 days")),
        // //     'expiry_at' => date("Y-m-d 23:59:59", strtotime("$lastPeriod +3 days")),
        // //     'transaction_type' => 'Cron Job', 
        // //     'description' => json_encode([
        // //         "zh-HK" => "模擬消分",
        // //         "en" => "Cancel unused expired point",
        // //     ])
        // // ]);

    }
}
