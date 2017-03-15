import React from 'react';
import config from '../config'
import {Link} from 'react-router'

class Scroll extends React.Component{
	render() {
		return (
<<<<<<< HEAD
			<div className="scroll">
				<Link to={this.props.next}>Scroll</Link>
=======
			<div className={"scroll " + this.props.className}>
				<p>Scroll</p>
>>>>>>> 603d2cd8319c576c0175083a21b6b1ec656eea66
				<img src={config.imagePath('scrollArrow_large.png')}/>
			</div>
		);
	}
}

export default Scroll;
