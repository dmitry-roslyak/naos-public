<template>
    <div class="container-fluid">
        <sidebar class="col-sm-3 col-md-2" style="padding: 0px 4px 0px 0px;"></sidebar>
        <div class="col-sm-9 col-md-10" style="padding:0">
            <div class="row itmc">
                {{lng.showed_items}}
                <a v-show="cng1" style="text-decoration:none" @click="cng1=false">&nbsp;{{items.length}}&nbsp;</a>
                <select v-show="!cng1" v-model.number="paginator.take" class="form-control input-sm" id="items-on-page" @change="getSelectedProd();cng1=true" @mouseleave="cng1=true">       
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                </select>({{paginator.total}})
                <div class="pull-right">
                    {{lng.sortby}}
                    <select v-model="ordby" class="form-control input-sm" id="sortby" @change="getSelectedProd()"> 
                        <option value="bydef">{{lng.bydef}}</option>
                        <option value="asc_price">{{lng.asc_price}}</option>
                        <option value="desc_price">{{lng.desc_price}}</option>
                        <option value="byrating">{{lng.byrating}}</option>
                        <option value="bynewest">{{lng.bynewest}}</option>
                    </select>
                </div>
            </div> 
            <div v-for="(item,i) in items" class="col-sm-6 col-md-4 col-lg-3 item-card" :key="item.id">
                <div class="item-note soon" v-if="item.isArriveSoon">{{lng.soon}}</div>
                <div class="item-note new" v-else-if="item.isNew">{{lng.new}}</div>
                <div class="item-note offer" v-else-if="item.discount">{{lng.offer}}</div>
                <div class="item-note hot" v-else-if="item.is_bestseller==1">{{lng.hot}}</div>
                <div class="action-frm" style="right:4px">
                    <a class="action-item fake-link" @click="to_compare(i)">
                        <span class="hidden-xs">{{lng.to_compare}}</span>
                        <i class="fa fa-balance-scale compare-state" :data-check="item.is_compare" aria-hidden="true"></i>
                    </a>&nbsp;
                    <a class="action-item fake-link" @click="to_wish(i)">
                        <span class="hidden-xs">{{lng.to_wishlist}}</span>
                        <i class="fa fa-heart heart-state" :data-check="item.isWish" aria-hidden="true"></i>
                    </a>
                </div>
                <div class="thumbnail ic-s">
                    <i class="fa fa-cog fa-spin" style="font-size:10rem"></i>  
                    <img class="item-card-img" style="display: none" :src="'file/'+item.img_src" @load="imgReady($event.target)" @error="img404($event.target)">
                    <div class="caption" style="padding: 2px 6px">
                        <router-link class="item-card-name" :to="{ name: 'detail', params: { id: item.id }}">{{item.name}}</router-link>
                        <star-rating :rating="+item.rating" :star-size="16" :show-rating="false" :read-only="true"></star-rating>
                        <!-- <router-link  to="coms">{{item.vote_count>0?lng.comments+': '+item.vote_count:lng.leave_comment}}</router-link> -->
                        <div class="row" style="padding:10px">
                            <div class="product-state">
                                <s v-if="item.discount&&item.available">{{(currency * item.price).toFixed(1)+' '+lng.currency}}</s>
                                <span v-if="!item.available">{{lng.not_in_stock}}</span>
                            </div>
                            <button v-if="item.available" class="btn btn-primary btn-md pull-right" @click="buyItem(item)">
                                <i class="fa fa-cart-plus" aria-hidden="true"></i>&nbsp;&nbsp;
                                {{(item.discount?currency * item.price - currency * item.price/100*item.discount.discount : currency * item.price).toFixed(1)+' '+lng.currency}}                       
                            </button>
                        </div>                    
                        <table class="item-spec">
                            <tbody >
                                <tr v-for="(specs,i) in item.specs" :key="i">
                                    <td>{{lng[specs.name]?lng[specs.name]:specs.name}}</td>
                                    <td style="padding-left:10px;width:55%">{{specs.value}}&nbsp;{{specs.val_type}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <buy-modal ref="buyModal"></buy-modal>
        <pagination v-model="paginator" class="col-xs-12"></pagination>      
    </div>
</template>
<script>
    var self, data = {
        lng: {},
        ordby: 'bydef',
        items: [],
        cng1: true,
        price: {
            array: null,
            range: [0, 0],
            visible: !0
        },
        paginator: {
            total: 0,
            take: 30,
            skip: 0,
            func: null
        },
    };
    export default {
        data: function () {return data;},
        computed: {
            currency: function () { return this.$store.state.currency },
        },
        mounted() {
            self = this; 
            this.lng = window.lng;
            this.paginator.func = this.getSelectedProd;
            this.getSelectedProd();
            // window.onhashchange= function(){
            //     if (location.hash != temp) data_self.anmRunning=0;
            // };
        },
        methods: {
            imgReady(e,i){
                e.parentElement.firstChild.style = 'display:none';
                e.style.display = 'inherit';
            },
            img404(e,i){
                e.parentElement.firstChild.style = 'display:none';
                e.src = "/images/404.png";
                e.style.display = 'inherit';
            },
            getSelectedProd() {
                var price = [];
                price.push(this.price.range[0] / this.$store.state.currency, this.price.range[1] / this.$store.state.currency);
                axios.get('prod_filter', {
                    params: {
                        ctg_id: this.$store.state.ctg_id,
                        skip: this.paginator.skip,
                        take: this.paginator.take,
                        f: this.$store.state.flt_ids,
                        price: price,
                        ordby: this.ordby
                    }
                }).then(function (response) {
                    var items = response.data[1];
                    for (var i = 0; i < items.length; i++) {
                        items[i].isWish = items[i].wish ? true : false;
                        items[i].is_compare = self.$root.compareHas(items[i].id) > -1;
                        items[i].isArriveSoon = new Date(items[i].arrive_date) > new Date();
                        items[i].isNew = new Date(items[i].arrive_date) > new Date() - 1000 * 60 * 60 * 24 * 21;
                    }
                    self.items = items; 
                    for (var n = [], i = 0; i < response.data[2].length; i++) {
                        n.push(self.$store.state.currency * response.data[2][i].price);
                    }
                    self.price.array = n.sort(function(t, e) {
                        return t - e
                    })
                    self.paginator.total = response.data[0]
                }).catch(function (error) {
                    self.$root.retry(self.getSelectedProd, error.response.status);
                });
            },
            buyItem(item){
                if(item.available) this.$refs.buyModal.$data.item = item;
            },
            to_compare(i){
                this.items[i].is_compare = this.items[i].is_compare ? false : true
                this.$store.commit('compare', this.items[i]);
            },
            // anm_scale($event.target) //need remove?
            // anm_scale(e){
            //     e.parentNode.lastElementChild.animate([
            //         { transform: 'scale(1)' }, 
            //         { transform: 'scale(1.4)' }, 
            //         { transform: 'scale(1)' }
            //     ], { duration: 350,iterations: 1});
            // },
            to_wish(i){
                axios.post('/to_wish',{
                    id: self.items[i].id
                }).then(function (response) {
                    self.items[i].isWish = response.data ? true : false;
                }).catch(function (error) {
                    self.$root.retry(self.to_wish, error.response.status);
                });
            }
        }
    }
</script>

<style lang="sass" src="../../sass/product.sass"></style>