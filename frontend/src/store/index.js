import { createStore } from "vuex";
import axiosClient from "../views/axios";

const store = createStore({
    state:{
        user:{
            data:{},
            token:sessionStorage.getItem('TOKEN') //token will be saved in a session storage and we wont be logged out 
        }
    },
    getters:{},
    actions:{
        
        register({ commit },user) {
            return axiosClient.post('/register',user)
            .then(({data})=> {
                commit('setUser',data)
                return data
            })
        },
        login({commit},user){
            return axiosClient.post('/login',user)
           
            .then(({data})=> {
                commit('setUser',data)
                return data
            })

        },
        logout({commit}){
            return axiosClient.post('/logout')

            .then(response =>{
                commit('logout')
                return response 
            })
        }
    },
    mutations:{ 
        logout: (state) =>{ //this function is to change the state of user and token to empty 
            state.user.data = {};
            state.user.token = null;
        },
        setUser:(state, userData) =>{ //this function is to assigned data received from backend to the frontend
            state.user.data = userData.data
            state.user.token = userData.token
            sessionStorage.setItem('TOKEN',userData.token)

        }
    },
    modules:{}
})

export default store;