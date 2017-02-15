'use strict';

import domready  from 'domready';
import React from 'react';
import ReactDOM from 'react-dom';
import TweenMax from './greenshock/TweenMax.js';
import TweenLite from './greenshock/TweenMax.js';
import idGen from './utils/idGenerator';
import AppRouter from './app/router'

class TimeRender extends React.Component{
	constructor(props){
			super(props);
			this.id = this.props.id ? this.props.id : idGen();
			this.state = {date: new Date(), count: 0};
			this.handleClick = this.handleClick.bind(this);
			this.anim = null;
			this.timeText = null;
	}

	handleClick(e){
			if(this.anim != null){
				if(this.anim._reversed)
					this.anim.play();
				else
					this.anim.reverse();
			}
			else {
				this.anim = TweenLite.to(dom, 2, {x:"542", backgroundColor:"black", borderBottomColor:"#90e500", color:"white"});
			}
	}

	componentDidMount(){
		this.timer = setInterval(function(that) {
			that.setState(prevState => ({ date : new Date(), count : prevState.count + 1}));
			if(that.timeText != null)
				that.timeText.innerHTML = 'It is ' + that.state.date.toLocaleTimeString() +'.';
		}, 1000, this);
		let dom = document.getElementById(this.id);
		this.anim = TweenLite.to(dom, 2, {x:"542", backgroundColor:"black", borderBottomColor:"#90e500", color:"white"});
	}

	componentWillUnmount(){
		clearInterval(this.timer);
	}

	shouldComponentUpdate(nextProps, nextState) {
		if (this.state.count !== nextState.count) {
      return false;
    }
		return false;
	}

	render(){
		let style = { backgroundColor:"white", color:"black"};
		return  <div id={this.id} style={style} onClick={this.handleClick}>
							<h1>Hello, world!</h1>
							<h2 ref={domRef => {this.timeText = domRef}}>{'It is ' + that.state.date.toLocaleTimeString() +'.'}</h2>
						</div>;
	}
}
domready(() => {
		//ReactDOM.render(<TimeRender/>, document.getElementById('root'));
		ReactDOM.render(AppRouter, document.getElementById('root'));
});

class Test extends React.Component{
	render(){
		return <div> <h2> this.props.lol </h2></div>
	}
}
