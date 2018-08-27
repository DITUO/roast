import UserAPI from '../api/userTest.js'

export const userTests = {
    state: {
        userTest: {},
        userTestLoadStatus: 0
    },
    actions: {
        loadUserTest( { commit } ){
            commit('setUserTestLoadStatus',1);

            UserAPI.getUserTest()
                .then( function( response ){
                    commit('setUserTest',response.data);
                    commit('setUserTestLoadStatus',2);
                } )
                .catch( function( response ){
                    commit('setUserTest',response.data);
                    commit('setUserLoadStatus',3);
                } )
        }
    },
    motatons: {
        setUserTestLoadStatus(state,status){
            state.userTestLoadStatus = status;
        },
        setUserTest(state,userTest){
            state.userTest = userTest;
        }
    },
    getters: {
        getUserTestLoadStatus(state){
            return function(){
                return state.userTestLoadStatus;
            }
        },
        getUserTest(state){
            return state.userTest;
        }
    }
}