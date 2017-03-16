import React from 'react';
import ReactDOM from 'react-dom';
import config from '../config'
import RouteComponent from '../utils/routecomponent';
import Slider from 'rc-slider';
import ItemNav from './itemnav'
import Dialog from '../utils/dialog'
import TransitionGroup from 'react-addons-transition-group';
import {User} from '../model/user';
import {Step} from '../model/step';
import {Requester} from '../utils/interfaceback';


export default RouteComponent({
  contextTypes : {user: React.PropTypes.objectOf(User)},

  getInitialState(){
    let that = this;
    if(this.props.params.id){
      Requester.getChoice(this.props.params.id).then(
        function(choice){
          Requester.getStep(choice.IDStep).then(
            function(step){
              Requester.getStep(choice.IDNextStep).then(
                function(nextStep){
                  that.setState({
                    id : choice.id,
                    step : step,
                    nextstep : nextStep,
                    transitiontext : choice.TransitionText,
                    answer : choice.Answer
                  });
                }
              );
            }
          );

        }
      );
    }
    this.sliders = [1,2,3].map((slider, index) =>
                                  <span key={index}>
                                    {slider}
                                    <Slider />
                                  </span>
                              );
    return {
      id : null,
      step : new Step(0,0,0,0,0,0),
      nextstep : new Step(0,0,0,0,0,0),
      transitiontext : '',
      answer : ''
    };
  },
	handleChange(event) {
    this.setState({
      transitiontext : this.refs.transitiontext.value,
      question : this.refs.question.value,
      answer : this.refs.question.value,
      search : this.refs.search.value,
      selectedItem : null
    });
	},

	handleSubmit(event) {
		event.preventDefault();
    var form = ReactDOM.findDOMNode(this).getElementsByTagName('form')[0];
    let that = this;
    if(!this.state.id){
      fetch(config.path('addchoice'), {
        method: 'POST',
        body: new FormData(form),
        credentials: "same-origin"
      }).then(function(response){
        return response.json()
      }).then(function(json){

        that.context.router.push(config.path('profils/admin/choices/'));
      });
    }
    else{
      fetch(config.path('updatechoice'), {
        method: 'POST',
        body: new FormData(form),
        credentials: "same-origin"
      }).then(function(response){
        return response.json()
      }).then(function(json){

        that.context.router.push(config.path('profils/admin/choices/'));
      });
    }

	},

  handleImage(){
    this.refs.imgpath.click();
  },
  returnSteps(){
    this.context.router.push(config.path('profils/admin/steps'));
  },
  setStartStep(element){
    this.setState({step: element.props.item});
  },
  setEndStep(element){
    this.setState({step: element.props.item});
  },
  loadSteps(offset, count, filter){
    return Requester.stepList(offset, count, filter);
  },
  selectHandler(item){
    this.setState({selectedItem:item});
  },
  cancelDialog(){
    this.setState({selectedItem:null});
  },
  setStart(){
    let that = this;
    this.refs.startDialog.show({
      title: 'Depart',
      body: <ItemNav loadSteps={that.loadSteps} selectHandler={that.setStartStep}/>,
      actions: [
        Dialog.Action(
          'Annuler',
          that.cancelDialog,
          'button btn-cancel'
        ),
        Dialog.Action(
          'OK',
          ()=>{},
          'button btn-confirm'
        ),
      ],
      bsSize: 'large',
      onHide: (dialog) => {
        dialog.hide()
      }
    });
  },

  setEnd(){
    let that = this;
    this.refs.endDialog.show({
      title: 'Arrivée',
      body: <ItemNav loadSteps={that.loadSteps} selectHandler={that.setEndStep}/>,
      actions: [
        Dialog.Action(
          'Annuler',
          that.cancelDialog,
          'button btn-cancel'
        ),
        Dialog.Action(
          'OK',
          ()=>{},
          'button btn-confirm'
        ),
      ],
      bsSize: 'large',
      onHide: (dialog) => {
        dialog.hide()
      }
    });
  },

  render(){
    let unavailable = true;
    if(this.context.user)
      if(this.context.user.isAdmin)
        unavailable = false;

    if(unavailable)
      return <div onClick={this.unselect}>
                <div className="columnsContainer">
                  <div className="content contentProfil contentSteps">
                    <div className="contentRight">
                      unavailable
                    </div>
                  </div>
                </div>
              </div>
		return  <div className="form-screen editing">
    				  <div className="content large">
                <div className="block">
                  <button onClick={this.returnSteps}>&lt;</button>
                </div>
    						<div className="block form">
                  <h1 className="element pageTitle">Choice</h1>
                  <div>
                    <div className="item" onClick={this.setStart}>
                      <h3>Péripétie Départ</h3>
                      {this.state.step ? this.state.step.display() : null}
                    </div>
                    <div className="inputs">
                      <form className="element" onSubmit={this.handleSubmit} method="post" encType="multipart/form-data">
                        <input name="idchoice" type="hidden" value={this.state.id}/>
                        <input name="idstep" type="hidden" value={this.state.step.id}/>
                        <input name="idnextstep" type="hidden" value={this.state.nextstep.id}/>
                        <span><input name="answer" type="text" placeholder="Réponse"
                          value={this.state.answer} onChange={this.handleChange} ref="answer" /></span>
                        <textarea name="transitiontext" type="text" placeholder="Texte de transition" value={this.state.transitiontext}
                          onChange={this.handleChange} ref="transitiontext" />
                        <span className="button" ><input className="submit" type="submit" value="Enregistrer"/></span>
                      </form>
                      <div className="sliders">
                        {this.sliders}
                      </div>
                    </div>
                    <div className="item" onClick={this.setEnd}>
                      <h3>Péripétie Arrivée</h3>
                      {this.state.nextstep ? this.state.nextstep.display() : null}
                    </div>
                  </div>
    						</div>
    					</div>
              <Dialog id="startDialog" ref='startDialog' className="editDialog"/>
              <Dialog id="endDialog" ref='endDialog' className="editDialog"/>
            </div>

    }
});
