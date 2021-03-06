import {GlobalBack} from './utils/interfaceback';

let config = {
  baseURL : '/taleastory',
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
