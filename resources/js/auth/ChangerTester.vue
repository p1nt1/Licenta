<template>
    <div class="changeContainer" @keydown.enter="change">
        <p>RED</p>
        <div class="loginInputContainer">
            <input type="text" placeholder="RED" v-model="form.red"/>
        </div>

        <p>BLUE</p>
        <div class="loginInputContainer">
            <input type="text" placeholder="BLUE" v-model="form.blue"/>
        </div>

        <p>GREEN</p>
        <div class="loginInputContainer">
            <input type="text" placeholder="GREEN" v-model="form.green"/>
        </div>

        <p>BRIGHTNESS</p>
        <div class="loginInputContainer">
            <input type="text" placeholder="BRIGHTNESS" v-model="form.brightness"/>
        </div>
        <ButtonCustom :loading="loading" :text="'Change'" @click.native="send"></ButtonCustom>

        <img src="storage\app\public\public\clients\male_brunette.png" alt="not loaded">

    </div>


</template>

<script>

    import ButtonCustom from '../page/ButtonCustom';

    export default {
        components: {ButtonCustom},
        data(){
            var form;
            return {
                form:{

                },
                error:'',
                loading: 0
            }
        },
        methods:{

            send(){
                axios.post('/api/color', this.form)
                    .then(res => {
                        this.refresh();
                    })
                    .catch(err => {

                    })
            }
        },
        comments:{
            ButtonCustom
        }
    }

</script>

<style scoped>
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