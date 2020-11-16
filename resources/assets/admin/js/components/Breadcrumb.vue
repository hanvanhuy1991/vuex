<template>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ name || $t('common.dashboard') }} </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item" v-for="(item, index) in list" :key="index">
                        <!-- <span class="active" v-if="isLast(index)">{{ showName(item) }}</span> -->
                        <router-link :to="item">{{ showName(item) }}</router-link>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    name: 'AppBreadcrumd',
    props: {
        list: {
            type: Array,
            required: true,
            default: () => [],
        },
    },

    computed: {
        name() {
            return this.$route.name;
        },
    },

    methods: {
        isLast(index) {
            return index !== 0;
        },

        showName(item) {
            let name = item.name;
            if (item.meta && item.meta.label) {
                name = item.meta.label;
            }

            return name;
        },

        setNavigation(navigation) {
            this.$store.commit('SET_NAVIGATION', {
                navigation: navigation
            });
        },
    },
};
</script>
