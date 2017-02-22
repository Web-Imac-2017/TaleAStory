import TweenMax from '../greenshock/TweenMax.js';
import TweenLite from '../greenshock/TweenMax.js';

let animationOut, animationIn, prevRoute, nextRoute;
function _animationIn(animationFunc){
  return (component, callback) => {
    let dom = ReactDOM.findDOMNode(component);
    console.log(component.props.route, prevRoute, component.props.route == prevRoute);
    dom.style.position = "absolute";
    animationIn(dom).eventCallback("onComplete",
                                  () => { dom.style.position = "unset"; callback(); });
    animationFunc(dom);
  }
}

function updateAnimation(prev, next, replace, callback){
  animationOut = outDefault;
  animationIn = inDefault;
  if(prev != null){
    prevRoute = prev.routes[prev.routes.length -2];
    nextRoute = next.routes[next.routes.length -2];
    if(prevRoute != nextRoute){
      if(prevRoute.index && nextRoute.index){
        if(nextRoute.index > prevRoute.index){
          animationOut = outLeft;
          animationIn = inLeft;
        }
        else{
          animationOut = outRight;
          animationIn = inRight;
        }
      }
    }
    else{
      prevRoute = prev.routes[prev.routes.length -1];
      nextRoute = next.routes[next.routes.length -1];
      if(prevRoute.index && nextRoute.index){
        if(nextRoute.index > prevRoute.index){
          animationOut = outUp;
          animationIn = inUp;
        }
        else{
          animationOut = outDown;
          animationIn = inDown;
        }
      }
    }
  }
  callback();
}

function outUp(dom) {
  return TweenLite.to(dom, 1.2,{y:"-100%", opacity:0});
}

function inUp(dom) {
  return TweenLite.fromTo(dom, 1.2, {y:"100%", opacity:0}, {y:"0%", opacity:1});
}

function outDown(dom) {
  return TweenLite.to(dom, 1.2, {y:"100%", opacity:0});
}

function inDown(dom) {
  return TweenLite.fromTo(dom, 1.2,{y:"-100%", opacity:0},{y:"0%", opacity:1});
}

function outLeft(dom) {
  return TweenLite.to(dom, 1.2,{x:"-100%", opacity:0});
}

function inLeft(dom) {
  return TweenLite.fromTo(dom, 1.2, {x:"100%", opacity:0}, {x:"0%", opacity:1});
}

function outRight(dom) {
  return TweenLite.to(dom, 1.2, {x:"100%", opacity:0});
}

function inRight(dom) {
  return TweenLite.fromTo(dom, 1.2,{x:"-100%", opacity:0},{x:"0%", opacity:1});
}


function outDefault(dom) {
  return TweenLite.to(dom, 1.2, {opacity:0});
}

function inDefault(dom) {
  return TweenLite.fromTo(dom, 1.2, {opacity:0}, {opacity:1});
}

animationOut = outDefault;
animationIn = inDefault;

export default {animationIn, animationOut};
export {animationIn, animationOut, updateAnimation};
