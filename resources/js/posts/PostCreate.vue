<template>
    <div class="postContainer" @keydown.enter="post">
        <h1 class="postTitle">Create post</h1>

        <p class="successText" v-if="success !== ''">{{ success }}</p>

        <div class="postInputContainer">
            <p class="errorText" v-if="errors.title !== ''">{{ errors.title }}</p>
            Title
            <input type="text" style="margin-top: 10px;" class="form-control postInput" :class="{ 'errorInput' : errors.title !== ''}" v-model="form.title" placeholder="Title">
        </div>

        <div class="postInputDescription">
            <p class="errorText" v-if="errors.description !== ''">{{ errors.description }}</p>
            Description
            <input type="text" style="margin-top: 10px;" class="form-control postInput" :class="{ 'errorInput' : errors.description !== ''}" v-model="form.description" placeholder="Description">
        </div>

        <div class="postInputContainer">
            <p class="errorText" v-if="errors.size !== ''">{{ errors.size }}</p>
            Size
            <input type="number" style="margin-top: 10px;" class="form-control postInput" :class="{ 'errorInput' : errors.size !== ''}" v-model="form.size" placeholder="Size">
        </div>

        <div class="Neon Neon-theme-dragdropbox">
            <input style="z-index: 999; opacity: 0; width: 320px; height: 200px; position: absolute; right: 0px; left: 0px; margin-right: auto; margin-left: auto;" name="files[]" id="filer_input2" multiple="multiple" type="file" :class="{ 'errorInput' : errors.picture !== ''}"  placeholder="picture" ref="myPicture"  @change="setPicture">
            <div class="Neon-input-dragDrop"><div class="Neon-input-inner"><div class="Neon-input-icon"><i class="fa fa-file-image-o"></i></div><div class="Neon-input-text"><h3>Drag&amp;Drop files here</h3> <span style="display:inline-block; margin: 15px 0">or</span></div><a class="Neon-input-choose-btn blue">Browse Files</a></div></div>
        </div>

        <div class="postInputContainer">
            <p class="errorText" v-if="errors.price !== ''">{{ errors.price }}</p>
            Price
            <input type="number" style="margin-top: 10px;" class="form-control postInput" :class="{ 'errorInput' : errors.price !== ''}" v-model="form.price" placeholder="Price">
        </div>

        <div>
            <ButtonCustom :loading="loading" :text="'Create'" @click.native="post"></ButtonCustom>
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
                    title: 'Adidasi',
                    description: 'Conditie buna , purtati o singura data',
                    price: 300,
                    picture: '',
                    size : 45
                },
                errors:{
                    title: '',
                    description: '',
                    price: '',
                    picture: '',
                    size : ''
                },
                success: '',
                loading: 0
            }
        },
        methods:{
            setPicture(e) {
                var input = event.target;
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = (e) => {
                        this.form.picture = e.target.result;
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            },
            post(){
                this.loading = 1;

                this.errors.title = '';
                this.errors.description = '';
                this.errors.price = '';
                this.errors.picture = '';

                this.success = '';

                axios.post('/api/posts/create', this.form)
                    .then(res=>{
                        this.success = res.data.message;
                        this.loading = 0;
                    })
                    .catch(err=>{
                        console.log(err);
                        this.loading = 0;

                        let list = err.response.data.errors;
                        this.errors.title = list.hasOwnProperty('title') ? list.title[0] : '';
                        this.errors.description = list.hasOwnProperty('description') ? list.description[0] : '';
                        this.errors.price = list.hasOwnProperty('price') ? list.price[0] : '';
                        this.errors.size = list.hasOwnProperty('size') ? list.size[0] : '';

                    })


            }
        },
        comments:{
            ButtonCustom
        }
    }
</script>

<style lang="scss" scoped>
    .postContainer{
        color: #73879C;
        margin: 5% auto 0;
        padding: 25px 50px 20px;
        text-align: center;
        text-shadow: 0 1px 0 rgba(147, 147, 147, 0.44);
        position: relative;
        width: 60%;
        background-color: white;
        border-radius: 10px;
        border: 2px solid #3490dc;
    }

    .postTitle{
        font: 400 25px Helvetica,Arial,sans-serif;
        letter-spacing: -.05em;
        line-height: 20px;
        margin: 10px 0 30px;
    }

    .postTitle:after,
    .postTitle:before{
        background: #7e7e7e;
        content: "";
        height: 1px;
        position: absolute;
        top: 47px;
        width: 25%;
    }

    .postTitle:after{
        right: 0;
    }

    .postTitle:before{
        left: 0;
    }

    .postInputContainer{
        margin-bottom: 20px;
    }

    .postInputDescription{
    }

    .postInput:focus{
        -ms-box-shadow: 0 0 2px 0 #d2d2d2, 0 0 5px 1px rgba(0, 0, 0, 0.08) inset;
        -o-box-shadow: 0 0 2px 0 #d2d2d2, 0 0 5px 1px rgba(0, 0, 0, 0.08) inset;
        box-shadow: 0 0 2px 0 #d2d2d2, 0 0 5px 1px rgba(0, 0, 0, 0.08) inset;
        border: 1px solid #d3bad1;
    }

    .postInput{
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

    .Neon {
        font-family: sans-serif;
        font-size: 14px;
        color: #494949;
        position: relative;


    }
    .Neon * {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }
    .Neon-input-dragDrop {
        display: block;
        width: 343px;
        margin: 0 auto 25px auto;
        padding: 25px;
        color: #8d9499;
        color: #97A1A8;
        background: #fff;
        border: 2px dashed #C8CBCE;
        text-align: center;
        -webkit-transition: box-shadow 0.3s, border-color 0.3s;
        -moz-transition: box-shadow 0.3s, border-color 0.3s;
        transition: box-shadow 0.3s, border-color 0.3s;
    }
    .Neon-input-dragDrop .Neon-input-icon {
        font-size: 48px;
        margin-top: -10px;
        -webkit-transition: all 0.3s ease;
        -moz-transition: all 0.3s ease;
        transition: all 0.3s ease;
    }
    .Neon-input-text h3 {
        margin: 0;
        font-size: 18px;
    }
    .Neon-input-text span {
        font-size: 12px;
    }
    .Neon-input-choose-btn.blue {
        color: #008BFF;
        border: 1px solid #008BFF;
    }
    .Neon-input-choose-btn {
        display: inline-block;
        padding: 8px 14px;
        outline: none;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        white-space: nowrap;
        font-size: 12px;
        font-weight: bold;
        color: #8d9496;
        border-radius: 3px;
        border: 1px solid #c6c6c6;
        vertical-align: middle;
        background-color: #fff;
        box-shadow: 0px 1px 5px rgba(0,0,0,0.05);
        -webkit-transition: all 0.2s;
        -moz-transition: all 0.2s;
        transition: all 0.2s;
    }
</style>
