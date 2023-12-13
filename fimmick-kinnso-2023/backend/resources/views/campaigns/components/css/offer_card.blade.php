<style>
    .offer-card-wrapper {
        position: relative;
    }
    .offer-card-wrapper a {
        color: inherit;
        text-decoration: none;
    }
    .offer-card-wrapper a:hover {
        color: inherit;
    }
    .offer-card-wrapper .label-wrapper {
        position: absolute; 
        left: 4%;
        top: 0;
        width: 90%;
        display: flex;
    }
    .offer-card-wrapper .label {
        width: 40px;
        margin: 0 8px 8px 0;
        transform: translateY(-20%);
    }
    .offer-card-wrapper .label::before {
        content: ' ';
        padding-top: 54.64%;
        background-repeat: no-repeat;
        background-position: center center;
        background-size: contain;
        display: block;
    }
    .offer-card-wrapper .label {
        image-rendering: -webkit-optimize-contrast;
    }
    .offer-card-wrapper .label.hot-label::before {
        background-image: url("{{ asset('website/hot_tag.png') }}");
    }
    .offer-card-wrapper .label.new-label::before {
        background-image: url("{{ asset('website/new_tag.png') }}");
    }
    .offer-card-wrapper .label.kinso-label::before {
        background-image: url("{{ asset('website/kin_so_tag.png') }}");
    }
    .offer-card-wrapper .label.litter-label::before {
        background-image: url("{{ asset('website/little_tag.png') }}");
    }
    .offer-card-wrapper .label.soldout-label::before {
        background-image: url("{{ asset('website/soldout_tag.png') }}");
    }
    .offer-card-wrapper .label.end-label::before {
        background-image: url("{{ asset('website/end_tag.png') }}");
    }
    .offer-card {
        height: 100%;
        background: #FFF;
        box-shadow: 0px 1px 6px #00000029;
        border: 1px solid #DBDBDB;
        border-radius: 8px;
        overflow: hidden;

        cursor: pointer;
    }
    .offer-card .image::before {
        content: ' ';
        background-image: var(--background-image);
        background-repeat: no-repeat;
        background-position: center center;
        background-size: cover;
        display: block;
    }
    .offer-card .tagging {
        padding: 4px 8px 0 8px;
        overflow: hidden;
    }
    .offer-card .tagging ul {
        padding: 0;
    }
    .offer-card .tagging ul li {
        font: normal normal medium 14px/19px Noto Sans;
        letter-spacing: 0.35px;
        list-style: disc;
        list-style-position: inside;
        float: left; 
    }
    .offer-card .tagging ul li::after {
        content: attr(data-text);
        position: relative;
        left: -6px;
    }
    .offer-card .title {
        padding: 4px 8px 12px 8px;
        font: normal normal bold 16px/22px Noto Sans;
        letter-spacing: 0.4px;
        color: #707070;
    }
</style>