webpackJsonp([7],{

/***/ "./node_modules/babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]],\"plugins\":[\"transform-object-rest-spread\",[\"transform-runtime\",{\"polyfill\":false,\"helpers\":false}]]}!./node_modules/vue-loader/lib/selector.js?type=script&index=0!./resources/assets/js/components/AppUser.vue":
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

/* harmony default export */ __webpack_exports__["default"] = ({
    computed: {
        lng: function lng() {
            return this.$root.lng;
        }
    },
    methods: {
        get_currency: function get_currency(val) {
            var _this = this;

            axios.get('/set_currency?val=' + val).then(function (response) {
                _this.lng.currency = _this.lng[response.data.name];
                _this.$store.commit('set_currency', response.data.rate);
            });
        }
    }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-350a0265\",\"hasScoped\":false,\"buble\":{\"transforms\":{}}}!./node_modules/vue-loader/lib/selector.js?type=template&index=0!./resources/assets/js/components/AppUser.vue":
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { staticClass: "container-fluid", staticStyle: { "max-width": "80em" } },
    [
      _c("div", { staticClass: "container-fluid" }, [
        _c("div", { staticClass: "dropdown" }, [
          _c("i", { staticClass: "fa fa-money" }),
          _vm._v(" "),
          _c("label", [_vm._v(" " + _vm._s(_vm.lng.currency_type) + " :  ")]),
          _vm._v(" "),
          _c(
            "a",
            {
              staticClass: "dropdown-toggle",
              attrs: { "data-toggle": "dropdown", "aria-haspopup": "true" }
            },
            [
              _c("span", { staticStyle: { "vertical-align": "middle" } }, [
                _vm._v(" " + _vm._s(_vm.lng.currency) + " ")
              ]),
              _c("span", { staticClass: "caret" })
            ]
          ),
          _vm._v(" "),
          _c("ul", { staticClass: "dropdown-menu" }, [
            _c("li", [
              _c(
                "a",
                {
                  staticClass: "fake-link",
                  on: {
                    click: function($event) {
                      _vm.get_currency("UAH")
                    }
                  }
                },
                [_c("span", [_vm._v("  " + _vm._s(_vm.lng.UAH))])]
              )
            ]),
            _vm._v(" "),
            _c("li", [
              _c(
                "a",
                {
                  staticClass: "fake-link",
                  on: {
                    click: function($event) {
                      _vm.get_currency("RUB")
                    }
                  }
                },
                [_c("span", [_vm._v("  " + _vm._s(_vm.lng.RUB))])]
              )
            ]),
            _vm._v(" "),
            _c("li", [
              _c(
                "a",
                {
                  staticClass: "fake-link",
                  on: {
                    click: function($event) {
                      _vm.get_currency("USD")
                    }
                  }
                },
                [_c("span", [_vm._v("  " + _vm._s(_vm.lng.USD))])]
              )
            ])
          ])
        ])
      ]),
      _vm._v(" "),
      _c("hr", { staticStyle: { margin: "8px 0" } }),
      _vm._v(" "),
      _c("app-user-info"),
      _vm._v(" "),
      _c("hr", { staticStyle: { margin: "8px 0" } }),
      _vm._v(" "),
      _c("div", { staticClass: "container-fluid" }, [
        _c("i", { staticClass: "fa fa-envelope" }),
        _vm._v(" "),
        _c("label", [_vm._v(_vm._s(_vm.lng.email_me) + " :")]),
        _vm._v(" "),
        _c("div", { staticClass: "container-fluid" }, [
          _c("div", { staticClass: "checkbox disabled" }, [
            _c("label", [
              _c("input", { attrs: { type: "checkbox", disabled: "" } }),
              _vm._v(
                "\n                    " +
                  _vm._s(_vm.lng.email_me_offers) +
                  "\n                "
              )
            ])
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "checkbox" }, [
            _c("label", [
              _c("input", { attrs: { type: "checkbox", disabled: "" } }),
              _vm._v(
                "\n                    " +
                  _vm._s(_vm.lng.email_me_wishlist) +
                  "\n                "
              )
            ])
          ])
        ])
      ])
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
    require("vue-hot-reload-api")      .rerender("data-v-350a0265", module.exports)
  }
}

/***/ }),

/***/ "./resources/assets/js/components/AppUser.vue":
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__("./node_modules/vue-loader/lib/component-normalizer.js")
/* script */
var __vue_script__ = __webpack_require__("./node_modules/babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]],\"plugins\":[\"transform-object-rest-spread\",[\"transform-runtime\",{\"polyfill\":false,\"helpers\":false}]]}!./node_modules/vue-loader/lib/selector.js?type=script&index=0!./resources/assets/js/components/AppUser.vue")
/* template */
var __vue_template__ = __webpack_require__("./node_modules/vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-350a0265\",\"hasScoped\":false,\"buble\":{\"transforms\":{}}}!./node_modules/vue-loader/lib/selector.js?type=template&index=0!./resources/assets/js/components/AppUser.vue")
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
Component.options.__file = "resources\\assets\\js\\components\\AppUser.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-350a0265", Component.options)
  } else {
    hotAPI.reload("data-v-350a0265", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ })

});