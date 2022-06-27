import Vue from 'vue'
import App from './App.vue'
import store from './store'
import Mixin from './mixin'
import {BootstrapVue, IconsPlugin} from 'bootstrap-vue'
import Unicon from 'vue-unicons'
import {
    uniFacebook,
    uniFacebookF,
    uniVk,
    uniTwitter,
    uniTwitterAlt,
    uniTrashAlt,
    uniLink,
    uniSync,
    uniImagePlus,
    uniSearch,
    uniEnvelope,
    uniCommentQuestion,
    uniImage
} from 'vue-unicons/src/icons'

Unicon.add([uniFacebook, uniFacebookF, uniVk, uniTwitter, uniTwitterAlt, uniTrashAlt, uniLink, uniSync, uniImagePlus, uniSearch, uniEnvelope, uniCommentQuestion, uniImage])
Vue.use(Unicon)

// Install BootstrapVue
Vue.use(BootstrapVue)

// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)

// import router from '@/admin-settings/router/index'

Vue.config.productionTip = false

new Vue({
    // router,
    el: '#oggwc-vue-admin',
    mixins: [Mixin],
    render: h => h(App), store
}).$mount('#app')
