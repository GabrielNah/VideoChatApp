import LoginComponent from "../../Component/LoginComponent";
import RegisterComponent from "../../Component/RegisterComponent";
import {createRouter,createWebHistory} from "vue-router";
import sendRequestWithBerarer from "../axios/sendRequestWithBearer";
import Dashboard from "../../Component/Dashboard";
import store from "../store";
const routes=[
    {
        path:'/login',
        name:'Login',
        component:LoginComponent,
        meta:{authRequired:false},
    },
    {
        path:'/register',
        name:'Register',
        component:RegisterComponent,
        meta:{authRequired:false},
    }
    ,{
        path:'/dashboard',
        name:'dashboard',
        component:Dashboard,
        meta:{authRequired:true},
    }
]

const router = createRouter({
    // 4. Provide the history implementation to use. We are using the hash history for simplicity here.
    history: createWebHistory(),
    routes, // short for `routes: routes`
})
router.beforeEach(async (to,from,next)=>{
    if(to.meta.authRequired){
            if(localStorage.getItem('userToken') !== null){
                store.commit('changeUserStatus',true)
                return next()
            }


            else{
                store.commit('changeUserStatus',false);
                localStorage.removeItem('userToken')
                return next({path:'/login'})
            }



    }else {

            if(localStorage.getItem('userToken') !== null){
                store.commit('changeUserStatus',true)
                return next({path:'/dashboard'})
            }


            else {
                store.commit('changeUserStatus',false);
                localStorage.removeItem('userToken')
                return next()
            }


    }
})
export default router
