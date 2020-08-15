import "./bootstrap";
import router from "./router";
import store from "./store";

var data = {
  lng: null,
};
// eslint-disable-next-line no-new
new Vue({
  el: "#app",
  router,
  store,
  data: function() {
    return data;
  },
  // created() {
  //   this.$store.commit("set_currency", window.Laravel.currency.rate);
  //   if (window.Laravel.user) this.user = window.Laravel.user.name;
  // },
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
  },
});
