<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		@include('website/common/head')

		<style>
			body {
				background-color: #ffffff;
			}

			@font-face {
				font-family: kinnsoFont;
				src: url("{{ asset('assets/gensen.ttf') }}?v=1");
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

			.listing {
				color: #757575;
				font-size: 1rem;
				line-height: 1.57142857;
			}

			.heading {
				font-weight: bold;
				margin-top: 30px;
				margin-bottom: 10px;
			}

			.wrapper {
				/* background-image: url("{{ asset('website/about-us/background.png') }}?v=1"); */
				/* background-repeat: no-repeat; */
				/* background-color: #ffaf19; */
				/* background-size: cover; */
			}

			.content {
				text-align: center;
				padding-top: 100px;
				font-family: Noto Sans;
                padding-left: 200px;
                padding-right: 200px;
			}

            img { 
                position: inline;
            }

            .content h1{
                color:#F37621;
                text-align: center;
                font-size: 20px;
            }

            .content h4{
                color:#6D6D6D;
            }

            .content p{
                /* align-content: center;
                color: #6D6D6D;
                font-size: 25px;
                margin-bottom: 2px; */
                align-content: center;
                color: #6D6D6D;
                font-size: 1.4em;
                font-weight: 400;
                min-width: 100px;
                max-width: 200px;
                margin-bottom: 2px;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .content i{
                align-content: center;
                color: #6D6D6D;
                font-size: 12px;
                position:relative;
                font-style: normal;
            }

            p.solid {
                /* border-style: solid; 
                border-color: #FCCC08;
                border-radius: 5%; */
                min-width: 100px;   
                max-width: 200px;
                height: 35px;
                padding: 0 18px;
                border: 1px solid #FCD533;
                border-radius: 6px;
                letter-spacing: 0.4px;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .right_side_color{
                border-right-style: solid;
                border-right-color: #FACD56;
                border-right-width: thin;
            }

            .table_header{
                border-right-style: solid;
                border-right-color: #FACD56;
                border-bottom-style: solid;
                border-bottom-color: #FACD56;
                text-align: left;
				font-family: Noto Sans;
                font-weight: 120px;
                border-right-width: thin;
                border-bottom-width: thin;
                display: flex;
                align-items: center;
                justify-content: left;
            }

            .table_header_right{
                border-bottom-style: solid;
                border-bottom-color: #FACD56;
                border-bottom-width: thin;
                text-align: left;
				font-family: Noto Sans;
                font-weight: 120px;
                display: flex;
                align-items: center;
                justify-content: left;
            }

            .table_content{
                border-right-style: solid;
                border-right-color: #FACD56;
                border-right-width: thin;
                text-align: left;
                display: flex;
                align-items: center;
                justify-content: left;
                border-bottom-style: solid;
                border-bottom-color: rgba(240, 240, 240, 0.7);
                border-bottom-width: thin;
            }

            .table_content_right{
                text-align: left;
                display: flex;
                align-items: center;
                justify-content: left;
                border-bottom-style: solid;
                border-bottom-color: rgba(240, 240, 240, 0.7);
                border-bottom-width: thin;
            }

            .table_content_topline{
                border-right-style: solid;
                border-right-color: #FACD56;
                border-right-width: thin;
            }

            .t1{
                font-family: Noto Sans;
                font-size : 14px;
                color: grey;
            }

            .bg_left{
                position:absolute;
                left:-20px;
                top:-10px;
                width: 20%;
            }
            .bg_right{
                position:absolute;
                right:0;
                top:55%;
                width: 20%;
            }
            
			@media (max-width: 1365px) {
				.logo__desktop {
					display: none;
				}

                .content{
                    padding-left:5%;
                    padding-right:5%;
                }

                .content h1{
                    font-size: 1em;
                }

                .bg_left{
                    position:absolute;
                    left:-140px;
                    top:40px;
                    width:250px;
                }
                .bg_right{
                    position:absolute;
                    right:0;
                    top:75%;
                    width: 45%;
                }
                .t1{
                font-size : 12px;
            }
			}

			@media (min-width: 1366px) {
				.logo__mobile {
					display: none;
				}
			}

			.default-font-family {
				font-family: var(--bs-font-sans-serif);
			}
		</style>

		<script charset="utf-8" type="text/javascript" src="https://js.hsforms.net/forms/shell.js"></script>

        <!--help to ensure the left menu in right size-->
		<link rel="stylesheet" href="./offers/common/offer_listing.css?v=1" />

	</head>

	<body>
		@include('website/common/tracking_body')

        <div class="offer">
            @include('campaigns/common/header')
        </div>

        <div class="content">
            <img class="bg_left" src="{{ asset('website/point_history/background_left_con.png') }}" alt="bg_left1"/>
            <img class="bg_right" src="{{ asset('website/point_history/background_right_con.png') }}" alt="bg_right1"/>
            <div class="container-md">
                <div class="row" style="padding-bottom:30px;">
                    <div class="col-3"></div>
                    <div class="col-3"><img src="{{ asset('website/point_history/icon_profile.png') }}" alt="icon_profile" style="width:100px; padding:0px;" /></div>
                    <div class="col-3 justify-content-center">
                            <p style="font-size:18px">我的積分</p>
                            <p class="solid">{{ $pointBalance<0? 0:$pointBalance  }} <img src="{{ asset('website/point_history/icon_point_p.png') }}" alt="p01" style="width:18px;" /></p>
                    </div>
                    <div class="col-3"></div>
                </div>

                <div class="col"><h1><img src="{{ asset('website/point_history/icon_my_point.png') }}" alt="p01" style="width:32px;" /> 積分有效期概要</h1></div>   


                <div class="row" style="padding-bottom:15px; height:100px;">
@if(date("Y-m-d H:i:s") > "2022-12-31 23:59:59")
                    <div class="col justify-content-center right_side_color">
                        <h4 style="font-size:30px">{{ $period1Points<0 ? 0:$period1Points }} <img src="{{ asset('website/point_history/icon_point_p.png') }}" alt="p01" style="width:20px;" /></h4>
                        <i>將於{{$nowYear}}年{{$nowMonth<=6?'6月30日':'12月31日'}}到期</i>
                    </div>
@endif
                    <div class="col justify-content-center">
                        <h4 style="font-size:30px">{{ $period2Points<0? 0:$period2Points }} <img src="{{ asset('website/point_history/icon_point_p.png') }}" alt="p01" style="width:20px;" /></h4>
                        <i>將於{{$nowMonth<=6?$nowYear:($nowYear+1)}}年{{$nowMonth<=6?'12月31日':'6月30日'}}到期</i>
                    </div>
                </div>

                <div class="col"><h1><img src="{{ asset('website/point_history/icon_my_point.png') }}" alt="p01" style="width:28px;" /> 我的積分詳情</h1></div>

                <div class="row justify-content-md-center " style="height: 40px;">
                    <div class="col table_header">日期</div>
                    <div class="col-6 table_header">描述</div>
                    <div class="col-3 table_header_right">積分<img src="{{ asset('website/point_history/icon_point_p.png') }}" alt="p01" style="width:20px;" />
                    </div>
                </div>

@foreach( $pointHistory as $pointHistory )
                <div class="row justify-content-md-center t1" style="height: 40px;">
                    <div class="col table_content">{{ substr($pointHistory->created_at, 0, 10) }}</div>
                    <div class="col-6 table_content">{{ $pointHistory->description['zh-HK'] ?? ''}}</div>
                    <div class="col-3 table_content_right"> {{ ($pointHistory->delta_points>0) ? '+'.$pointHistory->delta_points : $pointHistory->delta_points }}</div>
                </div>
@endforeach

                <div class="row justify-content-md-center" style="height:12px;">
                    <div class="col table_content_topline"></div>
                    <div class="col-6 table_content_topline "></div>
                    <div class="col-3 "></div>
                </div>

            </div>
            <br>
            <br>
        </div>

        @include('website/common/footer')
		
	</body>
</html>
