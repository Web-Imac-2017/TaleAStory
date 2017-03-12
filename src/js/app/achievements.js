import RouteComponent from '../utils/routecomponent';
import React from 'react'
import {Link} from 'react-router';

export default RouteComponent({
  render(){
    return  <div>
              <div className="columnsContainer">
                <div className="content contentProfil">

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
