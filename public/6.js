webpackJsonp([6],{

/***/ "./node_modules/babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]],\"plugins\":[\"transform-object-rest-spread\",[\"transform-runtime\",{\"polyfill\":false,\"helpers\":false}]]}!./node_modules/vue-loader/lib/selector.js?type=script&index=0!./resources/assets/js/components/userInfo.vue":
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

var self,
    _data = {
    lng: {},
    edit: true,
    userInfo: {},
    guest: false
};
/* harmony default export */ __webpack_exports__["default"] = ({
    data: function data() {
        return _data;
    },
    created: function created() {
        self = this;
        this.lng = window.lng;
        if (window.Laravel.user) {
            this.usr_info();
        } else {
            this.guest = true;
            this.edit = true;
        }
    },

    methods: {
        usr_info: function usr_info() {
            axios.get('/user_info').then(function (response) {
                self.userInfo = response.data;
                if (self.userInfo.fname) self.edit = false;
            });
        },
        upd_usr_info: function upd_usr_info() {
            if (!self.edit) self.edit = true;else {
                self.edit = false;
                axios.post('/update_user_info', { user: self.userInfo });
            }
        }
    }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-709af620\",\"hasScoped\":false,\"buble\":{\"transforms\":{}}}!./node_modules/vue-loader/lib/selector.js?type=template&index=0!./resources/assets/js/components/userInfo.vue":
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "container-fluid" }, [
    _c("i", { staticClass: "fa fa-id-card" }),
    _vm._v("  "),
    _c("label", [_vm._v(_vm._s(_vm.lng.personal_info))]),
    _vm._v(" \n    "),
    !_vm.guest
      ? _c(
          "a",
          {
            staticClass: "fake-link",
            on: {
              click: function($event) {
                _vm.upd_usr_info()
              }
            }
          },
          [
            _c("i", { staticClass: "fa fa-edit" }),
            _vm._v(
              " " + _vm._s(_vm.edit ? _vm.lng.confirm : _vm.lng.edit) + "\n    "
            )
          ]
        )
      : _vm._e(),
    _vm._v(" "),
    _c("div", { staticClass: "container-fluid" }, [
      _c("table", { staticClass: "table user-info-table" }, [
        _c("tbody", [
          _c("tr", [
            _c("td", [_vm._v(_vm._s(_vm.lng.fname))]),
            _vm._v(" "),
            _vm.edit
              ? _c("td", [
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.userInfo.fname,
                        expression: "userInfo.fname"
                      }
                    ],
                    staticClass: "form-control myinput1",
                    domProps: { value: _vm.userInfo.fname },
                    on: {
                      input: function($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(_vm.userInfo, "fname", $event.target.value)
                      }
                    }
                  })
                ])
              : _c("td", [_vm._v(_vm._s(_vm.userInfo.fname))])
          ]),
          _vm._v(" "),
          _c("tr", [
            _c("td", [_vm._v(_vm._s(_vm.lng.lname))]),
            _vm._v(" "),
            _vm.edit
              ? _c("td", [
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.userInfo.lname,
                        expression: "userInfo.lname"
                      }
                    ],
                    staticClass: "form-control myinput1",
                    domProps: { value: _vm.userInfo.lname },
                    on: {
                      input: function($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(_vm.userInfo, "lname", $event.target.value)
                      }
                    }
                  })
                ])
              : _c("td", [_vm._v(_vm._s(_vm.userInfo.lname))])
          ]),
          _vm._v(" "),
          _c("tr", [
            _c("td", [_vm._v(_vm._s(_vm.lng.tel_number))]),
            _vm._v(" "),
            _vm.edit
              ? _c("td", [
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.userInfo.tel,
                        expression: "userInfo.tel"
                      }
                    ],
                    staticClass: "form-control myinput1",
                    domProps: { value: _vm.userInfo.tel },
                    on: {
                      input: function($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(_vm.userInfo, "tel", $event.target.value)
                      }
                    }
                  })
                ])
              : _c("td", [_vm._v(_vm._s(_vm.userInfo.tel))])
          ])
        ])
      ])
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-709af620", module.exports)
  }
}

/***/ }),

/***/ "./resources/assets/js/components/userInfo.vue":
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__("./node_modules/vue-loader/lib/component-normalizer.js")
/* script */
var __vue_script__ = __webpack_require__("./node_modules/babel-loader/lib/index.js?{\"cacheDirectory\":true,\"presets\":[[\"env\",{\"modules\":false,\"targets\":{\"browsers\":[\"> 2%\"],\"uglify\":true}}]],\"plugins\":[\"transform-object-rest-spread\",[\"transform-runtime\",{\"polyfill\":false,\"helpers\":false}]]}!./node_modules/vue-loader/lib/selector.js?type=script&index=0!./resources/assets/js/components/userInfo.vue")
/* template */
var __vue_template__ = __webpack_require__("./node_modules/vue-loader/lib/template-compiler/index.js?{\"id\":\"data-v-709af620\",\"hasScoped\":false,\"buble\":{\"transforms\":{}}}!./node_modules/vue-loader/lib/selector.js?type=template&index=0!./resources/assets/js/components/userInfo.vue")
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
Component.options.__file = "resources\\assets\\js\\components\\userInfo.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-709af620", Component.options)
  } else {
    hotAPI.reload("data-v-709af620", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ })

});