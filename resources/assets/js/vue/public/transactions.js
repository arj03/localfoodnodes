require('../bootstrap');

window.Vue = require('vue');
window.moment = require('moment');

Vue.component('transactions-list', require('./components/TransactionsList.vue'));

const app = new Vue({
    el: '#transactions',
});
