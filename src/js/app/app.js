import React from 'react';
import {Link} from 'react-router'
import config from '../config';
import {currentUser} from '../model/user';
import {User} from '../model/user';
import TransitionGroup from 'react-addons-transition-group';

class App extends React.Component{
  constructor(props) {
    super(props);
    let that = this;
    this.state = {
      user: null,
      setUser : function(user){
        that.setState({'user': user});
      }
    };
  }

  componentWillMount(){
    let response = currentUser();
    let that = this;
    response.then(function(user){
      that.setState({'user': user});
      console.log(user);
    });
  }

  getChildContext() {
    return this.state;
  }

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

App.childContextTypes = {
  user: React.PropTypes.objectOf(User),
  setUser: React.PropTypes.func
};

export default App;
