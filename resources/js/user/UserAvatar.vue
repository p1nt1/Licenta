<template>
    <div class="container">
        <p>Upload or select an old avatar</p>

        <button class="btn btn-info" @click="oldAvatars = !oldAvatars">
            <span v-if="!oldAvatars">Old avatars</span>
            <span v-if="oldAvatars">Upload</span>
        </button>



        <div class="row">
            <div class="col-md-4" v-if="!oldAvatars">
                <vue-avatar
                        :width=150
                        :height=150
                        :border=10
                        ref="vueavatar"
                        @vue-avatar-editor:image-ready="onImageReady"
                        :image="preview"
                >
                </vue-avatar>
                <vue-avatar-scale
                        ref="vueavatarscale"
                        @vue-avatar-editor-scale:change-scale="onChangeScale"
                        :width=170
                        :min=1
                        :max=3
                        :step=0.02
                >
                </vue-avatar-scale>
            </div>
            <div class="col-md-4 images" v-if="oldAvatars">
                <div v-for="image in images">
                    <i class="fa fa-trash deleteAvatar" @click="deleteOldAvatar(image)"></i>
                    <img :src="`/storage/${image}`" class="image" @click="selectOldAvatar(image)">
                </div>
            </div>

            <div class="col-md-4">
                <img :src="preview" class="avatarBig">
            </div>
            <div class="col-md-4">
                <img :src="preview" class="avatarSmall">
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-info" @click="saveClicked">Change Picture</button>
                <button class="btn btn-danger" @click="done">Cancel</button>
            </div>
        </div>

        <br>
    </div>

</template>

<script>
    import VueAvatar from './avatar-editor/VueAvatar'

    import VueAvatarScale from './avatar-editor/VueAvatarScale'

    export default {
        components: {
            VueAvatar,

            VueAvatarScale
        },
        data(){
            return{
                images: [],
                preview: '/storage/avatar/avatar-upload.png',
                avatar: '',
                oldAvatars: 0
            }
        },
        created(){
            axios.get('/api/user/pictures/1')
            .then((res)=>{
                this.images = res.data.pictures;
            })
        },
        methods:{
            onChangeScale (scale) {
                this.$refs.vueavatar.changeScale(scale);
                this.preview = this.$refs.vueavatar.getImageScaled().toDataURL();
            },
            saveClicked(){
                if(this.preview != '/storage/avatar/avatar-upload.png') {
                    if(this.oldAvatars){
                        axios.post('/api/user/avatar/change', {id: this.$store.getters.currentUser.id, avatar:this.preview})
                            .then(res => {
                                this.$store.getters.currentUser.avatar = this.preview;
                                this.$store.commit('updateUser', this.$store.getters.currentUser);

                                this.$emit('doneAvatar');
                            })
                            .catch(err => {

                            })
                    }
                    else{
                        const formData = new FormData();

                        formData.append('image', this.$refs.vueavatar.getImageScaled().toDataURL());
                        formData.append('id', this.$store.getters.currentUser.id);

                        axios.post('/api/user/avatar', formData)
                            .then(res => {
                                this.$store.getters.currentUser.avatar = res.data.path;
                                this.$store.commit('updateUser', this.$store.getters.currentUser);

                                this.$emit('doneAvatar');
                            })
                            .catch(err => {

                            })
                    }
                }
            },
            onImageReady(scale){
                this.$refs.vueavatarscale.setScale(scale);
                this.preview = this.$refs.vueavatar.getImageScaled().toDataURL();
            },
            done(){
                this.$emit('doneAvatar');
            },
            selectOldAvatar(image){
                this.preview = '/storage/' + image;
            },
            deleteOldAvatar(image){
                axios.post('/api/user/pictures/delete/', {name: image})
                    .then(res=>{
                        console.log(res);
                    })
                    .catch(err=>{
                        console.log(err);
                    })
            }
        }
    }
</script>

<style scoped>
    .images{
        height: 158px;
        overflow-y: scroll;
        float: left;
        overflow-x: hidden;
    }

    .image{
        margin: 3px;
        border: 1px solid black;
        cursor: pointer;
        width: 120px;
     }

    .avatarBig{
        border: 1px solid black;
        width: 150px;
    }

    .avatarSmall{
        width: 60px;
        border-radius: 50%;
    }

    .deleteAvatar{
        position: relative;
        top: 34px;
        left: 100px;
        cursor: pointer;
        font-size: 20px;
    }
</style>