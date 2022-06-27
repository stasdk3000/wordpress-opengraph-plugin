<template>
    <div class="content inner-block-paddings">
        <Settings v-if="$store.state.activeTab === 'Settings'"/>
        <Limits v-if="$store.state.activeTab === 'Limits'"/>
        <Statistic v-if="$store.state.activeTab === 'Statistic'"/>
        <Overview v-if="$store.state.activeTab === 'Overview'"/>
    </div>
</template>

<script>
    import Settings from '@/admin-settings/components/content-pages/Settings.vue'
    import Limits from '@/admin-settings/components/content-pages/Limits.vue'
    import Statistic from '@/admin-settings/components/content-pages/Statistic.vue'
    import Overview from '@/admin-settings/components/content-pages/Overview.vue'

    export default {
        name: "Content",
        components: {
            Settings,
            Limits,
            Statistic,
            Overview
        },
        props: {},
        data: () => ({}),
    }
</script>

<style scoped>
    .content {
        width: 100%;
        height: 100%;
        padding-left: 0;
        padding-top: 30px;
    }
</style>