import axios from "axios";
let sendRequestWithBerarer=axios.create({
    baseURL:'http://127.0.0.1:8000/api/v1'
})
let userToken=localStorage.getItem('userToken');
sendRequestWithBerarer.defaults.headers.common['Authorization']='Bearer '+userToken;
export default sendRequestWithBerarer
