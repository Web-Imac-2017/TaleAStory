import React from 'react';
import config from '../config';
import {currentUser} from '../model/user';
import {User} from '../model/user';
import TransitionGroup from 'react-addons-transition-group';
import Dialog from '../utils/dialog'

let AppInstance = null;

class App extends React.Component{
  constructor(props) {
    super(props);
    AppInstance = this;
    let that = this;
    this.resizeHandlers = [];
    this.state = {
      user: null,
      requestedPage: null,
      dialog : function(options){
        this.refs.dialog.show(options);
      },
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
      },
      addResizeHandler : function(handler){
        that.resizeHandlers.push(handler);
      },
      removeResizeHandler : function(handler){
        let index = that.resizeHandlers.indexOf(5);
        if (index > -1) {
            that.resizeHandlers.splice(index, 1);
        }
      }
    };
  }

  resizeHandle(){
    this.resizeHandlers.forEach(function(callback){
      callback();
    })
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
              <Dialog id="yolo" ref='dialog'/>
            </div>
  }
};

App.contextTypes = {
  router: React.PropTypes.object
}

App.childContextTypes = {
  router: React.PropTypes.object,
  user: React.PropTypes.objectOf(User),
  dialog : React.PropTypes.func,
  setUser: React.PropTypes.func,
  requestPage : React.PropTypes.func,
  goRequestedPage : React.PropTypes.func,
  requestedPage : React.PropTypes.string,
  unsetUser : React.PropTypes.func,
  addResizeHandler : React.PropTypes.func,
  removeResizeHandler : React.PropTypes.func
};

let AppContextTypes = App.childContextTypes;

export {AppContextTypes, AppInstance};
export default App;
