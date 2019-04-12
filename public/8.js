webpackJsonp([8],{

/***/ "./node_modules/babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]],\"plugins\":[\"transform-object-rest-spread\",[\"transform-runtime\",{\"polyfill\":false,\"helpers\":false}]]}!./node_modules/vue-loader/lib/selector.js?type=script&index=0!./resources/assets/js/components/AppDetailCharts.vue":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var chart_data = {
    datasets: [{
        lineTension: 0,
        backgroundColor: 'rgba(255, 99, 132, 0.2)',
        borderColor: 'rgba(255,99,132,1)'
    }]
};
var options1 = {
    legend: { display: false },
    tooltips: {
        displayColors: false
    },
    scales: {
        xAxes: [{
            type: 'time',
            time: {
                tooltipFormat: 'DD.MM.YY',
                unitStepSize: 1,
                unit: 'day',
                displayFormats: {
                    hour: 'MM.YY', // Sept 4, 5PM
                    day: 'DD.MM.YY', // Sep 4 2015
                    week: 'll', // Week 46, or maybe "[W]WW - YYYY" ?
                    month: 'DD.MM.YYYY' // Sept 2015
                }
            }
        }]

    }
};
var chart_self,
    self,
    _data = {
    ready: false
};
/* harmony default export */ __webpack_exports__["default"] = ({
    props: ['productId'],
    data: function data() {
        return _data;
    },
    computed: {
        lng: function lng() {
            return this.$root.lng;
        }
    },
    mounted: function mounted() {
        self = this;
        chart_self = new Chart(document.getElementById('graph'), {
            type: 'line',
            data: chart_data,
            options: options1
        });
        this.show_hist(1);
    },

    methods: {
        show_hist: function show_hist(t) {
            axios.get('/prod_history', { params: { id: this.productId, currency: window.Laravel.currency.name } }).then(function (response) {
                var i = 0,
                    l = response.data.length,
                    l2 = chart_data.datasets[0].data.length;
                if (l2 > 0 && l2 > l) chart_data.datasets[0].data.splice(0, l2);
                if (t == 1) {
                    for (; i < l; i++) {
                        chart_data.datasets[0].data[i] = { x: response.data[i].date,
                            y: response.data[i].price * response.data[i].currency.rate
                        };
                        chart_data.datasets[0].label = 'Price';
                    }
                } else if (t == 2) {
                    for (; i < l; i++) {
                        chart_data.datasets[0].data[i] = { x: response.data[i].date,
                            y: response.data[i].sales
                        };
                        chart_data.datasets[0].label = 'Sold';
                    }
                }
                chart_self.update();
            });
        }
    }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-739e71c0\",\"hasScoped\":false,\"buble\":{\"transforms\":{}}}!./node_modules/vue-loader/lib/selector.js?type=template&index=0!./resources/assets/js/components/AppDetailCharts.vue":
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _c(
      "div",
      {
        staticClass: "panel panel-primary",
        staticStyle: { margin: "15px 0 0", "border-width": "0" }
      },
      [
        _c(
          "div",
          {
            staticClass: "panel-heading",
            staticStyle: { padding: "6px 15px" }
          },
          [
            _c("i", {
              staticClass: "fa fa-bar-chart",
              attrs: { "aria-hidden": "true" }
            }),
            _vm._v(" "),
            _c("span", [_vm._v(_vm._s(_vm.lng.hist_graph) + " ")]),
            _vm._v(" "),
            _c(
              "div",
              {
                staticClass: "btn-group",
                attrs: { role: "group", "aria-label": "..." }
              },
              [
                _c(
                  "button",
                  {
                    staticClass: "btn btn-default btn-sm",
                    attrs: { id: "graph_btn1", type: "button" },
                    on: {
                      click: function($event) {
                        _vm.show_hist(1)
                      }
                    }
                  },
                  [_vm._v(_vm._s(_vm.lng.price_hist))]
                ),
                _vm._v(" "),
                _c(
                  "button",
                  {
                    staticClass: "btn btn-default btn-sm",
                    attrs: { id: "graph_btn2", type: "button" },
                    on: {
                      click: function($event) {
                        _vm.show_hist(2)
                      }
                    }
                  },
                  [_vm._v(_vm._s(_vm.lng.sales_hist))]
                )
              ]
            )
          ]
        ),
        _vm._v(" "),
        _vm._m(0)
      ]
    )
  ])
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "div",
      { staticClass: "panel-body", staticStyle: { padding: "4px" } },
      [_c("canvas", { attrs: { height: "70", id: "graph" } })]
    )
  }
]
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-739e71c0", module.exports)
  }
}

/***/ }),

/***/ "./resources/assets/js/components/AppDetailCharts.vue":
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__("./node_modules/vue-loader/lib/component-normalizer.js")
/* script */
var __vue_script__ = __webpack_require__("./node_modules/babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]],\"plugins\":[\"transform-object-rest-spread\",[\"transform-runtime\",{\"polyfill\":false,\"helpers\":false}]]}!./node_modules/vue-loader/lib/selector.js?type=script&index=0!./resources/assets/js/components/AppDetailCharts.vue")
/* template */
var __vue_template__ = __webpack_require__("./node_modules/vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-739e71c0\",\"hasScoped\":false,\"buble\":{\"transforms\":{}}}!./node_modules/vue-loader/lib/selector.js?type=template&index=0!./resources/assets/js/components/AppDetailCharts.vue")
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources\\assets\\js\\components\\AppDetailCharts.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-739e71c0", Component.options)
  } else {
    hotAPI.reload("data-v-739e71c0", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ })

});