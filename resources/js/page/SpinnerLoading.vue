<template>
        <div class="loader" v-if="loading"></div>
</template>

<script>
    export default {
        props:{
            loading: 0,
        }
    }
</script>

<style lang="scss">
    .loader,
    .loader:after {
        border-radius: 50%;
        width: 10px;
        height: 10px;
    }
    .loader {
        margin: 4px auto;
        font-size: 10px;
        position: relative;
        text-indent: -9999em;
        border-top: 1.1em solid black;
        border-right: 1.1em solid black;
        border-bottom: 1.1em solid black;
        border-left: 1.1em solid #af3f3f;
        -webkit-transform: translateZ(0);
        -ms-transform: translateZ(0);
        transform: translateZ(0);
        -webkit-animation: load8 1.1s infinite linear;
        animation: load8 1.1s infinite linear;
    }
    @-webkit-keyframes load8 {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }
    @keyframes load8 {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }
</style>