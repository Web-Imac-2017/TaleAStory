import RouteComponent from '../utils/routecomponent';
import React from 'react'
import {Link} from 'react-router';
import config from '../config';

export default RouteComponent({
  contextTypes : {user: React.PropTypes.objectOf(User)},
  render(){
    return  <div>
              <div className="columnsContainer">
                <div className="content contentProfil">
                  <div className="contentRight">
                  	<div className="insideContent">
                      <p>Email :<span>{this.context.user.mail}</span></p>
                  		<p>Pseudo :
                        <span>this.context.user.pseudo}</span>
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
