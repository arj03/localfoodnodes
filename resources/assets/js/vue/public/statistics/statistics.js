require('../../bootstrap');

window.Vue = require('vue');

Vue.component('orders-by-node-chart', require('./OrdersByNodeChart.vue'));

const app = new Vue({
    el: '#statistics',
});
