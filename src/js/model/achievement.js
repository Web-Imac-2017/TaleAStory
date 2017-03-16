import React from 'react';
import ReactDOM from 'react-dom';
import config from '../config';

class Achievement{
  constructor(id, Name, ImgPath, Brief){
    this.id = id;
    this.Name = Name;
    this.ImgPath = ImgPath;
    this.Brief = Brief;
  }

  display() {
    return <div className="insideItem achievement no-image">
                <h3 className="userName">{this.Name}</h3>
            </div>
  }

}


export {Achievement};
export default Achievement;
