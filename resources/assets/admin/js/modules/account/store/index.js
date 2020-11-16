import accountService from '../api';
import Helper from '@/library/hepler';

const SEARCH_ACCOUNT = 'LIST_POST';
const BUSINESS_CATEGORY = 'BUSINESS_CATEGORY';
const SET_ERROR_SEARCH_ACCOUNT = 'SET_ERROR_SEARCH_ACCOUNT';
const SET_DETAIL_ACCOUNT = 'SET_DETAIL_ACCOUNT';
const SET_LIST_POST = 'SET_LIST_POST';

const state = {
    listAccounts: null,
    businessCategories: null,
    account: null,
    errors: null,
    posts: null,
    allPost: null
};

const mutations = {
    [SEARCH_ACCOUNT](state, listAccounts) {
        state.listAccounts = listAccounts;
    },

    [BUSINESS_CATEGORY](state, businessCategories) {
        state.businessCategories = businessCategories;
    },

    [SET_ERROR_SEARCH_ACCOUNT](state, errors) {
        state.errors = errors;
    },

    [SET_DETAIL_ACCOUNT](state, account) {

        account.role.forEach(function(item) {
            if (item.name === 'business_account') {
                account.isBusinessAccount = true;
            }
        });

        state.account = account;
    },

    [SET_LIST_POST](state, posts) {
        state.posts = posts;
    },
};

const actions = {
    async actionSearchAccounts({ commit }, { vue, params, page }) {
        let mainLoading = vue.$store.state.storeLoading.mainLoading;
        vue.$store.dispatch('setAdminMainLoading', {...mainLoading, show: true });
        let searchResponse = await accountService.getListAccounts(params, page);
        vue.$store.dispatch('setAdminMainLoading', {...mainLoading, show: false });

        if (searchResponse.status === 200) {
            return commit(SEARCH_ACCOUNT, searchResponse.data.data);
        }

        if (searchResponse.response.status === 422) {
            return commit(SET_ERROR_SEARCH_ACCOUNT, searchResponse.response.data.errors);
        }

        return Helper.handleError(vue, searchResponse.data.status);
    },

    async actionGetBusinessCategory({ commit }, { vue }) {
        let searchBusinessCategoryResponse = await accountService.getBusinessCategory();
        if (searchBusinessCategoryResponse.status === 200) {
            return commit(BUSINESS_CATEGORY, searchBusinessCategoryResponse.data.data);
        }

        return Helper.handleError(vue, searchBusinessCategoryResponse.data.status);
    },

    async actionShowDetailAccount({ commit }, { vue, id }) {
        let mainLoading = vue.$store.state.storeLoading.mainLoading;
        vue.$store.dispatch('setAdminMainLoading', {...mainLoading, show: true });
        let detailAccountResponse = await accountService.getDetailAccount(id);
        vue.$store.dispatch('setAdminMainLoading', {...mainLoading, show: false });

        if (detailAccountResponse.status === 200) {
            return commit(SET_DETAIL_ACCOUNT, detailAccountResponse.data.data);
        }

        return Helper.handleError(vue, detailAccountResponse.response.data.status);
    },

    async actionShowListPosts({ commit }, { vue, id, page }) {
        let mainLoading = vue.$store.state.storeLoading.mainLoading;
        vue.$store.dispatch('setAdminMainLoading', {...mainLoading, show: true });
        let listPostsResponse = await accountService.getListPosts(id, page);
        vue.$store.dispatch('setAdminMainLoading', {...mainLoading, show: false });

        if (listPostsResponse.status === 200) {
            return commit(SET_LIST_POST, listPostsResponse.data.data);
        }

        return Helper.handleError(vue, listPostsResponse.response.data.status);
    }
};

const storeAccounts = {
    state,
    actions,
    mutations,
};

export default storeAccounts;