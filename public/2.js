webpackJsonp([2],{

/***/ "./node_modules/babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]],\"plugins\":[\"transform-object-rest-spread\",[\"transform-runtime\",{\"polyfill\":false,\"helpers\":false}]]}!./node_modules/vue-loader/lib/selector.js?type=script&index=0!./resources/assets/js/components/AppDetail.vue":
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

var self,
    timerId,
    _data = {
    show_specs: true,
    item: {},
    offerTime: null,
    showGraph: true
};
/* harmony default export */ __webpack_exports__["default"] = ({
    props: ['id'],
    data: function data() {
        return _data;
    },
    computed: {
        category: function category() {
            var _this = this;

            return Object.keys(window.Laravel.catalog).filter(function (key) {
                return window.Laravel.catalog[key].id == _this.item.category_id;
            })[0];
        },
        lng: function lng() {
            return this.$root.lng;
        },
        itemPriceResult: function itemPriceResult() {
            var _this2 = this;

            return function (item) {
                return _this2.$root.itemPriceResult(item);
            };
        }
    },
    created: function created() {
        var _this3 = this;

        self = this;
        this.itemById();
        this.clientWidth();
        window.onresize = function () {
            self.clientWidth();
        };
        window.onblur = function () {
            if (_this3.$route.path.indexOf("detail") > -1) clearInterval(timerId);
        };
        window.onfocus = function () {
            if (_this3.$route.path.indexOf("detail") > -1) {
                clearInterval(timerId);
                _this3.set_total_time();
            }
        };
    },
    destroyed: function destroyed() {
        if (timerId) clearInterval(timerId);
    },

    methods: {
        clientWidth: function clientWidth() {
            this.showGraph = document.documentElement.clientWidth > 620;
        },
        buyItem: function buyItem(item) {
            this.$store.commit('cart', { id: item.id, count: 1 });
        },
        to_compare: function to_compare() {
            this.item.is_compare = !this.item.is_compare;
            this.$store.commit('compare', { id: this.item.id, category_id: this.item.category_id, category: this.category });
        },
        to_wish: function to_wish() {
            self.item.isWish = !self.item.isWish;
            axios.post('/to_wish', {
                id: self.item.id
            }).catch(function () {
                self.item.isWish = !self.item.isWish;
            });
        },
        itemById: function itemById() {
            axios.get('prod_by_id?id=' + self.id).then(function (response) {
                response.data.isWish = !!response.data.wish;
                response.data.is_compare = false;
                self.item = response.data;
                self.$nextTick(function () {
                    var categoryIndex = self.$store.getters.compareCategoryIndex(window.Laravel.catalog[self.category].id);
                    self.item.is_compare = categoryIndex > -1 && self.$store.getters.isCompare(categoryIndex, response.data.id) > -1;
                });
                self.set_total_time();
            });
        },
        set_total_time: function set_total_time() {
            this.offerTime = null;
            if (this.item && this.item.discount) {
                this.offerTime = new Date(this.item.discount.end_at) - new Date();
                this.tick();
            }
        },
        zero: function zero(value) {
            if (value < 10) value = '0' + value;
            return value;
        },
        tick: function tick() {
            if (+this.offerTime < 1) return;
            this.offerTime = new Date(this.offerTime - 1000);
            timerId = setTimeout(function () {
                self.tick();
            }, 1000);
        }
    }
});

/***/ }),

/***/ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-8645b92a\",\"scoped\":false,\"hasInlineConfig\":true}!./node_modules/sass-loader/lib/loader.js?indentedSyntax!./resources/assets/sass/detail.sass":
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__("./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.tb-offer {\n  position: absolute;\n  top: 0;\n  text-align: center;\n  border: thin solid tomato;\n  border-width: 0 0 thin;\n  border-radius: 0 0 8px 0;\n  -webkit-box-shadow: 0 0 8px red;\n          box-shadow: 0 0 8px red;\n}\n.tb-offer span:nth-child(1) {\n    display: inline-block;\n    padding: 0.15em 0.6em;\n    background-color: tomato;\n    color: white;\n}\n.tb-offer span:nth-child(2) {\n    padding: 0 0.4em;\n    color: tomato;\n}\n.btn-buy {\n  -webkit-transition: all 0.3s;\n  transition: all 0.3s;\n  position: absolute;\n  padding: 0.6em 1em;\n  border: 0px solid white;\n  border-width: thin 0 0 thin;\n  border-radius: 0.8rem 0;\n  right: 0;\n  bottom: 0;\n  width: 10em;\n  color: white;\n}\n.btn-buy:hover {\n    background-color: royalblue;\n}\n.border1 {\n  background-color: white;\n  border: thin solid lightgray;\n  border-top-width: 0;\n}\n.border1 tr:nth-child(even) {\n    background-color: whitesmoke;\n}\n.nav-tabs li.active a {\n  background-color: white;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-8645b92a\",\"hasScoped\":false,\"buble\":{\"transforms\":{}}}!./node_modules/vue-loader/lib/selector.js?type=template&index=0!./resources/assets/js/components/AppDetail.vue":
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    {
      staticClass: "container-fluid",
      staticStyle: { "max-width": "80em", padding: "0" }
    },
    [
      _c(
        "div",
        { staticClass: "col-md-7", staticStyle: { "padding-right": "0" } },
        [
          _c(
            "div",
            {
              staticClass: "action-frm",
              staticStyle: {
                "border-color": "white",
                border: "1px solid white",
                "border-width": "0 0 1px 1px",
                "background-color": "inherit",
                "z-index": "1"
              }
            },
            [
              _c(
                "a",
                {
                  staticClass: "action-item fake-link",
                  on: {
                    click: function($event) {
                      _vm.to_compare()
                    }
                  }
                },
                [
                  _c("span", { staticClass: "hidden-xs" }, [
                    _vm._v(_vm._s(_vm.lng.to_compare))
                  ]),
                  _vm._v(" "),
                  _c("i", {
                    staticClass:
                      "fa fa-balance-scale compare-state anm-bounce-scale",
                    attrs: {
                      "data-check": _vm.item.is_compare,
                      "aria-hidden": "true"
                    }
                  })
                ]
              ),
              _vm._v(" \n            "),
              _c(
                "a",
                {
                  staticClass: "action-item fake-link",
                  on: {
                    click: function($event) {
                      _vm.to_wish()
                    }
                  }
                },
                [
                  _c("span", { staticClass: "hidden-xs" }, [
                    _vm._v(_vm._s(_vm.lng.to_wishlist))
                  ]),
                  _vm._v(" "),
                  _c("i", {
                    staticClass: "fa fa-heart heart-state anm-bounce-scale",
                    attrs: {
                      "data-check": _vm.item.isWish,
                      "aria-hidden": "true"
                    }
                  })
                ]
              )
            ]
          ),
          _vm._v(" "),
          _c(
            "div",
            {
              staticClass: "carousel slide",
              attrs: { id: "carousel2", "data-ride": "carousel" }
            },
            [
              _vm.item.gallery
                ? _c("ol", { staticClass: "carousel-indicators" }, [
                    _c("li", {
                      staticClass: "active",
                      attrs: {
                        "data-target": "#carousel2",
                        "data-slide-to": "0"
                      }
                    })
                  ])
                : _vm._e(),
              _vm._v(" "),
              _c(
                "div",
                {
                  staticClass: "carousel-inner carousel-inner-bcolor",
                  staticStyle: {
                    "background-color": "inherit",
                    "background-image":
                      "linear-gradient(141deg, #ffffff00 0%, #ffffff00 40%, #1484e3 100%)"
                  },
                  attrs: { role: "listbox" }
                },
                [
                  _c("div", { staticClass: "item active" }, [
                    _c("img", {
                      staticClass: "carousel-img",
                      staticStyle: { "max-height": "28rem" },
                      attrs: {
                        src: _vm.item.img_src && "file/" + _vm.item.img_src
                      }
                    }),
                    _vm._v(" "),
                    +_vm.offerTime > 0
                      ? _c("div", { staticClass: "tb-offer" }, [
                          _c("span", [
                            _vm._v(
                              _vm._s(
                                _vm.lng.discount +
                                  " -" +
                                  _vm.item.discount.discount +
                                  "%"
                              )
                            )
                          ]),
                          _vm._v(" "),
                          _c("span", { staticClass: "hidden-xs" }, [
                            _vm._v(
                              _vm._s(
                                _vm.lng.offer_end_at +
                                  " " +
                                  _vm.zero(_vm.offerTime.getDate()) +
                                  " " +
                                  _vm.lng.offer_d +
                                  " " +
                                  _vm.zero(_vm.offerTime.getHours()) +
                                  ":" +
                                  _vm.zero(_vm.offerTime.getMinutes()) +
                                  ":" +
                                  _vm.zero(_vm.offerTime.getSeconds())
                              ) + "\n                        "
                            )
                          ])
                        ])
                      : _vm._e()
                  ])
                ]
              ),
              _vm._v(" "),
              _vm.item.available > 0
                ? _c(
                    "div",
                    {
                      staticClass: "btn-buy fake-link",
                      on: {
                        click: function($event) {
                          _vm.buyItem(_vm.item)
                        }
                      }
                    },
                    [
                      _c("i", {
                        staticClass: "fa fa-cart-plus anm-bounce-scale",
                        attrs: { "aria-hidden": "true" }
                      }),
                      _vm._v(
                        "  \n                " +
                          _vm._s(_vm.itemPriceResult(_vm.item)) +
                          "\n            "
                      )
                    ]
                  )
                : _c("div", { staticClass: "btn-buy disabled" }, [
                    _vm._v(_vm._s(_vm.lng.not_in_stock))
                  ])
            ]
          ),
          _vm._v(" "),
          _c(
            "div",
            { staticStyle: { "margin-top": "15px" } },
            [
              _c("ul", { staticClass: "nav nav-tabs" }, [
                _c(
                  "li",
                  {
                    class: { active: _vm.show_specs },
                    attrs: { role: "presentation" },
                    on: {
                      click: function($event) {
                        _vm.show_specs = true
                      }
                    }
                  },
                  [_c("a", [_vm._v(_vm._s(_vm.lng.specs))])]
                ),
                _vm._v(" "),
                _c(
                  "li",
                  {
                    class: { active: !_vm.show_specs },
                    attrs: { role: "presentation" },
                    on: {
                      click: function($event) {
                        _vm.show_specs = false
                      }
                    }
                  },
                  [_c("a", [_vm._v(_vm._s(_vm.lng.descr))])]
                )
              ]),
              _vm._v(" "),
              _vm.show_specs && _vm.item
                ? _c("table", { staticClass: "table border1" }, [
                    _c(
                      "tbody",
                      { staticStyle: { "text-align": "center" } },
                      _vm._l(_vm.item.specs, function(specs, i) {
                        return _c("tr", { key: i }, [
                          _c("td", [
                            _vm._v(
                              _vm._s(
                                _vm.lng[specs.name]
                                  ? _vm.lng[specs.name]
                                  : specs.name
                              )
                            )
                          ]),
                          _vm._v(" "),
                          _c("td", [
                            _vm._v(
                              _vm._s(specs.value) + " " + _vm._s(specs.val_type)
                            )
                          ])
                        ])
                      })
                    )
                  ])
                : _c(
                    "div",
                    { staticClass: "border1", staticStyle: { padding: "4px" } },
                    [_vm._v(_vm._s(_vm.item.description))]
                  ),
              _vm._v(" "),
              _vm.showGraph
                ? _c("app-detail-charts", { attrs: { "product-id": _vm.id } })
                : _vm._e()
            ],
            1
          )
        ]
      ),
      _vm._v(" "),
      _c("app-comments", {
        staticClass: "col-md-5",
        attrs: { "product-id": _vm.id }
      })
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-8645b92a", module.exports)
  }
}

/***/ }),

/***/ "./node_modules/vue-style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-8645b92a\",\"scoped\":false,\"hasInlineConfig\":true}!./node_modules/sass-loader/lib/loader.js?indentedSyntax!./resources/assets/sass/detail.sass":
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__("./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-8645b92a\",\"scoped\":false,\"hasInlineConfig\":true}!./node_modules/sass-loader/lib/loader.js?indentedSyntax!./resources/assets/sass/detail.sass");
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__("./node_modules/vue-style-loader/lib/addStylesClient.js")("bb88be48", content, false, {});
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../node_modules/css-loader/index.js!../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-8645b92a\",\"scoped\":false,\"hasInlineConfig\":true}!../../../node_modules/sass-loader/lib/loader.js?indentedSyntax!./detail.sass", function() {
     var newContent = require("!!../../../node_modules/css-loader/index.js!../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-8645b92a\",\"scoped\":false,\"hasInlineConfig\":true}!../../../node_modules/sass-loader/lib/loader.js?indentedSyntax!./detail.sass");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ "./resources/assets/js/components/AppDetail.vue":
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__("./node_modules/vue-style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-8645b92a\",\"scoped\":false,\"hasInlineConfig\":true}!./node_modules/sass-loader/lib/loader.js?indentedSyntax!./resources/assets/sass/detail.sass")
}
var normalizeComponent = __webpack_require__("./node_modules/vue-loader/lib/component-normalizer.js")
/* script */
var __vue_script__ = __webpack_require__("./node_modules/babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]],\"plugins\":[\"transform-object-rest-spread\",[\"transform-runtime\",{\"polyfill\":false,\"helpers\":false}]]}!./node_modules/vue-loader/lib/selector.js?type=script&index=0!./resources/assets/js/components/AppDetail.vue")
/* template */
var __vue_template__ = __webpack_require__("./node_modules/vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-8645b92a\",\"hasScoped\":false,\"buble\":{\"transforms\":{}}}!./node_modules/vue-loader/lib/selector.js?type=template&index=0!./resources/assets/js/components/AppDetail.vue")
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = injectStyle
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
Component.options.__file = "resources\\assets\\js\\components\\AppDetail.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-8645b92a", Component.options)
  } else {
    hotAPI.reload("data-v-8645b92a", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ })

});