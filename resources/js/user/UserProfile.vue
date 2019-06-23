<template>
    <div>
        <spinner-loading :loading="loading" style="width: 100px; height: 100px; margin: 20px auto"></spinner-loading>
        <div v-if="!loading">
            <div class="loginContainer">
                <img :src="user.avatar" class="avatar">
                <h1 class="loginTitle">{{user.name}}</h1>

                <div class="loginInputContainer">
                    <label class="loginLabel">Email</label>
                    <input type="text" class="form-control loginInput" placeholder="Email" v-model="user.email" disabled>
                    <i class="fa fa-envelope passwordIcon"></i>
                </div>

                <div class="loginInputContainer">
                    <label class="loginLabel">Phone</label>

                    <input class="form-control loginInput" v-model="user.phone" disabled>
                    <i class="fa fa-phone passwordIcon"></i>
                </div>

                <div class="loginInputContainer">
                    <label class="loginLabel">City</label>

                    <input class="form-control loginInput" v-model="user.city" disabled>
                    <i class="fa fa-building passwordIcon"></i>
                </div>

                <div>
                    <button class="btn btn-custom" type="submit" :disabled="loading == 1" @click="update">
                        <i class="fa fa-spinner fa-spin" style="font-size:18px" v-if="loading"></i> Update Info
                    </button>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import UserAvatar from './UserAvatar.vue'

    export default {
        created(){
            axios.get('/api/user/find/'+this.$route.params.id)
                .then(res=>{
                    if(res.data.length == 0)
                        this.$router.push({path: '/not-found'});
                    this.user = res.data[0];
                    this.user.city = 'Iasi';
                    this.loading = 0;
                })
                .catch(err=>{
                    this.$router.push({path: '/not-found'});
                })
        },
        data(){
            return {
                user:[],
                response: '',
                loading: 1,
                changeAvatar: 0,
            }
        },
        methods: {
            logout() {
                this.$store.commit('logout');
                this.$router.push('/login');
            },
            update(){
                this.loading=1;
                axios.post('/api/user/update', this.currentUser)
                    .then(res=>{
                        this.loading=0;
                        this.response = res.data.data;
                        this.$store.commit('updateUser', this.currentUser);
                    })
                    .catch(err=>{
                        this.loading=0;
                    })
            }
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser
            }
        },
        components:{
            UserAvatar
        }
    }
</script>

<style lang="scss">
    .btn-custom{
        color: #e1e8ef;
        background-color: #5DBCD2;
        border-color: #9aa5ad;
        border-radius: 7px;
    }

    ::placeholder{
        color: #2FA0CB;
    }

    .avatar{
        width: 200px;
        margin-top: 40px;
        background-color: #2FA0CB;
        border-radius: 50%;
        border: 2px solid black;
    }

    .loginContainer{
        margin: 60px auto 10px;
        max-width: 460px;
        text-align: center;
        text-shadow: 0 1px 0 rgba(147, 147, 147, 0.44);
        position: relative;
        background-color: white;
        height: 685px;
        padding: 25px 30px 25px;
        border-radius: 13px;
        -webkit-box-shadow: 0 0 3px 0 black;
        box-shadow: 0 0 3px 0 black;
        color: #2FA0CB;
    }

    .loginTitle {
        font: 400 45px Helvetica, Arial, sans-serif;
        letter-spacing: -.05em;
        line-height: 20px;
        margin: 40px 0 30px;
    }

    .loginInputContainer{
        margin-bottom: 20px;
    }

    .loginInput:focus{
        -ms-box-shadow: 0 0 2px 0 #d2d2d2ab, inset 0 0 2px 0 #2a81a2d9;
        -o-box-shadow: 0 0 2px 0 #d2d2d2ab, inset 0 0 2px 0 #2a81a2d9;
        box-shadow: 0 0 2px 0 #d2d2d2ab, inset 0 0 2px 0 #2a81a2d9;
    }

    .loginInput{
        border-radius: 7px;
        border: 1px solid #2a81a2;
        position: relative;
        padding-right: 33px;
        padding-left: 15px;
    }

    .loginLabel{
        float: left;
        padding-left: 4px;
        color: #2a81a2;
    }

    .passwordIcon{
        position: relative;
        float: right;
        bottom: 28px;
        right: 11px;
        cursor: pointer;
        color: #2a81a2;
    }

    .passwordIcon:hover{
        color: black;
    }

    .errorText{
        margin-top: 5px;
        font-size: 21px;
        color: red;
        margin-bottom: 10px;
        margin-left: 5px;
    }
</style>