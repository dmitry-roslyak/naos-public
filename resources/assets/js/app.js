import './bootstrap';
import router from "./router";
import store from "./store";
import firebase from 'firebase/app';
import 'firebase/auth';
import 'chart.js';

firebase.initializeApp({
    apiKey: "AIzaSyDS8NA7CFPEAqO0-bvoLIpeRfpWNnUvRAA",
    authDomain: "dev-naos.firebaseapp.com",
    databaseURL: "https://dev-naos.firebaseio.com",
    projectId: "dev-naos",
    storageBucket: "dev-naos.appspot.com",
    messagingSenderId: "515353712594"
});

var data = {
    lng: null,
    langs: null,
    user: null
};
const app = new Vue({
    router,
    el: '#app',
    store,
    data: function () { return data; },
    created() {
        this.langs = window.Laravel.langsAvailable;
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
            });
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
            });
        },
        logIn(provider) {
            firebase.auth().signInWithPopup(provider).then(function (result) {
                // This gives you a Google Access Token. You can use it to access the Google API.
                // var token = result.credential.accessToken;
                app.logIn2(result.user.qa);
            }).catch(function (error) {
                //different api error
                // var errorCode = error.code;
                // var errorMessage = error.message;
                // var email = error.email;
                // var credential = error.credential;
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