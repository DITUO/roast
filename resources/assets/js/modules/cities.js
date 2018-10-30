import CityAPI from '../api/city.js';

export const cities = {
    state: {
        cities: [],
        citiesLoadStatus: 0,

        city: {},
        cityLoadStatus: 0
    },

    actions: {
        /**
         * 获取所有城市
         * @param {*} param0 
         */
        loadCities( { commit } ){
            commit('setCitiesLoadStatus',1);

            CityAPI.getCities()
                .then(function(response){
                    commit('setCities',response.data);
                    commit('setCitiesLoadStatus',2);
                })
                .catch(function(){
                    commit('setCities',[]);
                    commit('setCitiesLoadStatus',3);
                });
        },
        /**
         * 获取指定城市
         */
        loadCity( { commit },data ){
            commit('setCityLoadStatus',1);

            CityAPI.getCity(data.id)
                .then(function(response){
                    commit('setCity',response.data);
                    commit('setCityLoadStatus',2);
                })
                .catch(function(){
                    commit('setCity',{});
                    commit('setCityLoadStatus',3);
                });
        }
    },

    mutations: {
        setCities(state,cities){
            state.cities = cities;
        },

        setCitiesLoadStatus(state,status){
            state.citiesLoadStatus = status;
        },

        setCity(state,city){
            state.city = city;
        },

        setCityLoadStatus(state,status){
            state.cityLoadStatus = status;
        }
    },

    getters: {
        getCities(state){
            return state.cities;
        },

        getCitiesLoadStatus(state){
            return state.citiesLoadStatus;
        },

        getCity(state){
            return state.city;
        },

        getCityLoadStatus(state){
            return state.cityLoadStatus;
        }
    }
}