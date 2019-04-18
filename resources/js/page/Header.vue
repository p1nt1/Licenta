<template>
    <nav class="navbar" v-if="currentUser">
        <Sidebar></Sidebar>
    </nav>
</template>

<script>
    import Sidebar from './Sidebar';

    export default {
        components:{
            Sidebar
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser
            }
        }
    }
</script>

<style lang="scss" scoped>
    .navbar{
        background-color: #EDEDED;
    }

    .nav-link{
        color: black !important;
    }
</style>
