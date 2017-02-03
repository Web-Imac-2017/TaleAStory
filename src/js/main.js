'use strict';

import domready  from 'domready';
import React from 'react';
import ReactDOM from 'react-dom';
import TweenLite from './greenshock/TweenLite.js';

class TimeRender extends React.Component{

	constructor(props){
			super(props);
			this.state = {date: new Date(), count: 0};
			this.dom = <div>
						  <h1>Hello, world!</h1>
						  <h2>It is {this.state.date.toLocaleTimeString()}.</h2>
						</div>;
	}

	componentDidMount(){
		this.timer = setInterval(function(that) {
			that.setState(prevState => ({ date: new Date(), count: prevState.count + 1}));
		}, 1000, this);
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
		console.log(this.dom);
		return this.dom;
	}
}
domready(() => {
		const element = <TimeRender />;
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
