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
            app_id:"b5c8c3df2621406a80e2f2d616971085",
            token:"006b5c8c3df2621406a80e2f2d616971085IADAH/kLqJbqkgxl2TVhfLU8jkHmyAHma2M949tVJeTWu3lK1Y0AAAAAEAC8e3Fn25fXYgEAAQDbl9di",
            channel:"MyAppChannel",
            client:null,

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
