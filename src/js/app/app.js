import React from 'react';
import {Link} from 'react-router'
import config from '../config';
import TransitionGroup from 'react-addons-transition-group';

export default class App extends React.Component{

  render(){
    let children = React.cloneElement(this.props.children, {
                      key: this.props.children.props.route.path
                    });
    return  <div id="app">
              <TransitionGroup>
                {children}
              </TransitionGroup>
            </div>
  }
};
