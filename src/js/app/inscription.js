import React from 'react';
import {animationIn, animationOut} from './router';
export default React.createClass({

  dom : null,

  componentDidMount() {
    animationIn(this.dom);
  },

  render(){
    return  <div className="page" ref={(dom) => { this.dom = dom; }}>
              <div className="mini-title"> TALE A STORY </div>
              <div className="main-title"> INSCRIPTION </div>
            </div>
  }
});
