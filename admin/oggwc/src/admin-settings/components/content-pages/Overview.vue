<template>
    <div class="overview">
        <b-row>
            <b-col cols="6">
                <div class="content-block-item overview__updates">
                    <div class="content-block-item__title content-block-item__title_min">
                        {{ __( 'How_It_Works', 'oggwc' ) }}
                    </div>
                    <div v-html="hiw" class="text-regular"></div>
                </div>
            </b-col>
            <b-col cols="6">
                <div class="content-block-item overview__support">
                    <div class="content-block-item__title content-block-item__title_min">
                        {{ __( 'Support', 'oggwc' ) }}
                    </div>
                    <div class="overview__support-content">
                        <div class="overview__support-contact-wrap">

                            <!--   e-mail   -->
                            <a :href="emailLink" class="overview__support-contact-item" target="_blank">
                                <div class="overview__support-contact-icon-wrap">
                                    <unicon name="envelope" fill="var(--first-accent-color)" width="26" height="26"/>
                                </div>
                                <div class="overview__support-contact-item-title">
                                    {{ email }}
                                </div>
                            </a>

                            <!--   site support   -->
                            <a :href="siteLink" class="overview__support-contact-item" target="_blank">
                                <div class="overview__support-contact-icon-wrap">
                                    <unicon name="comment-question" fill="var(--first-accent-color)" width="26"
                                            height="26"/>
                                </div>
                                <div class="overview__support-contact-item-title">
                                    {{ site }}
                                </div>
                            </a>
                        </div>

                        <div class="overview__support-contact-notice">
                            {{ __( 'You_have_been_support', 'oggwc' ) }}
                        </div>
                    </div>
                </div>
            </b-col>
        </b-row>
    </div>
</template>

<script>
    import Mixin from '../../../mixin'

    export default {
        name: "Overview",
        mixins: [Mixin],
        data: () => ({
            emailLink: 'mailto:support@manu.team',
            email: 'support@manu.team',
            siteLink: 'https://manu.team/support',
            site: 'manu.team/support',
            oavar: [],
            hiw: "",
        }),
        created() {
            // init global js WP var
            this.oavar = window.oggwc_admin;
            this.hiw = this.oavar.oggwc_hiw;
        },
        methods: {}
    }
</script>

<style scoped>
    .overview__top-text {
        margin-bottom: 25px;
    }

    .overview__support-contact-wrap {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 25px;
    }

    .overview__support-contact-item {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        border: var(--border-line-style);
        border-radius: 8px;
        padding: 15px 10px;
        transition: var(--transition-default);
        text-decoration: none;
        color: var(--first-accent-color);
    }

    .overview__support-contact-item:hover {
        cursor: pointer;
        background-color: var(--first-accent-color);
        color: #ffffff;
    }

    .overview__support-contact-notice {
        font-size: 16px;
        font-weight: 500;
        color: var(--text-color);
        text-align: center;
    }

    .overview__support-contact-item:hover .overview__support-contact-icon-wrap {
        background-color: #ffffff;
    }

    .overview__support-contact-item:not(:last-child) {
        margin-right: 10px;
    }

    .overview__support-contact-icon-wrap {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #f1f1f1;;
        margin-bottom: 5px;
    }

    /*                    Media                       */
    @media screen and (max-width: 1468px) {
        .overview__support-contact-wrap {
            flex-direction: column;
        }

        .overview__support-contact-item {
            min-width: 200px;
        }

        .overview__support-contact-item:not(:last-child) {
            margin-bottom: 10px;
            margin-right: 0;
        }
    }

    /*================================================*/
</style>