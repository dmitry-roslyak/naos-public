<template>
  <nav class="navbar">
    <div class="container">
      <div class="navbar-header">
        <router-link to="/" class="navbar-brand">NAOS</router-link>
        <button
          type="button"
          class="navbar-toggle collapsed"
          data-toggle="collapse"
          data-target="#app-navbar-collapse"
        >
          <span class="sr-only">Toggle Navigation</span>
          <span class="icon-bar" />
          <span class="icon-bar" />
          <span class="icon-bar" />
        </button>
      </div>
      <div id="app-navbar-collapse" class="collapse navbar-collapse">
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
            <a
              href="#"
              class="dropdown-toggle"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
              role="button"
            >
              <i class="fa fa-globe" />
              {{ lng.lang_name }}
            </a>
            <ul class="dropdown-menu">
              <li v-for="(lng2, ISO_code) in langs" :key="lng2.text">
                <a href="#" @click.prevent="get_locale(ISO_code)">
                  <img :src="'/images/' + ISO_code + '.png'" loading="lazy" />
                  {{ lng2.text }}
                </a>
              </li>
            </ul>
          </li>
          <li>
            <div class="input-group input-group-sm search">
              <span class="input-group-addon">
                <i class="fa fa-search" />
              </span>
              <input
                type="search"
                class="form-control"
                :placeholder="lng.search"
                @focus="search_result || toSearch()"
                @input="toSearch()"
              />
              <ul class="dropdown-menu" style="display: initial">
                <li v-for="item in search_result" :key="item.id">
                  <router-link :to="{ name: 'AppDetail', params: { id: item.id } }">
                    <div class="search-img-wrapper">
                      <img :src="'/file/' + item.img_src" />
                    </div>
                    <div>
                      {{ item.name }}
                      <star-rating
                        :rating="+item.rating"
                        :star-size="16"
                        :show-rating="false"
                        :inactive-color="'black'"
                        :active-color="'deepskyblue'"
                        :glow="2"
                        :glow-color="'deepskyblue'"
                        :read-only="true"
                      />
                    </div>
                    <nobr>{{ itemPriceResult(item) }}</nobr>
                  </router-link>
                </li>
              </ul>
            </div>
          </li>
          <li>
            <router-link to="/cart">
              <i class="fa fa-shopping-cart" aria-hidden="true" />
              <span>{{ lng.cart }}</span>
              <span class="badge">{{ this.$store.getters.cartItemsCount }}</span>
            </router-link>
          </li>
          <li>
            <a
              id="compare"
              href="#"
              role="button"
              @click.prevent="$store.state.compare.length == 1 && toCompare(0)"
            >
              <i class="fa fa-balance-scale" aria-hidden="true" />
              <span>{{ lng.compare }}</span>
              <span class="badge">{{ this.$store.getters.compareItemsCount }}</span>
              <ul
                v-show="this.$store.state.compare.length > 1"
                class="dropdown-menu"
                style="display: initial"
              >
                <li v-for="(item, key) in compare" :key="key">
                  <a
                    href="#"
                    @click.prevent="toCompare(key)"
                  >{{ lng[item.category] + ": " + item.array.length }}</a>
                </li>
              </ul>
            </a>
          </li>
          <li v-if="!user">
            <a href="/login">{{ lng.login }}</a>
          </li>
          <li v-if="!user">
            <a href="/register">{{ lng.register }}</a>
          </li>
          <li v-if="user" class="dropdown">
            <a
              href="#"
              class="dropdown-toggle"
              data-toggle="dropdown"
              role="button"
              aria-expanded="false"
            >
              <i class="fa fa-user" />
              {{ user }}
            </a>
            <ul class="dropdown-menu">
              <li>
                <router-link to="/account">
                  <i class="fa fa-address-card" />
                  {{ lng.myprofile }}
                </router-link>
              </li>
              <li>
                <a href="/logout">
                  <i class="fa fa-sign-out" />
                  {{ lng.logout }}
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>
<script>
var data = {
  lng: null,
  user: null,
  langs: null,
  search_result: null,
};
export default {
  data: function () {
    return data;
  },
  computed: {
    compare() {
      return this.$store.state.compare;
    },
    itemPriceResult() {
      return (item) => this.$root.itemPriceResult(item);
    },
  },
  watch: {
    lng: function () {
      this.$root.lng = this.lng;
    },
  },
  created() {
    this.langs = window.Laravel.langsAvailable;
    this.lng = window.Laravel.lng;
    this.$store.commit("set_currency", window.Laravel.currency.rate);
    if (window.Laravel.user) this.user = window.Laravel.user.name;

    this.$store.commit("loadFromLocalStorage", "compare");
    this.$store.commit("loadFromLocalStorage", "cart");
    this.$router.afterEach((to, from, next) => {
      this.search_result = null;
    });
  },
  methods: {
    toCompare(i) {
      document.getElementById("compare").blur();
      this.$router.push(
        `/compare/${this.$store.state.compare[i].category}/${JSON.stringify(this.$store.state.compare[i].array)}`
      );
    },
    toSearch: debounce(function () {
      var text = document.querySelector(".search > input").value;
      this.search_result = null;
      text.length &&
        axios
          .post("/search", {
            search: text,
          })
          .then((response) => {
            this.search_result = response.data;
          });
    }, 400),
    get_locale(lng) {
      axios
        .get("/lang/" + lng)
        .then((response) => {
          response.data[0].currency = response.data[0][response.data[1].name];
          this.lng = window.Laravel.lng = response.data[0];
          this.$store.commit("set_currency", response.data[1].rate);
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>
