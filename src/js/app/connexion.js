import RouteComponent from '../utils/routecomponent'
import React from 'react';
import config from '../config';
import {Link} from 'react-router';
import User from '../model/user'
import {Requester} from '../utils/interfaceback'
import {AppContextTypes} from './app';

export default RouteComponent({
	contextTypes : AppContextTypes,
	getInitialState() {
		return {
			mail : '',
			pwd : ''
    };
	},

	handleChange(event) {
		let state = {
			mail : this.refs.mail.value,
			pwd : this.refs.pwd.value
		};
		this.setState(state);
	},

	handleSubmit(event) {
		event.preventDefault();
		let that = this;
		Requester.signIn(this.state.mail, this.state.pwd).then(
			function(result){
				if(result.status == "error"){
					Object.keys(result.message).map(function(key, index) {
					    var value = result.message[key];
							let dom = document.getElementById(key+'-error');
							if(value != "ok" && dom){
								dom.classList.remove('empty');
								dom.innerHTML = value;
							}
							else if(dom){
								dom.classList.add('empty');
								dom.innerHTML = '';
							}
					});
				}
				else{
					that.context.setUser(new User(result.id,result.pseudo,result.imgpath,result.isAdmin));
					if (that.context.requestedPage == null || that.context.requestedPage == undefined ) {
						that.context.requestedPage = config.path('home');
					}
					that.context.router.push(that.context.requestedPage)
				}
			}
		);
		return false;
	},

    render(){
		return  <div className="form-screen homePageConnectionScreen">
					<div className="content">
						<div className="block">
							<h1 className="element pageTitle">Connexion</h1>
							<form className="element" onSubmit={this.handleSubmit}>
								<p id="mail-error" className="error empty"></p>
								<span><input name="mail" type="text" placeholder="Login"
															value={this.state.mail} onChange={this.handleChange} ref="mail" /></span>
								<p id="pwd-error" className="error empty"></p>
								<span><input name="pwd" type="password" placeholder="Password"
															value={this.state.pwd} onChange={this.handleChange} ref="pwd" /></span>
								<span className="button" ><input className="submit" type="submit" value="Connexion"/></span>
							</form>
							<p>Pas encore de compte ?<Link className="link linkAnim" to={config.path('sign/up')}>Inscrivez-vous !</Link></p>
						</div>
					</div>
				</div>
    }
});
