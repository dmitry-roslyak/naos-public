<template>
    <div class="container-fluid">
        <router-link to='/' class="hidden-xs hidden-sm col-md-2 naos">
            <span>N<span class="vflip">V</span>OS</span>
        </router-link>
        <div class="col-sm-8 col-md-7">
            <div class="input-group search">
                <input type="search" class="form-control" :placeholder="lng.search" 
                    @focus="search_result || toSearch()" @input="toSearch()" autofocus>
                <table>
                    <tbody>
                        <tr v-for="item in search_result" :key="item.id">
                            <td class="search-img-cell">
                                <img v-bind:src="'/file/'+item.img_src">
                            </td>
                            <td>
                                <router-link :to="{ name: 'AppDetail', params: { id: item.id }}">{{item.name}}</router-link>
                                <star-rating :rating="+item.rating" :star-size="16" :show-rating="false" :read-only="true"></star-rating>
                            </td>
                            <td><nobr>{{itemPriceResult(item)}}</nobr></td>
                        </tr>
                    </tbody>
                </table>
                <span class="input-group-btn" >
                    <button class="btn btn-default" type="button" @click="toSearch()"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-3">
            <router-link to="/cart" class="dr-btn fake-link pull-right">
                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                <div><nobr>{{lng.cart}}</nobr></div>
                <span class="badge badge-offset" >{{this.$store.getters.cartItemsCount}}</span>
            </router-link>
            <a id="compare" href="#" class="dr-btn pull-right" @click.prevent="$store.state.compare.length == 1 && toCompare(0)">
                <i class="fa fa-balance-scale" aria-hidden="true"></i><div><nobr>{{lng.compare}}</nobr></div>
                <span class="badge badge-offset">{{this.$store.getters.compareItemsCount}}</span>
                <div v-show="this.$store.state.compare.length > 1" class="compare-drop">
                    <div v-for="(item, key) in compare" :key="key" @click="toCompare(key)">{{lng[item.category] + ': ' + item.array.length}}</div>
                </div>
            </a>
        </div>
    </div>
</template>
<script>
    var data = {
        search_result: null,
    };
    export default {
        data: function () { return data; },
        computed: {
            lng(){ return this.$root.lng },
            compare() { return this.$store.state.compare },
            itemPriceResult(){ return (item) => this.$root.itemPriceResult(item) }
        },
        created() {
            this.$store.commit("loadFromLocalStorage",'compare');
            this.$store.commit("loadFromLocalStorage",'cart');
            this.$router.afterEach((to, from, next) => {
                this.search_result = null;
            });
        },
        methods: {
            toCompare(i){
                compare.blur()
                this.$router.push(`/compare/${this.$store.state.compare[i].category}/${JSON.stringify(this.$store.state.compare[i].array)}`);
            },
            toSearch: debounce(function (text) {
                var text = document.querySelector(".search > input").value;
                this.search_result = null;
                text.length && axios.post('/search', {
                    search: text
                }).then((response) => {
                    this.search_result = response.data;
                });
            }, 400)
        }
    }
</script>