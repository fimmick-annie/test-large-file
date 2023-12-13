<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

use App\Models\ChannelReceiptSample;

class ChannelReceiptSampleSeeder extends Seeder
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

        ChannelReceiptSample::create([
            'start_at' => $oneMonthBefore,
            'end_at' => null ,
            'channel' => "Channel One - Happy Shop",
            'receipt_sample_url' => storage_path('foso/receipt-sample/receipt01.png'),
        ]);


        ChannelReceiptSample::create([
            'start_at' => $oneMonthBefore,
            'end_at' => $oneYearLater ,
            'channel' => "Channel Two - OK shop",
            'receipt_sample_url' => storage_path('foso/receipt-sample/receipt02.png'),
        ]);


        ChannelReceiptSample::create([
            'start_at' => $oneMonthLater,
            'end_at' => null ,
            'channel' => "Channel Three - Forever Fd",
            'receipt_sample_url' => storage_path('foso/receipt-sample/receipt03.png'),
        ]);

        ChannelReceiptSample::create([
            'start_at' => $oneYearBefore,
            'end_at' => null ,
            'channel' => "Channel Four - Missy",
            'receipt_sample_url' => storage_path('foso/receipt-sample/receipt04.png'),
        ]);

        ChannelReceiptSample::create([
            'start_at' => null,
            'end_at' => $oneYearLater,
            'channel' => "Channel Five - Hello Kitty",
            'receipt_sample_url' =>  storage_path('foso/receipt-sample/receipt05.jpg'),
        ]);

        ChannelReceiptSample::create([
            'start_at' => null,
            'end_at' => null,
            'channel' => "Channel Six - Never sleep shop",
            'receipt_sample_url' =>  "https://upload.wikimedia.org/wikipedia/commons/thumb/0/0b/ReceiptSwiss.jpg/200px-ReceiptSwiss.jpg",
        ]);

    }
}
