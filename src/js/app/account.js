import React from 'react';
import config from '../config';
import RouteComponent from '../utils/routecomponent';
import {Link} from 'react-router';
import Scroll from '../utils/scroll';
import {RightNavigation} from './wrapper';

export default RouteComponent({
  render(){
    return  <div>
              <div className="columnsContainer">
                <div className="content contentProfil">
                  <div className="colGauche">
                  	<div className="insideCol">
                  		<img className="bigProfil" src={config.imagePath('patulacci_large.jpg')}/>
                  		<h2 className="userName">Marcel Patullacci</h2>
                  		<img className="element" src={config.imagePath('wave_large.png')}/>
                  		<ul className="assideMenu">
                  			<li><Link to={config.path('profils/account')}>Mon compte</Link></li>
                  			<li><Link to={config.path('profils/trophy')}>Mes trophées</Link></li>
                  			<li><Link to={config.path('')}>Déconnexion</Link></li>
                  		</ul> 
                  		<a href="" className="element button">Jouer</a>
                  	</div>
                  </div>
                  <div className="contentRight">
                  	<div className="insideContent"> 
                  		<p>Pseudo : 
                        <span>Marcel Patullacci</span>
                        <img className="editElement" src={config.imagePath('pen_large.png')}/>
                      </p>
                  		<p>Email : 
                        <span>marcel.patullaci@gmail.com</span>
                        <img className="editElement" src={config.imagePath('pen_large.png')}/>
                      </p>
                  		<p>Changer de mot de passe
                        <img className="editElement" src={config.imagePath('pen_large.png')}/>
                      </p>
                  		<a href="" className="element button">Sauvegarder le profil</a>
                  	</div>
                  </div>
                </div>
              </div>
            </div>
  }
});
