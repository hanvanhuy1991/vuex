import Cookies from 'js-cookie';
// import config from '@/config';

export default {
    handleError(vue, statusCode) {
        if (statusCode === 401) {
            vue.$store.commit('AUTH_SET_TOKEN', null);
            vue.$store.commit('AUTH_SET_USER', null);
            vue.$store.commit('SET_LANG', null);
            vue.$store.commit('AUTH_LOGOUT', true);
            localStorage.clear();
            Cookies.remove('admin');

            return window.location.href = '/login';
        }

        if (statusCode === 404) {
            vue.$router.push({ name: 'Error Page' });
            // console.log(121212);
        }
    },

    truncate(text, length, clamp) {
        clamp = clamp || '...';

        return text.length > length ? text.slice(0, length) + clamp : text;
    },

    formatMoney(value) {
        let val = (value / 1).toFixed(2).replace('.', ',');

        return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
};