<template>
    <div class="container-fluid compare">
        <!-- <div>
            <canvas id="cmprGraph"></canvas>
        </div> -->
        <div v-show="!list.length" style="text-align: center;padding: 12em;">
            Add products to compare
        </div>
        <table class="table" v-if="list.length>0" style="margin-top: 1rem;"> 
            <tbody style="white-space: nowrap;">
                <tr style="display:inline-block">
                    <td style="padding: 25px;width: 12em;height: 12rem;text-align:center;border: inherit">
                        <button class="btn btn-primary btn-sm" style="width: 100%;" @click="diffType?diffType=0:diffType=1">{{diffType == 1 ? '%' : lng.value}}</button>
                        <router-link :to="'/products/'+ctg(list[0].category_id)" style="display:block;margin-top:10px">Add more products to compare</router-link>
                    </td>
                    <td class="td_name" v-for="specs in list[0].specs" :key="specs.name" @mouseover="reGraph(specs.name);show_graph=true" @mouseleave="show_graph=false" style="width: 12em;float:left;clear:both">
                        <!-- <i class="fa fa-bar-chart"  aria-hidden="true"></i> -->
                        {{lng[specs.name]?lng[specs.name]:specs.name}}
                    </td>
                </tr>
                <tr class="table-item t-name" @mouseover="cmpr(i)" v-for="(temp,i) in list" :key="i">
                    <td style="float:left;clear:both;width:100%;height: 12rem;position:relative">
                        <div class="action-frm" style="top:0">
                            <a class="action-item fake-link" @click="removeItem(i)">
                                <span class="hidden-xs">{{lng.remove}}</span>
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>
                        </div>
                        <div class="thumbnail" style="margin:0;border: 0;">
                            <img v-bind:src="'file/'+temp.img_src" @error="img404($event.target)" style="max-height: 4em">
                        </div>
                        <router-link class="t-name" :to="{ name: 'detail', params: { id: temp.id }}">{{temp.name}}</router-link>
                        <star-rating :rating="+temp.rating" :star-size="16" :show-rating="false" :read-only="true"></star-rating>
                    </td>
                    <td v-for="specs in temp.specs" :key="specs.name" style="float:left;clear:both;width:100%;overflow: hidden;text-overflow: ellipsis;">{{specs.value}}
                        <span v-show="+specs.diff" :style="specs.diff < 0 ? 'color:red' : 'color:green'">
                            {{(specs.diff > 0 ? '(+' : '(') + specs.diff  + (diffType == 0 ? '%) ' : ') ')}}
                        </span>{{specs.val_type}}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
    var data ={
        lng: {},
        list:[],
        show_graph:false,
        diffType:0,
    };
    var chartData = {
        labels: [],
        datasets: [{
            data: [],
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255,99,132,1)'
        }]
    };
    var self, selfChart;
    export default {
        data: function() { return data },
        props: ['ids'],
        watch: {
            '$route.params.ids': function () {
                this.get_prodsby_ids()
            }
        },
        mounted(){
            self = this;
            this.lng = window.lng;
            this.get_prodsby_ids();
            // selfChart = new Chart(document.getElementById('cmprGraph'), {
            //     type: 'polarArea',
            //     data: chartData,
            //     options: {}//{ legend: { display: true} }
            // });
            // this.$forceUpdate();
        },
        methods: {
            ctg(value){
                for (const key in window.Laravel.catalog) {
                    if (window.Laravel.catalog[key].id == value) {
                        return key;
                    }
                }
            },
            removeItem(i){
                this.$store.commit('compare', this.list[i]);
                this.list.splice(i,1)
            },
            img404(e){
                e.src = "/images/404.png";
            },
            cmpr(x){
                for (var k = 0; k < this.list.length; k++) {
                    for (var j = 0; j < this.list[k].specs.length; j++){
                        for (var i = 0; i < this.list.length; i++){
                            if(i!=x && this.list[k].specs[j].isComparable == 1) {
                                this.list[i].specs[j].diff = (this.diffType == 0 ? 
                                    Math.round(this.list[i].specs[j].value/this.list[x].specs[j].value*100)-100 :
                                    this.list[i].specs[j].value-this.list[x].specs[j].value).toFixed(1);
                            } else this.list[i].specs[j].diff = 0;
                        }  
                    }
                }
                this.$forceUpdate();
            },
            reGraph(val) {
                return;
                for (var i = 0; i < this.list.length; i++) {
                    for (var j = 0; j < this.list[i].specs.length; j++){
                        if(this.list[i].specs[j].name == val)
                            chartData.datasets[0].data[i] = this.list[i].specs[j].value;
                    }
                } 
                chartData.datasets[0].label = val;
                selfChart.update();
            },
            get_prodsby_ids(){
                axios.get('/prodsby_ids', {params:{ids:JSON.parse(this.ids)}}).then(function (response) {
                    self.list = response.data;
                    for (var i = 0; i < self.list.length; i++) {
                        self.list[i].specs.unshift({
                            name: 'price',
                            value: self.list[i].price,
                            isComparable: true
                        })
                        chartData.labels.push(self.list[i].name); 
                    }
                });
            }
        }
    }
</script>
<style>
.compare {
    overflow: overlay
}
@media (max-width: 768px){
    .compare {
        padding: 0;
    }
}
.t-name {
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.table-item:hover {
    box-shadow: 0 0 0.5rem #0049ce;
    background-color: white;
}
.table-item td:first-child {
    border: inherit !important
}
.table-item {
    display:inline-block;
    transition: all 0.5s;
    width: 20rem;
}
</style>