<template>
    <div>
        <ul class="pagination fake-link" style="position:relative;left:50%;z-index:1;transform: translateX(-50%);">
            <li :class="{'disabled': currentPage == 1}"><a @click="currentPage > 1 && page(currentPage-1)">&laquo;</a></li>
            <li v-show="currentPage!=1"><a @click="page(1)">1</a></li>
            <li>
                <a v-show="currentPage>2&&currentPage-2!=1&&currentPage<4" @click="page(currentPage-2)">{{currentPage-2}}</a>
                <a v-show="currentPage>3">...</a>
            </li>
            <li v-show="currentPage>1&&currentPage-1!=1"><a @click="page(currentPage-1)">{{currentPage-1}}</a></li>
            <li class="active"><a>{{currentPage}}</a></li>
            <li v-show="currentPage+1<pageCount"><a @click="page(currentPage+1)">{{currentPage+1}}</a></li>
            <!--<li v-show="currentPage+2<pageCount"><a>{{currentPage+2}}</a></li>-->
            <li>
                <a a @click="page(currentPage+2)" v-show="currentPage+2<pageCount">{{currentPage+2}}</a>
                <a v-show="currentPage<pageCount-3">...</a>
            </li>
            <li v-show="currentPage!=pageCount"><a @click="page(pageCount)">{{pageCount}}</a></li>
            <li :class="{'disabled': currentPage == pageCount}"><a @click="currentPage < pageCount && page(currentPage+1)">&raquo;</a></li>
        </ul>
    </div>
</template>
<script>
    export default {
        data: function () {
            return {
                currentPage: 1
            };
        },
        props: { 
            value: Object
        },
        computed: {
            pageCount() { return Math.ceil(this.value.total/this.value.take) || 1}
        },
        methods: {
            skip(){
                this.value.skip = (this.currentPage-1)*this.value.take;
                this.value.func();
                window.scrollTo(0,0)
                // window.scrollY = 0;
            },
            page(i){
                this.currentPage = i;
                this.skip()
            },
        }
    }
</script>