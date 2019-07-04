<template>
    <div class="container containerList">
        <spinner-loading :loading="loading" style="width: 100px; height: 100px; margin: 20px auto"></spinner-loading>

        <div v-if="!loading">
            <h2 class="titleList">User Management</h2>


            <table class="table" align="center">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Avatar</th>
                    <th scope="col">Email</th>
                    <th scope="col">Active</th>
                    <th scope="col">Handle</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, index) in users">
                    <th scope="row">{{index+1}}</th>
                    <td><img :src="item.avatar" class="avatarUser"></td>
                    <td>{{item.name}}</td>
                    <td>{{item.email}}</td>
                    <td><i class="fa fa-circle" :style="[ item.active == 1 ? {'font-size' : '20px', 'color':'green'} : {'font-size' : '20px', 'color': 'red'}]"></i></td>
                    <td><button v-if="item.active == 1" class="btn btn-danger" value="Activate" @click="desactivate(index, item)" >DezActivate</button></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import SpinnerLoading from '../page/SpinnerLoading';
    import pagination from '../page/PaginationCustom';

    export default {
        data(){
            return{
                users: [],
                loading: 0
            }
        },
        mounted(){
            this.loading = 1;

            axios.get('/api/user/get')
                .then(res=>{
                    this.users = res.data;
                    this.loading = 0
                })
                .catch(err=>{
                    this.loading = 0;
                })
        },
        methods:{
            desactivate(index, item){
                item.active = 0;
                this.$set(this.users, index, item)
            }
        },
        components:{
            SpinnerLoading
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

    .btnRound{
        font-size: 29px;
        border-radius: 50%;
        line-height: 36px;
    }

    table th{
        text-align: center;
        font-size: 18px;
    }

    table td{
        font-size: 17px;
    }

    .avatarUser{
        width: 35px;
        border: 1px solid black;
        border-radius: 50%;
    }
</style>
