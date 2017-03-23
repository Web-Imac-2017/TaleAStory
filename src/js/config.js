import {GlobalBack} from './utils/interfaceback';

let config = {
  baseURL : 'http://taleastoryv1.anfraylapeyre.com',
  path : function(path){
    return this.baseURL + '/' + path;
  },
  imagePath : function(path){
    	return this.path('assets/images/' + path);
  },
  soundPath : function(path){
    	return this.path('assets/sounds/' + path);
  }
};

export default config;
