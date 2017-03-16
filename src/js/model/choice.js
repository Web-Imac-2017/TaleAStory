import React from 'react';
import ReactDOM from 'react-dom';
import config from '../config';

class Choice{
  constructor(id, Answer, IDStep, TransitionText, IDNextStep){
    this.id = id;
    this.Answer = Answer;
    this.IDStep = IDStep;
    this.TransitionText = TransitionText;
    this.IDNextStep = IDNextStep;
  }

  display() {
    return <div className="insideItem choice no-image">
                <h3 className="userName">{this.Answer}</h3>
              </div>
  }

}

export {Choice};
export default Choice;
