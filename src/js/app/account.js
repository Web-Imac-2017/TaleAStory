import RouteComponent from '../utils/routecomponent';
import React from 'react'
import {Link} from 'react-router';
import config from '../config';

export default RouteComponent({
  render(){
    return  <div>
              <div className="columnsContainer">
                <div className="content contentProfil">
                  <div className="contentRight">
                  	<div className="insideContent">
                      <p>Email :<span>marcel.patullaci@gmail.com</span></p>
                  		<p>Pseudo :
                        <span>Marcel Patullacci</span>
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
