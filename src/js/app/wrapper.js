import React from 'react';
import ReactDOM from 'react-dom';
import {Link} from 'react-router'
import config from '../config';
import Header from './header';
import User from '../model/user';
import _ScrollListener from 'react-scroll-listener';
import {GlobalBack} from '../utils/interfaceback';
import RouteComponent from '../utils/routecomponent';
import TransitionGroup from 'react-addons-transition-group';
import editPicture from '../utils/ProfilePicture';

class ScrollListener extends _ScrollListener {

    constructor(dom){
        // can pass config object to constructor
        super({
            host	: dom, 	// default host
            delay   : 300 		// default scroll-end timeout
        });
    }
}

class RightNavigation extends React.Component{
  render(){
    let links = this.props.links ?
                    this.props.links.map((link, index) =>
                      <li key={index} data-content={link.label}>
                        <Link to={config.path(link.path)}>0{index+1}</Link>
                      </li>
                    ) : null;
    if(links == null)
      return null;
		return  <div className="index">
    					<div className="verticalLine"></div>
    					<div className="indexContent">
                {links}
    					</div>
    				</div>
	}
}

let WrapperSpec = {

  contextTypes : {user: React.PropTypes.objectOf(User)},

  getInitialState : function(){
    return {profilImg : ''};
  },

  handleChange : function(){
    this.setState({profilImg : this.refs.profilImg.value});
    editPicture(this.refs.profilImg, this.refs.divImg);
  },

  updateChilds : function(){
    let childProps = this.props.children.props;
    this.currentIndex = childProps.route.index ? childProps.route.index : 0;
    if(this.props.route.childRoutes)
      this.childs = this.props.route.childRoutes;
    if(this.props.route.indexRoute)
      if(this.childs)
        this.childs = this.childs.concat(this.props.route.indexRoute);
      else
        this.childs = [this.props.route.indexRoute];
  },
  findNext : function(){
    this.updateChilds();
    this.next = null;
    if(this.childs){
      this.childs.forEach(element =>{
        if(!this.next){
          if(element.index > this.currentIndex)
          {
            this.next = element;
          }
        }
        else{
          if(element.index > this.currentIndex && element.index < this.next.index)
          {
            this.next = element;
          }
        }
      });
    }
  },

  findPrevious : function(){
    this.updateChilds();
    this.previous = null;
    if(this.childs){
      this.childs.forEach(element =>{
        if(!this.previous){
          if(element.index < this.currentIndex)
          {
            this.previous = element;
          }
        }
        else{
          if(element.index < this.currentIndex && element.index > this.previous.index)
          {
            this.previous = element;
          }
        }
      });
    }
  },
  componentDidMount : function(){
    this.transitionGroup = ReactDOM.findDOMNode(this).getElementsByTagName('span')[0];
    this.scrollListener = new ScrollListener(this.transitionGroup);
    setTimeout(function(that) {
      that.scrollListener.addScrollEndHandler('transition-scroll', that.handleScroll);
    }, 60, this);

  },

  componentWillUnmount : function(){
    this.scrollListener.removeScrollEndHandler('transition-scroll', that.handleScroll);
  },

  shouldComponentUpdate : function(nextProps, nextState, nextContext){
    let childProps = nextProps.children.props;
    if(this.currentIndex != childProps.route.index){
      this.currentIndex = childProps.route.index ? childProps.route.index : 0;
      setTimeout(function(that) {
        let dom = ReactDOM.findDOMNode(that).getElementsByTagName('span')[0];
        that.manualScroll = true;
        dom.scrollTop = 2;
        setTimeout(function(that){
          that.scroll = false;
        },500,that);
      }, 200, this);
      return true;
    }
    let _return = this.user != nextContext.user || this.children.type != nextProps.children.type;
    return _return;
  },

  handleScroll(e) {
    if(this.manualScroll){
      this.manualScroll = false;
      return;
    }
    let dom = ReactDOM.findDOMNode(this).getElementsByTagName('span')[0];
    let scrollMax = dom.getElementsByClassName('route-component')[0].clientHeight - dom.clientHeight;
    if(!this.scroll){
      let scrollTop = dom.scrollTop;
      console.log(scrollTop);
      if(scrollTop<=1){
        if(this.previous){
          this.scroll = true;
          let path = '';
          let last = this.props.routes.length + (this.children.key != "/" ? -1:-1);
          for(let i=0; i<last;i++){
            path += this.props.routes[i].path + '/';
          }
          path = path + (this.previous.path ? this.previous.path : '');
          path = path.replace('//','/').replace(/\(.*\)/,'');
          this.context.router.push(path);
          return;
        }
      }
      else if(scrollTop>=scrollMax-1){
        if(this.next){
          this.scroll = true;
          let path = '';
          let last = this.props.routes.length + (this.children.key != "/" ? -1:-1);
          for(let i=0; i<last;i++){
            path += this.props.routes[i].path + '/';
          }
          path = path + (this.next.path ? this.next.path : '');
          path = path.replace('//','/').replace(/\(.*\)/,'');
          this.context.router.push(path);
          return;
        }
      }
      this.scroll = false;
    }
    return;
  },

  render : function(){
    this.user = this.context.user;
    this.header = this.props.route.noheader ?
                  null :
                  this.user != null ?
                    <Header pseudo={this.user.pseudo}
                            imgpath={this.user.imgpath}
                            adminOpt={this.user.isAdmin}/> :
                    <Header/>
                  ;
    this.children = this.props.children ?
                      React.cloneElement(this.props.children, {
                        key: this.props.children.props.route.path ? this.props.children.props.route.path : "/"
                      }) :
                      this.props.route.indexComponent ?
                      React.createElement(this.props.route.indexComponent, {
                        key: '/'
                      }) :
                      null;
    this.findNext();
    this.findPrevious();
    return  <div className={"wrapper "+this.props.route.className}>
              {this.header}
              <div className="caca">
                <div className="scroller">
                  <TransitionGroup>
                    {this.children}
                  </TransitionGroup>
                </div>
                <RightNavigation links={this.props.route.links}/>
              </div>
            </div>
  }
};

let AccountWrapperSpec = Object.assign({}, WrapperSpec, {
  disconnect : function(){
    this.context.unsetUser();
  },
  contextTypes : {user: React.PropTypes.objectOf(User)},
  render : function(){
    this.user = this.context.user;
    this.header = this.props.route.noheader ?
                  null :
                  this.user != null ?
                    <Header pseudo={this.user.pseudo}
                            imgpath={this.user.imgpath}
                            adminOpt={this.user.isAdmin}/> :
                    <Header/>
                  ;
    this.children = this.props.children ?
                      React.cloneElement(this.props.children, {
                        key: this.props.children.props.route.path ? this.props.children.props.route.path : "/"
                      }) :
                      this.props.route.indexComponent ?
                      React.createElement(this.props.route.indexComponent, {
                        key: '/'
                      }) :
                      null;
    this.findNext();
    this.findPrevious();
    let links = [
      {path: 'profils/account', label:'Mon compte'},
      {path: 'profils/trophy', label:'Mes trophées'}
    ];
    if(this.user){
      if(this.user.isAdmin){
        links = links.concat([
          {path: 'profils/admin/steps', label:'Péripéties'},
          {path: 'profils/admin/achievements', label:'Réussites'},
          {path: 'profils/admin/items', label:'Items'},
          {path: 'profils/admin/choices', label:'Choix'}
        ]);
      }
    }

    links = links.map((link, index) =>
      <li key={index}>
        <Link to={config.path(link.path)}>{link.label}</Link>
      </li>
    );

    return  <div id="wrapper" className={this.props.route.className}>
              {this.header}
              <div className="caca mediaBlock">
                <div className="colGauche">
                  <div className="insideCol">
                    <div onClick={()=>{this.refs.profilImg.click();}} className="roundProfil" ref="divImg">
                      <img className="bigProfil" src={config.imagePath('patulacci_large.jpg')}/>
                    </div>
                    <input name="inputImage" type="file" accept='image/*' value={this.state.profilImg}
                                   onChange={this.handleChange} ref="profilImg"
                                   multiple={false} style={{display:"none"}}/>
                    <h2 className="userName">Marcel Patullacci</h2>
                    <img className="element" src={config.imagePath('wave_large.png')}/>
                    <ul className="assideMenu">
                      { links }
                      <li><Link to={config.path('')}>Déconnexion</Link></li>
                    </ul>
                    <Link to={config.path('game')} className="element button">Jouer</Link>
                  </div>
                </div>
                <div className="scroller">
                  <TransitionGroup>
                    {this.children}
                  </TransitionGroup>
                </div>
                <RightNavigation links={this.props.route.links}/>
              </div>
            </div>
  }
});
let AccountWrapper = RouteComponent(AccountWrapperSpec);
let Wrapper = RouteComponent(WrapperSpec);

export default Wrapper;
export {RightNavigation, Wrapper, AccountWrapper};
