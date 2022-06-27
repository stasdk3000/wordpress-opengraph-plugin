<template>
    <div id="app">
        <!--      Loader Animation      -->
        <div class="loader__overlay"
             v-if="$store.state.loaderIsShow"
        >
            <svg class="circular" height="50" width="50" ng-show="showpreloader">
                <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="2"
                        stroke-miterlimit="10"></circle>
            </svg>
        </div>
        <!-- ============================= -->
        <b-row>
            <b-col sm="12" class="top-bar-wrap">
                <TopBar/>
            </b-col>
        </b-row>

        <!--    -->
        <div class="main-content">
            <b-row>
                <!--  Sidebar  -->
                <b-col sm="3">
                    <Sidebar/>
                </b-col>

                <!--  Content  -->
                <b-col sm="9">
                    <Content/>
                </b-col>
            </b-row>

        </div>
    </div>
</template>

<script>
    import TopBar from './admin-settings/components/TopBar.vue'
    import Sidebar from './admin-settings/components/Sidebar.vue'
    import Content from './admin-settings/components/Content.vue'

    export default {
        name: 'App',
        components: {
            TopBar,
            Sidebar,
            Content
        },
        data: () => ({}),
        methods: {},
        mounted() {
            console.log(this.$store.state.activeTab)
        },
    }
</script>

<style>
    @import '~bootstrap/dist/css/bootstrap.css';
    @import '~bootstrap-vue/dist/bootstrap-vue.css';
    @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;700;900&display=swap');

    /*   CUSTOM VARIABLES   */
    :root {
        /* Colors */
        --main-block-bg-color-light: #ff6f69;
        --text-accent-color-dark: #212529;
        --text-color: #7d7e8e;
        --content-block-title: #495057;
        --content-block-bg: #fdfffc;
        --content-block-text-color: #878d94;
        --first-accent-color: #1a8fe3;
        --error: #f28482;

        /*    border   */
        --border-radius: 5px;
        --border-line-style: 2px solid #e9ecef;

        /*   paddings   */
        --inner-block-padding: 20px 15px;

        /*    box-shadow   */
        --box-shadow-main: 0 5px 30px rgba(0, 0, 0, .1);
        --box-shadow-content-block: 7px 4px 20px 0px #e9eaea;

        /*  transition  */
        --transition-default: all .2s ease;
    }

    /*======================*/


    #app {
        font-family: 'Open Sans', sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        /*text-align: center;*/
        color: #2c3e50;
        /* border: 1px solid gray; */
        background-color: #fbfcfc;
        border-radius: var(--border-radius);
        margin-top: 20px;
        margin-right: 15px;
        padding: 0;
        box-shadow: var(--box-shadow-main);
    }

    .main-content {
        min-height: calc(100vh - 32px - 65px - 150px - 40px);
    }

    .top-bar-wrap {
        padding: 0;
    }

    .main-content .row, .main-content .col {
        height: 100%;
    }

    .row {
        margin-left: unset;
        margin-right: unset;
    }

    .inner-block-paddings {
        padding: var(--inner-block-padding);
    }

    /*  Custom Content block items  */
    .content-block-item {
        background-color: var(--content-block-bg);
        padding: 30px 25px;
        border-radius: var(--border-radius);
        box-shadow: 0px 0px 5px 0px #e9eaea;;
        height: 100%;
    }

    .content-block-item_blue {
        background-color: var(--first-accent-color);
        color: #ffffff;
    }

    .content-block-item__title {
        color: var(--content-block-title);
        font-size: 36px;
        font-weight: 700;
        line-height: 1.25;
        margin-bottom: 20px;
    }

    .content-block-item__title_blue {
        color: #ffffff;
    }

    .content-block-item__title_min {
        font-size: 21px;
    }

    .content-block-item__subtitle {
        color: var(--content-block-title);
        font-size: 16px;
        font-weight: 700;
        line-height: 1.25;
        margin: 10px 0;
    }

    .content-block-item__text {
        color: var(--content-block-text-color);
    }

    /*==============================*/

    .no-select {
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /*   Buttons   */
    .button-default-item {
        background-color: transparent;
        padding: 0 25px;
        transition: all ease .2s;
        border: none;
        color: var(--text-color);
        font-size: 16px;
        font-weight: 700;
        text-align: left;
        border-radius: var(--border-radius);
        height: 55px;
    }

    .button-default-item:hover {
        background-color: transparent;
        color: var(--first-accent-color);
    }

    .button-default-item:focus {
        box-shadow: none !important;
    }

    .button-default-item.active {
        background-color: var(--first-accent-color) !important;
        color: #ffffff !important;
    }

    .button-default-item:active {
        background-color: var(--first-accent-color) !important;
        outline: none;
        border: none;
    }


    .button-default-item__icon {
        margin-right: 15px;
    }

    /*====================================*/

    /*   Custom buttons   */
    .button__custom {
        min-width: 165px;
        height: 50px;
        padding: 0 20px;
        border: none;
        border-radius: 20px;
        font-size: 14px;
        line-height: 1.25;
        font-weight: 600;
        -webkit-transition: all .25s;
        -o-transition: all .25s;
        transition: all .2s ease;
    }

    .button__custom_blue {
        color: #ffffff;
        background-color: var(--first-accent-color);
    }

    .button__custom_blue:hover {
        background-color: #076bb3;
    }

    .button__custom_blue:focus {
        background-color: var(--first-accent-color);
    }

    .button__custom.button__custom_blue:active {
        color: #ffffff !important;
        background-color: #004692 !important;
        border-color: unset !important;
    }

    /*====================*/

    /*   Custom inputs   */
    .custom__input {
        height: 55px;
        padding: 0 20px !important;
        border-radius: 16px !important;
        border: none !important;
        background-color: #f1f1f1 !important;
        font-size: 14px !important;
        font-weight: 500 !important;
    }

    .custom__input_licence-key {
        max-width: 450px;
    }

    .custom__input_example-text {
        padding-right: 40px !important;
    }

    /*===================*/

    /* fix, turn of outline */
    a.active.focus,
    a.active:focus,
    a.focus,
    a:active.focus,
    a:active:focus,
    a:focus,
    button.active.focus,
    button.active:focus,
    button.focus,
    button:active.focus,
    button:active:focus,
    button:focus,
    .btn.active.focus,
    .btn.active:focus,
    .btn.focus,
    .btn:active.focus,
    .btn:active:focus,
    .btn:focus {
        outline: 0 !important;
        outline-color: transparent !important;
        outline-width: 0 !important;
        outline-style: none !important;
        box-shadow: 0 0 0 0 rgba(0, 123, 255, 0) !important;
    }

    /*================================================*/
    /* Slick Slider */

    .slick-track {
      display: flex;
    }

    .slick-track, .slick-list{
      padding-left: 0 !important;
      padding-right: 0 !important;
    }

    /*================================================*/
    /* Loader CSS */
    .circular {
        animation: rotate 2s linear infinite;
        height: 50px;
        left: 50%;
        position: absolute;
        top: 50%;
        transition: all .2s ease;
        transform: translate(-50%, -50%) rotate(360deg);
        width: 50px;
        z-index: 4;
    }

    .path {
        stroke-dasharray: 1, 500;
        stroke-dashoffset: 0;
        animation: dash 1.5s ease-in-out infinite, color 6s ease-in-out infinite;
        stroke-linecap: round;
        stroke: #3498db;
    }

    .loader__overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(50, 55, 60, .85);
        z-index: 1000;
    }


    @keyframes rotate {
        0% {
            transform: translate(-50%, -50%) rotate(0deg);
        }
        100% {
            transform: translate(-50%, -50%) rotate(360deg)
        }
    }

    @keyframes dash {
        0% {
            stroke-dasharray: 1, 200;
            stroke-dashoffset: 0;
        }
        50% {
            stroke-dasharray: 89, 200;
            stroke-dashoffset: -35;
        }
        100% {
            stroke-dasharray: 89, 200;
            stroke-dashoffset: -124;
        }
    }

    /*=============*/


    /*                    Media                       */

    /*================================================*/
</style>
