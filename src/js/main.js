'use strict';

import domready  from 'domready';
import React from 'react';
import ReactDOM from 'react-dom';
import TweenMax from './greenshock/TweenMax.js';
import TweenLite from './greenshock/TweenMax.js';
import idGen from './utils/idGenerator';

class TimeRender extends React.Component{

	constructor(props){
			super(props);
			this.id = this.props.id ? this.props.id : idGen();
			this.state = {date: new Date(), count: 0};
	}

	componentDidMount(){
		this.timer = setInterval(function(that) {
			that.setState(prevState => ({ date: new Date(), count: prevState.count + 1}));
		}, 1000, this);
		let dom = document.getElementById(this.id);
		TweenLite.to(dom, 2, {x:"542", backgroundColor:"black", borderBottomColor:"#90e500", color:"white"})
	}

	componentWillUnmount(){
		clearInterval(this.timer);
	}

	shouldComponentUpdate(nextProps, nextState) {
		if (this.state.count !== nextState.count) {
      return true;
    }
		return false;
	}

	render(){
		let style = { backgroundColor:"white", color:"black"};
		return  <div id={this.id} style={style}>
					<h1>Hello, world!</h1>
					<h2>It is {this.state.date.toLocaleTimeString()}.</h2>
				</div>;
	}
}

domready(() => {
		//const element = <TimeRender id="lol"/>;
		const element = <PresentationPage/>;
		ReactDOM.render(
			element,
			document.getElementById('root')
		);

});

class HeaderUnregistered extends React.Component{
	render() {
		return (
			<header>
				<h1><a href="">Tale A Story</a></h1>
				<a href="">Inscription</a>
				<a href="">Connexion</a>
			</header>
		);
	}
}

class PresentationPage extends React.Component{
	render(){		
		return  <div>
					<PresentationPageScreen01/>
					<PresentationPageScreen02/>
					<HomePageConnectionScreen/>
					<HomePageRegisterScreen/>
				</div>;
	}
}


class PresentationPageScreen01 extends React.Component{
	render(){		
		return  <div className="screen presentationPageScreen01 blueScreen" >
					<HeaderUnregistered/>
					<div className="content">
						<img className="picto element" src={imagesPath() + 'pictoMountains_large.png'}/>
						<img className="element" src={imagesPath() + 'wave_large.png'}/>
						<h1 className="pageTitle element">Tale A Story</h1>
						<button className="element" type="button">Commencer l'aventure</button>
						<div className="scroll element">
							<p>Scroll</p>
							<img src={imagesPath() + 'scrollArrow_large.png'}/>
						</div>
					</div>
				</div>;
	}
}

class PresentationPageScreen02 extends React.Component{
	render(){		
		return  <div className="screen presentationPageScreen02 orangeScreen" >
					<HeaderUnregistered/>
					<div className="content">
						<div className="sectionTitle">
							<p className="number">02</p>
							<p className="name">Presentation</p>
						</div>
						<div className="sectionContent">
							<img className="picto element" src={imagesPath() + 'pictoMountains_large.png'}/>
							<img className="element" src={imagesPath() + 'wave_large.png'}/>
							<p>Lorem ipsum dolor sit amet consecteur nulla adispisin bacon ipsum jambon fromage poulet rotie j’ai pas internet donc je ne peux pas télécharger du lorem ipsum alors j’écris un petit peu n’importe quoi</p>
						</div>
						<div className="scroll element">
							<p>Scroll</p>
							<img src={imagesPath() + 'scrollArrow_large.png'}/>
						</div>
					</div>
				</div>;
	}
}

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







