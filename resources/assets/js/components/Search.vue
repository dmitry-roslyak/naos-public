<template>
    <div class="container-fluid" style="padding:12px">
        <router-link to='/'>
            <span class="col-sm-12 col-md-2 naos">N&nbsp;&nbsp;&nbsp;<span class="vflip">V</span>OS</span>
        </router-link>
        <div class="col-sm-8 col-md-6" style="padding:0;margin-top:14px" @mouseenter="search_result?search_show=1:null" @mouseleave="search_show=0">
            <div class="input-group">
                <input type="text" class="form-control" :placeholder='lng.search' v-model="search_text" @input='toSearch' autofocus>
                <span class="input-group-btn" >
                    <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
                </span>
            </div>
            <div class="search-list" :show-st="search_show">
                <table style="margin-bottom: 0;width:100%">
                    <tbody style="border-width:0" v-for="item in search_result" :key="item.id">
                        <!-- <div style="height:1.5em;" v-if="item.show">
                        <span style="position:absolute;width:99%;background:cornflowerblue;color:white">{{lng[item.ctg.name]?lng[item.ctg.name]:item.ctg.name}}</span>
                        </div> -->
                        <tr class="search-itm">
                            <td style="border-width:0;width:0;padding:4px;text-align:center">
                                <img v-bind:src="'file/'+item.img_src" style="height:5rem">
                            </td>
                            <td style="border-width:0;padding:4px">
                                <router-link :to="{ name: 'detail', params: { id: item.id }}">{{item.name}}</router-link>
                                <star-rating :rating="+item.rating" :star-size="16" :show-rating="false" :read-only="true"></star-rating>
                            </td>
                            <td style="border-width:0">{{(currency * item.price).toFixed(1) +' '+ lng.currency}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4">
            <router-link v-show="cartVisible" to="/cart/[]" class="dr-btn fake-link pull-right">
                <i class="fa fa-shopping-cart font1" aria-hidden="true"></i>
                <div><nobr>{{lng.cart}}</nobr></div>
                <span class="badge badge-offset" >{{this.$store.state.cartLength}}</span>
            </router-link>
            <a href="#" class="dr-btn pull-right" @click="$event.preventDefault();compare()">
                <i class="fa fa-balance-scale font1" aria-hidden="true"></i>
                <div><nobr>{{lng.compare}}</nobr></div>
                <span class="badge badge-offset">{{this.$store.state.compare_list.length}}</span>
            </a>
        </div>
    </div>
</template>
<script>
    var self, timerId, data = {
        lng: {},
        search_result: null,
        search_show: false,
        search_text: '',
    };
    export default {
        data: function () {return data;},
        computed: {
            currency: function () { return this.$store.state.currency },
            cartVisible() { return this.$route.path.indexOf("cart") > -1 ? false : true }
        },
        mounted() {
            self = this;
            this.lng = window.lng;
        },
        methods: {
            compare(){
                if(this.$store.state.compare_list.length>1)
                    this.$router.push("/compare/" + JSON.stringify(self.$store.state.compare_list));
            },
            toSearch() {
                if (this.search_text > '') {
                    _.throttle(function(){
                        axios.post('/search', {
                            search: self.search_text
                        }).then(function (response) {
                            self.search_result = response.data;
                            var temp = null;
                            for (var i = 0; i < self.search_result.length; i++) {
                                if(self.search_result[i].category_id == temp){
                                    self.search_result[i].show = 0;
                                }else {
                                    self.search_result[i].show = 1;
                                    temp=self.search_result[i].category_id;
                                }   
                            }
                            if(self.search_result.length<1){
                                self.search_show = 0;
                                self.search_result = 0;
                            }else self.search_show = 1;
                        }).catch(function (error) {
                            self.$root.retry(self.toSearch, error.response.status);
                        });
                    }, 500)
                } else self.search_show = 0;
            }
        }
    }
</script>