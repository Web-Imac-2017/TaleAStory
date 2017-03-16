import React from 'react';
import ReactDOM from 'react-dom';
import config from '../config';

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
    return <div className="insideItem step no-image">
                <h3 className="userName">{this.Title}</h3>
              </div>
  }

}

export {Step};
export default Step;
