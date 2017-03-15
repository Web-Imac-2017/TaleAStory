import fetch from 'isomorphic-fetch';
import Promise from 'es6-promise';
import config from '../config';
import {GlobalBack} from '../utils/interfaceback';


class Item{
  constructor(IDItem, Name, ImgPath, Brief){
    this.IDItem = IDItem;
    this.Name = Name;
    this.ImgPath = ImgPath;
    this.Brief = Brief;
  }
}

export {Item};
export default Item;
