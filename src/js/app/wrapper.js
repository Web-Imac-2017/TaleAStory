import React from 'react';
import {Link} from 'react-router'
import config from '../config';
import Header from './header';
import {GlobalBack} from '../utils/interfaceback';
import RouteComponent from '../utils/routecomponent';
import TransitionGroup from 'react-addons-transition-group';

class RightNavigation extends React.Component{
  render(){
		return  <div className="index">
    					<div className="verticalLine"></div>
    					<div className="indexContent">
                <Link to={config.path('home')}>01</Link>
                <Link to={config.path('home/brief1')}>02</Link>
                <Link to={config.path('home/brief2')}>03</Link>
    					</div>
    				</div>
	}
}

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
    let header = this.props.route.noheader ?
                  null :
                  GlobalBack.get('userID') ?
                    <Header name={GlobalBack.get('userName')} surname={GlobalBack.get('userSurname')}
                            imgpath={GlobalBack.get('userImgpath')}/> :
                    <Header/>
                  ;
    /*
    <ul className="header">
      {links}
    </ul>
    */
    return  <div id="wrapper" className={this.props.route.className}>
              {header}

              <div className="caca">
                <TransitionGroup>
                  {children}
                </TransitionGroup>
                <RightNavigation/>
              </div>
            </div>
  }
});

export {RightNavigation};
