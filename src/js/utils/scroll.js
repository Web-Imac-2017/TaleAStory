import React from 'react';
import config from '../config'
import {Link} from 'react-router'

class Scroll extends React.Component{
	render() {
		return (
			<div className={"scroll " + this.props.className}>
				<Link to={this.props.next}>Scroll</Link>
				<img src={config.imagePath('scrollArrow_large.png')}/>
			</div>
		);
	}
}

export default Scroll;
