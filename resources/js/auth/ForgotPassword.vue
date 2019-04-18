<template>
    <div class="forgotContainer" @keydown.enter="forgot">
        <h1 class="forgotTitle">Forgot password</h1>

        <p class="errorText" v-if="error !== ''">{{ error }}</p>

        <p class="successText" v-if="success !== ''">{{success}}</p>

        <div class="forgotInputContainer">
            <input type="email" class="form-control forgotInput" placeholder="Email" v-model="form.email"/>
        </div>

        <div>
            <ButtonCustom :loading="loading" :text="'Send email'" @click.native="forgot"></ButtonCustom>
        </div>

        <div class="clearfix"></div>

        <div class="separator">
            <p class="change_link">Already a user?
                <router-link to="/">Log in</router-link>
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
                    email: ''

                },
                error:'',
                success: '',
                loading: 0
            }
        },
        methods:{

            forgot(){
                this.loading = 1;

                this.error = '';

                axios.post('/api/password/create', this.form)
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
    .forgotContainer{
        color: #73879C;
        margin: 5% auto 0;
        max-width: 350px;
        padding: 25px 0 0;
        text-align: center;
        text-shadow: 0 1px 0 rgba(147, 147, 147, 0.44);
        position: relative;
    }

    .forgotTitle{
        font: 400 25px Helvetica,Arial,sans-serif;
        letter-spacing: -.05em;
        line-height: 20px;
        margin: 10px 0 30px;
    }

    .forgotTitle:after,
    .forgotTitle:before{
        background: #7e7e7e;
        content: "";
        height: 1px;
        position: absolute;
        top: 47px;
        width: 20%;
    }

    .forgotTitle:after{
        right: 0;
    }

    .forgotTitle:before{
        left: 0;
    }

    .forgotInputContainer{
        margin-bottom: 20px;
    }

    .forgotInput:focus{
        -ms-box-shadow: 0 0 2px 0 #d2d2d2, 0 0 5px 1px rgba(0, 0, 0, 0.08) inset;
        -o-box-shadow: 0 0 2px 0 #d2d2d2, 0 0 5px 1px rgba(0, 0, 0, 0.08) inset;
        box-shadow: 0 0 2px 0 #d2d2d2, 0 0 5px 1px rgba(0, 0, 0, 0.08) inset;
        border: 1px solid #d3bad1;
    }

    .forgotInput{
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

    .successText{
        color: green;
        font-size: 20px;
        margin-bottom: 17px;
        margin-left: 5px;
    }
</style>

