import React from 'react';
import {Link} from 'react-router'
import config from '../config';
import TransitionGroup from 'react-addons-transition-group';

export default ({ children, location }) => (
  <div id="wrapper">
    <ul className="header">
      <li><Link to={config.path('')}>Home</Link></li>
      <li><Link to={config.path('connexion')}>Connexion</Link></li>
      <li><Link to={config.path('inscription')}>Inscription</Link></li>
    </ul>
    <TransitionGroup>
      {React.cloneElement(children, {
        key: location.pathname
      })}
    </TransitionGroup>
  </div>
);
