<template>
    <div class="container-fluid" v-if="item">
        <div class="overlay-background overlay-background-buy-modal"></div>
        <div class="dr-modal__dialog">
            <div class="thumbnail" style="margin:0">
                <img :src="'file/'+item.img_src" style="max-height:10em;max-width:10em">
             </div>
            <div style="background-color:cornflowerblue;color:white;padding:0.5em">{{item.name}}</div>
            <div style="margin:1em">
                <span>{{lng.count}}:</span>
                <input class="form-control input-xs" v-model.number="count" type="number">
            </div>
            <div style="margin:1em">
                <button class="btn btn-primary btn-block" @click="buy(true)">{{lng.to_order}}</button>
                <button class="btn btn-primary btn-block" @click="buy">{{lng.addto_cart}}</button>      
            </div>
            <div class="fake-link dr-modal__close" @click="count=1;item=null">X</div>
        </div>
    </div>
</template>
<script>
    var data = {
        lng: {},
        item: null,
        count: 1
    };
    export default {
        data: function () {return data;},
        mounted() {
            this.lng = window.lng;
        },
        methods: {
            buy(order) {
                this.$store.commit('cart', {id: this.item.id, count: this.count});
                this.count = 1;
                this.item = null;
                if(order) this.$router.push("/cart/[]");
            }
        }
    }
</script>
<style>
    .overlay-background-buy-modal{
        background-color: gray;
        opacity: 0.5;
    }
    .dr-modal__close{
        position: absolute;
        border-bottom-left-radius: 0.6em;
        padding: 0 0.4em;
        color:white;
        background-color: cornflowerblue;
        top: 0;
        right: 0;
        font-size:1.2em;
        opacity: 1;
        z-index:1002;
    }
    .dr-modal__close:hover{
        color:white;
        background-color: tomato;
    }
    .dr-modal__dialog{ 
        position: fixed;       
        border-radius: 0.6em; 
        /* border:2px solid cornflowerblue; */
        background-color: white;
        z-index:1001;
        transform: translate(-50%,-50%);
        left: 50%;
        top: 50%;
    }
</style>