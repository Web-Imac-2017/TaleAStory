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
                  			<li><Link to={config.path('')}>Mon compte</Link></li>
                  			<li><Link to={config.path('profils/trophy')}>Mes trophées</Link></li>
                  			<li><Link to={config.path('')}>Déconnexion</Link></li>
                  		</ul> 
                  		<a href="" className="element button">Jouer</a>
                  	</div>
                  </div>
                  <div className="contentRight">
                  	<div className="insideContent"> 
                  		<p>Prenom : <span>Marcel</span></p>
                  		<p>Nom : <span>Patullacci</span></p>
                  		<p>Email : <span>marcel.patullaci@gmail.com</span></p>
                  		<p>Changer de mot de passe</p>
                  		<p>Changer de photo de profil : </p>
                  		<div className="addPhoto">
                  			<a href="" className="element button"></a>
                  			<a href="" className="element button">Parcourir</a>
                  		</div>
                  		<a href="" className="element button">Sauvegarder le profil</a>
                  	</div>
                  </div>
                </div>
              </div>
            </div>
  }
});
