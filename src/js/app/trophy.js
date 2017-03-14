import React from 'react';
import config from '../config';
import RouteComponent from '../utils/routecomponent';

export default RouteComponent({
  render(){
    return  <div>
              <div className="columnsContainer">
                <div className="content contentProfil contentTrophy">
                  <div className="contentRight">
                    <div className="item">
                      <div className="insideItem">
                        <img className="picto element" src={config.imagePath('pictoMountains_large.png')}/>
                        <img className="element" src={config.imagePath('wave_large.png')}/>
                        <h3 className="userName">Un trophée</h3>
                      </div>
                    </div>
                    <div className="item">
                      <div className="insideItem">
                        <img className="picto element" src={config.imagePath('pictoMountains_large.png')}/>
                        <img className="element" src={config.imagePath('wave_large.png')}/>
                        <h3 className="userName">Un trophée</h3>
                      </div>
                    </div>
                    <div className="item">
                      <div className="insideItem">
                        <img className="picto element" src={config.imagePath('pictoMountains_large.png')}/>
                        <img className="element" src={config.imagePath('wave_large.png')}/>
                        <h3 className="userName">Un trophée</h3>
                      </div>
                    </div>
                    <div className="item">
                      <div className="insideItem">
                        <img className="picto element" src={config.imagePath('pictoMountains_large.png')}/>
                        <img className="element" src={config.imagePath('wave_large.png')}/>
                        <h3 className="userName">Un trophée</h3>
                      </div>
                    </div>
                    <div className="item">
                      <div className="insideItem">
                        <img className="picto element" src={config.imagePath('pictoMountains_large.png')}/>
                        <img className="element" src={config.imagePath('wave_large.png')}/>
                        <h3 className="userName">Un trophée</h3>
                      </div>
                    </div>
                    <div className="item">
                      <div className="insideitem">
                        <img className="picto element" src={config.imagePath('pictoMountains_large.png')}/>
                        <img className="element" src={config.imagePath('wave_large.png')}/>
                        <h3 className="userName">Un trophée</h3>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
  }
});
