import NotFound from './page/NotFound'

import Login from './auth/Login.vue';
import ChangePassword from './auth/ChangePassword.vue';
import ChangerTester from './auth/ChangerTester.vue';
import ForgotPassword from './auth/ForgotPassword.vue';
import ChangePasswordUser from './auth/ChangePasswordUser.vue';
import VerifyAccount from './auth/VerifyAccount.vue';

import UserList from './user/UserList.vue';
import HandleUser from './user/HandleUser.vue';
import CreateUser from './user/CreateUser.vue';

import ColorList from './color/ColorList';
import ColorCreate from './color/ColorCreate';
import HandleColor from './color/HandleColor';

import ClientList from './client/ClientList';
import ClientCreate from './client/ClientCreate';

import PictureList from './picture/PictureList';
import PictureCreate from './picture/PictureCreate';
import PictureHandle from './picture/PictureHandle';

export const routes = [
    {
        path: '/',
        component: Login
    },
    {
        path: '/forgot',
        component: ForgotPassword
    },
    {
        path: '/change-password/:token',
        component: ChangePassword
    },
    {
        path: '/picker',
        component: ChangerTester,
    },
    {
        path: '/change-password-user',
        component: ChangePasswordUser,
        meta:{
            requiresAuth: true
        }
    },
    {
        path: '/verify/:token',
        component: VerifyAccount
    },
    {
        path: '/user/list',
        component: UserList,
        meta:{
            requiresAuth: true,
            role: 'admin'
        }
    },
    {
        path: '/user/handle/:id',
        component: HandleUser,
        meta:{
            requiresAuth: true,
            role: 'admin'
        }
    },
    {
        path: '/user/create',
        component: CreateUser,
        meta:{
            requiresAuth: true,
            role: 'admin'
        }
    },
    {
        path: '/color/list',
        component: ColorList,
        meta:{
            requiresAuth: true,
            role: 'admin'
        }
    },
    {
        path: '/color/handle/:id',
        component: HandleColor,
        meta:{
            requiresAuth: true,
            role: 'admin'
        }
    },
    {
        path: '/color/create',
        component: ColorCreate,
        meta:{
            requiresAuth: true,
            role: 'admin'
        }
    },

    {
        path: '/client/list',
        component: ClientList,
        meta:{
            requiresAuth: true,
            role: 'user'
        }
    },
    {
        path: '/client/create',
        component: ClientCreate,
        meta:{
            requiresAuth: true,
            role: 'user'
        }
    },

    {
        path: '/picture/list',
        component: PictureList,
        meta:{
            requiresAuth: true,
            role: 'user'
        }
    },
    {
        path: '/picture/create',
        component: PictureCreate,
        meta:{
            requiresAuth: true,
            role: 'user'
        }
    },
    {
        path: '/picture/handle',
        component: PictureHandle,
        meta:{
            requiresAuth: true,
            role: 'user'
        }
    },
    {
        path: '*',
        component: NotFound
    }
];