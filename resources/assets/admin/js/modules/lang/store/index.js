// const SET_LANG = 'SET_LANG';

const state = {
    languages: [{
        key: 'en',
        title: 'Version English',
        image: '/admin/image/en.png'
    },
    {
        key: 'jp',
        title: 'Version Janpan',
        image: '/admin/image/jp.png'
    },
    ],
    current: navigator.language === 'jp' ? 'jp' : 'en'
};

const mutations = {
    SET_LANG(state, { vue, lang }) {
        state.current = lang;
        vue.$i18n.locale = lang;
    }
};

const actions = {
    setLang({ commit }, { vue, lang }) {
        commit('SET_LANG', { vue, lang });
    }
};

const storeLang = {
    state,
    actions,
    mutations,
};

export default storeLang;