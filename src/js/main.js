'use strict';

import domready  from 'domready';
import {GlobalBack} from './utils/interfaceback';
import React from 'react';
import ReactDOM from 'react-dom';
import TweenMax from './greenshock/TweenMax.js';
import TweenLite from './greenshock/TweenMax.js';
import Media from 'react-media';
import AppRouter from './app/router';
import webGL from './webgl.js';

var bg_anim;
domready(() => {
		ReactDOM.render(AppRouter, document.getElementById('root'));
		//webGL.runWebGL();
});

class HomePageConnectionScreen extends React.Component{
	render(){
		this.onAction = "";
		return  <div className="screen homePageConnectionScreen orangeScreen">
					<HeaderUnregistered/>
					<div className="content">
						<div className="block">
							<h1>Connexion</h1>
							<form onSubmit={this.onAction}>
								<input type="text" className="formField" placeholder="Login" ref="login" />
								<input type="text" className="formField" placeholder="Password" ref="password" />
								<input className="submit" type="submit" value="Submit"/>
							</form>
							<p>Pas encore de compte ? <a href="">Inscrivez-vous !</a></p>
						</div>
					</div>
				</div>;
	}
}

class HomePageRegisterScreen extends React.Component{
	render(){
		this.onAction = "";
		return  <div className="screen homePageRegisterScreen orangeScreen">
					<HeaderUnregistered/>
					<div className="content">
						<div className="block">
							<h1>Connexion</h1>
							<form onSubmit={this.onAction}>
								<input type="text" className="formField" placeholder="Nom d'utilisateur" ref="username" />
								<input type="text" className="formField" placeholder="Email" ref="email" />
								<input type="text" className="formField" placeholder="Password" ref="password" />
								<input type="text" className="formField" placeholder="Confirmation Password" ref="confirmPassword" />
								<input type="submit" value="Inscription"/>
							</form>
						</div>
					</div>
				</div>;
	}
}

function imagesPath() {
	return 'assets/images/';
}
