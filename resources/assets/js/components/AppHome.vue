<template>
    <div class="container-fluid" style="max-width: 80em">
        <div class="col-sm-3 col-md-2" style="padding:0px">
            <div class="btn-primary fake-link">
                <div style="padding: 9px 10px 6px">
                    <i class="fa fa-list"></i>
                    {{lng.catalog}}
                </div>
                <ul class="ctg-frm" style="display: inherit">
                    <div class="btn-default fake-link" v-for="(item, name) in catalog" @click="category(name, item.id)" :key="item.id">
                        {{lng[name]?lng[name]:name}}
                    </div>
                    <div class="btn-default fake-link" v-for="i in dummyCategory" :key="i">&nbsp;</div>
                </ul>
            </div>
        </div>
        <div class="col-sm-9 col-md-10">
            <div id="carousel1" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li  data-target="#carousel1"
                        v-for="(item,i) in items" :class="{'active': !i}" :data-slide-to="i" :key="item.id">
                    </li>
                </ol>
                <!-- Wrapper for slides -->
                <div class="carousel-inner carousel-inner-bcolor" role="listbox">
                    <div v-for="(item,i) in items" :class="{'item': true, 'active': !i}" :key="item.id">
                        <img class="carousel-img" :src="'file/'+item.img_src" alt="...">
                        <div class="carousel-caption carousel-content">
                            <h3>
                                <router-link :to="{ name: 'detail', params: { id: item.id }}">
                                    {{item.name}}
                                </router-link>
                            </h3>
                            <span style="font-style:italic;text-shadow:0 0 1rem black">{{itemPriceResult(item)}}</span>
                        </div>
                    </div>
                </div>
                <a class="left carousel-control" href="#carousel1" role="button" data-slide="prev">
                    <span class="icon-prev"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#carousel1" role="button" data-slide="next">
                    <span class="icon-next"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>
</template>
<script>
    var data = {
        catalog: [],
        items: [],
        dummyCategory: []
    };
    export default {
        data: function () { return data; },
        computed: {
            lng(){ return this.$root.lng },
            itemPriceResult(){ return (item) => this.$root.itemPriceResult(item) }
        },
        mounted() {
            this.catalog = window.Laravel.catalog;
            this.dummyCategory.length = 8;
            this.get_random_products();
        },
        methods: {
            category(name, id) {
                this.$router.push('products/'+name);
            },
            get_random_products() {
                axios.get('/prod_rnd').then((response) => {
                    this.items = response.data;
                });
            }
        }
    }
</script>