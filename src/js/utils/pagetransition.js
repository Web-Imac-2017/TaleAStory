import TweenMax from '../greenshock/TweenMax.js';
import TweenLite from '../greenshock/TweenMax.js';
import ReactDOM from 'react-dom'

let animationOut, animationIn, prevRoute, nextRoute;
let animationDuration  = 1.22;
function _animation(animationFunc){
  return (component, callback) => {
    let dom = ReactDOM.findDOMNode(component);
    let anim = animationFunc(dom);
    anim.eventCallback("onComplete",
                      () => { callback();});
    return anim;
  }
}

function updateAnimation(prev, next, replace, callback){
  animationOut = _animation(outDefault);
  animationIn = _animation(inDefault);
  if(prev != null){
    prevRoute = prev.routes[prev.routes.length -2];
    nextRoute = next.routes[next.routes.length -2];
    if(prevRoute != nextRoute){
      if(prevRoute.index && nextRoute.index){
        if(nextRoute.index > prevRoute.index){
          animationOut = _animation(outLeft);
          animationIn = _animation(inLeft);
        }
        else if(nextRoute.index < prevRoute.index){
          animationOut = _animation(outRight);
          animationIn = _animation(inRight);
        }
      }
    }
    else{
      prevRoute = prev.routes[prev.routes.length -1];
      nextRoute = next.routes[next.routes.length -1];
      if(prevRoute.index && nextRoute.index){
        if(nextRoute.index > prevRoute.index){
          animationOut = _animation(outUp);
          animationIn = _animation(inUp);
        }
        else if(nextRoute.index < prevRoute.index){
          animationOut = _animation(outDown);
          animationIn = _animation(inDown);
        }
      }
    }
  }
  callback();
}

function outUp(dom) {
  return TweenLite.to(dom, animationDuration*0.7,{y:"-100%", opacity:0});
}

function inUp(dom) {
  return TweenLite.fromTo(dom, animationDuration, {y:"100%", opacity:0}, {y:"0%", opacity:1});
}

function outDown(dom) {
  return TweenLite.to(dom, animationDuration*0.7, {y:"100%", opacity:0});
}

function inDown(dom) {
  return TweenLite.fromTo(dom, animationDuration,{y:"-100%", opacity:0},{y:"0%", opacity:1});
}

function outLeft(dom) {
  return TweenLite.to(dom, animationDuration*0.7,{x:"-100%", opacity:0});
}

function inLeft(dom) {
  return TweenLite.fromTo(dom, animationDuration, {x:"100%", opacity:0}, {x:"0%", opacity:1});
}

function outRight(dom) {
  return TweenLite.to(dom, animationDuration*0.7, {x:"100%", opacity:0});
}

function inRight(dom) {
  return TweenLite.fromTo(dom, animationDuration,{x:"-100%", opacity:0},{x:"0%", opacity:1});
}


function outDefault(dom) {
  return TweenLite.to(dom, animationDuration, {opacity:0});
}

function inDefault(dom) {
  return TweenLite.fromTo(dom, animationDuration, {opacity:0}, {opacity:1});
}

animationOut = _animation(outDefault);
animationIn = _animation(inDefault);

export default {animationIn, animationOut};
export {animationIn, animationOut, updateAnimation};
