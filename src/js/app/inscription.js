import React from 'react';
import ReactDOM from 'react-dom';
import RouteComponent from '../utils/routecomponent';
import {Requester} from '../utils/interfaceback'
import config from '../config';
import {AppContextTypes} from './app'
import User from '../model/user';

export default RouteComponent({
	contextTypes : AppContextTypes,
	getInitialState() {
    return {
			pseudo : '',
			mail : '',
			pwd : '',
			confirmpwd : ''
    };

	},

	handleChange(event) {
		let state = {
			pseudo : this.refs.pseudo.value,
			mail : this.refs.mail.value,
			pwd : this.refs.pwd.value,
			confirmpwd : this.refs.confirmpwd.value
		};
		this.setState(state);
	},

	handleSubmit(event) {
		event.preventDefault();
		let that = this;
		Requester.signUp(this.state.pseudo, this.state.mail, this.state.pwd, this.state.confirmpwd).then(
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
		return  <div className="form-screen homePageRegisterScreen">
					<div className="content">
						<div className="block">
							<h1 className="element pageTitle">Inscription</h1>
							<form className="element" onSubmit={this.handleSubmit}>
								<p id="mail-error" className="error empty"></p>
								<span><input name="mail" type="text" placeholder="Email"
															value={this.state.mail} onChange={this.handleChange} ref="mail" /></span>
								<p id="pseudo-error" className="error empty"></p>
								<span><input name="pseudo" type="text" placeholder="Pseudo utilisateur"
															value={this.state.pseudo} onChange={this.handleChange} ref="pseudo" /></span>
								<p id="pwd-error" className="error empty"></p>
								<span><input name="pwd" type="password" placeholder="Mot de passe"
															value={this.state.pwd} onChange={this.handleChange} ref="pwd" /></span>
								<p id="mail-confirmpwd" className="error empty"></p>
								<span><input name="confirmpwd" type="password" placeholder="Confirmation mot de passe"
									 						value={this.state.confirmpwd} onChange={this.handleChange} ref="confirmpwd" /></span>
								<span className="button" ><input className="submit" type="submit" value="Inscription"/></span>
							</form>
						</div>
					</div>
				</div>

    }
});
