<template>
    <div class="registerContainer" @keydown.enter="register">
        <spinner-loading :loading="loadingContainer" style="width: 100px; height: 100px; margin: 20px auto"></spinner-loading>

        <div v-if="!loadingContainer">
            <h1 class="registerTitle">Manage account</h1>

            <p class="successText" v-if="success !== ''">{{ success }}</p>

            <div class="registerInputContainer">
                <p class="errorText" v-if="errors.name !== ''">{{ errors.name }}</p>
                <input type="text" class="form-control registerInput" :class="{ 'errorInput' : errors.name !== ''}" v-model="form.name" placeholder="Nume">
            </div>

            <div class="registerInputContainer">
                <input type="email" class="form-control registerInput" v-model="form.email" placeholder="Email" disabled>
            </div>

            <div class="registerInputContainer">
                <p class="errorText" v-if="errors.phone !== ''">{{ errors.phone }}</p>
                <input type="text" class="form-control registerInput" :class="{ 'errorInput' : errors.phone !== ''}" v-model="form.phone" placeholder="Phone">
            </div>

            <div class="registerInputContainer">
                <p class="errorText" v-if="errors.role !== ''">{{ errors.role }}</p>
                <select class="form-control registerInput" v-model="form.role" :class="{ 'errorInput' : errors.role !== ''}">
                    <option value="0" disabled selected>Select an option</option>
                    <option value="1">User</option>
                    <option value="2">Admin</option>
                </select>
            </div>

            <div>
                <ButtonCustom :loading="loadingEdit" :text="'Save'" :background="'green'" @click.native="edit"></ButtonCustom>
                <ButtonCustom :loading="loadingDelete" :text="'Delete'" :background="'red'" @click.native="del"></ButtonCustom>
            </div>

            <div>
                <router-link to="/user/list" class="btn btn-info"><i class="fa fa-arrow-left"> Back</i></router-link>
            </div>
        </div>
    </div>
</template>

<script>
    import ButtonCustom from '../page/ButtonCustom';
    import SpinnerLoading from '../page/SpinnerLoading';

    export default {
        components: {
            ButtonCustom,
            SpinnerLoading
        },
        data(){
            return {
                form: {
                    name: '',
                    email: '',
                    role: 0,
                    phone: ''
                },
                errors:{
                    email: '',
                    name: '',
                    phone: '',
                    role: ''
                },
                success: '',
                loadingContainer: 0,
                loadingEdit: 0,
                loadingDelete: 0
            }
        },
        mounted(){
            this.loadingContainer = 1;
            axios.get('/api/user/find/' + this.$route.params.id)
                .then(res=>{
                    this.form = res.data[0];
                    this.loadingContainer = 0;
                })
                .catch(err=>{
                    this.loadingContainer = 0;
                    this.$router.push('/user/list');
                })
        },
        methods:{
            edit(){
                this.loadingEdit = 1;

                this.error = '';
                this.success = '';

                axios.put('/api/user/edit/' + this.form.id , this.form)
                    .then(res=>{
                        this.success = res.data.message;
                        this.loadingEdit = 0;
                    })
                    .catch(err=>{
                        let list = err.response.data.errors;
                        this.errors.name = list.hasOwnProperty('name') ? list.name[0] : '';
                        this.errors.phone = list.hasOwnProperty('phone') ? list.phone[0] : '';

                        this.loadingEdit = 0;
                    })
            },
            del(){
                this.loadingDelete = 1;

                axios.delete('/api/user/delete/'+this.$route.params.id)
                    .then(res=>{
                        this.$router.push('/user/list');
                        this.loadingDelete = 0;
                    })
                    .catch(err=>{
                        this.$router.push('/user/list');
                        this.loadingDelete = 0;
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

