require('../bootstrap');

window.Vue = require('vue');
window.moment = require('moment');

Vue.component('metrics', require('./components/frontpage/Metrics.vue'));

const app = new Vue({
    el: '#frontpage-metrics',
});
