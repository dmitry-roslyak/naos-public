import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    compare: [],
    flt_ids: [],
    currency: 0,
    cart: {},
  },
  mutations: {
    cartClear(state) {
      state.cart = {};
      localStorage.cart = "";
    },
    cart(state, item) {
      if (item.toRemove) {
        state.cart[item.id] = 0;
        delete state.cart[item.id];
      } else Vue.set(state.cart, item.id, item.count);
      localStorage.cart = JSON.stringify(state.cart);
    },
    set_currency(state, value) {
      state.currency = value;
    },
    filterReset(state) {
      state.flt_ids = [];
    },
    filter(state, id) {
      var i = state.flt_ids.indexOf(id);
      i < 0 ? state.flt_ids.push(id) : state.flt_ids.splice(i, 1);
    },
    compare(state, item) {
      var categoryIndex = this.getters.compareCategoryIndex(item.category_id);

      if (categoryIndex < 0) {
        categoryIndex = state.compare.push({ categoryId: item.category_id, category: item.category, array: [] }) - 1;
      }

      var i = this.getters.isCompare(categoryIndex, item.id);
      i < 0 ? state.compare[categoryIndex].array.push(item.id) : state.compare[categoryIndex].array.splice(i, 1);
      !state.compare[categoryIndex].array.length && state.compare.splice(categoryIndex, 1);

      localStorage.compare = JSON.stringify(state.compare);
    },
    loadFromLocalStorage(state, propery) {
      if (localStorage[propery] && localStorage[propery].length) {
        try {
          state[propery] = JSON.parse(localStorage[propery]);
        } catch (error) {
          console.log(error);
          localStorage[propery] = "";
        }
      }
    },
  },
  getters: {
    compareCategoryIndex(state) {
      return (categoryId) => state.compare.findIndex((value) => value.categoryId === categoryId);
    },
    isCompare(state) {
      return (categoryIndex, itemId) => state.compare[categoryIndex].array.indexOf(itemId);
    },
    cartItemsCount(state) {
      var count = 0;
      for (const key in state.cart) {
        count += state.cart[key];
      }
      return count;
    },
    compareItemsCount(state) {
      var count = 0;
      for (const key in state.compare) {
        count += state.compare[key].array.length;
      }
      return count;
    },
  },
});
