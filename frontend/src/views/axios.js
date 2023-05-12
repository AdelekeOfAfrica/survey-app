import axios from "axios"
import store from "../store"

const axiosClient = axios.create({
    baseURL:'http://localhost:8000/api' 
})
axiosClient.interceptors.request.use(config =>{
    config.headers.Authorization = `Bearer ${store.state.user.token}` //code is to get the token and store it in the user token
    return config
})

export default axiosClient