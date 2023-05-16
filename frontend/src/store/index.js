import { createStore } from "vuex";
import axiosClient from "../views/axios";

const tmpSurveys = [
    {
        id:100,
        title:"basic life survey ",
        slug:"basic-life-survey",
        status:"draft",
        image:"https://www.shutterstock.com/image-vector/your-opinion-matters-symbol-survey-260nw-1937069089.jpg",
        description:"Basic Life Support, or BLS, generally refers to the type of care that first-responders, healthcare providers and public safety professionals provide to anyone who is experiencing cardiac arrest, respiratory distress or an obstructed airway. It requires knowledge and skills in cardiopulmonary resuscitation (CPR), using automated external defibrillators (AED) and relieving airway obstructions in patients of every age.",
        create_at:"2021-02-12 18:00:00",
        updated_at:"2021-02-12 18:00:00",
        expiry_date:"2021-02-12 18:00:00",
        questions:[
            {
            id:1,
            type:"select",
            question:"which country are you from",
            description:"by the special grace of God these development stuff i go sabi am pah ",
            data:{
                options:[
                    {uuid:"f8af96f2-1d80-4632-9e9e-b560670e52ea", text:"USA"},
                    {uuid:"r8af96u2-1z20-4682-9e7e-2560670egg6a", text:"georia"},
                ],
            },
        },

        {
            id:2,
            type:"checkbox",
            question:"which programming language can you use",
            description:"by the special grace of God these development stuff i go sabi am pah ",
            data:{
                options:[
                    {uuid:"f8af96f2-1d80-4632-9e9e-b560670e52ea", text:"php"},
                    {uuid:"r8af96u2-1z20-4682-9e7e-2560670egg6a", text:"javascript"},
                ],
            },
        }
    ],
    },
    {
        id:200,
        title:"healthcare Assistant training ",
        slug:"healthcare-assistant-training",
        status:"draft",
        image:"https://media.istockphoto.com/id/1326067203/photo/survey-message-and-megaphone.jpg?b=1&s=170667a&w=0&k=20&c=RF6ZwXYxsUXYnP5Zq8Eg1IMNrkk-fKluz4bzs2PZ9kU=",
        description:"Healthcare assistants make sure the patient experience is as comfortable and stress-free as possible. It can also be the stepping stone into many other NHS roles. ",
        create_at:"2021-02-12 18:00:00",
        updated_at:"2021-02-12 18:00:00",
        expiry_date:"2021-02-12 18:00:00",
        questions:[
            {
            id:1,
            type:"select",
            question:"which country are you from",
            description:"by the special grace of God these development stuff i go sabi am pah ",
            data:{
                options:[
                    {uuid:"f8af96f2-1d80-4632-9e9e-b560670e52ea", text:"USA"},
                    {uuid:"r8af96u2-1z20-4682-9e7e-2560670egg6a", text:"georia"},
                ],
            },
        },

        {
            id:2,
            type:"checkbox",
            question:"which programming language can you use",
            description:"by the special grace of God these development stuff i go sabi am pah ",
            data:{
                options:[
                    {uuid:"f8af96f2-1d80-4632-9e9e-b560670e52ea", text:"php"},
                    {uuid:"r8af96u2-1z20-4682-9e7e-2560670egg6a", text:"javascript"},
                ],
            },
        }
    ],
    }

];

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
        surveys:[...tmpSurveys],
        questionTypes:["text","select","radio","checkbox","textarea"],
    },
    getters:{},
    actions:{
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
                    return res
                })
            } else{
                response = axiosClient.post("/survey", survey).then((res) =>{
                    commit("saveSurvey",res.data);
                    return res;

                })
                }
                return response
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
        setCurrentSurvey:(state, survey)=>{
            state.currentSurvey.data= survey.data
        },
        saveSurvey:(state, survey) =>{
            state.surveys =[...state.surveys, survey.data];
        },
        updateSurvey:(state,survey)=>{
            state.surveys = state.surveys.map((s) =>{
                if(s.id == survey.data.id){
                    return survey.data
                }
                return s
            })

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