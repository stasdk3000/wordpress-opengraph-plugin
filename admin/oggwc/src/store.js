import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const store = new Vuex.Store ({
    state: {
        activeTab: 'Overview',
        loaderIsShow: false,
    },
    mutations: {
        CHANGE_ACTIVE_TAB: (state, data) => {
            state.activeTab = data.tab
        }
    },
    actions: {
        TOGGLE_ACTIVE_TAB(context, data) {
            localStorage.setItem("OGGWC_ACTIVE_TAB", data.tab)

            context.commit('CHANGE_ACTIVE_TAB', data)
        }
    },
    getters: {
        ACTIVE_TAB_STATE(state) {
            return state.activeTab
        }
    }
})

export default store;