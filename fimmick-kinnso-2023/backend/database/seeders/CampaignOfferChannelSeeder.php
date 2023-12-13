<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

use App\Models\CampaignOfferChannel;

class CampaignOfferChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $oneMonthBefore = date("Y-m-d 00:00:00", strtotime("-30 days"));
        $oneMonthLater = date("Y-m-d 23:59:59", strtotime("+30 days"));
        $oneYearBefore = date("Y-m-d 00:00:00", strtotime("-1 year"));
        $oneYearLater = date("Y-m-d 23:59:59", strtotime("+1 year"));

        CampaignOfferChannel::create([
            'start_at' => $oneMonthBefore,
            'end_at' => null ,
            'offer_id' => 1,
            'sample_id_involved' => "1,3,4",
            'receipt_approval_point' =>  10 ,
        ]);

        CampaignOfferChannel::create([
            'start_at' => $oneMonthBefore,
            'end_at' => null ,
            'offer_id' => 2,
            'sample_id_involved' => "1,3,4",
            'receipt_approval_point' =>  10 ,
        ]);

        CampaignOfferChannel::create([
            'start_at' => $oneMonthLater,
            'end_at' => null ,
            'offer_id' => 3,
            'sample_id_involved' => "2",
            'receipt_approval_point' =>  10 ,
        ]);

        CampaignOfferChannel::create([
            'start_at' => $oneMonthBefore,
            'end_at' => null ,
            'offer_id' => 4,
            'sample_id_involved' => "2,4,5,6",
            'receipt_approval_point' =>  10 ,
        ]);

        CampaignOfferChannel::create([
            'start_at' => null,
            'end_at' => null ,
            'offer_id' => 5,
            'sample_id_involved' => "1",
            'receipt_approval_point' =>  10 ,
        ]);

    }
}
