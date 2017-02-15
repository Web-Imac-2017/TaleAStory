import React from 'react';
import {Link} from 'react-router'
import config from '../config'

export default React.createClass({
  render : function(){
    return  <div id="wrapper">
              <ul className="header">
                <li><Link to={config.path('')}>Home</Link></li>
                <li><Link to={config.path('connexion')}>Connexion</Link></li>
                <li><Link to={config.path('inscription')}>Inscription</Link></li>
              </ul>
              {this.props.children}
            </div>
  }
});
