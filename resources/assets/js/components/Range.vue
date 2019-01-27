<template>
    <div class="bar">
        <div class="filled"></div> 
        <div class="circle">
            <div :class="{'t': true,'circle-drag': isDraged == 1}"></div>
        </div> 
        <div class="circle">
            <div :class="{'t': true,'circle-drag': isDraged == 2}"></div>
        </div>
    </div>
</template>
<script>
    var self;
    export default {
        data: function () {return {isDraged: 0} },
        props: {
            value: {
                type: Object,
                default: null
            }
        },
        mounted() {
            self = this,
            this.init()
            this.$emit("load")
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
                    < 10 * percentPerArrayItem
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
                    self.moveTo(Math.abs(i.offsetX - circles[0].offsetLeft) < Math.abs(i.offsetX - circles[1].offsetLeft) ? circles[0] : circles[1],
                      i, bar, filled, circles, offset)
                };
                for (var i = 0; i < circles.length; i++){
                    let circle = circles[i];
                    let index = i
                    circles[i].ontouchmove = function (params) {
                        if(firstTouch) {
                            offset = circles[0].offsetLeft
                            firstTouch = false
                        }
                        self.isDraged = index+1
                        self.moveTo(this, { x: params.touches[0].clientX }, bar, filled, circles, offset)
                    }
                    circles[i].onmousedown = function(e) {
                        e = e || window.event;
                        e.preventDefault() 
                        self.isDraged = index+1
                        circle.onmousemove = function (move) {
                            self.moveTo(circle, move, bar, filled, circles, offset)
                        }
                    }
                    circle.ontouchend = function() {
                        self.isDraged = 0
                    }
                    circle.onmouseleave = circle.onmouseup = function() {
                        self.isDraged = 0
                        circle.onmousemove = null;
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
    transition: all 0.25s;
    margin: 1.5em 1.4em 1.2em;
    border-radius: 1em;
    background-color: whitesmoke;
    box-shadow: 0 0 0.2em;
}
.filled {
    height: 1rem;
    background-color:rgb(136, 184, 255);
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
}
.circle:hover {
    background-color:#f5f5f5
}
.t {
    transition: all 0.25s;
    position: absolute;
    top: -0.5em;
    left: -0.5em;
    border-radius: 50%;
    background: radial-gradient( #008cff34 50%,rgb(0, 101, 253));
    width: 3em;
    height: 3em;
    transform: scale(0);
    z-index: -1;
}
.circle-drag{
    transform: scale(1);
}
</style>