
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../bootstrap');

window.Vue = require('vue');
window.moment = require('moment');
window._ = require('lodash');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('index-page', require('./components/admin/index/IndexPage.vue'));
Vue.component('user-page', require('./components/admin/users/UserPage.vue'));
Vue.component('order-page', require('./components/admin/orders/OrderPage.vue'));
Vue.component('economy-page', require('./components/admin/economy/EconomyPage.vue'));
Vue.component('api-page', require('./components/admin/api/ApiPage.vue'));

// Passport
Vue.component('passport-clients', require('./components/passport/Clients.vue'));
Vue.component('passport-authorized-clients', require('./components/passport/AuthorizedClients.vue'));
Vue.component('passport-personal-access-tokens', require('./components/passport/PersonalAccessTokens.vue'));

const app = new Vue({
    el: '#admin-app',
});
