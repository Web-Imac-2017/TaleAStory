'use strict';

import domready  from 'domready';
import {GlobalBack} from './utils/interfaceback';
import React from 'react';
import ReactDOM from 'react-dom';
import config from './config';
import {Requester} from './utils/interfaceback';

class Test extends React.Component{
	constructor(props){
		super(props);
		this.state = { text : "" };
	}

	componentDidMount(){
		console.log('-------------');
		Requester.testCurrentUser().then(
            function(json){
              console.log("in main");
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
