import { createRouter, createWebHistory } from "vue-router";
import DefaultLayout from '../components/DefaultLayout.vue'
import AuthLayout from '../components/AuthLayout.vue'
import Dashboard from '../views/Dashboard.vue'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import Surveys from '../views/Surveys.vue'
import store from '../store/index.js'

const routes = [
    {
        path:'/login',
        name:'Login',
        component:Login
    }, 

    {
        path:'/auth',
        redirect:'/login',
        name:'Auth',
        component: AuthLayout,
        children:[
            {path:'/login', name:'Login', component:Login},
            {path:'/register', name:'Register', component:Register}
        ]
    },

    {
        path:'/register',
        name:'Register',
        component:Register
    }, 

    {
        path:'/',
        redirect:'/dashboard',
        name:'Dashboard',
        component:DefaultLayout,
        meta:{requireAuth:true},
        children:[
            
                {path:'/dashboard', name:'Dashboard', component:Dashboard},
                {path:'/surveys', name:'Survey', component:Surveys}
            
        ]
    },

];

const router = createRouter({
    history:createWebHistory(),
    routes
})

router.beforeEach((to,from,next)=>{
    if(to.meta.requireAuth && !store.state.user.token){
        next({name: 'Login'})
    } else if(store.state.user.token && (to.name === 'Login' || to.name === 'Register' )) {
         next({name: 'Dashboard'});
    } else {
        next()
    } 
})

export default router 