import React from 'react';
import config from '../config';
import RouteComponent from '../utils/routecomponent';
import {Link} from 'react-router';
import Scroll from '../utils/scroll';
import {RightNavigation} from './wrapper';
import Media from 'react-media';

export default RouteComponent({
  render(){
    return  <div>
              <div className="columnsContainer">
                <div className="content cols">
  						    <div className="sectionTitle">
    								<div>
    									<p className="number">03</p>
    									<p className="name">L'Aventure</p>
    								</div>
    							</div>
    							<div className="sectionContent rows">
    								<img className="picto element" src={config.imagePath('pictoMountains_large.png')}/>
    								<img className="element" src={config.imagePath('wave_large.png')}/>
    								<p>Lorem ipsum dolor sit amet consecteur nulla adispisin bacon ipsum jambon fromage poulet rotie. Bon alors ici faut pas trop de text pour le responsive, hein, déso.</p>
    								<Media query="(max-width: 599px)">
                        {matches => matches ? (
                          <div className="buttons rows">
                              <a href="" className="element button">Faites le test</a>
                              <a href="" className="element button">Inscription</a>
                          </div>
                        ) : (
                        <div className="buttons cols">
                            <a href="" className="element button">Faites le test</a>
                            <a href="" className="element button">Inscription</a>
                        </div>
                        )}
                    </Media>
    							</div>
    						</div>
              </div>
              <Scroll/>
            </div>
  }
});
