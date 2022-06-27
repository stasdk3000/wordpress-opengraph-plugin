<template>
    <div class="sidebar inner-block-paddings">
        <!--  Nav Menu  -->
        <nav class="sidebar-menu__nav">
            <div class="nav-wrapper">
                <ul class="sidebar-menu__list">
                    <li class="sidebar-menu__item">
                        <b-button class="sidebar-menu__item-btn button-default-item"
                                  :class="{active: get_active_tab === 'Overview'}"
                                  @click="activeTab('Overview')"
                        >
                            <b-icon icon="box" aria-hidden="true" class="button-default-item__icon"></b-icon>
                            {{ __( 'Overview', 'oggwc' ) }}
                        </b-button>
                    </li>
                    <li class="sidebar-menu__item">
                        <b-button class="sidebar-menu__item-btn button-default-item"
                                  :class="{active: get_active_tab === 'Statistic'}"
                                  @click="activeTab('Statistic')"
                        >
                            <b-icon icon="bar-chart-line" aria-hidden="true" class="button-default-item__icon"></b-icon>
                            {{ __( 'Statistics', 'oggwc' ) }}
                        </b-button>
                    </li>
                    <li class="sidebar-menu__item">
                        <b-button class="sidebar-menu__item-btn button-default-item"
                                  :class="{active: get_active_tab === 'Limits'}"
                                  @click="activeTab('Limits')"
                        >
                            <b-icon icon="clock-history" aria-hidden="true" class="button-default-item__icon"></b-icon>
                            {{ __( 'Limits', 'oggwc' ) }}
                        </b-button>
                    </li>
                    <li class="sidebar-menu__item">
                        <b-button class="sidebar-menu__item-btn button-default-item"
                                  :class="{active: get_active_tab === 'Settings'}"
                                  @click="activeTab('Settings')"
                        >
                            <b-icon icon="gear" aria-hidden="true" class="button-default-item__icon"></b-icon>
                            {{ __( 'Settings', 'oggwc' ) }}
                        </b-button>
                    </li>
                </ul>
            </div>
        </nav>

    </div>
</template>

<script>
    import Mixin from '../../mixin'

    export default {
        name: "Sidebar",
        mixins: [Mixin],
        props: {},
        data: () => ({
            tabActive: ""
        }),
        computed: {
            get_active_tab() {
                //return ( this.getStorageTabValue === null ) ? this.$store.getters.ACTIVE_TAB_STATE : this.getStorageTabValue
                return this.$store.getters.ACTIVE_TAB_STATE;
            }
        },
        methods: {
            activeTab(tab) {
                this.$store.dispatch('TOGGLE_ACTIVE_TAB', {tab: tab})
            },
            getStorageTabValue() {
                return localStorage.getItem('OGGWC_ACTIVE_TAB')
            },
        },

    }
</script>

<style scoped>
    .sidebar {
        border-right: var(--border-line-style);
        width: 100%;
        height: 100%;
        padding-left: 0;
        padding-top: 30px;
    }

    .sidebar-menu__nav {
        height: auto;
    }

    .sidebar-menu__list {
        display: flex;
        flex-direction: column;
    }

    .sidebar-menu__item-btn {
        width: 100%;
    }
</style>