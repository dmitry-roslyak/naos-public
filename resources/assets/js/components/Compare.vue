<template>
    <div class="container-fluid">
        <hr>
        <div style="margin:0 15px 15px">
            <div class="product-labels" v-for="(prod,i) in list" :key="i">
                <router-link :to="{ name: 'detail', params: { id: prod.id }}">{{prod.name}}</router-link>
                <!-- <span @click="removeProd(i)">X</span> -->
            </div>
        </div>
        <div class="col-sm-3 col-xs-6">
            <div style="text-align:center">Price</div>
            <canvas id="graph-1"></canvas>
        </div>
        <div v-once v-for="(spec,i) in list[0].specs" :key="i" v-if="list.length" class="col-sm-3 col-xs-6">
            <div v-if="spec.isComparable">
                <div style="text-align:center">{{spec.name}}</div>
                <canvas :id="'graph'+i"></canvas>
            </div>
        </div>
    </div>
</template>
<script>
var data = {
    list: []
};
var self, selfData, selfProps, selfChart=[],promiseGraph;
export default {
    data: function () { return data },
    props: ['ids'],
    created() {
        self = this, selfData = this.$data, selfProps = this.$props;        
        this.get_prodsby_ids();
        promiseGraph.then(()=>{ self.initGraphs() })
    },
    methods: {
        removeProd(i){
            selfData.list.splice(i,1);    
            //selfChart[0].update();
        },
        initGraphs() {
            for (var i = -1; i < selfData.list[0].specs.length; i++) {
                if(selfData.list[0].specs[i] && !selfData.list[0].specs[i].isComparable) continue;
                var temp = {
                    labels: [],
                    datasets: [{
                        data: [],
                        backgroundColor: ['rgba(255, 99, 132, 0.2)','rgba(66, 95, 244,0.2)','rgba(255, 234, 50,0.2)','rgba(32, 201, 48,0.2)',
                            'rgba(30, 195, 255,0.2)','rgba(172, 30, 255,0.2)','rgba(255, 30, 161,0.2)',],
                        borderColor: ['rgba(255,99,132,1)','rgba(66, 95, 244,1)','rgba(255, 234, 50,1)','rgba(32, 201, 48,1)','rgba(30, 195, 255,1)',
                            'rgba(172, 30, 255,1)','rgba(255, 30, 161,1)',]
                    }]
                };
                for (var j = 0; j < selfData.list.length; j++) {
                    if(i==-1) temp.datasets[0].data.push((selfData.list[j].price * window.Laravel.currency.rate).toFixed(1));
                    else temp.datasets[0].data.push(selfData.list[j].specs[i].value)
                    temp.labels.push(selfData.list[j].name)
                    /// temp.label = val;
                }
                selfChart.push(new Chart(document.getElementById('graph' + i), {
                    type: 'polarArea',
                    data: temp,
                    options: {responsive: true,legend: { display: false},layout: {padding: 4},}
                }));
            }
        },
        get_prodsby_ids() {
            promiseGraph=axios.get('/prodsby_ids', { params: { ids: JSON.parse(selfProps.ids) } }).then(function (response) {
                selfData.list = response.data;
            }).catch(function (error) {
                self.$root.retry(self.get_prodsby_ids, error.response.status);
            });
        }
    }
}
</script>

<style lang="sass" src="../../sass/compare.sass"></style>
