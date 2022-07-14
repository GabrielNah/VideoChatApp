import {createStore} from "vuex";
import router from "../router";
import Echo from "laravel-echo";
import sendRequestWithBerarer from "../axios/sendRequestWithBearer";
const store = createStore({
    state () {
        return {
            UserLogedIn: false,
            registerRequestUrl:'api/v1/register',
            loginRequestUrl:'api/v1/login',

        }
    },
    mutations: {
        changeUserStatus (state,Userstate) {
            state.UserLogedIn=Userstate
        },

    },
    actions:{
       async login({ commit, state },{credentials}){
           axios.post(state.loginRequestUrl,credentials).then((userData)=>{
               console.log(userData)
               if(userData.status==200){
                   localStorage.setItem('userToken',userData.data.token)
                   commit('changeUserStatus',true);
                   router.push({path:'/dashboard'})
               }
           })

        },
        async registration({ commit, state },{credentials}){
           console.log(credentials)
           axios.post(state.registerRequestUrl,credentials).then((userData)=>{
               console.log(userData)
               if(userData.status==201){
                   localStorage.setItem('userToken',userData.data.userToken)
                   commit('changeUserStatus',true);
                   router.push({path:'/dashboard'})
               }
           })


        },
        async logout({ commit, state }){
           let token=localStorage.getItem('userToken');
            sendRequestWithBerarer.post('/logout').then((IfEverythingWentRight)=>{
                    if (IfEverythingWentRight){
                        localStorage.clear();
                        commit('changeUserStatus',false);
                        router.push({path:'/login'})
                    }
            })

        }
    }
})
export default store
