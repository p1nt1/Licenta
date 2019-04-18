<template>
    <div class="colorContainer" @keydown.enter="colorAdd">
        <h1 class="colorTitle">Create color</h1>

        <p class="successText" v-if="success !== ''">{{ success }}</p>

        <div class="colorInputContainer">
            <p class="errorText" v-if="errors.name_color !== ''">{{ errors.name_color }}</p>
            <input type="text" class="form-control colorInput" :class="{ 'errorInput' : errors.name_color !== ''}" v-model="form.name_color" placeholder="Nume">
        </div>

        <div class="colorInputContainer">
            <p class="errorText" v-if="errors.color_hex !== ''">{{ errors.color_hex }}</p>
            <sketch v-model="color" ></sketch>
        </div>

        <div>
            <ButtonCustom :loading="loading" :text="'Create'" @click.native="colorAdd"></ButtonCustom>
        </div>

        <div>
            <router-link to="/color/list" class="btn btn-info"><i class="fa fa-arrow-left"> Back</i></router-link>
        </div>
    </div>
</template>

<script>
    import ButtonCustom from '../page/ButtonCustom';
    import Sketch from "vue-color/src/components/Sketch";

    export default {
        components: {Sketch, ButtonCustom},
        data(){
            return {
                form: {
                    name_color: '',
                    color_hex: ''
                },
                errors:{
                    name_color: '',
                    color_hex: ''
                },
                success: '',
                loading: 0,
                color: ''
            }
        },
        methods:{

            colorAdd(){
                this.loading = 1;

                this.errors.name_color = '';
                this.errors.color_hex = '';
                this.success = '';

                this.form.color_hex = this.color.hex;

                axios.post('/api/color/create', this.form)
                    .then(res=>{
                        this.success = res.data.message;
                        this.loading = 0;
                    })
                    .catch(err=>{
                        console.log(err);
                        let list = err.response.data.errors;
                        this.errors.name_color = list.hasOwnProperty('name_color') ? list.name_color[0] : '';
                        this.errors.color_hex = list.hasOwnProperty('color_hex') ? list.color_hex[0] : '';

                        this.loading = 0;
                    })
            }
        }
    }
</script>

<style lang="scss" scoped>
    .colorContainer{
        color: #73879C;
        margin: 1% auto 0;
        max-width: 350px;
        padding: 25px 0 0;
        text-align: center;
        text-shadow: 0 1px 0 rgba(147, 147, 147, 0.44);
        position: relative;
    }

    .colorTitle{
        font: 400 25px Helvetica,Arial,sans-serif;
        letter-spacing: -.05em;
        line-height: 20px;
        margin: 10px 0 30px;
    }

    .colorTitle:after,
    .colorTitle:before{
        background: #7e7e7e;
        content: "";
        height: 1px;
        position: absolute;
        top: 47px;
        width: 25%;
    }

    .colorTitle:after{
        right: 0;
    }

    .colorTitle:before{
        left: 0;
    }

    .colorInputContainer{
        margin-bottom: 20px;
        display: flex;
        justify-content: center;
    }

    .colorInput:focus{
        -ms-box-shadow: 0 0 2px 0 #d2d2d2, 0 0 5px 1px rgba(0, 0, 0, 0.08) inset;
        -o-box-shadow: 0 0 2px 0 #d2d2d2, 0 0 5px 1px rgba(0, 0, 0, 0.08) inset;
        box-shadow: 0 0 2px 0 #d2d2d2, 0 0 5px 1px rgba(0, 0, 0, 0.08) inset;
        border: 1px solid #d3bad1;
    }

    .colorInput{
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
        position: relative;
        z-index: 1;
    }

    .errorInput{
        border: 1px solid #ff000052;
    }

    .successText{
        color: green;
        font-size: 20px;
    }

    .square {
        height: 50px;
        width: 50px;
    }
</style>

