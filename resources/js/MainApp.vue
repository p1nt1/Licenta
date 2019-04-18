<template>
    <div class="content">
        <Header></Header>
        <router-view></router-view>
    </div>
</template>

<script>
    import Header from './page/Header';

    export default{
        components:{
            Header
        }
    }
</script>




