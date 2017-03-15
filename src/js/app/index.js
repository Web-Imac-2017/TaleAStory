import React from 'react';
import ReactDOM from 'react-dom'
import config from '../config';
import RouteComponent from '../utils/routecomponent';
import {Link} from 'react-router';
import Scroll from '../utils/scroll';
import {AppInstance} from './app'
import {RightNavigation} from './wrapper';
import {AppContextTypes} from './app';
import {User, Guest} from '../model/user';
import Dialog from '../utils/dialog'

export default RouteComponent({
  contextTypes : AppContextTypes,
  componentDidMount(){
    AppInstance.update();
  },
  guestStart(){
    this.context.setUser(new Guest());
    this.start();
  },

  signin(){
    this.context.requestPage(config.path('game'));
    this.context.router.push(config.path('sign/in'));
  },

  start(){
    this.context.router.push(config.path('game'));
  },

  handleStartBtn(){
    let that = this;
    if(this.context.user == null){
      this.refs.dialog.show({
        title: 'Connexion',
        body: 'The game save automatically your progress but if you want load \
              your progress in another device, you must sign in',
        actions: [
          Dialog.Action(
            'No matter!',
            that.guestStart,
            'button btn-cancel'
          ),
          Dialog.Action(
            'Sign in',
            that.signin,
            'button btn-confirm'
          ),
        ],
        bsSize: 'medium',
        onHide: (dialog) => {
          dialog.hide()
        }
      });
    }
    else{
      this.start();
    }
  },
  render(){
    let text = "Commencer l'aventure";
    if(this.context.user){
      if(this.context.user.currentStep){
        text = "Reprendre l'aventure";
      }
    }
    return  <div>
              <div className="columnsContainer">
                <div className="content">
                  <img className="picto element" src={config.imagePath('pictoMountains_large.png')}/>
                  <img className="element wave" src={config.imagePath('wave_large.png')}/>
                  <h1 className="pageTitle element">Tale A Story</h1>
                  <button className="element button" onClick={this.handleStartBtn}>
                    {text}
                  </button>
                </div>
              </div>
              <Scroll next={config.path('home/brief1')}/>
              <Dialog id="yolo" ref='dialog'/>
            </div>
  }
});
