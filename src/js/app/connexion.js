import RouteComponent from '../utils/routecomponent'
import React from 'react';
import config from '../config';
import {Link} from 'react-router';
import User from '../model/user'
import {AppContextTypes} from './app';

export default RouteComponent({
	contextTypes : AppContextTypes,
	getInitialState() {
		this.state = [];
	    return {
			null
	    };

	},

	handleChange(event) {
		const target = event.target;
		const name = target.name;
		this.state[name] = target.value;
	},

	handleSubmit(event) {
		/* alert(this.state.login + '-' + this.state.password ); */
		event.preventDefault();
		this.context.setUser(new User(1,this.state.login, 'default_tiny.png'));
		this.context.goRequestedPage();
	},
  render(){
	return  <div className="homePageConnectionScreen">
				<div className="content">
					<div className="block">
						<h1 className="element pageTitle">Connexion</h1>
						<form className="element" onSubmit={this.handleSubmit}>
							<span><input name="login" type="text" placeholder="Login" value={this.state.login} onChange={this.handleChange} ref="login" /></span>
							<span><input name="password" type="text" placeholder="Password" value={this.state.password} onChange={this.handleChange} ref="password" /></span>
							<span className="button" ><input className="submit" type="submit" value="Connexion"/></span>
						</form>
						<p>Pas encore de compte ?<Link className="link linkAnim" to={config.path('sign/up')}>Inscrivez-vous !</Link></p>
					</div>
				</div>
			</div>

  }
});
