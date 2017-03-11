'use strict';

import domready  from 'domready';
import {GlobalBack} from './utils/interfaceback';
import React from 'react';
import ReactDOM from 'react-dom';
import config from './config';

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
											'Content-Type' : 'application/json'
		                },
										credentials: "same-origin",
										body: JSON.stringify({
											yolo : "bonjour",
											lol : 5
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
		return <p style={{backgroundColor:"white"}}></p>
	}
}

domready(() => {
		const test = <Test></Test>
		ReactDOM.render(test, document.getElementById('root'));
});
