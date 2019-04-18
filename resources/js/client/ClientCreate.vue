<template>
    <vue-tabs class="tabs">
        <v-tab title="New Client">
            <div class="clientCreateContainer" @keydown.enter="createClient">
                <h1 class="clientCreateTitle">Client details</h1>

                <p class="successText" v-if="success !== ''">{{ success }}</p>

                <div class="clientCreateInputContainer">
                    <p class="errorText" v-if="errors.name !== ''">{{ errors.name }}</p>
                    <input type="text" class="form-control clientCreateInput" :class="{ 'errorInput' : errors.name !== ''}" v-model="form.name" placeholder="Nume">
                </div>

                <div class="clientCreateInputContainer">
                    <p class="errorText" v-if="errors.email !== ''">{{ errors.email }}</p>
                    <input type="email" class="form-control clientCreateInput" :class="{ 'errorInput' : errors.email !== ''}" v-model="form.email" placeholder="Email">
                </div>

                <div>
                    <ButtonCustom :loading="loading" :text="'Save client&Add picture'" @click.native="createClient"></ButtonCustom>
                </div>

                <div>
                    <a class="btn btn-info" @click="router.go(-1)"><i class="fa fa-arrow-left"> Back</i></a>
                </div>
            </div>
        </v-tab>

        <v-tab title="Old Client">
            <div  style="text-align: center">
                <search @search="search"></search>

                <div v-if="clients.length != 0">
                    <p class="successText" v-if="success !== ''">{{ success }}</p>
                    <div class="clientContainer" >
                        <div class="client" v-for="item in clients" @click="clientSelected=item.id" :class="{ 'clientSelected' : clientSelected==item.id}">
                            <input type="radio" :value="item.id" class="clientSelect d-none" v-model="clientSelected">
                            <img :src="item.picture" class="clientPicture">
                            <span class="clientName">{{item.name}}</span>
                        </div>
                    </div>
                    <ButtonCustom :text="'Add picture'" @click.native="editClient()"></ButtonCustom>
                </div>
                <div class="clientContainer" v-else>
                    <p>No clients! Create a new one, plese!</p>
                </div>
            </div>



        </v-tab>
    </vue-tabs>
</template>

<script>
    import ButtonCustom from '../page/ButtonCustom';
    import Search from '../page/SearchComponent';

    export default {
        components: {ButtonCustom, Search},
        data(){
            return {
                clients: [],
                form: {
                    name: '',
                    email: '',
                    picture: ''
                },
                errors:{
                    email: '',
                    name: '',
                },
                success: '',
                loading: 0,
                clientSelected: 0
            }
        },
        mounted(){
            axios.get('/api/client/get')
                .then(res=>{
                    this.clients = res.data;
                })
        },
        methods:{
            createClient(){
                this.loading = 1;

                this.error = '';
                this.success = '';

                this.form.picture = this.$store.getters.currentUserPhoto;

                axios.post('/api/client/create', this.form)
                    .then(res=>{
                        axios.post('/api/picture/create', {
                            color : this.$store.getters.colorModifiedPhoto,
                            photo : this.$store.getters.modifiedUserPhoto,
                            user : res.data.id
                        })
                            .then(res=>{
                                this.loading = 0;
                            })
                            .catch(err=>{
                                this.loading = 0;
                            });
                        this.success = res.data.message;
                    })
                    .catch(err=>{
                        let list = err.response.data.errors;
                        this.errors.name = list.hasOwnProperty('name') ? list.name[0] : '';
                        this.errors.email = list.hasOwnProperty('email') ? list.email[0] : '';

                        this.loading = 0;
                    })
            },
            editClient(){
                this.loading = 1;

                this.success = '';

                axios.post('/api/picture/create/', {
                    color : this.$store.getters.colorModifiedPhoto,
                    photo : this.$store.getters.modifiedUserPhoto,
                    user : this.clientSelected
                })
                    .then(res=>{
                        this.success = res.data.message;
                        this.loading = 0;
                    })
                    .catch(err=>{
                        this.loading = 0;
                    })
            },
            search(word){
                this.loading=1;
                axios.get('/api/client/search', {params : { word : word}})
                    .then(response => {
                        this.clients = response.data;
                        this.loading = 0;
                    })
            }
        },
        comments:{
            ButtonCustom
        }
    }
</script>

<style lang="scss" scoped>
    .tabs{
        max-width: 404px;
        margin: 5% auto 0;
    }

    .clientCreateContainer{
        color: #73879C;
        padding: 25px 0 0;
        text-align: center;
        text-shadow: 0 1px 0 rgba(147, 147, 147, 0.44);
        position: relative;
    }

    .clientCreateTitle{
        font: 400 25px Helvetica,Arial,sans-serif;
        letter-spacing: -.05em;
        line-height: 20px;
        margin: 10px 0 30px;
    }

    .clientCreateTitle:after,
    .clientCreateTitle:before{
        background: #7e7e7e;
        content: "";
        height: 1px;
        position: absolute;
        top: 47px;
        width: 25%;
    }

    .clientCreateTitle:after{
        right: 0;
    }

    .clientCreateTitle:before{
        left: 0;
    }

    .clientCreateInputContainer{
        margin-bottom: 20px;
    }

    .clientCreateInput:focus{
        -ms-box-shadow: 0 0 2px 0 #d2d2d2, 0 0 5px 1px rgba(0, 0, 0, 0.08) inset;
        -o-box-shadow: 0 0 2px 0 #d2d2d2, 0 0 5px 1px rgba(0, 0, 0, 0.08) inset;
        box-shadow: 0 0 2px 0 #d2d2d2, 0 0 5px 1px rgba(0, 0, 0, 0.08) inset;
        border: 1px solid #d3bad1;
    }

    .clientCreateInput{
        border-radius: 3px;
        -ms-box-shadow: 0 1px 0 #fff,0 -2px 5px rgba(0,0,0,.08) inset;
        -o-box-shadow: 0 1px 0 #fff,0 -2px 5px rgba(0,0,0,.08) inset;
        box-shadow: 0 1px 0 #fff, 0 -2px 5px rgba(0,0,0,.08) inset;
        border: 1px solid #c8c8c8;
        color: #777;
    }

    .separator{
        border-top: 1px solid #D8D8D8;
        margin-top: 10px;
        padding-top: 10px;
    }

    .errorText{
        font-size: 15px;
        color: red;
        margin-bottom: 1px;
        margin-left: 5px;
        float: left;
    }

    .errorInput{
        border: 1px solid #ff000052;
    }

    .successText{
        color: green;
        font-size: 20px;
    }

    .clientPicture{
        width: 65px;
        border: 1px solid black;
        height: 50px;
    }

    .clientName{
        font-size: 20px;
        margin: 11px 19px 19px;
    }

    .clientSelect{
        margin-top: 20px;
        margin-right: 15px;
    }

    .client{
        padding: 10px;
        width: 100%;
        display: flex;
        border-bottom: 1px solid gray;
        margin-bottom: 10px;
        cursor: pointer;
    }

    .client:hover{
        background-color: #8080803d;
    }

    .clientSelected{
        background-color: rgba(69, 69, 69, 0.42);
    }

    .clientSelected:hover{
        background-color: rgba(69, 69, 69, 0.42);
    }

    .clientContainer{
        text-align: center;
        padding-top: 10px;
        height: 400px;
        overflow-y: scroll;
    }
</style>

