import React from 'react';
import config from '../config';
import RouteComponent from '../utils/routecomponent';
import {Link} from 'react-router';
import Scroll from '../utils/scroll';
import {RightNavigation} from './wrapper';

export default RouteComponent({
  name : 'brief1',
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
    								<p>Bonjour et bienvenue sur TaleAStory, premier Site dont vous êtes le héros !
                      Créez un compte et commencez à vivre l'histoire que nous vous avons concocté.
                    </p>
    							</div>
    						</div>
              </div>
              <Scroll next={config.path('home/brief2')}/>
            </div>
  }
});
