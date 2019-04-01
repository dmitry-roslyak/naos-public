<template>
    <div class="container-fluid" style="max-width: 80em;padding:0">
        <div class="col-md-7" style="padding-right:0">
            <!-- <h4 style="padding-left:8px">{{item.name}}</h4> -->
            <div class="action-frm" style="border-color: white;border: 1px solid white;border-width: 0 0 1px 1px;background-color:inherit">
                <a class="action-item fake-link" @click="to_wish()">
                    <span class="hidden-xs">{{lng.to_wishlist}}</span>
                    <i class="fa fa-heart heart-state" :data-check="item.isWish" aria-hidden="true"></i>
                </a>
            </div>
            <div id="carousel2" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators" v-if="item.gallery">
                    <li data-target="#carousel2" data-slide-to="0" class="active"></li>
                </ol>
                <div class="carousel-inner carousel-inner-bcolor" role="listbox" style="background-color: inherit;background-image: linear-gradient(141deg, #ffffff00 0%, #ffffff00 40%, #1484e3 100%);">
                    <div class="item active">
                        <img class="carousel-img" style="max-height:28rem" :src="item.img_src && 'file/'+item.img_src">
                        <div class="tb-offer" v-if="+offerTime > 0">
                            <span>{{lng.discount +' -'+item.discount.discount+'%'}}</span>
                            <span class="hidden-xs">{{
                                lng.offer_end_at + " " +
                                zero(offerTime.getDate()) + " " + 
                                lng.offer_d + " " + 
                                zero(offerTime.getHours()) + ":" + 
                                zero(offerTime.getMinutes()) + ":" + 
                                zero(offerTime.getSeconds())
                                }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="btn-buy fake-link" v-if="item.available > 0" @click="buyItem(item)">
                    <i class="fa fa-cart-plus" aria-hidden="true"></i>&nbsp;&nbsp;
                    {{itemPriceResult(item)}}
                </div>
                <div class="btn-buy disabled" v-else>{{lng.not_in_stock}}</div>
            </div>
            <div style="margin-top:15px">
                <ul class="nav nav-tabs" >
                    <li role="presentation" :class="{ active: show_specs}" @click="show_specs=true"><a>{{lng.specs}}</a></li>
                    <li role="presentation" :class="{ active: !show_specs}" @click="show_specs=false"><a>{{lng.descr}}</a></li>
                </ul>
                <table v-if="show_specs&&item"  class="table border1">
                    <tbody style="text-align:center">
                        <tr v-for="(specs,i) in item.specs" :key="i">
                            <td>{{lng[specs.name]?lng[specs.name]:specs.name}}</td>
                            <td>{{specs.value}}&nbsp;{{specs.val_type}}</td>
                        </tr>
                    </tbody>
                </table>
                <div v-else class="border1" style="padding:4px">{{item.description}}</div>
                <app-detail-charts v-if="showGraph" :product-id="id"></app-detail-charts>
            </div>
        </div>
        <app-comments class="col-md-5" :product-id="id"></app-comments> 
    </div>
</template>
<script>
    var self, timerId, data = {
        show_specs: true,
        item: {},
        lng: {},
        offerTime: null,
        showGraph: true
    };
    export default {
        props: ['id'],
        data: function() { return data },
        computed: {
            itemPriceResult(){ return (item) => this.$root.itemPriceResult(item) }
        },
        created(){
            self = this
            this.lng = window.lng;
            this.itemById();
            this.clientWidth();
            window.onresize = function(){self.clientWidth();}; 
            window.onblur = () => {
                if(this.$route.path.indexOf("detail")>-1) clearInterval(timerId);
            }
            window.onfocus = () => {
                if(this.$route.path.indexOf("detail")>-1) {
                    clearInterval(timerId);
                    this.set_total_time();
                }
            }
        },
        destroyed(){ if(timerId) clearInterval(timerId);},
        methods: {
            clientWidth(){ this.showGraph = document.documentElement.clientWidth > 620 },
            buyItem(item){
                this.$store.commit('cart', {id: item.id, count: 1});
            },
            to_compare(i){
                this.item.is_compare = !this.item.is_compare
                this.$store.commit('compare', this.item.id);
                this.$forceUpdate();
            },
            to_wish(){
                axios.post('/to_wish',{
                    id: self.item.id
                }).then(function (response) {
                    self.item.isWish = !!response.data;
                    self.$forceUpdate();
                });
            },
            itemById(){
                axios.get('prod_by_id?id='+self.id).then(function (response) {
                    self.item = response.data;
                    self.item.isWish = !!response.data.is_wish;
                    self.set_total_time();
                });    
            },
            set_total_time(){
                this.offerTime = null;
                if(this.item && this.item.discount){
                    this.offerTime = new Date(this.item.discount.end_at) - new Date();
                    this.tick();
                }
            },
            zero(value){
                if(value < 10) value = '0' + value
                return value
            },
            tick(){
                if(+this.offerTime < 1) return;
                this.offerTime = new Date(this.offerTime - 1000)
                timerId = setTimeout(function(){ self.tick()  },1000);
            }
        }
    }
</script>

<style lang="sass" src="../../sass/detail.sass"></style>