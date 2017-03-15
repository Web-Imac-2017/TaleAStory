import fetch from 'isomorphic-fetch';
import Promise from 'es6-promise';
import config from '../config';
import {GlobalBack} from '../utils/interfaceback';


class Achievement{
  constructor(IDPlayer, IDAchievement, isRead){
    this.IDPlayer = IDPlayer;
    this.IDAchievement = IDAchievement;
    this.isRead = isRead;
  }
}


export {Achievement};
export default Achievement;
