<template>
    <div class="container-fluid" >
        <div class="btn-primary fake-link" tabindex="0" @blur="catalog_btn_toggle(0)" @focus="catalog_btn_toggle(1)" type="button" id="dropdownMenu1" aria-haspopup="true">
            <div style="padding: 9px 10px 6px">
                <i class="fa fa-list"></i>
                {{lng.catalog}}
            </div>
            <ul class="ctg-frm" aria-labelledby="dropdownMenu1">
                <div class="btn-default fake-link" v-for="(item, name) in catalog" @click="get_filters(name, item.id)" :key="item.id">
                    {{lng[name]?lng[name]:name}}
                </div>
            </ul>
        </div>
        <div style="font-weight:bold;padding: 1rem">
            <i class="fa fa-filter"></i>
            {{lng.filters}}
            <span class="flt-btn fake-link" style="font-weight: 500;padding: 0 1rem" v-show="showClear" @click="flt_reset()">
                {{lng.filtersReset}}
                <i class="fa fa-times"></i>
            </span>
        </div>

        <div class="thumbnail flt-grp">
            <div class="flt-btn fake-link" @click="expand($event.currentTarget)">
                {{lng.price}}
                <i class="fa fa-angle-up font1 pull-right" style="display:none" aria-hidden="true"></i>
                <i class="fa fa-angle-down font1 pull-right" aria-hidden="true"></i>
            </div>
            <div class="flip">
                {{lng.from}}<div class="input-group"><input type="number" class="form-control myinput1" v-model.number="price.range[0]"><span class="input-group-addon">{{lng.currency}}</span></div>
                {{lng.to}}<div class="input-group"><input type="number" class="form-control myinput1" v-model.number="price.range[1]"><span class="input-group-addon">{{lng.currency}}</span></div> 
                <range v-model="vRangeSlidersPosition" ref="range" style="margin-top:8px" @change="rangeReset()"></range>
            </div>
        </div>
        <div class="thumbnail flt-grp" v-for="(filter,i1) in filters" :key="filter.id">
            <div class="flt-btn fake-link" @click="expand($event.currentTarget)">
                <!-- <span :title="filter.desc"><i class="fa fa-info-circle"></i></span> -->
                {{lng[filter.name]?lng[filter.name]:filter.name}}
                <i class="fa fa-angle-down pull-right" style="display:none" aria-hidden="true"></i>
                <i class="fa fa-angle-up pull-right" aria-hidden="true"></i>
            </div>
            <div class="flip">
                <div class="checkbox" v-for="(value,i2) in filter.values" :key="value.id">
                    <label><input type="checkbox" :data-id="value.id" :data-i1="i1" :data-i2="i2"  @click="toFilter($event)">
                        {{value.value}}
                        <!-- {{value.value+' ('+value.count+')'}} -->
                    </label>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    const range = require('./VRange.vue')
    // import range from './VRange.vue'
    var data = {
        catalog: [],
        filters: [],
        vRangeSlidersPosition: [0, 100],
        price: {}
    };
    export default {
        components: {
            range
        },
        data: function () {return data;},
        computed: {
            lng(){ return this.$root.lng },
            showClear: function () { return this.$store.state.flt_ids.length },
        },
        created() {
            this.catalog = window.Laravel.catalog;
            this.price = this.$parent.price;
            if(window.Laravel.catalog[this.$parent.category]) 
                this.get_filters(this.$parent.category,window.Laravel.catalog[this.$parent.category].id);
        },
        methods: {
            rangeReset(){
                if(!this.price.array.length) return;

                var percentPerArrayItem = 100 / (this.price.array.length - 1),
                    priceFrom = this.price.array[Math.round(this.vRangeSlidersPosition[0] / percentPerArrayItem)],
                    priceTo = this.price.array[Math.round(this.vRangeSlidersPosition[1] / percentPerArrayItem)];
                
                this.price.range = [ (priceFrom * this.$store.state.currency).toFixed(1) , (priceTo * this.$store.state.currency).toFixed(1) ];
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
                var checkList = document.getElementsByClassName('checkbox');
                for (var i = 0; i < checkList.length; i++) {
                    checkList[i].firstChild.firstChild.checked = false;
                }
                this.price.range = [null, null];
                this.$store.commit('filterReset')
            },
            get_filters(name, id) {
                this.$router.push('/products/'+name);
                axios.get('/get_filters?id='+id).then((response) => {
                    this.filters = response.data;
                })  
                this.flt_reset();
            },
            toFilter(e){
                this.$store.commit('filter', this.filters[e.target.dataset.i1].values[e.target.dataset.i2].id);
            }
        }
    }
</script>

<style lang="scss" src="../../sass/AppProductsFilters.scss"></style>