<template>
    <div class="pagination" v-if="pagination.last_page > 1 && pagination.current_page <= pagination.last_page">
        <a href="#" @click.prevent="changePage(1)" v-if="pagination.current_page > 1"><<</a>
        <a href="#" @click.prevent="changePage(pagination.current_page - 1)" v-if="pagination.current_page > 1"> < </a>
        <a href="#" @click.prevent="changePage(pagination.current_page - 1)" v-if="pagination.current_page > 1"> {{ pagination.current_page - 1 }} </a>
        <a href="#" class="active" @click.prevent="changePage(pagination.current_page)"> {{ pagination.current_page }} </a>
        <a href="#" @click.prevent="changePage(pagination.current_page + 1)" v-if="pagination.current_page < pagination.last_page"> {{ pagination.current_page + 1}} </a>
        <a href="#" @click.prevent="changePage(pagination.current_page + 1)" v-if="pagination.current_page < pagination.last_page"> > </a>
        <a href="#" @click.prevent="changePage(pagination.last_page)" v-if="pagination.current_page < pagination.last_page"> >> </a>
    </div>
</template>

<style>
    .pagination {
        margin-top: 40px;
    }
</style>

<script>
    export default {
        props: {
            pagination: Object,
            link: String
        },
        methods: {
            changePage(page) {
                this.pagination.current_page = page;
                this.$emit('paginate');
                this.$router.push(this.link + page);
            }
        }
    }
</script>

<style>
    .pagination {
        display: inline-block;
    }

    .pagination a {
        color: black;
        float: left;
        padding: 8px 16px;
        text-decoration: none;
        transition: background-color .3s;
        border: 1px solid #ddd;
        margin: 0 4px;
    }

    .pagination a.active {
        background-color: #4CAF50;
        color: white;
        border: 1px solid #4CAF50;
    }
</style>