<template>
<div class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <b>CMS</b>comuni
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
                <p class="login-box-msg" v-if="sendMail" style="color:#28a745">Sent email for Forgot Password successfully!</p>
                <form  v-on:keydown.enter.prevent="submit()">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" v-model.trim="information.email"  v-bind:class="{ 'is-invalid': errorsForgot && objectNotEmpty(errorsForgot.email)}" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        <span id="exampleInputPassword1-error" v-if=" errorsForgot && objectNotEmpty(errorsForgot.email)" class="error invalid-feedback">{{ errorsForgot.email[0] }}</span>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="button" class="btn btn-primary btn-block"  :disabled="isDisableForgotPass" @click="submit()">
                                Request new password
                            </button>
                        </div>
                    </div>
                </form>
                <p class="mt-3 mb-1">
                <router-link to="login">Login</router-link>
                </p>
            </div>
        </div>
    </div>

</div>
</template>

<script>
import {
    mapState
} from 'vuex';


export default {
    name: 'ForgotPassword',

    data: function () {
        return {
            information: {
                'email': null
            }
        };
    },

    created() {
        this.$store.commit('AUTH_SET_ERROR_FORGOT_PASS', null );
    },

    computed: {
        ...mapState({
            isDisableForgotPass: state => state.storeAuth.isDisableForgotPass,
            sendMail: state => state.storeAuth.sendMail,
            errorsForgot: state => state.storeAuth.errorsForgot ? state.storeAuth.errorsForgot.error : state.storeAuth.errorsForgot,
        }),
    },

    watch: {
        'information.email'() {
            if (this.errorsForgot) {
                this.errorsForgot.email = null;
            }

            this.disableMessage();
        },
    },

    methods: {
        async submit() {
            await this.$store.dispatch('actionForgotPassword', {
                vue: this,
                params: this.information,
            });

        },

        disableMessage()
        {
            this.$store.commit('AUTH_SET_SENT_MAIL_SUCCESS', false );
        },

        objectNotEmpty(object) {
            return !_.isEmpty(object);
        },
    }
};
</script>
