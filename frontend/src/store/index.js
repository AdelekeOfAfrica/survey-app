import { createStore } from "vuex";

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
            return fetch(`http://localhost:8000/api/register`
            ,{
                headers:{
                    "Content-Type":"application/json",
                    Accept:"application/json",
                },
                method:"POST",
                body:JSON.stringify(user),
            })
            .then((res)=>res.json())
            .then((res)=>{
                commit("setUser",res);
                return res;
            });

        },
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