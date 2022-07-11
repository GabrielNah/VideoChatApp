require('./bootstrap');
import {createApp} from "vue"
import store from "./store";
import router from "./router";
import MainComponent from "../Component/MainComponent";
const app=createApp(MainComponent)
app.use(router).use(store).mount('#app')
