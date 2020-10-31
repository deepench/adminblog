import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

export default new Vuex.Store({
    state : {
        user: false,
         userPermission: null
    },
    getters: {
        // getCounter(state){

        
        // },

         getUserPermission(state){
             return state.userPermission
        },
    },
    mutations: {
        setUpdateUser(state, data){
            state.user = data
        },
        setUserPermission(state, data){
            state.userPermission = data
        },

    },

    actions :{
        // changeCounterAction({commit}, data){
        //     commit('changeTheCounter', data)
        // }
    }


})