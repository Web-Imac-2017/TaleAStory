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

let Wrapper = RouteComponent({

  contextTypes : {user: React.PropTypes.objectOf(User)},
  findNext : function(){
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
  },

  findPrevious : function(){
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
  },

  componentDidMount : function(){
    let childProps = this.props.children.props;
    this.currentIndex = childProps.route.index ? childProps.route.index : 0;
    if(this.props.route.indexRoute)
    this.childs = this.props.route.childRoutes;
    if(this.props.route.indexRoute)
      if(this.childs)
        this.childs = this.childs.concat(this.props.route.indexRoute);
      else
        this.childs = [this.props.route.indexRoute];
    this.findNext();
    this.findPrevious();
    this.transitionGroup = ReactDOM.findDOMNode(this).getElementsByTagName('span')[0];
    this.transitionGroup.onscroll = this.handleScroll;
  },

  componentWillUnmount : function(){
  },

  shouldComponentUpdate : function(nextProps, nextState, nextContext){
    let childProps = this.props.children.props;
    let _return = this.currentIndex != childProps.route.index || this.user != nextContext.user;
    this.currentIndex = childProps.route.index ? childProps.route.index : 0;

    if(this.childs){
      this.findNext();
      this.findPrevious();
    }

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
      if(scrollTop<=3){
        if(this.previous){
          let path = '';
          for(let i=0; i<this.props.routes.length-1;i++)
            path += this.props.routes[i].path + '/';
          path = (path + this.previous.path ? this.previous.path : '');
          path = path.replace('//','/');
          this.context.router.push(path);
          return false;
        }
      }
      else if(scrollTop>=7){
        if(this.next){
          let path = '';
          for(let i=0; i<this.props.routes.length-1;i++)
            path += this.props.routes[i].path + '/';
          path = (path + this.next.path ? this.next.path : '');
          path = path.replace('//','/');
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
                            imgpath={this.user.imgpath}/> :
                    <Header/>
                  ;
    this.children = this.props.children ?
                      React.cloneElement(this.props.children, {
                        key: this.props.children.props.route.path
                      }) :
                      this.props.route.indexComponent ?
                        React.createElement(this.props.route.indexComponent, {
                          key: '/'
                        }) :
                        null;
    return  <div id="wrapper" className={this.props.route.className}>
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
});

export default Wrapper;
export {RightNavigation};
