import { createStore } from "vuex";
import axiosClient from "../views/axios";



const store = createStore({
    state:{
        user:{
            data:{},
            token:sessionStorage.getItem('TOKEN') //token will be saved in a session storage and we wont be logged out 
        },
        currentSurvey:{
            loading:false,
            data:{

            }
        },
        surveys:{
            loading:false,
            data:[]
        },
        questionTypes:["text","select","radio","checkbox","textarea"],
    },
    getters:{},
    actions:{
        deleteSurvey({}, id){
            return axiosClient.delete(`/survey/${id}`)
    },
        getSurvey({commit},id){
            commit("setCurrentSurveyLoading",true)
            return axiosClient
            .get(`/survey/${id}`)
            .then((res) => {
                commit("setCurrentSurvey",res.data)
                commit("setCurrentSurveyLoading",false)
                return res  
            })
            .catch((err) => {
                commit ("setCurrentSurveyLoading",false)
                throw err
            })
        },
        saveSurvey({commit}, survey){
            delete survey.image_url
            let response;
            if (survey.id){
                response = axiosClient
                .put(`/survey/${survey.id}`,survey)
                .then((res)=>{
                    commit("setCurrentSurvey", res.data);
                    return res
                })
            } else{
                response = axiosClient.post("/survey", survey).then((res) =>{
                    commit("setCurrentSurvey",res.data);
                    return res;

                })
                }
                return response
            }, 

            getSurveys({commit}){
                commit('setSurveysLoading',true)
                return axiosClient.get(`/survey`).then((res)=>{
                    commit('setSurveysLoading',false)
                    commit('setSurveys',res.data)
                    return res;
                })
            },
      
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
                commit('setUser',data) //data is the result being fetched 
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
        setCurrentSurveyLoading:(state,loading) =>{
            state.currentSurvey.loading = loading

        },
        setCurrentSurveysLoading:(state,loading) =>{
            state.surveys.loading = loading

        },
        setCurrentSurvey:(state, survey)=>{
            state.currentSurvey.data= survey.data
        },
        setSurveys:(state,survey) => {
            state.surveys.data = survey.data
        },
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