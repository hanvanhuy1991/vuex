<template>
<div class="wrapper">
    <router-view></router-view>
</div>
</template>

<script>
import { mapState } from 'vuex';
import ConfigAxios from '@/library/axios';

export default {
    name: 'App',

    computed: {
        ...mapState({
            token: state => state.storeAuth.token,
            user: state => state.storeAuth.user
        }),
    },

    async created() {
        if (this.token) {
            const tokenType = this.token.token_type;
            const accessToken = this.token.access_token;

            ConfigAxios.setAuthorizationHeader(tokenType, accessToken);
        }
    },
};
</script>
