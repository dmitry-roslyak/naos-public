import firebase from "firebase/app";
import "firebase/auth";

class Auth {
  static initialize() {
    firebase.initializeApp({
      apiKey: "AIzaSyB48itgCIjbWqAGmM5dbUXY-IuHnnDkWW4",
      authDomain: "naos-c6e11.firebaseapp.com",
      databaseURL: "https://naos-c6e11.firebaseio.com",
      projectId: "naos-c6e11",
      storageBucket: "naos-c6e11.appspot.com",
      messagingSenderId: "400801183019",
      appId: "1:400801183019:web:aaf0052d87e017ae4d816e",
    });

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
    fetch("/auth", {
      headers: new Headers({
        "X-CSRF-TOKEN": document.getElementsByName("csrf-token")[0].content,
        Authorization: "Bearer " + jwt,
      }),
    })
      .then((res) => {
        if (!res.ok) throw new Error("invalid token");
        location.replace("/");
      })
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
      fetch("/logout", {
        method: "post",
        headers: new Headers({
          "X-CSRF-TOKEN": document.getElementsByName("csrf-token")[0].content,
        }),
      }).catch((error) => console.log(error)),
      firebase
        .auth()
        .signOut()
        .catch((error) => console.log(error)),
    ];
    Promise.all(logout).then(() => location.replace("/"));
  }
}

if (__NODE_ENV === "production") {
  Auth.initialize();
}
