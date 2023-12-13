<style>
    .offer-wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        grid-gap: 37.91px 16px;
        padding: 0 8px;
        margin-bottom: 29.02px;
    }
    .offer-wrapper.no-result {
        display: block;
    }
    .offer-wrapper .no-result {
        padding: 8px 0;
        font-size: 'Helvetica Neue';
        letter-spacing: 3.73px;
        text-align: center;
        color: #57524F;
    }
    .offer-wrapper .no-result .sleeping-bear {
        width: 180px;
        margin: 24px auto;
    }
    .offer-wrapper .no-result .sleeping-bear::after {
        content: ' ';
        padding-top: 75.52%;
        background-image: url("{{ asset('website/sleeping_bear.png') }}");
        background-repeat: no-repeat;
        background-position: center center;
        background-size: contain;
        display: block;
    }
    .offer-wrapper .offer-card .image::before {
        padding-top: 49%;
    }
    @media (max-width: 767px) {
        .offer-wrapper {
            padding: 0 16px;
            grid-template-columns: 1fr 1fr;
        }
    }
    .offer-wrapper.hot-offer {
        position: relative;
        margin-bottom: 180px;
    }
    {{--
    /* .offer-wrapper.hot-offer .offer-card-wrapper {
        display: none;
    }
    .offer-wrapper.hot-offer .offer-card-wrapper:nth-child(1),
    .offer-wrapper.hot-offer .offer-card-wrapper:nth-child(2),
    .offer-wrapper.hot-offer .offer-card-wrapper:nth-child(3) {
        display: block;
    }
    @media (max-width: 767px) {
        .offer-wrapper.hot-offer .offer-card-wrapper:nth-child(4) {
            display: block;
        }
    } */
    --}}
    .offer-wrapper.hot-offer .show-all-button {
        position: absolute;
        bottom: -90px;
        left: 0;
        transform: translateY(50%);
        width: calc(100% - (16px * 2));
        padding: 8px 0;
        margin: 0 16px;
        border: 1px solid #FBCB30;
        border-radius: 30px;
        font-size: 20px;
        letter-spacing: 6px;
        line-height: 30px;
        text-align: center;
        color: #F37621;
        cursor: pointer;
    }
    .offer-wrapper.hot-offer.show-all {
        margin-bottom: 30px;
    }
    {{--
    /* .offer-wrapper.hot-offer.show-all .offer-card-wrapper {
        display: block;
    }
    .offer-wrapper.hot-offer.show-all .show-all-button {
        display: none;
    } */
    --}}
</style>