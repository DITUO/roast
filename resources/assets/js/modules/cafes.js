/*
|-------------------------------------------------------------------------------
| VUEX modules/cafes.js
|-------------------------------------------------------------------------------
| The Vuex data store for the cafes
*/

import CafeAPI from '../api/cafe.js';

export const cafes = {
    state: {
        cafes: [],
        cafesLoadStatus: 0,

        cafe: {},
        cafeLoadStatus: 0,

        user:{
            avatar : 'https://sfault-avatar.b0.upaiyun.com/147/223/147223148-573297d0913c5_huge256',
        },
        userLoadStatus: 0,
    },

    actions:{
        loadCafes( { commit } ){
            commit('setCafesLoadStatus',1);
            CafeAPI.getCafes()
                .then( function(response){
                    commit('setCafes',response.data);
                    commit('setCafesLoadStatus',);
                })
                .catch( function(){
                    commit('setCafes',[]);
                    commit('setCafesLoadStatus',3);
                })
        },
        loadCafe( { commit },data ){
            commit( 'setCafeLoadStatus', 1 );

            CafeAPI.getCafe( data.id )
                .then( function( response ){
                    commit( 'setCafe', response.data );
                    commit( 'setCafeLoadStatus', 2 );
                })
                .catch( function(){
                    commit( 'setCafe', {} );
                    commit( 'setCafeLoadStatus', 3 );
                });
        },
        loadUser( { commit }){
            commit( 'setUserLoadStatus', 1 );

            CafeAPI.getUser()
                .then( function( response ){
                    commit( 'setUser', user);
                    commit( 'setUserLoadStatus', 2 );
                })
                .catch( function(){
                    commit( 'setUser', {} );
                    commit( 'setUserLoadStatus', 3 );
                });
        },

    },
    mutations: {
        setCafesLoadStatus( state, status ){
          state.cafesLoadStatus = status;
        },
    
        setCafes( state, cafes ){
          state.cafes = cafes;
        },
    
        setCafeLoadStatus( state, status ){
          state.cafeLoadStatus = status;
        },
    
        setCafe( state, cafe ){
          state.cafe = cafe;
        },

        setUserLoadStatus( state, status ){
          state.userLoadStatus = status;
        },
      
        setUser( state, user ){
          state.user = user;
        }
    },
    getters: {
        getCafesLoadStatus( state ){
          return state.cafesLoadStatus;
        },
    
        getCafes( state ){
          return state.cafes;
        },
    
        getCafeLoadStatus( state ){
          return state.cafeLoadStatus;
        },
    
        getCafe( state ){
          return state.cafe;
        }
    }
};

