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
                <div className="antiIndex"></div>
                <div className="content">
                  <img className="picto element" src={config.imagePath('pictoMountains_large.png')}/>
                  <img className="element" src={config.imagePath('wave_large.png')}/>
                  <h1 className="pageTitle element">Tale A Story</h1>
                  <a href="" className="element button">Commencer l'aventure</a>
                </div>
              </div>
              <Scroll/>
            </div>
  }
});
