import {applyRouterMiddleware, Router, IndexRoute, Route, browserHistory } from 'react-router'
import { useScroll } from 'react-router-scroll';
import React from 'react';
import Wrapper from './wrapper'
import config from '../config'
import Index from './index'
import Connexion from './connexion'
import Inscription from './inscription'
import TweenMax from '../greenshock/TweenMax.js';
import TweenLite from '../greenshock/TweenMax.js';

let AppRouter, App, routes, animationOut, animationIn,
    outUp, outDown, inUp, inDown, outDefault, inDefault;

function onChange(prev, next, replace, callback){
  animationOut = outDefault;
  animationIn = inDefault;
  if(prev != null){
    let prevRoute = prev.routes[prev.routes.length -1];
    let nextRoute = next.routes[next.routes.length -1];
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
  callback();
}

function onUpdate(){
    /*this.props.currentIndex = this.state.routes[this.state.routes.length - 1].index;
    if (typeof this.props.currentIndex == "undefined")
      this.props.currentIndex = -1;*/
}

function createElement(Component, props) {
  return <Component {...props}/>;
}

outUp = dom => {
  return TweenLite.to(dom, 1.2,{y:"-100%", opacity:0});
}

inUp = dom => {
  return TweenLite.to(dom, 1.2, {y:"0%", opacity:1});
}

outDown = dom => {
  return TweenLite.to(dom, 1.2, {y:"100%", opacity:0});
}

inDown = dom => {
  return TweenLite.to(dom, 1.2, {y:"0%", opacity:1});
}

outDefault = dom => {
  return TweenLite.to(dom, 1.2, {opacity:0});
}

inDefault = dom => {
  return TweenLite.fromTo(dom, 1.2, {opacity:0}, {opacity:1});
}

animationOut = outDefault;
animationIn = inDefault;

AppRouter =
    <Router history={browserHistory} createElement={createElement} onUpdate={onUpdate}>
      <Route path={config.path('')} component={Wrapper} onChange={onChange}>
        <IndexRoute component={Index} index={1}/>
        <Route path='connexion' component={Connexion} index={2}/>
        <Route path='inscription' component={Inscription} index={3}/>
      </Route>
    </Router>

export default AppRouter;
export {animationIn, animationOut};
