import LoginComponent from "../../Component/LoginComponent";
import RegisterComponent from "../../Component/RegisterComponent";
import {createRouter,createWebHistory} from "vue-router";
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
                let config={
                    headers: {
                        'Authorization': 'Bearer ' + token
                    }
                }
                axios.get('/api/v1/checkToken').then((IfTokenIsValid)=>{
                    if(!IfTokenIsValid){
                        store.commit('changeUserStatus',false);
                        localStorage.clear();
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
                let config={
                    headers: {
                        'Authorization': 'Bearer ' + token
                    }
                }
                axios.get('/api/v1/checkToken').then((IfTokenIsValid)=>{
                    if(!IfTokenIsValid){
                        localStorage.clear()
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
                let config={
                    headers: {
                        'Authorization': 'Bearer ' + token
                    }
                }
                axios.get('/api/v1/checkToken').then((IfTokenIsValid)=>{
                    if(!IfTokenIsValid){
                        store.commit('changeUserStatus',false)
                        localStorage.clear()
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
