export function initialize(store, router) {
    router.beforeEach((to, from, next) => {
        const currentUser = store.state.currentUser;

        const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
        const both = to.matched.some(record => record.meta.both);

        console.log(both);

        if(requiresAuth && currentUser==null) {
            next('/login');
        }
        else{
            if(!requiresAuth && currentUser!=null){
                if(both) {
                    next();
                }
                else
                    next('/');
            }
            else {
                switch (to.meta.role) {
                    case 'admin' :
                        if (currentUser.role != 2) {
                            next('/client/list');
                        }
                        break;
                    case 'user' :
                        next();
                        break;
                }
            }
        }

        next();
    });

    if (store.getters.currentUser) {
        setAuthorization(store.getters.currentUser.token);
    }
}

export function setAuthorization(token) {
    axios.defaults.headers.common["Authorization"] = `Bearer ${token}`
}

export function getLocalUser() {
    const userStr = localStorage.getItem("user");
    if(!userStr) {
        return null;
    }
    return JSON.parse(userStr);
}