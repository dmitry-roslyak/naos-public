/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');

var firebase = require("firebase/app");
require("firebase/auth");
// Initialize Firebase
var config = {
    apiKey: "AIzaSyDS8NA7CFPEAqO0-bvoLIpeRfpWNnUvRAA",
    authDomain: "dev-naos.firebaseapp.com",
    databaseURL: "https://dev-naos.firebaseio.com",
    projectId: "dev-naos",
    storageBucket: "dev-naos.appspot.com",
    messagingSenderId: "515353712594"
};
firebase.initializeApp(config);

import Chart from 'chart.js';
import VueRouter from 'vue-router';
Vue.use( VueRouter );
import Vuex from 'vuex'
Vue.use(Vuex);


window._ = {};

window._.throttle = function (func, timeout) {
    if(this.throttle.id) clearTimeout(this.throttle.id);

    this.throttle.id = setTimeout(function () {
        func();
    }, timeout);
}
// Enable pusher logging - don't include this in production
// Pusher.logToConsole = true;

// var pusher = new Pusher('69e878ea5991b6099fb6', {
//   cluster: 'eu',
//   encrypted: true
// });

// var channel = pusher.subscribe('my-channel');
// channel.bind('my-event', function(data) {
//   alert(data.message);
// });
window.socket =  new WebSocket("wss://ws-eu.pusher.com:443/app/69e878ea5991b6099fb6?protocol=7&client=js&version=4.1.0&flash=false");
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const home = Vue.component('home', require('./components/Home.vue'));
Vue.component('search', require('./components/Search.vue'));
// Vue.component('star-rating', require('vue-star-rating'));
Vue.component('star-rating', require('C:/Users/xiaomi/Desktop/downloads/naos/node_modules/vue-star-rating/src/star-rating.vue'));
Vue.component('buy-modal', require('./components/buy-modal.vue'));
Vue.component('pagination', require('./components/Pagination.vue'));
Vue.component('range', require('./components/Range.vue'));

Vue.component('charts', function (resolve) {
    require(['./components/charts.vue'], resolve)
});
Vue.component('sidebar', function (resolve) {
    require(['./components/Sidebar.vue'], resolve)
});
// const products = Vue.component('products', require('./components/Products.vue'));
// const detail = Vue.component('detail', require('./components/detail.vue'));
// const comments = Vue.component('comments', require('./components/comm1.vue'));
// const compare = Vue.component('compare', require('./components/Compare.vue'));
// const cart = Vue.component('cart', require('./components/Cart.vue'));
// const account = Vue.component('account', require('./components/Account.vue'));

const products = Vue.component('products', function (resolve) {
    require(['./components/Products.vue'], resolve)
});
const detail = Vue.component('detail', function (resolve) {
    require(['./components/detail.vue'], resolve)
});
const comments = Vue.component('comments', function (resolve) {
    require(['./components/comm1.vue'], resolve)
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
const routes = [
    { path: '/', component: home },
    { path: '/products', component: products },
    { name: 'detail', path: '/detail/:id', component: detail, props: true },
    { path: '/compare/:ids', component: compare, props: true },
    { path: '/cart/:ids1', component: cart, props: true  },
    { path: '/account', component: account }
];
const router = new VueRouter({
    routes // short for routes: routes
});
const store = new Vuex.Store({
    state: {
        count: 0,
        cartLength: 0,
        compare_list: [],
        ctg_id: 0,
        ctg_ids: [],
        flt_ids: [0],
        currency:0,
        cart: {},
    },
    mutations: {
        cartClear: function(state) {
            state.cart = {}
            localStorage.cart = ''
        },
        cart(state, item){
            if(item){
                state.cart[item.id] ? state.cart[item.id] += item.count : state.cart[item.id] = item.count
                if(item.toRemove) delete state.cart[item.id]
                localStorage.cart = JSON.stringify(state.cart);
            } 
            else {
                if(localStorage.cart && localStorage.cart.length) {
                    try {
                        state.cart = JSON.parse(localStorage.cart);
                    } catch (error) {
                        console.log(error)
                        localStorage.cart = ''
                    }
                }
            }
            state.cartLength = 0
            for (const key in state.cart) {
                state.cartLength += state.cart[key];
            }
        },
        set_currency(state,value){
            state.currency = value;
        },
        set_filter_params(state, obj) {
            state.flt_ids = obj.flt_ids;
        },
        set_ctg_id(state, id) { 
            state.ctg_id = id; 
            state.compare_list.length = 0;
            state.flt_ids.length = 0;
        },
        compare: function(state, item) {
            if (item.is_compare) {
                state.compare_list.push(item.id);
            }
            else {
                var i = app.compareHas(item.id)
                if(i > -1) state.compare_list.splice(i, 1)
            }
        }
    }
});
var data = {
    lng: null,
    langs: null,
    ajaxError: 0,
    user: null
};
const app = new Vue({
    router,
    el: '#app',
    store,
    data: function () { return data; },
    created() {
        this.langs = $.map(window.Laravel.langsAvailable, function (value) {
            return {img: value[0].text, name: value[1].text, ISO: value[2].text};
        });
        this.lng = window.lng;
        this.$store.commit('set_currency', window.Laravel.currency.rate);
        if (window.Laravel.user) this.user = window.Laravel.user.name;
        this.$store.commit('cart');
    },
    methods: {
        get_locale(lng) {
            axios.get('lang/' + lng).then(function (response) {
                response.data[0].map(function (t) { window.lng[t.name] = t.text; });
                window.lng.currency = window.lng[response.data[1].name];
                app.$store.commit('set_currency', response.data[1].rate);
                app.$forceUpdate();
            }).catch(function (error) {
                app.$root.retry(app.get_locale, error.response.status);
            });
        },
        compareHas(id) {
            for (var i = 0; i <app.$store.state.compare_list.length; i++) {
                if(app.$store.state.compare_list[i]==id) return i;       
            }
            return -1;
        },
        /** 
        * Retry request if connection refused 
        * @method retry
        * @param {Function} request function
        * @param {Number} HTTP response code 
        */
        retry(f,e) {
            if(e) app.ajaxError = e;
            else {
                setTimeout(function () {
                    if(!app.n) app.n = 0;
                    if(app.n<6) {
                        app.n++;
                        f();
                    }
                    else{
                        app.ajaxError = 5;
                        app.n = 0;
                    }
                },700);
            }
        },
        googleIn() {
            this.logIn(new firebase.auth.GoogleAuthProvider());
        },
        facebookLogIn() {
            this.logIn(new firebase.auth.FacebookAuthProvider());
        },
        logIn2(ie) {
            axios.post('/auth', {
                input: ie
            }).then(function (response) {
                // app.user = response.data;
                // this.$router.push('/');
                location.replace('/');
            }).catch(function (error) {
                app.$root.retry(app.logIn2, error.response.status);
            });
        },
        logIn(provider) {
            firebase.auth().signInWithPopup(provider).then(function (result) {
                // This gives you a Google Access Token. You can use it to access the Google API.
                var token = result.credential.accessToken;
                app.logIn2(result.user.qa);
            }).catch(function (error) {
                //different api error
                var errorCode = error.code;
                var errorMessage = error.message;
                var email = error.email;
                var credential = error.credential;
                // ...
            });
        },
        logout() {
            axios.post('/logout').then(function (response) { app.user = null; });
            firebase.auth().signOut().then(function () {
                // Sign-out successful.
            }).catch(function (error) {
                console.log(error);
                // An error happened.
            });
        }
    }
});