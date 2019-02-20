<template>
    <div class="container-fluid">
        <router-link to='/' class="hidden-xs hidden-sm col-md-2 naos">
            <span>N<span class="vflip">V</span>OS</span>
        </router-link>
        <div class="col-sm-8 col-md-7">
            <div class="input-group" style="margin-top:14px">
                <input type="text" class="form-control search-input" :placeholder="lng.search" v-model="search_text" @input="toSearch()" autofocus>
                <div class="search-list">
                    <table style="width:100%">
                        <tbody>
                            <tr class="search-itm" v-for="item in search_result" :key="item.id">
                                <td style="padding:4px;text-align:center">
                                    <img v-bind:src="'file/'+item.img_src" style="height:5rem">
                                </td>
                                <td>
                                    <router-link :to="{ name: 'detail', params: { id: item.id }}">{{item.name}}</router-link>
                                    <star-rating :rating="+item.rating" :star-size="16" :show-rating="false" :read-only="true"></star-rating>
                                </td>
                                <td>{{(currency * item.price).toFixed(1) +' '+ lng.currency}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <span class="input-group-btn" >
                    <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-3">
            <router-link to="/cart" class="dr-btn fake-link pull-right">
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
    var self, data = {
        lng: {},
        search_result: null,
        search_text: '',
    };
    export default {
        data: function () {return data;},
        computed: {
            currency: function () { return this.$store.state.currency }
        },
        mounted() {
            self = this;
            this.lng = window.lng;
        },
        methods: {
            toSearch() {
                if (!this.search_text.length) { 
                    self.search_result = null;
                    return;
                }
                _.throttle(function(){
                    axios.post('/search', {
                        search: self.search_text
                    }).then(function (response) {
                        self.search_result = response.data;
                    });
                }, 500)
            }
        }
    }
</script>