import React from 'react'
import ReactDOM from 'react-dom'
import {animationIn, animationOut} from './pagetransition'

function render(){
  let component = this._render();
  let className = component.props.className ? component.props.className : "";
  return <div {...component.props} className={className + " route-component"}>
          {component.props.children}
        </div>;
}

function animationInDom(callback){
  animationIn(this, callback);
}

function animationOutDom(callback){
  animationOut(this, callback);
}

function componentWillEnter(callback){
  this._componentWillEnter();
  animationInDom(callback);
}

function componentWillLeave(callback){
  this._componentWillLeave();
  animationOutDom(callback);
}

function callOnEnter(){
  if(this.props.route)
    if(typeof this.props.route.onEnter == "function")
      this.props.route.onEnter();
}

function componentWillMount(){
  this._componentWillMount();
  callOnEnter();
}

export default function(spec){
  let classSpec = Object.assign({
  }, spec);

  classSpec.contextTypes = Object.assign({
    router : React.PropTypes.object
  }, spec.contextTypes);

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

  if(typeof spec.componentWillMount != "undefined"){
    classSpec._componentWillMount = spec.componentWillMount;
    classSpec.componentWillMount = componentWillMount;
  }
  else{
    classSpec.componentWillMount = callOnEnter;
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
