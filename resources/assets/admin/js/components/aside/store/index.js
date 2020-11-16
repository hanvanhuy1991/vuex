const SET_ACTIVE_ASIDE = 'SET_ACTIVE_ASIDE';

const state = {
    activeAside: 'dashboard',
};

const mutations = {
    [SET_ACTIVE_ASIDE](state, { aside }) {
        return state.activeAside = aside;
    },
};

const storeAside = {
    state,
    mutations,
};

export default storeAside;