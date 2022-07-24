import axios from "axios";
let sendRequestWithBerarer=axios.create({
    baseURL:'http://127.0.0.1:8000/api/v1'
})
sendRequestWithBerarer.interceptors.request.use(function (config) {
    let userToken=localStorage.getItem('userToken');
    config.headers.common['Authorization']='Bearer '+userToken;
    return config;
})
export default sendRequestWithBerarer
