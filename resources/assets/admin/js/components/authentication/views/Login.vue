<template>
<div class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <b>CMS</b>comuni
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <p class="login-box-msg" v-if="errors && errors.status" style="color:#dc3545">{{errors.message}}</p>
                <form v-on:keydown.enter.prevent="redirectToAdmin()">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" v-bind:class="{ 'is-invalid': errors && objectNotEmpty(errors.email)}" aria-describedby="exampleInputEmail1-error" aria-invalid="true" v-model.trim ="information.email" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        <span id="exampleInputEmail1-error" v-if="errors && objectNotEmpty(errors.email)" class="error invalid-feedback">{{ errors.email[0] }}</span>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" class="form-control" v-bind:class="{ 'is-invalid': errors && objectNotEmpty(errors.password)}" aria-describedby="exampleInputPassword1-error" aria-invalid="true" v-model.trim ="information.password" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <span id="exampleInputPassword1-error" v-if="errors && objectNotEmpty(errors.password)" class="error invalid-feedback">{{ errors.password[0] }}</span>
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="button" class="btn btn-primary btn-block" :disabled="isDisable" @click="redirectToAdmin()">
                                Sign In
                            </button>
                        </div>
                    </div>
                </form>
                <p class="mb-1">
                <router-link to="forgot-password">I forgot my password</router-link>
                </p>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import {
    mapState,
    mapGetters
} from 'vuex';

export default {
    name: 'Login',

    data: function () {
        return {
            information: {
                'email': null,
                'password': null
            }
        };
    },

    watch: {
        'information.email'() {
            if (this.errors) {
                this.errors.email = null;
                this.errors.message = null;
            }
        },

        'information.password'() {
            if (this.errors) {
                this.errors.password = null;
                this.errors.message = null;
            }
        },
    },

    computed: {
        ...mapState({
            isDisable: state => state.storeAuth.isDisable,
            token: state => state.storeAuth.token,
            errors: state => state.storeAuth.errors && state.storeAuth.errors.status === 422 ? state.storeAuth.errors.error : state.storeAuth.errors
        }),

        ...mapGetters({
            isLoggedIn: 'isLoggedIn',
        }),
    },

    methods: {
        async redirectToAdmin() {
            await this.$store.dispatch('actionLogin', {
                vue: this,
                params: this.information,
            });

            if (this.isLoggedIn) {
                return this.$router.push({
                    path: '/',
                });
            }
        },

        objectNotEmpty(object) {
            return !_.isEmpty(object);
        },
    }
};
</script>
