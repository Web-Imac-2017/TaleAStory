import React from 'react';
import ReactDOM from 'react-dom';
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

export default Dialog;
