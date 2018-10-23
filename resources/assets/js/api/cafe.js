/**
 * Imports the Roast API URL from the config.
 */
import { ROAST_CONFIG } from '../config.js';

export default {
    /**
     * GET /api/v1/cafes
     */
    getCafes: function(){
        return axios.get( ROAST_CONFIG.API_URL + '/cafes' );
    },

    /**
     * GET /api/v1/cafes/{cafeID}
     */
    getCafe: function( cafeID ){
        return axios.get( ROAST_CONFIG.API_URL + '/cafes/' + cafeID );
    },

    /**
     * POST /api/v1/cafes
     */
    postAddNewCafe: function(name, locations, website, description, roaster, picture){
        var forData = new FormData();
        var json = JSON.stringify(locations) 
        console.log(json);
        forData.append('name',name);
        forData.append('locations',json);
        forData.append('website',website);
        forData.append('description',description);
        forData.append('roaster',roaster);
        forData.append('picture',picture);
        return axios.post( ROAST_CONFIG.API_URL + '/cafes',forData,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
        );
    },

    /**
     * 喜欢
     * POST  /api/v1/cafes/{cafeID}/like
     */
    postLikeCafe: function (cafeID) {
        return axios.post(ROAST_CONFIG.API_URL + '/cafes/' + cafeID + '/like');
    },

     /**
      * 不喜欢
    * DELETE /api/v1/cafes/{cafeID}/like
    */
    deleteLikeCafe: function (cafeID) {
        return axios.delete(ROAST_CONFIG.API_URL + '/cafes/' + cafeID + '/like');
    }
}