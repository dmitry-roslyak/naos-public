<template>
    <div class="container-fluid">
        <div id="order-done" style="display:none" class="overlay-background"><h4>{{lng.order_done}} !</h4> </div>
        <hr>
        <div class="col-md-7 col-sm-12" style="padding:0">
            <div class="col-md-12" style="padding:0 0 15px 15px">
                <div class="cart-caption">
                    {{lng.cart}}
                    <div class="pull-right">{{lng.total_sum}}
                    {{(total).toFixed(1)+' '+ lng.currency}}</div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12" style="padding:0 0 15px 15px" v-for="item in products" :key="item.id">
                <div class="action-frm">
                    <a class="action-item fake-link" @click="removeFromCart(item.id)">
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
    var self, data = {
        lng: {},
        products: [],
        payment: 'cash',
        delivery: 'customer',
        cardValidate: { number: false , expire: { month: false, year: false },  cvv2: false },
        card: { number: '' , expire: { month: '', year: '' },  cvv2: '' }
    };
    export default {
        data: function () { return data },
        props: ['ids1'],
        computed: {
            currency: function () { return this.$store.state.currency },
            total: function () {
                var res = 0;
                for (var i = 0; i < this.products.length; i++) {
                    res += this.products[i].price * this.$store.state.currency * this.products[i].count;
                }
                return res;
            }
        },
        created() {
            self = this
            this.lng = window.lng;
            var requestIDs = JSON.parse(this.ids1);
            if(requestIDs.length) {
                for (var i = 0; i < requestIDs.length; i++) {
                    this.$store.commit('cart', {id: requestIDs[i], count: 1});
                }
            }
            else {
                for (const key in this.$store.state.cart) {
                    requestIDs.push(key);
                }
            }
            if(requestIDs.length) this.get_prodsby_ids(requestIDs);
        },
        methods: {
            removeFromCart(id) {
                self.products.forEach((element,i) => {
                    if(element.id == id) self.products.splice(i, 1); 
                });
                this.$store.commit('cart', {id: id, toRemove: true});
            },
            get_prodsby_ids(ids) {
                axios.get('/prodsby_ids', { params: { ids: ids } }).then(function (response) {
                    self.products = response.data;
                    self.products.forEach(function (element)  {
                        element.count = self.$store.state.cart[element.id]
                    });
                }).catch(function (error) {
                });
            },
            chk_input(i) {
                this.cardValidate.number = /^(\d{13,19})$/.test(this.card.number)
                if((this.cardValidate.expire.month = /^([0][1-9]|[1][0-2])$/.test(this.card.expire.month)) && i==2) this.next_input(3);
                if((this.cardValidate.expire.year = /^(\d{2})$/.test(this.card.expire.year)) && i==3) this.next_input(4);
                this.cardValidate.cvv2 = /^(\d{3,4})$/.test(this.card.cvv2);                        
            },
            next_input(i) {
                if (i == 2) this.card.number = 4005520000011126;
                document.getElementById('input' + i).focus();
            },
            to_order() {
                axios.post('/order', {
                    products: this.products,
                    card: this.card,
                    // n: this.card.number,
                    // e: this.card.expire.month + '-' + this.card.expire.year,
                    // c: this.card.cvv2,
                    user_info: self.$refs.userInfo.userInfo,
                    payment: this.payment,
                    delivery: this.delivery,
                    delivery_adr: 'data_self.delivery adr',
                }).then(function (response) {
                    self.$store.commit('cartClear');
                    // document.getElementById('order-done').style = "display: initial";
                    // setTimeout(function (params) {
                    //     document.getElementById('order-done').style = "display: none";
                    //     location.replace('/');
                    // },2000)
                }).catch(function (error) {
                });
            }
        }
    }
</script>

<style lang="sass" src="../../sass/cart.sass"></style>