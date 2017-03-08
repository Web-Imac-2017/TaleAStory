'use strict';

import domready  from 'domready';
import {GlobalBack} from './utils/interfaceback';
import React from 'react';
import ReactDOM from 'react-dom';
//import TweenMax from './greenshock/TweenMax.js';
//import TweenLite from './greenshock/TweenMax.js';
//import AppRouter from './app/router';
import webGL from './webgl.js';
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
			fetch(config.path('connexion'), {
                        method: 'post',
                        headers: {
                        },
												body: JSON.stringify({
													yolo : "bonjour",
													lol : 5
												})
                      }
          ).then(function(response){
						return response.text();
          }, function(error) {
					  that.setState({ text : error.message});
					}).then(function(texte){
						let dom = ReactDOM.findDOMNode(that);
						dom.innerHTML = texte;
						that.setState({ text : texte});
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
