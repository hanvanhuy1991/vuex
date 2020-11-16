<template>
<div class="modal fade" id="modal-changepassword" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ $t('common.change_password') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form role="form" v-on:keydown.enter.prevent="updatePassword()">
                    <div class="form-group">
                        <label class="col-form-label">{{ $t('change_password.old_password') }}</label>
                        <input type="password" class="form-control" v-model.trim="changePassword.current_password"  v-bind:class="{ 'is-invalid': errorsChangePassword && objectNotEmpty(errorsChangePassword.current_password)}" :placeholder="$t('change_password.old_password')">
                        <span v-if="errorsChangePassword && objectNotEmpty(errorsChangePassword.current_password)" class="error invalid-feedback">{{ errorsChangePassword.current_password[0] }}</span>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">{{ $t('change_password.new_password') }}</label>
                        <input type="password" class="form-control" v-model.trim="changePassword.new_password" v-bind:class="{ 'is-invalid': errorsChangePassword && objectNotEmpty(errorsChangePassword.new_password)}" :placeholder="$t('change_password.new_password')">
                        <span v-if="errorsChangePassword && objectNotEmpty(errorsChangePassword.new_password)" class="error invalid-feedback">{{ errorsChangePassword.new_password[0] }}</span>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">{{ $t('change_password.confirm_password') }}</label>
                        <input type="password" class="form-control" v-model.trim="changePassword.new_password_confirmation" v-bind:class="{ 'is-invalid': errorsChangePassword && objectNotEmpty(errorsChangePassword.new_password_confirmation)}" :placeholder="$t('change_password.confirm_password')">
                        <span v-if="errorsChangePassword && objectNotEmpty(errorsChangePassword.new_password_confirmation)" class="error invalid-feedback">{{ errorsChangePassword.new_password_confirmation[0] }}</span>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ $t('common.close') }}</button>
                <button type="button" class="btn btn-primary" @click="updatePassword()">{{ $t('common.save_change') }}</button>
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
    name: 'ChangePassword',

    data: function () {
        return {
            changePassword: {
                'current_password': null,
                'new_password': null,
                'new_password_confirmation': null
            }
        };
    },

    watch: {
        'changePassword.current_password'() {
            if (this.errorsChangePassword) {
                this.errorsChangePassword.current_password = null;
            }
        },

        'changePassword.new_password'() {
            if (this.errorsChangePassword) {
                this.errorsChangePassword.new_password = null;
            }
        },

        'changePassword.new_password_confirmation'() {
            if (this.errorsChangePassword) {
                this.errorsChangePassword.new_password_confirmation = null;
            }
        }
    },

    computed: {
        ...mapState({
            errorsChangePassword: state => state.storeAuth.errorsChangePassword,
            changePasswordSuccess: state => state.storeAuth.changePasswordSuccess
        }),
    },

    methods: {
        async updatePassword() {
            await this.$store.dispatch('actionChangePassword', {
                vue: this,
                params: this.changePassword,
            });

            if (this.changePasswordSuccess) {
                this.$toasted.success(this.$t('common.update_successfully'), {
                    theme: 'bubble',
                    position: 'top-right',
                    duration: 1000
                });
            }
        },

        objectNotEmpty(object) {
            return !_.isEmpty(object);
        },
    }
};
</script>
