import { ROAST_CONFIG } from '../config.js';
import axios from 'axios';

export default {
    /**
     * 获取所有城市
     */
    getCities: function(){
        return axios.get(ROAST_CONFIG.API_URL + '/cities');
    },

    /**
     * 获取指定城市
     */
    getCity: function(id){
        return axios.get(ROAST_CONFIG.API_URL + '/cities/' + id);
    }
}