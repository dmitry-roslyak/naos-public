<template>
  <div class="container-fluid">
    <div id="order-done" style="display:none" class="overlay-view">
      <h4>{{lng.order_done}} !</h4>
    </div>
    <hr />
    <div class="col-md-7 col-sm-12 cart-products">
      <div class="cart-caption">
        {{lng.cart}}
        <div class="pull-right">{{lng.total_sum +': '+total+' '+ lng.currency}}</div>
      </div>
      <table class="table">
        <tbody>
          <tr>
            <th />
            <th>{{lng.product}}</th>
            <th>{{lng.count}}</th>
            <th>{{lng.price}}</th>
          </tr>
          <tr v-for="(item, i) in products" :key="item.id">
            <td>
              <img :src="'/file/'+item.img_src" />
            </td>
            <td>
              <router-link :to="{ name: 'AppDetail', params: { id: item.id }}">{{item.name}}</router-link>
            </td>
            <td>
              <input
                v-model="item.count"
                class="form-control"
                type="number"
                @input="reCount(item.id, i)"
              />
            </td>
            <td style="white-space: nowrap;">{{itemPriceResult(item)}}</td>
            <div class="action-frm">
              <a class="action-item fake-link" @click="removeFromCart(item.id)">
                <span class="hidden-xs">{{lng.remove}}</span>
                <i class="fa fa-trash" aria-hidden="true" />
              </a>
            </div>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="col-sm-12 col-md-5">
      <app-user-info ref="userInfo" style="padding:0"></app-user-info>
      <hr />
      <label>{{lng.payment_type}}&nbsp;</label>
      <div class="col-xs-12">
        <div class="radio">
          <i class="fa fa-money" />
          <label>
            <input v-model="payment" type="radio" value="cash" />
            {{lng.cash}}
          </label>
        </div>
        <div class="radio">
          <i class="fa fa-credit-card" />
          <label>
            <input v-model="payment" type="radio" value="pay_card" />
            {{lng.paycard}}
          </label>
        </div>
      </div>
      <hr />
      <div v-if="payment=='pay_card'">
        <label>{{lng.paycard_info}}&nbsp;</label>
        <i class="fa fa-cc-visa" />
        <table class="table">
          <tbody>
            <tr>
              <td>{{lng.paycard_number}}</td>
              <td>
                <input
                  id="number"
                  v-model="card.number"
                  v-validate
                  class="form-control myinput1"
                  maxlength="19"
                  @keyup.13="card.number = '4005520000011126'; next_input($event.target, 'month')"
                />
              </td>
              <td>
                <i :class="validate['number'] ? 'fa fa-check-circle' : 'fa fa-times'" />
              </td>
            </tr>
            <tr>
              <td>Exp Date</td>
              <td>
                <div class="form-inline">
                  <input
                    id="month"
                    v-model="card.expire.month"
                    v-validate
                    class="form-control myinput1"
                    style="width:3em"
                    maxlength="2"
                    @keyup.13="next_input($event.target, 'year')"
                  />&nbsp;/
                  <input
                    id="year"
                    v-model="card.expire.year"
                    v-validate
                    class="form-control myinput1"
                    style="width:3em"
                    maxlength="2"
                    @keyup.13="next_input($event.target, 'cvv2')"
                  />
                </div>
              </td>
              <td>
                <i
                  :class="validate['month'] && validate['year'] ? 'fa fa-check-circle' : 'fa fa-times'"
                />
              </td>
            </tr>
            <tr>
              <td>CVV2</td>
              <td>
                <input
                  id="cvv2"
                  v-model="card.cvv2"
                  v-validate
                  class="form-control myinput1"
                  style="width:7em"
                  maxlength="4"
                />
              </td>
              <td>
                <i :class="validate.cvv2 ? 'fa fa-check-circle' : 'fa fa-times'" />
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <button class="btn btn-primary" @click="to_order">{{lng.confirm_order}}</button>
    </div>
    <hr />
  </div>
</template>

<script>
import Validator from "../validate";

var data = {
  products: [],
  payment: "cash",
  delivery: "customer",
  card: { number: "", expire: { month: "", year: "" }, cvv2: "" },
  validate: {},
};
var validator = new Validator(
  {
    number: /^(\d{13,19})$/,
    month: /^([0][1-9]|[1][0-2])$/,
    year: /^(\d{2})$/,
    cvv2: /^(\d{3,4})$/,
  },
  data.validate
);

export default {
  components: {
    AppUserInfo: () => import(/* webpackChunkName: "js/AppUserInfo-vue" */ "./AppUserInfo.vue"),
  },
  props: {
    ids: {
      type: String,
      default: function () {
        return "[]";
      },
    },
  },
  data: function () {
    return data;
  },
  computed: {
    lng() {
      return this.$root.lng;
    },
    total: function () {
      var res = 0;
      for (var i = 0; i < this.products.length; i++) {
        res += this.products[i].price * this.$store.state.currency * this.products[i].count;
      }
      return res.toFixed(1);
    },
    itemPriceResult() {
      return (item) => this.$root.itemPriceResult(item);
    },
  },
  created() {
    try {
      var parsed = this.ids && JSON.parse(this.ids);
    } catch (error) {
      console.log(error);
    }
    var requestIDs = parsed || Object.keys(this.$store.state.cart);
    if (requestIDs.length) this.get_prodsby_ids(requestIDs);
  },
  methods: {
    reCount(id, i) {
      if (!/^([1-9]\d{0,})$/.test(this.products[i].count)) {
        this.products[i].count = 1;
      }
      this.ids || this.$store.commit("cart", { id, count: +this.products[i].count });
    },
    removeFromCart(id) {
      this.products.forEach((element, i) => {
        if (element.id === id) this.products.splice(i, 1);
      });
      this.$store.commit("cart", { id: id, toRemove: true });
    },
    get_prodsby_ids(ids) {
      this.products.length = 0;
      axios.get("/products_with_discount_by_ids", { params: { ids: ids } }).then((response) => {
        response.data.forEach((element, i) => {
          element.count = this.$store.state.cart[element.id] || 1;
          this.products.push(element);
        });
      });
    },
    next_input(target, next) {
      this.validate[target.id] && document.getElementById(next).focus();
    },
    to_order() {
      if (this.payment === "pay_card" && !validator.isValid()) return;
      axios
        .post("/order", {
          products: this.products,
          card: this.card,
          user_info: this.$refs.userInfo.userInfo,
          payment: this.payment === "pay_card" ? "visa" : this.payment,
          delivery: this.delivery,
          delivery_adr: "somewhere",
        })
        .then((response) => {
          this.$store.commit("cartClear");
          document.getElementById("order-done").style = "display: initial";
          setTimeout((params) => {
            document.getElementById("order-done").style = "display: none";
            this.$router.push("/");
          }, 3000);
        });
    },
  },
};
</script>
