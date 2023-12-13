<style>
    .kinnso-bear {
        position: fixed;
        bottom: 0;
        right: 0;
        width: 60px;
        z-index: 100;
        cursor: pointer;
    }
    .kinnso-bear::before {
        content: ' ';
        padding-top: 206%;
        background-image: url("{{ asset('website/cs_bear.png') }}");
        background-repeat: no-repeat;
        background-position: center center;
        background-size: contain;
        display: block;
    }
</style>