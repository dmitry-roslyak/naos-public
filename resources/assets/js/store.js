import Vue from "vue";
import Vuex from 'vuex'

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        cartLength: 0,
        compare_list: [],
        flt_ids: [],
        currency:0,
        cart: {},
    },
    mutations: {
        cartClear(state) {
            state.cart = {}
            localStorage.cart = ''
        },
        cart(state, item){
            if(item){
                state.cart[item.id] ? state.cart[item.id] += item.count : state.cart[item.id] = item.count
                if(item.toRemove) delete state.cart[item.id]
                localStorage.cart = JSON.stringify(state.cart);
            } 
            else {
                if(localStorage.cart && localStorage.cart.length) {
                    try {
                        state.cart = JSON.parse(localStorage.cart);
                    } catch (error) {
                        console.log(error)
                        localStorage.cart = ''
                    }
                }
            }
            state.cartLength = 0
            for (const key in state.cart) {
                state.cartLength += state.cart[key];
            }
        },
        set_currency(state,value){
            state.currency = value;
        },
        setFilter(state, id) {
            if(id) {
                var i = state.flt_ids.indexOf(id)
                i < 0 ? state.flt_ids.push(id) : state.flt_ids.splice(i,1)
            } else state.flt_ids.length = 0
        },
        compare(state, id) {
            var i = state.compare_list.indexOf(id)
            i < 0 ? state.compare_list.push(id) : state.compare_list.splice(i,1)
        }
    }
});
