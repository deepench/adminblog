import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router);
import mainapp from './components/mainapp'
import dashboard from './components/Dashboard';
import profile from './components/Profile';
import tag from './components/Tag';
import category from './components/Category';
import user from './components/User';
import login from './components/login';
import role from './components/role';
import assignrole from './components/assignrole';
 import post from './components/createPost';
const routes = [
    // { path: '/', component: mainapp },
    {
        path: '/',
        component: dashboard,
        name:'dashboard'
    },
    {
        path: '/profile',
        component: profile,
        name:'profile'
    },
    {
        path: '/tags',
        component: tag,
        name:'tags'
    },
    {
        path: '/category',
        component: category,
        name:'category'
    },
    {
        path: '/user',
        component: user,
        name:'user'
    },
    {
        path: '/login',
        component: login,
        name:'login'
    },
        {
        path: '/role',
        component: role,
        name:'role'
    },
         {
        path: '/assignrole',
         component: assignrole,
         name:'assignrole'
    },
          {
        path: '/createPost',
        component: post,
        name:'createPost'
    }


]

export default new Router({
    mode: 'history',
    routes
});

