class GlobalBack{
  static get(field){
    if(typeof document._globalBackValue == "undefined")
      return '';
    if(!(field in document._globalBackValue))
      return '';
    return document._globalBackValue[field];
  }
  static set(field, value){
     document._globalBackValue[field] = value;
  }
  static setObject(fields){
    for (let key in fields){
      if (fields.hasOwnProperty(key)) {
         GlobalBack.set(key, fields[key]);
       }
    }
  }
}
document.globalBack = GlobalBack;
document._globalBackValue = {};
export {GlobalBack};
