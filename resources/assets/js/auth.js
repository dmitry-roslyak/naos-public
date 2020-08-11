import firebase from "firebase/app";
import "firebase/auth";
import axios from "axios";

class Auth {
  static initialize() {
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

    if (window.location.pathname.includes("logout")) {
      Auth.logout();
    }

    window.googleIn = Auth.googleIn;
    window.facebookLogIn = Auth.facebookLogIn;
  }

  static googleIn() {
    Auth.logIn(new firebase.auth.GoogleAuthProvider());
  }

  static facebookLogIn() {
    Auth.logIn(new firebase.auth.FacebookAuthProvider());
  }

  static fetch(jwt) {
    axios
      .post("/auth", {
        input: jwt,
      })
      .then(() => location.replace("/"))
      .catch((error) => console.log(error));
  }

  static logIn(provider) {
    firebase
      .auth()
      .signInWithPopup(provider)
      .then(function(result) {
        result.user.getIdToken().then((jwt) => Auth.fetch(jwt));
      })
      .catch(function(error) {
        console.log(error);
        //different api error
        // var errorCode = error.code;
        // var errorMessage = error.message;
        // var email = error.email;
        // var credential = error.credential;
      });
  }

  static logout() {
    const logout = [
      axios.post("/logout").catch((error) => console.log(error)),
      firebase
        .auth()
        .signOut()
        .catch((error) => console.log(error)),
    ];
    Promise.all(logout).then(() => location.replace("/"));
  }
}

Auth.initialize();
