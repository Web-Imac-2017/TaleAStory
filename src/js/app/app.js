import React from 'react';
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
      requestedPage: null,
      setUser : function(user){
        that.setState({'user': user});
      },
      unsetUser : function(){
        that.setState({'user':null});
      },
      goRequestedPage : function(){
        let requestedPage;
        if (that.state.requestedPage) {
          requestedPage = that.state.requestedPage;
          that.setState({'requestedPage': null});
    		}
        else{
          requestedPage = config.path('home');
        }
    		that.context.router.push(requestedPage)
      },
      requestPage : function(page){
        that.setState({'requestedPage': page})
      }
    };
  }

  componentDidMount(){
    let response = currentUser();
    let that = this;
    response.then(function(user){
      that.setState({'user': user});
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

App.contextTypes = {
  router: React.PropTypes.object
}

App.childContextTypes = {
  router: React.PropTypes.object,
  user: React.PropTypes.objectOf(User),
  setUser: React.PropTypes.func,
  requestPage : React.PropTypes.func,
  goRequestedPage : React.PropTypes.func,
  requestedPage : React.PropTypes.string,
  unsetUser : React.PropTypes.func
};

let AppContextTypes = App.childContextTypes;

export {AppContextTypes};
export default App;
