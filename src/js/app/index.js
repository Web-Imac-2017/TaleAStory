import React from 'react';
import ReactDOM from 'react-dom'
import config from '../config';
import RouteComponent from '../utils/routecomponent';
import {Link} from 'react-router';
import Scroll from '../utils/scroll';
import {RightNavigation} from './wrapper';
import App from './app';
import {User, Guest} from '../model/user';
import {Button, Modal} from 'react-bootstrap';
import _Dialog from 'react-bootstrap-dialog';

class Dialog extends _Dialog {

  constructor(props){
    super(props);
    //this.removeStyle = this.removeStyle.bind(this);
  }

  onEnter(){}

  removeStyle(dom){
      dom.style = "";
  }

  render () {
    let modalBase = super.render();
    return <Modal {...modalBase.props}
                  ref='lol'
                  bsClass="tas-dialog modal"
                  dialogClassName={this.props.className}
                  onEnter={this.removeStyle}>
            {modalBase.props.children}
           </Modal>
  }
}

export default RouteComponent({
  contextTypes : App.childContextTypes,

  guestStart(){
    this.context.setUser(new Guest());
    this.start();
  },

  signin(){
    this.context.router.push(config.path('sign/in'));
  },

  start(){
    this.context.router.push(config.path('game'));
  },

  handleStartBtn(){
    let that = this;
    console.log(this.context);
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
            'button btn-cancel'
          ),
        ],
        bsSize: 'medium',
        onHide: (dialog) => {
          dialog.hide()
          console.log('closed by clicking background.')
        }
      });
    }
    else{
      this.start();
    }
  },
  render(){
    return  <div>
              <div className="columnsContainer">
                <div className="content">
                  <img className="picto element" src={config.imagePath('pictoMountains_large.png')}/>
                  <img className="element" src={config.imagePath('wave_large.png')}/>
                  <h1 className="pageTitle element">Tale A Story</h1>
                  <button className="element button" onClick={this.handleStartBtn}>
                    Commencer l'aventure
                  </button>
                </div>
              </div>
              <Scroll/>
              <Dialog id="yolo" ref='dialog' className='yolo'/>
            </div>
  }
});
