import fetch from 'isomorphic-fetch';
import Promise from 'es6-promise';
import config from '../config';
import {GlobalBack} from '../utils/interfaceback';


class Step{
  constructor(IDStep, ImgPath, Body, Question, IDType, Title){
    this.IDStep = IDStep;
    this.ImgPath = ImgPath;
    this.Body = Body;
    this.Question = Question;
    this.IDType = IDType;
    this.Title = Title;
  }
}

export {Step};
export default Step;
