<template>
    <div class="bar">
        <div class="circle"></div> 
        <div class="filled"></div> 
        <div class="circle"></div>
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
            moveTo: function(circle, e, bar, filled, circles, offset) {
                var pxPerPercent = bar.offsetWidth / 100,
                    step = (e.x - bar.offsetLeft + offset ) / pxPerPercent,
                    percentPerArrayItem = 100 / (self.value.array.length - 1);

                if(
                    (parseInt(circle.style.left) < parseInt(circles[1].style.left) 
                    ? parseInt(circles[1].style.left) - step 
                    :  step - parseInt(circles[0].style.left))
                    < 3 * percentPerArrayItem
                ) return;
                if(step < 0)
                    circle.style.left = '0%'
                else if (step > 100)
                    circle.style.left = '100%'
                else
                    circle.style.left = step + '%'

                filled.style.left =  circles[0].style.left
                filled.style.width =  parseInt(circles[1].style.left) - parseInt(circles[0].style.left) + '%'

                this.value.range = [
                    this.value.array [Math.round((parseInt(circles[0].style.left)) / percentPerArrayItem )], 
                    this.value.array [Math.round((parseInt(circles[1].style.left)) / percentPerArrayItem )]
                ];
                this.$emit("change")
            },
            init: function() {
                var circles = document.getElementsByClassName("circle")
                    , filled = document.getElementsByClassName("filled")[0]
                    , bar = document.getElementsByClassName("bar")[0]
                    , offset = circles[0].offsetLeft
                    , firstTouch = true

                circles[0].style.left = "0%";
                circles[1].style.left = '100%';
                
                this.value.range = [this.value.array[0], this.value.array[this.value.array.length - 1]];
                bar.onclick = function(i) {
                    // if(!isMousemove) return;
                    self.moveTo(Math.abs(i.offsetX - circles[0].offsetLeft) < Math.abs(i.offsetX - circles[1].offsetLeft) ? circles[0] : circles[1],
                      i, bar, filled, circles, offset)
                };
                for (var i = 0; i < circles.length; i++){
                    let circle = circles[i];
                    
                    circles[i].ontouchmove = function (params) {
                        if(firstTouch) {
                            offset = circles[0].offsetLeft
                            firstTouch = false
                        }
                        var obj = { x: params.touches[0].clientX }
                        self.moveTo(this, obj, bar, filled, circles, offset)
                    }
                    circles[i].onmousedown = function(e) {
                        circle.onmousemove = function (move) {
                            self.moveTo(circle, move, bar, filled, circles, offset)
                        },
                        circle.onmouseleave = circle.onmouseup = function() {
                            circle.onmousemove = null;
                        }
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
    margin-left:-1em;
    width: 2em;
    height: 2em;
    border-radius:2rem;
    background-color:#fff;
    box-shadow:0 0 .6rem #a9a9a9;
    z-index:3}
.circle:hover {
    background-color:#f5f5f5
}
</style>