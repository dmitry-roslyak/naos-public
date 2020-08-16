<template>
  <div class="container-fluid">
    <app-products-filters class="col-sm-3 col-md-2" style="padding: 0" />
    <div class="col-sm-9 col-md-10" style="padding:0">
      <div class="panel panel-default itmc">
        <div class="panel-body">
          <label>{{ lng.showed_items }}</label>
          <div class="dropdown">
            <a class="dropdown-toggle fake-link" data-toggle="dropdown" aria-haspopup="true">{{ items.length }}</a>
            <ul class="dropdown-menu">
              <li>
                <a class="fake-link" @click="paginator.take = 20">20</a>
              </li>
              <li>
                <a class="fake-link" @click="paginator.take = 30">30</a>
              </li>
              <li>
                <a class="fake-link" @click="paginator.take = 40">40</a>
              </li>
            </ul>
          </div>
          ({{ paginator.total }})
          <div class="pull-right">
            <label for="sortby">{{ lng.sortby }}&nbsp;</label>
            <select id="sortby" v-model="ordby" class="form-control input-sm" @change="productsfetch()">
              <option value="bydef">{{ lng.bydef }}</option>
              <option value="asc_price">{{ lng.asc_price }}</option>
              <option value="desc_price">{{ lng.desc_price }}</option>
              <option value="byrating">{{ lng.byrating }}</option>
              <option value="bynewest">{{ lng.bynewest }}</option>
            </select>
          </div>
        </div>
      </div>
      <div v-for="(item, i) in items" :key="item.id" class="col-sm-6 col-md-4 col-lg-3 item-card">
        <div>
          <div class="image-wrapper">
            <img :src="'/file/' + item.img_src" @load="imgReady($event.target)" @error="img404($event.target)" />
          </div>
          <div v-if="item.isArriveSoon" class="item-note soon">{{ lng.soon }}</div>
          <div v-else-if="item.isNew" class="item-note new">{{ lng.new }}</div>
          <div v-else-if="item.discount" class="item-note offer">{{ lng.offer }}</div>
          <div v-else-if="item.is_bestseller == 1" class="item-note hot">{{ lng.hot }}</div>
          <div class="action-frm">
            <a class="action-item fake-link" @click="to_compare(i)">
              <span class="hidden-xs">{{ lng.to_compare }}</span>
              <i
                class="fa fa-balance-scale compare-state anm-bounce-scale"
                :data-check="item.is_compare"
                aria-hidden="true"
              />
            </a>
            <a class="action-item fake-link" @click="to_wish(i)">
              <span class="hidden-xs">{{ lng.to_wishlist }}</span>
              <i class="fa fa-heart heart-state anm-bounce-scale" :data-check="item.isWish" aria-hidden="true" />
            </a>
          </div>
          <div class="caption">
            <router-link class="item-card-name" :to="{ name: 'AppDetail', params: { id: item.id } }">{{
              item.name
            }}</router-link>
            <star-rating :rating="+item.rating" :star-size="16" :show-rating="false" :read-only="true"></star-rating>
            <div class="product-state pull-right">
              <s v-if="item.discount && item.available">{{ (currency * item.price).toFixed(1) }}</s>
              <span v-if="!item.available">{{ lng.not_in_stock }}</span>
              <span v-else>{{ itemPriceResult(item) }}</span>
            </div>
            <div class="btn-group pull-right">
              <button type="button" class="btn btn-default action-item" @click="addToCart(i, item.id)">
                <span>{{ lng.addto_cart }}</span>
                <i
                  class="fa fa-cart-plus btn-in-cart-i anm-bounce-scale"
                  :data-check="item.isInCart"
                  aria-hidden="true"
                />
              </button>
              <button type="button" class="btn btn-primary" @click="buy(item.id)">{{ lng.buy }}</button>
            </div>
            <table class="item-spec">
              <tbody>
                <tr v-for="(specs, j) in item.specs" :key="j">
                  <td>{{ lng[specs.name] ? lng[specs.name] : specs.name }}</td>
                  <td>{{ specs.value }}&nbsp;{{ specs.val_type }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <v-pagination v-model="paginator" class="col-xs-12"></v-pagination>
  </div>
</template>
<script>
import AppProductsFilters from "./AppProductsFilters.vue";

var data = {
  ordby: "bydef",
  items: [],
  price: {
    array: [],
    range: [null, null],
  },
  paginator: {
    total: 0,
    take: 30,
    skip: 0,
    page: 1,
  },
};
export default {
  components: {
    AppProductsFilters,
    VPagination: () => import(/* webpackChunkName: "js/VPagination-vue" */ "./VPagination.vue"),
  },
  props: {
    category: {
      type: String,
      required: true,
    },
  },
  data: function() {
    return data;
  },
  computed: {
    lng() {
      return this.$root.lng;
    },
    currency() {
      return this.$store.state.currency;
    },
    itemPriceResult() {
      return (item) => this.$root.itemPriceResult(item);
    },
  },
  watch: {
    "$store.state.flt_ids": "productsfetch",
    "paginator.take": "productsfetch",
    "paginator.skip": function() {
      this.productsfetch("withSkip");
    },
    "price.range": function(array) {
      array.length === 2 && this.productsfetch();
    },
  },
  methods: {
    imgReady(e) {
      e.style.visibility = "initial";
    },
    img404(e) {
      e.src = "/images/404.png";
      e.style.visibility = "initial";
      e.style.padding = "4em";
    },
    productsfetch: debounce(function(arg) {
      if (arg !== "withSkip") {
        this.paginator.skip = 0;
        this.paginator.page = 1;
      }
      var price = [this.price.range[0] / this.currency, this.price.range[1] / this.currency];
      axios
        .get("/prod_filter", {
          params: {
            ctg_id: window.Laravel.catalog[this.category].id,
            skip: this.paginator.skip,
            take: this.paginator.take,
            f: this.$store.state.flt_ids,
            price: price,
            ordby: this.ordby,
          },
        })
        .then((response) => {
          var categoryIndex = this.$store.getters.compareCategoryIndex(window.Laravel.catalog[this.category].id);
          var items = response.data[1];
          for (var i = 0; i < items.length; i++) {
            items[i].isInCart = !!this.$store.state.cart[items[i].id];
            items[i].isWish = !!items[i].wish;
            items[i].is_compare = categoryIndex > -1 && this.$store.getters.isCompare(categoryIndex, items[i].id) > -1;
            items[i].isArriveSoon = new Date(items[i].arrive_date) > new Date();
            items[i].isNew = new Date(items[i].arrive_date) > new Date() - 1000 * 60 * 60 * 24 * 21;
          }
          this.items = items;

          this.price.array = response.data[2]
            .map((el) => +el.price)
            .sort((a, b) => {
              return a - b;
            });

          if (!this.price.range[0] && !this.price.range[1]) {
            this.price.range = [
              (this.price.array[0] * this.currency).toFixed(1),
              (this.price.array[this.price.array.length - 1] * this.currency).toFixed(1),
              { doNotFetch: true },
            ];
          }
          this.paginator.total = response.data[0];
        });
    }, 750),
    addToCart(i, id) {
      this.items[i].isInCart = !this.$store.state.cart[id];
      this.$store.commit("cart", { id: id, count: 1, toRemove: !this.items[i].isInCart });
    },
    buy(id) {
      this.$router.push(`/cart/[${id}]`);
    },
    to_compare(i) {
      this.items[i].is_compare = !this.items[i].is_compare;
      this.$store.commit("compare", {
        id: this.items[i].id,
        category_id: this.items[i].category_id,
        category: this.category,
      });
    },
    to_wish(i) {
      this.items[i].isWish = !this.items[i].isWish;
      axios
        .post("/to_wish", {
          id: this.items[i].id,
        })
        .catch(() => {
          this.items[i].isWish = !this.items[i].isWish;
        });
    },
  },
};
</script>
