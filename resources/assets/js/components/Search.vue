<template>
    <div class="container-fluid" style="padding:12px">
        <router-link to='/'>
            <span class="col-sm-12 col-md-2 naos">N&nbsp;&nbsp;&nbsp;<span class="vflip">V</span>OS</span>
        </router-link>
        <div class="col-sm-8 col-md-6" style="padding:0;margin-top:14px" @mouseenter="search_result?search_show=1:null" @mouseleave="search_show=0">
            <div class="input-group">
                <input type="text" class="form-control" :placeholder='lng.search' v-model="search_text" @input='searchTimeout' autofocus>
                <span class="input-group-btn" >
                    <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
                </span>
            </div>
            <div class="search-list" :show-st="search_show">
                <table style="margin-bottom: 0;width:100%">
                    <tbody style="border-width:0" v-for="item in search_result">
                        <!--<div style="height:1.5em;" v-if="item.show">
                        <span style="position:absolute;width:99%;background:cornflowerblue;color:white">{{lng[item.ctg.name]?lng[item.ctg.name]:item.ctg.name}}</span>
                        </div>-->
                        <tr class="search-itm">
                            <td style="border-width:0;width:0;padding:4px;text-align:center">
                                <img v-bind:src="'file/'+item.img_src" style="height:5rem">
                            </td>
                            <td style="border-width:0;padding:4px">
                                <router-link :to="{ name: 'detail', params: { id: item.id }}">{{item.name}}</router-link>
                                <star-rating :rating="item.rating" :star-size="16" :show-rating="false" :read-only="true"></star-rating>
                            </td>
                            <td style="border-width:0">{{(currency * item.price).toFixed(1) +' '+ lng.currency}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4">
            <router-link to="/cart/[]" class="dr-btn fake-link pull-right">
                <i class="fa fa-shopping-cart font1" aria-hidden="true"></i>
                <div><nobr>{{lng.cart}}</nobr></div>
                <span class="badge badge-offset" >{{this.$store.state.cartLength}}</span>
            </router-link>
            <a href="#" class="dr-btn pull-right" disabled  @click="$event.preventDefault()">
                <i class="fa fa-balance-scale font1" aria-hidden="true"></i>
                <div><nobr>{{lng.compare}}</nobr></div>
                <span class="badge badge-offset">{{this.$store.state.compare_list.length}}</span>
            </a>
        </div>
    </div>
</template>
<script>
    var data={
        lng: {},
        search_result:null,
        search_show:false,
        search_text:'',
        timerId:0
    };
    var selfData,self;
    export default {
        data: function () {return data;},
        computed: {
            currency: function () { return this.$store.state.currency }
        },
        mounted() {
            selfData = this.$data;
            self = this;
            selfData.lng = window.lng;
        },
        methods: {
            compare(){
                if(this.$store.state.compare_list.length>1)
                    this.$router.push("/compare/" + JSON.stringify(self.$store.state.compare_list));
            },
            searchTimeout(){
                if(selfData.timerId){
                    clearTimeout(selfData.timerId);
                    selfData.timerId = setTimeout(function(){ self.toSearch()},500);
                }
                else selfData.timerId = setTimeout(function(){ self.toSearch()},500);
            },
            toSearch() {
                if (selfData.search_text > '') {
                    axios.post('/search', {
                        search: selfData.search_text
                    }).then(function (response) {
                        selfData.search_result = response.data;
                        var temp = null;
                        for (var i = 0; i < selfData.search_result.length; i++) {
                            if(selfData.search_result[i].category_id==temp){
                                selfData.search_result[i].show = 0;
                            }else {
                                selfData.search_result[i].show = 1;
                                temp=selfData.search_result[i].category_id;
                            }   
                        }
                        if(selfData.search_result.length<1){
                            selfData.search_show = 0;
                            selfData.search_result = 0;
                        }else selfData.search_show = 1;
                    }).catch(function (error) {
                        self.$root.retry(self.toSearch, error.response.status);
                    });
                } else selfData.search_show= 0;
            }
        }
    }
</script>