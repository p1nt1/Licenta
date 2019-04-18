<template>
    <div class="container containerList">
        <spinner-loading :loading="loading" style="width: 100px; height: 100px; margin: 20px auto"></spinner-loading>

        <div v-if="!loading">
            <h2 class="titleList">Color Management</h2>

            <router-link class="btn btn-info buttonCustom btnRound" to="/color/create"><i class="fa fa-plus-circle"> </i></router-link>

            <table class="table" align="center" v-if="colors.length != 0">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Color</th>
                    <th scope="col">Manage</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, index) in colors">
                    <th scope="row">{{index+1}}</th>
                    <td>{{item.name_color}}</td>
                    <td><div class="color" :style="{ 'background-color': item.color_hex}"></div></td>
                    <td><router-link class="btn btn-primary" :to="`/color/handle/`+item.id"> <i class="fa fa-edit"></i></router-link> </td>
                </tr>
                </tbody>
            </table>

            <p v-else class="emptyList"> No elements! Please add colors!</p>

            <pagination :pagination="pagination" :link="'/color/list?page='" @paginate="paginate"></pagination>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                colors: [],
                loading: 0,
                pagination: {
                    current_page : 1,
                }
            }
        },
        mounted(){
            let page = this.$route.query.page;
            if(page !=  null){
                if(page>1){
                    this.pagination.current_page = parseInt(this.$route.query.page);
                }
            }

            this.paginate();
        },
        methods:{
            paginate(){
                this.loading = 1;
                axios.get('/api/color/get?page='+this.pagination.current_page)
                    .then(res=>{
                        this.colors = res.data.data;
                        this.pagination = res.data;
                        this.loading = 0
                    })
                    .catch(err=>{
                        this.loading = 0;
                    })
            }
        }
    }
</script>

<style>
    .containerList{
        margin-top: 30px;
        text-align: center;
        text-shadow: 0 1px 0 rgba(147, 147, 147, 0.44);
    }

    .buttonCustom{
        margin: 10px;
        float: right;
    }

    table th{
        text-align: center;
        font-size: 18px;
    }

    table td{
        font-size: 17px;
    }

    .emptyList{
        margin-top: 80px;
        font-size: 25px;
        color: red;
    }

    .color{
        width: 35px;
        height: 35px;
        margin: 0 auto;
        border-radius: 50%;
        border: 1px solid black;
    }

    .btnRound{
        font-size: 29px;
        border-radius: 50%;
        line-height: 36px;
    }
</style>