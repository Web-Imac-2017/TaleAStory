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
		const element = <TimeRender id="lol"/>;
		ReactDOM.render(
			element,
			document.getElementById('root')
		);

});

class Test extends React.Component{
	render(){
		return <div> <h2> this.props.lol </h2></div>
	}
}
