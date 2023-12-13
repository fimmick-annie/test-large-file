<style>
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
        /* left: 86.26px; */
        left: 50%;
        top: 50%;
        width: 100%;
        max-width: 132px;
        display: block;
        -webkit-transform: translate(0, -50%);
        -ms-transform: translate(0, -50%);
        transform: translate(0, -50%);
    }

    @media (min-width:1201px) {
        .logo {
            left: calc(((100vw - 1200px) / 2 + 71.26px));
        }
    }

    @media (max-width: 767px) {
        .logo {
            left: 50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
        }
        .logo__desktop {
            display: none;
        }
    }

    @media (min-width: 768px) {
        .logo__mobile {
            display: none;
        }
    }
</style>