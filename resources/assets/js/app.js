import "./bootstrap";
import router from "./router";
import store from "./store";

// eslint-disable-next-line no-new
new Vue({
  el: "#app",
  router,
  store,
  data: function() {
    return {
      lng: null,
    };
  },
  created() {
    this.appLocaleSet(window.Laravel);
  },
  methods: {
    appLocaleSet(locale) {
      locale.lng.currency = locale.lng[locale.currency.name];
      this.lng = locale.lng;
      this.$store.commit("set_currency", locale.currency.rate);
    },
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
