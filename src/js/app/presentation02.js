import React from 'react';
import config from '../config';
import RouteComponent from '../utils/routecomponent';
import {Link} from 'react-router';
import Scroll from '../utils/scroll';
import {User, Guest} from '../model/user';
import {RightNavigation} from './wrapper';
import Media from 'react-media';
import {AppContextTypes} from './app'

class Buttons extends React.Component{

  constructor(props){
      super(props);
      this.start = this.start.bind(this);
      this.guestStart = this.guestStart.bind(this);
  }

  guestStart(){
    this.context.setUser(new Guest());
    this.start();
  }

  start(){
    this.context.router.push(config.path('game'));
  }

  render(){
    this.user = this.context.user;
    let buttons;
    let className = "buttons " + this.props.layout;
    if(this.user){
      if(this.user.stepid){
        buttons = <div className={className}>
                    <button onClick={this.start} className="element button">Reprendre le jeu</button>
                    <Link className="element button" to={config.path('account')}>Compte</Link>
                  </div>
      }
      else{
        buttons = <div className={className}>
                      <button onClick={this.start} className="element button">Commencer le jeu</button>
                      <Link className="element button" to={config.path('account')}>Compte</Link>
                  </div>
      }
    }
    else{
      buttons = <div className={className}>
                    <button onClick={this.guestStart} className="element button">Faites le test</button>
                    <Link className="element button" to={config.path('sign/up')}>Inscription</Link>
                </div>
    }
    return buttons;
  }
}

Buttons.contextTypes = AppContextTypes;

export default RouteComponent({
  render(){
    return  <div>
              <div className="columnsContainer">
                <div className="content cols">
  						    <div className="sectionTitle">
    								<div>
    									<p className="number">03</p>
    									<p className="name">L'Aventure</p>
    								</div>
    							</div>
    							<div className="sectionContent rows">
    								<img className="picto element" src={config.imagePath('pictoMountains_large.png')}/>
    								<img className="element" src={config.imagePath('wave_large.png')}/>
    								<p>Lorem ipsum dolor sit amet consecteur nulla adispisin bacon ipsum jambon fromage poulet rotie.
                       Bon alors ici faut pas trop de text pour le responsive, hein, déso.</p>
    								<Media query="(max-width: 599px)">
                        {matches => matches ? (
                          <Buttons layout="rows"/>
                        ) : (
                          <Buttons layout="cols"/>
                        )}
                    </Media>
    							</div>
    						</div>
              </div>
<<<<<<< HEAD
=======
              <Scroll className="disableScroll"/>
>>>>>>> 603d2cd8319c576c0175083a21b6b1ec656eea66
            </div>
  }
});
