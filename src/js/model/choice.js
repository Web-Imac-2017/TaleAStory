import fetch from 'isomorphic-fetch';
import Promise from 'es6-promise';
import config from '../config';
import {GlobalBack} from '../utils/interfaceback';


class Choice{
  constructor(IDChoice, Answer, IDStep, TransitionStep, IDNextStep){
    this.IDChoice = IDChoice;
    this.Answer = Answer;
    this.IDStep = IDStep;
    this.TransitionStep = TransitionStep;
    this.IDNextStep = IDNextStep;
  }
}

export {Choice};
export default Choice;
