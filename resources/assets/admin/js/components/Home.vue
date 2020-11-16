<template>
<div class="wrapper">
    <div class="splash-screen" v-show="mainLoading.show">
        <div class="splash-overlay" v-show="mainLoading.show"></div>
        <div class="splash-spinner"></div>
    </div>
    <app-navbar></app-navbar>
    <app-aside></app-aside>
    <div class="content-wrapper">
        <app-breadcrumd :list="listRoute"></app-breadcrumd>
        <section class="content">
            <div class="container-fluid">
                <router-view></router-view>
            </div>
        </section>
    </div>
    <app-footer></app-footer>
    <profile-modal></profile-modal>
    <change-password></change-password>
    <div class="control-sidebar-bg"></div>
</div>
</template>

<script>
import AppNavbar from '@/components/Navbar';
import AppAside from '@/components/aside/views/Aside';
import AppBreadcrumd from '@/components/Breadcrumb';
import AppFooter from '@/components/Footer';
import ProfileModal from '@/components/authentication/views/ProfileModal';
import ChangePassword from '@/components/authentication/views/ChangePassword';

import {
    mapState
} from 'vuex';

export default {
    name: 'Home',
    components: {
        AppNavbar,
        AppAside,
        AppBreadcrumd,
        AppFooter,
        ProfileModal,
        ChangePassword
    },
    computed: {
        ...mapState({
            user: state => state.storeAuth.user,
            mainLoading: state => state.storeLoading.mainLoading
        }),
        listRoute() {
            return this.$route.matched;
        },
    },

    /**
     * Created with component
     */
    created() {
        /**
         * Do something
         */
    }
};
</script>
