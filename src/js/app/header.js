import React from 'react'
import {Link} from 'react-router'
import config from '../config';
import {AppContextTypes} from './app'

class Header extends React.Component{

	render() {
		if (this.props.pseudo) {
			return (
				<HeaderRegistered pseudo={this.props.pseudo}
                          img={this.props.imgpath}
													adminOpt={this.props.adminOpt}/>
			);
		} else {
			return (
				<HeaderUnregistered/>
			);
		}
	}
}

class HeaderUnregistered extends React.Component{
	render() {
		return (
			<header>
				<Link to={config.path('home')} className="linkHeader"><h1>Tale A Story</h1></Link>
				<div className="links">
					<Link to={config.path('sign/up')} className="linkHeader">Inscription</Link>
					<Link to={config.path('sign/in')} className="linkHeader">Connexion</Link>
				</div>
			</header>
		);
	}
}


/* <a href="">{this.props.name}</a>
<PresentationPageScreen01 name="John"/>
*/
class HeaderRegistered extends React.Component{
	onHover() {
        document.getElementById("profilePopup").style.opacity = 1;
        document.getElementById("profilePopup").style.right = "0px";
    }

	render() {
		return (
			<header>
				<Link to={config.path('home')} className="linkHeader"><h1>Tale A Story</h1></Link>
				<div className="links">
					<Link className="profileLink" to={config.path('profils/account')} onMouseOver={this.onHover} className="linkHeader">{this.props.pseudo}</Link>
					<img className="rounded profilPic" src={config.imagePath(this.props.img)}/>
				</div>
				<ProfileMenu adminOpt={this.props.adminOpt}/>
			</header>
		);
	}
}

class ProfileMenu extends React.Component{

	constructor(props){
		super(props);
		this.disconnect = this.disconnect.bind(this)
	}

	disconnect(e){
		e.preventDefault();
		this.context.router.push(config.path('home'));
		this.context.unsetUser();
	}

	hide() {
        document.getElementById("profilePopup").style.opacity = 0;
        document.getElementById("profilePopup").style.right = "-250px";
    }

	render() {
		let adminCols = null;
		if(this.props.adminOpt){
			adminCols = <div className="cols">
										<img src={config.imagePath('deco.svg')}/>
										<Link to={config.path('admin')} className="linkHeader">Edition</Link>
									</div>
		}
		return (
				<div id="profilePopup" onMouseLeave={this.hide}>
					<div className="profileMenu rows" >
						<div className="cols">
							<img src={config.imagePath('profil.svg')}/>
							<Link to={config.path('profils/account')} className="linkHeader">Mon profil</Link>
						</div>
						<div className="cols">
							<img src={config.imagePath('trophy.svg')}/>
							<Link to={config.path('profils/trophy')} className="linkHeader">Mes Trophées</Link>
						</div>
						{adminCols}
						<div className="cols">
							<img src={config.imagePath('deco.svg')}/>
							<a href="" onClick={this.disconnect} className="linkHeader">Déconnexion</a>
						</div>
					</div>
				</div>
		);
	}
}

ProfileMenu.contextTypes = AppContextTypes;

export default Header;
