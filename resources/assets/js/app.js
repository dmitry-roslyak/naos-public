/* eslint-disable no-undef */
import "./bootstrap";
import router from "./router";
import store from "./store";
import firebase from "firebase/app";
import "firebase/auth";

if (__NODE_ENV === "production") {
  firebase.initializeApp({
    apiKey: "AIzaSyDS8NA7CFPEAqO0-bvoLIpeRfpWNnUvRAA",
    authDomain: "dev-naos.firebaseapp.com",
    databaseURL: "https://dev-naos.firebaseio.com",
    projectId: "dev-naos",
    storageBucket: "dev-naos.appspot.com",
    messagingSenderId: "515353712594",
  });
}

var data = {
  lng: null,
  langs: null,
  user: null,
};
const app = new Vue({
  el: "#app",
  router,
  store,
  data: function() {
    return data;
  },
  created() {
    this.langs = window.Laravel.langsAvailable;
    this.lng = window.Laravel.lng;
    this.$store.commit("set_currency", window.Laravel.currency.rate);
    if (window.Laravel.user) this.user = window.Laravel.user.name;
  },
  methods: {
    itemPriceResult(item) {
      return (
        (item.discount
          ? this.$store.state.currency * item.price -
            ((this.$store.state.currency * item.price) / 100) * item.discount.discount
          : this.$store.state.currency * item.price
        ).toFixed(1) +
        " " +
        this.lng.currency
      );
    },
    get_locale(lng) {
      axios
        .get("/lang/" + lng)
        .then(function(response) {
          response.data[0].currency = response.data[0][response.data[1].name];
          app.lng = window.Laravel.lng = response.data[0];
          app.$store.commit("set_currency", response.data[1].rate);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    googleIn() {
      this.logIn(new firebase.auth.GoogleAuthProvider());
    },
    facebookLogIn() {
      this.logIn(new firebase.auth.FacebookAuthProvider());
    },
    auth(jwt) {
      axios
        .post("/auth", {
          input: jwt,
        })
        .then(function(response) {
          // app.user = response.data;
          // this.$router.push('/');
          location.replace("/");
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    logIn(provider) {
      firebase
        .auth()
        .signInWithPopup(provider)
        .then(function(result) {
          result.user.getIdToken().then((jwt) => app.auth(jwt));
        })
        .catch(function(error) {
          console.log(error);
          //different api error
          // var errorCode = error.code;
          // var errorMessage = error.message;
          // var email = error.email;
          // var credential = error.credential;
        });
    },
    logout() {
      axios.post("/logout").then(function(response) {
        app.user = null;
      });
      firebase
        .auth()
        .signOut()
        .then(function() {
          // Sign-out successful.
        })
        .catch(function(error) {
          console.log(error);
          // An error happened.
        });
    },
  },
});
