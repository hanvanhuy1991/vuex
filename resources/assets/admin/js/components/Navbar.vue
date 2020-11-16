<template>
<nav class="main-header navbar navbar-expand navbar-dark navbar-primary">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">15</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">15 {{ $t('common.notifications') }}</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-envelope mr-2"></i> 4 new messages
                    <span class="float-right text-muted text-sm">3 mins</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-users mr-2"></i> 8 friend requests
                    <span class="float-right text-muted text-sm">12 hours</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-file mr-2"></i> 3 new reports
                    <span class="float-right text-muted text-sm">2 days</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">{{ $t('common.see_all_notifications') }}</a>
            </div>
        </li>
        <li class="dropdown user user-menu open">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                <img :src=" currentLanguage == 'en' ? '/admin/image/en.png' : '/admin/image/jp.png'" style="height: 1.1rem; width: 1.1rem;" class="user-image img-circle elevation-2" alt="English">
                <span class="hidden-xs">{{ currentLanguage == 'en' ? $t('common.english') : $t('common.japanese') }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="width: 10em; min-width: unset; background: #007bff!important;">
                <li class="" v-if="currentLanguage == 'en'">
                    <a href="#" @click="setLang('jp')" class="nav-link">
                        <img src="/admin/image/jp.png" style="height: 1.1rem; width: 1.1rem;" class="user-image img-circle elevation-2" alt="Janpanese">
                        <span class="hidden-xs">{{ $t('common.japanese') }}</span>
                    </a>
                </li>
                <li class="" v-if="currentLanguage == 'jp'">
                    <a href="#" @click="setLang('en')" class="nav-link">
                        <img src="/admin/image/en.png" style="height: 1.1rem; width: 1.1rem;" class="user-image img-circle elevation-2" alt="Janpanese">
                        <span class="hidden-xs">{{ $t('common.english') }}</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="dropdown user user-menu open">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                <img v-if="user" :src="user.avatar ? user.avatar : 'https://s3-comuni.s3-ap-southeast-1.amazonaws.com/file/1599188010/avatar1.jpg'" class="user-image img-circle elevation-2" alt="User Image">
                <span v-if="user" class="hidden-xs">{{ user.name }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <li class="user-header bg-primary">
                    <p>
                        {{ user ? user.email : null  }}
                        <small v-if="user"> {{ $t('common.created_about') }} : {{user.created_at}}</small>
                    </p>
                </li>
                <li class="user-body">
                    <div class="row">
                        <div class="col-12 text-center">
                            <a href="#"  data-toggle="modal" @click="getProfile()" data-target="#modal-default">{{ $t('common.profile') }}</a>
                        </div>
                    </div>
                </li>
                <li class="user-body">
                    <div class="row">
                        <div class="col-12 text-center">
                            <a href="#"  data-toggle="modal" @click="clearErrorChangePassword()" data-target="#modal-changepassword">{{ $t('common.change_password') }}</a>
                        </div>
                    </div>
                </li>
                <li class="user-body">
                    <div class="row">
                        <div class="col-12 text-center">
                            <a href="#" @click="logoutAndRedirect()"> {{ $t('common.logout')}}</a>
                        </div>
                    </div>
                </li>
            </ul>
        </li>
    </ul>
</nav>
</template>

<script>
import {
    mapState
} from 'vuex';
import Cookies from 'js-cookie';

export default {
    name: 'AppNavbar',

    computed: {
        ...mapState({
            user: state => state.storeAuth.user,
            logout: state => state.storeAuth.logout,
            currentLanguage: state => state.storeLang.current
        }),
    },

    methods: {
        async logoutAndRedirect() {
            await this.$store.dispatch('actionLogout', {
                vue: this,
            });
            if (this.logout) {
                this.$router.push({
                    path: '/login',
                });
                localStorage.clear();
                return Cookies.remove('admin');
            }
        },

        setLang(lang) {
            this.$store.dispatch('setLang', {
                vue: this,
                lang
            });
        },

        async getProfile() {
            await this.$store.dispatch('actionGetProfile', {
                vue: this,
            });

            this.$store.commit('DISABLE_INPUT', true);
        },

        clearErrorChangePassword() {
            this.$store.commit('AUTH_SET_ERROR_CHANGE_PASSWORD', null);
        },
    },
};
</script>

<style lang="css">
.navbar-nav>.user-menu>.dropdown-menu>li.user-header {
    height: unset !important
}
</style>
