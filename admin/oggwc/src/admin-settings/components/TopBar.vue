<template>
    <div class="topbar inner-block-paddings">
        <h2 class="topbar__main-title">{{plugin_name}}</h2>
        <h4 class="topbar__subtitle">
            {{plugin_v}} by
            <a :href="plugin_author_link" class="topbar__link">{{plugin_author}}</a>
        </h4>
    </div>
</template>

<script>
    export default {
        name: 'TopBar',
        props: {},
        data: () => ({
            plugin_name: 'Open Graph Generator',
            plugin_v: 'v1.0.',
            plugin_author: 'Manu:Team',
            plugin_author_link: 'https://manu.team/',
        }),
    }
</script>


<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .topbar {
        border-bottom: var(--border-line-style);
        max-width: 100%;
        min-height: 110px;
        width: 100%;
        height: 100%;
    }

    .topbar__main-title {
        font-weight: 500;
        margin-bottom: 15px;
    }

    .topbar__subtitle {
        font-size: 14px;
        font-weight: 300;
    }

    .topbar__link {
        color: var(--first-accent-color);
        text-decoration: none;
    }

    .topbar__link:hover {
        font-weight: 400;
        color: #0085e4;
    }
</style>
