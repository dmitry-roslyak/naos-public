<template>
  <div class="container-fluid app-detail">
    <div class="col-md-7">
      <!-- <h4 style="padding-left:8px">{{item.name}}</h4> -->
      <div class="action-frm-wrapper">
        <div class="action-frm">
          <a class="action-item fake-link" @click="to_compare()">
            <span class="hidden-xs">{{lng.to_compare}}</span>
            <i
              class="fa fa-balance-scale compare-state anm-bounce-scale"
              :data-check="item.is_compare"
              aria-hidden="true"
            ></i>
          </a>&nbsp;
          <a class="action-item fake-link" @click="to_wish()">
            <span class="hidden-xs">{{lng.to_wishlist}}</span>
            <i
              class="fa fa-heart heart-state anm-bounce-scale"
              :data-check="item.isWish"
              aria-hidden="true"
            ></i>
          </a>&nbsp;
          <a
            class="action-item fake-link"
            :href="'https://www.facebook.com/dialog/share?app_id=1358482950908486&display=popup&href='+href"
            target="_blank"
          >
            <span class="hidden-xs">{{lng.share}}</span>
            <i class="fa fa-facebook-square"></i>
          </a>
        </div>
      </div>
      <div id="carousel2" class="carousel slide carousel-background-color" data-ride="carousel">
        <ol v-if="item.gallery" class="carousel-indicators">
          <li data-target="#carousel2" data-slide-to="0" class="active"></li>
        </ol>
        <div class="carousel-inner" role="listbox">
          <div class="item active image-wrapper">
            <img :src="item.img_src && '/file/'+item.img_src" />
            <div v-if="+offerTime > 0" class="tb-offer">
              <span>{{lng.discount +' -'+item.discount.discount+'%'}}</span>
              <span class="hidden-xs">{{offer}}</span>
            </div>
          </div>
        </div>
        <div class="col-xs-9" style="position: absolute;bottom: 0;">
          <div class="product-state">
            <s v-if="item.discount&&item.available">{{(currency * item.price).toFixed(1)}}</s>
            <span>{{itemPriceResult(item)}}</span>
          </div>
          <star-rating
            :rating="item.user_rating ? item.user_rating.rating : +item.rating"
            :star-size="21"
            :show-rating="false"
            :active-color="item.user_rating ? 'orangered' : '#ffd055'"
            @rating-selected="product_rate($event)"
          ></star-rating>
          {{item.vote_count}}
        </div>
        <div class="app-detail-btn-group">
          <div v-if="item.available > 0" class="btn-group" role="group" aria-label="...">
            <button type="button" class="btn btn-default action-item" @click="addToCart()">
              <span>{{lng.addto_cart}}</span>
              <i
                class="fa fa-cart-plus btn-in-cart-i anm-bounce-scale"
                :data-check="item.isInCart"
                aria-hidden="true"
              ></i>&nbsp;&nbsp;
            </button>
            <button type="button" class="btn btn-primary" @click="buy()">{{lng.buy}}</button>
          </div>
          <button v-else type="button" class="btn btn-primary disabled">{{lng.not_in_stock}}</button>
        </div>
      </div>
      <ul class="nav nav-tabs">
        <li role="presentation" :class="{ active: show_specs}" @click="show_specs=true">
          <a>{{lng.specs}}</a>
        </li>
        <li role="presentation" :class="{ active: !show_specs}" @click="show_specs=false">
          <a>{{lng.descr}}</a>
        </li>
      </ul>
      <table v-if="show_specs&&item" class="table border1">
        <tbody>
          <tr v-for="(specs,i) in item.specs" :key="i">
            <td>{{lng[specs.name]?lng[specs.name]:specs.name}}</td>
            <td>{{specs.value}}&nbsp;{{specs.val_type}}</td>
          </tr>
        </tbody>
      </table>
      <div v-else class="border1">{{item.description}}</div>
      <app-detail-charts v-if="showGraph" :product-id="id"></app-detail-charts>
    </div>
    <app-comments class="col-md-5" :product-id="id"></app-comments>
  </div>
</template>
<script>
import AppDetailCharts from "./AppDetailCharts.vue";

var timerId;
var data = {
  show_specs: true,
  item: {},
  offerTime: null,
  showGraph: true,
};
export default {
  components: {
    AppComments: () => import(/* webpackChunkName: "js/AppComments-vue" */ "./AppComments.vue"),
    AppDetailCharts,
  },
  props: {
    id: {
      type: [String, Number],
      required: true,
    },
  },
  data: function () {
    return data;
  },
  computed: {
    href() {
      return location.href;
    },
    currency() {
      return this.$store.state.currency;
    },
    lng() {
      return this.$root.lng;
    },
    itemPriceResult() {
      return (item) => this.$root.itemPriceResult(item);
    },
    offer() {
      function zero(value) {
        if (value < 10) value = "0" + value;
        return value;
      }
      return (
        this.lng.offer_end_at +
        " " +
        this.offerTime.getDate() +
        " " +
        this.lng.offer_d +
        " " +
        zero(this.offerTime.getHours()) +
        ":" +
        zero(this.offerTime.getMinutes()) +
        ":" +
        zero(this.offerTime.getSeconds())
      );
    },
  },
  watch: {
    id: "itemById",
  },
  created() {
    this.itemById();
    this.clientWidth();
    window.onresize = () => {
      this.clientWidth();
    };
    window.onblur = () => {
      if (this.$route.path.indexOf("detail") > -1) clearInterval(timerId);
    };
    window.onfocus = () => {
      if (this.$route.path.indexOf("detail") > -1) {
        clearInterval(timerId);
        this.set_total_time();
      }
    };
  },
  destroyed() {
    if (timerId) clearInterval(timerId);
  },
  methods: {
    clientWidth() {
      this.showGraph = document.documentElement.clientWidth > 620;
    },
    addToCart() {
      this.item.isInCart = !this.$store.state.cart[this.item.id];
      this.$store.commit("cart", { id: this.item.id, count: 1, toRemove: !this.item.isInCart });
    },
    buy() {
      this.$router.push(`/cart/[${this.item.id}]`);
    },
    to_compare() {
      this.item.is_compare = !this.item.is_compare;
      this.$store.commit("compare", { id: this.item.id, category_id: this.item.category_id, category: this.category });
    },
    product_rate(value) {
      axios.get("/product_rate", {
        params: {
          id: this.item.id,
          rating: value,
        },
      });
    },
    to_wish() {
      this.item.isWish = !this.item.isWish;
      axios
        .post("/to_wish", {
          id: this.item.id,
        })
        .catch(() => {
          this.item.isWish = !this.item.isWish;
        });
    },
    itemById() {
      axios.get("/prod_by_id?id=" + this.id).then((response) => {
        response.data.isWish = !!response.data.wish;
        response.data.is_compare = false;
        response.data.isInCart = !!this.$store.state.cart[response.data.id];
        var categoryIndex = this.$store.getters.compareCategoryIndex(response.data.category_id);
        response.data.is_compare =
          categoryIndex > -1 && this.$store.getters.isCompare(categoryIndex, response.data.id) > -1;
        this.item = response.data;
        this.set_total_time();
      });
    },
    set_total_time() {
      this.offerTime = null;
      if (this.item && this.item.discount) {
        this.offerTime = new Date(this.item.discount.end_at) - new Date();
        this.tick();
      }
    },
    tick() {
      if (+this.offerTime < 1) return;
      this.offerTime = new Date(this.offerTime - 1000);
      timerId = setTimeout(() => {
        this.tick();
      }, 1000);
    },
  },
};
</script>
