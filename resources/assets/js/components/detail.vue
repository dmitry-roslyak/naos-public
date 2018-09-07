<template>
    <div class="container-fluid">
        <search></search><hr style="margin: 0 15px">
        <buy-modal ref="buyModal"></buy-modal>
        <div v-if="item" class="col-md-7" style="padding-right:0">
            <h4 style="padding-left:8px">{{item.name}}</h4>
            <div class="action-frm">
                <a class="action-item fake-link" @click="to_wish()">
                    <span class="hidden-xs">{{lng.to_wishlist}}</span>
                    <i class="fa fa-heart heart-state" :data-check="item.isWish" aria-hidden="true"></i>
                </a>
                <!-- <a class="halots fake-link">&nbsp;
                    <i class="fa fa-share-alt heart-state" data-check="0" aria-hidden="true"></i>
                    <div @click="fbshare" class="fake-link share fbshare">{{lng.share}}&nbsp;<i class="fa fa-facebook-official"></i></div>
                    <div @click="gshare" class="fake-link share gshare">{{lng.share}}&nbsp;<i class="fa fa-google-plus"></i></div>
                </a> -->
            </div>
            <div class="prod-container">
                <div class="btn-buy fake-link" v-if="item.available > 0" @click="buyItem(item)">
                    <i class="fa fa-cart-plus" aria-hidden="true"></i>&nbsp;&nbsp;
                        {{(item.discount?currency * item.price - currency * item.price/100*item.discount.discount : currency * item.price).toFixed(1)+' '+lng.currency}}
                </div>
                <div class="btn-buy disabled" v-else>{{lng.not_in_stock}}</div>
                <!-- <div class="col-sm-1" style="padding:0 2px;margin-top:8px;">
                    <div class="col-md-12 col-sm-12 hidden-exs" style="margin:0;padding:0;height:4.85em" v-for="(img,i) in img_list" v-if="i<4">
                        <div class="thumbnail" style="border-width:0;background-color: inherit;">
                            <img v-bind:src="'file/'+img.img" @mouseover="imgZoom=img.img" @mouseleave="imgZoom=-1" style="max-height:4.85em;max-width:4em">  
                        </div>
                    </div>
                </div> -->
                <div class="col-xs-7 thumbnail" style="border-width: 0;background-color: transparent">
                    <img v-bind:src="'file/'+item.img_src" style="max-height:28rem">
                </div>
                <div class="col-xs-4" style="padding:2.5em 0 0 0">
                    <star-rating :rating="+item.rating" :star-size="16" :show-rating="false" :read-only="true"></star-rating>
                    <div class="tb-offer" v-if="+offerTime > 0">
                        <div class="offer-caption">{{lng.discount +' -'+item.discount.discount+'%'}}</div>
                        <div class="hidden-exs">
                            <h5 style="margin-left:1em">{{lng.offer_end_at}}</h5>
                            <table class="table" style="margin-bottom: 0">
                                <tr>
                                    <th style="text-align: center;border-width:0">{{zero(offerTime.getDate())}}</th>
                                    <th style="text-align: center;border-width:0">{{zero(offerTime.getHours())}}</th>
                                    <th style="text-align: center;border-width:0">{{zero(offerTime.getMinutes())}}</th> 
                                    <th style="text-align: center;border-width:0">{{zero(offerTime.getSeconds())}}</th>
                                </tr>
                                <tr style="text-align: center">
                                    <td style="border-width:0">{{lng.offer_d}}</td>
                                    <td style="border-width:0">{{lng.offer_h}}</td>
                                    <td style="border-width:0">{{lng.offer_m}}</td>
                                    <td style="border-width:0">{{lng.offer_s}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
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
            // newSchema(){
            //     var prodSchema = document.createElement('script');
            //     prodSchema.setAttribute('type','application/ld+json');
            //     var obj = {
            //         "@context": "http://schema.org/",
            //         "@type": "Product",
            //         name: selfData.item.name,
            //         image: window.location.hostname+'/file/'+selfData.item.img_src,
            //         description: selfData.item.description,
            //         brand: {
            //             "@type": "Thing",
            //             name: "ACME"
            //         },
            //         aggregateRating: {
            //             "@type": "AggregateRating",
            //             ratingValue: selfData.item.rating,
            //             reviewCount: selfData.item.vote_count
            //         },
            //         offers: {
            //             "@type": "Offer",
            //             priceCurrency: window.lng.currency,
            //             price: selfData.item.price,
            //             priceValidUntil: new Date(),
            //             itemCondition: "http://schema.org/UsedCondition",
            //             availability: selfData.item.available>1?"InStock":"OutOfStock",
            //             seller: {
            //                 "@type": "Organization",
            //                 name: "NAOS"
            //             }
            //         }
            //     };
            //     prodSchema.innerText=JSON.stringify(obj);
            //     document.getElementsByTagName('head')[0].appendChild(prodSchema);
            // },
            clientWidth(){
                if(document.documentElement.clientWidth<620) this.showGraph = false;
                else this.showGraph = true;
            },
            // fbshare(){
            //     window.open('https://www.facebook.com/dialog/share?'+
            //     "app_id=1358482950908486&display=popup&href="+location.host+'/#'+this.$route.path);
            // },
            // gshare(){
            //     window.open('https://plus.google.com/share?url='+location.host+'/#'+this.$route.path);
            //     // window.open('https://plus.google.com/share?url='+location.href+'?hl=ru');
            // },
            buyItem(item){
                if(item.available) this.$refs.buyModal.$data.item = item;
            },
            to_compare(){
                if(selfData.item.is_compare){
                    this.$store.commit('rm_compare', this.item.id);
                    this.item.is_compare = 0;
                }
                else{
                    this.$store.commit('add_compare', this.item.id);
                    this.item.is_compare = 1;
                }
                this.$forceUpdate();
            },
            to_wish(){
                axios.post('/to_wish',{
                    id: self.item.id
                }).then(function (response) {
                    self.item.isWish = response.data ? 1:0;
                    self.$forceUpdate();
                }).catch(function (error) {
                    self.$root.retry(self.to_wish, error.response.status);
                });
            },
            itemById(){
                axios.get('prod_by_id?id='+self.id).then(function (response) {
                    self.item = response.data;
                    self.item.isWish = response.data.is_wish ? 1:0;
                    self.item.is_compare = self.$root.compareHas(self.item.id);
                    self.set_total_time();
                    // self.newSchema();
                }).catch(function (error) {
                    self.$root.retry(self.itemById, error.response.status);
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