import {ROAST_CONFIG} from '../config.js'

export default {
    getBrewMethods: function(){
        return axios.get(ROAST_CONFIG.API_URL + '/brew-methods');
    }
}