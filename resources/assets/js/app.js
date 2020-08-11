import "./bootstrap";
import router from "./router";
import store from "./store";

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
  },
});
