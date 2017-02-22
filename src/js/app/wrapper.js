import React from 'react';
import {Link} from 'react-router'
import config from '../config';
import RouteComponent from '../utils/routecomponent';
import TransitionGroup from 'react-addons-transition-group';

export default RouteComponent({
  render : function(){
    let children = this.props.children ?
                      React.cloneElement(this.props.children, {
                        key: this.props.children.props.route.path
                      }) :
                      this.props.route.indexComponent ?
                        React.createElement(this.props.route.indexComponent, {
                          key: '/'
                        }) :
                        null;
    let links = this.props.route.links ?
                    this.props.route.links.map((link, index) =>
                      <li key={index}>
                        <Link to={config.path(link.path)}>{link.label}</Link>
                      </li>
                    ) : null;
    return  <div id="wrapper">
              <ul className="header">
                {links}
              </ul>
              <TransitionGroup>
                {children}
              </TransitionGroup>
            </div>
  }
});
