import React from 'react';
import ReactDOM from 'react-dom';
import {Link} from 'react-router'
import config from '../config';
import Header from './header';
import Dialog from '../utils/dialog'
import {Requester} from '../utils/interfaceback'
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
                        <Link to={config.path(link.path)}>0{index}</Link>
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
    this.scroll = false;
    this.lock = false;
    return {image : ''};
  },

  handleChange : function(){
    editPicture(this.refs.image, this.refs.divImg);
    Requester.updatePlayerImage(this.refs.form);
  },

   handleConfirm : function(){
    let that = this;
    this.setState({image : this.refs.image.value});
    this.refs.dialog.show({
        title: 'Modification de la photo de profil',
        body: 'Voulez-vous actualiser votre photo de profil ?',
        actions: [
          Dialog.Action(
            'Non',
            () => {},
            'button btn-cancel'
          ),
          Dialog.Action(
            'Oui',
            that.handleChange,
            'button btn-confirm'
          ),
        ],
        bsSize: 'medium',
        onHide: (dialog) => {
          dialog.hide()
        }
      });
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
    if(this.context.user){
      if(!this.context.user.isAdmin){
        for(let i=0; i<this.childs.length;){
          if(this.childs[i].admin)
          {
            this.childs.splice(i,1);
          }
          else {
            i++;
          }
        }
      }
    }
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
    if(this.transitionGroup){
      this.scrollListener = new ScrollListener(this.transitionGroup);
      setTimeout(function(that) {
        that.scrollListener.addScrollEndHandler('transition-scroll', that.handleScroll);
      }, 60, this);
    }
  },

  componentWillUnmount : function(){
    if(this.transitionGroup)
      this.transitionGroup.onscroll= null;
  },

  shouldComponentUpdate : function(nextProps, nextState, nextContext){
    let childProps = nextProps.children.props;
    if(this.currentIndex != childProps.route.index){
      this.currentIndex = childProps.route.index ? childProps.route.index : 0;
      setTimeout(function(that) {
        let dom = ReactDOM.findDOMNode(that).getElementsByTagName('span')[0];
        that.manualScroll = true;
        dom.scrollTop = 3;
        setTimeout(function(that){
          that.scroll = false;
        },400,that);
      }, 100, this);
      return true;
    }
    let _return = this.user != nextContext.user ||
                  this.children.type != nextProps.children.type ||
                  this.state.image != nextState.image;
    return true;
  },

  handleScroll(e,p) {
    if(this.manualScroll){
      this.manualScroll = false;
      return;
    }
    let dom = ReactDOM.findDOMNode(this).getElementsByTagName('span')[0];
    if(!this.scroll){
      if(!this.lock){
        this.lock = true;
        let that = this;
        window.requestAnimationFrame(function() {
          that.lock = false;
          let scrollTop = dom.scrollTop;
          let scrollMax = dom.getElementsByClassName('route-component')[0].clientHeight - dom.clientHeight;
          if(scrollTop<=1){
            if(that.previous){
              that.scroll = true;
              let path = '';
              let last = that.props.routes.length + (that.children.key != "/" ? -1:-1);
              for(let i=0; i<last;i++){
                path += that.props.routes[i].path + '/';
              }
              path = path + (that.previous.path ? that.previous.path : '');
              path = path.replace('//','/').replace(/\(.w*\)/,'');
              that.context.router.push(path);
              return;
            }
          }
          else if(scrollTop>=scrollMax-1){
            if(that.next){
              that.scroll = true;
              let path = '';
              let last = that.props.routes.length + (that.children.key != "/" ? -1:-1);
              for(let i=0; i<last;i++){
                path += that.props.routes[i].path + '/';
              }
              path = path + (that.next.path ? that.next.path : '');
              path = path.replace('//','/').replace(/\(.*\)/,'');
              that.context.router.push(path);
              return;
            }
          }
        });
      }
      let scrollTop = dom.scrollTop;
    }
    else {
      e.preventDefault();
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
  disconnect : function(e){
    e.preventDefault();
    this.context.router.push(config.path('home'));
    this.context.unsetUser();
  },
  contextTypes : {user: React.PropTypes.objectOf(User), unsetUser: React.PropTypes.func},
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
    else{
      return <div></div>
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
                    <div onClick={()=>{this.refs.image.click();}} className="roundProfil" ref="divImg">
                      <img className="bigProfil" src={config.imagePath(this.context.user.imgpath)}/>
                    </div>
                    <form ref='form'>
                      <input name="image" type="file" accept='image/*' value={this.state.image}
                                     onChange={this.handleConfirm} ref="image"
                                     multiple={false} style={{display:"none"}}/>
                    </form>
                    <h2 className="userName">{this.context.user.pseudo}</h2>
                    <img className="element" src={config.imagePath('wave_large.png')}/>
                    <ul className="assideMenu">
                      { links }
                      <li><a href="" onClick={this.disconnect}>Déconnexion</a></li>
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
              <Dialog ref='dialog' className='yolo'/>
            </div>
  }
});
let AccountWrapper = RouteComponent(AccountWrapperSpec);
let Wrapper = RouteComponent(WrapperSpec);

export default Wrapper;
export {RightNavigation, Wrapper, AccountWrapper};
