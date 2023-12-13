<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('website/common/head')
        <style>
			body {
				background-color: #ffffff;
                
			}

			.header {
				position: fixed;
				top: 0;
				left: 0;
				width: 100%;
				z-index: 1500;
				display: -webkit-box;
				display: -ms-flexbox;
				display: flex;
				-ms-flex-wrap: wrap;
				flex-wrap: wrap;
				-webkit-box-align: center;
				-ms-flex-align: center;
				align-items: center;
				-webkit-box-pack: justify;
				-ms-flex-pack: justify;
				justify-content: space-between;
				padding: 35px 30px;
				background-color: #3d444e;
				-webkit-transition: background-color .3s ease;
				-o-transition: background-color .3s ease;
				transition: background-color .3s ease;

				-webkit-box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
				box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
			}

			.header__landing {
				background-color: #ffffff;
			}

			.logo {
				position: absolute;
				left: 50%;
				top: 50%;
				width: 100%;
				max-width: 132px;
				display: block;
				-webkit-transform: translate(-50%, -50%);
				-ms-transform: translate(-50%, -50%);
				transform: translate(-50%, -50%);
			}

			.heading {
				font-weight: bold;
				margin-top: 30px;
				margin-bottom: 10px;
			}

			@media (max-width: 1365px) {
				.logo__desktop {
					display: none;
				}
			}

			@media (min-width: 1366px) {
				.logo__mobile {
					display: none;
				}
			}

            .wrapper {
                max-width: 1200px;
            }
            .section-title {
                color: #F37621;
                font: normal normal 800 22px/30px Noto Sans;
                letter-spacing: 1.1px;
                display: flex;
                justify-content: center;
            }
            .section-title::after {
                content: attr(data-text);
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
            .section-title .icon {
                margin-right: 8px;
                display: inline-block;
            }
            .no-result {
                padding: 8px 0;
                font-size: 'Helvetica Neue';
                letter-spacing: 3.73px;
                text-align: center;
                color: #57524F;
            }
            .no-result .sleeping-bear {
                width: 180px;
                margin: 24px auto;
            }
            .no-result .sleeping-bear::after {
                content: ' ';
                padding-top: 75.52%;
                background-image: url("{{ asset('website/sleeping_bear.png') }}");
                background-repeat: no-repeat;
                background-position: center center;
                background-size: contain;
                display: block;
            }
            .popup {
                position: fixed;
                width: 100vw;
                height: 100%;
                background: rgba(0, 0, 0, 0.3);
                z-index: 2;
                display: none;
            }
            a.button-1 {
                width: 100%; max-width: 375px;
                height: 50px;
                border: 1px solid #FBCB30;
                border-radius: 25px;
                font: normal normal 800 20px/50px Noto Sans;
                text-align: center;
                letter-spacing: 2px;
                color: #F37621;
                text-decoration: none;
                display: inline-block;
            }
            a.button-1:hover {
                color: #F37621;
            }
            a.button-1.disabled {
                border: 0;
                background: #DDDCDC;
                color: #707070;
                cursor: default;
            }
		</style>

                <!--help to ensure the left menu in right size-->
		<link rel="stylesheet" href="./offers/common/offer_listing.css?v=1" />
        
    </head>
    <body>
        @include('website/common/tracking_body')
        <!--  Segment  -->
		<script>
			!function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","reset","group","track","ready","alias","debug","page","once","off","on","addSourceMiddleware","addIntegrationMiddleware","setAnonymousId","addDestinationMiddleware"];analytics.factory=function(e){return function(){var t=Array.prototype.slice.call(arguments);t.unshift(e);analytics.push(t);return analytics}};for(var e=0;e<analytics.methods.length;e++){var key=analytics.methods[e];analytics[key]=analytics.factory(key)}analytics.load=function(key,e){var t=document.createElement("script");t.type="text/javascript";t.async=!0;t.src="https://cdn.segment.com/analytics.js/v1/" + key + "/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(t,n);analytics._loadOptions=e};analytics._writeKey="W1yJ9TYonvWx2FlA6469eTyvX0xegGS3";analytics.SNIPPET_VERSION="4.13.2";
				analytics.load("{{ env('SEGMENT_ID') }}");
				analytics.page("redemption", {
					ip: "{{ $ipAddress }}",
					userAgent: "{{ $userAgent }}",
				});
			}}();
		</script>
		<!--  End Segment  -->
        <style>
            .popup.gift-detail {
				
			}
            .popup.gift-detail .popup-wrapper {
                position: absolute;
                left: 50%; top: 50%;
                transform: translate(-50%, -50%);
                max-width: 820px;
                width: 80%;
                height: 510px;
                margin-top: 50px;
                background: #FFF;
                border: 4px solid #F5CD47;
                border-radius: 64px;
            }
            .popup.gift-detail .popup-wrapper::before {
                content: ' ';
                position: absolute;
                left: 50px; top: -75px;
                width: 118.5px;
                height: 75.5px;
                background: url("{{ asset('website/filter_bear_v1.png') }}") no-repeat center center;
                background-size: contain;
                display: inline-block;
            }
            @media (max-width: 768px) {
                .popup.gift-detail .popup-wrapper {
                    left: 0; top: initial; bottom: 0;
                    transform: translate(0, 0);
                    max-width: initial;
                    width: 100%;
                    height: 75%;
                    padding-top: 26px;
                    border: 0;
                    border-radius: 64px 64px 0 0;
                }
                .popup.gift-detail .popup-wrapper::before {
                    top: -70px;
                }
            }
            .popup.gift-detail .popup-wrapper .close-button {
                position: absolute;
                right: -17px; top: -17px;
                width: 35px; height: 35px;
                background: url("{{ asset('website/redemption/redemption_centre_after_login_cross_button.png') }}") no-repeat center center;
                background-size: contain;
            }
            @media (max-width: 768px) {
                .popup.gift-detail .popup-wrapper .close-button {
                    right: 39px; top: 12px;
                }
            }
            .popup.gift-detail .popup-wrapper .detail-wrapper {
                height: 100%;
                border-radius: 60px;
                display: flex;
                flex-direction: column;
                overflow: hidden;
            }
            @media (max-width: 768px) {
                .popup.gift-detail .popup-wrapper .detail-wrapper {
                    border-radius: 0;
                }
            }
            .popup.gift-detail .popup-wrapper .gift {
                margin: 36px auto 18px auto;
                max-width: 90%;
            }
            @media (max-width: 768px) {
				.popup.gift-detail .popup-wrapper .gift {
					margin: 18px auto 18px auto;
				}
			}
            .popup .popup-wrapper .gift .image {
                padding-right: 40px;
            }
            .popup .popup-wrapper .gift .detail {
                padding: 4px 0 4px 20px;
                flex: 1 1 0;
            }
            @media (max-width: 800px) {
                .popup .popup-wrapper .gift {
                    width: 90%;
                }
                .popup .popup-wrapper .gift .image {
                    padding-right: 20px;
                }
                .popup .popup-wrapper .gift .detail {
                    min-width: initial;
                }
            }
            .popup .popup-wrapper .gift .detail .info .title,
            .popup .popup-wrapper .gift .detail .info .subtitle {
                color: #707070;
                display: inline-block;
            }
            .popup .popup-wrapper .gift .detail .info .title {
                font: normal normal 800 18px/24px Noto Sans;
            }
            .popup .popup-wrapper .gift .detail .info .subtitle {
                font: normal normal 800 16px/20px Noto Sans;
            }
            .popup .popup-wrapper .gift .quota {
                font: normal normal 800 16px/40px Noto Sans;
            }
            .popup .popup-wrapper .gift .point .point-wrapper {
                height: 40px; padding: 0 30px;
                border-radius: 40px;
                font: normal normal 800 16px/40px Noto Sans;
            }
            @media (max-width: 560px) {
                .popup .popup-wrapper .gift .detail .info .title {
                    font: normal normal 800 4.6vw/1.5 Noto Sans;
                }
                .popup .popup-wrapper .gift .detail .info .subtitle {
                    font: normal normal 800 4vw/1.5 Noto Sans;
                }
                .popup .popup-wrapper .gift .quota {
                    font: normal normal 800 3.4vw/1.5 Noto Sans;
                }
                .popup .popup-wrapper .gift .point .point-wrapper {
                    height: 30px; padding: 0 16px;
                    border-radius: 30px;
                    font: normal normal 800 3.4vw/1.5 Noto Sans;
                }
            }
            .popup.gift-detail .popup-wrapper .content {
                padding: 20px;
                margin: 0 10px 10px 10px;
                overflow-y: scroll;
                scrollbar-width: thin;
                scrollbar-color: rgba(243, 118, 33, 0.7) #F8F8F8;
                flex: 1 1 0;
            }
            .popup.gift-detail .popup-wrapper .content::-webkit-scrollbar {
                width: 4px;
                height: 4px;
            }
            .popup.gift-detail .popup-wrapper .content::-webkit-scrollbar-track {
                box-shadow: inset 0 0 5px #EEE;
                border-radius: 10px;
            }
            .popup.gift-detail .popup-wrapper .content::-webkit-scrollbar-thumb {
                background: rgba(243, 118, 33, 0.7);
                border-radius: 10px;
            }
            .popup.gift-detail .popup-wrapper .button {
                height: 94px;
                background: #FCCC08;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            @media (max-width: 768px) {
				.popup.gift-detail .popup-wrapper .button {
					height: 74px;
				}
			}
            .popup.gift-detail .popup-wrapper .button a.next-button {
                width: 60%;
                text-align: center; 
                background: #FFF; color: #F37621;
                border-radius: 30px; text-decoration: auto;
                font: normal normal 800 18px/40px Noto Sans;
                display: inline-block;
            }
            .popup.gift-detail .popup-wrapper .button a::before {
                content: attr(data-text);
            }
            .popup.gift-detail .content {
                color: #707070;
                font-family: Noto Sans;
                line-height: 2;
            }
        </style>
        <div class="popup gift-detail">
            <div class="popup-wrapper">
                <a href="javascript:void(0)" class="close-button"></a>
                <div class="detail-wrapper">
                    
                </div>
            </div>
        </div>
        <!-- gift-detail-popup -->
	    <script id="gift-detail-popup-template" type="text/x-handlebars-template">
            <div class="gift">
                <div class="image" style="--background-image:url('{{ asset('redemptions') }}/@{{thumbnail_filename}}')"></div>
                <div class="detail">
                    <div class="info">
                        <div class="title">@{{title.zh-HK}}</div>
                        <div class="subtitle">@{{subtitle.zh-HK}}</div>
                    </div>
                    @{{#if quota}}
                    <div class="quota">限量@{{quota}}份</div>
                    @{{/if}}
                    <div class="point">
                        <div class="point-wrapper">@{{required_points}}</div>
                    </div>
                </div>
            </div>
            <div class="content">
                @{{{details.zh-HK}}}
            </div>
            <div class="button">
                @if( $memberID == 0 )
                <a href="{{ route('website.login.html') }}" class="next-button" data-text="即領獎賞"></a>
                @else
                <a href="javascript:void(0)" class="next-button" data-id="@{{id}}" data-text="即領獎賞"></a>
                @endif
            </div>
        </script>

        <style>
            .popup.gift-redeem {
                
            }
            .popup.gift-redeem .popup-wrapper {
                position: absolute;
                left: 50%; top: 50%;
                transform: translate(-50%, -50%);
                max-width: 820px;
                width: 80%;
                padding: 48px 28px;
                background: #FFF;
                border: 4px solid #F5CD47;
                border-radius: 64px;
            }
            @media (max-width: 768px) {
                .popup.gift-redeem .popup-wrapper {
                    left: 0; top: initial; bottom: 0;
                    transform: translate(0, 0);
                    max-width: initial;
                    width: 100%;
                    height: auto;
                    padding: 20px;
                    border: 0;
                    border-radius: 0;
                    border-top: 20px solid #FCCC08;
                }
            }
            .popup.gift-redeem .popup-wrapper .close-button {
                position: absolute;
                right: -17px; top: -17px;
                width: 35px; height: 35px;
                background: url("{{ asset('website/redemption/redemption_centre_after_login_cross_button.png') }}") no-repeat center center;
                background-size: contain;
            }
            @media (max-width: 768px) {
                .popup.gift-redeem .popup-wrapper .close-button {
                    right: 16px; top: -38px;
                }
            }
            .popup.gift-redeem .popup-wrapper .container {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                align-items: center;
            }
            .popup.gift-redeem .popup-wrapper .container > div {
                width: 100%;
                margin-bottom: 30px;
            }
            .popup.gift-redeem .popup-wrapper .container > div:last-child {
                margin-bottom: 0;
            }
            .popup.gift-redeem .gift {
                margin-bottom: 0;
            }
            .popup.gift-redeem .quantity-point-container {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }
            .popup.gift-redeem .quantity-outer-wrapper {
                width: 80%;
                margin-bottom: 16px;
                flex: 1 1 auto;
                display: flex;
                justify-content: space-evenly;
                align-items: center;
            }
            .popup.gift-redeem .quantity-wrapper {
                margin: 0 30px;
            }
            .popup.gift-redeem a.plus,
            .popup.gift-redeem a.minus {
                width: 35px; height: 35px;
                display: inline-block;
            }
            .popup.gift-redeem a.plus {
                background: url("{{ asset('website/redemption/_+_ button.png') }}") no-repeat center center;
                background-size: contain;
            }
            .popup.gift-redeem a.minus {
                background: url("{{ asset('website/redemption/_-_ botton.png') }}") no-repeat center center;
                background-size: contain;
            }
            .popup.gift-redeem span.quantity {
                font: normal normal 800 30px/50px Noto Sans;
            }
            .popup.gift-redeem span.slash {
                font: normal normal normal 24px/50px Noto Sans;
            }
            .popup.gift-redeem span.maximum {
                font: normal normal normal 16px/50px Noto Sans;
            }
            .popup.gift-redeem .point-balance .point-wrapper {
                min-width: initial;
                height: auto;
                padding: 0 10px;
                border: 0;
                border-radius: 3px;
                font: normal normal 800 18px/30px Noto Sans;
                background: #FFFAE6;
                color: #707070;
                display: inline-flex;
                justify-content: center;
                align-items: center;
            }
            .popup.gift-redeem .point-balance .point-wrapper::after {
                width: 20px; height: 20px;
            }
            .popup.gift-redeem .button-wrapper {
                display: flex;
                justify-content: center;
            }
        </style>
        <div class="popup gift-redeem">
            <div class="popup-wrapper">
                <a href="javascript:void(0)" class="close-button"></a>
                <div class="container">

                </div>
            </div>
        </div>
        <!-- gift-redeem-popup -->
	    <script id="gift-redeem-popup-template" type="text/x-handlebars-template">
            <div class="gift">
                <div class="image" style="--background-image:url('{{ asset('redemptions') }}/@{{thumbnail_filename}}')"></div>
                <div class="detail">
                    <div class="info">
                        <div class="title">@{{title.zh-HK}}</div>
                        <div class="subtitle">@{{subtitle.zh-HK}}</div>
                    </div>
                    @{{#if quota}}
                    <div class="quota">限量@{{quota}}份</div>
                    @{{/if}}
                    <div class="point">
                        <div class="point-wrapper">@{{required_points}}</div>
                    </div>
                </div>
            </div>
            <div class="quantity-point-container">
                <div class="quantity-outer-wrapper">
                    <a href="javascript:void(0)" class="minus"></a>
                    <div class="quantity-wrapper">
                        <span class="quantity">1</span>
                        <span class="slash">/</span>
                        <span class="maximum">@{{maximum_quantity}}</span>
                    </div>
                    <a href="javascript:void(0)" class="plus"></a>
                </div>
                <div class="point-balance">
                    積分結餘：
                    <div class="point-wrapper">{{ $pointBalance<0 ? 0:$pointBalance  }}</div>
                </div>
            </div>
            <div class="button-wrapper">
                <a href="javascript:void(0)" class="redeem-button button-1" data-id="@{{id}}" data-title="@{{title.zh-HK}}" data-subtitle="@{{subtitle.zh-HK}}" data-required-points="@{{required_points}}">兌換獎賞</a>
            </div>
        </script>

        <style>
            .popup.gift-redeem-success {
                
            }
            .popup.gift-redeem-success .popup-wrapper {
                position: absolute;
                left: 50%; top: 50%;
                transform: translate(-50%, -50%);
                max-width: 820px;
                width: 80%;
                padding: 48px 28px;
                background: #FFF;
                border: 4px solid #F5CD47;
                border-radius: 64px;
            }
            @media (max-width: 768px) {
                .popup.gift-redeem-success .popup-wrapper {
                    left: 0; top: initial; bottom: 0;
                    transform: translate(0, 0);
                    max-width: initial;
                    width: 100%;
                    height: auto;
                    padding: 20px;
                    border: 0;
                    border-radius: 0;
                    border-top: 20px solid #FCCC08;
                }
            }
            .popup.gift-redeem-success .popup-wrapper .close-button {
                position: absolute;
                right: -17px; top: -17px;
                width: 35px; height: 35px;
                background: url("{{ asset('website/redemption/redemption_centre_after_login_cross_button.png') }}") no-repeat center center;
                background-size: contain;
            }
            @media (max-width: 768px) {
                .popup.gift-redeem-success .popup-wrapper .close-button {
                    right: 16px; top: -38px;
                }
            }
            .popup.gift-redeem-success .popup-wrapper .container {
                height: 100%;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                align-items: center;
            }
            .popup.gift-redeem-success .popup-wrapper .container > div {
                width: 100%;
                margin-bottom: 30px;
            }
            .popup.gift-redeem-success .popup-wrapper .container > div:last-child {
                margin-bottom: 0;
            }
            .popup.gift-redeem-success .icon-wrapper {
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .popup.gift-redeem-success .icon-wrapper .icon {
                font: normal normal 800 20px/36px Noto Sans;
                color: #F37621;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }
            .popup.gift-redeem-success .icon-wrapper .icon::before {
                content: ' ';
                width: 66px; height: 65px;
                background: url("{{ asset('website/redemption/noun-reward-4136207ca.png') }}") no-repeat center center;
                background-size: contain;
                display: block;
            }
            .popup.gift-redeem-success .content-wrapper {
                font: normal normal normal 20px/36px Noto Sans;
                text-align: center;
                color: #707070;
            }
            .popup.gift-redeem-success .button-wrapper {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }
            .popup.gift-redeem-success a.back-button {
                width: 100%; max-width: 375px;
                height: 50px; margin-bottom: 20px;
                border: 1px solid #FBCB30;
                border-radius: 25px;
                font: normal normal 800 20px/50px Noto Sans;
                text-align: center;
                letter-spacing: 2px;
                color: #F37621;
                text-decoration: none;
                display: inline-block;
            }
            .popup.gift-redeem-success a.history-button {
                width: 100%; max-width: 375px;
                height: 50px;
                border: 1px solid #DDDCDC;
                border-radius: 25px;
                font: normal normal 800 20px/50px Noto Sans;
                text-align: center;
                letter-spacing: 2px;
                color: #707070;
                text-decoration: none;
                display: inline-block;
            }
        </style>
        <div class="popup gift-redeem-success">
            
        </div>
        <!-- gift-redeem-success -->
	    <script id="gift-redeem-success-popup-template" type="text/x-handlebars-template">
            <div class="popup-wrapper">
                <a href="{{ route('website.redemption.html') }}" class="close-button"></a>
                <div class="container">
                    <div class="icon-wrapper">
                        <div class="icon">成功換領</div>
                    </div>
                    <div class="content-wrapper">
                        你已成功以@{{points}}積分換取@{{title}} @{{subtitle}}﹐並已新增到「我的獎賞記錄」。
                    </div>
                    <div class="button-wrapper">
                        <a href="{{ route('website.redemption.html') }}" class="back-button">返回獎賞中心</a>
                        <a href="{{ route('website.myrewards.html') }}" class="history-button">查閱獎賞記錄</a>
                    </div>
                </div>
            </div>
        </script>

        <div class="wrapper">
            <div class="redemption">
                @include('campaigns/common/header')

                <style>
                    .content {
                        padding-top: 70px;
                    }
                </style>
                <div class="content">
                    <style>
                        .top {
                            padding: 32px 0 108px 0;
                            background: url("{{ asset('website/redemption/redemption_centre_before_login_bear.png') }}") no-repeat center bottom;
                            background-size: auto 108px; 
                        }
                        .section-title.reward-centre {
                            margin-bottom: 36px; 
                        }
                        .section-title.reward-centre .icon {
                            width: 29px;
                            background: url("{{ asset('website/redemption/redemption_centre_before_login_icon.png') }}") no-repeat center center;
                            background-size: contain;
                        }
                        .my-point {
                            font: normal normal normal 22px/30px Noto Sans;
                            letter-spacing: 1.1px;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                        }
                        .my-point::before {
                            content: attr(data-text);
                            margin-right: 22px;
                        }
                        .point-wrapper {
                            position: relative;
                            min-width: 132px; height: 39px;
                            padding: 0 18px;
                            border: 1px solid #FCD533;
                            border-radius: 6px;
                            letter-spacing: 2px;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                        }
                        .point-wrapper::after {
                            content: ' ';
                            width: 30px; height: 30px; margin-left: 12px;
                            background: url("{{ asset('website/redemption/p_icon-content.png') }}") no-repeat center center;
                            background-size: contain;
                            display: inline-block;
                        }
                        .login-button-wrapper {
                            padding: 0 30px;
                            display: flex;
                            justify-content: center;
                        }
                    </style>
                    <div class="top">
                        <div class="section-title reward-centre" data-text="獎賞中心">
                            <div class="icon"></div>
                        </div>
                        @if ($memberID != 0)
                        <div class="my-point" data-text="我的積分">
                            <div class="point-wrapper">
                                {{ $pointBalance<0 ? 0:$pointBalance }}
                            </div>
                        </div>
                        @else
                        <div class="login-button-wrapper">
                            <a href="{{ route('website.login.html') }}" class="login-button button-1">登入換獎賞</a>
                        </div>
                        @endif
                    </div>
                    <style>
                        .gift-list {
                            padding-top: 44px;
                            margin: 0 auto;
                        }
                        .section-title.hot-item {

                        }
                        .section-title.hot-item {
                            margin-bottom: 42px; 
                        }
                        .section-title.hot-item .icon {
                            width: 29px;
                            background: url("{{ asset('website/redemption/redemption_centre_before_login_icon.png') }}") no-repeat center center;
                            background-size: contain;
                        }
                        .gift-list .list {
                            display: flex;
                            flex-direction: column;
                            justify-content: center;
                            padding: 0 20px;
                        }
                        .gift-list .list .gift {
                            cursor: pointer;
                        }
                        .gift {
                            /* margin-bottom: 46px; */
                            margin-bottom: 30px;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                        }
                        @media (max-width: 600px) {
                            .gift {
                                min-width: initial;
                                width: 100%;
                            }
                        }
                        .gift .image {
                            width: 104px; 
                            height: 104px;
                            background-image: var(--background-image);
                            background-repeat: no-repeat;
                            background-size: contain;
                            background-position: left center;
                            padding-right: 20px;
                            border-right: 1px solid #F3884B;
                            box-sizing: initial;
                        }
                        .gift .detail {
                            min-width: 320px;
                            min-height: 104px;
                            padding: 14px 0 14px 20px;
                            letter-spacing: 1.1px;
                            display: grid;
                            grid-template-columns: auto auto;
                            grid-template-rows: auto 40px;
                        }
                        @media (max-width: 540px) {
                            .gift .image {
                                padding-right: 6%;
                            }
                            .gift .detail {
                                min-width: initial;
                                padding-left: 6%;
                                flex: 1 1 0;
                            }
                        }
                        .gift .detail .info {
                            grid-column: 1 / span 2;
                            grid-row: 1;
                        }
                        .gift .detail .info .title {
                            font: normal normal 800 18px/28px Noto Sans;
                        }
                        .gift .detail .info .subtitle {
                            margin-bottom: 4px;
                            font: normal normal 800 16px/22px Noto Sans;
                        }
                        @media (max-width: 600px) {
                            .gift .detail .info .title,
                            .gift .detail .info .subtitle {
                                display: inline-block;
                            }
                            .gift .detail .info .title {
                                font: normal normal 800 16px/22px Noto Sans;
                            }
                            .gift .detail .info .subtitle {
                                font: normal normal 800 14px/18px Noto Sans;
                            }
                        }
                        .gift .quota {
                            color: #999;
                            font: normal normal 800 14px/30px Noto Sans;
                            grid-column: 1;
                            grid-row: 2;
                            display: flex;
                            align-items: center;
                            width: max-content;
                        }
                        .gift .point {
                            grid-column: 2;
                            grid-row: 2;
                            display: flex;
                            justify-content: end;
                            align-items: center;
                        }
                        .gift .point .point-wrapper {
                            min-width: auto;
                            height: 30px; padding: 0 20px;
                            border-radius: 30px;
                            letter-spacing: initial;
                            color: #F37621;
                            display: inline-flex;
                        }
                        @media (max-width: 600px) {
                            .gift .point .point-wrapper {
                                padding: 0 12px;
                            }
                        }
                        .gift .point .point-wrapper::after {
                            width: 22px; height: 22px;
                            margin-left: 4px;
                        }
                    </style>
                    <div class="gift-list">
                        <div class="section-title hot-item" data-text="人氣獎賞">
                            <div class="icon"></div>
                        </div>
                        <div class="list">
@if( $redemptionGifts->isEmpty() )
                            <div class="no-result">
                                暫時未有相關獎賞
                                <div class="sleeping-bear"></div>
                            </div>
@else
@foreach( $redemptionGifts as $redemptionGift )
@if ($redemptionGift->quota >= 0)
                            <div class="gift" data-id="{{ $redemptionGift->id }}" data-data="{{ json_encode($redemptionGift->toArray(), JSON_UNESCAPED_UNICODE) }}">
                                <div class="image" style="--background-image:url('{{ empty($redemptionGift->thumbnail_filename)? asset('redemptions/empty.png'):asset('redemptions/'.$redemptionGift->thumbnail_filename) }}')"></div>
                                <div class="detail">
                                    <div class="info">
                                        <div class="title">{{ $redemptionGift->title['zh-HK'] ?? '' }}</div>
                                        <div class="subtitle">{{ $redemptionGift->subtitle['zh-HK'] ?? '' }}</div>
                                    </div>
@if ($redemptionGift->quota >0)
                                    <div class="quota">限量{{ $redemptionGift->quota }}份</div>
@endif
                                    <div class="point">
                                        <div class="point-wrapper">{{ $redemptionGift->required_points }}</div>
                                    </div>
                                </div>
                            </div>
@endif
@endforeach
@endif
                        </div>
                    </div>
                    <script src="{{ asset('assets/vendor/handlebars/handlebars.js') }}"></script>
                    <script>
                        (function(window, document, undefined){
                            const giftDetailPopupEle = document.querySelector('.popup.gift-detail');
                            const giftRedeemPopupEle = document.querySelector('.popup.gift-redeem');
                            const giftRedeemSuccessPopupEle = document.querySelector('.popup.gift-redeem-success');
                            // template
                            const giftDetailPopupTemptlate = Handlebars.compile(document.getElementById('gift-detail-popup-template').innerHTML);
                            const giftRedeemPopupTemptlate = Handlebars.compile(document.getElementById('gift-redeem-popup-template').innerHTML);
                            const giftRedeemSuccessPopupTemptlate = Handlebars.compile(document.getElementById('gift-redeem-success-popup-template').innerHTML);

                            let giftEles = document.querySelectorAll('.gift-list .gift');
                            giftEles.forEach(ele => {
                                ele.onclick = function() {
                                    let data = this.dataset.data;
                                    try {
                                        data = JSON.parse(data);
                                        if ( !data ) return false;

                                        const html = giftDetailPopupTemptlate(data);
                                        const detailWrapperEle = giftDetailPopupEle.querySelector('.detail-wrapper');
                                        if ( !detailWrapperEle ) return false;

                                        detailWrapperEle.innerHTML = html;
                                        giftDetailPopupEle.style.display = 'block';
                                        document.body.style.overflow = 'hidden';
                                        bindGiftDetailPopupEvent(data);
                                    } catch (err) {
                                        console.error(err);
                                    }
                                }
                            });

                            const popupDetailCloseButtonEle = giftDetailPopupEle.querySelector('a.close-button');
                            const popupRedeemCloseButtonEle = giftRedeemPopupEle.querySelector('a.close-button');
                            popupDetailCloseButtonEle.onclick = function() {
                                giftDetailPopupEle.style.display = 'none';
                                document.body.style.overflow = 'scroll';
                            }
                            popupRedeemCloseButtonEle.onclick = function() {
                                giftRedeemPopupEle.style.display = 'none';
                                document.body.style.overflow = 'scroll';
                            }

                            function bindGiftDetailPopupEvent(data) {
                                const nextButtonEle = giftDetailPopupEle.querySelector('a.next-button');
                                nextButtonEle.onclick = function() {
                                    const id = this.dataset.id;
                                    if ( !id ) return true;

                                    const giftEle = document.querySelector('.gift-list .gift[data-id="'+id+'"]');
                                    if ( !giftEle ) return false;

                                    let data = giftEle.dataset.data;
                                    try {
                                        data = JSON.parse(data);
                                        if ( !data ) return false;

                                        const html = giftRedeemPopupTemptlate(data);
                                        const containerEle = giftRedeemPopupEle.querySelector('.container');
                                        if ( !containerEle ) return false;

                                        containerEle.innerHTML = html;

                                        giftDetailPopupEle.style.display = 'none';
                                        giftRedeemPopupEle.style.display = 'block';
                                        document.body.style.overflow = 'hidden';
                                        bindGiftRedeemPopupEvent(data);
                                    } catch (err) {
                                        console.error(err);
                                    }
                                }
                            }

                            function bindGiftRedeemPopupEvent(data) {
                                let quantity = 1;
                                let maximumQuantity = parseInt(data.maximum_quantity);
                                const quantityEle = giftRedeemPopupEle.querySelector('.quantity-wrapper .quantity');
                                const minusButtonEle = giftRedeemPopupEle.querySelector('a.minus');
                                const plusButtonEle = giftRedeemPopupEle.querySelector('a.plus');
                                const redeemButtonEle = giftRedeemPopupEle.querySelector('a.redeem-button');

                                if (maximumQuantity == 0) {
                                    quantity = 0;
                                    quantityEle.innerText = quantity;
                                    redeemButtonEle.classList.add('disabled');
                                }

                                minusButtonEle.onclick = function() {
                                    if ( quantity <= 1 ) return false;
                                    quantity--;
                                    quantityEle.innerText = quantity;
                                }
                                plusButtonEle.onclick = function() {
                                    if ( quantity >= maximumQuantity ) return false;
                                    quantity++;
                                    quantityEle.innerText = quantity;
                                }
                                redeemButtonEle.onclick = function() {
                                    if ( redeemButtonEle.classList.contains('disabled') ) return false;
                                    redeemButtonEle.classList.add('disabled');

                                    const id = this.dataset.id;
                                    const title = this.dataset.title;
                                    const subtitle = this.dataset.subtitle;
                                    const requiredPoints = this.dataset.requiredPoints;
                                    let data = {"id": id, "quantity": quantity};

                                    fetch('{{ route("website.redemption.json") }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify(data)
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.status == 'success') {
                                            const templateData = {"title": title, "subtitle": subtitle, "points": requiredPoints * quantity};
                                            const html = giftRedeemSuccessPopupTemptlate(templateData);
                                            giftRedeemSuccessPopupEle.innerHTML = html;

                                            giftRedeemPopupEle.style.display = 'none';
                                            giftRedeemSuccessPopupEle.style.display = 'block';
                                            document.body.style.overflow = 'hidden';
                                        } else if (data.status == 'error') {
                                            let messages = [];
                                            if (data.errors instanceof Object) {
                                                for(let key in data.errors) {
                                                    if (data.errors[key] instanceof Array && 
                                                        typeof data.errors[key][0] == 'string' )
                                                        messages.push(data.errors[key][0]);
                                                }
                                            }
                                            if (messages.length > 0) alert(messages.join("\n"));
                                        } else {
                                            alert('發生錯誤，請稍後再試。(#90)');
                                        }
                                    })
                                    .catch((error) => {
                                        alert('發生錯誤，請稍後再試。(#91)');
                                    })
                                    .finally(() => {
                                        redeemButtonEle.classList.remove('disabled');
                                    });
                                }
                            }
                        })(window, document, undefined);
                    </script>
                </div>
            </div>
        </div>

        @include('website/common/footer')
    </body>
</html>