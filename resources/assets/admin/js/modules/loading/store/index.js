const ADMIN_MAIN_LOADING = 'ADMIN_MAIN_LOADING';
const ADMIN_ERROR_PAGE = 'ADMIN_ERROR_PAGE';

const state = {
    mainLoading: {
        show: false,
    },
    errorPage: {
        status: false,
        code: null,
    },
};

const mutations = {
    [ADMIN_MAIN_LOADING](state, { mainLoading }) {
        state.mainLoading = mainLoading;
    },

    [ADMIN_ERROR_PAGE](state, { errorPage }) {
        state.errorPage = errorPage;
    },
};

const actions = {
    setAdminMainLoading({ commit }, mainLoading) {
        commit(ADMIN_MAIN_LOADING, { mainLoading });
    },

    setErrorPage({ commit }, error) {
        const errorPage = error.error;
        commit(ADMIN_ERROR_PAGE, { errorPage });
    },
};

const storeLoading = {
    state,
    actions,
    mutations,
};

export default storeLoading;