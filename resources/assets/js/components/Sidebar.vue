<template>
    <div class="container-fluid">
        <div class="ctg-btn fake-link" tabindex="0" @blur="catalog_btn_toggle(0)" @click="catalog_btn_toggle(1)"  type="button" id="dropdownMenu1" aria-haspopup="true">
            <i class="fa fa-list" style="font-size:1.2em"></i>
            {{lng.catalog}}
        </div>
        <ul class="ctg-frm" aria-labelledby="dropdownMenu1">
            <div class="ctg-itm fake-link" v-for="item in catalog" @click="get_filters(item.id)" :key="item.id">
                {{lng[item.name]?lng[item.name]:item.name}}
            </div>
        </ul>
        <button class="form-control" v-show="show_clear" @click="flt_reset">{{lng.flt_reset}}</button>
        <div class="thumbnail flt-grp" v-if="filters.length" >
            <div class="flt-btn fake-link" @click="price_show?price_show=false:price_show=true;expand($event.currentTarget)">
                {{lng.price}}
                <i v-show="price_show" class="fa fa-angle-up font1 pull-right" aria-hidden="true"></i>
                <i v-show="!price_show" class="fa fa-angle-down font1 pull-right" aria-hidden="true"></i>
            </div>
            <div class="flip" style="margin: 6px 0px;">
                {{lng.from}}<div class="input-group"><input type="number" class="form-control myinput1" v-model="price.range[0]"><span class="input-group-addon">₽</span></div>
                {{lng.to}}<div class="input-group"><input type="number" class="form-control myinput1" v-model="price.range[1]"><span class="input-group-addon">₽</span></div> 
                <range v-model="price" @change="priceRangeChange"></range>
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
                            <label><input style="height:1em" :data-id="value.id" :data-i1="i1" :data-i2="i2" type="checkbox" @click="toFilter()">
                                {{value.value+' ('+value.count+')'}}
                            </label>
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
        // fltOrigin: null,
        // flts: [],
        price: {},
        price_show:true,
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
            this.price = this.$parent.price,
            this.get_filters(this.$store.state.ctg_id);
        },
        methods: {
            // priceRange: function(t, e) {
            //     r.price.range[e] = (t.currentTarget.value / this.currency).toFixed(2),
            //     this.$root.throttle(this.$parent.getSelectedProd, 750)
            // },
            priceRangeChange() {
                _.throttle(this.$parent.getSelectedProd, 750)
            },
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
                    self.$store.commit('set_ctg_id', id);
                    self.flt_reset();
                }).catch(function (error) {
                    self.$root.retry(self.get_filters, error.response.status);
                });                  
            },
            i: function (t) {
                if (Array.isArray(t)) {
                    for (var e = 0, i = Array(t.length); e < t.length; e++)
                        i[e] = t[e];
                    return i
                }
                return Array.from(t)
            },
            intersect2: function(t, e) {
                for (var n = [], r = 0, a = 0; r < e.length; r++)
                    if (t != e[r].filterID)
                        for (var s = [], l = r; l < e.length; l++) {
                            if (e[r].filterID != e[l].filterID) {
                                r = l - 1,
                                n.push(s);
                                break
                            }
                            s.push.apply(s, self.i(e[l].itemIDS)),
                            e.length == l + 1 && (r = l, n.push(s))
                        }
                    else
                        a++;
                if (a == e.length || !n.length)
                    return [];
                for (var o = [], c = 0, a = 0; c < n[0].length; c++) {
                    for (var r = 1; r < n.length; r++)
                        for (var l = 0; l < n[r].length; l++)
                            if (n[0][c].product_id == n[r][l].product_id) {
                                a++;
                                break
                            }
                    n.length - 1 == a && o.push(n[0][c].product_id)
                }
                return o
            },
            toFilter: function() {
                for (var t = [], e = [], i = document.getElementsByClassName("checkbox"), a = 0; a < i.length; a++)
                    if (i[a].firstChild.firstChild.checked) {
                        var s = i[a].firstChild.firstChild.dataset.i1
                            , l = i[a].firstChild.firstChild.dataset.i2;
                        e.push(self.filters[s].values[l].id),
                        t.push({
                            filterID: self.filters[s].id,
                            itemIDS: self.filters[s].values[l].prod_ids
                        })
                    }
                self.show_clear = e.length;
                for (var a = 0; a < self.filters.length; a++)
                    for (var o = 0; o < self.filters[a].values.length; o++) {
                        var c = 0
                            , f = this.intersect2(self.filters[a].id, t);
                        if (f.length) {
                            for (var u = 0; u < self.filters[a].values[o].prod_ids.length; u++)
                                for (var d = 0; d < f.length; d++)
                                    f[d] == self.filters[a].values[o].prod_ids[u].product_id && c++;
                            self.filters[a].values[o].count = c
                        } else
                            self.filters[a].values[o].count = self.filters[a].values[o].prod_ids.length
                    }
                self.$store.commit('set_filter_params', { flt_ids: e });
                window._.throttle(this.$parent.getSelectedProd, 750)
            }
        }
    }
</script>

<style lang="sass" src="../../sass/sidebar.sass"></style>