import React from 'react';
import ReactDOM from 'react-dom';
import {Link} from 'react-router'
import config from '../config';
import Header from './header';
import User from '../model/user';
import {GlobalBack} from '../utils/interfaceback';
import RouteComponent from '../utils/routecomponent';
import TransitionGroup from 'react-addons-transition-group';

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
    this.transitionGroup.onscroll = this.handleScroll;
  },

  componentWillUnmount : function(){
  },

  shouldComponentUpdate : function(nextProps, nextState, nextContext){
    let childProps = this.props.children.props;
    let _return = this.currentIndex != childProps.route.index || this.user != nextContext.user
                  || this.children.type != nextProps.children.type;
    this.currentIndex = childProps.route.index ? childProps.route.index : 0;

    ReactDOM.findDOMNode(this).getElementsByTagName('span')[0].scrollTop = 5;
    this.scrollTop = 5;
    if(this.scroll){
      setTimeout(function(that) {
        that.scroll = false;
      }, 50, this);
    }
    return _return;
  },

  handleScroll(e) {
    let scrollTop = ReactDOM.findDOMNode(this).getElementsByTagName('span')[0].scrollTop;
    e.preventDefault();
    e.stopPropagation();
    if(!this.scroll && this.scrollTop != scrollTop){
      this.scroll = true;
      this.scrollTop = scrollTop;
      if(scrollTop<=4){
        if(this.previous){
          let path = '';
          let last = this.props.routes.length + (this.children.key != "/" ? -1:-1);
          for(let i=0; i<last;i++){
            path += this.props.routes[i].path + '/';
          }
          path = path + (this.previous.path ? this.previous.path : '');
          path = path.replace('//','/').replace(/\(.*\)/,'');
          this.context.router.push(path);
          return false;
        }
      }
      else if(scrollTop>=6){
        if(this.next){
          let path = '';
          let last = this.props.routes.length + (this.children.key != "/" ? -1:-1);
          for(let i=0; i<last;i++){
            path += this.props.routes[i].path + '/';
          }
          path = path + (this.next.path ? this.next.path : '');
          path = path.replace('//','/').replace(/\(.*\)/,'');
          this.context.router.push(path);
          return false;
        }
      }
      this.scroll = false;
    }
    return false;
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
    return  <div id="wrapper" className={this.props.route.className + " orangeScreen"}>
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

    return  <div id="wrapper" className={this.props.route.className + " orangeScreen"}>
              {this.header}
              <div className="caca">
                <div className="colGauche">
                  <div className="insideCol">
                    <img className="bigProfil" src={config.imagePath('patulacci_large.jpg')}/>
                    <h2 className="userName">Marcel Patullacci</h2>
                    <img className="element" src={config.imagePath('wave_large.png')}/>
                    <ul className="assideMenu">
                      { links }
                      <li><Link to={config.path('')}>Déconnexion</Link></li>
                    </ul>
                    <a href="" className="element button">Jouer</a>
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
