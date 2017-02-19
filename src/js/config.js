let config = {
  baseURL : '/taleastory',
  path : function(path){
    return config.baseURL + '/' + path;
  }
};

export default config;
