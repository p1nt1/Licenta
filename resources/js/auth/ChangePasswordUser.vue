<template>
    <div class="changeContainer" @keydown.enter="change">
        <h1 class="changeTitle">Change password</h1>

        <p class="errorText" v-if="error !== ''">{{ error }}</p>

        <p class="successText" v-if="success !== ''">{{success}}</p>

        <div class="input-group changeInputContainer">
            <input :type="passwordView1" class="form-control changeInput" v-model="form.password" placeholder="Enter password">
            <div class="input-group-append">
                <span class="input-group-text" style="cursor: pointer" @click="passwordView1=='password' ? passwordView1='text' : passwordView1='password'"><i class="fa fa-eye"></i></span>
            </div>
        </div>

        <div class="input-group changeInputContainer">
            <input :type="passwordView2" class="form-control changeInput" v-model="form.password_confirmation" placeholder="Enter confirmation password">
            <div class="input-group-append">
                <span class="input-group-text" style="cursor: pointer" @click="passwordView2=='password' ? passwordView2='text' : passwordView2='password'"><i class="fa fa-eye"></i></span>
            </div>
        </div>

        <div>
            <ButtonCustom :loading="loading" :text="'Set password'" @click.native="change"></ButtonCustom>
        </div>

        <div class="clearfix"></div>

        <div class="separator">
            <p class="change_link">Already a user?
                <router-link to="/login">Log in</router-link>
            </p>
        </div>
    </div>
</template>

<script>
    import ButtonCustom from '../page/ButtonCustom';

    export default {
        components: {ButtonCustom},
        data(){
            return {
                form:{
                    password: '123456',
                    password_confirmation: '123456',
                    email: ''

                },
                error:'',
                success: '',
                loading: 0,
                passwordView1: 'password',
                passwordView2: 'password'
            }
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser
            }
        },
        methods:{
            change(){
                this.loading = 1;

                this.error = '';
                this.success = '';

                this.form.email = this.currentUser.email;

                axios.post('/api/auth/set-password', this.form)
                    .then(res=>{
                        this.success = res.data.message;
                        this.loading = 0;
                    })
                    .catch(err=>{
                        this.error = err.response.data.message;
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
    .changeContainer{
        color: #73879C;
        margin: 5% auto 0;
        max-width: 350px;
        padding: 25px 0 0;
        text-align: center;
        text-shadow: 0 1px 0 rgba(147, 147, 147, 0.44);
        position: relative;
    }

    .changeTitle{
        font: 400 25px Helvetica,Arial,sans-serif;
        letter-spacing: -.05em;
        line-height: 20px;
        margin: 11px 0 30px;
    }

    .changeUserName{
        font-size: 25px;
        margin-top: 3px;
    }

    .changeTitle:after,
    .changeTitle:before{
        background: #7e7e7e;
        content: "";
        height: 1px;
        position: absolute;
        top: 47px;
        width: 20%;
    }

    .changeTitle:after{
        right: 0;
    }

    .changeTitle:before{
        left: 0;
    }

    .changeInputContainer{
        margin-bottom: 20px;
    }

    .changeInput:focus{
        -ms-box-shadow: 0 0 2px 0 #d2d2d2, 0 0 5px 1px rgba(0, 0, 0, 0.08) inset;
        -o-box-shadow: 0 0 2px 0 #d2d2d2, 0 0 5px 1px rgba(0, 0, 0, 0.08) inset;
        box-shadow: 0 0 2px 0 #d2d2d2, 0 0 5px 1px rgba(0, 0, 0, 0.08) inset;
        border: 1px solid #d3bad1;
    }

    .changeInput{
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
        margin-top: 5px;
        font-size: 21px;
        color: red;
        margin-bottom: 10px;
        margin-left: 5px;
    }

    .errorText{
        font-size: 20px;
        color: red;
        margin-bottom: 17px;
        margin-left: 5px;
    }

    .successText{
        color: green;
        font-size: 20px;
        margin-bottom: 17px;
        margin-left: 5px;
    }
</style>