import {applyRouterMiddleware, Router, IndexRoute, IndexRedirect,
        Route, browserHistory } from 'react-router'
import { updateAnimation } from '../utils/pagetransition'
import React from 'react';
import {Wrapper, AccountWrapper} from './wrapper'
import App from './app'
import config from '../config'
import Index from './index'
import Pres01 from './presentation01'
import Pres02 from './presentation02'
import Connexion from './connexion'
import Inscription from './inscription'
import Account from './account'
import Trophy from './trophy'
import Choices from './choiceslist'
import Achievements from './achievementslist'
import Items from './itemslist'
import Steps from './stepslist'
import Game from './game'
import StepEdit from './stepedit'
import ItemEdit from './itemedit'
import AchievementEdit from './achievementedit'
import ChoiceEdit from './choiceedit'

let AppRouter, routes;

function onUpdate(){
}

function createElement(Component, props) {
  return <Component {...props}/>;
}
AppRouter =
    <Router history={browserHistory} createElement={createElement} onUpdate={onUpdate}>
        <Route path={config.path('')} component={App} onChange={updateAnimation}>
          <IndexRedirect to={config.path('home')} />
          <Route path='home' component={Wrapper} className="screen"
              indexComponent={Index} index={1}
              links={[{'path' :'home', 'label' : 'Accueil'},
                      {'path' :'home/brief1', 'label' : 'Presentation'},
                      {'path' :'home/brief2', 'label' : 'Aventure'}]}>
            <IndexRoute component={Index} index={1}/>
            <Route path='brief1' component={Pres01} index={2}/>
            <Route path='brief2' component={Pres02} index={3}/>
          </Route>
          <Route path='sign' component={Wrapper} index={2} className="screen">
            <IndexRedirect to={config.path('sign/in')} />
            <Route path='in' component={Connexion} index={2}/>
            <Route path='up' component={Inscription} index={3}/>
          </Route>
          <Route path='profils' component={AccountWrapper} index={3} className="screen">
            <IndexRedirect to={config.path('profils/account')} />
            <Route path='account' component={Account} index={1}/>
            <Route path='trophy' component={Trophy} index={2}/>
            <Route path='admin/steps(/:stepid)'component={Steps} index={4}/>,
            <Route path='admin/achievements' component={Achievements} index={5}/>,
            <Route path='admin/items' component={Items} index={6}/>,
            <Route path='admin/choices' component={Choices} index={7}/>
          </Route>
          <Route path='admin' index={1} className="screen">
            <IndexRedirect to={config.path('profils/admin/steps')} />
          </Route>
          <Route path='edit/step(/:id)' component={Wrapper} index={4} className="screen">
            <IndexRoute component={StepEdit} index={1}/>
          </Route>
          <Route path='edit/choice(/:id)' component={Wrapper} index={5} className="screen">
            <IndexRoute component={ChoiceEdit} index={1}/>
          </Route>
          <Route path='edit/achievement(/:id)' component={Wrapper} index={6} className="screen">
            <IndexRoute component={AchievementEdit} index={1}/>
          </Route>
          <Route path='edit/item(/:id)' component={Wrapper} index={7} className="screen">
            <IndexRoute component={ItemEdit} index={1}/>
          </Route>
          <Route path='game' component={Wrapper} index={1} className="screen">
            <IndexRoute component={Game} index={1}/>
          </Route>
        </Route>
    </Router>

export default AppRouter;
