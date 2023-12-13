<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

use App\Models\Redemption;
use App\Models\RedemptionCode;

use Illuminate\Support\Str;

class RedemptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $50優惠券
        $quota = 10;
        $redemption = Redemption::create([
            'start_at' => '2022-01-01 00:00:00',
            'end_at' => '2999-12-31 23:59:59',
            'code_type' => 'barcode',
            'thumbnail_filename' => 'redemption_1.png',
            'title' => json_decode('{"zh-HK": "$50優惠券"}', true),
            'subtitle' => json_decode('{"zh-HK": "(價值$50)"}', true),
            'redemption_path' => '93VNuAAbBD0Pd2Au', 
            'quota' => $quota,
            'required_points' => 100,
            'details' => json_decode('{"zh-HK": "<div style=\"background:#eeeeee;border:1px solid #cccccc;padding:5px 10px;\"><strong>使用細則：</strong></div>\r\n\r\n<pre>\r\n1. 憑此優惠券於2022年5月1日至10月31日在專門店，購買任何正價產品滿$1000即減$50\r\n2. 優惠只適用於指定專門店\r\n3. 此優惠券不設找贖、不可更改、轉讓、轉售、兌換現金。\r\n4. 如有任何不法或濫用，保留法律追究權利。\r\n5. 本公司保留一切修改、添加或剔除活動條款及細則之權利，並無須先行通知。\r\n6. 如有任何爭議，本公司保留最終決定權。\r\n</pre>\r\n\r\n<p>&nbsp;</p>"}', true),
            'void_details' => json_decode('{"zh-HK": "<div style=\"background:#eeeeee;border:1px solid #cccccc;padding:5px 10px;\"><strong>使用條款：</strong></div>\r\n\r\n<ul>\r\n\t<li><small><tt><big>請於付款前於收銀處以流動裝置出示此現⾦券。恕不接受列印本。</big></tt></small></li>\r\n\t<li><small><tt><big>每人每次只限使用1張優惠券。不可與其他優惠同時使用。</big></tt></small></li>\r\n\t<li><small><tt><big>如欲辦理退貨，將根據各店鋪之換貨程序處理，不得退回現金或現金券。</big></tt></small></li>\r\n</ul>"}', true),
        ]);
        if ( $redemption )  {
            $redemptionID = $redemption->id;
            for( $i = 1; $i <= $quota; $i++ )  {
                $code = Str::random(10);
                RedemptionCode::create([
                    'redemption_id' => $redemptionID,
                    'code' => $code,
                ]);
            }
        }

        // $100優惠券
        $quota = 10;
        $redemption = Redemption::create([
            'start_at' => '2022-01-01 00:00:00',
            'end_at' => '2999-12-31 23:59:59',
            'code_type' => 'qrcode',
            'thumbnail_filename' => 'redemption_2.png',
            'title' => json_decode('{"zh-HK": "$100優惠券"}', true),
            'subtitle' => json_decode('{"zh-HK": "(價值$100)"}', true),
            'redemption_path' => '13VNuPjbBE8Pd1Km', // 
            'quota' => $quota,
            'required_points' => 200,
            'details' => json_decode('{"zh-HK": "<p><strong>使用方法：</strong></p>\r\n\r\n<hr />\r\n<ul>\r\n\t<li><strong>憑此優惠券可於本店購物享有八五折優惠(以一單收據計算)。</strong></li>\r\n\t<li><strong>此優惠券不能兌換現金。影印本無效。</strong></li>\r\n\t<li><strong>此優惠券不適用於購買節日禮品、禮物籃及禮券，或與其他優惠同時使用。</strong></li>\r\n\t<li><strong>此優惠券逾期無效，恕不補發。&nbsp;</strong></li>\r\n\t<li><strong>如遺失此優惠券將不獲補發。</strong></li>\r\n</ul>"}', true),
            'void_details' =>json_decode( '{"zh-HK": "<p><strong>獎賞細則：</strong></p>\r\n\r\n<hr />\r\n<ul>\r\n\t<li><strong>商品不可退款，不得用於轉售或其他商業用途。</strong></li>\r\n\t<li><strong>食品和禮劵不設更換。</strong></li>\r\n</ul>"}', true),
        ]);
        if ( $redemption )  {
            $redemptionID = $redemption->id;
            for( $i = 1; $i <= $quota; $i++ )  {
                $code = Str::random(10);
                RedemptionCode::create([
                    'redemption_id' => $redemptionID,
                    'code' => $code,
                ]);
            }
        }

        // $150優惠券
        $quota = 10;
        $redemption = Redemption::create([
            'start_at' => '2022-01-01 00:00:00',
            'end_at' => '2999-12-31 23:59:59',
            'code_type' => 'promocode',
            'thumbnail_filename' => 'redemption_3.png',
            'title' => json_decode('{"zh-HK": "$150優惠券"}', true),
            'subtitle' => json_decode('{"zh-HK": "(價值$150)"}', true),
            'redemption_path' => '13VNuAAbBE8Pd1Km', // 
            'quota' => $quota,
            'required_points' => 300,
            'details' => json_decode('{"zh-HK": "<p><ins>一般條款</ins></p>\r\n\r\n<ul>\r\n\t<li>請在使用本網頁之前仔細閱讀本條款及細則。閣下使用電子商店或其中任何部分，即表示同意閣下已閱讀本條款及細則，並且接受和同意受本條款及細則所約束。</li>\r\n\t<li>我們保留權利可更改貨品的價格而不作另行通知。我們可根據存貨供應，全權決定是否接受所有優專使用。</li>\r\n\t<li>我們會盡努力確保所列載的貨品提供存貨供應。如任何貨品因缺貨而未能提供予顧客，我們有權提供相同種類及價格的貨品以供替代。</li>\r\n\t<li>所有訂單須視乎相關貨品的供應情況再作最後確認。</li>\r\n</ul>"}', true),
            'void_details' => json_decode('{"zh-HK": "<p>使用條文：</p>\r\n\r\n<ul>\r\n\t<li>我們提供的促銷代碼或優惠券只適用於透過我們網站一次性的使用,&nbsp;並受條款及細則所約束。</li>\r\n\t<li>促銷代碼或優惠券既不退款,&nbsp;也不能兌換現金。任何剩餘未使用的金額將被作廢。</li>\r\n</ul>"}', true),
        ]); 
        if ( $redemption )  {
            $redemptionID = $redemption->id;
            for( $i = 1; $i <= $quota; $i++ )  {
                $code = Str::random(10);
                RedemptionCode::create([
                    'redemption_id' => $redemptionID,
                    'code' => $code,
                ]);
            }
        }
    }
}
