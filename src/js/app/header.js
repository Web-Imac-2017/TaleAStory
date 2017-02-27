import React from 'react'
import {Link} from 'react-router'
import config from '../config';

class Header extends React.Component{

	render() {
		if (this.props.name) {
			return (
				<HeaderRegistered name={this.props.name}
                          surname={this.props.surname}
                          img={this.props.imgpath}/>
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
				<Link to="/"><h1>Tale A Story</h1></Link>
				<div className="links">
					<a href="">Inscription</a>
					<a href="">Connexion</a>
				</div>
			</header>
		);
	}
}


/* <a href="">{this.props.name}</a>
<PresentationPageScreen01 name="John"/>
*/
class HeaderRegistered extends React.Component{
	render() {
		let tmpName = "Marcel Patulacci";
		return (
			<header>
				<Link to={config.path('')}><h1>Tale A Story</h1></Link>
				<div className="links">
					<Link to={config.path('profils/account')}>{this.props.name + ' ' + this.props.surname}</Link>
					<img className="rounded profilPic" src={config.imagePath(this.props.img)}/>
				</div>
			</header>
		);
	}
}

export default Header;
