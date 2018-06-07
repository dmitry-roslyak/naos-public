/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import VueRouter from'vue-router';
Vue.use(VueRouter)
// import Vuex from 'vuex'

// Vue.use(Vuex)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

var dashboard = Vue.component('dashboard', require('./components/Dashboard.vue'));

const routes = [
  { path: '/', component: dashboard }
]
const router = new VueRouter({
  routes // short for routes: routes
});
// const store = new Vuex.Store({
//   state: {
//     promise_lng:null
//   },
//   mutations: {
//     set_promise_lng (state,val) {
//         state.promise_lng=val;  
//     }
//   }
// });
var data={
    lang:'null',
    lang_list:null,
    ready:false
};
var self, selfData,test1;
const app = new Vue({
    router,
    el: '#app',
    data: function () {return data;},
    created(){
        self=this;     
        selfData = this.$data;
        // axios.get('lang_list').then(function (response) {
        //     self.$data.lang_list=$.map(response.data, function(value, index) {return [value];});
        // }).catch(function (error) {
        //     console.log(error);
        // });         
        // this.get_locale(window.navigator.language);
        // this.$store.state.promise_lng.then(function (response) {
        //     selfData.ready = true;
        //     selfData.lang = response.data;
        // });
    },
    methods:{
        // get_locale(lng){
        //     var temp = axios.get('lang/'+lng).then(function (response) {return response;})
        //     .catch(function (error) {
        //         console.log(error);
        //     }); 
        //     this.$store.commit('set_promise_lng',temp); 
        // }
    }
});