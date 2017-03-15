import RouteComponent from '../utils/routecomponent';
import React from 'react'
import {User} from '../model/user'
import {Link} from 'react-router';
import config from '../config';

export default RouteComponent({
  contextTypes : {user: React.PropTypes.objectOf(User)},
  render(){
    if(!this.context.user)
      return <div> You are not logged </div>
    return  <div>
              <div className="columnsContainer">
                <div className="content contentProfil">
                  <div className="contentRight">
                  	<div className="insideContent">
                      <p>Email :<span>{this.context.user.mail}</span></p>
                  		<p>Pseudo :
                        <span>{this.context.user.pseudo}</span>
                        <img className="editElement" src={config.imagePath('pen_large.png')}/>
                      </p>
                  		<p>Changer de mot de passe
                        <img className="editElement" src={config.imagePath('pen_large.png')}/>
                      </p>
                  	</div>
                  </div>
                </div>
              </div>
            </div>
  }
});
