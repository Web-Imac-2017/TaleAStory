import React from 'react';
import config from '../config';
import RouteComponent from '../utils/routecomponent';
import {Link} from 'react-router';
import Scroll from '../utils/scroll';
import {RightNavigation} from './wrapper';

export default RouteComponent({
  render(){
    return  <div>
              <div className="columnsContainer">
                <div className="content cols">
    							<div className="sectionTitle">
    								<div>
    									<p className="number">02</p>
    									<p className="name">Presentation</p>
    								</div>
    							</div>
    							<div className="sectionContent">
    								<img className="picto element" src={config.imagePath('pictoMountains_large.png')}/>
    								<img className="element" src={config.imagePath('wave_large.png')}/>
    								<p>Lorem ipsum dolor sit amet consecteur nulla adispisin bacon ipsum jambon fromage poulet rotie j’ai pas internet donc je ne peux pas télécharger du lorem ipsum alors j’écris un petit peu n’importe quoi</p>
    							</div>
    						</div>
              </div>
              <Scroll/>
            </div>
  }
});
