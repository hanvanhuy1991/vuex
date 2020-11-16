<template>
<div class="modal fade" id="modal-default" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ $t('profile.title') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form role="form"  v-on:keydown.enter.prevent="editProfle()">
                    <div class="form-group">
                        <label class="col-form-label">ID: {{ profileUser.id }} </label>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">{{ $t('profile.name') }}</label>
                        <input type="text" class="form-control" v-model.trim="profileUser.name" :disabled="isDisableInput" v-bind:class="{ 'is-invalid': errorsFormUpdateProfile && objectNotEmpty(errorsFormUpdateProfile.name)}" :placeholder="$t('profile.name')">
                        <span v-if="errorsFormUpdateProfile && objectNotEmpty(errorsFormUpdateProfile.name)" class="error invalid-feedback">{{ errorsFormUpdateProfile.name[0] }}</span>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">{{ $t('profile.gender') }}</label>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" v-model="profileUser.gender" :disabled="isDisableInput" value="0" name="radio1">
                            <label class="form-check-label">{{ $t('profile.female') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" v-model="profileUser.gender" :disabled="isDisableInput" value="1" name="radio1">
                            <label class="form-check-label">{{ $t('profile.male') }}</label>
                        </div>
                        <span v-if="errorsFormUpdateProfile && objectNotEmpty(errorsFormUpdateProfile.gender)" class="error invalid-feedback">{{ errorsFormUpdateProfile.gender[0] }}</span>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">{{ $t('profile.mobile') }}</label>
                        <input type="text" class="form-control" v-model.trim="profileUser.phone" :disabled="isDisableInput" v-bind:class="{ 'is-invalid': errorsFormUpdateProfile && objectNotEmpty(errorsFormUpdateProfile.phone)}" :placeholder="$t('profile.mobile')">
                        <span v-if="errorsFormUpdateProfile && objectNotEmpty(errorsFormUpdateProfile.phone)" class="error invalid-feedback">{{ errorsFormUpdateProfile.phone[0] }}</span>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">{{ $t('profile.email') }}: {{ profileUser.email }}</label>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">{{ $t('profile.address') }}</label>
                        <input type="text" class="form-control" v-model.trim="profileUser.address" :disabled="isDisableInput" v-bind:class="{ 'is-invalid': errorsFormUpdateProfile && objectNotEmpty(errorsFormUpdateProfile.address)}" :placeholder="$t('profile.address')">
                        <span v-if="errorsFormUpdateProfile && objectNotEmpty(errorsFormUpdateProfile.address)" class="error invalid-feedback">{{ errorsFormUpdateProfile.address[0] }}</span>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">{{ $t('profile.birthday') }}</label>
                        <input type="date" v-model="profileUser.birthday" :disabled="isDisableInput" v-bind:class="{ 'is-invalid': errorsFormUpdateProfile && objectNotEmpty(errorsFormUpdateProfile.birthday)}" class="form-control">
                        <span v-if="errorsFormUpdateProfile && objectNotEmpty(errorsFormUpdateProfile.birthday)" class="error invalid-feedback">{{ errorsFormUpdateProfile.birthday[0] }}</span>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ $t('common.close') }}</button>
                <button type="button" class="btn btn-primary" v-if="!isDisableInput" @click="editProfle()">{{ $t('common.save_change') }}</button>
                <button type="button" class="btn btn-primary" v-else @click="$store.commit('DISABLE_INPUT', !isDisableInput);">{{ $t('profile.edit_profile') }}</button>
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
    name: 'ProfileModal',

    data: function () {
        return {

        };
    },

    watch: {
        'profileUser.name'() {
            if (this.errorsFormUpdateProfile) {
                this.errorsFormUpdateProfile.name = null;
            }
        },
        'profileUser.gender'() {
            if (this.errorsFormUpdateProfile) {
                this.errorsFormUpdateProfile.gender = null;
            }
        },
        'profileUser.phone'() {
            if (this.errorsFormUpdateProfile) {
                this.errorsFormUpdateProfile.phone = null;
            }
        },
        'profileUser.address'() {
            if (this.errorsFormUpdateProfile) {
                this.errorsFormUpdateProfile.address = null;
            }
        },
        'profileUser.birthday'() {
            if (this.errorsFormUpdateProfile) {
                this.errorsFormUpdateProfile.birthday = null;
            }
        }
    },

    computed: {
        ...mapState({
            profileUser: state => state.storeAuth.profileUser,
            isDisableInput: state => state.storeAuth.isDisableInput,
            errorsFormUpdateProfile: state => state.storeAuth.errorsFormUpdateProfile,
            updateProfileSuccess: state => state.storeAuth.updateProfileSuccess
        }),
    },

    methods: {
        async editProfle() {
            if (!this.isDisableInput) {
                await this.$store.dispatch('actionUpdateProfile', {
                    vue: this,
                    params: this.profileUser,
                });
            }

            if (this.updateProfileSuccess) {
                this.$toasted.success(this.$t('common.update_successfully'), {
                    theme: 'bubble',
                    position: 'top-right',
                    duration: 1000
                });
                return this.$store.commit('DISABLE_INPUT', true);
            }
        },

        objectNotEmpty(object) {
            return !_.isEmpty(object);
        },
    }
};
</script>
