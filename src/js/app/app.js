import React from 'react';
import config from '../config';
import {currentUser} from '../model/user';
import {User} from '../model/user';
import TransitionGroup from 'react-addons-transition-group';
import Dialog from '../utils/dialog'
import webGL from '../webgl/webgl.js';
import {Requester} from '../utils/interfaceback';

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
      updateUser : function(){
        let response = currentUser();
        response.then(function(user){
          that.setState({'user': user});
        });
      },
      unsetUser : function(){
        Requester.signOut().then(function(result){
        });
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

  update(){
    let user = this.state.user;
    if(user){
      user.updateCurrentStep();
    }
    else{
      user = currentUser();
    }
    this.setState({'user': user});
  }

  componentDidMount(){
    let response = currentUser();
    let that = this;
    response.then(function(user){
      that.setState({'user': user});
    });
	if(webGL.bg_anim != null){
		  var links = document.getElementsByTagName("a");
			var color;
			color = webGL.bg_anim.getColor();
			for(var i=0;i<links.length;i++)
			{
				if(links[i].href && links[i].className=="linkHeader")
				{
					TweenLite.to(links[i], 0.5,{color:"rgb("+Math.floor(255*color[0]+30)+","+Math.floor(255*color[1]+30)+","+Math.floor(255*color[2]+30)+")"});
				}
			}

			links = document.getElementsByClassName("progress-bar");
			for(var i=0;i<links.length;i++)
			{
				TweenLite.to(links[i], 0.5,{backgroundColor:"rgb("+Math.floor(255*color[0]+30)+","+Math.floor(255*color[1]+30)+","+Math.floor(255*color[2]+30)+")"});
			}
	  }
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
  updateUser : React.PropTypes.func,
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
