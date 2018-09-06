<template>
    <div class="bar">
        <div class="circle" style="left: 25px;"></div> 
        <div class="filled"></div> 
        <div class="circle" style="left: 214px;"></div>
    </div>
</template>
<script>
    var self;
    export default {
        props: {
            value: {
                type: Object,
                default: null
            }
        },
        computed: {
            arl: function() {
                return this.value.array.length
            }
        },
        watch: {
            arl: function() {
                this.value.range = [this.value.array[0], this.value.array[this.value.array.length - 1]]
            }
        },
        mounted() {
            self = this,
            this.init()
        },
        methods: {
            moveTo: function(t, e, n, i, a) {
                var o = n.offsetWidth / (self.value.array.length - 1);
                n.offsetLeft > e.pageX - t.offsetWidth || n.offsetLeft + n.offsetWidth < e.pageX - t.offsetWidth || t.offsetLeft != a[1].offsetLeft && e.pageX > parseInt(a[1].style.left) + t.offsetWidth || t.offsetLeft != a[0].offsetLeft && e.pageX < parseInt(a[0].style.left) + t.offsetWidth || (t.style.left = e.pageX - t.offsetWidth + "px",
                i.style.left = a[0].offsetWidth / 2 + a[0].offsetLeft + "px",
                i.style.width = a[1].offsetLeft - a[0].offsetLeft + "px",
                self.value.range = [self.value.array[Math.round((parseInt(a[0].style.left) - n.offsetLeft) / o)], self.value.array[Math.round((parseInt(a[1].style.left) - n.offsetLeft) / o)]],
                self.$emit("change"))
            },
            init: function() {
                var t = document.getElementsByClassName("circle")
                  , e = document.getElementsByClassName("filled")[0]
                  , n = document.getElementsByClassName("bar")[0];
                t[0].style.left = n.offsetLeft + "px";
                t[1].style.left = n.offsetLeft + n.offsetWidth + "px";
                this.value.range = [this.value.array[0], this.value.array[this.value.array.length - 1]];
                n.onclick = function(i) {
                    var a = null;
                    a = Math.abs(i.offsetX - t[0].offsetLeft) < Math.abs(i.offsetX - t[1].offsetLeft) ? t[0] : t[1],
                    self.moveTo(a, i, n, e, t)
                };
                for (var i = 0; i < t.length; i++)
                    t[i].onmousedown = function(i) {
                        document.onmousemove = (i) => {
                            self.moveTo(this, i, n, e, t)
                        },
                        document.onmouseup = function() {
                            document.onmouseup = null;
                            document.onmousemove = null;
                            // var t = n.onclick;
                            // n.onclick = function(e) {
                            //     n.onclick = t
                            // }
                        }
                    }
            }
        }
    }
</script>

<style>
.bar,.filled {
    position:relative
}
.bar {
    top: -1rem;
    margin: 3rem 2rem 0;
    background-color: #d3d3d3;
}
.bar:hover .filled { 
    background-color:#4169e1
}
.filled {
    height: 1rem;
    background-color:#6495ed;
    z-index: 2
}
.filled:hover {
    background-color:#4169e1
}
.circle {
    position:absolute;
    top:-1rem;
    margin-left:-4rem;
    width:3rem;
    height:3rem;
    border-radius:2rem;
    background-color:#fff;
    box-shadow:0 0 .6rem #a9a9a9;
    z-index:3}
.circle:hover {
    background-color:#f5f5f5
}
</style>