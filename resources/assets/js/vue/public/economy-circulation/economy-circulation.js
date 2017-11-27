require('../../bootstrap');

window.Vue = require('vue');

Vue.component('economy-circulation', require('./TotalOrdersMoneyChart.vue'));

const app = new Vue({
    el: '#economy-circulation',
});
