<template>
    <div class="range">
        <div class="bar">
            <div class="filled"></div> 
            <div class="circle">
                <div :class="{'t': true,'circle-drag': isDraged == 1}"></div>
            </div> 
            <div class="circle">
                <div :class="{'t': true,'circle-drag': isDraged == 2}"></div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            value: {
                type: Array,
                default: function () {
                    return [0, 100]
                },
                validator: function (array) {
                    return array.length == 2 && array[0] >= 0 && array[1] <= 100;
                }
            }
        },
        data: function () {return {isDraged: 0} },
        mounted() {
            var self = this;
            var range = this.$el
                , circles = range.getElementsByClassName("circle")
                , filled = range.getElementsByClassName("filled")[0]
                , bar = range.getElementsByClassName("bar")[0]
                , offset = circles[0].offsetLeft
                , firstTouch = true
                , pxPerPercent = bar.offsetWidth / 100
                , minWidthBetweenCirclesPercents = circles[0].offsetWidth / pxPerPercent;

            bar.onclick = function(e) {
                var i = Math.abs(e.offsetX - circles[0].offsetLeft) < Math.abs(e.offsetX - circles[1].offsetLeft) ? 0 : 1;
                var step = (e.x - bar.offsetLeft + offset ) / pxPerPercent;

                moveTo(i, step);
            };
            for (let i = 0; i < circles.length; i++){
                circles[i].ontouchmove = function (params) {
                    if(firstTouch) {
                        offset = circles[0].offsetLeft;
                        firstTouch = false;
                        pxPerPercent = bar.offsetWidth / 100;
                        minWidthBetweenCirclesPercents = circles[0].offsetWidth / pxPerPercent;
                    }
                    self.isDraged = i + 1
                    var step = (params.touches[0].clientX - bar.offsetLeft + offset ) / pxPerPercent;
                    moveTo(i, step);
                }
                circles[i].ontouchend = function() {
                    self.isDraged = 0
                }
                circles[i].onmousedown = function(e) {
                    e = e || window.event;
                    e.preventDefault() 
                    self.isDraged = i + 1
                    range.onmousemove = function (move) {
                        var step = (move.x - bar.offsetLeft + offset ) / pxPerPercent;

                        moveTo(i, step);
                    }
                }
            }
            range.onmouseleave = range.onmouseup = function() {
                self.isDraged = 0
                range.onmousemove = null;
            }
            
            filled.style.left = circles[0].style.left = this.value[0] + "%";
            filled.style.width = circles[1].style.left = this.value[1] + "%" ;

            this.$emit("ready")

            function moveTo(index, step) {
                var betweenCircles = index ? step - self.value[0] : self.value[1] - step;
                
                if(betweenCircles < minWidthBetweenCirclesPercents) return;

                if(step < 0)
                    step = 0;
                else if (step > 100)
                    step = 100;

                self.value[index] = step;
                circles[index].style.left = step + '%';

                filled.style.left = circles[0].style.left;
                filled.style.width = self.value[1] - self.value[0] + '%';

                self.$emit("change")
            }
        }
    }
</script>

<style scoped>
.range {
    padding: 1px 0;
}
.bar,.filled {
    position:relative
}
.bar {
    transition: all 0.25s;
    margin: 1.2em 1.1em;
    border-radius: 0.3em;
    background-color: whitesmoke;
    box-shadow: 0 0 0.2em;
}
.filled {
    height: 1rem;
    background-color: lightslategray;
    /* background-color:rgb(136, 184, 255); */
}
.circle {
    position:absolute;
    top:-1rem;
    margin-left:-1em;
    width: 2em;
    height: 2em;
    border-radius: 50%;
    background-color:#fff;
    box-shadow: 0 0 0.5rem #868686;
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