<template>
    <div class="container-fluid">
        <div class="col-xs-12">
            <ul class="pagination fake-link" style="position:relative;left:50%;z-index:1">
                <li><a @click="mv_pg(-1)">&laquo;</a></li>
                <li v-show="currentPage!=1"><a @click="mv_pg(0)">1</a></li>
                <li><a @click="mv_pg(-2)" v-show="currentPage>2&&currentPage-2!=1&&currentPage<4">{{currentPage-2}}</a><a v-show="currentPage>3">...</a></li>
                <li v-show="currentPage>1&&currentPage-1!=1"><a @click="mv_pg(-1)">{{currentPage-1}}</a></li>
                <li class="active"><a>{{currentPage}}</a></li>
                <li v-show="currentPage+1<lastPage"><a @click="mv_pg(1)">{{currentPage+1}}</a></li>
                <!--<li v-show="currentPage+2<lastPage"><a>{{currentPage+2}}</a></li>-->
                <li><a a @click="mv_pg(2)" v-show="currentPage+2<lastPage">{{currentPage+2}}</a>
                    <a v-show="currentPage<lastPage-3">...</a></li>
                <li v-show="currentPage!=lastPage"><a @click="mv_pg(lastPage-1)">{{lastPage}}</a></li>
                <li><a @click="mv_pg(1)">&raquo;</a></li>
            </ul>
        </div>
    </div>
</template>
<script>
    var data = {
        showItems: 30
    };
    export default {
        data: function () {return data;},
        computed: {
            skipItems: function () { 
                return this.$store.state.skipItems
            },
            currentPage: function () {
                return this.skipItems/this.$data.showItems+1;
            },
            gotoPage: function () {
                return this.$store.state.gotoPage;
            },
            totalItems: function () {
                return this.$store.state.totalItems;
            },
            lastPage: function () {
                return Math.floor(this.totalItems/this.$data.showItems)+1;
            }
        },
        methods: {
            mv_pg(mov) {
                var skipItems = this.skipItems;
                if(mov == 0) skipItems = 0;
                else if(mov == this.lastPage) skipItems = this.itemsTotal - this.$data.showItems;
                else skipItems += mov * this.$data.showItems

                if(skipItems == this.skipItems || skipItems < 0 || skipItems >= this.itemsTotal) return;
                this.$store.commit('set_skipItems', skipItems);    
                window.scrollY=0;//window.scrollTo(0,0)
                this.gotoPage();  
            }
            // window.onscroll = function(){self.autoload()}; 
            // autoload(){
            //     //scrollTop
            //     if(window.scrollY+document.documentElement.clientHeight>=document.documentElement.offsetHeight-300){
            //         console.log('its srooll down');
            //         var skipItems = this.$store.state.skipItems;
            //         selfData.countItems*=2;
            //         if(skipItems>-1&&skipItems<=selfData.prodsTotal){
            //             this.$store.commit('set_scItems',{skip:skipItems,count:selfData.countItems});    
            //             this.getSelectedProd();
            //         }
            //     }
            // },
        }
    }
</script>