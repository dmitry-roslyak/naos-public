<template>
    <div class="container-fluid">
         <search></search><hr>
         <div class="container" style="position:absolute;width:8em;text-align:center">
            <div style="margin-bottom:6px">Сравнить</div>
            <button v-if="diffType==1" class="btn btn-primary btn-sm" style="width:100%" @click="diffType=0">Проценты</button>
            <button v-else class="btn btn-primary btn-sm" style="width:100%" @click="diffType=1">Значения</button>
        </div>
        <buy-modal ref="buyModal"></buy-modal>
        <div class="row" style="margin:0 20px"> 
            <div v-show="show_graph" class="container fds">
                <canvas id="cmprGraph"></canvas>
            </div>
            <div class="col-xs-4 col-md-2" style="top:13.18em;padding-right:0">
                <table class="table" v-if="list.length>0"> 
                    <tbody>
                        <tr>
                            <td class="td_name" @mouseover="reGraph('price');show_graph=true" @mouseleave="show_graph=false" style="width:1em">
                                <i class="fa fa-bar-chart"  aria-hidden="true"></i>
                                <!--<span class="glyphicon glyphicon-stats"  aria-hidden="true"></span>-->
                            </td>
                            <td>Price</td>
                        </tr>
                        <tr v-for="specs in list[0].specs">
                            <td class="td_name" @mouseover="reGraph(specs.name);show_graph=true" @mouseleave="show_graph=false" style="width:1em">
                                <i class="fa fa-bar-chart"  aria-hidden="true"></i>
                            </td>
                            <td>{{specs.name}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-8 col-md-10" style="padding: 0">
                <a class="mv-item fake-link" style="left:20px" @click="mv_pg(-1)">&laquo;</a>
                <a class="mv-item fake-link" style="right:20px" @click="mv_pg(1)">&raquo;</a>
                <table class="table" @mouseover="cmpr(i1)" v-if="i1>=firstItem&&i1<=lastItem" v-for="(temp,i1) in list" style="display:inline">
                    <tbody>
                        <tr>
                            <td style="width:12em">
                                <div class="thumbnail" style="margin:0">
                                    <img v-bind:src="'file/'+temp.img_src" style="max-height: 4em">
                                </div>
                                <router-link to="">{{temp.name}}</router-link>
                                <div>
                                    <star-rating :rating="temp.rating" :star-size="16" :show-rating="false" :read-only="true"></star-rating>
                                    <router-link to="/coms">{{temp.vote_count}} Reviews</router-link>
                                </div>
                                <button class="btn btn-primary btn-md" style="width:100%" @click="curItem(temp)">
                                    <div class="pull-left">
                                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                        <!--<span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>-->
                                    </div>
                                    <span v-if="temp.available < 1">&nbsp;&nbsp;Not available</span>
                                    <span v-if="temp.available > 0">&nbsp;&nbsp;{{temp.price}}</span>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0">{{temp.price}}
                                <span v-show="temp.priceDiff">
                                    <span v-if="temp.priceDiff<0" style="color:red">{{'('+temp.priceDiff}}</span>
                                    <span v-else style="color:green">(+{{temp.priceDiff}}</span>
                                    <span v-if="diffType==0">%)</span>
                                    <span v-if="diffType==1">)</span>
                                </span>Грн
                            </td>
                        </tr>
                        <tr v-for="specs in temp.specs">
                            <td style="padding: 8px 0">{{specs.value}}&nbsp;
                                <span v-show="specs.diff">
                                    <span v-if="specs.diff<0" style="color:red">{{'('+specs.diff}}</span>
                                    <span v-else style="color:green">(+{{specs.diff}}</span>
                                    <span v-if="diffType==0">%)</span>
                                    <span v-if="diffType==1">)</span>
                                </span>{{specs.val_type}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
    var data ={
        list:[],
        firstItem:0,
        lastItem:5,
        runOnce:true,
        show_graph:false,
        diffType:0,
        elMax:0
    };
    var chartData = {
        labels: [],
        datasets: [{
            data: [],
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255,99,132,1)'
        }]
    };
    var self,selfData,selfProps,selfChart;
    export default {
        data: function() { return data },
        props: ['ids'],
        mounted(){
            self=this,selfData=this.$data,selfProps=this.$props;
            selfChart = new Chart(document.getElementById('cmprGraph'), {
                type: 'polarArea',
                data: chartData,
                options: {}//{ legend: { display: true} }
            });
            this.get_prodsby_ids();
            this.$forceUpdate();
        },
        updated(){
            if(this.$data.runOnce){
                if(document.documentElement.clientWidth>1320) this.$data.lastItem = 5;
                else if(document.documentElement.clientWidth>=1100&&document.documentElement.clientWidth<1320)
                    this.$data.lastItem = 4; 
                else if(document.documentElement.clientWidth>=990&&document.documentElement.clientWidth<1100) 
                    this.$data.lastItem = 3; 
                else if(document.documentElement.clientWidth>=768&&document.documentElement.clientWidth<1100) this.$data.lastItem = 2; 
                else if(document.documentElement.clientWidth<768) this.$data.lastItem = 2; 
                this.$data.runOnce=false;
            }
        },
        methods: {
            curItem(item){
                this.$refs.buyModal.$data.item = item;
            },
            mv_pg(i){
                if(i>0){
                    if(selfData.lastItem<selfData.list.length-1){
                        selfData.firstItem+=i; selfData.lastItem+=i;
                    }
                }else {
                    if(selfData.firstItem>0){
                        selfData.firstItem+=i; selfData.lastItem+=i;
                    }
                }
            },
            cmpr(x){
                var j,i = 0, l = selfData.list.length;
                for (var k = 0; k < l; k++) {
                    var l2 = selfData.list[k].specs.length;
                    if(i!=x){
                        if(selfData.diffType==0)
                        selfData.list[k].priceDiff=Math.round(
                            selfData.list[k].price/selfData.list[x].price*100)-100;
                        else selfData.list[k].priceDiff=
                            (selfData.list[k].price-selfData.list[x].price).toFixed(1);
                        
                    }else selfData.list[i].priceDiff = 0;
                    for (j = 0; j < l2; j++){
                        for (i = 0; i < l; i++){
                            if(i!=x){
                                if(selfData.diffType==0)
                                selfData.list[i].specs[j].diff=Math.round(
                                    selfData.list[i].specs[j].value/selfData.list[x].specs[j].value*100)-100;
                                else selfData.list[i].specs[j].diff=
                                    selfData.list[i].specs[j].value-selfData.list[x].specs[j].value;
                                
                            }else selfData.list[i].specs[j].diff = 0;
                        }  
                    }
                }
                this.$forceUpdate();
            },
            reGraph(val){
                for (var i = 0; i < selfData.list.length; i++) {
                    for (var j = 0; j < selfData.list[i].specs.length; j++){
                        if(selfData.list[i].specs[j].name==val)
                            chartData.datasets[0].data[i] = selfData.list[i].specs[j].value;
                        else if(val=='price')
                            chartData.datasets[0].data[i] = selfData.list[i].price;
                    }
                } 
                chartData.datasets[0].label = val;
                selfChart.update();
            },
            get_prodsby_ids(n){
                axios.get('/prodsby_ids', {params:{ids:JSON.parse(selfProps.ids)}}).then(function (response) {
                    selfData.list = response.data;
                    for (var i = 0; i < selfData.list.length; i++) {
                        chartData.labels.push(selfData.list[i].name); 
                        chartData.datasets[0].data.push(selfData.list[i].price);
                    }
                    chartData.datasets[0].label = 'price';
                }).catch(function (error) {
                    self.$root.retry(n,self.get_prodsby_ids);
                });
            }
        }
    }
</script>
<style>
.fds{
    transition: all 0.4s;
    width:30em;
    height:25em;
    position:absolute;
    margin: -15em 0 0 -12.5em;
    z-index:4;
    position: fixed;
    /*transform: translate(60%, 45%);*/
    left:45%;
    top:60%;
    background-color:white;
    border-radius:8px;
    /*color:white;*/
    box-shadow: 0 0 1em blue;
}
.td_name{
    transition: all 0.3s;
}
.mv-item{
    z-index:4;
    top:50%;
    position: fixed;
    padding: 4px 8px;
    border-radius:8px;
    color:white;
    background-color: cornflowerblue;
}
.mv-item:hover{
    color:white;
    background-color: royalblue;
}

.td_name:hover{
    color: white;
    background-color: cornflowerblue;
}
</style>
