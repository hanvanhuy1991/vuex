import postsService from '../api';
import Helper from '@/library/hepler';

const LIST_POST = 'LIST_POST';
const ACCOUNTS_SEARCH = 'ACCOUNTS_SEARCH';
const SEARCH_POST = 'SEARCH_POST';
const SET_ERROR_SEARCH_POST = 'SET_ERROR_SEARCH_POST';
const DETAIL_POST = 'DETAIL_POST';
const COMMENT_POST = 'COMMENT_POST';


const state = {
    posts: null,
    postsSearch: null,
    accounts: null,
    post: null,
    errors: null,
    comments: null
};

const mutations = {
    [LIST_POST](state, posts) {
        state.posts = posts;
    },

    [ACCOUNTS_SEARCH](state, accounts) {
        state.accounts = accounts;
    },

    [SEARCH_POST](state, posts) {
        state.postsSearch = posts;
    },

    [SET_ERROR_SEARCH_POST](state, errors) {
        state.errors = errors;
    },

    [DETAIL_POST](state, post) {
        state.post = post;
    },

    [COMMENT_POST](state, comments) {
        state.comments = comments;
    },
};

const actions = {
    async actionGetAccount({ commit }, { vue }) {
        let accountResponse = await postsService.getAccounts();
        if (accountResponse.status === 200) {
            return commit(ACCOUNTS_SEARCH, accountResponse.data.data);
        }
        let errorAccountResponse = accountResponse.response;

        if (errorAccountResponse.status !== 422) {
            return Helper.handleError(vue, errorAccountResponse.status);
        }
    },

    async actionSearchPosts({ commit }, { vue, params, page }) {
        let mainLoading = vue.$store.state.storeLoading.mainLoading;
        vue.$store.dispatch('setAdminMainLoading', {...mainLoading, show: true });
        let searchResponse = await postsService.searchPosts(params, page);
        vue.$store.dispatch('setAdminMainLoading', {...mainLoading, show: false });

        if (searchResponse.status === 200) {
            return commit(SEARCH_POST, searchResponse.data.data);
        }

        if (searchResponse.response.status === 422) {
            return commit(SET_ERROR_SEARCH_POST, searchResponse.response.data.error);
        }

        return Helper.handleError(vue, searchResponse.data.status);
    },

    async actionDetailPost({ commit }, { vue, slug }) {
        let mainLoading = vue.$store.state.storeLoading.mainLoading;
        vue.$store.dispatch('setAdminMainLoading', {...mainLoading, show: true });
        let detailPostResponse = await postsService.getDetailPost(slug);
        vue.$store.dispatch('setAdminMainLoading', {...mainLoading, show: false });

        if (detailPostResponse.status === 200) {
            return commit(DETAIL_POST, detailPostResponse.data.data);
        }

        return Helper.handleError(vue, detailPostResponse.data.status);
    },

    async actionGetCommentPost({ commit }, { vue, slug, page }) {
        let mainLoading = vue.$store.state.storeLoading.mainLoading;
        vue.$store.dispatch('setAdminMainLoading', {...mainLoading, show: true });
        let commentPostResponse = await postsService.getCommentPost(slug, page);
        vue.$store.dispatch('setAdminMainLoading', {...mainLoading, show: false });

        if (commentPostResponse.status === 200) {
            return commit(COMMENT_POST, commentPostResponse.data.data);
        }

        return Helper.handleError(vue, commentPostResponse.data.status);
    },
};

const storePosts = {
    state,
    actions,
    mutations,
};

export default storePosts;