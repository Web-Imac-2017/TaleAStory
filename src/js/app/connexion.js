import React from 'react';
import RouteComponent from '../utils/routecomponent'
import config from '../config';
import {Link} from 'react-router';

export default RouteComponent({

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
		/*this.context.router.push(config.path('home'));*/
		if (this.context.requestedPage == null || this.context.requestedPage == undefined ) {
			this.context.requestedPage = config.path('home');
		}
		this.context.router.push(this.context.requestedPage)
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