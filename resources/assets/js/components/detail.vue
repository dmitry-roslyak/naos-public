<template>
    <div class="container-fluid" style="max-width: 80em;padding:0">
        <div v-if="item" class="col-md-7" style="padding-right:0">
            <!-- <h4 style="padding-left:8px">{{item.name}}</h4> -->
            <div class="action-frm" style="border-color: white;border: 1px solid white;border-width: 0 0 1px 1px;background-color:inherit">
                <a class="action-item fake-link" @click="to_wish()">
                    <span class="hidden-xs">{{lng.to_wishlist}}</span>
                    <i class="fa fa-heart heart-state" :data-check="item.isWish" aria-hidden="true"></i>
                </a>
                <a class="action-item fake-link">&nbsp;
                    <i class="fa fa-share-alt heart-state" aria-hidden="true"></i>
                    <div @click="fbshare" class="fake-link">{{lng.share}}&nbsp;<i class="fa fa-facebook-official"></i></div>
                </a>
            </div>
            <!-- <div class="col-sm-1" style="padding:0 2px;margin-top:8px;">
                <div class="col-md-12 col-sm-12" style="margin:0;padding:0;height:4.85em" v-for="(img,i) in img_list" v-if="i<4">
                    <div class="thumbnail" style="border-width:0;background-color: inherit;">
                        <img v-bind:src="'file/'+img.img" @mouseover="imgZoom=img.img" @mouseleave="imgZoom=-1" style="max-height:4.85em;max-width:4em">  
                    </div>
                </div>
            </div> -->
            <!-- <div class="col-xs-7 thumbnail" style="border-width: 0;background-color: transparent">
                <img v-bind:src="'file/'+item.img_src" style="max-height:28rem">
            </div> -->
            <div id="carousel2" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators" v-if="item.gallery">
                    <li data-target="#carousel2" data-slide-to="0" class="active"></li>
                </ol>
                <div class="carousel-inner carousel-inner-bcolor" role="listbox" style="background-color: inherit;background-image: linear-gradient(141deg, #ffffff00 0%, #ffffff00 40%, #1484e3 100%);">
                    <div class="item active">
                        <img class="carousel-img" :src="'file/'+item.img_src" alt="..." style="max-height:28rem">
                        <!-- <div class="carousel-caption carousel-content">
                            <h3>{{item.name}}</h3>
                            <star-rating :rating="+item.rating" :star-size="16" :show-rating="false" :read-only="true"></star-rating>
                            <span>{{(currency * item.price).toFixed(1)+' '+lng.currency}}</span>
                        </div> -->
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
                        {{(item.discount?currency * item.price - currency * item.price/100*item.discount.discount : currency * item.price).toFixed(1)+' '+lng.currency}}
                </div>
                <div class="btn-buy disabled" v-else>{{lng.not_in_stock}}</div>
            </div>
            <div style="margin-top:15px">
                <ul class="nav nav-tabs" >
                    <li role="presentation" v-if="show_specs" class="active"><a>{{lng.specs}}</a></li>
                    <li role="presentation" v-else><a @click="show_specs=true">{{lng.specs}}</a></li>
                    <li role="presentation" v-if="show_specs==false" class="active"><a>{{lng.descr}}</a></li>
                    <li role="presentation" v-else><a @click="show_specs=false">{{lng.descr}}</a></li>
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
                <charts v-if="showGraph"></charts>
            </div>
        </div>
        <comments class="col-md-5"></comments> 
    </div>
</template>
<script>
    var self, timerId, data = {
        show_specs: true,
        item: null,
        img_list: [],
        lng: {},
        offerTime: null,
        showGraph: true
    };
    export default {
        props: ['id'],
        data: function() { return data },
        computed: {
            currency: function () { return this.$store.state.currency }
        },
        mounted(){
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
        methods:{
            clientWidth(){
                if(document.documentElement.clientWidth<620) this.showGraph = false;
                else this.showGraph = true;
            },
            fbshare(){
                window.open('https://www.facebook.com/dialog/share?'+
                "app_id=1358482950908486&display=popup&href="+location.host+'/#'+this.$route.path);
            },
            buyItem(item){
                this.$store.commit('cart', {id: item.id, count: 1});
            },
            to_compare(i){
                this.item.is_compare = this.item.is_compare ? false : true
                this.$store.commit('compare', this.item.id);
                this.$forceUpdate();
            },
            to_wish(){
                axios.post('/to_wish',{
                    id: self.item.id
                }).then(function (response) {
                    self.item.isWish = response.data ? true : false;
                    self.$forceUpdate();
                }).catch(function (error) {
                });
            },
            itemById(){
                axios.get('prod_by_id?id='+self.id).then(function (response) {
                    self.item = response.data;
                    self.item.isWish = response.data.is_wish ? true : false;
                    self.item.is_compare = self.$root.compareHas(self.item.id) > -1;
                    self.set_total_time();
                }).catch(function (error) {
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