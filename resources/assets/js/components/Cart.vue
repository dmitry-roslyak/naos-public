<template>
    <div class="container-fluid" style="padding: 0 40px">
        <div id="order-done" style="display:none" class="overlay-background"><h4>{{lng.order_done}} !</h4> </div>
        <search></search>
        <hr>
        <div class="col-md-7 col-sm-12" style="padding:0">
            <div class="col-md-12" style="padding:0 0 15px 15px">
                <div class="cart-caption">
                    {{lng.cart}}
                    <div class="pull-right">{{lng.total_sum}}
                    {{(total).toFixed(1)+' '+ lng.currency}}</div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12" style="padding:0 0 15px 15px" v-for="(item,i1) in result">
                <div class="action-frm">
                    <a class="action-item fake-link" @click="rmFromCart(i1)">
                        <span class="hidden-xs">{{lng.remove}}</span>
                        <i class="fa fa-minus" style="font-size:1.5rem" aria-hidden="true"></i>
                    </a>
                </div>
                <div class="cart-item container-fluid">
                    <div class="col-sm-6" style="padding:0">
                        <img v-bind:src="'file/'+item.img_src" style="height:10rem;">
                    </div>
                    <div class="col-sm-6">
                        <div>{{item.name}}</div>
                        {{(currency * item.price).toFixed(1)+' '+ lng.currency}}
                        <div><input class="form-control" v-model="item.count" type="number"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-5">
            <user-info ref="userInfo"></user-info>
            <hr>
            <!-- <div class="form-inline">
                <span>Способ доставки:&nbsp;</span>
                <div class="radio">
                    <label><input type="radio" value="standart"  name="optionsRadioso" checked>Курьер</label>
                </div>
            </div> -->
            <!-- <table class="table">
                <tr>
                    <td>{{lng.address}}</td>
                    <td v-if="edit"><input v-model="userInfo.adr" class="form-control myinput1"></td>
                    <td v-else>{{userInfo.adr}}</td>
                </tr>
            </table> -->
            <label>{{lng.payment_type}}&nbsp;</label>
            <div class="container-fluid">
                <div class="radio">
                    <i class="fa fa-money"></i>
                    <label><input type="radio" v-model="payment" value="cash">{{lng.cash}} </label>
                </div>
                <div class="radio">
                    <i class="fa fa-credit-card"></i>
                    <label><input type="radio" v-model="payment" value="pay_card">{{lng.paycard}} </label>
                </div>
            </div>
            <hr>
            <div v-if="payment=='pay_card'">
                <label>{{lng.paycard_info}}&nbsp;</label><i class="fa fa-cc-visa"></i>
                <table class="table">
                    <tbody>
                        <tr>
                            <td>{{lng.paycard_number}}</td>
                            <td><input placeholder="4005520000011126" v-model="card.number" maxlength="19" @keyup.13='next_input(2)'
                                    @input="chk_input(1)" class="form-control myinput1"></td>
                            <td><i v-if="cardValidate.number" class="fa fa-check-circle" style="color:green"></i>
                                <i v-else class="fa fa-times" style="color:red"></i>
                            </td>
                        </tr>
                        <tr>
                            <td>Exp Date</td>
                            <td>
                                <div class="form-inline">
                                    <input id="input2" maxlength="2" @keyup.13='next_input(3)' style="width:3em" @input="chk_input(2)"
                                        v-model="card.expire.month" class="form-control myinput1">&nbsp;/
                                    <input id="input3" maxlength="2" @keyup.13='next_input(4)' style="width:3em" @input="chk_input(3)" 
                                        v-model="card.expire.year" class="form-control myinput1">
                                </div>
                            </td>
                            <td><i v-if="cardValidate.expire.month && cardValidate.expire.year" class="fa fa-check-circle" style="color:green"></i>
                                <i v-else class="fa fa-times" style="color:red"></i>
                            </td>
                        </tr>
                        <tr>
                            <td>CVV2</td>
                            <td><input id="input4" maxlength="4" v-model="card.cvv2" style="width:7em" @input="chk_input(4)" class="form-control myinput1"></td>
                            <td>
                                <i v-if="cardValidate.cvv2" class="fa fa-check-circle" style="color:green"></i>
                                <i v-else class="fa fa-times" style="color:red"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <button class="btn btn-primary pull-right" style="margin-top:10px" @click="to_order">{{lng.confirm_order}}</button>
        </div>
        <hr>
    </div>
</template>

<script>
    var data = {
        lng: {},
        products: [],
        result: [],
        payment: 'cash',
        delivery: 'customer',
        cardValidate: { number: false , expire: { month: false, year: false },  cvv2: false },
        card: { number: '' , expire: { month: '', year: '' },  cvv2: '' }
    };
    var self, selfData, selfProps;
    export default {
        data: function () { return data },
        props: ['ids1'],
        computed: {
            currency: function () { return this.$store.state.currency },
            total: function () {
                var res = 0;
                for (var i = 0; i < selfData.result.length; i++) {
                    res += selfData.result[i].price * this.$store.state.currency * selfData.result[i].count;
                }
                return res;
            }
        },
        created() {
            self = this, selfData = this.$data;
            selfData.lng = window.lng;
            var tempIds = JSON.parse(this.$props.ids1);
            if(tempIds.length){
                for (var i = 0; i < tempIds.length; i++) {
                    selfData.products.push({id: tempIds[i], count: 1});
                }
                this.$store.commit('setCartLength', tempIds.length);
                localStorage.cart = JSON.stringify(selfData.products);
                this.get_prodsby_ids(tempIds);
            }
            else
            {
                if (localStorage.cart && localStorage.cart.length > 1) {
                    selfData.products = JSON.parse(localStorage.cart);
                    for (var i = 0; i < selfData.products.length; i++) {
                        tempIds.push(selfData.products[i].id);
                    }
                    this.get_prodsby_ids(tempIds);
                }
            }
        },
        methods: {
            rmFromCart(i) {
                for (var j = 0; j < selfData.products.length; j++) {
                    if(selfData.products[j].id == selfData.result[i].id) {
                        var count = this.$store.state.cartLength - selfData.result[i].count;
                        this.$store.commit('setCartLength', count);
                        selfData.products.splice(j,1); 
                    }
                }
                selfData.result.splice(i,1);
                localStorage.cart = JSON.stringify(selfData.products);
            },
            cartClear() {
                this.$store.commit('setCartLength', 0);
                localStorage.cart = '';
            },
            get_prodsby_ids(ids) {
                if(ids.length){
                    axios.get('/prodsby_ids', { params: { ids: ids } }).then(function (response) {
                        var res = response.data;
                        for (var i = 0; i < selfData.products.length; i++) {
                            for (var j = 0; j < res.length; j++) {
                                if(selfData.products[i].id == res[j].id) {
                                    res[j].count = selfData.products[i].count;
                                }
                            }
                        }
                        selfData.result = res;
                    }).catch(function (error) {
                        self.$root.retry(self.get_prodsby_ids, error.response.status);
                    });
                }
            },
            chk_input(i) {
                selfData.cardValidate.number = /^(\d{13,19})$/.test(selfData.card.number)
                if((selfData.cardValidate.expire.month = /^([0][1-9]|[1][0-2])$/.test(selfData.card.expire.month)) && i==2) this.next_input(3);
                if((selfData.cardValidate.expire.year = /^(\d{2})$/.test(selfData.card.expire.year)) && i==3) this.next_input(4);
                selfData.cardValidate.cvv2 = /^(\d{3,4})$/.test(selfData.card.cvv2);                        
            },
            next_input(i) {
                if (i == 2) selfData.card.number = 4005520000011126;
                document.getElementById('input' + i).focus();
            },
            to_order() {
                axios.post('/order', {
                    products: selfData.products,
                    card: selfData.card,
                    // n: selfData.card.number,
                    // e: selfData.card.expire.month + '-' + selfData.card.expire.year,
                    // c: selfData.card.cvv2,
                    user_info: self.$refs.userInfo.userInfo,
                    payment: selfData.payment,
                    delivery: selfData.delivery,
                    delivery_adr: 'data_self.delivery adr',
                }).then(function (response) {
                    localStorage.cart = '';
                    // document.getElementById('order-done').style = "display: initial";
                    // setTimeout(function (params) {
                    //     document.getElementById('order-done').style = "display: none";
                    //     location.replace('/');
                    // },2000)
                }).catch(function (error) {
                    self.$root.retry(self.to_order, error.response.status);
                });
            }
        }
    }
</script>

<style lang="sass" src="../../sass/cart.sass"></style>