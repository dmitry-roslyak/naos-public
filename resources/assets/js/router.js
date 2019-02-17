import Vue from "vue";
import VueRouter from 'vue-router';

Vue.use(VueRouter);

const home = Vue.component('home', require('./components/Home.vue'));
// const products = Vue.component('products', require('./components/Products.vue'));
// const detail = Vue.component('detail', require('./components/detail.vue'));
// const comments = Vue.component('comments', require('./components/comm1.vue'));
// const compare = Vue.component('compare', require('./components/Compare.vue'));
// const cart = Vue.component('cart', require('./components/Cart.vue'));
// const account = Vue.component('account', require('./components/Account.vue'));
Vue.component('search', require('./components/Search.vue'));
Vue.component('star-rating', require('vue-star-rating/src/star-rating.vue'));
Vue.component('pagination', require('./components/Pagination.vue'));

Vue.component('charts', function (resolve) {
    require(['./components/charts.vue'], resolve)
});
Vue.component('sidebar', function (resolve) {
    require(['./components/Sidebar.vue'], resolve)
});
Vue.component('comments', function (resolve) {
    require(['./components/comm1.vue'], resolve)
});
const products = Vue.component('products', function (resolve) {
    require(['./components/Products.vue'], resolve)
});
const detail = Vue.component('detail', function (resolve) {
    require(['./components/detail.vue'], resolve)
});
const compare = Vue.component('compare', function (resolve) {
    require(['./components/Compare.vue'], resolve)
});
const cart = Vue.component('cart', function (resolve) {
    require(['./components/Cart.vue'], resolve)
});
const account = Vue.component('account', function (resolve) {
    require(['./components/Account.vue'], resolve)
});
Vue.component('user-info', function (resolve) {
    require(['./components/userInfo.vue'], resolve)
});
export default new VueRouter({
    // mode: 'history',
    routes: [
        { path: '/', component: home },
        { path: '/products/:category', component: products, props: true  },
        { name: 'detail', path: '/detail/:id', component: detail, props: true },
        { path: '/compare/:ids', component: compare, props: true },
        { path: '/cart/:ids1', component: cart, props: true  },
        { path: '/account', component: account }
    ]
});