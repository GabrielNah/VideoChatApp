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
        beforeEnter:(to,from)=>{
            let token=localStorage.getItem('userToken');
            if (token == null){
                store.commit('changeUserStatus',false)
                return true;
            }else {
                sendRequestWithBerarer.get('/checkToken').then((IfTokenIsValid)=>{
                    if(!IfTokenIsValid){
                        store.commit('changeUserStatus',false);
                        localStorage.removeItem('userToken');
                        return true;
                    }else {
                        store.commit('changeUserStatus',true)
                        router.push({path:'/dashboard'});

                    }
                })
            }
        }
    },
    {
        path:'/register',
        name:'Register',
        component:RegisterComponent,
        beforeEnter:(to,from)=>{
            let token=localStorage.getItem('userToken');
            if (token == null){
                store.commit('changeUserStatus',false)
                return true;
            }else {

                sendRequestWithBerarer.get('/checkToken').then((IfTokenIsValid)=>{
                    if(!IfTokenIsValid){
                        localStorage.removeItem('userToken');
                        store.commit('changeUserStatus',false)
                        return true;
                    }else {
                        store.commit('changeUserStatus',true)
                        router.push({path:'/dashboard'})
                    }
                })
            }
        }
    }
    ,{
        path:'/dashboard',
        name:'dashboard',
        component:Dashboard,
        beforeEnter:(to,from)=>{
            let token=localStorage.getItem('userToken');
            if (token == null){
                store.commit('changeUserStatus',false)
               router.push({path:'/login'})
            }else {
                sendRequestWithBerarer.get('/checkToken').then((IfTokenIsValid)=>{
                    if(!IfTokenIsValid){
                        store.commit('changeUserStatus',false)
                        localStorage.removeItem('userToken')
                        router.push({path:'/login'})
                    }else {
                        store.commit('changeUserStatus',true)
                        return true;
                    }
                })
            }
        }
    }
]

const router = createRouter({
    // 4. Provide the history implementation to use. We are using the hash history for simplicity here.
    history: createWebHistory(),
    routes, // short for `routes: routes`
})
export default router
