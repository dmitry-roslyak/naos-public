<template>
  <div class="container-fluid compare">
    <!-- <div>
            <canvas id="cmprGraph"></canvas>
    </div>-->
    <div v-show="!list.length" style="text-align: center;padding: 12em;">
      <router-link :to="'/products/' + category" style="display:block;margin-top:10px">{{
        lng.compareAddMore
      }}</router-link>
    </div>
    <table v-if="list.length > 0" class="table" style="margin-top: 1rem;">
      <tbody style="white-space: nowrap;">
        <tr style="display:inline-block">
          <td style="padding: 25px;width: 12em;height: 12rem;text-align:center;border: inherit">
            <button
              class="btn btn-primary btn-sm"
              style="width: 100%;"
              @click="diffType ? (diffType = 0) : (diffType = 1)"
            >
              {{ diffType == 1 ? "%" : lng.value }}
            </button>
            <router-link :to="'/products/' + category" style="display:block;margin-top:10px">{{
              lng.compareAddMore
            }}</router-link>
          </td>
          <td
            v-for="specs in list[0].specs"
            :key="specs.name"
            class="td_name"
            style="width: 12em;float:left;clear:both"
          >
            <!-- <i class="fa fa-bar-chart"  aria-hidden="true"/> -->
            {{ lng[specs.name] ? lng[specs.name] : specs.name }}
            {{ specs.name == "price" ? lng.currency : "" }}
          </td>
        </tr>
        <tr v-for="(product, i) in list" :key="i" class="table-item t-name" @mouseenter="cmpr(i)">
          <td style="float:left;clear:both;width:100%;height: 12rem;position:relative">
            <div class="action-frm" style="top:0">
              <a class="action-item fake-link" @click="removeItem(i)">
                <span class="hidden-xs">{{ lng.remove }}</span>
                <i class="fa fa-trash" aria-hidden="true" />
              </a>
            </div>
            <div class="thumbnail" style="margin:0;border: 0;">
              <img :src="'/file/' + product.img_src" style="max-height: 4em" @error="img404($event.target)" />
            </div>
            <router-link class="t-name" :to="{ name: 'AppDetail', params: { id: product.id } }">{{
              product.name
            }}</router-link>
            <star-rating :rating="+product.rating" :star-size="16" :show-rating="false" :read-only="true" />
          </td>
          <td
            v-for="specs in product.specs"
            :key="specs.name"
            style="float:left;clear:both;width:100%;overflow: hidden;text-overflow: ellipsis;"
          >
            {{ specs.value }}
            <span v-show="+specs.diff" :style="specs.diff < 0 ? 'color:red' : 'color:green'">{{
              (specs.diff > 0 ? "(+" : "(") + specs.diff + (diffType == 0 ? "%) " : ") ")
            }}</span>
            {{ specs.val_type }}
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script>
export default {
  props: {
    ids: {
      type: String, //JSON Array
      required: true,
    },
    category: {
      type: String,
      required: true,
    },
  },
  data: function() {
    return {
      list: [],
      diffType: 0,
    };
  },
  computed: {
    lng() {
      return this.$root.lng;
    },
  },
  watch: {
    "$route.params.ids": "productsFetch",
  },
  created() {
    this.productsFetch();
    // selfChart = new Chart(document.getElementById('cmprGraph'), {
    //     type: 'polarArea',
    //     data: chartData,
    //     options: {}//{ legend: { display: true} }
    // });
    // this.$forceUpdate();
  },
  methods: {
    removeItem(i) {
      this.$store.commit("compare", this.list[i]);
      this.list.splice(i, 1);
    },
    img404(e) {
      e.src = "/images/404.png";
    },
    cmpr(x) {
      this.list.forEach((product, i) => {
        product.specs.forEach((spec, j) => {
          if (spec.isComparable && i !== x) {
            if (this.diffType === 0) {
              spec.diff = (Math.round((spec.value / this.list[x].specs[j].value) * 100) - 100).toFixed(1);
            } else spec.diff = (spec.value - this.list[x].specs[j].value).toFixed(1);
          } else spec.diff = 0;
        });
      });
      this.$forceUpdate();
    },
    productsFetch() {
      axios.get("/products", { params: { ids: JSON.parse(this.ids) } }).then((response) => {
        this.list = response.data.forEach((product) => {
          product.specs.unshift({
            name: "price",
            value: this.$root.itemPriceResult(product).split(" ")[0],
            isComparable: true,
          });
        });
        this.list = response.data;
      });
    },
  },
};
</script>
