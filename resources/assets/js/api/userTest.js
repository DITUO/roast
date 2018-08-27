import { ROAST_CONFIG } from '../config.js'
import Axios from 'axios';

export default {
    getUserTest:function(){
        return Axios.get( ROAST_CONFIG.API_URL + '/userTest' );
    }
}