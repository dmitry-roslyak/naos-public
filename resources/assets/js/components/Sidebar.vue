<template>
    <div class="container-fluid">
        <div class="ctg-btn fake-link" tabindex="0" @blur="catalog_btn_toggle(0)" @mouseover="catalog_btn_toggle(1)"  type="button" id="dropdownMenu1" aria-haspopup="true">
            <div style="padding: 9px 10px 6px">
                <i class="fa fa-list" style="font-size:1.2em"></i>
                {{lng.catalog}}
            </div>
            <ul class="ctg-frm" aria-labelledby="dropdownMenu1">
                <div class="ctg-itm fake-link" v-for="(item, name) in catalog" @click="get_filters(name, item.id);catalog_btn_toggle(0)" :key="item.id">
                    {{lng[name]?lng[name]:name}}
                </div>
            </ul>
        </div>
        <button class="form-control" v-show="showClear" @click="flt_reset">{{lng.flt_reset}}</button>
        <div class="thumbnail flt-grp" v-if="filters.length" >
            <div class="flt-btn fake-link" @click="price_show?price_show=false:price_show=true;expand($event.currentTarget)">
                {{lng.price}}
                <i v-show="price_show" class="fa fa-angle-up font1 pull-right" aria-hidden="true"></i>
                <i v-show="!price_show" class="fa fa-angle-down font1 pull-right" aria-hidden="true"></i>
            </div>
            <div class="flip" style="margin: 6px 0px;">
                {{lng.from}}<div class="input-group"><input type="number" class="form-control myinput1" v-model.number="price.range[0]" @input="priceRangeChange()"><span class="input-group-addon">{{lng.currency}}</span></div>
                {{lng.to}}<div class="input-group"><input type="number" class="form-control myinput1" v-model.number="price.range[1]" @input="priceRangeChange()"><span class="input-group-addon">{{lng.currency}}</span></div> 
                <range v-model="price" ref="range" @change="rangeIndexReset();priceRangeChange()" @ready="rangeIndexReset()"></range>
            </div>
        </div>
        <div class="thumbnail flt-grp" v-for="(filter,i1) in filters" :key="filter.id">
            <div class="flt-btn fake-link" @click="expand($event.currentTarget)">
                <span :title="filter.desc"><i class="fa fa-info-circle"></i></span>
                {{lng[filter.name]?lng[filter.name]:filter.name}}
                <i class="fa fa-angle-down font1 pull-right" style="display:none" aria-hidden="true"></i>
                <i class="fa fa-angle-up font1 pull-right" aria-hidden="true"></i>
            </div>
            <div class="flip">
                <div class="row" v-for="(value,i2) in filter.values" :key="value.id">
                    <div class="col-xs-2"></div>
                    <div class="col-xs-10">
                        <div class="checkbox" style="margin-top:2px;margin-bottom:2px">
                            <label><input style="height:1em" :data-id="value.id" :data-i1="i1" :data-i2="i2" type="checkbox" @click="toFilter($event)">
                                {{value.value}}
                                <!-- {{value.value+' ('+value.count+')'}} -->
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    const range = require('./Range.vue')
    // import range from './Range.vue'
    // var debounce = require('lodash.debounce');
    var throttle = require('lodash.throttle');
    var self, data = {
        lng: {},
        catalog: [],
        filters: [],
        price: {},
        price_show: true
    };
    export default {
        data: function () {return data;},
        components: {
            range
        },
        computed: {
            showClear: function () { return this.$store.state.flt_ids.length },
        },
        mounted() {
            self = this;
            this.catalog = window.Laravel.catalog;
            this.lng = window.lng;
            this.price = this.$parent.price;
            this.get_filters(this.$parent.category,window.Laravel.catalog[this.$parent.category].id);
        },
        methods: {
            rangeIndexReset(){
                this.price.range = [ this.price.array[this.price.indexFrom] * this.$store.state.currency , this.price.array[this.price.indexTo] * this.$store.state.currency ];
            },
            priceRangeChange() {
                _.throttle(this.$parent.getSelectedProd, 750)
            },
            expand: throttle(function(el) {
                $(el.parentElement.getElementsByClassName('flip')[0]).slideToggle();
                $(el.getElementsByClassName('fa-angle-up')[0]).toggle();
                $(el.getElementsByClassName('fa-angle-down')[0]).toggle();
            }, 300, { 'trailing': false }),
            catalog_btn_toggle(i){
                i?$(".ctg-frm").slideDown():$(".ctg-frm").slideUp();
            },
            flt_reset(){
                this.price.range = [null, null];
                var checkList = document.getElementsByClassName('checkbox');
                for (var i = 0; i < checkList.length; i++) {
                    checkList[i].firstChild.firstChild.checked = false;
                }
                this.$store.commit('setFilter')
            },
            get_filters(name, id) {
                this.$router.push('/products/'+name);
                axios.get('/get_filters?id='+id).then(function (response) {
                    self.filters = response.data;
                });                  
                this.flt_reset();
                this.$parent.getSelectedProd().then( ()=> self.$refs.range.$emit('reset') );
            },
            toFilter(e){
                this.$store.commit('setFilter', this.filters[e.target.dataset.i1].values[e.target.dataset.i2].id);
                _.throttle(this.$parent.getSelectedProd, 750)
            }
        }
    }
</script>

<style lang="sass" src="../../sass/sidebar.sass"></style>