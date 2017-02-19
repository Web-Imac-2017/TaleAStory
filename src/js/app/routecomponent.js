import React from 'react'
import ReactDOM from 'react-dom'
import {animationIn, animationOut} from './router.js'

function render(){
  let component = this._render();
  let className = component.props.className ? component.props.className : "";
  return <div {...component.props} className={className + " route-component"}>
          {component.props.children}
        </div>;
}

function animationInDom(callback){
  let dom = ReactDOM.findDOMNode(this);
  animationIn(dom).eventCallback("onComplete", () => setTimeout(callback,500));
}

function animationOutDom(callback){
  let dom = ReactDOM.findDOMNode(this);
  animationOut(dom).eventCallback("onComplete", () => setTimeout(callback,500));
}

function componentWillEnter(callback){
  this._componentWillEnter();
  animationInDom(callback);
}

function componentWillLeave(callback){
  this._componentWillLeave();
  animationOutDom(callback);
}

export default function(spec){
  let classSpec = Object.assign({
  }, spec);

  if(typeof spec.render != "undefined"){
    classSpec._render = spec.render;
    classSpec.render = render;
  }
  else{
    classSpec.render = () => {
      return  <div className="route-component empty">
              </div>
    }
  }
  if(typeof spec.componentWillEnter != "undefined"){
    classSpec._componentWillEnter = spec.componentWillEnter;
    classSpec.componentWillAppear = classSpec.componentWillEnter
                                  = componentWillEnter;
  }
  else{
    classSpec.componentWillAppear = classSpec.componentWillEnter
                                  = animationInDom;
  }
  if(typeof spec.componentWillLeave != "undefined"){
    classSpec._componentWillLeave = spec.componentWillLeave;
    classSpec.componentWillLeave = componentWillLeave;
  }
  else{
    classSpec.componentWillLeave = animationOutDom;
  }
  return React.createClass(classSpec);
};
