<template>
    <div class="container containerList">
        <spinner-loading :loading="loading" style="width: 100px; height: 100px; margin: 20px auto"></spinner-loading>

        <div v-if="!loading">
            <h2 class="titleList">Client Management</h2>

            <div v-if="clients.length != 0" class="containerClientList">
                <div class="clientCard" v-for="item in clients">
                    <i class="fa fa-times deleteIcon" @click="confirmDelete(item)" data-toggle="modal" data-target="#myModal"></i>
                    <p>
                        <img :src="item.picture" class="clientPicture">
                    </p>

                    <p class="clientName">{{item.name}}</p>
                </div>
            </div>
            <p v-else class="emptyList"> No elements! Please add client!</p>
        </div>

        <dialog-component :title="'Delete'" :body="'Are you sure you want to delete?'" @action="deleteClient"></dialog-component>
    </div>
</template>

<script>
    import DialogComponent from '../page/DialogComponent';

    export default {
        data(){
            return{
                clients: [],
                selectedClient : '',
                loading: 0
            }
        },
        mounted(){
            this.loading = 1;
            axios.get('/api/client/get')
                .then(res=>{
                    this.clients = res.data;
                    this.loading = 0
                })
                .catch(err=>{
                    this.loading = 0;
                })
        },
        methods:{
            confirmDelete(item){
                this.selectedClient = item;
            },
            deleteClient(){
                if(this.selectedClient!=0){
                    axios.delete('/api/client/delete/'+this.selectedClient.id)
                        .then(
                            this.clients.splice(this.clients.indexOf(this.selectedClient),1)
                        )
                }
            }
        },
        components:{
            DialogComponent
        }
    }
</script>

<style scoped>
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
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;
    }

    @media (max-width: 1199px){
        .containerClientList{
            justify-content: center;
        }
    }

    .clientCard {
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
        transition: 0.3s;
        max-width: 30%;
        min-width: 300px;
        padding: 5px;
        margin: 10px;
        position: relative;
    }

    .clientCard:hover {
        box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
    }

    .colorSquare{
        width: 35px;
        height: 35px;
        border-radius: 50%;
        border: 1px solid black;
        margin: 5px auto 10px;
    }

    .clientPicture{
        width: 100%;
    }

    .clientName{
        font-size: 25px;
        font-weight: bold;
    }

    .deleteIcon{
        position: absolute;
        right: 10px;
        top: 10px;
        font-size: 25px;
        cursor: pointer;
    }

    .deleteIcon:hover{
        color: red;
    }

</style>
