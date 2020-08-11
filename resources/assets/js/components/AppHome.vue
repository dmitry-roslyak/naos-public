<template>
  <div class="container-fluid" style="max-width: 80em;">
    <div class="col-sm-3 col-md-2" style="padding: 0px;">
      <div class="list-group">
        <a class="list-group-item active">
          <i class="fa fa-list" />
          {{ lng.catalog }}
        </a>
        <router-link
          v-for="(item, name) in catalog"
          :key="item.id"
          :to="{ name: 'AppProducts', params: { category: name } }"
          class="list-group-item"
        >{{ lng[name] ? lng[name] : name }}</router-link>
        <a v-for="i in dummyCategory" :key="i" class="list-group-item">&nbsp;</a>
      </div>
    </div>
    <div class="col-sm-9 col-md-10">
      <div id="carousel1" class="carousel slide carousel-background-color" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
          <li
            v-for="(item, i) in items"
            :key="item.id"
            :class="{ active: !i }"
            data-target="#carousel1"
            :data-slide-to="i"
          />
        </ol>
        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
          <div
            v-for="(item, i) in items"
            :key="item.id"
            :class="{ 'item image-wrapper': true, active: !i }"
          >
            <img :src="'/file/' + item.img_src" :alt="item.name" />
            <div class="carousel-caption carousel-content">
              <h3>
                <router-link :to="{ name: 'AppDetail', params: { id: item.id } }">{{ item.name }}</router-link>
              </h3>
              <span
                style="font-style: italic; text-shadow: 0 0 1rem black;"
              >{{ itemPriceResult(item) }}</span>
            </div>
          </div>
        </div>
        <a class="left carousel-control" href="#carousel1" role="button" data-slide="prev">
          <span class="icon-prev" />
          <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#carousel1" role="button" data-slide="next">
          <span class="icon-next" />
          <span class="sr-only">Next</span>
        </a>
      </div>
    </div>
  </div>
</template>
<script>
var itemsCountMax = 7;
var data = {
  catalog: {},
  items: [],
  dummyCategory: [],
};
export default {
  data: function () {
    return data;
  },
  computed: {
    lng() {
      return this.$root.lng;
    },
    itemPriceResult() {
      return (item) => this.$root.itemPriceResult(item);
    },
  },
  mounted() {
    this.catalog = window.Laravel.catalog;
    this.get_random_products();
  },
  methods: {
    get_random_products() {
      axios.get("/prod_rnd").then((response) => {
        this.items = response.data;
        this.dummyCategory.length = itemsCountMax - Object.keys(this.catalog).length;
      });
    },
  },
};
</script>
