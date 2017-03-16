import fetch from 'isomorphic-fetch';
import Promise from 'es6-promise';
import config from '../config';
import {GlobalBack} from '../utils/interfaceback';


class Achievement{
  constructor(id, Name, ImgPath, Brief){
    this.id = id;
    this.Name = Name;
    this.ImgPath = ImgPath;
    this.Brief = Brief;
  }

  display() {
    return <div className="insideItem">
                <img className="element" src={config.imagePath(this.ImgPath)}/>
                <img className="element" src={config.imagePath('wave_large.png')}/>
                <h3 className="userName">this.Name</h3>
              </div>
  }

}


export {Achievement};
export default Achievement;
