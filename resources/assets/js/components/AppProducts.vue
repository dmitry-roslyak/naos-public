<template>
    <div class="container-fluid">
        <app-products-filters class="col-sm-3 col-md-2" style="padding: 0"></app-products-filters>
        <div class="col-sm-9 col-md-10" style="padding:0">
            <div class="panel panel-default itmc">
                <div class="panel-body">
                    <label>{{lng.showed_items}}</label>
                    <div class="dropdown">
                        <a class="dropdown-toggle fake-link" data-toggle="dropdown" aria-haspopup="true">
                            {{items.length}}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="fake-link" @click="paginator.take = 20">20</a></li>
                            <li><a class="fake-link" @click="paginator.take = 30">30</a></li>
                            <li><a class="fake-link" @click="paginator.take = 40">40</a></li>
                        </ul>
                    </div>
                    ({{paginator.total}})
                    <div class="pull-right">
                        <label for="sortby">{{lng.sortby}}&nbsp;</label>
                        <select v-model="ordby" class="form-control input-sm" id="sortby" @change="productsfetch()"> 
                            <option value="bydef">{{lng.bydef}}</option>
                            <option value="asc_price">{{lng.asc_price}}</option>
                            <option value="desc_price">{{lng.desc_price}}</option>
                            <option value="byrating">{{lng.byrating}}</option>
                            <option value="bynewest">{{lng.bynewest}}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div v-for="(item,i) in items" class="col-sm-6 col-md-4 col-lg-3 item-card" :key="item.id">
                <div class="item-note soon" v-if="item.isArriveSoon">{{lng.soon}}</div>
                <div class="item-note new" v-else-if="item.isNew">{{lng.new}}</div>
                <div class="item-note offer" v-else-if="item.discount">{{lng.offer}}</div>
                <div class="item-note hot" v-else-if="item.is_bestseller==1">{{lng.hot}}</div>
                <div class="action-frm">
                    <a class="action-item fake-link" @click="to_compare(i)">
                        <span class="hidden-xs">{{lng.to_compare}}</span>
                        <i class="fa fa-balance-scale compare-state anm-bounce-scale" :data-check="item.is_compare" aria-hidden="true"></i>
                    </a>&nbsp;
                    <a class="action-item fake-link" @click="to_wish(i)">
                        <span class="hidden-xs">{{lng.to_wishlist}}</span>
                        <i class="fa fa-heart heart-state anm-bounce-scale" :data-check="item.isWish" aria-hidden="true"></i>
                    </a>
                </div>
                <div class="thumbnail ic-s">
                    <div class="image-wrapper">
                        <img :src="'/file/'+item.img_src" @load="imgReady($event.target)" @error="img404($event.target)">
                    </div>
                    <div class="caption">
                        <router-link class="item-card-name" :to="{ name: 'detail', params: { id: item.id }}">{{item.name}}</router-link>
                        <div class="col-xs-12" style="padding: 3px 0">
                            <star-rating :rating="+item.rating" :star-size="16" :show-rating="false" :read-only="true" style="display:inline-block"></star-rating>
                            <div class="product-state pull-right">
                                <s v-if="item.discount&&item.available">{{(currency * item.price).toFixed(1)}}</s>
                                <span v-if="!item.available">{{lng.not_in_stock}}</span>
                                <span v-else>{{itemPriceResult(item)}}</span>
                            </div>
                        </div>
                        <div class="col-xs-12" style="padding: 0 0 8px">
                            <div class="btn-group pull-right" role="group" aria-label="...">
                                <button type="button" class="btn btn-default action-item" @click="addToCart(i, item.id)">
                                    <span>{{lng.addto_cart}}</span>
                                    <i class="fa fa-cart-plus btn-in-cart-i anm-bounce-scale" :data-check="item.isInCart" aria-hidden="true"></i>&nbsp;&nbsp;
                                </button>
                                <button type="button" class="btn btn-primary" @click="buy(item.id)">{{lng.buy}}</button>
                            </div>
                        </div>
                        <table class="item-spec">
                            <tbody >
                                <tr v-for="(specs,i) in item.specs" :key="i">
                                    <td>{{lng[specs.name]?lng[specs.name]:specs.name}}</td>
                                    <td>{{specs.value}}&nbsp;{{specs.val_type}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <v-pagination v-model="paginator" class="col-xs-12"></v-pagination>      
    </div>
</template>
<script>
    var self, data = {
        ordby: 'bydef',
        items: [],
        price: {
            array: [],
            range: [null, null],
        },
        paginator: {
            total: 0,
            take: 30,
            skip: 0,
            page: 1
        },
    };
    export default {
        props: ['category'],
        data: function () {return data;},
        watch: {
            '$store.state.flt_ids': 'productsfetch',
            'paginator.take': 'productsfetch',
            'paginator.skip': function () {
                this.productsfetch('withSkip');
             },
            'price.range': function (array) {
                array.length == 2 && this.productsfetch();
            }
        },
        computed: {
            lng(){ return this.$root.lng },
            currency() { return this.$store.state.currency },
            itemPriceResult(){ return (item) => this.$root.itemPriceResult(item) }
        },
        created() {
            self = this; 
        },
        methods: {
            imgReady(e){
                e.style.visibility = 'initial';
            },
            img404(e){
                e.src = "/images/404.png";
                e.style.visibility = 'initial';
                e.style.padding = "4em";
            },
            productsfetch: debounce(function(arg) {
                if(arg != "withSkip") {
                    this.paginator.skip = 0;
                    this.paginator.page = 1;
                }
                var price = [this.price.range[0] / this.currency, this.price.range[1] / this.currency];
                axios.get('/prod_filter', {
                    params: {
                        ctg_id: window.Laravel.catalog[this.category].id,
                        skip: this.paginator.skip,
                        take: this.paginator.take,
                        f: this.$store.state.flt_ids,
                        price: price,
                        ordby: this.ordby
                    }
                }).then(function (response) {
                    var categoryIndex = self.$store.getters.compareCategoryIndex(window.Laravel.catalog[self.category].id)
                    var items = response.data[1];
                    for (var i = 0; i < items.length; i++) {
                        items[i].isInCart = !!self.$store.state.cart[items[i].id];
                        items[i].isWish = !!items[i].wish;
                        items[i].is_compare = categoryIndex > -1 && self.$store.getters.isCompare(categoryIndex, items[i].id) > -1;
                        items[i].isArriveSoon = new Date(items[i].arrive_date) > new Date();
                        items[i].isNew = new Date(items[i].arrive_date) > new Date() - 1000 * 60 * 60 * 24 * 21;
                    }
                    self.items = items; 
                    for (var n = [], i = 0; i < response.data[2].length; i++) {
                        n.push(+response.data[2][i].price);
                    }
                    self.price.array = n.sort(function(t, e) {
                        return t - e
                    })
                    if(!self.price.range[0] && !self.price.range[1]) {
                        self.price.range = [
                            (self.price.array[0] * self.currency).toFixed(1),
                            (self.price.array[self.price.array.length-1] * self.currency).toFixed(1),
                            { doNotFetch: true }
                        ]
                    }
                    self.paginator.total = response.data[0];
                });
            }, 750),
            addToCart(i, id){
                this.items[i].isInCart = !this.$store.state.cart[id];
                this.$store.commit('cart', {id: id, count: 1, toRemove: !this.items[i].isInCart});
            },
            buy(id){
                this.$router.push(`/cart/[${id}]`)
            },
            to_compare(i){
                this.items[i].is_compare = !this.items[i].is_compare;
                this.$store.commit('compare', {id: this.items[i].id, category_id: this.items[i].category_id, category: this.category});
            },
            to_wish(i){
                self.items[i].isWish = !self.items[i].isWish ;
                axios.post('/to_wish',{
                    id: self.items[i].id
                }).catch(function () {
                    self.items[i].isWish = !self.items[i].isWish ;
                });
            }
        }
    }
</script>

<style lang="scss" src="../../sass/AppProducts.scss"></style>