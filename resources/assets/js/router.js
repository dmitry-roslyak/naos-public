import Vue from "vue";
import VueRouter from 'vue-router';

Vue.use(VueRouter);

const home = Vue.component('AppHome', require('./components/AppHome.vue'));
const App404 = Vue.component('App404', require('./components/App404.vue'));
// const products = Vue.component('products', require('./components/Products.vue'));
// const detail = Vue.component('detail', require('./components/detail.vue'));
// const comments = Vue.component('comments', require('./components/comm1.vue'));
// const compare = Vue.component('compare', require('./components/Compare.vue'));
// const cart = Vue.component('cart', require('./components/Cart.vue'));
// const account = Vue.component('account', require('./components/Account.vue'));
Vue.component('AppHeader', require('./components/AppHeader.vue'));
Vue.component('star-rating', require('vue-star-rating/src/star-rating.vue'));
Vue.component('VPagination', require('./components/VPagination.vue'));

Vue.component('AppComments', function (resolve) {
    require(['./components/AppComments.vue'], resolve)
});
const products = Vue.component('AppProducts', function (resolve) {
    require(['./components/AppProducts.vue'], resolve)
});
Vue.component('AppProductsFilters', function (resolve) {
    require(['./components/AppProductsFilters.vue'], resolve)
});
const detail = Vue.component('AppDetail', function (resolve) {
    require(['./components/AppDetail.vue'], resolve)
});
Vue.component('AppDetailCharts', function (resolve) {
    require(['./components/AppDetailCharts.vue'], resolve)
});
const compare = Vue.component('AppCompare', function (resolve) {
    require(['./components/AppCompare.vue'], resolve)
});
const cart = Vue.component('AppCart', function (resolve) {
    require(['./components/AppCart.vue'], resolve)
});
const account = Vue.component('AppUser', function (resolve) {
    require(['./components/AppUser.vue'], resolve)
});
Vue.component('AppUserInfo', function (resolve) {
    require(['./components/AppUserInfo.vue'], resolve)
});
export default new VueRouter({
    // mode: 'history',
    routes: [
        { path: '/404', component: App404 },
        { path: '*', redirect: '/404' },
        { path: '/', component: home },
        { name: 'products', path: '/products/:category', component: products, props: true  },
        { name: 'detail', path: '/detail/:id', component: detail, props: true },
        { path: '/compare/:category/:ids', component: compare, props: true },
        { path: '/cart/:ids?', component: cart, props: true },
        { path: '/account', component: account }
    ]
});