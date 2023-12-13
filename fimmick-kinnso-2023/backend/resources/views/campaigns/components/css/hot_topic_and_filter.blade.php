<!-- hot topic and filter -->
<style>
    .hot-topic-wrapper {
        padding: 0 24px;
        margin: 26.84px 0;
        display: flex;
        justify-content: center;
    }
    .hot-topic-wrapper::before {
        content: attr(data-text);
        min-width: 100px;
        color: #F37621;
        font: normal normal bold 16px/22px Noto Sans;
        letter-spacing: 1.6px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .hot-topic-list {
        min-width: 480px;
        margin-right: 12px;
        display: flex;
        flex-wrap: wrap;
        scrollbar-width: thin;
        scrollbar-color: rgba(243, 118, 33, 0.7) #F8F8F8;
    }
    @media (max-width: 767px) {
        .hot-topic-list {
            min-width: initial;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            overflow-x: auto;
        }
        .hot-topic-list::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }
        .hot-topic-list::-webkit-scrollbar-track {
            box-shadow: inset 0 0 5px #EEE;
            border-radius: 10px;
        }
        .hot-topic-list::-webkit-scrollbar-thumb {
            background: rgba(243, 118, 33, 0.7);
            border-radius: 10px;
        }
    }
    .hot-topic-list a.hot-topic {
        min-width: 96px;
        padding: 0 8px;
        margin: 6px 0 6px 12px;
        border: 1px solid var(--color, #F37621);
        border-radius: 16px;
        color: var(--color, #F37621);
        font: normal normal normal 16px/22px Noto Sans;
        letter-spacing: 0.8px;
        line-height: 22px;
        text-align: center;
        text-decoration: none;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
        display: inline-block;
    }
    .hot-topic-list a.hot-topic::before {
        content: attr(data-text);
    }
    .hot-topic-list a.hot-topic:hover,
    .hot-topic-list a.hot-topic.active {
        background: var(--color, #F37621);
        color: #FFF;
    }
    .hot-topic-wrapper .filter-button {
        position: relative;
        width: 26px;
        min-width: 26px;
        margin: 0 12px;
        display: inline-block;
        cursor: pointer;
    }
    .hot-topic-wrapper .filter-button::before {
        content: ' ';
        position: relative;
        top: 50%;
        transform: translateY(-50%);
        padding-top: 75%;
        background-image: url("{{ asset('website/filter.png') }}");
        background-repeat: no-repeat;
        background-position: center center;
        background-size: contain;
        display: block;
        z-index: 2;
    }
    @media (max-width: 767px) {
        .hot-topic-wrapper .filter-button::before {
            z-index: 0;
        }
    }
    .hot-topic-wrapper .filter-button::after {
        content: attr(data-count);
        position: absolute;
        top: 0;
        right: 0;
        transform: translate(100%, -25%);
        font-size: 14px;
        font-weight: bold;
        color: #FCCC08;
    }
    .hot-topic-container {
        position:relative;
    }
    .hot-topic-wrapper .filter-button.active .filter-wrapper {
        transform: translateY(0%);
        opacity: 1;
        display: block;
    }
    .filter-wrapper {
        position: absolute; 
        right: -8px;
        top: calc(50% + 24px);
        width: 100vw;
        max-width: 680px;
        padding: 24px 32px;
        background: #FFF;
        border: 2px solid #FCCC08;
        border-radius: 32px 0 32px 0;
        z-index: 10;
        transition: transform .5s, opacity .5s;
        transform: translateY(50%);
        opacity: 0;
        box-shadow: 0 0 10px rgba(0, 0, 0, .3);
        display: none;
    }
    .filter-wrapper::after {
        content: ' ';
        position: absolute;
        right: -2px;
        top: 0;
        width: 42px;
        height: 48px;
        background: #FCCC08;
        border-radius: 20px 20px 0 0;
        transform: translateY(-100%);
        opacity: 0.3;
        pointer-events: none;
    }
    .filter-wrapper .close-button {
        display: none;
    }
    @media (max-width: 767px) {
        .filter-wrapper {
            position: fixed;
            right: 0;
            bottom: 0;
            top: initial;
            width: 100%;
            max-width: initial;
            border: 0;
            border-radius: 48px 48px 0 0;
        }
        .filter-wrapper::before {
            content: ' ';
            position: absolute;
            left: 50px;
            top: 1px;
            width: 118.5px;
            height: 75.5px;
            background-image: url("{{ asset('website/filter_bear_v1.png') }}");
            background-repeat: no-repeat;
            background-position: center center;
            background-size: contain;
            transform: translateY(calc(-100% + 4px));
        }
        .filter-wrapper::after {
            display: none;
        }
        .filter-wrapper .close-button {
            position: absolute;
            right: 40px;
            top: 20px;
            width: 40px;
            height: 40px;
            border: 3px solid #F5CD47;
            border-radius: 100%;
            display: block;
            z-index: 2;
        }
        .filter-wrapper .close-button::before,
        .filter-wrapper .close-button::after {
            content: ' ';
            position: absolute;
            left: 50%;
            top: 50%;
            width: 3px;
            height: 23px;
            background: #F5CD47;
            border-radius: 4px;
        }
        .filter-wrapper .close-button::before {
            transform: translate(-50%, -50%) rotate(45deg);
        }
        .filter-wrapper .close-button::after {
            transform: translate(-50%, -50%) rotate(-45deg);
        }
    }
    .filter-title {
        position: relative;
        margin-bottom: 8px;
        text-align: center;
        line-height: 60px;
        font: normal normal 800 22px/30px Noto Sans;
        letter-spacing: 1.1px;
        color: #F37621;
    }
    .filter-title::after {
        content: attr(data-text);
    }
    .filter-category-list {
        max-height: 600px;
        overflow-y: scroll;
        scrollbar-width: thin;
        scrollbar-color: rgba(243, 118, 33, 0.7) #F8F8F8;
    }
    .filter-category-list::-webkit-scrollbar {
        width: 4px;
        height: 4px;
    }
    .filter-category-list::-webkit-scrollbar-track {
        box-shadow: inset 0 0 5px #EEE;
        border-radius: 10px;
    }
    .filter-category-list::-webkit-scrollbar-thumb {
        background: rgba(243, 118, 33, 0.7);
        border-radius: 10px;
    }
    @media (max-width: 767px) {
        .filter-category-list {
            max-height: 45vh;
        }
    }
    .filter-category {
        margin-bottom: 8px;
    }
    .filter-category::before {
        content: attr(data-text);
        margin-bottom: 8px;
        font: normal normal normal 16px/22px Noto Sans;
        letter-spacing: 0.8px;
        display: block;
    }
    .filter-sub-category-list {
        display: flex;
        flex-wrap: wrap;
    }
    a.filter-sub-category {
        padding: 0 16px;
        margin: 0 8px 8px 0;
        border: 1px solid #CCC;
        border-radius: 30px;
        text-decoration: none;
        line-height: 22px;
        font: normal normal normal 16px/22px Noto Sans;
        letter-spacing: 0.8px;
        color: #57524F;
        display: inline-block;
    }
    a.filter-sub-category:hover, 
    a.filter-sub-category.active {
        background: #F37621;
        border: 1px solid #F37621;
        color: #FFF;
    }
    .filter-sub-category::before {
        content: attr(data-text);
    }
    .filter-wrapper .button-wrapper {
        margin: 32px 0 16px 0;
        display: flex;
    }
    .filter-wrapper .button-wrapper .button {
        padding: 8px 24px;
        margin: 0 4%;
        border: 1px solid #CCC;
        border-radius: 30px;
        text-decoration: none;
        text-align: center;
        color: #57524F;
        letter-spacing: 4.8px;
        display: block;
        flex: 1 1 0;
    }
    .filter-wrapper .button-wrapper .button:hover {
        background: #CCC;
        color: #FFF;
    }
    @media (max-width: 767px) {
        .filter-wrapper .button-wrapper {
            margin: 16px 0 8px 0;
            flex-direction: column;
        }
        .filter-wrapper .button-wrapper .button {
            margin: 2% 0;
        }
    }
    .filter-wrapper .button-wrapper .button::before {
        content: attr(data-text);
    }
    .filter-wrapper .button-wrapper .search-button {
        color: #F37621;
        border-color: #F37621;
    }
    .filter-wrapper .button-wrapper .search-button:hover {
        background: #F37621;
    }
    #filter-mask {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, .3);
        z-index: 2;
        display: none;
    }
</style>
<!-- offer category -->
<style>
    .offer-category-wrapper {
        margin-bottom: 24px;
        display: flex;
        justify-content: center;
    }
    .offer-category-wrapper a.category {
        position: relative;
        width: 84px;
        margin: 0 16px;
        display: inline-block;
    }
    .offer-category-wrapper a.category::before {
        content: ' ';
        position: relative;
        padding-top: 100%;
        background-image: var(--background-image);
        background-repeat: no-repeat;
        background-position: center center;
        background-size: contain;
        display: block;
    }
    .offer-category-wrapper a.category.active::before,
    .offer-category-wrapper a.category:hover::before {
        background-image: var(--hover-background-image);
    }
    .offer-category-wrapper a.category::after {
        content: attr(data-text);
        position: relative;
        bottom: -6px;
        width: 100%;
        font: normal normal normal 16px/22px Noto Sans;
        letter-spacing: 0.8px;
        color: #57524F;
        text-align: center;
        display: inline-block;
        transform: translateY(-50%);
    }

</style>

<style>
    .offer-category-wrapper-new {
        margin-bottom: 24px;
        display: flex;
        justify-content: center;
    }
    
    .offer-category-wrapper-new a.category {
        position: relative;
        width: 85px;
        margin: 0 26px;
        display: inline-block;
    }
    .offer-category-wrapper-new a.category::before {
        content: attr(data-text);
        position: relative;
        width: 100%;
        padding-top: 100%;
        font: normal normal normal 16px/40px Noto Sans;
        letter-spacing: 0.8px;
        background-image: var(--background-image);
        background-repeat: no-repeat;
        background-position: center center;
        background-size: contain;
        display: inline-block;
        color: #57524F;
        text-align: center;
    }
    .offer-category-wrapper-new a.category.active::before,
    .offer-category-wrapper-new a.category:hover::before {
        background-image: var(--hover-background-image);
    }
    .offer-category-wrapper-new a.category::after{
        content: attr(data-point);
        position: relative;
        width: 100%;
        font: normal normal normal 17px/35px Arial;
        letter-spacing: normal;
        color: #f6b401;
        text-align: center;
        display: inline-block;
        transform: translateY(-40%);
        background-image: url("{{ asset('website/point_pages_image/icon00_p_point.png') }}");
        background-repeat: no-repeat;
        background-size: 18px;
        background-position: 85% 50%;
    }

</style>