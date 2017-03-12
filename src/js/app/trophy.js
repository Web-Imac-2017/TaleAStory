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
                <div className="content contentProfil contentTrophy">
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
                    <div className="trophy one">
                      <div className="insideTrophy">
                        <img className="picto element" src={config.imagePath('pictoMountains_large.png')}/>
                        <img className="element" src={config.imagePath('wave_large.png')}/>
                        <h3 className="userName">Un trophée</h3>
                      </div>
                    </div>
                    <div className="trophy two">
                      <div className="insideTrophy">
                        <img className="picto element" src={config.imagePath('pictoMountains_large.png')}/>
                        <img className="element" src={config.imagePath('wave_large.png')}/>
                        <h3 className="userName">Un trophée</h3>
                      </div>
                    </div>
                    <div className="trophy three">
                      <div className="insideTrophy">
                        <img className="picto element" src={config.imagePath('pictoMountains_large.png')}/>
                        <img className="element" src={config.imagePath('wave_large.png')}/>
                        <h3 className="userName">Un trophée</h3>
                      </div>
                    </div>
                    <div className="trophy four">
                      <div className="insideTrophy">
                        <img className="picto element" src={config.imagePath('pictoMountains_large.png')}/>
                        <img className="element" src={config.imagePath('wave_large.png')}/>
                        <h3 className="userName">Un trophée</h3>
                      </div>
                    </div>
                    <div className="trophy five">
                      <div className="insideTrophy">
                        <img className="picto element" src={config.imagePath('pictoMountains_large.png')}/>
                        <img className="element" src={config.imagePath('wave_large.png')}/>
                        <h3 className="userName">Un trophée</h3>
                      </div>
                    </div>
                    <div className="trophy six">
                      <div className="insideTrophy">
                        <img className="picto element" src={config.imagePath('pictoMountains_large.png')}/>
                        <img className="element" src={config.imagePath('wave_large.png')}/>
                        <h3 className="userName">Un trophée</h3>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
  }
});
