'use strict';

import domready  from 'domready';
import {GlobalBack} from './utils/interfaceback';
import React from 'react';
import ReactDOM from 'react-dom';
import config from './config';
import {Requester} from './utils/interfaceback';
//import TweenMax from './greenshock/TweenMax.js';
//import TweenLite from './greenshock/TweenMax.js';
//import AppRouter from './app/router';
import webGL from './webgl/webgl.js';
import fetch from 'isomorphic-fetch';
import Promise from 'es6-promise';

var bg_anim;

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
            function(items){
                /*console.log(items[0].IDItem);
                console.log(items[0].Name);
                console.log(items[0].ImgPath);
                console.log(items[0].Brief);
                console.log(items[1].IDItem);
                console.log(items[1].Name);
                console.log(items[1].ImgPath);
                console.log(items[1].Brief);*/
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
            function(achievements){
                //console.log(achievements[0].isRead);
         });
		Requester.currentUserUnreadAchievements().then(
            function(json){
                console.log(json);
         });
		Requester.stepCount().then(
            function(json){
                console.log(json);
         });
		Requester.stepList(1,3).then( 
            function(json){
                console.log(json);
         });
		Requester.stepList(1).then(
            function(json){
                console.log(json);
         });
		Requester.currentStepResponse("A").then(
            function(json){
                console.log(json);
         });
		/*Requester.stepAdd("mySuperBody","mySurperAnswer",0,"mySuperTitle").then(
            function(json){
                console.log(json);
         });*/
		Requester.deleteChoice(-1).then(
            function(json){
                console.log(json);
         });
		Requester.deleteStep(3).then(
            function(json){
                console.log(json);
         });
		Requester.stepListByTitle("JeSuisUnTitre").then(
            function(steps){
                /*console.log(steps[0].IDStep);
                console.log(steps[0].ImgPath);
                console.log(steps[0].Body);
                console.log(steps[0].Question);
                console.log(steps[0].IDType);
                console.log(steps[0].Title);*/
         });
		Requester.deleteItem(4).then(
            function(json){
                console.log(json);
         });
		Requester.deleteAchievement(5).then(
            function(json){
                console.log(json);
         });
		Requester.signOut().then(
            function(json){
                console.log(json);
         });
		Requester.deletePlayer(6).then(
            function(json){
                console.log(json);
         });





		/*
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
					*/
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
