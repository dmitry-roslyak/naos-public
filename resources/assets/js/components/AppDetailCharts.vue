<template>
  <div>
    <div class="panel panel-primary" style="margin:15px 0 0;border-width: 0;">
      <div class="panel-heading" style="padding:6px 15px">
        <i class="fa fa-bar-chart" aria-hidden="true"></i>
        <span>{{ lng.hist_graph }}&nbsp;</span>
        <div class="btn-group" role="group" aria-label="...">
          <button
            id="graph_btn1"
            type="button"
            class="btn btn-default btn-sm"
            @click="show_hist(1)"
          >{{ lng.price_hist }}</button>
          <button
            id="graph_btn2"
            type="button"
            class="btn btn-default btn-sm"
            @click="show_hist(2)"
          >{{ lng.sales_hist }}</button>
        </div>
      </div>
      <div class="panel-body" style="padding:4px">
        <canvas id="graph" height="70"></canvas>
      </div>
    </div>
  </div>
</template>
<script>
import { Chart } from "chart.js";

var chart_data = {
  datasets: [
    {
      lineTension: 0,
      backgroundColor: "rgba(255, 99, 132, 0.2)",
      borderColor: "rgba(255,99,132,1)",
    },
  ],
};
var options1 = {
  legend: { display: false },
  tooltips: {
    displayColors: false,
  },
  scales: {
    xAxes: [
      {
        type: "time",
        time: {
          tooltipFormat: "DD.MM.YY",
          unitStepSize: 1,
          unit: "day",
          displayFormats: {
            hour: "MM.YY", // Sept 4, 5PM
            day: "DD.MM.YY", // Sep 4 2015
            week: "ll", // Week 46, or maybe "[W]WW - YYYY" ?
            month: "DD.MM.YYYY", // Sept 2015
          },
        },
      },
    ],
  },
};

var chart_self;
var data = {
  ready: false,
};
export default {
  props: {
    productId: {
      type: [String, Number],
      required: true,
    },
  },
  data: function () {
    return data;
  },
  computed: {
    lng() {
      return this.$root.lng;
    },
  },
  watch: {
    productId: function () {
      this.show_hist(1);
    },
  },
  mounted() {
    chart_self = new Chart(document.getElementById("graph"), {
      type: "line",
      data: chart_data,
      options: options1,
    });
    this.show_hist(1);
  },
  methods: {
    show_hist(t) {
      axios.get("/prod_history", { params: { id: this.productId } }).then(function (response) {
        var i = 0;
        var l = response.data.length;
        var l2 = chart_data.datasets[0].data.length;
        if (l2 > 0 && l2 > l) chart_data.datasets[0].data.splice(0, l2);
        if (t === 1) {
          for (; i < l; i++) {
            chart_data.datasets[0].data[i] = {
              x: response.data[i].date,
              y: (response.data[i].price * response.data[i].currency.rate).toFixed(2),
            };
            chart_data.datasets[0].label = "Price";
          }
        } else if (t === 2) {
          for (; i < l; i++) {
            chart_data.datasets[0].data[i] = { x: response.data[i].date, y: response.data[i].sales };
            chart_data.datasets[0].label = "Sold";
          }
        }
        chart_self.update();
      });
    },
  },
};
</script>
