require('../../bootstrap');

window.Vue = require('vue');

Vue.component('transactions-list', require('./TransactionsList.vue'));

const app = new Vue({
    el: '#transactions',
});
