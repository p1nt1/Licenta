<template>
    <button type="submit" v-bind:style="{ backgroundColor: background }" class="btn btnCustom" :disabled="loading == 1" >
        <spinner-loading :loading="loading" v-if="loading"></spinner-loading>
        <span v-else>{{text}}</span>
    </button>
</template>

<script>
    import SpinnerLoading from "./SpinnerLoading";

    export default {
        components: {SpinnerLoading},
        props:{
            text: '',
            loading: 0,
            background:{
                default: '#73879C',
                type: String
            }
        },
        comments:{
            SpinnerLoading
        }
    }
</script>

<style lang="scss">
    .btnCustom{
        border-radius: 11px;
        margin: 10px;
        text-transform: uppercase;
        color: white;
        padding: 7px 45px;
        font-size: 13px;
        letter-spacing: 1px;

        -webkit-box-shadow: 0px 0px 20px 0px #00000061;
        -moz-box-shadow: 0px 0px 20px 0px #00000061;
        box-shadow: 0px 0px 20px 0px #00000061;
    }

    .btnCustom:hover{
        background-color: #a5bdd2;

        -webkit-box-shadow: 0px 0px 20px 0px #00000096;
        -moz-box-shadow: 0px 0px 20px 0px #00000096;
        box-shadow: 0px 0px 20px 0px #00000096;
    }

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
        border-top: 1.1em solid rgba(0, 0, 0, 0.2);
        border-right: 1.1em solid rgba(0, 0, 0, 0.2);
        border-bottom: 1.1em solid rgba(0, 0, 0, 0.2);
        border-left: 1.1em solid #000000;
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