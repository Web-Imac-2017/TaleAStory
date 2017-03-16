import fetch from 'isomorphic-fetch';
import Promise from 'es6-promise';
import config from '../config';
import {GlobalBack} from '../utils/interfaceback';


class Step{
  constructor(id, ImgPath, Body, Question, IDType, Title){
    this.id = id;
    this.ImgPath = ImgPath;
    this.Body = Body;
    this.Question = Question;
    this.IDType = IDType;
    this.Title = Title;
  }

  display() {
    return <div className="insideItem">
                <img className="element" src={config.imagePath(this.ImgPath)}/>
                <img className="element" src={config.imagePath('wave_large.png')}/>
                <h3 className="userName">this.Title</h3>
              </div>
  }
  
}

export {Step};
export default Step;
