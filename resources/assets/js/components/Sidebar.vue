<template>
    <div class="container-fluid">
        <div style="padding:0 4px 0"> 
            <div class="ctg-btn fake-link" tabindex="0" @blur="catalog_btn_toggle(0)" @click="catalog_btn_toggle(1)"  type="button" id="dropdownMenu1" aria-haspopup="true">
                <i class="fa fa-list" style="font-size:1.2em"></i>
                {{lng.catalog}}
            </div>
            <ul class="ctg-frm" aria-labelledby="dropdownMenu1">
                <div class="ctg-itm fake-link" v-for="item in catalog" @click="get_filters(item.id)">
                    {{lng[item.name]?lng[item.name]:item.name}}
                </div>
            </ul>
        </div>
        <div style="padding:4px">
            <button class="form-control" v-show="show_clear" @click="flt_reset">{{lng.flt_reset}}</button>
            <div class="thumbnail flt-grp" v-if="filters.length" >
                <div class="flt-btn fake-link" @click="price_show?price_show=false:price_show=true;expand($event.currentTarget)">
                    {{lng.price}}
                    <i v-show="price_show" class="fa fa-angle-up font1 pull-right" aria-hidden="true"></i>
                    <i v-show="!price_show" class="fa fa-angle-down font1 pull-right" aria-hidden="true"></i>
                </div>
                <div class="flip" style="margin-top:6px;margin-bottom:6px">
                    {{lng.from}}<input type="number" v-model="pricef" class="form-control myinput1">
                    {{lng.to}}<input type="number" v-model="pricel" class="form-control myinput1">
                </div>
            </div>
            <div class="thumbnail flt-grp" v-for="(filter,i1) in filters">
                <div class="flt-btn fake-link" @click="expand($event.currentTarget)">
                    <span :title="filter.desc"><i class="fa fa-info-circle"></i></span>
                    {{lng[filter.name]?lng[filter.name]:filter.name}}
                    <i class="fa fa-angle-down font1 pull-right" style="display:none" aria-hidden="true"></i>
                    <i class="fa fa-angle-up font1 pull-right" aria-hidden="true"></i>
                </div>
                <div class="flip">
                    <div class="row" v-for="(value,i2) in filter.values" >
                        <div class="col-xs-2"></div>
                        <div class="col-xs-10">
                            <div class="checkbox" style="margin-top:2px;margin-bottom:2px">
                                <label><input style="height:1em" :data-id="value.id" type="checkbox" @click="toFilter()">
                                    {{value.value+' ('+value.count+')'}}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    var data={
        lng: {},
        catalog:[],
        filters:[],
        fltOrigin: null,
        flts: [],
        price_show:true,
        pricef:1,
        pricel:1000000,
        show_clear:0
    };
    var self, selfData;
    export default {
        data: function () {return data;},
        mounted() {
            self = this;
            selfData = this.$data;
            selfData.catalog = window.Laravel.catalog;
            selfData.lng = window.lng;
            this.get_filters(this.$store.state.ctg_id);
        },
        methods: {
            expand(el){
                $(el.parentElement.getElementsByClassName('flip')[0]).slideToggle();
                $(el.getElementsByClassName('fa-angle-up')[0]).toggle();
                $(el.getElementsByClassName('fa-angle-down')[0]).toggle();
            },
            catalog_btn_toggle(i){
                i?$(".ctg-frm").slideDown():$(".ctg-frm").slideUp();
            },
            flt_reset(){
                var checkList = document.getElementsByClassName('checkbox');
                for (var i = 0; i < checkList.length; i++) {
                    checkList[i].firstChild.firstChild.checked = false;
                    selfData.show_clear = 0;
                }
                this.toFilter();
            },
            get_filters(id) {
                axios.get('/get_filters?id='+id).then(function (response) {
                    selfData.filters = response.data;
                    for (var i = 0; i < selfData.filters.length; i++) {
                        for (var j = 0; j < selfData.filters[i].values.length; j++) {
                            selfData.filters[i].values[j].count = selfData.filters[i].values[j].prod_ids.length;
                        }
                    }
                    self.$store.commit('set_ctg_id', id);
                    self.flt_reset();
                }).catch(function (error) {
                    self.$root.retry(self.get_filters, error.response.status);
                });                  
            },
            toFilter() {
                var flt_ids=[],checkList = document.getElementsByClassName('checkbox');
                selfData.show_clear = 0;

                for (var i = 0; i < checkList.length; i++) {
                    if(checkList[i].firstChild.firstChild.checked){
                        selfData.show_clear++;
                        flt_ids.push(checkList[i].firstChild.firstChild.dataset.id);
                    }
                }
                if(flt_ids.length > 0 && flt_ids.length < 2){
                    for (var i = 0; i < selfData.filters.length; i++) {
                        for (var j = 0; j < selfData.filters[i].values.length; j++) {
                            if(selfData.filters[i].values[j].id == flt_ids[0]){
                                selfData.fltOrigin = {
                                    id: selfData.filters[i].id,
                                    prod_ids: selfData.filters[i].values[j].prod_ids
                                };
                            }
                        }
                    }
                }
                selfData.flts.length = 0;
                if(selfData.fltOrigin){
                    for (var i = 0; i < selfData.filters.length; i++) {
                        for (var j = 0; j < selfData.filters[i].values.length; j++) {
                            
                            if(selfData.filters[i].id == selfData.fltOrigin.id){
                                for (var z = 0; z < flt_ids.length; z++) {
                                    
                                    if(flt_ids[z] == selfData.filters[i].values[j].id){
                                        selfData.flts.push({
                                            id: selfData.filters[i].id,
                                            prod_ids: selfData.filters[i].values[j].prod_ids
                                        });
                                    }
                                }
                            }
                        }
                    }
                    
                    for (var i = 0; i < selfData.filters.length; i++) {
                        for (var j = 0; j < selfData.filters[i].values.length; j++) {
                            if(selfData.fltOrigin.id == selfData.filters[i].id){
                                selfData.filters[i].values[j].count = selfData.filters[i].values[j].prod_ids.length;
                            }
                            else{
                                var count = 0;
                                for (var h = 0; h < selfData.flts.length; h++) {
                                    for (var k = 0; k < selfData.filters[i].values[j].prod_ids.length; k++) {
                                        for (var z = 0; z < selfData.flts[h].prod_ids.length; z++) {
                                            if(selfData.filters[i].values[j].prod_ids[k].product_id == selfData.flts[h].prod_ids[z].product_id){
                                                count++;
                                            }
                                        }
                                    }
                                }
                                selfData.filters[i].values[j].count = count;
                            }
                        }
                    }
                }
                if(flt_ids.length < 1 ){
                    for (var i = 0; i < selfData.filters.length; i++) {
                        for (var j = 0; j < selfData.filters[i].values.length; j++) {
                            selfData.filters[i].values[j].count = selfData.filters[i].values[j].prod_ids.length;
                        }
                    }
                    selfData.flts.length = 0;
                }
                self.$store.commit('set_filter_params',{
                    flt_ids:flt_ids,
                    pricef:selfData.pricef,
                    pricel:selfData.pricel
                });
                this.$parent.getSelectedProd();
            }
        }
    }
</script>

<style lang="sass" src="../../sass/sidebar.sass"></style>