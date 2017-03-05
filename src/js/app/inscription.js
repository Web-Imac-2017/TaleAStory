import React from 'react';
import RouteComponent from '../utils/routecomponent';

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
		if (this.context.requestedPage == null || this.context.requestedPage == undefined ) {
			this.context.requestedPage = config.path('home');
		}
		this.context.router.push(this.context.requestedPage)
	},

    render(){
		return  <div className="homePageRegisterScreen">
					<div className="content">
						<div className="block">
							<h1 className="element pageTitle">Inscription</h1>
							<form className="element" onSubmit={this.handleSubmit}>
								<span><input name="username" type="text" placeholder="Nom d'utilisateur" value={this.state.username} onChange={this.handleChange} ref="username" /></span>  
								<span><input name="email" type="text" placeholder="Email" value={this.state.email} onChange={this.handleChange} ref="email" /></span>
								<span><input name="password" type="text" placeholder="Password" value={this.state.password} onChange={this.handleChange} ref="password" /></span>
								<span><input name="confirmPassword" type="text" placeholder="Confirmation Password" value={this.state.confirmPassword} onChange={this.handleChange} ref="confirmPassword" /></span>
								<span className="button" ><input className="submit" type="submit" value="Inscription"/></span>
							</form>
						</div>
					</div>
				</div>

    }
});