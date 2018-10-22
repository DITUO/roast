import UserAPI from '../api/user.js'

export const users = {
    state: {
        user: {},
        userLoadStatus: 0,
        userUpdateStatus: 0
    },
    actions: {
        loadUser( { commit } ){
            commit( 'setUserLoadStatus',1 );

            UserAPI.getUser()
                .then( function( response ){
                    commit('setUser',response.data);
                    commit('setUserLoadStatus',2);
                } )
                .catch( function(){
                    commit('setUser',response.data);
                    commit('setUserLoadStatus',3);
                } )
        },
        editUser({commit,state,dispatch},data){
            commit('setUserUpdateStatus',1);

            UserAPI.putUpdateUser(data.public_visibility, data.favorite_coffee, data.flavor_notes, data.city, data.state)
                .then(function(resposne){
                    commit('setUserUpdateStatus',2);
                    dispatch('loadUser');
                })
                .catch(function(response){
                    commit('setUserUpdateStatus',3);
                })
        }
    },
    mutations: {
        setUserLoadStatus(state,status){
            state.userLoadStatus = status;
        },
        setUser(state,user){
            state.user = user;
        },
        setUserUpdateStatus(state,status){
            state.userUpdateStatus = status;
        }
    },
    getters: {
        getUserLoadStatus( state ){
            return function(){
                return state.userLoadStatus;
            }
        },
        getUser(state){
            return state.user;
        },
        getUserLoadStatus(state){
            return state.userUpdateStatus;
        }
    }
}