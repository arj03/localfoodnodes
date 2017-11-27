require('../../bootstrap');

window.Vue = require('vue');

Vue.component('metrics', require('./Metrics.vue'));

const app = new Vue({
    el: '#economy-in-out',
});
