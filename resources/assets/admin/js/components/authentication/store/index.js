import Vue from 'vue';
import User from '@/library/User';
import ConfigAxios from '@/library/axios';
import authService from '../api';
import Helper from '@/library/hepler';

const AUTH_SET_USER = 'AUTH_SET_USER';
const AUTH_SET_TOKEN = 'AUTH_SET_TOKEN';
const AUTH_SET_LOADING = 'AUTH_SET_LOADING';
const AUTH_SET_LOADING_FORGOT_PASS = 'AUTH_SET_LOADING_FORGOT_PASS';
const AUTH_SET_ERROR_FORGOT_PASS = 'AUTH_SET_ERROR_FORGOT_PASS';
const AUTH_SET_SENT_MAIL_SUCCESS = 'AUTH_SET_SENT_MAIL_SUCCESS';
const DISABLE_INPUT = 'DISABLE_INPUT';
const SET_PROFILE_USER = 'SET_PROFILE_USER';
const AUTH_SET_ERROR_UPDATE_PROFILE = 'AUTH_SET_ERROR_UPDATE_PROFILE';
const UPDATE_PROFILE_SUCCESS = 'UPDATE_PROFILE_SUCCESS';
const AUTH_SET_ERROR_CHANGE_PASSWORD = 'AUTH_SET_ERROR_CHANGE_PASSWORD';
const CHANGE_PASSWORD_SUCCESS = 'CHANGE_PASSWORD_SUCCESS';

const AUTH_LOGOUT = 'AUTH_LOGOUT';
const AUTH_SET_ERROR = 'AUTH_SET_ERROR';

const state = {
    user: null,
    profileUser: {
        'address': null,
        'avatar': null,
        'birthday': null,
        'email': null,
        'gender': null,
        'id': null,
        'name': null,
        'phone': null,
    },
    token: null,
    errors: null,
    errorsForgot: null,
    isDisable: false,
    isDisableForgotPass: false,
    logout: false,
    sendMail: false,
    isDisableInput: true,
    errorsFormUpdateProfile: null,
    errorsChangePassword: null,
    updateProfileSuccess: false,
    changePasswordSuccess: false
};

const getters = {
    isLoggedIn(state) {
        return !!state.token;
    },

    getUserRole(state) {
        if (state.user) {
            return state.user.roles ? state.user.roles : null;
        }

        return null;

    },
};

const mutations = {
    [AUTH_SET_LOADING](state, isDisable) {
        state.isDisable = isDisable;
    },

    [AUTH_SET_LOADING_FORGOT_PASS](state, isDisableForgotPass) {
        state.isDisableForgotPass = isDisableForgotPass;
    },

    [AUTH_SET_SENT_MAIL_SUCCESS](state, status) {
        state.sendMail = status;
    },

    [AUTH_SET_USER](state, user) {
        state.user = user;
        Vue.prototype.$user = new User(user);
    },

    [SET_PROFILE_USER](state, user) {
        state.profileUser = user;
    },

    [AUTH_SET_TOKEN](state, token) {
        state.token = token;
    },

    [AUTH_LOGOUT](state, status) {
        state.logout = status;
    },

    [AUTH_SET_ERROR](state, errors) {
        state.errors = errors;
    },

    [AUTH_SET_ERROR_FORGOT_PASS](state, errors) {
        state.errorsForgot = errors;
    },

    [DISABLE_INPUT](state, isDisableInput) {
        state.isDisableInput = isDisableInput;
    },

    [AUTH_SET_ERROR_UPDATE_PROFILE](state, errorsFormUpdateProfile) {
        state.errorsFormUpdateProfile = errorsFormUpdateProfile;
    },

    [UPDATE_PROFILE_SUCCESS](state, updateProfileSuccess) {
        state.updateProfileSuccess = updateProfileSuccess;
    },

    [AUTH_SET_ERROR_CHANGE_PASSWORD](state, errorsChangePassword) {
        state.errorsChangePassword = errorsChangePassword;
    },

    [CHANGE_PASSWORD_SUCCESS](state, changePasswordSuccess) {
        state.changePasswordSuccess = changePasswordSuccess;
    },
};

const actions = {
    /**
     * Acrion login into CMS
     *
     * @param {*} { commit }
     * @param {*} { params }
     * @return {*}
     */
    async actionLogin({ commit }, { params }) {
        commit(AUTH_SET_LOADING, true);
        let loginResponse = await authService.login(params);
        if (loginResponse.status === 200) {
            let token = loginResponse.data.data;
            ConfigAxios.setAuthorizationHeader(token.token_type, token.access_token);
            commit(AUTH_SET_LOADING, false);
            commit(AUTH_SET_TOKEN, token);
            let userResponse = await authService.getProfile();

            return commit(AUTH_SET_USER, userResponse.data);
        }

        commit(AUTH_SET_LOADING, false);

        return commit(AUTH_SET_ERROR, loginResponse.response.data);
    },

    /**
     * Acrion login into CMS
     *
     * @param {*} { commit }
     * @param {*} { params }
     * @return {*}
     */
    async actionForgotPassword({ commit }, { params }) {
        commit(AUTH_SET_LOADING_FORGOT_PASS, true);
        let forgotPasswordResponse = await authService.forgotPassword(params);
        if (forgotPasswordResponse.status === 200) {
            commit(AUTH_SET_LOADING_FORGOT_PASS, false);

            return commit(AUTH_SET_SENT_MAIL_SUCCESS, true);
        }

        commit(AUTH_SET_LOADING_FORGOT_PASS, false);

        return commit(AUTH_SET_ERROR_FORGOT_PASS, forgotPasswordResponse.response.data);
    },

    /**
     * Action get profile
     *
     * @param {*} { commit }
     * @param {*} { params }
     * @return {*}
     */
    async actionGetProfile({ commit }, { vue }) {
        let mainLoading = vue.$store.state.storeLoading.mainLoading;
        vue.$store.dispatch('setAdminMainLoading', {...mainLoading, show: true });
        let userResponse = await authService.getProfile();
        vue.$store.dispatch('setAdminMainLoading', {...mainLoading, show: false });

        if (userResponse.status === 200) {
            return commit(SET_PROFILE_USER, userResponse.data);
        }

        let errorResponse = userResponse.response;
        if (errorResponse.status === 401) {
            return Helper.handleError(vue, errorResponse.status);
        }
    },


    /**
     * Action update profile
     *
     * @param {*} { commit }
     * @param {*} { vue, params }
     * @return {*}
     */
    async actionUpdateProfile({ commit }, { vue, params }) {
        let mainLoading = vue.$store.state.storeLoading.mainLoading;
        vue.$store.dispatch('setAdminMainLoading', {...mainLoading, show: true });
        let updateProfileResponse = await authService.updateProfile(params);
        vue.$store.dispatch('setAdminMainLoading', {...mainLoading, show: false });
        if (updateProfileResponse.status === 200) {
            let userResponse = await authService.getProfile();
            commit(AUTH_SET_USER, userResponse.data);
            return commit(UPDATE_PROFILE_SUCCESS, true);
        }

        commit(UPDATE_PROFILE_SUCCESS, false);

        if (updateProfileResponse.response.status === 422) {
            return commit(AUTH_SET_ERROR_UPDATE_PROFILE, updateProfileResponse.response.data.error);
        }

        return Helper.handleError(vue, updateProfileResponse.data.status);
    },

    /**
     * Action update profile
     *
     * @param {*} { commit }
     * @param {*} { vue, params }
     * @return {*}
     */
    async actionChangePassword({ commit }, { vue, params }) {
        let mainLoading = vue.$store.state.storeLoading.mainLoading;
        vue.$store.dispatch('setAdminMainLoading', {...mainLoading, show: true });
        let changePasswordResponse = await authService.changePassword(params);
        vue.$store.dispatch('setAdminMainLoading', {...mainLoading, show: false });
        if (changePasswordResponse.status === 200) {
            return commit(CHANGE_PASSWORD_SUCCESS, true);
        }
        commit(CHANGE_PASSWORD_SUCCESS, false);

        if (changePasswordResponse.response.status === 422) {
            return commit(AUTH_SET_ERROR_CHANGE_PASSWORD, changePasswordResponse.response.data.error);
        }

        return Helper.handleError(vue, changePasswordResponse.data.status);
    },



    /**
     * Action Logout
     *
     * @param {*} { commit }
     * @param {*} { vue }
     * @return {*}
     */
    async actionLogout({ commit }, { vue }) {
        let mainLoading = vue.$store.state.storeLoading.mainLoading;
        vue.$store.dispatch('setAdminMainLoading', {...mainLoading, show: true });
        let logoutResponse = await authService.logout();
        vue.$store.dispatch('setAdminMainLoading', {...mainLoading, show: false });

        if (logoutResponse.status === 200) {
            commit(AUTH_SET_TOKEN, null);
            commit(AUTH_SET_USER, null);

            return commit(AUTH_LOGOUT, true);
        }

        let errorResponse = logoutResponse.response;
        return Helper.handleError(vue, errorResponse.status);
    }
};

const storeAuth = {
    getters,
    state,
    actions,
    mutations,
};

export default storeAuth;