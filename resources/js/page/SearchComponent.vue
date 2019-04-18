<template>
    <div class="searchContainer">
        <input type="text" class="searchInput" placeholder="search" v-model.lazy="word">
        <i class="fa fa-search searchIcon" @click="word"></i>
        <i class="fa fa-times deleteIcon" v-if="word.length!=0" @click="clear"></i>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                word: ''
            }
        },
        watch:{
            word(){
                this.$emit('search', this.word);
            }
        },
        methods:{
            clear(){
                this.word = '';
            }
        }
    }
</script>

<style scoped>
    .searchContainer{
        position: relative;
        width: 315px;
        margin: 15px auto;
    }

    .searchInput{
        border: 1px solid;
        border-radius: 10px;
        padding-left: 17px;
        padding-right: 30px;
        width: 312px;
        height: 32px;
        margin-left: 3px;
    }

    .searchIcon{
        position: absolute;
        right: 11px;
        top: 8px;
        cursor: pointer;
    }

    .deleteIcon{
        position: absolute;
        right: 33px;
        top: 6px;
        cursor: pointer;
        font-size: 17px;
        color: red;
    }

    .deleteIcon:hover{
        color: black;
    }
</style>