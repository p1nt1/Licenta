<template>
    <div class="container containerList">
        <h2 class="titleList">All Posts</h2>

        <search @search="search"></search>

        <spinner-loading :loading="loading" style="width: 100px; height: 100px; margin: 20px auto"></spinner-loading>
        <div v-if="!loading">
            <div v-if="posts.length != 0" class="containerClientList" >

                <div class="clientCard" v-for="(item, key) in posts" v-if="item.status == 1" :key="item.id">

                    <div class="pictureItem">
                        <div class="prev" @click="change_current_picture_prev(item)"></div>
                        <div class="next" @click="change_current_picture_next(item)"></div>
                        <img :src="item.picture[item.current_picture]" class="clientPicture">
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

        <dialog-component :title="'Delete'" :body="'Are you sure you want to delete?'" @action="confirmDelete"></dialog-component>
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
                    this.posts = res.data.data;
                    let i = 0;
                    for (i = 0; i < this.posts.length; i++) {
                        this.posts[i].picture = this.posts[i].picture.split(';');
                        this.posts[i].current_picture = 0;
                    }
                    this.loading = 0
                })
                .catch(err=>{
                    this.loading = 0;
                })
        },
        methods:{
            change_current_picture_prev(item){
                let i;
                for (i = 0; i < this.posts.length; i++) {
                    if(this.posts[i].id == item.id){
                        if(this.posts[i].current_picture > 0){
                            item.current_picture = item.current_picture - 1;
                            this.$set(this.posts, i, item)
                        }
                    }
                }
            },
            change_current_picture_next(item){
                let i;
                for (i = 0; i < this.posts.length; i++) {
                    if(this.posts[i].id == item.id){
                        if(this.posts[i].picture.length-1 > this.posts[i].current_picture){
                            item.current_picture = item.current_picture + 1;
                            this.$set(this.posts, i, item)
                        }
                    }
                }
            },
            confirmDelete(item){
                this.selectedItem = item;
            },
            search(word){
                this.loading=1;
                axios.get('/api/posts/search', {params : { word : word}})
                    .then(response => {
                        this.posts = response.data;
                        let i = 0;
                        for (i = 0; i < this.posts.length; i++) {
                            this.posts[i].picture = this.posts[i].picture.split(';');
                            this.posts[i].current_picture = 0;
                        }
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
    .prev{
        width: 100px;
        cursor: pointer;
        height: 75%;
        position: absolute;
    }
    .next{
        width: 100px;
        cursor: pointer;
        height: 75%;
        position: absolute;
        left: 190px;
    }
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
        height: 265px;
    }

    .clientCard:hover {
        box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
    }

    .clientPicture{
        vertical-align: middle;
        border-style: none;
        height: 200px;
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