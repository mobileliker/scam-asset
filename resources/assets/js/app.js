
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//vue-router
import router from './router'

//i18n
var VueI18n = require('vue-i18n');
import zhLocal from './lang/Zh-CN/Zh-CN'

Vue.use(VueI18n);
Vue.config.lang = 'Zh_CN';
Vue.locale('Zh_CN', zhLocal);

//vee-validate
import VeeValidate from 'vee-validate';
import VeeValidateZhCN from './lang/Zh-CN/vee-validate';
const config = {
    errorBagName: 'errors', // change if property conflicts.
    fieldsBagName: 'fields',
    delay: 0,
    locale: 'zh-CN',
    dictionary: {
        'zh-CN' : {
            messages : VeeValidateZhCN
        }
    },
    strict: true,
    enableAutoClasses: false,
    classNames: {
        touched: 'touched', // the control has been blurred
        untouched: 'untouched', // the control hasn't been blurred
        valid: 'valid', // model is valid
        invalid: 'invalid', // model is invalid
        pristine: 'pristine', // control has not been interacted with
        dirty: 'dirty' // control has been interacted with
    }
};
Vue.use(VeeValidate, config);

//Vuex
import store from './store'

//ElementUI
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-default/index.css'
Vue.use(ElementUI)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Vue.component('example', require('./components/Example.vue'));
//Vue.component('app', require('./components/App.vue'));

import App from './components/App'

const app = new Vue({
    el: '#app',
    router,
    store,
    template: '<App/>',
    components: { App }
});
