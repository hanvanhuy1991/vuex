import Vue from 'vue';
import App from './App.vue';
import Router from 'vue-router';
import Toasted from 'vue-toasted';
import routes from './router';
import i18n from './lang/i18n';
import User from '@/library/User';
import store from './store';

import 'viewerjs/dist/viewer.css';
import Viewer from 'v-viewer';

require('./library/boostrap');

Vue.use(Router);
Vue.use(Toasted);
Vue.use(Viewer);

const router = new Router({
    mode: 'history',
    routes,
});

if (store.state.storeAuth.user) {
    Vue.prototype.$user = new User(store.state.storeAuth.user);
}

router.beforeEach((to, from, next) => {
    const isLoggedIn = store.getters['isLoggedIn'];
    if (to.matched.some(record => record.meta.requiresAuth)) {
        const userRole = store.getters['getUserRole'];
        var isAccessDeny;

        if (!isLoggedIn) {
            return next({
                path: '/login'
            });
        }

        if (to.matched.some(record => record.meta.accessedBy)) {
            isAccessDeny = true;
        }

        userRole.forEach(element => {
            if (to.matched.some(record => record.meta.accessedBy && record.meta.accessedBy.indexOf(element.name) !== -1)) {
                isAccessDeny = false;
                return next();
            }
        });

        if (isAccessDeny === true) {
            return next({
                path: '/'
            });
        }
    } else if (isLoggedIn) {
        return next({ path: '/' });
    }

    // if (store.state.storeLoading.errorPage.status) {
    //     store.dispatch('setErrorPage', {
    //         vue: this,
    //         error: {
    //             status: false,
    //             code: null,
    //         },
    //     });
    // }
    // const toPath = to.path;
    // if (toPath === '/') {
    //     store.commit('SET_NAVIGATION', { navigation: 'dashboard' });
    // } else {
    //     store.commit('SET_NAVIGATION', { navigation: Helper.pathToNavigation(toPath) });
    // }

    return next();
});

new Vue({
    el: '#admin',
    render: h => h(App),
    router,
    i18n,
    store
});