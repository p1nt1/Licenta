import { getLocalUser } from "./helpers/general";

const user = getLocalUser();

export default {
    state: {
        currentUser: user,
        currentUserPhoto: '',
        modifiedUserPhoto: '',
        colorModifiedPhoto: ''

    },
    getters: {
        currentUser(state) {
            state.currentUser = getLocalUser();
            return state.currentUser;
        },
        currentUserPhoto(state){
            return state.currentUserPhoto;
        },
        modifiedUserPhoto(state){
            return state.modifiedUserPhoto;
        },
        colorModifiedPhoto(state){
            return state.colorModifiedPhoto;
        }
    },
    actions:{
        logout(context) {
            localStorage.removeItem("user");
            context.commit('deleteUser');
        },
    },
    mutations: {
        deleteUser(state){
            state.currentUser = null;
        },
        login(state, payload) {
            state.isLoggedIn = true;
            state.currentUser = Object.assign({}, payload.user, {token: payload.access_token, expires:payload.expires_at});
            localStorage.setItem("user", JSON.stringify(state.currentUser));
        },

        photo(state, payload){
            state.currentUserPhoto = payload.user_photo;
            state.modifiedUserPhoto = payload.modified_user_photo;
            state.colorModifiedPhoto = payload.color_modified_photo;
        }
    }
};
