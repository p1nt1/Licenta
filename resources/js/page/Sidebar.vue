<template>
    <div>
        <i @click="active=!active" class="fa fa-bars navbar-brand ml-4 navbar_menu" style="cursor: pointer"></i>

        <vs-sidebar parent="body" default-index="1"  color="primary" class="sidebarx" spacer v-model="active">
            <div class="header-sidebar" slot="header">
                <vs-avatar size="70px" :src="currentUser.avatar"/>

                <h3>
                    {{currentUser.name}}
                </h3>
            </div>

            <div v-if="role==2">
                <vs-divider icon="person" position="left">
                </vs-divider>

                <vs-sidebar-item index="2" to="/user/list" class="items">
                    Users List
                </vs-sidebar-item>

                <vs-sidebar-item index="3" to="/user/create" class="items">
                    User Create
                </vs-sidebar-item>
            </div>

            <div v-if="role==2">
                <vs-divider icon="color_lens" position="left">
                </vs-divider>

                <vs-sidebar-item index="4" to="/color/list" class="items">
                    Color List
                </vs-sidebar-item>

                <vs-sidebar-item index="5" to="/color/create" class="items">
                    Color Create
                </vs-sidebar-item>
            </div>

            <vs-divider icon="people" position="left">
            </vs-divider>

            <vs-sidebar-item index="6" to="/client/list" class="items">
                Client List
            </vs-sidebar-item>

            <vs-divider icon="photo_album" position="left">
            </vs-divider>

            <vs-sidebar-item index="7" to="/picture/list" class="items">
                Picture List
            </vs-sidebar-item>

            <vs-sidebar-item index="8" to="/picture/create" class="items">
                Picture Create
            </vs-sidebar-item>

            <div class="footer-sidebar" slot="footer">
                <vs-button color="danger" type="flat" @click="logout" class="log_off_button"><i class="fa fa-power-off log_off"> </i> <strong>Logout</strong></vs-button>
            </div>
        </vs-sidebar>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                active : false
            }
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser
            },
            role(){
                return this.$store.getters.currentUser.role
            }
        },
        methods:{
            logout(){
                this.active = false;

                setTimeout(()=>{
                    this.$store.dispatch('logout');
                    this.$router.push('/');
                }, 300)
            }
        }
    }
</script>

<style>
    .header-sidebar{
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        width: 100%;
    }

    h4{
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
    }

    .navbar_menu {
      padding: 2px 16px;
      transition: all ease-out 0.325s;
    }

    .navbar_menu:hover {
      background-color: #c5bebe;
    }

    .footer-sidebar{
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }

    .vs-button.log_off_button {
      transition: all ease-in 0.1s;
    }

    .vs-button.log_off_button:hover {
      background-color: #ff0404 !important;
      color:white;
    }

    .vs-button.log_off_button:hover .log_off {
      border: solid 1.5px white;
    }

    .log_off {
      border: solid 1.5px red;
      border-radius: 15%;
      padding: 4px;
    }

</style>
