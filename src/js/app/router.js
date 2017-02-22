import {applyRouterMiddleware, Router, IndexRoute, IndexRedirect,
        Route, browserHistory } from 'react-router'
import { useScroll } from 'react-router-scroll';
import { updateAnimation } from '../utils/pagetransition'
import React from 'react';
import Wrapper from './wrapper'
import App from './app'
import config from '../config'
import Index from './index'
import Connexion from './connexion'
import Inscription from './inscription'

let AppRouter, routes;

function onUpdate(){
    /*this.props.currentIndex = this.state.routes[this.state.routes.length - 1].index;
    if (typeof this.props.currentIndex == "undefined")
      this.props.currentIndex = -1;*/
}

function createElement(Component, props) {
  return <Component {...props}/>;
}

AppRouter =
    <Router history={browserHistory} createElement={createElement} onUpdate={onUpdate}>
        <Route path={config.path('')} component={App} onChange={updateAnimation}>
          <IndexRedirect to={config.path('home')} />
          <Route path='home' component={Wrapper} indexComponent={Index} index={1}
              links={[{'path' :'home', 'label' : 'Home'},
                      {'path' :'home/connexion', 'label' : 'Connexion'},
                      {'path' :'sign/in', 'label' : 'Inscription'}]}>
            <IndexRoute component={Index} index={1}/>
            <Route path='connexion' component={Connexion} index={2}/>
            <Route path='inscription' component={Inscription} index={3}/>
          </Route>
          <Route path='sign' component={Wrapper} index={2}>
            <Route path='in' component={Connexion} index={2}/>
            <Route path='up' component={Inscription} index={3}/>
          </Route>
        </Route>
    </Router>

export default AppRouter;
