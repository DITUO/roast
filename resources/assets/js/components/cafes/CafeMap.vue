<style lang="scss">
    div#cafe-map {
        width: 100%;
        height: 400px;
    }
</style>

<template>
    <div id="cafe-map">

    </div>
</template>

<script>
export default {
    props: {
        'latitude': {//经度
            type: Number,
            default: function (){
                return 120.21
            }
        },
        'longitude': {//纬度
            type: Number,
            default: function (){
                return 30.29
            }
        },
        'zoom': {   // 缩放级别
            type: Number,
            default: function (){
                return 4
            }
        }
    },
    data(){
        return {}
    },
    mounted() {
        this.map = new AMap.Map('cafe-map', {
            center: [this.latitude, this.longitude],
            zoom: this.zoom
        });
    }
}
</script>


