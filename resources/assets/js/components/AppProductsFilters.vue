<template>
  <div class="container-fluid">
    <div class="list-group">
      <a class="list-group-item active">
        <i class="fa fa-list" />
        {{ lng.catalog }}
      </a>
      <a
        v-for="(item, name) in catalog"
        :key="item.id"
        class="list-group-item fake-link"
        @click="get_filters(name, item.id)"
      >
        {{ lng[name] ? lng[name] : name }}
      </a>
    </div>

    <div class="flt-grp" style="padding: 1rem">
      <label>{{ lng.showed_items }}</label>
      <div class="dropdown" style="padding-left: 10px;">
        <a class="dropdown-toggle fake-link" data-toggle="dropdown" aria-haspopup="true">{{ display_items }}</a>
        {{ total_items }}
        <ul class="dropdown-menu">
          <li>
            <a class="fake-link" @click="$emit('take', 20)">20</a>
          </li>
          <li>
            <a class="fake-link" @click="$emit('take', 30)">30</a>
          </li>
          <li>
            <a class="fake-link" @click="$emit('take', 40)">40</a>
          </li>
        </ul>
      </div>
      <label for="sortby">{{ lng.sortby }}&nbsp;</label>
      <select id="sortby" class="form-control input-sm" @change="$emit('ordby', $event.target.value)">
        <option value="bydef">{{ lng.bydef }}</option>
        <option value="asc_price">{{ lng.asc_price }}</option>
        <option value="desc_price">{{ lng.desc_price }}</option>
        <option value="byrating">{{ lng.byrating }}</option>
        <option value="bynewest">{{ lng.bynewest }}</option>
      </select>
    </div>

    <div style="font-weight:bold;padding: 1rem">
      <i class="fa fa-filter" />
      {{ lng.filters }}
      <span v-show="showClear" class="flt-btn fake-link" style="float:right;padding: 0;" @click="flt_reset()">
        {{ lng.filtersReset }}
        <i class="fa fa-times" />
      </span>
    </div>

    <div class="flt-grp">
      <div class="flt-btn fake-link" @click="expand($event.currentTarget)">
        {{ lng.price }}
        <i class="fa fa-angle-up font1 pull-right" style="display:none" aria-hidden="true" />
        <i class="fa fa-angle-down font1 pull-right" aria-hidden="true" />
      </div>
      <div class="flip">
        {{ lng.from }}
        <div class="input-group">
          <input v-model.number="price.range[0]" type="number" class="form-control myinput1" />
          <span class="input-group-addon">{{ lng.currency }}</span>
        </div>
        {{ lng.to }}
        <div class="input-group">
          <input v-model.number="price.range[1]" type="number" class="form-control myinput1" />
          <span class="input-group-addon">{{ lng.currency }}</span>
        </div>
        <range ref="range" v-model="vRangeSlidersPosition" style="margin-top:8px" @change="rangeReset()" />
      </div>
    </div>
    <div v-for="(filter, i1) in filters" :key="filter.id" class="flt-grp">
      <div class="flt-btn fake-link" @click="expand($event.currentTarget)">
        <!-- <span :title="filter.desc"><i class="fa fa-info-circle"></i></span> -->
        {{ lng[filter.name] ? lng[filter.name] : filter.name }}
        <i class="fa fa-angle-down pull-right" style="display:none" aria-hidden="true"></i>
        <i class="fa fa-angle-up pull-right" aria-hidden="true"></i>
      </div>
      <div class="flip">
        <div v-for="(value, i2) in filter.values" :key="value.id" class="checkbox">
          <label>
            <input type="checkbox" :data-id="value.id" :data-i1="i1" :data-i2="i2" @click="toFilter($event)" />
            {{ value.value }}
            <!-- {{value.value+' ('+value.count+')'}} -->
          </label>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import range from "vrange";
var data = {
  catalog: [],
  filters: [],
  vRangeSlidersPosition: [0, 100],
  price: {},
};
export default {
  components: {
    range,
  },
  props: {
    // eslint-disable-next-line vue/prop-name-casing
    total_items: {
      type: Number,
      default: 0,
    },
    // eslint-disable-next-line vue/prop-name-casing
    display_items: {
      type: Number,
      default: 0,
    },
  },
  data: function() {
    return data;
  },
  computed: {
    lng() {
      return this.$root.lng;
    },
    showClear: function() {
      return this.$store.state.flt_ids.length;
    },
  },
  created() {
    this.catalog = window.Laravel.catalog;
    this.price = this.$parent.price;
    if (window.Laravel.catalog[this.$parent.category]) {
      this.get_filters(this.$parent.category, window.Laravel.catalog[this.$parent.category].id);
    }
  },
  methods: {
    rangeReset() {
      if (!this.price.array.length) return;

      var percentPerArrayItem = 100 / (this.price.array.length - 1);
      var priceFrom = this.price.array[Math.round(this.vRangeSlidersPosition[0] / percentPerArrayItem)];
      var priceTo = this.price.array[Math.round(this.vRangeSlidersPosition[1] / percentPerArrayItem)];

      this.price.range = [
        (priceFrom * this.$store.state.currency).toFixed(1),
        (priceTo * this.$store.state.currency).toFixed(1),
      ];
    },
    expand: throttle(
      function(el) {
        $(el.parentElement.getElementsByClassName("flip")[0]).slideToggle();
        $(el.getElementsByClassName("fa-angle-up")[0]).toggle();
        $(el.getElementsByClassName("fa-angle-down")[0]).toggle();
      },
      300,
      { trailing: false }
    ),
    flt_reset() {
      var checkList = document.getElementsByClassName("checkbox");
      for (var i = 0; i < checkList.length; i++) {
        checkList[i].firstChild.firstChild.checked = false;
      }
      this.price.range = [null, null];
      this.$store.commit("filterReset");
    },
    get_filters(name, id) {
      this.$router.push("/products/" + name);
      axios.get("/get_filters?id=" + id).then((response) => {
        this.filters = response.data;
      });
      this.flt_reset();
    },
    toFilter(e) {
      this.$store.commit("filter", this.filters[e.target.dataset.i1].values[e.target.dataset.i2].id);
    },
  },
};
</script>
