<div id="filter-mask"></div>
<div class="hot-topic-container">
    <div class="hot-topic-wrapper" data-text="熱門主題：">
        <div id="hot-topic-list" class="hot-topic-list" data-filter="{{ $filterData['hot_topic'] ?? '' }}"></div>
        <div id="filter-button" class="filter-button">
            <div class="filter-wrapper">
                <a href="javascript:void(0)" class="button close-button"></a>
                <div class="filter-title" data-text="活動分類"></div>
                <div id="offer-filter-list" class="filter-category-list" data-filter="{{ $filterData['filters'] ?? '' }}">
                    <div class="filter-category" data-text="好去處">
                        <div class="filter-sub-category-list">
                            <!-- <a href="javascript:void(0)" class="filter-sub-category" data-text="一日遊"></a>
                            <a href="javascript:void(0)" class="filter-sub-category" data-text="情侶"></a>
                            <a href="javascript:void(0)" class="filter-sub-category" data-text="親子"></a>
                            <a href="javascript:void(0)" class="filter-sub-category" data-text="戶外活動"></a>
                            <a href="javascript:void(0)" class="filter-sub-category" data-text="室內活動"></a> -->
                            <a href="javascript:void(0)" class="filter-sub-category" data-text="玩樂熱點"></a>
                            <a href="javascript:void(0)" class="filter-sub-category" data-text="本地遊"></a>
                            <a href="javascript:void(0)" class="filter-sub-category" data-text="展覽及活動"></a>
                            <a href="javascript:void(0)" class="filter-sub-category" data-text="音樂及舞台"></a>
                            <a href="javascript:void(0)" class="filter-sub-category" data-text="運動"></a>
                            <a href="javascript:void(0)" class="filter-sub-category" data-text="旅行"></a>
                        </div>
                    </div>
                    <div class="filter-category" data-text="美食">
                        <div class="filter-sub-category-list">
                            <a href="javascript:void(0)" class="filter-sub-category" data-text="放題 / Buffet"></a>
                            <a href="javascript:void(0)" class="filter-sub-category" data-text="米芝蓮 / Omakase"></a>
                            <!-- <a href="javascript:void(0)" class="filter-sub-category" data-text="外賣"></a>
                            <a href="javascript:void(0)" class="filter-sub-category" data-text="散水餅"></a> -->
                            <a href="javascript:void(0)" class="filter-sub-category" data-text="新店"></a>
                        </div>
                    </div>
                    <div class="filter-category" data-text="優惠">
                        <div class="filter-sub-category-list">
                            <a href="javascript:void(0)" class="filter-sub-category" data-text="娛樂"></a>
                            <a href="javascript:void(0)" class="filter-sub-category" data-text="嬰童"></a>
                            <a href="javascript:void(0)" class="filter-sub-category" data-text="電器及家品"></a>
                            <a href="javascript:void(0)" class="filter-sub-category" data-text="美容及護膚"></a>
                            <a href="javascript:void(0)" class="filter-sub-category" data-text="信用卡"></a>
                            <a href="javascript:void(0)" class="filter-sub-category" data-text="Staycation"></a>
                            <a href="javascript:void(0)" class="filter-sub-category" data-text="服飾"></a>
                        </div>
                    </div>
                    <!-- <div class="filter-category" data-text="網購">
                        <div class="filter-sub-category-list">
                            <a href="javascript:void(0)" class="filter-sub-category" data-text="國際"></a>
                            <a href="javascript:void(0)" class="filter-sub-category" data-text="本地"></a>
                        </div>
                    </div> -->
                </div>
                <div class="button-wrapper">
                    <a href="javascript:void(0)" class="button search-button" data-text="搜尋"></a>
                    <a href="javascript:void(0)" class="button reset-button" data-text="重設"></a>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="offer-category-list" class="offer-category-wrapper" data-filter="{{ $filterData['category'] ?? '' }}"></div>