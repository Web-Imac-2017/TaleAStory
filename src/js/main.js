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
		Requester.signIn('marcel', 'inconnus').then(
            function(json){
                console.log(json);
         });
		// message d'error OK si mauvais mail, sinon (comme ci-dessous) hard crash
		Requester.signUp('Babar', 'BabarLogin', 'babar@gmail.com', 'BabarPass').then(
            function(json){
                console.log(json);
         });
		Requester.currentUser().then(
            function(json){
                console.log(json);
         });
		Requester.currentUserStats().then(
            function(json){
                console.log(json);
         });
		Requester.makeGuest().then(
            function(json){
                console.log(json);
         });
		Requester.currentUserItems().then(
            function(json){
                console.log(json);
         });
		Requester.currentUserStep().then(
            function(json){
                console.log(json);
         });
		Requester.currentUserStory().then(
            function(json){
                console.log(json);
         });
		Requester.currentUserAchievements().then(
            function(json){
                console.log(json);
         });
		Requester.currentUserUnreadAchievements().then(
            function(json){
                console.log(json);
         });
		Requester.stepCount().then(
            function(json){
                console.log(json);
         });
		// A partir de là, à vérifier. 
		// ==> notamment, voir le type de données attendues, etc
		Requester.stepList(0,0).then( 
            function(json){
                console.log(json);
         });
		Requester.stepList(0).then(
            function(json){
                console.log(json);
         });
		Requester.currentStepResponse(0).then(
            function(json){
                console.log(json);
         });
		Requester.stepAdd(0,0,0).then(
            function(json){
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
