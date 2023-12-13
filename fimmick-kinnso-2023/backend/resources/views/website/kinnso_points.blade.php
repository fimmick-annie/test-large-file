<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		@include('website/common/head')
		@include('campaigns/components/css/hot_topic_and_filter')

		<!--  Help to ensure the left menu in right size  -->
		<link rel="stylesheet" href="./offers/common/offer_listing.css?v=1" />

		<style>
			body {
				background-color: #ffffff;
			}

			@font-face {
				font-family: kinnsoFont;
				src: url("{{ asset('assets/gensen.ttf') }}?v=1");
			}

			.header  {
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
			.header__landing  {
				background-color: #ffffff;
			}
			.logo  {
				position: absolute;
				left: 50%;
				top: 50%;
				width: 100%;
				max-width: 100px;
				display: block;
				-webkit-transform: translate(-50%, -50%);
				-ms-transform: translate(-50%, -50%);
				transform: translate(-50%, -50%);
			}
			.listing  {
				color: #757575;
				font-size: 1rem;
				line-height: 1.57142857;
			}
			.heading  {
				font-weight: bold;
				margin-top: 30px;
				margin-bottom: 10px;
			}
			.wrapper  {
				background-image: url("{{ asset('website/about-us/background.png') }}?v=1");
				background-repeat: no-repeat;
				background-color: #ffaf19;
				background-size: cover;
			}
			.content  {
				width: 100%;
				text-align: center;
				padding-bottom: 20px;
				padding-left: 0%;
				padding-right: 0%;
				padding-top: 20px;
				font-family: sans-serif;
                font-weight: 85;
			}

            .title_top{
				text-align: center;
                vertical-align: bottom;
				font-size: 20px;
                font-weight: bold;
                color: #f37720;
            }

            img {
                display:inline
            }

            .button_low {
                background-color: #ffffff;
                border: 2px solid #fce088;
                color: #f37720;
                text-align: center;
                width: 300px;
                height: 40px;
                font-size: 15px;
                cursor: pointer;
                border-radius: 20px;
            }
            
            .jumbotron{
                background-color: #fffce4;
                height: 185px;
                padding-top: 1%;
                position:relative;
            }

            .container{
                display: flex;
                justify-content: center;
                overflow: hidden;
                align-items: start;
            }

            .horizontal_scroll{
                display: flex;
                flex-direction:row;
                justify-content: space-between;
                align-items: center;
                position: relative;
                overflow: hidden;
                width: 300px;
                height: 180px;
            }


            .cate_container_desktop{
                display: flex;
                flex-direction: row;
                justify-content: center;
                align-items: center;
                position: content;
                height: 165px;
            }

            .cate_container{
                display: flex;
                flex-direction: row;
                justify-content: center;
                align-items: center;
                position: absolute;
                left: 0px;
                transition: 0.2s all ease-out;
            }

            .cate { /* adjust the content insides the cate */
                position: relative;
                width: 110px;
                margin: 10px 22px 20px 22px;
                justify-content: top;
                scroll-behavior: smooth;
            }

            .cate img,p{
                text-align: center;
            }

            .cate p{
                margin-bottom: 0.4rem;
            }

            .overlay{
                position: absolute;
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                height: 100%;
                width: 100%;
                opacity: 0;
            }

            @media(hover: hover){
                .cate:hover .overlay{
                    opacity: 1;
                }
            }

            .selecting_layover{
                position: absolute;
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                width: 100%;
                opacity: 1;
                display:none;
            }

            .s1{
                letter-spacing: 0.6px;
                color: #57524F;
            }

            .s2{
                letter-spacing: normal;
                color: #f6b401;
            }
            
            .s_coupon{
                font-family: sans-serif;
                font-weight: 85;
                font-size: 15px;
            }

            h1{
                border-right-style: solid;
                border-right-color: #F5CD47;
                width:90%;
            }

            .coupon_show{
                border-style: solid;
                border-color: #F5CD47;
                border-radius: 25px;
                height: 178px;
                width: 340px;
                align-content: center;
                align-items: center;
                position: relative;
            }

            .indicator{
                display: grid;
                justify-content: center;
                align-content: center;
            }

            #btn_scroll_left{
                stroke:#F5CD47;
                fill:#F5CD47;
            }

            #btn_scroll_right{
                stroke:#F5CD47;
                fill:#F5CD47;
            }

            #btn_scroll_left.splide__arrow--prev{
                background: white;
                border: 2px solid #F5CD47;
                border-radius:50%;
                position:absolute;
                top:40%;
                left:15px;
            }

            #btn_scroll_right.splide__arrow--next{
                background: white;
                border: 2px solid #F5CD47;
                border-radius:50%;
                position:absolute;
                top:40%;
                right:15px;
            }

            .left_1 {
                /* transform: rotate(180deg); */
                -webkit-transform: rotate(180deg);
            }

            .coupon_line{
                position: absolute;
                left: 100px;
                top: 11px;
                width: 4px;
                height: 150px;
                background-color:#F5CD47;
            }

			@media (max-width: 800px)  {
				.logo__desktop  {display: none;}
			}
			@media (min-width: 800px) {
				.logo__mobile  {display: none;}
			}

			.default-font-family {
				font-family: var(--bs-font-sans-serif);
			}

		</style>
    
	</head>

    <body>
    @include('website/common/tracking_body')

        <!--  Segment  -->
        <script>
            !function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","reset","group","track","ready","alias","debug","page","once","off","on","addSourceMiddleware","addIntegrationMiddleware","setAnonymousId","addDestinationMiddleware"];analytics.factory=function(e){return function(){var t=Array.prototype.slice.call(arguments);t.unshift(e);analytics.push(t);return analytics}};for(var e=0;e<analytics.methods.length;e++){var key=analytics.methods[e];analytics[key]=analytics.factory(key)}analytics.load=function(key,e){var t=document.createElement("script");t.type="text/javascript";t.async=!0;t.src="https://cdn.segment.com/analytics.js/v1/" + key + "/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(t,n);analytics._loadOptions=e};analytics._writeKey="W1yJ9TYonvWx2FlA6469eTyvX0xegGS3";analytics.SNIPPET_VERSION="4.13.2";
                analytics.load("{{ env('SEGMENT_ID') }}");
                analytics.page("about-us", {
                    ip: "{{ $ipAddress }}",
                    userAgent: "{{ $userAgent }}",
                });
            }}();
        </script>
        <!--  End Segment  -->
    
        @include('campaigns/common/header')
      
        <div class="content">
            <div class="title_top"><br><br>
                <img src="{{ asset('website/point_pages_image/icon01_top_p.png') }}?v=1" alt="p01" style="width:50px" /> 儲 Kinnso Points 換獎賞</div>
                <div class="col"><img src="{{ asset('website/point_pages_image/icon02_top_pbear.png') }}?v=1" alt="p02" style="width:250px;" />
                    <br><br>第一次成功拎著數後，
                    <br>就可以參加儲 <img src="{{ asset('website/point_pages_image/icon00_p_point.png') }}?v=1" alt="PP_icon" style="width:20px;" /> Kinnso Points 活動。
                    <br>
                    <br>係Kinnso WhatsApp，
                    <br>每完成以下一個任務就可以獲得 <img src="{{ asset('website/point_pages_image/icon00_p_point.png') }}?v=1" alt="PP_icon1" style="width:20px;" /> Kinnso Points﹗
                </div>
            </div>
        </div>

        <div class="jumbotron">
            <div class="logo__desktop">
                <div class="cate_container_desktop" >
                    <div id="option1" class="cate">
                        <img src="{{ asset('website/point_pages_image/button01_a.png') }}?v=1" alt="option1_a" />
                        <div class="overlay">
                            <img src="{{ asset('website/point_pages_image/button01_b.png') }}?v=1" alt="option1_b." />
                        </div>
                        <img  id="cover01a" class="selecting_layover" src="{{ asset('website/point_pages_image/button01_b.png') }}?v=1" alt="option1_b." />
                        <p class="s1">{{__('messages.TITLE_TAKE_OFFER')}}</p>
                        <p class="s2">{{ config('points.offer_taking') }} <img src="{{ asset('website/point_pages_image/icon00_p_point.png') }}?v=1" alt="PP_icon1" style="width:20px;" /></p>
                    </div>
                    
                    <div id="option2" class="cate">
                        <img src="{{ asset('website/point_pages_image/button02_a.png') }}?v=1" alt="option2_a" />
                        <div class="overlay">
                            <img src="{{ asset('website/point_pages_image/button02_b.png') }}?v=1" alt="option2_b" />
                        </div>
                        <img  id="cover02a" class="selecting_layover" src="{{ asset('website/point_pages_image/button02_b.png') }}?v=1" alt="option2_b." />
                        <p class="s1">{{__('messages.TILTE_DAILY_QUESTION')}}</p>
                        <p class="s2">{{ config('points.daily_question') }}  <img src="{{ asset('website/point_pages_image/icon00_p_point.png') }}?v=1" alt="PP_icon1" style="width:20px;" /></p>
                    </div>

                    <div id="option3" class="cate">
                        <img src="{{ asset('website/point_pages_image/button03_a.png') }}?v=1" alt="option3_a" />
                        <div class="overlay">
                            <img src="{{ asset('website/point_pages_image/button03_b.png') }}?v=1" alt="option3_b" />
                        </div>
                        <img  id="cover03a" class="selecting_layover" src="{{ asset('website/point_pages_image/button03_b.png') }}?v=1" alt="option3_b." />
                        <p class="s1">{{__('messages.TITLE_SUCCESS_MEMBER_REFERRAL')}}</p>
                        <p class="s2">{{ config('points.success_referral') }} <img src="{{ asset('website/point_pages_image/icon00_p_point.png') }}?v=1" alt="PP_icon1" style="width:20px;" /></p>
                    </div>

                    <div id="option4" class="cate">
                        <img src="{{ asset('website/point_pages_image/button04_a.png') }}?v=1" alt="option4_a" />
                        <div class="overlay">
                            <img src="{{ asset('website/point_pages_image/button04_b.png') }}?v=1" alt="option4_b" />
                        </div>
                        <img  id="cover04a" class="selecting_layover" src="{{ asset('website/point_pages_image/button04_b.png') }}?v=1" alt="option4_b." />
                        <p class="s1">{{__('messages.TITLE_SPECIAL_TASK')}}</p>
                        <p class="s2">{{ config('points.special_mission') }} <img src="{{ asset('website/point_pages_image/icon00_p_point.png') }}?v=1" alt="PP_icon1" style="width:20px;" /></p>
                    </div>

                    <div id="option5" class="cate">
                        <img src="{{ asset('website/point_pages_image/button05_a.png') }}?v=1" alt="option5_a" />
                        <div class="overlay">
                            <img src="{{ asset('website/point_pages_image/button05_b.png') }}?v=1" alt="option5_b" />
                        </div>
                        <img id="cover05a" class="selecting_layover" src="{{ asset('website/point_pages_image/button05_b.png') }}?v=1" alt="option5_b." />
                        <p class="s1">{{__('messages.TITLE_OFFER_HURTING')}}</p>
                        <p class="s2">{{ config('points.offer_hunting') }} <img src="{{ asset('website/point_pages_image/icon00_p_point.png') }}?v=1" alt="PP_icon1" style="width:20px;" /></p>
                    </div>
                </div>
            </div>

            <div class="logo__mobile">
                <div class="container" >
                    <div class="horizontal_scroll">
                        <div class="cate_container"> 
                            <div id="option1b" class="cate">
                                <img src="{{ asset('website/point_pages_image/button01_a.png') }}?v=1" alt="option1_a" />
                                <div class="overlay">
                                    <img src="{{ asset('website/point_pages_image/button01_b.png') }}?v=1" alt="option1_a" />
                                </div>
                                <img id="cover01b" class="selecting_layover" src="{{ asset('website/point_pages_image/button01_b.png') }}?v=1" alt="option1_b" />
                                <p class="s1">{{__('messages.TITLE_TAKE_OFFER')}}</p>
                                <p class="s2">{{ config('points.offer_taking') }}  <img src="{{ asset('website/point_pages_image/icon00_p_point.png') }}?v=1" alt="PP_icon1" style="width:20px;" /></p>
                            </div>
                            
                            <div id="option2b" class="cate">
                                <img src="{{ asset('website/point_pages_image/button02_a.png') }}?v=1" alt="option1_a" />
                                <div class="overlay">
                                    <img src="{{ asset('website/point_pages_image/button02_b.png') }}?v=1" alt="option1_a" />
                                </div>
                                <img id="cover02b" class="selecting_layover" src="{{ asset('website/point_pages_image/button02_b.png') }}?v=1" alt="option2_b" />
                                <p class="s1">{{__('messages.TILTE_DAILY_QUESTION')}}</p>
                                <p class="s2">{{ config('points.daily_question') }} <img src="{{ asset('website/point_pages_image/icon00_p_point.png') }}?v=1" alt="PP_icon1" style="width:20px;" /></p>
                            </div>

                            <div id="option3b" class="cate">
                                <img src="{{ asset('website/point_pages_image/button03_a.png') }}?v=1" alt="option1_a" />
                                <div class="overlay">
                                    <img src="{{ asset('website/point_pages_image/button03_b.png') }}?v=1" alt="option1_a" />
                                </div>
                                <img id="cover03b" class="selecting_layover" src="{{ asset('website/point_pages_image/button03_b.png') }}?v=1" alt="option3_b" />
                                <p class="s1">{{__('messages.TITLE_SUCCESS_MEMBER_REFERRAL')}}</p>
                                <p class="s2">{{ config('points.success_referral') }} <img src="{{ asset('website/point_pages_image/icon00_p_point.png') }}?v=1" alt="PP_icon1" style="width:20px;" /></p>
                            </div>

                            <div id="option4b" class="cate">
                                <img src="{{ asset('website/point_pages_image/button04_a.png') }}?v=1" alt="option1_a" />
                                <div class="overlay">
                                    <img src="{{ asset('website/point_pages_image/button04_b.png') }}?v=1" alt="option1_a" />
                                </div>
                                <img id="cover04b" class="selecting_layover" src="{{ asset('website/point_pages_image/button04_b.png') }}?v=1" alt="option4_b" />
                                <p class="s1">{{__('messages.TITLE_SPECIAL_TASK')}}</p>
                                <p class="s2">{{ config('points.special_mission') }} <img src="{{ asset('website/point_pages_image/icon00_p_point.png') }}?v=1" alt="PP_icon1" style="width:20px;" /></p>
                            </div>

                            <div id="option5b" class="cate">
                                <img src="{{ asset('website/point_pages_image/button05_a.png') }}?v=1" alt="option1_a" />
                                <div class="overlay">
                                    <img src="{{ asset('website/point_pages_image/button05_b.png') }}?v=1" alt="option1_a" />
                                </div>
                                <img id="cover05b" class="selecting_layover" src="{{ asset('website/point_pages_image/button05_b.png') }}?v=1" alt="option5_b" />
                                <p class="s1">{{__('messages.TITLE_OFFER_HURTING')}}</p>
                                <p class="s2">{{ config('points.offer_hunting') }} <img src="{{ asset('website/point_pages_image/icon00_p_point.png') }}?v=1" alt="PP_icon1" style="width:20px;" /></p>
                            </div>
                        </div>
                    </div>
                    <button id="btn_scroll_left" class="splide__arrow splide__arrow--prev left_1" onclick="scroll_hor(1)" >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 55 55" width="15" height="15">
                            <path d="m15.5 0.932-4.3 4.38 14.5 14.6-14.5 14.5 4.3 4.4 14.6-14.6 4.4-4.3-4.4-4.4-14.6-14.6z"></path>
                        </svg>
                    </button>
                    <button id="btn_scroll_right" class="splide__arrow splide__arrow--next" onclick="scroll_hor(-1)" >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35 55" width="15" height="15" >
                            <path d="m15.5 0.932-4.3 4.38 14.5 14.6-14.5 14.5 4.3 4.4 14.6-14.6 4.4-4.3-4.4-4.4-14.6-14.6z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <br>
        <!--Show coupon content by selecting above list-->
        <div class="container">
            <div class="row coupon_show">
                <div id="coupon_icon" class="col-4"><img src="{{ asset('website/point_pages_image/button01_bottom.png') }}?v=1" alt="option1_a" style="width:90%;" /></div>
                <div class="col-8">
                    <div class="row" style=""><p id="coupon_title" class="s1" style="margin-bottom: 0.5rem;" >{{__('messages.TITLE_TAKE_OFFER')}}</p></div>
                    <div class="row" style="height:90px;"><p id="coupon_content" class="s_coupon">{{__('messages.MSG_TAKE_OFFER')}}</p></div>
                    <div class="row">
                        <div class="col"><p id="coupon_points" class="s2" style="margin: 0.5rem;">10 <img src="{{ asset('website/point_pages_image/icon00_p_point.png') }}?v=1" alt="PP_icon1" style="width:20px;" /></p></div>
                    </div>
                <view class="coupon_line"></view>
                </div>
            </div>
        </div>

        
        <!--go to redemption page-->
        <div class="content">
            <a href="{{ route('website.redemption.html') }}"> 
                <button class="button_low" type="button">立 即 瀏 覽 獎 賞 中 心！</button>
            </a>
        </div>
        

        <!--JS for selecting option coupon-->
        <script>
                const element1 = document.getElementById("option1");
                const element2 = document.getElementById("option2");
                const element3 = document.getElementById("option3");
                const element4 = document.getElementById("option4");
                const element5 = document.getElementById("option5");
                const element1b = document.getElementById("option1b");
                const element2b = document.getElementById("option2b");
                const element3b = document.getElementById("option3b");
                const element4b = document.getElementById("option4b");
                const element5b = document.getElementById("option5b");

                element1.addEventListener("click", function() {
                    document.getElementById("cover01a").style.display="block";
                    document.getElementById("cover02a").style.display="none";
                    document.getElementById("cover03a").style.display="none";
                    document.getElementById("cover04a").style.display="none";
                    document.getElementById("cover05a").style.display="none";
                    document.getElementById("coupon_icon").innerHTML = "<img src=\"{{ asset('website/point_pages_image/button01_bottom.png') }}?v=1\" alt=\"option1_a\" style=\"width:90%;\" />";
                    document.getElementById("coupon_title").innerHTML = "{{__('messages.TITLE_TAKE_OFFER')}}";
                    // document.getElementById("coupon_content").innerHTML = "每一次成功透過WhatsApp拎著數，即獲得{{ config('points.offer_taking') }} Points!";
                    document.getElementById("coupon_content").innerHTML = "{{__('messages.MSG_TAKE_OFFER')}}";
                    document.getElementById("coupon_points").innerHTML = "{{ config('points.offer_taking') }} <img src=\"{{ asset('website/point_pages_image/icon00_p_point.png') }}?v=1\" alt=\"PP_icon1\" style=\"width:20px;\" />";
                },false);

                element2.addEventListener("click", function() {
                    document.getElementById("cover01a").style.display="none";
                    document.getElementById("cover02a").style.display="block";
                    document.getElementById("cover03a").style.display="none";
                    document.getElementById("cover04a").style.display="none";
                    document.getElementById("cover05a").style.display="none";
                    document.getElementById("coupon_icon").innerHTML = "<img src=\"{{ asset('website/point_pages_image/button02_bottom.png') }}?v=1\" alt=\"option1_a\" style=\"width:90%;\" />";
                    document.getElementById("coupon_title").innerHTML = "{{__('messages.TILTE_DAILY_QUESTION')}}";
                    // document.getElementById("coupon_content").innerHTML = "每一日成功透過WhatsApp 與蜜熊傾計，即獲得{{ config('points.daily_question') }} Points!";
                    document.getElementById("coupon_content").innerHTML = "{{__('messages.MSG_DAILY_QUESTION')}}";
                    document.getElementById("coupon_points").innerHTML = "{{ config('points.daily_question') }} <img src=\"{{ asset('website/point_pages_image/icon00_p_point.png') }}?v=1\" alt=\"PP_icon1\" style=\"width:20px;\" />";
                },false);

                element3.addEventListener("click", function() {
                    document.getElementById("cover01a").style.display="none";
                    document.getElementById("cover02a").style.display="none";
                    document.getElementById("cover03a").style.display="block";
                    document.getElementById("cover04a").style.display="none";
                    document.getElementById("cover05a").style.display="none";
                    document.getElementById("coupon_icon").innerHTML = "<img src=\"{{ asset('website/point_pages_image/button03_bottom.png') }}?v=1\" alt=\"option3_a\" style=\"width:90%;\" />";
                    document.getElementById("coupon_title").innerHTML = "{{__('messages.TITLE_SUCCESS_MEMBER_REFERRAL')}}";
                    // document.getElementById("coupon_content").innerHTML = "每一次成功透過WhatsApp拎著數並成功把著數推薦給Kinnso全新用戶朋友，即獲得{{ config('points.success_referral') }} Points!";
                    document.getElementById("coupon_content").innerHTML = "{{__('messages.MSG_SUCCESS_MEMBER_REFERRAL')}}";
                    document.getElementById("coupon_points").innerHTML = "{{ config('points.success_referral') }} <img src=\"{{ asset('website/point_pages_image/icon00_p_point.png') }}?v=1\" alt=\"PP_icon1\" style=\"width:20px;\" />";
                },false);

                element4.addEventListener("click", function() {
                    document.getElementById("cover01a").style.display="none";
                    document.getElementById("cover02a").style.display="none";
                    document.getElementById("cover03a").style.display="none";
                    document.getElementById("cover04a").style.display="block";
                    document.getElementById("cover05a").style.display="none";
                    document.getElementById("coupon_icon").innerHTML = "<img src=\"{{ asset('website/point_pages_image/button04_bottom.png') }}?v=1\" alt=\"option1_a\" style=\"width:90%;\" />";
                    document.getElementById("coupon_title").innerHTML = "{{__('messages.TITLE_SPECIAL_TASK')}}";
                    // document.getElementById("coupon_content").innerHTML = "不定期透過WhatsApp推出特別任務，成功完成即獲得{{ config('points.special_mission') }} Points! ";
                    document.getElementById("coupon_content").innerHTML = "{{__('messages.MSG_SPECIAL_TASK')}}";
                    document.getElementById("coupon_points").innerHTML = "{{ config('points.special_mission') }} <img src=\"{{ asset('website/point_pages_image/icon00_p_point.png') }}?v=1\" alt=\"PP_icon1\" style=\"width:20px;\" />";
                },false);

                element5.addEventListener("click", function() {
                    document.getElementById("cover01a").style.display="none";
                    document.getElementById("cover02a").style.display="none";
                    document.getElementById("cover03a").style.display="none";
                    document.getElementById("cover04a").style.display="none";
                    document.getElementById("cover05a").style.display="block";
                    document.getElementById("coupon_icon").innerHTML = "<img src=\"{{ asset('website/point_pages_image/button05_bottom.png') }}?v=1\" alt=\"option1_a\" style=\"width:90%;\" />";
                    document.getElementById("coupon_title").innerHTML = "{{__('messages.TITLE_OFFER_HURTING')}}";
                    // document.getElementById("coupon_content").innerHTML = "優惠報料，優惠一經採用，即獲得{{ config('points.offer_hunting') }} points! ";
                    document.getElementById("coupon_content").innerHTML = "{{__('messages.MSG_OFFER_HURTING')}}";
                    document.getElementById("coupon_points").innerHTML = "{{ config('points.offer_hunting') }} <img src=\"{{ asset('website/point_pages_image/icon00_p_point.png') }}?v=1\" alt=\"PP_icon1\" style=\"width:20px;\" />";
                },false);

                element1b.addEventListener("click", function() {
                    document.getElementById("cover01b").style.display="block";
                    document.getElementById("cover02b").style.display="none";
                    document.getElementById("cover03b").style.display="none";
                    document.getElementById("cover04b").style.display="none";
                    document.getElementById("cover05b").style.display="none";
                    document.getElementById("coupon_icon").innerHTML = "<img src=\"{{ asset('website/point_pages_image/button01_bottom.png') }}?v=1\" alt=\"option1_a\" style=\"width:90%;\" />";
                    document.getElementById("coupon_title").innerHTML = "{{__('messages.TITLE_TAKE_OFFER')}}";
                    // document.getElementById("coupon_content").innerHTML = "每一次成功透過WhatsApp拎著數，即獲得{{ config('points.offer_taking') }}  Points!";
                    document.getElementById("coupon_content").innerHTML = "{{__('messages.MSG_TAKE_OFFER')}}";
                    document.getElementById("coupon_points").innerHTML = "{{ config('points.offer_taking') }} <img src=\"{{ asset('website/point_pages_image/icon00_p_point.png') }}?v=1\" alt=\"PP_icon1\" style=\"width:20px;\" />";
                },false);

                element2b.addEventListener("click", function() {
                    document.getElementById("cover01b").style.display="none";
                    document.getElementById("cover02b").style.display="block";
                    document.getElementById("cover03b").style.display="none";
                    document.getElementById("cover04b").style.display="none";
                    document.getElementById("cover05b").style.display="none";
                    document.getElementById("coupon_icon").innerHTML = "<img src=\"{{ asset('website/point_pages_image/button02_bottom.png') }}?v=1\" alt=\"option1_a\" style=\"width:90%;\" />";
                    document.getElementById("coupon_title").innerHTML = "{{__('messages.TILTE_DAILY_QUESTION')}}";
                    // document.getElementById("coupon_content").innerHTML = "每一日成功透過WhatsApp 與蜜熊傾計，即獲得{{ config('points.daily_question') }} Points!";
                    document.getElementById("coupon_content").innerHTML = "{{__('messages.MSG_DAILY_QUESTION')}}";
                    document.getElementById("coupon_points").innerHTML = "{{ config('points.daily_question') }} <img src=\"{{ asset('website/point_pages_image/icon00_p_point.png') }}?v=1\" alt=\"PP_icon1\" style=\"width:20px;\" />";
                },false);

                element3b.addEventListener("click", function() {
                    document.getElementById("cover01b").style.display="none";
                    document.getElementById("cover02b").style.display="none";
                    document.getElementById("cover03b").style.display="block";
                    document.getElementById("cover04b").style.display="none";
                    document.getElementById("cover05b").style.display="none";
                    document.getElementById("coupon_icon").innerHTML = "<img src=\"{{ asset('website/point_pages_image/button03_bottom.png') }}?v=1\" alt=\"option3_a\" style=\"width:90%;\" />";
                    document.getElementById("coupon_title").innerHTML = "{{__('messages.TITLE_SUCCESS_MEMBER_REFERRAL')}}";
                    // document.getElementById("coupon_content").innerHTML = "每一次成功透過WhatsApp拎著數並成功把著數推薦給Kinnso全新用戶朋友，即獲得{{ config('points.success_referral') }} Points!";
                    document.getElementById("coupon_content").innerHTML = "{{__('messages.MSG_SUCCESS_MEMBER_REFERRAL')}}";
                    document.getElementById("coupon_points").innerHTML = "{{ config('points.success_referral') }} <img src=\"{{ asset('website/point_pages_image/icon00_p_point.png') }}?v=1\" alt=\"PP_icon1\" style=\"width:20px;\" />";
                },false);

                element4b.addEventListener("click", function() {
                    document.getElementById("cover01b").style.display="none";
                    document.getElementById("cover02b").style.display="none";
                    document.getElementById("cover03b").style.display="none";
                    document.getElementById("cover04b").style.display="block";
                    document.getElementById("cover05b").style.display="none";
                    document.getElementById("coupon_icon").innerHTML = "<img src=\"{{ asset('website/point_pages_image/button04_bottom.png') }}?v=1\" alt=\"option1_a\" style=\"width:90%;\" />";
                    document.getElementById("coupon_title").innerHTML = "{{__('messages.TITLE_SPECIAL_TASK')}}";
                    // document.getElementById("coupon_content").innerHTML = "不定期透過WhatsApp推出特別任務，成功完成即獲得{{ config('points.special_mission') }} Points! ";
                    document.getElementById("coupon_content").innerHTML = "{{__('messages.MSG_SPECIAL_TASK')}}";
                    document.getElementById("coupon_points").innerHTML = "{{ config('points.special_mission') }} <img src=\"{{ asset('website/point_pages_image/icon00_p_point.png') }}?v=1\" alt=\"PP_icon1\" style=\"width:20px;\" />";
                },false);

                element5b.addEventListener("click", function() {
                    document.getElementById("cover01b").style.display="none";
                    document.getElementById("cover02b").style.display="none";
                    document.getElementById("cover03b").style.display="none";
                    document.getElementById("cover04b").style.display="none";
                    document.getElementById("cover05b").style.display="block";
                    document.getElementById("coupon_icon").innerHTML = "<img src=\"{{ asset('website/point_pages_image/button05_bottom.png') }}?v=1\" alt=\"option1_a\" style=\"width:90%;\" />";
                    document.getElementById("coupon_title").innerHTML ="{{__('messages.TITLE_OFFER_HURTING')}}";
                    // document.getElementById("coupon_content").innerHTML = "優惠報料，優惠一經採用，即獲得{{ config('points.offer_hunting') }} points! ";
                    document.getElementById("coupon_content").innerHTML = "{{__('messages.MSG_OFFER_HURTING')}}";
                    document.getElementById("coupon_points").innerHTML = "{{ config('points.offer_hunting') }} <img src=\"{{ asset('website/point_pages_image/icon00_p_point.png') }}?v=1\" alt=\"PP_icon1\" style=\"width:20px;\" />";
                },false);
                
            </script>
           
            <!--scrolling code-->
            <script>
                let current_position_num = 0;
                let scrollAmount = 156; /* one cate width with padding */
                
                const sCont = document.querySelector(".cate_container");
                const hScroll = document.querySelector(".horizontal_scroll");
                // const btnScroll_Left = document.querySelector("#btn_scroll_left");
                // const btnScroll_Right = document.querySelector("#btn_scroll_right");

                let maxScroll = -sCont.offsetWidth + hScroll.offsetWidth;

                function scroll_hor(val){

                    current_position_num += (val * scrollAmount);

                    if (current_position_num >= 0){
                        current_position_num = 0;
                    }

                    if (current_position_num <= maxScroll){
                        current_position_num = maxScroll;
                    }

                    sCont.style.left = current_position_num + "px";
                }
                    
            </script>


            @include('website/common/footer')
            
    </body>
</html>