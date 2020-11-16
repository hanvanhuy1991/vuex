<template>
<aside class="main-sidebar sidebar-dark-primary elevation-4" style="">
    <a href="/" class="brand-link">
        <img src="/dist/img/AdminLTELogo.png" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">comuni</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <router-link to="/" :class="{'active': activeAside == 'dashboard'}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            {{ $t('common.dashboard') }}
                        </p>
                    </router-link>
                </li>

                <li class="nav-item">
                    <router-link to="/accounts" :class="{'active': activeAside == 'users'}" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            {{ $t('common.account_management') }}
                        </p>
                    </router-link>
                </li>

                <li class="nav-item">
                    <router-link to="/posts" :class="{'active': activeAside == 'posts'}" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            {{ $t('common.post_management') }}
                        </p>
                    </router-link>
                </li>

                <li class="nav-item">
                    <router-link to="/" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            {{ $t('common.ads_management') }}
                        </p>
                    </router-link>
                </li>

                <li class="nav-item">
                    <router-link to="/" class="nav-link">
                        <i class="nav-icon fas fa-flag"></i>
                        <p>
                            {{ $t('common.report_center') }}
                        </p>
                    </router-link>
                </li>

                <li class="nav-item">
                    <router-link to="/" class="nav-link">
                        <i class="nav-icon fas fa-exclamation"></i>
                        <p>
                            {{ $t('common.notifications') }}
                        </p>
                    </router-link>
                </li>

                <li class="nav-item">
                    <router-link to="/" class="nav-link">
                        <i class="nav-icon fas fa-coins"></i>
                        <p>
                            {{ $t('common.financial') }}
                        </p>
                    </router-link>
                </li>
                <li class="nav-header">{{ $t('common.system') }}</li>
                <li class="nav-item">
                    <router-link to="/" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            {{ $t('common.user_management') }}
                        </p>
                    </router-link>
                </li>

                <li class="nav-item">
                    <router-link to="/" class="nav-link">
                        <i class="nav-icon fas fa-edit"></i>
                        <p>
                            {{ $t('common.system_configuration') }}
                        </p>
                    </router-link>
                </li>
            </ul>
        </nav>
    </div>
</aside>
</template>

<script>
import {
    mapState
} from 'vuex';

export default {
    name: 'AppAside',
    computed: {
        ...mapState({
            activeAside: state => state.storeAside.activeAside,
        })
    },
};
</script>

