<template>
    <div class="container-fluid" v-if="item">
        <div class="overlay-background overlay-background-buy-modal"></div>
        <div class="dr-modal__dialog">
            <div style="background-color:cornflowerblue;color:white;padding:0.5em">{{item.name}}</div>
            <div class="thumbnail">
                <img :src="'file/'+item.img_src" style="max-height:10em;max-width:10em">
             </div>
            <div style="margin:1em">
                <span>{{lng.count}}:</span>
                <input class="form-control input-xs" v-model.number="count" type="number">
            </div>
            <div style="margin:1em">
                <button class="btn btn-primary btn-lg btn-block" @click="buy(1)">{{lng.to_order}}</button>
                <button class="btn btn-primary btn-lg btn-block" @click="buy">{{lng.addto_cart}}</button>      
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
                if(this.item){
                    var count = 0, cart = [];
                    if(!localStorage.cart||localStorage.cart.length<2) {
                        cart.push({id: this.item.id, count: this.count});
                        count = this.count;
                    }
                    else{
                        var array = JSON.parse(localStorage.cart);
                        array.push({id: this.item.id, count: this.count});
                        for (var index = 0; index < array.length; index++) {
                            if (!array[index]) continue;
                            var b = 0, temp = array[index].id;
                            for (var j = 0; j < array.length; j++) {
                                if (!array[j] || temp != array[j].id) continue;
                                if (++b > 1) array[j] = null;
                            }
                            cart.push({id: array[index].id, count: array[index].count + b - 1});
                            count += array[index].count + b - 1;
                        }
                    }
                    localStorage.cart = JSON.stringify(cart);
                    this.$store.commit('setCartLength', count);
                    this.count = 1;
                    this.item = null;
                    if(order==1) this.$router.push("/cart/[]");
                }
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
        border-bottom-left-radius: 0.8em;
        padding: 0 0.4em;
        color:cornflowerblue;
        background-color: white;
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
        border-radius: 0 0 1.8em 1.8em; 
        border:2px solid cornflowerblue;
        background-color: white;
        z-index:1001;
        margin-top: -10em;
        margin-left: -6em;
        left: 50%;
        top: 50%;
    }
</style>