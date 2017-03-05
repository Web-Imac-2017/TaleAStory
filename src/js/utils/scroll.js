import React from 'react';
import config from '../config'

class Scroll extends React.Component{
	render() {
		return (
			<div className="scroll">
				<p>Scroll</p>
				<img src={config.imagePath('scrollArrow_large.png')}/>
			</div>
		);
	}
}

export default Scroll;
