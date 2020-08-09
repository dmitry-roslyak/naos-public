<template>
    <div class="container-fluid">
        <div id="order-done" style="display:none" class="overlay-view"><h4>{{lng.order_done}} !</h4> </div>
        <hr>
        <div class="col-md-7 col-sm-12 cart-products">
            <div class="cart-caption">{{lng.cart}}
                <div class="pull-right">{{lng.total_sum +': '+total+' '+ lng.currency}}</div>
            </div>
            <table class="table">
                <tbody>
                    <tr>
                        <th></th>
                        <th>{{lng.product}}</th>
                        <th>{{lng.count}}</th>
                        <th>{{lng.price}}</th>
                    </tr>
                    <tr v-for="(item, i) in products" :key="item.id">
                        <td><img v-bind:src="'/file/'+item.img_src"></td>
                        <td><router-link :to="{ name: 'AppDetail', params: { id: item.id }}">{{item.name}}</router-link></td>
                        <td><input class="form-control" v-model="item.count" @input="reCount(item.id, i)" type="number"></td>
                        <td style="white-space: nowrap;">{{itemPriceResult(item)}}</td>
                        <div class="action-frm">
                            <a class="action-item fake-link" @click="removeFromCart(item.id)">
                                <span class="hidden-xs">{{lng.remove}}</span>
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>
                        </div>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-sm-12 col-md-5">
            <app-user-info ref="userInfo" style="padding:0"></app-user-info>
            <hr>
            <label>{{lng.payment_type}}&nbsp;</label>
            <div class="col-xs-12">
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
                            <td><input id="number" class="form-control myinput1" v-model="card.number" v-validate
                                @keyup.13="card.number = '4005520000011126'; next_input($event.target, 'month')" maxlength="19"></td>
                            <td><i :class="validate['number'] ? 'fa fa-check-circle' : 'fa fa-times'"></i></td>
                        </tr>
                        <tr>
                            <td>Exp Date</td>
                            <td>
                                <div class="form-inline">
                                    <input id="month" class="form-control myinput1" 
                                        @keyup.13="next_input($event.target, 'year')" style="width:3em" v-model="card.expire.month" v-validate maxlength="2">&nbsp;/
                                    <input id="year" class="form-control myinput1" 
                                        @keyup.13="next_input($event.target, 'cvv2')" style="width:3em" v-model="card.expire.year" v-validate maxlength="2" >
                                </div>
                            </td>
                            <td><i :class="validate['month'] && validate['year'] ? 'fa fa-check-circle' : 'fa fa-times'"></i></td>
                        </tr>
                        <tr>
                            <td>CVV2</td>
                            <td><input id="cvv2" class="form-control myinput1" v-model="card.cvv2" v-validate style="width:7em" maxlength="4" ></td>
                            <td><i :class="validate.cvv2 ? 'fa fa-check-circle' : 'fa fa-times'"></i></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <button class="btn btn-primary" @click="to_order">{{lng.confirm_order}}</button>
        </div>
        <hr>
    </div>
</template>

<script>
    var self,
        data = {
            products: [],
            payment: 'cash',
            delivery: 'customer',
            card: { number: '', expire: { month: '', year: '' },  cvv2: '' },
            validate: {}
        }, 
        validator = new Validator({
            number: /^(\d{13,19})$/,
            month: /^([0][1-9]|[1][0-2])$/,
            year: /^(\d{2})$/,
            cvv2: /^(\d{3,4})$/,
        }, data.validate);

    export default {
        components: {
            'AppUserInfo': () => import(/* webpackChunkName: "js/AppUserInfo-vue" */"./AppUserInfo.vue")
        },
        props: ['ids'],
        data: function () { return data },
        computed: {
            lng(){ return this.$root.lng },
            total: function () {
                var res = 0;
                for (var i = 0; i < this.products.length; i++) {
                    res += this.products[i].price * this.$store.state.currency * this.products[i].count;
                }
                return res.toFixed(1);
            },
            itemPriceResult(){ return (item) => this.$root.itemPriceResult(item) }
        },
        created() {
            self = this
            try {
                var parsed = this.ids && JSON.parse(this.ids)
            } catch (error) {
                console.log(error)
            }
            var requestIDs = parsed || Object.keys(this.$store.state.cart)
            if(requestIDs.length) this.get_prodsby_ids(requestIDs);
        },
        methods: {
            reCount(id, i) {
                if (!/^([1-9]\d{0,})$/.test(this.products[i].count)) {
                    this.products[i].count = 1
                }
                this.ids || this.$store.commit('cart', {id, count: +this.products[i].count});
            },
            removeFromCart(id) {
                self.products.forEach((element,i) => {
                    if(element.id == id) self.products.splice(i, 1); 
                });
                this.$store.commit('cart', {id: id, toRemove: true});
            },
            get_prodsby_ids(ids) {
                self.products.length = 0
                axios.get('/products_with_discount_by_ids', { params: { ids: ids } }).then(function (response) {
                    response.data.forEach(function (element, i)  {
                        element.count = self.$store.state.cart[element.id] || 1
                        self.products.push(element)
                    });
                });
            },
            next_input(target, next) {
                this.validate[target.id] && document.getElementById(next).focus()
            },
            to_order() {
                if(this.payment=='pay_card' && !validator.isValid()) return;
                axios.post('/order', {
                    products: this.products,
                    card: this.card,
                    user_info: self.$refs.userInfo.userInfo,
                    payment: this.payment=='pay_card' ? 'visa' : this.payment,
                    delivery: this.delivery,
                    delivery_adr: 'somewhere',
                }).then(function (response) {
                    self.$store.commit('cartClear');
                    document.getElementById('order-done').style = "display: initial";
                    setTimeout(function (params) {
                        document.getElementById('order-done').style = "display: none";
                        self.$router.push('/')
                    }, 3000)
                });
            }
        }
    }
</script>
