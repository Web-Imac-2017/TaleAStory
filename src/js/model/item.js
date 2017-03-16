import React from 'react';
import ReactDOM from 'react-dom';
import config from '../config';

class Item{
  constructor(id, Name, ImgPath, Brief){
    this.id = id;
    this.Name = Name;
    this.ImgPath = ImgPath;
    this.Brief = Brief;
  }

  display() {
    return <div className="insideItem">
                <h3 className="userName">{this.Name}</h3>
            </div>
  }

}

export {Item};
export default Item;
