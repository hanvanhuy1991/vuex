import Vue from 'vue';
import VueI18n from 'vue-i18n';
import jpMessage from './jp.json';
import enMessage from './en.json';
import Cookies from 'js-cookie';

Vue.use(VueI18n);

const messages = {
    jp: jpMessage,
    en: enMessage
};

var location = 'en';
let cookies = Cookies.get('admin') ? JSON.parse(Cookies.get('admin')) : null;

if (cookies && cookies.storeLang && cookies.storeLang.current) {
    location = cookies.storeLang.current;
} else {
    navigator.language === 'jp' ? location = 'jp' : location = 'en';
}

const i18n = new VueI18n({
    locale: location, // set locale
    messages,
    fallbackLocale: location,
});

export default i18n;