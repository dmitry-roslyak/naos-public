<template>
    <div class="container-fluid" style="max-width: 80em">
        <div class="col-sm-3 col-md-2" style="padding:0px">
            <div class="ctg-btn fake-link">
                <i class="fa fa-list" style="font-size:1.2em"></i>
                {{lng.catalog}}
            </div>
            <ul class="ctg-frm" style="display: inherit">
                <div class="ctg-itm fake-link" v-for="item in catalog" @click="category(item.id)" :key="item.id">
                    {{lng[item.name]?lng[item.name]:item.name}}
                </div>
                <div class="ctg-itm fake-link" v-for="i in dummyCategory" :key="i">*</div>
            </ul>
        </div>
        <div v-if="items.length" class="col-sm-6">
            <div id="carousel1" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li data-target="#carousel1" data-slide-to="0" class="active"></li>
                    <li data-target="#carousel1" data-slide-to="1"></li>
                    <li data-target="#carousel1" data-slide-to="2"></li>
                    <li data-target="#carousel1" data-slide-to="3"></li>
                    <li data-target="#carousel1" data-slide-to="4"></li>
                </ol>
                <!-- Wrapper for slides -->
                <div class="carousel-inner carousel-inner-bcolor" role="listbox">
                    <div v-for="(item,i) in items" :class="'item '+(i?'':'active')" :key="item.id">
                        <img class="carousel-img" :src="'file/'+item.img_src" alt="...">
                        <div class="carousel-caption">
                            <h3>
                                <router-link :to="{ name: 'detail', params: { id: item.id }}" class="carousel-content">
                                    {{item.name}}
                                </router-link>
                            </h3>
                            <span class="carousel-content">{{(currency * item.price).toFixed(1)+' '+lng.currency}}</span>
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
    var self, data = {
        lng: {},
        catalog: [],
        items: [],
        dummyCategory: []
    };
    export default {
        data: function () { return data; },
        computed: {
            currency: function () { return this.$store.state.currency }
        },
        mounted() {
            self = this;
            this.dummyCategory.length = 8;
            this.get_random_products();
            this.lng = window.lng;
            this.catalog = window.Laravel.catalog;
        },
        methods: {
            category(id) {
                self.$store.commit('set_ctg_id', id);
                self.$router.push("/products");
            },
            get_random_products() {
                axios.get('/prod_rnd').then(function (response) {
                    self.items = response.data;
                }).catch(function (error) {
                    self.$root.retry(self.get_random_products, error.response.status);
                });
            }
        }
    }
</script>

<style lang="sass">
.carousel-content
    color: white !important
    padding: 5px
    text-shadow: 0 0 5px black, 0 0 5px black
    background: radial-gradient(rgba(80,80,80,1),rgba(255,0,0,0))
    border-radius: 14px
    &:hover
        color: white
.carousel-img
    margin: 15px auto
    height: 33rem !important
.carousel-inner-bcolor
    background-color: gray
</style>