import fetch from 'isomorphic-fetch';
import Promise from 'es6-promise';
import config from '../config';
import {GlobalBack} from '../utils/interfaceback';


class Choice{
  constructor(id, Answer, IDStep, TransitionStep, IDNextStep){
    this.id = id;
    this.Answer = Answer;
    this.IDStep = IDStep;
    this.TransitionStep = TransitionStep;
    this.IDNextStep = IDNextStep;
  }

  display() {
    return <div className="insideItem">
                <h3 className="userName">this.Answer</h3>
              </div>
  }

}

export {Choice};
export default Choice;
