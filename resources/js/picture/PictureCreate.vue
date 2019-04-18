<template>
    <div class="container">
        <div class="pictureContainer" v-if="!pictureShow">
            <div class="oval"></div>
            <video autoplay ref="video"></video>

            <canvas style="display:none;" ref="canvas"></canvas>

            <a class="fa fa-camera cameraInput" @click="takePhoto"></a>
            <img src="/icons-static/palette.svg" class="palette" :style="{ 'border': '8px solid ' + selectedColor, 'background-color' : selectedColor}" @click="colorMenu1Display = !colorMenu1Display">

            <div class="color1Container" v-if="colorMenu1Display == true">
                <div class="colorDisplay" v-for="item in colors" :style="{ 'background-color': item.color_hex}" @click="changeColor(item)"></div>
            </div>
        </div>

        <div class="pictureContainer" v-else>
            <img :src="pictureUrl" class="picture" :style="loading==1 ? {'filter': 'blur(5px)'} : {}">

            <spinner-loading class="loader" :loading="loading"></spinner-loading>

            <div class="menuPicture">
                <img ref="color" v-if="menuPicture==1" src="/icons-static/palette.svg" class="palettePictureMenu" :style="{ 'border': '8px solid ' + selectedColor, 'background-color' : selectedColor}" @click="colorMenu2Display = !colorMenu2Display">
                <b-tooltip :target="() => $refs.color" title="Select new color!"></b-tooltip>

                <i ref="retry" v-if="menuPicture==1" class="fa fa-undo iconsMenu" @click="retry"></i>
                <b-tooltip :target="() => $refs.retry" title="Change color!" ></b-tooltip>

                <router-link ref="save" v-if="menuPicture==1" class="fa fa-save iconsMenu" to="/client/create/"></router-link>
                <b-tooltip :target="() => $refs.save" title="Save!"></b-tooltip>

                <i ref="back" v-if="menuPicture==1" class="fa fa-backward iconsMenu" @click="changeCamera"></i>
                <b-tooltip :target="() => $refs.back" title="Go Back!"></b-tooltip>

                <i ref="menu" class="fa fa-ellipsis-h iconsMenu" @click="menuPicture= !menuPicture"></i>
                <b-tooltip :target="() => $refs.menu" title="Menu"></b-tooltip>
            </div>

            <div class="color2Container" v-if="colorMenu2Display == true">
                <div class="colorDisplay" v-for="item in colors" :style="{ 'background-color': item.color_hex}" @click="changeColor(item)"></div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                colorMenu1Display: false,
                colorMenu2Display: false,
                colors: [],
                selectedColor:'#cacaca',
                menuPicture: 1,
                pictureShow: 0,
                pictureUrl: '',
                loading: 0
            }
        },
        mounted(){
            this.camera();

            axios.get('/api/color/getAll')
                .then(res=>{
                    this.colors = res.data;
                })
        },
        methods: {
            camera() {
                navigator.mediaDevices.getUserMedia({video: true}).then(stream => {
                    this.$refs['video'].srcObject = stream;

                })
                    .catch(err=>{
                        alert('Please enable webcam!');
                        this.$router.push('/client/list');
                    });
            },
            changeColor(item){
                this.colorMenu1Display = false;
                this.colorMenu2Display = false;
                this.selectedColor = item.color_hex;
            },
            takePhoto(){
                if(this.selectedColor == '#cacaca'){
                    alert("Please select a color first!");
                    return;
                }

                this.loading = 1;

                this.$refs['canvas'].width = this.$refs['video'].videoWidth;
                this.$refs['canvas'].height = this.$refs['video'].videoHeight;

                this.$refs['canvas'].getContext('2d').scale(-1,1);
                this.$refs['canvas'].getContext('2d').drawImage(this.$refs['video'],0,0, this.$refs['canvas'].width*-1, this.$refs['canvas'].height);

                this.pictureUrl = this.$refs['canvas'].toDataURL('image/png');
                this.pictureShow = 1;

                let color = this.hexToRGB(this.selectedColor, 0.3);

                axios.post('/api/picture/operation',
                    {
                        picture : this.$refs['canvas'].toDataURL('image/png'),
                        red : color.r,
                        green : color.g,
                        blue : color.b,
                        alpha: color.a
                    })
                    .then(res=>{
                        this.loading = 0;

                        this.$store.commit('photo',
                            {
                                user_photo: this.pictureUrl,
                                modified_user_photo: res.data,
                                color_modified_photo: this.selectedColor
                            });
                        this.pictureUrl = res.data;
                    })
                    .catch(err=>{
                        console.log(err);
                        this.loading = 0;
                    })
            },
            retry(){
                this.loading=1;

                let color = this.hexToRGB(this.selectedColor, 0.3);
                axios.post('/api/picture/operation',
                    {
                        picture : this.$store.getters.currentUserPhoto,
                        red : color.r,
                        green : color.g,
                        blue : color.b,
                        alpha: color.a
                    })
                    .then(res=>{
                        this.loading = 0;

                        this.$store.commit('photo',
                            {
                                user_photo: this.$store.getters.currentUserPhoto,
                                modified_user_photo: res.data,
                                color_modified_photo: this.selectedColor
                            });
                        this.pictureUrl = res.data;
                    })
                    .catch(err=>{
                        console.log(err);
                        this.loading = 0;
                    })
            },
            changeCamera(){
                this.pictureShow = 0;
                this.camera();
            },
            hexToRGB(hex, alpha) {
                var r = parseInt(hex.slice(1, 3), 16),
                    g = parseInt(hex.slice(3, 5), 16),
                    b = parseInt(hex.slice(5, 7), 16);

                return {
                    r : r,
                    g: g,
                    b: b,
                    a : alpha
                }
            }
        }
    }
</script>

<style scoped>
    .loader{
        width: 150px;
        height: 150px;
        position: absolute;
        z-index: 48px;
        left: 0;
        right: 0;
        top: 150px;
    }

    video{
        width: 100%;
        height: 700px;
        transform: scaleX(-1);
    }

    .picture{
        width: 90%;
        height: 100%;
    }

    @media only screen and (max-width: 572px) {
        video{
            height: 400px;
        }

        .picture{
            margin-top: 50px;
        }
    }

    .pictureContainer{
        text-align: center;
        position: relative;
    }

    .cameraInput{
        font-size: 35px;
        position: relative;
        bottom: 70px;
        background-color: #cacaca;
        border-radius: 50%;
        border: 10px solid #cacaca;
        cursor: pointer;
        text-decoration: none !important;
        color: black;
    }

    .oval {
        width: 255px;
        height: 355px;
        border: 3px solid red;
        position: absolute;
        left: 0;
        right: 0;
        margin: 168px auto;
        border-radius: 50%;
        z-index: 1;
    }

    .palette{
        width: 45px;
        height: 45px;
        position: relative;
        float: right;
        right: 120px;
        bottom: 65px;
        background-color: #cacaca;
        border-radius: 50%;
        border: 8px solid #cacaca;
        cursor: pointer;
    }

    .palettePictureMenu{
        width: 45px;
        height: 45px;
        position: relative;
        background-color: #cacaca;
        border-radius: 50%;
        border: 8px solid #cacaca;
        cursor: pointer;
        margin: 5px;
    }

    .color1Container{
        width: 193px;
        max-height: 169px;
        background-color: #fffafacc;
        bottom: 125px;
        position: absolute;
        right: 120px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        border-radius: 5px;
        overflow-y: scroll;
        justify-content: flex-start;
    }

    .color2Container{
        bottom: 111px;
        max-height: 169px;
        position: absolute;
        right: 153px;
        width: 193px;
        background-color: #fffafacc;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        border-radius: 5px;
        overflow-y: scroll;
        justify-content: flex-start;
    }

    .colorDisplay{
        width: 35px;
        height: 35px;
        border-radius: 50%;
        border: 1px solid black;
        cursor: pointer;
        margin: 10px;
    }

    .menuPicture{
        display: flex;
        flex-direction: row;
        position: absolute;
        right: 76px;
        bottom: 57px;
        flex-wrap: wrap;
    }

    .iconsMenu{
        font-size: 23px;
        position: relative;
        background-color: #cacaca;
        border-radius: 50%;
        border: 10px solid #cacaca;
        cursor: pointer;
        text-decoration: none !important;
        color: black;
        margin: 5px;
        width: 45px;
        height: 45px;
        line-height: 24px;
    }
</style>
