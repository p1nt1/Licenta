<template>
    <div class="loginContainer" @keydown.enter="login">
        <h1 class="loginTitle">Login</h1>

        <p class="errorText" v-if="error !== ''">{{ error }}</p>

        <div class="loginInputContainer">
            <input type="text" class="form-control loginInput" placeholder="Username" v-model="form.email"/>
        </div>

        <div class="input-group loginInputContainer">
            <input :type="passwordView" class="form-control loginInput" v-model="form.password" placeholder="Enter password">
            <div class="input-group-append">
                <span class="input-group-text" style="cursor: pointer" @click="passwordView=='password' ? passwordView='text' : passwordView='password'"><i class="fa fa-eye"></i></span>
            </div>
        </div>

        <div class="text-right">
            <input type="checkbox" class="form-check-input" v-model="form.remember"><label>Remember me</label>
        </div>

        <div>
            <ButtonCustom :loading="loading" :text="'Login'" @click.native="login"></ButtonCustom>
        </div>

        <div class="clearfix"></div>

        <div class="separator">
            <p class="change_link">Lost your password?
                <router-link to="/forgot">Reset it</router-link>
            </p>
        </div>
    </div>
</template>

<script>
    import {setAuthorization} from "../helpers/general";
    import ButtonCustom from '../page/ButtonCustom';

    export default {
        components: {ButtonCustom},
        data(){
            return {
                form:{
                    email: 'iliuta@contursoft.ro',
                    password: '123456',
                    remember: true

                },
                error:'',
                remember: 0,
                loading: 0,
                passwordView: 'password'
            }
        },
        methods:{

            login(){
                this.loading = 1;

                this.error = '';

                axios.post('/api/auth/login', this.form)
                    .then(res=>{
                        setAuthorization(res.data.access_token);
                        this.$store.commit("login", res.data);
                        this.loading = 0;
                        this.$router.push('/client/list');
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
    .loginContainer{
        color: #73879C;
        margin: 5% auto 0;
        max-width: 350px;
        padding: 25px 0 0;
        text-align: center;
        text-shadow: 0 1px 0 rgba(147, 147, 147, 0.44);
        position: relative;
    }

    .loginTitle{
        font: 400 25px Helvetica,Arial,sans-serif;
        letter-spacing: -.05em;
        line-height: 20px;
        margin: 10px 0 30px;
    }

    .loginTitle:after,
    .loginTitle:before{
        background: #7e7e7e;
        content: "";
        height: 1px;
        position: absolute;
        top: 47px;
        width: 25%;
    }

    .loginTitle:after{
        right: 0;
    }

    .loginTitle:before{
        left: 0;
    }

    .loginInputContainer{
        margin-bottom: 20px;
    }

    .loginInput:focus{
        -ms-box-shadow: 0 0 2px 0 #d2d2d2, 0 0 5px 1px rgba(0, 0, 0, 0.08) inset;
        -o-box-shadow: 0 0 2px 0 #d2d2d2, 0 0 5px 1px rgba(0, 0, 0, 0.08) inset;
        box-shadow: 0 0 2px 0 #d2d2d2, 0 0 5px 1px rgba(0, 0, 0, 0.08) inset;
        border: 1px solid #d3bad1;
    }

    .loginInput{
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
</style>

