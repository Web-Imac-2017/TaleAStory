'use strict';

import domready  from 'domready';
import {GlobalBack} from './utils/interfaceback';
import React from 'react';
import ReactDOM from 'react-dom';
//import TweenMax from './greenshock/TweenMax.js';
//import TweenLite from './greenshock/TweenMax.js';
//import AppRouter from './app/router';
import webGL from './webgl/webgl.js';
import config from './config';
import fetch from 'isomorphic-fetch';
import Promise from 'es6-promise';

var bg_anim;

class Test extends React.Component{
	constructor(props){
		super(props);
		this.state = { text : "" };
	}
	componentDidMount(){
		let that = this;
		fetch(config.path('/step/list?count=2&start=1'), {
		                method: 'post',
		                headers: {
											'Content-Type' : 'application/json'
		                },
										credentials: "same-origin",
										body: JSON.stringify({
											"nameFilter" : "JeNeSuisPasUnTitre",
										})
		              }
				  ).then(
						function(response){
							return response.json();
				  	},
						function(error) {
					  	that.setState({ text : error.message});
						}
					).then(
						function(json){
							let dom = ReactDOM.findDOMNode(that);
							dom.innerHTML = JSON.stringify(json);
							that.setState({ text : JSON.stringify(json)});
							console.log(json);
					});
	}

	shouldComponentUpdate(nextProps, nextState){
			console.log(this, this.state);
			if(this.state.text){
				console.log(this, this.state);
				return true;
			}
			return true;
	}

	render(){
		return <div style={{backgroundColor:"white"}}>
				</div>
	}
}

domready(() => {
		const test = <Test></Test>
		ReactDOM.render(test, document.getElementById('root'));
});
