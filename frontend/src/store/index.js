import { createStore } from "vuex";
import axiosClient from "../views/axios";

const tmpSurveys =[
    {
        id:1000,
        title:"michael adeleke channel",
        slug:"michaek-adeleke-channel",
        status:"draft",
        image:"https://www.google.com/imgres?imgurl=https%3A%2F%2Fwww.voxco.com%2Fwp-content%2Fuploads%2F2021%2F04%2Fstudents-feedback-survey-cvr.jpg&tbnid=fWibG_-20__LOM&vet=12ahUKEwjQhqzyxfD-AhUWmycCHdgeDFcQMygBegUIARDgAQ..i&imgrefurl=https%3A%2F%2Fwww.voxco.com%2Fblog%2Fstudent-survey%2F&docid=txOCa__RmKdTDM&w=1200&h=628&q=survey&ved=2ahUKEwjQhqzyxfD-AhUWmycCHdgeDFcQMygBegUIARDgAQ",
        description:"testing this for the first time",
        created_at:"22-01-20, 15:00:00",
        updated_at:"22-01-20, 15:00:00",
        expire_at:"22-01-22,15:00:0)",
        questions:[
            {
                id:1,
                type:"select",
                question:"from which country are you taking this surve from",
                description:null,
                data:{
                    options:[
                        {uudi:"fnwfkffnf222",text:"usa"},
                        {uudi:"fnwfkffnf222",text:"africa"},
                        {uudi:"fnwfkffnf222",text:"nigeria"},
                    ],
                },
            },
            {
                id:2,
                type:"checkbbox",
                question:"which code do you like most",
                description:null,
                data:{
                    options:[
                        {uudi:"fnwfkffnf222",text:"php"},
                        {uudi:"fnwfkffnf222",text:"java"},
                        {uudi:"fnwfkffnf222",text:"python"},
                    ],
                },
            },
            {
                id:3,
                type:"select",
                question:"from which country are you taking this surve from",
                description:null,
                data:{
                    options:[
                        {uudi:"fnwfkffnf222",text:"usa"},
                        {uudi:"fnwfkffnf222",text:"africa"},
                        {uudi:"fnwfkffnf222",text:"nigeria"},
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
        }
    },
    survey:[...tmpSurveys],
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