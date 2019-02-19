<template>
    <div class="container-fluid">
        <router-link to='/' class="hidden-xs hidden-sm col-md-2 naos">
            <span>N<span class="vflip">V</span>OS</span>
        </router-link>
        <div class="col-sm-8 col-md-7">
            <div style="position: relative;margin-top:14px" @mouseenter="search_result?search_show=1:null" @mouseleave="search_show=0">
                <div class="input-group">
                    <input type="text" class="form-control" :placeholder='lng.search' v-model="search_text" @input='toSearch' autofocus>
                    <span class="input-group-btn" >
                        <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
                    </span>
                </div>
                <div class="search-list" :show-st="search_show">
                    <table style="width:100%">
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
        </div>
        <div class="col-xs-12 col-sm-4 col-md-3">
            <router-link v-show="cartVisible" to="/cart/[]" class="dr-btn fake-link pull-right">
                <i class="fa fa-shopping-cart font1" aria-hidden="true"></i>
                <div><nobr>{{lng.cart}}</nobr></div>
                <span class="badge badge-offset" >{{this.$store.state.cartLength}}</span>
            </router-link>
            <a id="compare" href="#" class="dr-btn pull-right" @click="$event.preventDefault();compare()">
                <i class="fa fa-balance-scale font1" aria-hidden="true"></i><div><nobr>{{lng.compare}}</nobr></div>
                <span class="badge badge-offset">{{compareLength1}}</span>
                <div v-show="compareLength() > 1" class="compare-drop">
                    <div v-for="(item, key) in compare1" :key="key" @click="compare(item)">{{lng[ctg(key)] + ': ' + item.length}}</div>
                </div>
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
        catalog: []
    };
    export default {
        data: function () {return data;},
        computed: {
            compareLength1() { return this.$store.state.compareLength },
            compare1() { return this.$store.state.compare },
            currency: function () { return this.$store.state.currency },
            cartVisible() { return this.$route.path.indexOf("cart") > -1 ? false : true }
        },
        mounted() {
            self = this;
            this.lng = window.lng;
            this.catalog = window.Laravel.catalog;
            this.$store.commit('compareInit');
        },
        methods: {
            compareLength() { return Object.keys(this.$store.state.compare).length },
            ctg(value){
                for (const key in this.catalog) {
                    if (this.catalog[key].id == value) {
                        return key;
                    }
                }
            },
            compare(item = []){
                (this.$store.state.compare[Object.keys(this.$store.state.compare)[0]] && this.compareLength() == 1) ? 
                    item = this.$store.state.compare[Object.keys(this.$store.state.compare)[0]] : null;
                if(item.length || this.$store.state.compare_list.length>1) {
                    compare.blur()
                    this.$router.push("/compare/" + JSON.stringify(item || self.$store.state.compare_list));
                }
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
                        });
                    }, 500)
                } else self.search_show = 0;
            }
        }
    }
</script>

<style lang="scss">
.compare-drop {
    position: absolute;
    transition: all 0.3s;
    background-color: #ffffff;
    right: 0;
    color: black;
    margin: 15px 0px;
    border-radius: 0.3em;
    box-shadow: 0 1px 0.3em cornflowerblue;
    z-index: 5;
    border-top: 1px solid cornflowerblue;
    white-space: nowrap;
    text-align: left;
    opacity: 0;
    transform-origin: top right;
    transform: scale(0);
}
.compare-drop > div {
    border-radius: 0.3em;
    padding: 5px 10px;
}
.compare-drop > div:hover {
    background-color: #f5f5f5;
    color: cornflowerblue;
}
.compare-drop::after {
    content: "";
    top: -21px;
    position: absolute;
    right: 15px;
    border: 0 solid transparent;
    border-bottom-color: cornflowerblue;
}
</style>
