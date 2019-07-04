<template>
    <div class="registerContainer" @keydown.enter="register">
        <h1 class="registerTitle">Create account</h1>

        <p class="successText" v-if="success !== ''">{{ success }}</p>

        <div class="registerInputContainer">
            <p class="errorText" v-if="errors.name !== ''">{{ errors.name }}</p>
            <input type="text" class="form-control registerInput" :class="{ 'errorInput' : errors.name !== ''}" v-model="form.name" placeholder="Nume">
        </div>

        <div class="registerInputContainer">
            <p class="errorText" v-if="errors.email !== ''">{{ errors.email }}</p>
            <input type="email" class="form-control registerInput" :class="{ 'errorInput' : errors.email !== ''}" v-model="form.email" placeholder="Email">
        </div>

        <div class="registerInputContainer">
            <p class="errorText" v-if="errors.password !== ''">{{ errors.password }}</p>
            <input type="password" class="form-control registerInput" :class="{ 'errorInput' : errors.password !== ''}" v-model="form.password" placeholder="Password">
        </div>

        <div class="registerInputContainer">
            <p class="errorText" v-if="errors.password_confirmation !== ''">{{ errors.password_confirmation }}</p>
            <input type="password" class="form-control registerInput" :class="{ 'errorInput' : errors.password_confirmation !== ''}" v-model="form.password_confirmation" placeholder="Password Confirmation">
        </div>

        <div class="registerInputContainer">
            <p class="errorText" v-if="errors.phone !== ''">{{ errors.phone }}</p>
            <input type="text" class="form-control registerInput" :class="{ 'errorInput' : errors.phone !== ''}" v-model="form.phone" placeholder="Phone">
        </div>

        <div class="registerInputContainer">
            <p class="errorText" v-if="errors.city !== ''">{{ errors.city }}</p>
            <input type="text" class="form-control registerInput" :class="{ 'errorInput' : errors.city !== ''}" v-model="form.city" placeholder="City">
        </div>

        <div>
            <ButtonCustom :loading="loading" :text="'Create'" @click.native="register"></ButtonCustom>
        </div>

        <div>
            <router-link to="/" class="btn btn-info"><i class="fa fa-arrow-left"></i> Back</router-link>
        </div>
    </div>
</template>

<script>
    import ButtonCustom from '../page/ButtonCustom';

    export default {
        components: {ButtonCustom},
        data(){
            return {
                form: {
                    name: 'Cont ',
                    email: 'cont@email.ro',
                    role: 2,
                    phone: '0751929003',
                    city: 'Iasi',
                    password: '123456',
                    password_confirmation: '123456'
                },
                errors:{
                    email: '',
                    name: '',
                    phone: '',
                    role: '',
                    city: '',
                    password: '',
                    password_confirmation: ''

                },
                success: '',
                loading: 0
            }
        },
        methods:{

            register(){
                this.loading = 1;

                this.errors.name = '';
                this.errors.email = '';
                this.errors.phone = '';
                this.errors.role = ''
                this.errors.city = '';
                this.errors.password = '';
                this.errors.password_confirmation = '';

                this.success = '';

                axios.post('/api/auth/signup', this.form)
                    .then(res=>{
                        this.success = res.data.message;
                        this.loading = 0;
                    })
                    .catch(err=>{
                        console.log(err);
                        let list = err.response.data.errors;
                        this.errors.name = list.hasOwnProperty('name') ? list.name[0] : '';
                        this.errors.email = list.hasOwnProperty('email') ? list.email[0] : '';
                        this.errors.phone = list.hasOwnProperty('phone') ? list.phone[0] : '';
                        this.errors.city = list.hasOwnProperty('city') ? list.city[0] : '';
                        this.errors.password = list.hasOwnProperty('password') ? list.password[0] : '';
                        this.errors.password_confirmation = list.hasOwnProperty('password_confirmation') ? list.password_confirmation[0] : '';

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
    .registerContainer{
        color: #73879C;
        margin: 5% auto 0;
        max-width: 350px;
        padding: 25px 0 0;
        text-align: center;
        text-shadow: 0 1px 0 rgba(147, 147, 147, 0.44);
        position: relative;
    }

    .registerTitle{
        font: 400 25px Helvetica,Arial,sans-serif;
        letter-spacing: -.05em;
        line-height: 20px;
        margin: 10px 0 30px;
    }

    .registerTitle:after,
    .registerTitle:before{
        background: #7e7e7e;
        content: "";
        height: 1px;
        position: absolute;
        top: 47px;
        width: 25%;
    }

    .registerTitle:after{
        right: 0;
    }

    .registerTitle:before{
        left: 0;
    }

    .registerInputContainer{
        margin-bottom: 20px;
    }

    .registerInput:focus{
        -ms-box-shadow: 0 0 2px 0 #d2d2d2, 0 0 5px 1px rgba(0, 0, 0, 0.08) inset;
        -o-box-shadow: 0 0 2px 0 #d2d2d2, 0 0 5px 1px rgba(0, 0, 0, 0.08) inset;
        box-shadow: 0 0 2px 0 #d2d2d2, 0 0 5px 1px rgba(0, 0, 0, 0.08) inset;
        border: 1px solid #d3bad1;
    }

    .registerInput{
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
</style>
