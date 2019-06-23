<template>
    <div class="container containerList">
        <h2 class="titleList">All Posts</h2>

        <search @search="search"></search>

        <spinner-loading :loading="loading" style="width: 100px; height: 100px; margin: 20px auto"></spinner-loading>
        <div v-if="!loading">


            <div v-if="posts.length != 0" class="containerClientList">

                <div class="clientCard" v-for="item in posts">
                    <div class="pictureItem">
                        <img :src="item.picture" class="clientPicture">
                    </div>

                    <div class="titleItem">
                        {{item.title}}
                    </div>

                    <div class="descriptionItem">
                        {{item.description}}
                    </div>

                    <div class="sizeItem">
                       Size: {{item.size}}
                    </div>

                    <div class="priceItem">
                        {{item.price}} RON
                    </div>

                    <div class="gotobtn">
                        <router-link :to="{path: '/profile/' + item.user_id}" class="btn btn-custom">Go to user</router-link>
                    </div>

                </div>

            </div>
            <p v-else class="emptyList"> No elements! </p>
        </div>

        <dialog-component :title="'Delete'" :body="'Are you sure you want to delete?'" @action="deleteItem"></dialog-component>
    </div>
</template>

<script>
    import Search from '../page/SearchComponent';
    import DialogComponent from '../page/DialogComponent';

    export default {
        data(){
            return{
                posts: [],
                selectedItem : '',
                loading: 0
            }
        },
        mounted(){
            this.loading = 1;
            axios.get('/api/posts/get')
                .then(res=>{
                    this.posts = res.data.data
                    this.loading = 0
                })
                .catch(err=>{
                    this.loading = 0;
                })
        },
        methods:{
            confirmDelete(item){
                this.selectedItem = item;
            },
            search(word){
                this.loading=1;
                axios.get('/api/posts/search', {params : { word : word}})
                    .then(response => {
                        this.posts = response.data;
                        this.loading = 0;
                    })
                    .catch(err =>
                        this.loading = 0
                    )
            }
        },
        components:{Search, DialogComponent}
    }
</script>

<style scoped>
    .btn-custom{
        color: #e1e8ef;
        background-color: #5DBCD2;
        border-color: #9aa5ad;
        border-radius: 7px;
    }

    .containerList{
        margin-top: 30px;
        text-align: center;
        text-shadow: 0 1px 0 rgba(147, 147, 147, 0.44);
    }

    .emptyList{
        margin-top: 80px;
        font-size: 25px;
        color: red;
    }

    .containerClientList{
        margin: 0;
        padding: 0;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        outline: none;
        text-transform: none;
        text-decoration: none;
    }

    @media (max-width: 1199px){
        .containerClientList{
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
        }
    }

    @media(max-width: 800px){
        .clientCard {
            max-width: 90%;
            min-width: 330px !important;
            padding: 20px;
            margin: 15px;
            position: relative;
            cursor: pointer;
            height: 450px !important;
        }

        .clientPicture {
            float: none !important;
        }
    }

    .clientCard {
        -webkit-box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
        -webkit-transition: 0.3s;
        transition: 0.3s;
        max-width: 90%;
        min-width: 90%;
        padding: 20px;
        margin: 10px;
        position: relative;
        cursor: pointer;
        height: 265px;
    }

    .clientCard:hover {
        box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
    }

    .pictureItem {


    }
    .clientPicture{
        vertical-align: middle;
        border-style: none;
        height: 100%;
        float: left;
        border: 1px solid black;
    }

    .titleItem {
        font-size: 22px;
        font-weight: bolder;
        margin-bottom: 15px;
    }

    .descriptionItem {
        font-style: italic;
        font-size: 19px;
        margin-bottom: 10px;
        overflow-x: hidden;

    }

    .priceItem {
        font-size: 27px;
        color: #5DBCD2;
        font-weight: bolder;
    }
    .gotobtn{
        float: right;
    }


</style>