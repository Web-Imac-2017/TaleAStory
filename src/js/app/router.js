import {applyRouterMiddleware, Router, IndexRoute, IndexRedirect,
        Route, browserHistory } from 'react-router'
import { updateAnimation } from '../utils/pagetransition'
import React from 'react';
import Wrapper from './wrapper'
import App from './app'
import config from '../config'
import Index from './index'
import Pres01 from './presentation01'
import Pres02 from './presentation02'
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
          <Route path='home' component={Wrapper} className="screen presentationPageScreen01 blueScreen"
              indexComponent={Index} index={1}
              links={[{'path' :'home', 'label' : 'Home'},
                      {'path' :'home/connexion', 'label' : 'Connexion'},
                      {'path' :'sign/in', 'label' : 'Inscription'}]}>
            <IndexRoute component={Index} index={1}/>
            <Route path='brief1' component={Pres01} index={2}/>
            <Route path='brief2' component={Pres02} index={3}/>
          </Route>
          <Route path='sign' component={Wrapper} index={2} className="screen presentationPageScreen03 orangeScreen">
            <Route path='in' component={Connexion} index={2}/>
            <Route path='up' component={Inscription} index={3}/>
          </Route>
        </Route>
    </Router>

export default AppRouter;
